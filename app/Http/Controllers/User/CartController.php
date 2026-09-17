<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|integer|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::with(['product', 'images'])->findOrFail($request->variant_id);

        $quantity = (int) $request->quantity;

        if ($quantity > $variant->stock) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Not enough stock.',
                ],
                422,
            );
        }

        $user = Auth::user();

        // Find existing cart item
        $cart = Cart::where('user_id', $user->id)->where('product_variant_id', $variant->id)->first();

        if ($cart) {
            $newQuantity = $cart->quantity + $quantity;

            if ($newQuantity > $variant->stock) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Not enough stock.',
                    ],
                    422,
                );
            }

            $cart->update([
                'quantity' => $newQuantity,
            ]);
        } else {
            Cart::create([
                'user_id' => $user->id,
                'product_variant_id' => $variant->id,
                'quantity' => $quantity,
            ]);
        }

        // Get updated cart count
        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart.',
            'cartCount' => $cartCount,
        ]);
    }

    public function createCart()
    {
        $user = Auth::user();

        $cart = Cart::with(['variant.product', 'variant.images'])
            ->where('user_id', $user->id)
            ->get();

        $cartCount = $cart->sum('quantity');

        $cartData = $cart->map(function ($item) {
            return [
                'variant_id' => $item->product_variant_id,
                'product_id' => $item->variant->product_id,
                'name' => $item->variant->product->name,
                'capacity' => $item->variant->capacity,
                'color' => $item->variant->color,
                'price' => $item->variant->price,
                'quantity' => $item->quantity,
                'image' => $item->variant->images->first()?->image,
            ];
        });

        return response()->json([
            'success' => true,
            'cart' => $cartData,
            'cartCount' => $cartCount,
        ]);
    }

    public function removeCartItems(int $variantId)
    {
        $user = Auth::user();

        Cart::where('user_id', $user->id)->where('product_variant_id', $variantId)->delete();

        $cartCount = Cart::where('user_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cartCount' => $cartCount,
        ]);
    }
}
