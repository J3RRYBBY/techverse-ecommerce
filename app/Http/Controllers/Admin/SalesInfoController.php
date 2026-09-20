<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalesInfoController extends Controller
{
    /**
     * Order statuses that are counted as sales.
     */
    private array $salesStatuses = ['confirmed', 'processing', 'shipped', 'delivered'];

    public function salesInfo(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | DATE FILTER
        |--------------------------------------------------------------------------
        */

        $filter = $request->get('filter', '30');

        $today = Carbon::today();

        switch ($filter) {
            case 'today':
                $fromDate = $today->copy();
                $toDate = $today->copy();
                break;

            case '7':
                $fromDate = $today->copy()->subDays(6);
                $toDate = $today->copy();
                break;

            case '30':
                $fromDate = $today->copy()->subDays(29);
                $toDate = $today->copy();
                break;

            case 'month':
                $fromDate = $today->copy()->startOfMonth();
                $toDate = $today->copy();
                break;

            case 'custom':
                $fromDate = $request->filled('from_date')
                    ? Carbon::parse($request->from_date)
                    : $today->copy()->subDays(29);

                $toDate = $request->filled('to_date') ? Carbon::parse($request->to_date) : $today->copy();

                /*
                |--------------------------------------------------------------------------
                | Prevent reversed dates
                |--------------------------------------------------------------------------
                */

                if ($fromDate->greaterThan($toDate)) {
                    [$fromDate, $toDate] = [$toDate, $fromDate];
                }

                break;

            default:
                $filter = '30';

                $fromDate = $today->copy()->subDays(29);
                $toDate = $today->copy();
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | BASE SALES QUERY
        |--------------------------------------------------------------------------
        */

        $salesQuery = Order::whereIn('status', $this->salesStatuses)->whereBetween('created_at', [
            $fromDate->copy()->startOfDay(),
            $toDate->copy()->endOfDay(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | TOTAL SALES
        |--------------------------------------------------------------------------
        */

        $totalSales = (clone $salesQuery)->sum('total');

        /*
        |--------------------------------------------------------------------------
        | TOTAL ORDERS
        |--------------------------------------------------------------------------
        */

        $totalOrders = (clone $salesQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL ITEMS SOLD
        |--------------------------------------------------------------------------
        */

        $orderIds = (clone $salesQuery)->pluck('id');

        $totalItemsSold = OrderItem::whereIn('order_id', $orderIds)->sum('quantity');

        /*
        |--------------------------------------------------------------------------
        | TODAY'S SALES
        |--------------------------------------------------------------------------
        */

        $todaySales = Order::whereIn('status', $this->salesStatuses)->whereDate('created_at', $today)->sum('total');

        /*
        |--------------------------------------------------------------------------
        | THIS MONTH'S SALES
        |--------------------------------------------------------------------------
        */

        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();

        $monthSales = Order::whereIn('status', $this->salesStatuses)
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum('total');

        /*
        |--------------------------------------------------------------------------
        | SALES CHART
        |--------------------------------------------------------------------------
        |
        | The chart always displays the selected period.
        | For very large custom periods, the chart can become crowded,
        | so we group by day.
        |
        */

        $salesChart = [];

        $chartDate = $fromDate->copy()->startOfDay();
        $chartEnd = $toDate->copy()->startOfDay();

        while ($chartDate->lessThanOrEqualTo($chartEnd)) {
            $daySales = Order::whereIn('status', $this->salesStatuses)
                ->whereDate('created_at', $chartDate)
                ->sum('total');

            $salesChart[] = [
                'date' => $chartDate->format('M d'),
                'day' => $chartDate->format('D'),
                'sales' => (float) $daySales,
            ];

            $chartDate->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | MAX CHART VALUE
        |--------------------------------------------------------------------------
        */

        $maxSales = collect($salesChart)->max('sales');

        if ($maxSales <= 0) {
            $maxSales = 1;
        }

        /*
        |--------------------------------------------------------------------------
        | SALES ORDERS
        |--------------------------------------------------------------------------
        */

        $salesOrders = (clone $salesQuery)->with('user')->latest()->paginate(10)->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTS
        |--------------------------------------------------------------------------
        */

        $topProducts = OrderItem::whereIn('order_id', $orderIds)
            ->select('product_name')
            ->selectRaw('SUM(quantity) as total_quantity')
            ->selectRaw('SUM(subtotal) as total_sales')
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->take(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SALES BY CATEGORY
        |--------------------------------------------------------------------------
        |
        | We use product_variant -> product -> category.
        |
        */

        $categorySales = OrderItem::with(['productVariant.product.category'])
            ->whereIn('order_id', $orderIds)
            ->get()
            ->groupBy(function ($item) {
                return $item->productVariant?->product?->category?->name ?? 'Uncategorized';
            })
            ->map(function ($items, $category) {
                return [
                    'category' => $category,
                    'quantity' => $items->sum('quantity'),
                    'sales' => $items->sum('subtotal'),
                ];
            })
            ->sortByDesc('sales')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | TOTAL CATEGORY SALES
        |--------------------------------------------------------------------------
        */

        $categoryTotalSales = $categorySales->sum('sales');

        /*
        |--------------------------------------------------------------------------
        | DASHBOARD DATA
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.salesInfo',
            compact(
                'filter',

                'fromDate',
                'toDate',

                'totalSales',
                'totalOrders',
                'totalItemsSold',

                'todaySales',
                'monthSales',

                'salesChart',
                'maxSales',

                'salesOrders',

                'topProducts',

                'categorySales',
                'categoryTotalSales',
            ),
        );
    }
}
