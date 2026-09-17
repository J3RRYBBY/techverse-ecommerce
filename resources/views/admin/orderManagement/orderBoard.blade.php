@extends ('admin.home')

@section ('content')
    <div class="px-10 py-7">
        {{-- Header --}}
        <div class="flex flex-col justify-between gap-4 mb-6 md:flex-row md:items-center">
            <div>
                <h1 class="text-2xl font-medium text-gray-800">Order Board</h1>

                <p class="text-sm text-gray-500">Manage and track customer orders.</p>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-4 py-2 text-sm bg-white border rounded-lg">
                    <span class="text-gray-500">Total Orders</span>

                    <span class="ml-2 font-bold text-gray-900"> {{ $totalOrders }} </span>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-2 gap-4 mb-6 md:grid-cols-3 xl:grid-cols-6">
            @php
                $statistics = [
                    [
                        'key' => 'pending',
                        'label' => 'Pending',
                        'icon' => 'fa-clock',
                        'color' => 'yellow',
                    ],
                    [
                        'key' => 'confirmed',
                        'label' => 'Confirmed',
                        'icon' => 'fa-circle-check',
                        'color' => 'blue',
                    ],
                    [
                        'key' => 'processing',
                        'label' => 'Processing',
                        'icon' => 'fa-gear',
                        'color' => 'purple',
                    ],
                    [
                        'key' => 'shipped',
                        'label' => 'Shipped',
                        'icon' => 'fa-truck',
                        'color' => 'orange',
                    ],
                    [
                        'key' => 'delivered',
                        'label' => 'Delivered',
                        'icon' => 'fa-box-open',
                        'color' => 'green',
                    ],
                    [
                        'key' => 'cancelled',
                        'label' => 'Cancelled',
                        'icon' => 'fa-xmark',
                        'color' => 'red',
                    ],
                ];
            @endphp

            @foreach ($statistics as $stat)
                <div class="p-4 bg-white border shadow-sm rounded-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-500">{{ $stat['label'] }}</p>

                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $counts[$stat['key']] }}</p>
                        </div>

                        <div
                            class="flex items-center justify-center w-10 h-10 text-{{ $stat['color'] }}-500 bg-{{ $stat['color'] }}-100 rounded-xl"
                        >
                            <i class="fa-solid {{ $stat['icon'] }}"></i>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>

        <div class="min-h-screen py-2 bg-white rounded-lg">
            {{-- Search / Filter --}}
            <div class="px-6 my-3">
                <form method="GET" action="{{ route('admin#orderBoard') }}">
                    <div class="">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6">
                            {{-- Search --}}
                            <div class="xl:col-span-2">
                                <label class="block mb-1.5 text-xs font-medium text-gray-600"> Search </label>

                                <div class="relative">
                                    <i
                                        class="absolute text-xs text-gray-400 -translate-y-1/2 fa-solid fa-magnifying-glass left-3.5 top-1/2"
                                    ></i>

                                    <input
                                        type="text"
                                        name="search"
                                        value="{{ request('search') }}"
                                        placeholder="Order number, customer, phone..."
                                        class="w-full h-10 py-2 pl-10 pr-4 text-sm text-gray-700 transition border border-gray-200 rounded-lg bg-gray-50 placeholder:text-gray-400 hover:bg-white focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 focus:bg-white"
                                    />
                                </div>
                            </div>

                            {{-- Payment Status --}}
                            <div>
                                <label class="block mb-1.5 text-xs font-medium text-gray-600"> Payment Status </label>

                                <div class="relative">
                                    <button
                                        type="button"
                                        id="paymentBtn"
                                        class="flex items-center justify-between w-full h-10 px-3.5 text-sm text-gray-700 transition border border-gray-200 rounded-lg bg-gray-50 hover:bg-white focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 focus:bg-white"
                                    >
                                        <span id="paymentText">
                                            @if (request('payment_status') === 'pending')
                                                Pending
                                            @elseif (request('payment_status') === 'verified')
                                                Verified
                                            @elseif (request('payment_status') === 'rejected')
                                                Rejected
                                            @else
                                                All Payment Status
                                            @endif
                                        </span>

                                        <i
                                            class="text-[10px] text-gray-400 transition-transform fa-solid fa-chevron-down"
                                            id="paymentArrow"
                                        ></i>
                                    </button>

                                    <div
                                        id="paymentMenu"
                                        class="absolute left-0 z-30 hidden w-full mt-1.5 overflow-hidden bg-white border border-gray-100 rounded-lg shadow-lg"
                                    >
                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value=""
                                        >
                                            All Payment Status
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="pending"
                                        >
                                            Pending
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="verified"
                                        >
                                            Verified
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="rejected"
                                        >
                                            Rejected
                                        </div>
                                    </div>

                                    <input
                                        type="hidden"
                                        name="payment_status"
                                        id="paymentStatus"
                                        value="{{ request('payment_status') }}"
                                    />
                                </div>
                            </div>

                            {{-- Order Status --}}
                            <div>
                                <label class="block mb-1.5 text-xs font-medium text-gray-600"> Order Status </label>

                                <div class="relative">
                                    <button
                                        type="button"
                                        id="orderStatusBtn"
                                        class="flex items-center justify-between w-full h-10 px-3.5 text-sm text-gray-700 transition border border-gray-200 rounded-lg bg-gray-50 hover:bg-white focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 focus:bg-white"
                                    >
                                        <span id="orderStatusText">
                                            @if (request('status') === 'pending')
                                                Pending
                                            @elseif (request('status') === 'confirmed')
                                                Confirmed
                                            @elseif (request('status') === 'processing')
                                                Processing
                                            @elseif (request('status') === 'shipped')
                                                Shipped
                                            @elseif (request('status') === 'delivered')
                                                Delivered
                                            @elseif (request('status') === 'cancelled')
                                                Cancelled
                                            @else
                                                All Order Status
                                            @endif
                                        </span>

                                        <i
                                            class="text-[10px] text-gray-400 transition-transform fa-solid fa-chevron-down"
                                            id="orderStatusArrow"
                                        ></i>
                                    </button>

                                    <div
                                        id="orderStatusMenu"
                                        class="absolute left-0 z-30 hidden w-full mt-1.5 overflow-hidden bg-white border border-gray-100 rounded-lg shadow-lg"
                                    >
                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value=""
                                        >
                                            All Order Status
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="pending"
                                        >
                                            Pending
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="confirmed"
                                        >
                                            Confirmed
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="processing"
                                        >
                                            Processing
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="shipped"
                                        >
                                            Shipped
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="delivered"
                                        >
                                            Delivered
                                        </div>

                                        <div
                                            class="px-3.5 py-2.5 text-sm text-gray-600 transition cursor-pointer hover:bg-gray-50 hover:text-gray-900"
                                            data-value="cancelled"
                                        >
                                            Cancelled
                                        </div>
                                    </div>

                                    <input
                                        type="hidden"
                                        name="status"
                                        id="orderStatus"
                                        value="{{ request('status') }}"
                                    />
                                </div>
                            </div>

                            {{-- Order Date --}}
                            <div>
                                <label class="block mb-1.5 text-xs font-medium text-gray-600"> Order Date </label>

                                <div class="relative">
                                    <i
                                        class="absolute text-xs text-gray-400 -translate-y-1/2 fa-regular fa-calendar left-3.5 top-1/2"
                                    ></i>

                                    <input
                                        type="date"
                                        name="date"
                                        value="{{ request('date') }}"
                                        class="w-full h-10 px-3.5 pl-10 text-sm text-gray-700 transition border border-gray-200 rounded-lg bg-gray-50 hover:bg-white focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 focus:bg-white"
                                    />
                                </div>
                            </div>

                            {{-- Filter Actions --}}
                            <div class="flex items-center justify-center gap-2 mt-5">
                                <button
                                    type="submit"
                                    class="inline-flex items-center justify-center h-10 gap-2 px-5 text-sm font-medium text-gray-900 transition rounded-lg text-nowrap bg-lime-400 hover:bg-lime-500"
                                >
                                    <i class="text-xs fa-solid fa-filter"></i>
                                    Apply Filters
                                </button>

                                <a
                                    href="{{ route('admin#orderBoard') }}"
                                    class="inline-flex items-center justify-center h-10 gap-2 px-5 text-sm font-medium text-gray-600 transition bg-gray-100 rounded-lg hover:bg-gray-200"
                                >
                                    <i class="text-xs fa-solid fa-rotate-left"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Dropdown JavaScript --}}
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            /*
                            |--------------------------------------------------------------------------
                            | Payment Status Dropdown
                            |--------------------------------------------------------------------------
                            */

                            const paymentBtn = document.getElementById('paymentBtn');
                            const paymentMenu = document.getElementById('paymentMenu');
                            const paymentText = document.getElementById('paymentText');
                            const paymentStatus = document.getElementById('paymentStatus');
                            const paymentArrow = document.getElementById('paymentArrow');

                            paymentBtn.addEventListener('click', function (event) {
                                event.stopPropagation();

                                paymentMenu.classList.toggle('hidden');
                                orderStatusMenu.classList.add('hidden');

                                paymentArrow.classList.toggle('rotate-180');
                                orderStatusArrow.classList.remove('rotate-180');
                            });

                            paymentMenu.querySelectorAll('[data-value]').forEach(function (item) {
                                item.addEventListener('click', function () {
                                    const value = this.dataset.value;
                                    const text = this.textContent.trim();

                                    paymentText.textContent = text;
                                    paymentStatus.value = value;

                                    paymentMenu.classList.add('hidden');
                                    paymentArrow.classList.remove('rotate-180');
                                });
                            });

                            /*
                            |--------------------------------------------------------------------------
                            | Order Status Dropdown
                            |--------------------------------------------------------------------------
                            */

                            const orderStatusBtn = document.getElementById('orderStatusBtn');
                            const orderStatusMenu = document.getElementById('orderStatusMenu');
                            const orderStatusText = document.getElementById('orderStatusText');
                            const orderStatus = document.getElementById('orderStatus');
                            const orderStatusArrow = document.getElementById('orderStatusArrow');

                            orderStatusBtn.addEventListener('click', function (event) {
                                event.stopPropagation();

                                orderStatusMenu.classList.toggle('hidden');
                                paymentMenu.classList.add('hidden');

                                orderStatusArrow.classList.toggle('rotate-180');
                                paymentArrow.classList.remove('rotate-180');
                            });

                            orderStatusMenu.querySelectorAll('[data-value]').forEach(function (item) {
                                item.addEventListener('click', function () {
                                    const value = this.dataset.value;
                                    const text = this.textContent.trim();

                                    orderStatusText.textContent = text;
                                    orderStatus.value = value;

                                    orderStatusMenu.classList.add('hidden');
                                    orderStatusArrow.classList.remove('rotate-180');
                                });
                            });

                            /*
                            |--------------------------------------------------------------------------
                            | Close Dropdowns When Clicking Outside
                            |--------------------------------------------------------------------------
                            */

                            document.addEventListener('click', function (event) {
                                if (!paymentBtn.contains(event.target) && !paymentMenu.contains(event.target)) {
                                    paymentMenu.classList.add('hidden');
                                    paymentArrow.classList.remove('rotate-180');
                                }

                                if (!orderStatusBtn.contains(event.target) && !orderStatusMenu.contains(event.target)) {
                                    orderStatusMenu.classList.add('hidden');
                                    orderStatusArrow.classList.remove('rotate-180');
                                }
                            });
                        });
                    </script>
                </form>
            </div>

            <div class="px-2">
                <div
                    class="grid grid-cols-6 px-8 text-sm font-medium tracking-widest uppercase bg-gray-100 rounded-md text-black/70"
                >
                    <div class="px-6 py-3">Product Name</div>
                    <div class="px-6 py-3">Customer Name</div>
                    <div class="px-6 py-3">Order Id</div>
                    <div class="px-6 py-3">Amount</div>
                    <div class="px-6 py-3">Order Status</div>
                    <div class="px-6 py-3">Action</div>
                </div>

                <div>
                    @if ($orders->count() > 0)
                        @foreach ($orders as $order)
                            <div class="grid items-center grid-cols-6 px-8 text-sm font-medium border-b">
                                {{-- Product Name --}}
                                <div class="px-6 py-3">
                                    @php
                                        $firstItem = $order->items->first();
                                    @endphp

                                    @if ($firstItem)
                                        <div class="flex items-center gap-3">
                                            {{-- Product Image --}}
                                            @if ($firstItem->product_image)
                                                <img
                                                    src="{{ $firstItem->product_image }}"
                                                    alt="{{ $firstItem->product_name }}"
                                                    class="object-cover w-12 h-12 rounded-lg"
                                                />

                                            @else
                                                <div
                                                    class="flex items-center justify-center w-12 h-12 text-gray-400 bg-gray-100 rounded-lg"
                                                >
                                                    <i class="fa-solid fa-image"></i>
                                                </div>

                                            @endif

                                            <div class="min-w-0">
                                                <p class="text-sm text-gray-800 truncate">
                                                    {{ $firstItem->product_name }}
                                                </p>

                                                @if ($order->items->count() > 1)
                                                    <p class="mt-1 text-xs text-gray-400">+ {{ $order->items->count() - 1 }} more item(s)</p>

                                                @else
                                                    <p class="mt-1 text-xs text-gray-400">
                                                        Qty: {{ $firstItem->quantity }}
                                                    </p>

                                                @endif
                                            </div>
                                        </div>

                                    @else
                                        <span class="text-gray-400"> No product </span>

                                    @endif
                                </div>

                                {{-- Customer Name --}}
                                <div class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex items-center justify-center flex-shrink-0 text-sm font-medium text-gray-700 bg-gray-100 rounded-full w-9 h-9"
                                        >
                                            {{ strtoupper(substr($order->name, 0, 1)) }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm text-gray-800 truncate">{{ $order->name }}</p>

                                            <p class="mt-1 text-xs text-gray-400 truncate">{{ $order->phone }}</p>
                                        </div>
                                    </div>
                                </div>

                                {{-- Order ID --}}
                                <div class="px-6 py-3">
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ $order->order_number ?? $order->id }}
                                    </p>

                                    <p class="mt-1 text-xs text-gray-400">{{ $order->created_at->format('d-F-Y') }}</p>
                                </div>

                                {{-- Amount --}}
                                <div class="px-6 py-3">
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ number_format($order->total) }} MMK
                                    </p>

                                    {{-- Payment Status --}}
                                    <div class="mt-1 -ml-2">
                                        @if ($order->payment_status === 'verified')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                Payment Verified
                                            </span>

                                        @elseif ($order->payment_status === 'rejected')
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                Payment Rejected
                                            </span>

                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2 py-1 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                                Payment Pending
                                            </span>

                                        @endif
                                    </div>
                                </div>

                                {{-- Order Status --}}
                                <div class="px-6 py-3">
                                    <div>
                                        @if ($order->status === 'pending')
                                            <span
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs text-yellow-700 bg-yellow-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></span>
                                                Pending
                                            </span>

                                        @elseif ($order->status === 'confirmed')
                                            <span
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs text-blue-700 bg-blue-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>
                                                Confirmed
                                            </span>

                                        @elseif ($order->status === 'processing')
                                            <span
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs text-purple-700 bg-purple-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-purple-500 rounded-full"></span>
                                                Processing
                                            </span>

                                        @elseif ($order->status === 'shipped')
                                            <span
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs text-orange-700 bg-orange-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-orange-500 rounded-full"></span>
                                                Shipped
                                            </span>

                                        @elseif ($order->status === 'delivered')
                                            <span
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs text-green-700 bg-green-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                Delivered
                                            </span>

                                        @elseif ($order->status === 'cancelled')
                                            <span
                                                class="inline-flex items-center gap-2 px-2 py-1 text-xs text-red-700 bg-red-100 rounded-full"
                                            >
                                                <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                                Cancelled
                                            </span>

                                        @endif
                                    </div>
                                </div>

                                {{-- Action --}}
                                <div class="px-4 py-3">
                                    <a
                                        href="{{ route('admin#orderBoardDetails', $order->id) }}"
                                        class="px-5 py-2 text-black transition border rounded-lg hover:bg-lime-400"
                                        title="View Order"
                                    >
                                        Details
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Empty Order State --}}
                        <div class="px-6 py-16">
                            <div class="flex flex-col items-center justify-center text-center">
                                <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full">
                                    @if (request()->hasAny(['search', 'payment_status', 'status', 'date']))
                                        <i class="text-xl text-gray-400 fa-solid fa-filter-circle-xmark"></i>
                                    @else
                                        <i class="text-xl text-gray-400 fa-solid fa-box-open"></i>
                                    @endif
                                </div>

                                @if (request()->hasAny(['search', 'payment_status', 'status', 'date']))
                                    <h3 class="font-medium text-gray-700">No matching orders</h3>

                                    <p class="max-w-md mt-1 text-sm text-gray-400">We couldn't find any orders matching your current search or filters. Try changing your filters or clearing them to see all orders.</p>

                                    <a
                                        href="{{ route('admin#orderBoard') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 mt-5 text-sm font-medium text-gray-700 transition rounded-lg bg-lime-400 hover:bg-lime-500"
                                    >
                                        <i class="text-xs fa-solid fa-rotate-left"></i>
                                        Clear Filters
                                    </a>
                                @else
                                    <h3 class="font-medium text-gray-700">No orders yet</h3>

                                    <p class="max-w-md mt-1 text-sm text-gray-400">There are no customer orders to display yet. Orders will appear here when customers place their first order.</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
