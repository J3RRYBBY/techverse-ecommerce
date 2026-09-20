@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        {{-- ========================================================= --}}
        {{-- HEADER --}}
        {{-- ========================================================= --}}

        <div class="flex flex-col gap-3 mb-8 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-medium text-gray-900 sm:text-3xl">Sales & Reports</h1>

                <p class="text-sm text-gray-500">Track your store's sales performance and revenue.</p>
            </div>

            <div class="text-sm text-gray-500">{{ now()->format('F d, Y') }}</div>
        </div>

        {{-- ========================================================= --}}
        {{-- DATE FILTER --}}
        {{-- ========================================================= --}}

        <div class="p-5 mb-6 bg-white border border-gray-200 rounded-xl">
            <div class="flex flex-col gap-5">
                <div>
                    <h2 class="font-medium text-gray-900">Sales Period</h2>

                    <p class="text-sm text-gray-500">Select a period to view sales information.</p>
                </div>

                {{-- QUICK FILTERS --}}

                <div class="flex flex-wrap gap-2">
                    <a
                        href="{{ route('admin#salesInfo', ['filter' => 'today']) }}"
                        class="px-4 py-2 text-sm border rounded-lg transition
                        {{ $filter === 'today'
                            ? 'bg-gray-900 text-white border-gray-900'
                            : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}"
                    >
                        Today
                    </a>

                    <a
                        href="{{ route('admin#salesInfo', ['filter' => '7']) }}"
                        class="px-4 py-2 text-sm border rounded-lg transition
                        {{ $filter === '7'
                            ? 'bg-gray-900 text-white border-gray-900'
                            : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}"
                    >
                        Last 7 Days
                    </a>

                    <a
                        href="{{ route('admin#salesInfo', ['filter' => '30']) }}"
                        class="px-4 py-2 text-sm border rounded-lg transition
                        {{ $filter === '30'
                            ? 'bg-gray-900 text-white border-gray-900'
                            : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}"
                    >
                        Last 30 Days
                    </a>

                    <a
                        href="{{ route('admin#salesInfo', ['filter' => 'month']) }}"
                        class="px-4 py-2 text-sm border rounded-lg transition
                        {{ $filter === 'month'
                            ? 'bg-gray-900 text-white border-gray-900'
                            : 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50' }}"
                    >
                        This Month
                    </a>
                </div>

                {{-- CUSTOM DATE --}}

                <form
                    action="{{ route('admin#salesInfo') }}"
                    method="GET"
                    class="grid grid-cols-1 gap-3 pt-5 border-t border-gray-100 sm:grid-cols-[1fr_1fr_auto]"
                >
                    <input type="hidden" name="filter" value="custom" />

                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-500"> From </label>

                        <input
                            type="date"
                            name="from_date"
                            value="{{ $filter === 'custom' ? $fromDate->format('Y-m-d') : '' }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                        />
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-medium text-gray-500"> To </label>

                        <input
                            type="date"
                            name="to_date"
                            value="{{ $filter === 'custom' ? $toDate->format('Y-m-d') : '' }}"
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                        />
                    </div>

                    <div class="flex items-end">
                        <button
                            type="submit"
                            class="w-full px-5 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg hover:bg-gray-800 sm:w-auto"
                        >
                            Apply
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- CURRENT PERIOD --}}
        {{-- ========================================================= --}}

        <div class="mb-6">
            <p class="text-sm text-gray-500">
                Showing sales from

                <span class="font-medium text-gray-900"> {{ $fromDate->format('M d, Y') }} </span>

                to

                <span class="font-medium text-gray-900"> {{ $toDate->format('M d, Y') }} </span>
            </p>
        </div>

        {{-- ========================================================= --}}
        {{-- SUMMARY CARDS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- TOTAL SALES --}}

            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Sales</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">
                            {{ number_format($totalSales) }}

                            <span class="text-sm font-normal text-gray-500"> MMK </span>
                        </p>
                    </div>

                    <div class="flex items-center justify-center rounded-lg w-11 h-11 text-lime-600 bg-lime-100">
                        <i class="fa-solid fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

            {{-- TODAY --}}

            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Today's Sales</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">
                            {{ number_format($todaySales) }}

                            <span class="text-sm font-normal text-gray-500"> MMK </span>
                        </p>
                    </div>

                    <div class="flex items-center justify-center text-blue-600 bg-blue-100 rounded-lg w-11 h-11">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                </div>
            </div>

            {{-- MONTH --}}

            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">This Month</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">
                            {{ number_format($monthSales) }}

                            <span class="text-sm font-normal text-gray-500"> MMK </span>
                        </p>
                    </div>

                    <div class="flex items-center justify-center text-purple-600 bg-purple-100 rounded-lg w-11 h-11">
                        <i class="fa-solid fa-calendar"></i>
                    </div>
                </div>
            </div>

            {{-- ITEMS SOLD --}}

            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Items Sold</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">{{ number_format($totalItemsSold) }}</p>
                    </div>

                    <div class="flex items-center justify-center text-orange-600 bg-orange-100 rounded-lg w-11 h-11">
                        <i class="fa-solid fa-box"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- SALES CHART --}}
        {{-- ========================================================= --}}

        <div class="p-5 mt-6 bg-white border border-gray-200 rounded-xl">
            <div class="flex flex-col gap-2 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Sales Overview</h2>

                    <p class="text-sm text-gray-500">Daily sales during the selected period.</p>
                </div>

                <div class="text-sm text-gray-500">{{ number_format($totalSales) }} MMK</div>
            </div>

            {{-- CHART --}}

            @if (count($salesChart))
                <div class="overflow-x-auto">
                    <div class="flex items-end gap-3 h-72 min-w-[650px] px-2">
                        @foreach ($salesChart as $item)
                            @php
                                $height = ($item['sales'] / $maxSales) * 100;

                                if ($item['sales'] > 0 && $height < 5) {
                                    $height = 5;
                                }
                            @endphp

                            <div class="flex flex-col items-center justify-end flex-1 h-full min-w-[35px] group">
                                {{-- BAR AREA --}}

                                <div class="relative flex items-end justify-center w-full h-full">
                                    {{-- TOOLTIP --}}

                                    <div
                                        class="absolute z-20 hidden px-3 py-2 mb-2 text-xs text-white bg-gray-900 rounded-lg shadow-lg bottom-full group-hover:block whitespace-nowrap"
                                    >
                                        <p>{{ $item['date'] }}</p>

                                        <p class="mt-1 font-medium">{{ number_format($item['sales']) }} MMK</p>
                                    </div>

                                    {{-- BAR --}}

                                    <div
                                        class="w-full max-w-[45px] bg-lime-400 rounded-t-md transition-all duration-200 group-hover:bg-lime-500"
                                        style="height: {{ $height }}%;"
                                    ></div>
                                </div>

                                {{-- DATE --}}

                                <div class="mt-3 text-center">
                                    <p class="text-[10px] text-gray-400 sm:text-xs">{{ $item['day'] }}</p>

                                    <p class="text-[10px] text-gray-500 sm:text-xs">{{ $item['date'] }}</p>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>

            @else
                <div class="flex flex-col items-center justify-center py-16">
                    <i class="mb-3 text-3xl text-gray-300 fa-solid fa-chart-column"></i>

                    <p class="text-sm text-gray-500">No sales data available.</p>
                </div>

            @endif
        </div>

        {{-- ========================================================= --}}
        {{-- TOP PRODUCTS + CATEGORY --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
            {{-- TOP PRODUCTS --}}

            <div class="bg-white border border-gray-200 rounded-xl">
                <div class="p-5 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Top Selling Products</h2>

                    <p class="text-sm text-gray-500">Products with the highest number of sales.</p>
                </div>

                @if ($topProducts->count())
                    <div class="divide-y divide-gray-100">
                        @foreach ($topProducts as $index => $product)
                            <div class="flex items-center gap-4 p-5">
                                {{-- RANK --}}

                                <div
                                    class="flex items-center justify-center flex-shrink-0 text-sm font-medium text-gray-600 bg-gray-100 rounded-full w-9 h-9"
                                >
                                    {{ $index + 1 }}
                                </div>

                                {{-- PRODUCT --}}

                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $product->product_name }}</p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ number_format($product->total_quantity) }} items sold
                                    </p>
                                </div>

                                {{-- SALES --}}

                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ number_format($product->total_sales) }}
                                    </p>

                                    <p class="text-xs text-gray-500">MMK</p>
                                </div>
                            </div>

                        @endforeach
                    </div>

                @else
                    <div class="flex flex-col items-center justify-center py-16">
                        <i class="mb-3 text-3xl text-gray-300 fa-solid fa-box-open"></i>

                        <p class="text-sm text-gray-500">No product sales yet.</p>
                    </div>

                @endif
            </div>

            {{-- CATEGORY SALES --}}

            <div class="bg-white border border-gray-200 rounded-xl">
                <div class="p-5 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Sales by Category</h2>

                    <p class="text-sm text-gray-500">Revenue generated by each category.</p>
                </div>

                @if ($categorySales->count())
                    <div class="p-5 space-y-5">
                        @foreach ($categorySales as $category)
                            @php
                                $percentage = $categoryTotalSales > 0 ? ($category['sales'] / $categoryTotalSales) * 100 : 0;
                            @endphp

                            <div>
                                <div class="flex items-center justify-between gap-3 mb-2">
                                    <div class="min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $category['category'] }}
                                        </p>

                                        <p class="text-xs text-gray-500">
                                            {{ number_format($category['quantity']) }} items sold
                                        </p>
                                    </div>

                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ number_format($category['sales']) }} MMK
                                        </p>

                                        <p class="text-xs text-gray-500">{{ number_format($percentage, 1) }}%</p>
                                    </div>
                                </div>

                                {{-- PROGRESS BAR --}}

                                <div class="w-full h-2 overflow-hidden bg-gray-100 rounded-full">
                                    <div
                                        class="h-full rounded-full bg-lime-400"
                                        style="width: {{ min($percentage, 100) }}%;"
                                    ></div>
                                </div>
                            </div>

                        @endforeach
                    </div>

                @else
                    <div class="flex flex-col items-center justify-center py-16">
                        <i class="mb-3 text-3xl text-gray-300 fa-solid fa-layer-group"></i>

                        <p class="text-sm text-gray-500">No category sales yet.</p>
                    </div>

                @endif
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- SALES ORDERS --}}
        {{-- ========================================================= --}}

        <div class="mt-6 bg-white border border-gray-200 rounded-xl">
            <div
                class="flex flex-col gap-3 p-5 border-b border-gray-200 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Sales Records</h2>

                    <p class="text-sm text-gray-500">Orders included in the selected sales period.</p>
                </div>

                <div class="text-sm text-gray-500">{{ $totalOrders }} orders</div>
            </div>

            @if ($salesOrders->count())
                {{-- DESKTOP TABLE --}}

                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-500">
                                <th class="px-5 py-3 font-medium">Order</th>

                                <th class="px-5 py-3 font-medium">Customer</th>

                                <th class="px-5 py-3 font-medium">Date</th>

                                <th class="px-5 py-3 font-medium">Total</th>

                                <th class="px-5 py-3 font-medium">Payment</th>

                                <th class="px-5 py-3 font-medium">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($salesOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    {{-- ORDER --}}

                                    <td class="px-5 py-4 font-medium text-gray-900">{{ $order->order_number }}</td>

                                    {{-- CUSTOMER --}}

                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $order->name ?? ($order->user?->name ?? 'Guest') }}
                                    </td>

                                    {{-- DATE --}}

                                    <td class="px-5 py-4 text-gray-500">
                                        {{ $order->created_at->format('M d, Y') }}

                                        <p class="text-xs text-gray-400">{{ $order->created_at->format('h:i A') }}</p>
                                    </td>

                                    {{-- TOTAL --}}

                                    <td class="px-5 py-4 font-medium text-gray-900">
                                        {{ number_format($order->total) }}

                                        <span class="text-xs font-normal text-gray-500"> MMK </span>
                                    </td>

                                    {{-- PAYMENT --}}

                                    <td class="px-5 py-4">
                                        @if ($order->payment_status === 'verified')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full"
                                            >
                                                Verified
                                            </span>

                                        @elseif ($order->payment_status === 'rejected')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full"
                                            >
                                                Rejected
                                            </span>

                                        @else
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
                                            >
                                                Pending
                                            </span>

                                        @endif
                                    </td>

                                    {{-- STATUS --}}

                                    <td class="px-5 py-4">
                                        @if ($order->status === 'delivered')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full"
                                            >
                                                Delivered
                                            </span>

                                        @elseif ($order->status === 'shipped')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full"
                                            >
                                                Shipped
                                            </span>

                                        @elseif ($order->status === 'processing')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full"
                                            >
                                                Processing
                                            </span>

                                        @elseif ($order->status === 'confirmed')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-full"
                                            >
                                                Confirmed
                                            </span>

                                        @else
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full"
                                            >
                                                {{ ucfirst($order->status) }}
                                            </span>

                                        @endif
                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE LIST --}}

                <div class="divide-y divide-gray-100 md:hidden">
                    @foreach ($salesOrders as $order)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-medium text-gray-900">#{{ $order->order_number }}</p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $order->name ?? ($order->user?->name ?? 'Guest') }}
                                    </p>
                                </div>

                                <div class="text-right">
                                    <p class="font-medium text-gray-900">{{ number_format($order->total) }}</p>

                                    <p class="text-xs text-gray-500">MMK</p>
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2 mt-4">
                                @if ($order->payment_status === 'verified')
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full"
                                    >
                                        Payment Verified
                                    </span>

                                @elseif ($order->payment_status === 'rejected')
                                    <span class="px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                        Payment Rejected
                                    </span>

                                @else
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
                                    >
                                        Payment Pending
                                    </span>

                                @endif

                                @if ($order->status === 'delivered')
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full"
                                    >
                                        Delivered
                                    </span>

                                @elseif ($order->status === 'shipped')
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-purple-700 bg-purple-100 rounded-full"
                                    >
                                        Shipped
                                    </span>

                                @elseif ($order->status === 'processing')
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full"
                                    >
                                        Processing
                                    </span>

                                @elseif ($order->status === 'confirmed')
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-100 rounded-full"
                                    >
                                        Confirmed
                                    </span>

                                @else
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full"
                                    >
                                        {{ ucfirst($order->status) }}
                                    </span>

                                @endif
                            </div>

                            <p class="mt-3 text-xs text-gray-400">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                    @endforeach
                </div>

                {{-- PAGINATION --}}

                @if ($salesOrders->hasPages())
                    <div class="px-5 py-4 border-t border-gray-200">{{ $salesOrders->links() }}</div>

                @endif

            @else
                {{-- EMPTY STATE --}}

                <div class="flex flex-col items-center justify-center px-5 py-16 text-center">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 text-gray-300 bg-gray-100 rounded-full">
                        <i class="text-2xl fa-solid fa-chart-line"></i>
                    </div>

                    <h3 class="font-medium text-gray-900">No sales found</h3>

                    <p class="max-w-md mt-1 text-sm text-gray-500">There are no completed sales during the selected period.</p>
                </div>

            @endif
        </div>

        {{-- ========================================================= --}}
        {{-- SALES INFORMATION --}}
        {{-- ========================================================= --}}

        <div class="p-5 mt-6 border border-lime-200 bg-lime-50 rounded-xl">
            <div class="flex gap-3">
                <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 bg-white rounded-lg text-lime-600">
                    <i class="fa-solid fa-circle-info"></i>
                </div>

                <div>
                    <h3 class="text-sm font-medium text-gray-900">Sales calculation</h3>

                    <p class="mt-1 text-sm leading-6 text-gray-600">Sales include orders with the statuses
                    <span class="font-medium text-gray-900"> confirmed, processing, shipped, and delivered. </span>

                    Cancelled and pending orders are not included in sales revenue.</p>
                </div>
            </div>
        </div>
    </div>

@endsection
