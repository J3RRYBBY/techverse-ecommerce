<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function createCheckout()
    {
        $paymentMethods = PaymentMethod::where('is_active', true)->get();

        // If user just completed an order,
        // show checkout page so the success modal can appear.
        if (session('orderSuccess')) {
            return view('user.checkout.checkout', [
                'paymentMethods' => $paymentMethods,
                'cart' => collect(),
                'subtotal' => 0,
                'shippingFee' => 0,
                'total' => 0,
            ]);
        }

        $cart = Auth::user()
            ->carts()
            ->with(['variant.product', 'variant.images'])
            ->get();

        if ($cart->isEmpty()) {
            return redirect()->route('user#productList')->with('error', 'Your cart is empty.');
        }

        $subtotal = $cart->sum(function ($item) {
            return $item->variant->price * $item->quantity;
        });

        $shippingFee = 5000;

        $total = $subtotal + $shippingFee;

        return view('user.checkout.checkout', compact('paymentMethods', 'cart', 'subtotal', 'shippingFee', 'total'));
    }

    public function placeOrder(Request $request, CloudinaryService $cloudinary)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:30',
            'address' => 'required|string',
            'city' => 'required|string|max:100',

            'payment_method_id' => ['required', 'exists:payment_methods,id'],

            'payment_receipt' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,avif', 'max:5120'],
        ]);

        // Get current user's cart
        $cart = Auth::user()
            ->carts()
            ->with(['variant.product', 'variant.images'])
            ->get();

        if ($cart->isEmpty()) {
            return redirect()->route('user#productList')->with('error', 'Your cart is empty.');
        }

        // Check stock before creating order
        foreach ($cart as $item) {
            if (!$item->variant) {
                return back()->withInput()->with('error', 'Product variant not found.');
            }

            if ($item->quantity > $item->variant->stock) {
                return back()
                    ->withInput()
                    ->with('error', $item->variant->product->name . ' does not have enough stock.');
            }
        }

        // Calculate totals from database
        $subtotal = $cart->sum(function ($item) {
            return $item->variant->price * $item->quantity;
        });

        $shippingFee = 5000;

        $total = $subtotal + $shippingFee;

        try {
            /*
            |--------------------------------------------------------------------------
            | Upload payment receipt to Cloudinary
            |--------------------------------------------------------------------------
            */

            $receiptUrl = null;
            $receiptPublicId = null;

            if ($request->hasFile('payment_receipt')) {
                $uploaded = $cloudinary->upload($request->file('payment_receipt'), 'techverse/payment-receipts');

                $receiptUrl = $uploaded['url'];
                $receiptPublicId = $uploaded['public_id'];
            }

            $order = DB::transaction(function () use (
                $request,
                $cart,
                $subtotal,
                $shippingFee,
                $total,
                $receiptUrl,
                $receiptPublicId,
            ) {
                /*
                |--------------------------------------------------------------------------
                | Create order
                |--------------------------------------------------------------------------
                */

                $order = Order::create([
                    'user_id' => Auth::id(),

                    'order_number' => 'TECHVERSE-' . strtoupper(Str::random(8)),

                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,

                    'payment_method_id' => $request->payment_method_id,

                    'payment_receipt' => $receiptUrl,
                    'payment_receipt_public_id' => $receiptPublicId,

                    'payment_status' => 'pending',

                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'total' => $total,

                    'status' => 'pending',
                ]);

                /*
                |--------------------------------------------------------------------------
                | Create order items
                |--------------------------------------------------------------------------
                */

                foreach ($cart as $item) {
                    $variant = $item->variant;

                    $firstImage = $variant->images->first();

                    OrderItem::create([
                        'order_id' => $order->id,

                        'product_variant_id' => $variant->id,

                        'product_name' => $variant->product->name,

                        // Already a Cloudinary URL
                        'product_image' => $firstImage?->image,

                        'capacity' => $variant->capacity,

                        'color' => $variant->color,

                        'price' => $variant->price,

                        'quantity' => $item->quantity,

                        'subtotal' => $variant->price * $item->quantity,
                    ]);

                    // Do NOT decrease stock here.
                    //
                    // Stock will be decreased after
                    // admin verifies the payment.
                }

                /*
                |--------------------------------------------------------------------------
                | Clear cart
                |--------------------------------------------------------------------------
                */

                Auth::user()->carts()->delete();

                return $order;
            });

            return redirect()
                ->route('user#productList')
                ->with([
                    'orderSuccess' => true,
                    'orderId' => $order->order_number,
                ]);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Something went wrong while placing your order.');
        }
    }
}
