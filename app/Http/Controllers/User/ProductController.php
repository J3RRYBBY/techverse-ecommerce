<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Comment;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function home()
    {
        // Featured Categories
        $categories = Category::latest()->take(4)->get();

        // Normal/latest products
        $products = Product::with(['variants.images'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->latest()
            ->take(8)
            ->get();

        // Trending products
        // Based on products sold during the last 30 days
        $trendingProductIds = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->whereIn('orders.status', ['confirmed', 'processing', 'shipped', 'delivered'])
            ->select('product_variants.product_id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('product_variants.product_id')
            ->orderByDesc('total_sold')
            ->take(4)
            ->pluck('product_variants.product_id');

        // Get actual trending products
        $trendingProducts = Product::with(['variants.images'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->whereIn('id', $trendingProductIds)
            ->get()
            ->sortBy(function ($product) use ($trendingProductIds) {
                return array_search($product->id, $trendingProductIds->toArray());
            })
            ->values();

        return view('user.home', compact('categories', 'products', 'trendingProducts'));
    }
    public function productList(Request $request)
    {
        $query = Product::with(['variants.images'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings');

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        |
        | Supports both:
        |
        | category=1
        | category[]=1&category[]=2
        |
        */

        $selectedCategories = $request->input('category', []);

        // Convert single category into an array
        if (!is_array($selectedCategories)) {
            $selectedCategories = [$selectedCategories];
        }

        // Remove empty values
        $selectedCategories = array_filter($selectedCategories);

        if (!empty($selectedCategories)) {
            $query->whereIn('category_id', $selectedCategories);
        }

        /*
    |--------------------------------------------------------------------------
    | Price Filter
    |--------------------------------------------------------------------------
    */

        if ($request->filled('min_price') || $request->filled('max_price')) {
            $query->whereHas('variants', function ($variantQuery) use ($request) {
                if ($request->filled('min_price')) {
                    $variantQuery->where('price', '>=', $request->min_price);
                }

                if ($request->filled('max_price')) {
                    $variantQuery->where('price', '<=', $request->max_price);
                }
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Rating Filter
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | rating=4
        |
        | Means 4 stars and above.
        |
        */

        if ($request->filled('rating')) {
            $query->having('ratings_avg_rating', '>=', $request->rating);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Filtered Products
        |--------------------------------------------------------------------------
        */

        $products = $query->get();

        /*
        |--------------------------------------------------------------------------
        | Rating Counts
        |--------------------------------------------------------------------------
        |
        | 1 star = products with average rating >= 1
        | 2 star = products with average rating >= 2
        | etc.
        |
        */

        $ratingProducts = Product::withAvg('ratings', 'rating')->get();

        $ratingCounts = [];

        for ($i = 1; $i <= 5; $i++) {
            $ratingCounts[$i] = $ratingProducts
                ->filter(function ($product) use ($i) {
                    return $product->ratings_avg_rating !== null && $product->ratings_avg_rating >= $i;
                })
                ->count();
        }

        /*
        |--------------------------------------------------------------------------
        | Price Range
        |--------------------------------------------------------------------------
        |
        | Always use ALL products so the slider range
        | doesn't change when filters are applied.
        |
        */

        $allProducts = Product::with('variants')->get();

        $prices = $allProducts->flatMap(fn($product) => $product->variants)->pluck('price')->filter()->values();

        $minPrice = $prices->min() ?? 0;
        $maxPrice = $prices->max() ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::withCount('products')->orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view('user.product', compact('products', 'minPrice', 'maxPrice', 'categories', 'ratingCounts'));
    }
    public function productDetails(int $id)
    {
        // Get current product with variants and variant images
        $product = Product::with(['variants.images'])->findOrFail($id);

        // Get current user's cart quantities
        $cartQuantities = [];

        if (Auth::check()) {
            $cartQuantities = Cart::where('user_id', Auth::id())->pluck('quantity', 'product_variant_id')->toArray();
        }

        // Get product ratings
        $product->load('ratings');

        $averageRating = $product->ratings->avg('rating');
        $ratingCount = $product->ratings->count();

        // Get current user's rating
        $userRating = null;

        if (Auth::check()) {
            $userRating = $product->ratings->where('user_id', Auth::id())->first();
        }

        // Get all ratings with users
        $ratings = Rating::where('product_id', $product->id)->with('user')->latest()->get();

        // Get all comments with users
        $comments = Comment::where('product_id', $product->id)->with('user')->latest()->get();

        /*
        |--------------------------------------------------------------------------
        | Related Products
        |--------------------------------------------------------------------------
        | Get products from the same category.
        | Exclude the product currently being viewed.
        | Limit to 4 products.
        */
        $relatedProducts = Product::with(['variants.images'])
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        return view(
            'user.product.details',
            compact(
                'product',
                'cartQuantities',
                'averageRating',
                'ratingCount',
                'userRating',
                'ratings',
                'comments',
                'relatedProducts',
            ),
        );
    }
}
