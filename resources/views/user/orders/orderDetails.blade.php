@extends ('layouts.user.app')

@section ('content')
    <div class="w-[90%] mx-auto pb-20 mt-6 sm:w-[85%] sm:mt-8 lg:w-[70%] lg:mt-10">
        {{-- Back --}}
        <a
            href="{{ route('user#myOrder') }}"
            class="inline-flex items-center gap-2 mb-6 text-sm text-gray-500 hover:text-gray-900"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to Orders
        </a>

        {{-- Header --}}
        <div class="flex items-center mb-8">
            <div class="w-full">
                <div class="flex flex-col items-start gap-3 sm:flex-row sm:items-center sm:gap-5">
                    <h1 class="text-2xl font-medium break-all">Order - {{ $order->order_number }}</h1>

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
                        class="inline-flex items-center gap-2 px-2 py-1.5 text-xs font-medium rounded-full {{ $statusClasses }}"
                    >
                        <span class="w-2 h-2 rounded-full {{ $dotClasses }}"></span>

                        {{ ucfirst($status) }}
                    </span>
                </div>

                <p class="mt-2 text-sm text-gray-500">Placed on <span class="text-black">{{ $order->created_at->format('d F Y, h:i A') }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            {{-- LEFT --}}
            <div class="lg:col-span-2">
                <div class="overflow-hidden border-y">
                    <div class="py-6">
                        <h2 class="font-medium">Ordered Items</h2>
                    </div>

                    @foreach ($order->items as $item)
                        @php
                            $variant = $item->productVariant;
                            $product = $variant?->product;
                            $image = $variant?->images?->first();
                        @endphp

                        <div class="flex flex-col gap-4 pb-6 pr-0 sm:flex-row sm:items-start sm:gap-5 sm:pr-6">
                            {{-- Image --}}
                            <div
                                class="flex items-center justify-center flex-shrink-0 w-24 h-24 overflow-hidden bg-gray-100 sm:w-28 sm:h-28 rounded-xl"
                            >
                                @if ($image)
                                    <img
                                        src="{{ $image->image }}"
                                        alt="{{ $product?->name }}"
                                        class="object-contain w-full h-full"
                                    />

                                @else
                                    <i class="text-2xl text-gray-400 fa-solid fa-image"></i>

                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0 sm:ml-1">
                                <h3 class="font-medium break-words">{{ $product?->name ?? 'Product' }}</h3>

                                <div class="flex flex-wrap mt-1 text-sm text-gray-500 gap-x-4 gap-y-1">
                                    @if ($variant?->capacity)
                                        <span>Capacity: {{ $variant->capacity }}</span>
                                    @endif

                                    @if ($variant?->color)
                                        <span>Color: {{ $variant->color }}</span>
                                    @endif
                                </div>

                                <p class="mt-4 text-sm text-gray-500 sm:mt-8">Quantity: {{ $item->quantity }}</p>
                            </div>

                            {{-- Price --}}
                            <div class="text-left sm:text-right sm:flex-shrink-0">
                                <p class="font-medium">{{ number_format($item->price * $item->quantity) }} MMK</p>
                            </div>
                        </div>

                    @endforeach
                </div>

                {{-- Payment Details --}}
                <div class="py-6 pr-0 sm:pr-6">
                    <h2 class="font-medium">Order Summary</h2>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-start justify-between gap-4">
                            <span class="text-sm text-gray-500">Payment Method</span>

                            <span class="text-right break-words"> {{ $order->paymentMethod->name ?? 'N/A' }} </span>
                        </div>

                        <div class="flex items-start justify-between gap-4">
                            <span class="text-sm text-gray-500">Subtotal</span>

                            <span class="text-right whitespace-nowrap">
                                {{ number_format($order->subtotal) }} MMK
                            </span>
                        </div>

                        <div class="flex items-start justify-between gap-4">
                            <span class="text-sm text-gray-500">Shipping Fee</span>

                            <span class="text-right whitespace-nowrap">
                                {{ number_format($order->shipping_fee ?? 0) }} MMK
                            </span>
                        </div>

                        <div class="font-medium">
                            <div class="flex items-center justify-between gap-4">
                                <span class="font-medium">Total</span>

                                <span class="text-lg font-medium whitespace-nowrap">
                                    {{ number_format($order->total) }} MMK
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="space-y-6">
                {{-- Delivery Information --}}
                <div class="p-5 border sm:p-6 rounded-xl">
                    <h2 class="font-medium text-gray-900">Delivery Information</h2>

                    <div class="mt-5 space-y-4 text-sm">
                        <div>
                            <p class="text-xs text-gray-500">Name</p>

                            <p class="mt-1 break-words">{{ $order->name }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Email</p>

                            <p class="mt-1 break-all">{{ $order->email }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Phone</p>

                            <p class="mt-1 break-words">{{ $order->phone }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Address</p>

                            <p class="mt-1 leading-6 break-words">{{ $order->address }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
