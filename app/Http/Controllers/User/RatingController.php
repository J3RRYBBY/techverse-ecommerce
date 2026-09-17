<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function feedback(Request $request, Product $product)
    {
        $request->validate(
            [
                'rating' => 'required|integer|min:1|max:5',
                'message' => 'nullable|string|max:1000',
            ],
            [
                'rating.required' => 'Please select a rating for review.',
            ],
        );

        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ],
            [
                'rating' => $request->rating,
            ],
        );

        if ($request->filled('message')) {
            Comment::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'message' => $request->message,
            ]);
        }

        return back()->with('success', 'Feedback submitted successfully!');
    }
}
