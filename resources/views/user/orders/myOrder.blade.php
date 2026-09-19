@extends ('layouts.user.app')

@section ('content')
    <div class="w-[92%] sm:w-[88%] md:w-[82%] lg:w-[75%] xl:w-[70%] mx-auto pb-20 mt-6 sm:mt-8 lg:mt-10">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6 sm:mb-8">
            <div>
                <h1 class="text-2xl font-medium text-gray-900 sm:text-3xl">My Orders</h1>

                {{-- <p class="mt-2 text-sm text-gray-500">View and manage your recent orders.</p> --}}
            </div>
        </div>

        {{-- Orders --}}
        @forelse ($orders as $order)
            <div class="mb-4 overflow-hidden bg-white border rounded-xl sm:mb-5">
                {{-- Order Header --}}
                <div
                    class="flex flex-col gap-3 px-4 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8"
                >
                    <div class="flex items-center gap-4 sm:gap-8">
                        <div class="flex items-center gap-2">
                            {{-- <p class="text-xs text-gray-500 uppercase">Order</p> --}}
                            <x-heroicon-o-shopping-bag class="size-5 sm:size-6" />

                            <p class="text-sm font-medium sm:text-base">{{ $order->order_number }}</p>
                        </div>

                        {{-- <div>
                        <p class="text-xs text-gray-500 uppercase">Date</p>

                        <p class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                    </div> --}}
                    </div>

                    {{-- Status --}}
                    @php
                        $status = strtolower($order->status ?? 'pending');

                        $statusClasses = match ($status) {
                            'confirmed', 'completed', 'delivered' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'processing' => 'bg-blue-100 text-blue-700',
                            'cancelled', 'canceled' => 'bg-red-100 text-red-700',
                            default => 'bg-gray-100 text-gray-700',
                        };

                        $dotClasses = match ($status) {
                            'confirmed', 'completed', 'delivered' => 'bg-green-500',
                            'pending' => 'bg-yellow-500',
                            'processing' => 'bg-blue-500',
                            'cancelled', 'canceled' => 'bg-red-500',
                            default => 'bg-gray-500',
                        };
                    @endphp

                    <span
                        class="inline-flex items-center self-start gap-2 px-3 py-1.5 text-xs font-medium rounded-full sm:self-auto {{ $statusClasses }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>

                        {{ ucfirst($status) }}
                    </span>
                </div>

                {{-- <div class="flex items-center justify-between gap-5 px-6 py-5">
                <p class="font-medium text-gray-900">#{{ $order->id }}</p>

                @php
                    $status = strtolower($order->status ?? 'pending');

                    $statusClasses = match ($status) {
                        'confirmed', 'completed', 'delivered' => 'bg-green-100 text-green-700',

                        'pending' => 'bg-yellow-100 text-yellow-700',

                        'processing' => 'bg-blue-100 text-blue-700',

                        'cancelled', 'canceled' => 'bg-red-100 text-red-700',

                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                <span class="px-3 py-1.5 text-xs font-medium rounded-full {{ $statusClasses }}">
                    {{ ucfirst($status) }}
                </span>

                <p class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
            </div> --}}

                {{-- Order Items --}}
                <div class="px-4 pt-1 pb-4 sm:px-6 sm:pt-2 lg:px-8">
                    @foreach ($order->items->take(1) as $item)
                        @php
                            $variant = $item->productVariant;
                            $product = $variant?->product;
                            $image = $variant?->images?->first();
                        @endphp

                        <div class="flex items-center px-3 py-3 mb-4 border rounded-xl sm:px-4 sm:py-4">
                            {{-- Image --}}
                            <div
                                class="flex items-center justify-center flex-shrink-0 w-16 h-16 overflow-hidden bg-gray-100 rounded-lg sm:w-20 sm:h-20"
                            >
                                @if ($image)
                                    <img
                                        src="{{ $image->image }}"
                                        alt="{{ $product?->name }}"
                                        class="object-contain w-full h-full"
                                    />
                                @else
                                    <i class="text-lg text-gray-400 sm:text-xl fa-solid fa-image"></i>
                                @endif
                            </div>

                            {{-- Product --}}
                            <div class="flex-1 min-w-0 ml-3 sm:ml-5">
                                <h3 class="text-sm font-medium text-gray-900 truncate sm:text-base">
                                    {{ $product?->name ?? 'Product' }}
                                </h3>

                                <p class="mt-1 text-xs text-gray-500 sm:text-sm">
                                    {{ $variant?->capacity }}

                                    @if ($variant?->color)
                                        / {{ $variant->color }}
                                    @endif
                                </p>

                                <p class="mt-1 text-xs text-gray-500 sm:text-sm">Qty: {{ $item->quantity }}</p>
                            </div>

                            {{-- Price --}}
                            {{-- <div class="text-right">
                            <p class="font-medium text-gray-900">
                                {{ number_format($item->price * $item->quantity) }} MMK
                            </p>
                        </div> --}}
                        </div>

                    @endforeach

                    {{-- More items --}}
                    @if ($order->items->count() > 1)
                        <a
                            href="{{ route('user#orderDetails', $order->id) }}"
                            class="inline-block mt-1 text-sm text-gray-500 hover:text-gray-700"
                        >
                            + {{ $order->items->count() - 1 }} more item(s)
                        </a>
                    @endif
                </div>

                {{-- Footer --}}
                <div
                    class="flex flex-col gap-4 px-4 py-4 border-t sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8"
                >
                    <div>
                        <p class="text-sm text-gray-500">Total</p>

                        <p class="font-medium text-gray-900">{{ number_format($order->total) }} MMK</p>
                    </div>

                    <a
                        href="{{ route('user#orderDetails', $order) }}"
                        class="w-full py-2 text-sm font-medium text-center transition rounded-full sm:w-auto px-7 bg-lime-400 hover:bg-lime-500"
                    >
                        View Details
                    </a>
                </div>
            </div>

        @empty
            {{-- Empty --}}
            <div class="px-4 py-16 text-center border rounded-2xl sm:py-20">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-5 bg-gray-100 rounded-full">
                    <i class="text-2xl text-gray-400 fa-solid fa-box-open"></i>
                </div>

                <h2 class="text-xl font-medium text-gray-900">No orders yet</h2>

                <p class="mt-2 text-sm text-gray-500">You haven't placed any orders yet.</p>

                <a
                    href="{{ route('user#productList') }}"
                    class="inline-block px-6 py-3 mt-6 text-sm font-medium rounded-full bg-lime-400 hover:bg-lime-500"
                >
                    Start Shopping
                </a>
            </div>

        @endforelse
    </div>

@endsection
