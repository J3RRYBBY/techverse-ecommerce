@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        {{-- <a
            href="{{ route('admin#orderBoard') }}"
            class="inline-flex items-center gap-2 mb-3 text-sm text-gray-500 hover:text-gray-900"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to Orders
        </a> --}}

        {{-- Header --}}
        <div class="flex items-start gap-4 mb-8">
            {{-- Back --}}
            <a
                href="{{ route('admin#orderBoard') }}"
                class="flex items-center justify-center w-10 h-10 text-gray-600 transition bg-white border border-gray-200 rounded-lg hover:bg-gray-100"
            >
                <i class="text-sm fa-solid fa-arrow-left"></i>
            </a>

            <div>
                <h1 class="text-2xl font-medium">Order - {{ $order->order_number ?? $order->id }}</h1>

                <p class="mt-1 text-sm text-gray-500">
                    Placed on
                    <span class="text-black"> {{ $order->created_at->format('d F Y, h:i A') }} </span>
                </p>
            </div>

            {{-- Order Status Badge --}}
            <div>
                @php
                    $status = strtolower($order->status ?? 'pending');

                    $statusClasses = match ($status) {
                        'confirmed', 'completed', 'delivered' => 'bg-green-100 text-green-700',

                        'pending' => 'bg-yellow-100 text-yellow-700',

                        'processing' => 'bg-blue-100 text-blue-700',

                        'shipped' => 'bg-purple-100 text-purple-700',

                        'cancelled', 'canceled' => 'bg-red-100 text-red-700',

                        default => 'bg-gray-100 text-gray-700',
                    };

                    $dotClasses = match ($status) {
                        'confirmed', 'completed', 'delivered' => 'bg-green-500',

                        'pending' => 'bg-yellow-500',

                        'processing' => 'bg-blue-500',

                        'shipped' => 'bg-purple-500',

                        'cancelled', 'canceled' => 'bg-red-500',

                        default => 'bg-gray-500',
                    };
                @endphp

                <span
                    class="inline-flex items-center gap-2 px-2 py-1.5 text-xs font-medium rounded-full {{ $statusClasses }}"
                >
                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClasses }}"></span>

                    {{ ucfirst($status) }}
                </span>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            {{-- ========================================================= --}}
            {{-- LEFT SIDE --}}
            {{-- ========================================================= --}}
            <div class="space-y-6 lg:col-span-2">
                {{-- Order Items --}}
                <div class="overflow-hidden bg-white border shadow-sm rounded-xl">
                    <div class="px-6 py-5">
                        <h2 class="font-medium">Order Items</h2>

                        <p class="mt-1 text-xs text-gray-400">{{ $order->items->sum('quantity') }} items</p>
                    </div>

                    <div class="divide-y">
                        @foreach ($order->items as $item)
                            <div class="flex gap-4 p-5">
                                {{-- Product Image --}}
                                @if ($item->product_image)
                                    <img
                                        src="{{ $item->product_image }}"
                                        class="object-cover w-20 h-20 rounded-xl"
                                        alt="{{ $item->product_name }}"
                                    />

                                @else
                                    <div class="flex items-center justify-center w-20 h-20 bg-gray-100 rounded-xl">
                                        <i class="text-gray-400 fa-solid fa-image"></i>
                                    </div>

                                @endif

                                {{-- Product Information --}}
                                <div class="flex-1">
                                    <h3 class="font-medium">{{ $item->product_name }}</h3>

                                    <div class="mt-1 mb-3 text-sm text-gray-500">
                                        @if ($item->capacity)
                                            <span> Capacity: {{ $item->capacity }} </span>
                                        @endif

                                        @if ($item->color)
                                            <span class="ml-4"> Color: {{ $item->color }} </span>
                                        @endif
                                    </div>

                                    <span class="text-xs"> Qty: {{ $item->quantity }} </span>
                                </div>

                                {{-- Price --}}
                                <div class="text-right">
                                    <p class="font-medium">{{ number_format($item->subtotal) }} MMK</p>

                                    <p class="mt-1 text-xs text-gray-400">
                                        {{ number_format($item->price) }} MMK × {{ $item->quantity }}
                                    </p>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="p-6 bg-white border shadow-sm rounded-xl">
                    <h2 class="font-medium">Order Summary</h2>

                    <div class="mt-6 space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500"> Subtotal </span>

                            <span> {{ number_format($order->subtotal) }} MMK </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500"> Shipping </span>

                            <span> {{ number_format($order->shipping_fee) }} MMK </span>
                        </div>

                        <div class="pt-3 border-t">
                            <div class="flex justify-between">
                                <span class="font-medium"> Total </span>

                                <span class="text-lg font-medium"> {{ number_format($order->total) }} MMK </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Customer Information --}}
                <div class="p-6 bg-white border shadow-sm rounded-xl">
                    <h2 class="font-medium">Customer Information</h2>

                    <div class="grid gap-5 mt-5 md:grid-cols-2">
                        <div>
                            <p class="text-xs text-gray-500">Name</p>

                            <p class="mt-1 text-sm font-medium">{{ $order->name }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Phone</p>

                            <p class="mt-1 text-sm font-medium">{{ $order->phone }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">Email</p>

                            <p class="mt-1 text-sm font-medium">{{ $order->email }}</p>
                        </div>

                        <div>
                            <p class="text-xs text-gray-500">City</p>

                            <p class="mt-1 text-sm font-medium">{{ $order->city }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <p class="text-xs text-gray-500">Delivery Address</p>

                            <p class="mt-1 text-sm font-medium">{{ $order->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- RIGHT SIDE --}}
            {{-- ========================================================= --}}
            <div class="space-y-6">
                {{-- ===================================================== --}}
                {{-- ORDER STATUS --}}
                {{-- ===================================================== --}}
                <div class="p-6 bg-white border shadow-sm rounded-xl">
                    <h2 class="font-medium">Update Order</h2>

                    @php
                        $availableStatuses = match ($order->status) {
                            'pending' => ['cancelled'],

                            'confirmed' => ['processing', 'cancelled'],

                            'processing' => ['shipped', 'cancelled'],

                            'shipped' => ['delivered'],

                            'delivered' => [],

                            'cancelled' => [],

                            default => [],
                        };
                    @endphp

                    {{-- ------------------------------------------------- --}}
                    {{-- Pending Payment --}}
                    {{-- ------------------------------------------------- --}}
                    @if (
                        $order->status === 'pending' &&
                        $order->payment_status === 'pending'
                    )
                        <div class="p-4 mt-5 text-sm text-yellow-700 bg-yellow-50 rounded-xl">
                            <div class="flex gap-2">
                                <i class="mt-0.5 fa-solid fa-clock"></i>

                                <div>
                                    <p class="font-medium">Waiting for payment verification</p>

                                    <p class="mt-1 text-xs">Verify the payment before this order can be confirmed.</p>
                                </div>
                            </div>
                        </div>

                    @endif

                    {{-- ------------------------------------------------- --}}
                    {{-- Status Form --}}
                    {{-- ------------------------------------------------- --}}
                    @if (count($availableStatuses) > 0)
                        <form action="{{ route('admin.order.status', $order) }}" method="POST" class="mt-5">
                            @csrf
                            @method ('PATCH')

                            <label class="text-xs text-gray-500"> Order Status </label>

                            <div class="relative mt-2">
                                {{-- Button --}}
                                <button
                                    type="button"
                                    onclick="toggleStatusMenu()"
                                    class="flex items-center justify-between w-full px-4 py-2.5 text-sm text-left border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                >
                                    <span id="selectedStatus"> {{ ucfirst($order->status) }} </span>

                                    <svg
                                        class="w-4 h-4 text-gray-500"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </button>

                                {{-- Menu --}}
                                <div
                                    id="statusMenu"
                                    class="absolute z-20 hidden w-full mt-1 overflow-hidden bg-white border rounded-lg shadow-lg"
                                >
                                    @foreach ($availableStatuses as $status)
                                        <button
                                            type="button"
                                            onclick="selectStatus('{{ $status }}')"
                                            class="block w-full px-4 py-2.5 text-sm text-left hover:bg-gray-100"
                                        >
                                            {{ ucfirst($status) }}
                                        </button>

                                    @endforeach
                                </div>

                                {{-- Hidden Status --}}
                                <input type="hidden" name="status" id="status" value="" />
                            </div>

                            {{-- Update Button --}}
                            <button
                                type="submit"
                                id="updateStatusButton"
                                disabled
                                class="w-full py-3 mt-4 text-sm font-semibold text-gray-400 transition bg-gray-100 cursor-not-allowed rounded-xl"
                            >
                                Update Status
                            </button>
                        </form>

                    @else
                        {{-- Terminal --}}
                        <div class="mt-5">
                            <label class="text-xs text-gray-500"> Order Status </label>

                            <div class="flex items-center justify-between px-4 py-3 mt-2 border rounded-lg bg-gray-50">
                                <span class="text-sm font-medium"> {{ ucfirst($order->status) }} </span>

                                @if ($order->status === 'delivered')
                                    <span class="text-xs text-green-600"> Completed </span>

                                @elseif ($order->status === 'cancelled')
                                    <span class="text-xs text-red-600"> Cancelled </span>

                                @endif
                            </div>

                            <p class="mt-3 text-xs text-gray-400">This order can no longer be moved to another status.</p>
                        </div>

                    @endif
                </div>

                {{-- ===================================================== --}}
                {{-- PAYMENT --}}
                {{-- ===================================================== --}}
                <div class="p-6 bg-white border shadow-sm rounded-2xl">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="font-medium">Payment</h2>

                            <p class="mt-1 text-xs text-gray-400">Review the customer's payment</p>
                        </div>
                    </div>

                    <div class="mt-5 space-y-4">
                        {{-- Payment Method --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"> Payment Method </span>

                            <span class="px-1 py-1 text-sm font-medium">
                                {{ $order->paymentMethod->name ?? 'N/A' }}
                            </span>
                        </div>

                        {{-- Payment Status --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500"> Payment Status </span>

                            @if ($order->payment_status === 'verified')
                                <span
                                    class="px-2.5 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full"
                                >
                                    Verified
                                </span>

                            @elseif ($order->payment_status === 'rejected')
                                <span class="px-2.5 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">
                                    Rejected
                                </span>

                            @else
                                <span
                                    class="px-2.5 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full"
                                >
                                    Pending
                                </span>

                            @endif
                        </div>

                        {{-- Receipt --}}
                        @if ($order->payment_receipt)
                            <div class="pt-4 border-t">
                                <p class="mb-3 text-xs font-medium text-gray-500">Payment Receipt</p>

                                <button
                                    type="button"
                                    onclick="openReceiptModal()"
                                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition bg-gray-900 rounded-lg hover:bg-gray-800"
                                >
                                    <i class="fa-solid fa-receipt"></i>

                                    View Receipt
                                </button>

                                <p class="mt-2 text-xs text-gray-400">Click to view the payment receipt.</p>
                            </div>

                        @else
                            <div class="p-4 text-sm text-center text-gray-500 bg-gray-50 rounded-xl">
                                No payment receipt uploaded.
                            </div>

                        @endif

                        {{-- ================================================= --}}
                        {{-- PAYMENT ACTIONS --}}
                        {{-- ================================================= --}}

                        @if ($order->payment_status === 'pending')
                            @if ($order->payment_receipt)
                                <div class="grid grid-cols-2 gap-3 pt-4 border-t">
                                    {{-- Reject --}}
                                    <form
                                        action="{{ route('admin.order.payment.reject', $order) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to reject this payment?');"
                                    >
                                        @csrf
                                        @method ('PATCH')

                                        <button
                                            type="submit"
                                            class="w-full px-4 py-3 text-sm font-medium text-red-700 transition bg-red-50 rounded-xl hover:bg-red-100"
                                        >
                                            <i class="mr-1 fa-solid fa-xmark"></i>

                                            Reject
                                        </button>
                                    </form>

                                    {{-- Verify --}}
                                    <form
                                        action="{{ route('admin.order.payment.verify', $order) }}"
                                        method="POST"
                                        onsubmit="
                                            return confirm(
                                                'Verify payment and confirm this order? Stock will be deducted.',
                                            );
                                        "
                                    >
                                        @csrf
                                        @method ('PATCH')

                                        <button
                                            type="submit"
                                            class="w-full px-4 py-3 text-sm font-medium transition bg-lime-400 rounded-xl hover:bg-lime-500"
                                        >
                                            <i class="mr-1 fa-solid fa-check"></i>

                                            Confirm Payment
                                        </button>
                                    </form>
                                </div>

                                <p class="mt-3 text-xs text-gray-400">Confirming payment will verify the payment, confirm the order, and deduct the ordered quantity from product stock.</p>

                            @else
                                <div
                                    class="p-4 mt-4 text-sm text-center text-yellow-700 border border-yellow-100 bg-yellow-50 rounded-xl"
                                >
                                    <i class="mr-1 fa-solid fa-clock"></i>

                                    Waiting for customer payment receipt.
                                </div>

                            @endif

                        @elseif ($order->payment_status === 'verified')
                            <div class="flex items-center gap-2 p-3 mt-4 text-sm text-green-700 bg-green-50 rounded-xl">
                                <i class="fa-solid fa-circle-check"></i>

                                <span> Payment has been verified. Stock has been deducted. </span>
                            </div>

                        @elseif ($order->payment_status === 'rejected')
                            <div class="flex items-center gap-2 p-3 mt-4 text-sm text-red-700 bg-red-50 rounded-xl">
                                <i class="fa-solid fa-circle-xmark"></i>

                                <span> Payment has been rejected. Order has been cancelled. </span>
                            </div>

                        @endif
                    </div>

                    {{-- ================================================= --}}
                    {{-- RECEIPT MODAL --}}
                    {{-- ================================================= --}}
                    <div
                        id="receiptModal"
                        class="fixed inset-0 z-50 items-center justify-center hidden p-4 bg-black/50"
                    >
                        <div class="relative w-full max-w-4xl max-h-[90vh]">
                            <button
                                type="button"
                                onclick="closeReceiptModal()"
                                class="absolute z-10 flex items-center justify-center w-10 h-10 text-xl text-white rounded-full bg-black/60 -top-6 right-3 hover:bg-black/80"
                            >
                                &times;
                            </button>

                            <div class="flex items-center justify-center overflow-auto rounded-xl max-h-[90vh]">
                                <img
                                    src="{{ $order->payment_receipt }}"
                                    alt="Payment Receipt"
                                    class="object-contain max-w-full max-h-[85vh]"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================ --}}
    {{-- JAVASCRIPT --}}
    {{-- ================================================================ --}}
    <script>
        function toggleStatusMenu() {
            const menu = document.getElementById('statusMenu');

            if (menu) {
                menu.classList.toggle('hidden');
            }
        }

        function selectStatus(status) {
            const statusInput = document.getElementById('status');

            const selectedStatus = document.getElementById('selectedStatus');

            const updateButton = document.getElementById('updateStatusButton');

            const menu = document.getElementById('statusMenu');

            statusInput.value = status;

            selectedStatus.textContent = status.charAt(0).toUpperCase() + status.slice(1);

            menu.classList.add('hidden');

            updateButton.disabled = false;

            updateButton.classList.remove('text-gray-400', 'bg-gray-100', 'cursor-not-allowed');

            updateButton.classList.add('text-gray-900', 'bg-lime-400', 'hover:bg-lime-500');
        }

        // Close status menu when clicking outside
        document.addEventListener('click', function (event) {
            const menu = document.getElementById('statusMenu');

            if (!menu) {
                return;
            }

            const button = menu.previousElementSibling;

            if (!menu.contains(event.target) && !button.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });

        // ================================================================
        // Receipt Modal
        // ================================================================

        function openReceiptModal() {
            const modal = document.getElementById('receiptModal');

            modal.classList.remove('hidden');

            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');
        }

        function closeReceiptModal() {
            const modal = document.getElementById('receiptModal');

            modal.classList.add('hidden');

            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');
        }

        const receiptModal = document.getElementById('receiptModal');

        if (receiptModal) {
            receiptModal.addEventListener('click', function (e) {
                if (e.target === this) {
                    closeReceiptModal();
                }
            });
        }

        // Escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeReceiptModal();
            }
        });
    </script>

@endsection
