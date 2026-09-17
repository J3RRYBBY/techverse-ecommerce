<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function myOrder()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['items.productVariant.product', 'items.productVariant.images'])
            ->latest()
            ->get();

        return view('user.orders.myOrder', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        // Make sure the user can only see their own order
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load(['items.productVariant.product', 'items.productVariant.images', 'paymentMethod']);

        return view('user.orders.orderDetails', compact('order'));
    }
}
