@extends ('layouts.user.app')

@section ('content')
    <div class="w-[70%] mx-auto mt-16 mb-20">
        <div class="mb-10 text-center">
            <div class="mb-4 text-5xl">✓</div>

            <h1 class="text-3xl font-semibold">Order Placed Successfully!</h1>

            <p class="mt-2 text-black/60">Thank you for your order.</p>

            <p class="mt-3 font-medium">Order {{ $order->id }}</p>
        </div>

        <div class="p-6 border rounded-lg">
            <h2 class="mb-6 text-xl font-semibold">Order Details</h2>

            {{-- Customer --}}
            <div class="grid grid-cols-1 gap-5 mb-8 md:grid-cols-2">
                <div>
                    <p class="text-sm text-black/50">Name</p>

                    <p class="font-medium">{{ $order->name }}</p>
                </div>

                <div>
                    <p class="text-sm text-black/50">Email</p>

                    <p class="font-medium">{{ $order->email }}</p>
                </div>

                <div>
                    <p class="text-sm text-black/50">Phone</p>

                    <p class="font-medium">{{ $order->phone }}</p>
                </div>

                <div>
                    <p class="text-sm text-black/50">City</p>

                    <p class="font-medium">{{ $order->city }}</p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-black/50">Address</p>

                    <p class="font-medium">{{ $order->address }}</p>
                </div>
            </div>

            {{-- Items --}}
            <h2 class="mb-5 text-xl font-semibold">Items</h2>

            <div class="space-y-4">
                @foreach ($order->items as $item)
                    <div class="flex justify-between pb-4 border-b">
                        <div>
                            <p class="font-medium">{{ $item->product_name }}</p>

                            <p class="text-sm text-black/60">{{ $item->capacity }} / {{ $item->color }}</p>

                            <p class="text-sm text-black/60">Qty: {{ $item->quantity }}</p>
                        </div>

                        <div class="font-medium">{{ number_format($item->subtotal) }} MMK</div>
                    </div>

                @endforeach
            </div>

            {{-- Total --}}
            <div class="max-w-sm mt-6 ml-auto space-y-3">
                <div class="flex justify-between">
                    <span class="text-black/60"> Subtotal </span>

                    <span> {{ number_format($order->subtotal) }} MMK </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-black/60"> Shipping </span>

                    <span> {{ number_format($order->shipping_fee) }} MMK </span>
                </div>

                <div class="flex justify-between pt-4 text-lg font-semibold border-t">
                    <span> Total </span>

                    <span> {{ number_format($order->total) }} MMK </span>
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <a href="{{ route('user#productList') }}" class="px-6 py-3 text-white bg-black rounded-lg">
                Continue Shopping
            </a>
        </div>
    </div>

@endsection
