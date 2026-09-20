@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        {{-- HEADER --}}
        <div class="flex flex-col gap-2 mb-8 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-medium text-gray-900 sm:text-3xl">Dashboard</h1>

                <p class="text-sm text-gray-500">Welcome back. Here's what's happening with TechVerse.</p>
            </div>

            <div class="text-sm text-gray-500">{{ now()->format('F d, Y') }}</div>
        </div>

        {{-- ========================================================= --}}
        {{-- STAT CARDS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            {{-- PRODUCTS --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Products</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">{{ number_format($totalProducts) }}</p>
                    </div>

                    <div class="flex items-center justify-center rounded-lg w-11 h-11 text-lime-600 bg-lime-100">
                        <i class="text-lg fa-solid fa-box"></i>
                    </div>
                </div>

                <a href="{{ route('product#list') }}" class="inline-block mt-4 text-sm font-medium">
                    View products
                    <i class="ml-1 text-xs fa-solid fa-arrow-right"></i>
                </a>
            </div>

            {{-- CUSTOMERS --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Customers</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">{{ number_format($totalCustomers) }}</p>
                    </div>

                    <div class="flex items-center justify-center text-blue-600 bg-blue-100 rounded-lg w-11 h-11">
                        <i class="text-lg fa-solid fa-users"></i>
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-500">Registered customers</p>
            </div>

            {{-- ORDERS --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Orders</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">{{ number_format($totalOrders) }}</p>
                    </div>

                    <div class="flex items-center justify-center text-purple-600 bg-purple-100 rounded-lg w-11 h-11">
                        <i class="text-lg fa-solid fa-cart-shopping"></i>
                    </div>
                </div>

                <a href="{{ route('admin#orderBoard') }}" class="inline-block mt-4 text-sm font-medium">
                    View orders
                    <i class="ml-1 text-xs fa-solid fa-arrow-right"></i>
                </a>
            </div>

            {{-- SALES --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total Sales</p>

                        <p class="mt-2 text-2xl font-medium text-gray-900">
                            {{ number_format($totalSales) }}
                            <span class="text-sm font-normal text-gray-500"> MMK </span>
                        </p>
                    </div>

                    <div class="flex items-center justify-center text-green-600 bg-green-100 rounded-lg w-11 h-11">
                        <i class="text-lg fa-solid fa-money-bill-wave"></i>
                    </div>
                </div>

                <p class="mt-4 text-sm text-gray-500">Confirmed and completed sales</p>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- ORDER / PAYMENT SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-4 mt-6 lg:grid-cols-2">
            {{-- ORDER SUMMARY --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-medium text-gray-900">Order Overview</h2>

                    <i class="text-gray-400 fa-solid fa-chart-pie"></i>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="p-4 rounded-lg bg-yellow-50">
                        <p class="text-xs text-yellow-600">Pending</p>

                        <p class="mt-1 text-xl font-medium text-yellow-700">{{ $pendingOrders }}</p>
                    </div>

                    <div class="p-4 rounded-lg bg-blue-50">
                        <p class="text-xs text-blue-600">Processing</p>

                        <p class="mt-1 text-xl font-medium text-blue-700">{{ $processingOrders }}</p>
                    </div>

                    <div class="p-4 rounded-lg bg-green-50">
                        <p class="text-xs text-green-600">Delivered</p>

                        <p class="mt-1 text-xl font-medium text-green-700">{{ $completedOrders }}</p>
                    </div>

                    <div class="p-4 rounded-lg bg-red-50">
                        <p class="text-xs text-red-600">Cancelled</p>

                        <p class="mt-1 text-xl font-medium text-red-700">{{ $cancelledOrders }}</p>
                    </div>
                </div>
            </div>

            {{-- PAYMENT SUMMARY --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-lg font-medium text-gray-900">Payment Overview</h2>

                    <i class="text-gray-400 fa-solid fa-credit-card"></i>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="p-4 rounded-lg bg-yellow-50">
                        <p class="text-xs text-yellow-600">Pending</p>

                        <p class="mt-1 text-xl font-medium text-yellow-700">{{ $pendingPayments }}</p>
                    </div>

                    <div class="p-4 rounded-lg bg-green-50">
                        <p class="text-xs text-green-600">Verified</p>

                        <p class="mt-1 text-xl font-medium text-green-700">{{ $verifiedPayments }}</p>
                    </div>

                    <div class="p-4 rounded-lg bg-red-50">
                        <p class="text-xs text-red-600">Rejected</p>

                        <p class="mt-1 text-xl font-medium text-red-700">{{ $rejectedPayments }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- SALES CHART + TOP PRODUCTS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-6 mt-6 xl:grid-cols-3">
            {{-- SALES CHART --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl xl:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Sales Overview</h2>

                        <p class="mt-1 text-sm text-gray-500">Sales for the last 7 days</p>
                    </div>

                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="w-2.5 h-2.5 bg-lime-400 rounded-full"></span>
                        Sales
                    </div>
                </div>

                {{-- CHART --}}
                <div class="flex items-end h-64 gap-3 sm:gap-5">
                    @foreach ($salesChart as $item)
                        @php
                            $height = ($item['sales'] / $maxSales) * 100;

                            if ($item['sales'] > 0 && $height < 8) {
                                $height = 8;
                            }
                        @endphp

                        <div class="flex flex-col items-center justify-end flex-1 h-full">
                            <div class="relative flex items-end justify-center w-full h-full group">
                                <div
                                    class="w-full max-w-[45px] bg-lime-400 rounded-t-md transition-all duration-200 group-hover:bg-lime-500"
                                    style="height: {{ $height }}%;"
                                ></div>

                                {{-- TOOLTIP --}}
                                <div
                                    class="absolute z-10 hidden px-2 py-1 mb-2 text-xs text-white bg-gray-900 rounded-md bottom-full group-hover:block whitespace-nowrap"
                                >
                                    {{ $item['full_date'] }}
                                    <br />
                                    {{ number_format($item['sales']) }} MMK
                                </div>
                            </div>

                            <p class="mt-2 text-xs text-gray-500">{{ $item['date'] }}</p>
                        </div>

                    @endforeach
                </div>
            </div>

            {{-- TOP PRODUCTS --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Top Products</h2>

                        <p class="mt-1 text-sm text-gray-500">Best selling products</p>
                    </div>

                    <i class="text-gray-400 fa-solid fa-ranking-star"></i>
                </div>

                @if ($topProducts->count())
                    <div class="space-y-4">
                        @foreach ($topProducts as $index => $product)
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-sm font-medium text-gray-600 bg-gray-100 rounded-full"
                                >
                                    {{ $index + 1 }}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $product->product_name }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ number_format($product->total_quantity) }} sold
                                    </p>
                                </div>

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
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <i class="mb-3 text-3xl text-gray-300 fa-solid fa-box-open"></i>

                        <p class="text-sm text-gray-500">No sales yet</p>
                    </div>

                @endif
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- RECENT ORDERS --}}
        {{-- ========================================================= --}}

        <div class="mt-6 bg-white border border-gray-200 rounded-xl">
            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                <div>
                    <h2 class="text-lg font-medium text-gray-900">Recent Orders</h2>

                    <p class="text-sm text-gray-500">Latest orders from your customers</p>
                </div>

                <a href="{{ route('admin#orderBoard') }}" class="text-sm font-medium text-lime-600 hover:text-lime-700">
                    View all
                </a>
            </div>

            @if ($recentOrders->count())
                {{-- DESKTOP TABLE --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr class="text-left text-gray-500">
                                <th class="px-5 py-3 font-medium">Order</th>

                                <th class="px-5 py-3 font-medium">Customer</th>

                                <th class="px-5 py-3 font-medium">Total</th>

                                <th class="px-5 py-3 font-medium">Payment</th>

                                <th class="px-5 py-3 font-medium">Status</th>

                                <th class="px-5 py-3 font-medium">Date</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @foreach ($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-5 py-4 font-medium text-gray-900">{{ $order->order_number }}</td>

                                    <td class="px-5 py-4 text-gray-700">
                                        {{ $order->name ?? ($order->user?->name ?? 'Guest') }}
                                    </td>

                                    <td class="px-5 py-4 text-gray-900">
                                        {{ number_format($order->total) }}
                                        <span class="text-xs text-gray-500"> MMK </span>
                                    </td>

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

                                    <td class="px-5 py-4">
                                        @if ($order->status === 'delivered')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full"
                                            >
                                                Delivered
                                            </span>

                                        @elseif ($order->status === 'cancelled')
                                            <span
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full"
                                            >
                                                Cancelled
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
                                                class="inline-flex px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
                                            >
                                                Pending
                                            </span>

                                        @endif
                                    </td>

                                    <td class="px-5 py-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- MOBILE ORDERS --}}
                <div class="divide-y divide-gray-100 md:hidden">
                    @foreach ($recentOrders as $order)
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-medium text-gray-900">#{{ $order->order_number }}</p>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ $order->name ?? ($order->user?->name ?? 'Guest') }}
                                    </p>
                                </div>

                                <p class="font-medium text-gray-900">
                                    {{ number_format($order->total) }}
                                    <span class="text-xs text-gray-500"> MMK </span>
                                </p>
                            </div>

                            <div class="flex flex-wrap items-center gap-2 mt-3">
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

                                @elseif ($order->status === 'cancelled')
                                    <span class="px-2.5 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full">
                                        Cancelled
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
                                        class="px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
                                    >
                                        Pending
                                    </span>

                                @endif
                            </div>

                            <p class="mt-3 text-xs text-gray-400">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>

                    @endforeach
                </div>

            @else
                <div class="flex flex-col items-center justify-center px-5 py-16 text-center">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 text-gray-300 bg-gray-100 rounded-full">
                        <i class="text-2xl fa-solid fa-cart-shopping"></i>
                    </div>

                    <h3 class="font-medium text-gray-900">No orders yet</h3>

                    <p class="text-sm text-gray-500">Orders will appear here when customers place them.</p>
                </div>

            @endif
        </div>

        {{-- ========================================================= --}}
        {{-- INVENTORY --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 gap-6 mt-6 lg:grid-cols-2">
            {{-- LOW STOCK --}}
            <div class="bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between p-5 border-b border-gray-200">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Low Stock</h2>

                        <p class="text-sm text-gray-500">Products with 5 or fewer items</p>
                    </div>

                    <i class="text-yellow-500 fa-solid fa-triangle-exclamation"></i>
                </div>

                @if ($lowStockVariants->count())
                    <div class="divide-y divide-gray-100">
                        @foreach ($lowStockVariants as $variant)
                            <div class="flex items-center justify-between gap-4 p-5">
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate">
                                        {{ $variant->product?->name ?? 'Product' }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-500">
                                        {{ $variant->capacity }}
                                        @if ($variant->color)
                                            · {{ $variant->color }}
                                        @endif
                                    </p>
                                </div>

                                <div class="flex-shrink-0">
                                    <span
                                        class="px-2.5 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
                                    >
                                        {{ $variant->stock }} left
                                    </span>
                                </div>
                            </div>

                        @endforeach
                    </div>

                @else
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <i class="mb-3 text-3xl text-green-400 fa-solid fa-circle-check"></i>

                        <p class="text-sm text-gray-500">No low-stock products</p>
                    </div>

                @endif
            </div>

            {{-- INVENTORY SUMMARY --}}
            <div class="p-5 bg-white border border-gray-200 rounded-xl">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="text-lg font-medium text-gray-900">Inventory</h2>

                        <p class="text-sm text-gray-500">Current stock status</p>
                    </div>

                    <i class="text-gray-400 fa-solid fa-warehouse"></i>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-5 bg-gray-50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-500">Out of Stock</p>

                            <i class="text-red-400 fa-solid fa-box-open"></i>
                        </div>

                        <p class="mt-3 text-3xl font-medium text-gray-900">{{ $outOfStockVariants }}</p>

                        <p class="mt-1 text-xs text-gray-500">Variants</p>
                    </div>

                    <div class="p-5 bg-lime-50 rounded-xl">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-lime-700">Low Stock</p>

                            <i class="text-lime-600 fa-solid fa-box"></i>
                        </div>

                        <p class="mt-3 text-3xl font-medium text-gray-900">{{ $lowStockVariants->count() }}</p>

                        <p class="mt-1 text-xs text-gray-500">Variants shown</p>
                    </div>
                </div>

                <div class="p-4 mt-4 border border-lime-200 bg-lime-50 rounded-xl">
                    <div class="flex gap-3">
                        <div
                            class="flex items-center justify-center flex-shrink-0 bg-white rounded-lg w-9 h-9 text-lime-600"
                        >
                            <i class="fa-solid fa-lightbulb"></i>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-900">Inventory reminder</p>

                            <p class="mt-1 text-xs leading-5 text-gray-600">Keep an eye on products with low stock so you don't miss customer orders.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="p-5 mt-6 bg-white border border-gray-200 rounded-xl">
            <h2 class="text-lg font-medium text-gray-900">Quick Actions</h2>

            <div class="grid grid-cols-1 gap-3 mt-5 sm:grid-cols-2 lg:grid-cols-4">
                <a
                    href="{{ route('product#create') }}"
                    class="flex items-center gap-3 p-4 transition border border-gray-200 rounded-lg hover:border-lime-400 hover:bg-lime-50"
                >
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg text-lime-600 bg-lime-100">
                        <i class="fa-solid fa-plus"></i>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-900">Add Product</p>

                        <p class="text-xs text-gray-500">Create a new product</p>
                    </div>
                </a>

                <a
                    href="{{ route('category#list') }}"
                    class="flex items-center gap-3 p-4 transition border border-gray-200 rounded-lg hover:border-lime-400 hover:bg-lime-50"
                >
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg text-lime-600 bg-lime-100">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-900">Categories</p>

                        <p class="text-xs text-gray-500">Manage categories</p>
                    </div>
                </a>

                <a
                    href="{{ route('admin#orderBoard') }}"
                    class="flex items-center gap-3 p-4 transition border border-gray-200 rounded-lg hover:border-lime-400 hover:bg-lime-50"
                >
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg text-lime-600 bg-lime-100">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-900">Orders</p>

                        <p class="text-xs text-gray-500">Manage customer orders</p>
                    </div>
                </a>

                <a
                    href="{{ route('admin#payment') }}"
                    class="flex items-center gap-3 p-4 transition border border-gray-200 rounded-lg hover:border-lime-400 hover:bg-lime-50"
                >
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg text-lime-600 bg-lime-100">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-900">Payments</p>

                        <p class="text-xs text-gray-500">Manage payment methods</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

@endsection
