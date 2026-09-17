<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderManagementController extends Controller
{
    public function orderBoard(Request $request)
    {
        $query = Order::with(['user', 'paymentMethod', 'items'])->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Payment status filter
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Order status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Specific order date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Total orders
        $totalOrders = Order::count();

        // Status counts
        $counts = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        $orders = $query->paginate(10)->withQueryString();

        return view('admin.orderManagement.orderBoard', compact('orders', 'totalOrders', 'counts'));
    }

    // Order Details
    public function orderBoardDetails(Order $order)
    {
        $order->load(['user', 'paymentMethod', 'items']);

        return view('admin.orderManagement.orderBoardDetails', compact('order'));
    }

    // Update Order Status
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $newStatus = $request->status;
        $oldStatus = $order->status;

        /*
        |--------------------------------------------------------------------------
        | Don't update if status is already the same
        |--------------------------------------------------------------------------
        */

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Order status is already ' . ucfirst($newStatus) . '.');
        }

        /*
        |--------------------------------------------------------------------------
        | Delivered / Cancelled orders cannot be changed
        |--------------------------------------------------------------------------
        */

        if (in_array($oldStatus, ['delivered', 'cancelled'])) {
            return back()->with('error', 'This order can no longer be changed.');
        }

        /*
        |--------------------------------------------------------------------------
        | Payment must be verified before confirming the order
        |--------------------------------------------------------------------------
        */

        if ($newStatus === 'confirmed' && $order->payment_status !== 'verified') {
            return back()->with('error', 'Payment must be verified before confirming this order.');
        }

        /*
        |--------------------------------------------------------------------------
        | Do not allow processing/shipped/delivered without payment verification
        |--------------------------------------------------------------------------
        */

        if (in_array($newStatus, ['processing', 'shipped', 'delivered']) && $order->payment_status !== 'verified') {
            return back()->with('error', 'Payment must be verified before moving this order forward.');
        }

        /*
        |--------------------------------------------------------------------------
        | Cancellation
        |--------------------------------------------------------------------------
        |
        | If stock was already deducted, restore it.
        |
        | Stock is considered deducted when the order was:
        |
        | confirmed
        | processing
        | shipped
        |
        */

        if ($newStatus === 'cancelled' && in_array($oldStatus, ['confirmed', 'processing', 'shipped'])) {
            try {
                DB::transaction(function () use ($order) {
                    $order->load('items');

                    foreach ($order->items as $item) {
                        $variant = ProductVariant::where('id', $item->product_variant_id)->lockForUpdate()->first();

                        if (!$variant) {
                            throw new \Exception("Product variant #{$item->product_variant_id} was not found.");
                        }

                        /*
                        |--------------------------------------------------------------------------
                        | Restore stock
                        |--------------------------------------------------------------------------
                        */

                        $variant->increment('stock', $item->quantity);
                    }

                    $order->update([
                        'status' => 'cancelled',
                    ]);
                });

                return back()->with('success', 'Order cancelled and stock restored successfully.');
            } catch (\Throwable $e) {
                return back()->with('error', 'Unable to cancel the order: ' . $e->getMessage());
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Normal status update
        |--------------------------------------------------------------------------
        */

        $order->update([
            'status' => $newStatus,
        ]);

        return back()->with('success', 'Order status updated to ' . ucfirst($newStatus) . '.');
    }

    /*
    |--------------------------------------------------------------------------
    | Verify Payment
    |--------------------------------------------------------------------------
    |
    | When payment is verified:
    |
    | 1. payment_status = verified
    | 2. order status = confirmed
    | 3. stock is decreased
    |
    */

    public function verifyPayment(Order $order)
    {
        if ($order->payment_status === 'verified') {
            return back()->with('error', 'Payment has already been verified.');
        }

        if ($order->payment_status === 'rejected') {
            return back()->with('error', 'This payment has already been rejected.');
        }

        if (!$order->payment_receipt) {
            return back()->with('error', 'No payment receipt has been uploaded.');
        }

        try {
            DB::transaction(function () use ($order) {
                $order->load('items');

                /*
            |--------------------------------------------------------------------------
            | Check ALL stock first
            |--------------------------------------------------------------------------
            */

                foreach ($order->items as $item) {
                    $variant = ProductVariant::where('id', $item->product_variant_id)->lockForUpdate()->first();

                    if (!$variant) {
                        throw new \Exception("Product variant for {$item->product_name} no longer exists.");
                    }

                    if ($variant->stock < $item->quantity) {
                        throw new \Exception(
                            "Not enough stock for {$item->product_name}. " .
                                "Available: {$variant->stock}, " .
                                "Required: {$item->quantity}.",
                        );
                    }
                }

                /*
            |--------------------------------------------------------------------------
            | Deduct stock
            |--------------------------------------------------------------------------
            */

                foreach ($order->items as $item) {
                    $variant = ProductVariant::where('id', $item->product_variant_id)->lockForUpdate()->first();

                    $variant->decrement('stock', $item->quantity);
                }

                /*
            |--------------------------------------------------------------------------
            | Update payment + order
            |--------------------------------------------------------------------------
            */

                $order->update([
                    'payment_status' => 'verified',
                    'status' => 'confirmed',
                ]);
            });

            return back()->with('success', 'Payment verified, order confirmed, and stock updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // Reject Payment
    public function rejectPayment(Order $order)
    {
        /*
        |--------------------------------------------------------------------------
        | Don't reject an already verified payment
        |--------------------------------------------------------------------------
        */

        if ($order->payment_status === 'verified') {
            return back()->with('error', 'This payment has already been verified and cannot be rejected.');
        }

        if ($order->payment_status === 'rejected') {
            return back()->with('error', 'This payment has already been rejected.');
        }

        $order->update([
            'payment_status' => 'rejected',
            'status' => 'cancelled',
        ]);

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Stock is NOT restored here because payment was never verified,
        | therefore stock was never deducted.
        |
        */

        return back()->with('success', 'Payment rejected and order cancelled.');
    }
}
