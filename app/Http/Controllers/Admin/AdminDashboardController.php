<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function dashboard()
    {
        /*
        |--------------------------------------------------------------------------
        | BASIC COUNTS
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $totalCustomers = User::where('role', 'user')->count();

        $totalOrders = Order::count();

        /*
        |--------------------------------------------------------------------------
        | SALES
        |--------------------------------------------------------------------------
        |
        | Cancelled orders are not counted as sales.
        |
        */

        $salesStatuses = ['confirmed', 'processing', 'shipped', 'delivered'];

        $totalSales = Order::whereIn('status', $salesStatuses)->sum('total');

        /*
        |--------------------------------------------------------------------------
        | ORDER STATUS COUNTS
        |--------------------------------------------------------------------------
        */

        $pendingOrders = Order::where('status', 'pending')->count();

        $processingOrders = Order::whereIn('status', ['confirmed', 'processing', 'shipped'])->count();

        $completedOrders = Order::where('status', 'delivered')->count();

        $cancelledOrders = Order::where('status', 'cancelled')->count();

        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        $pendingPayments = Order::where('payment_status', 'pending')->count();

        $verifiedPayments = Order::where('payment_status', 'verified')->count();

        $rejectedPayments = Order::where('payment_status', 'rejected')->count();

        /*
        |--------------------------------------------------------------------------
        | RECENT ORDERS
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::with('user')->latest()->take(7)->get();

        /*
        |--------------------------------------------------------------------------
        | LOW STOCK PRODUCTS
        |--------------------------------------------------------------------------
        |
        | Change 5 if you want another low-stock threshold.
        |
        */

        $lowStockVariants = ProductVariant::with('product')
            ->where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->take(6)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | OUT OF STOCK
        |--------------------------------------------------------------------------
        */

        $outOfStockVariants = ProductVariant::with('product')->where('stock', 0)->count();

        /*
        |--------------------------------------------------------------------------
        | SALES FOR LAST 7 DAYS
        |--------------------------------------------------------------------------
        */

        $salesChart = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);

            $sales = Order::whereIn('status', $salesStatuses)->whereDate('created_at', $date)->sum('total');

            $salesChart[] = [
                'date' => $date->format('D'),
                'full_date' => $date->format('M d'),
                'sales' => (float) $sales,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | MAX SALES FOR CHART
        |--------------------------------------------------------------------------
        */

        $maxSales = collect($salesChart)->max('sales');

        if ($maxSales <= 0) {
            $maxSales = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | TOP SELLING PRODUCTS
        |--------------------------------------------------------------------------
        |
        | Uses order_items because the product information is stored
        | as a snapshot in the order item.
        |
        */

        $topProducts = OrderItem::select('product_name')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_sales')
            ->whereHas('order', function ($query) use ($salesStatuses) {
                $query->whereIn('status', $salesStatuses);
            })
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard',
            compact(
                'totalProducts',
                'totalCustomers',
                'totalOrders',
                'totalSales',

                'pendingOrders',
                'processingOrders',
                'completedOrders',
                'cancelledOrders',

                'pendingPayments',
                'verifiedPayments',
                'rejectedPayments',

                'recentOrders',

                'lowStockVariants',
                'outOfStockVariants',

                'salesChart',
                'maxSales',

                'topProducts',
            ),
        );
    }
}
