@extends ('layouts.user.app')

@section ('content')
    <div class="w-[90%] mx-auto pb-20 mt-6 sm:w-[85%] sm:mt-8 lg:w-[70%] lg:mt-10">
        <h1 class="text-2xl font-medium sm:text-3xl">Checkout</h1>

        <div class="grid grid-cols-1 gap-8 mt-6 lg:grid-cols-3 lg:gap-10">
            {{-- LEFT SIDE --}}
            <div class="lg:col-span-2">
                <form action="{{ route('user#placeOrder') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- shipping information --}}
                    <div class="">
                        <h2 class="text-lg">Shipping Information</h2>

                        <div class="mt-5">
                            <label class="block text-sm text-black/50"> Name </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', auth()->user()->name) }}"
                                class="w-full px-4 mt-2 border py-2.5 text-sm rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                required
                            />
                        </div>

                        <div class="flex flex-col items-center justify-between gap-0 sm:flex-row sm:gap-5">
                            <div class="w-full mt-5">
                                <label class="block text-sm text-black/50"> Email </label>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', auth()->user()->email) }}"
                                    class="w-full px-4 mt-2 border py-2.5 text-sm rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                    required
                                />
                            </div>

                            <div class="w-full mt-5">
                                <label class="block text-sm text-black/50"> Phone </label>

                                <input
                                    type="text"
                                    name="phone"
                                    class="w-full px-4 mt-2 border py-2.5 text-sm rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                    required
                                />
                            </div>
                        </div>

                        <div class="flex flex-col items-center justify-between gap-0 sm:flex-row sm:gap-5">
                            <div class="w-full mt-5 sm:w-2/3">
                                <label class="block text-sm text-black/50"> Address </label>

                                <input
                                    type="text"
                                    name="address"
                                    class="w-full px-4 mt-2 border py-2.5 text-sm rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                    required
                                />
                            </div>

                            <div class="w-full mt-5 sm:w-1/3">
                                <label class="block text-sm text-black/50"> City </label>

                                <input
                                    type="text"
                                    name="city"
                                    class="w-full px-4 mt-2 border py-2.5 text-sm rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                    required
                                />
                            </div>
                        </div>
                    </div>

                    {{-- Payment --}}
                    <div class="mt-10">
                        <h2 class="text-lg">Payment Method</h2>

                        <div class="grid grid-cols-2 gap-3 mt-5 sm:grid-cols-3 sm:gap-4 lg:grid-cols-4 lg:gap-5">
                            @foreach ($paymentMethods as $paymentMethod)
                                <label
                                    class="cursor-pointer payment-method"
                                    data-payment-id="{{ $paymentMethod->id }}"
                                    data-account-name="{{ $paymentMethod->account_name }}"
                                    data-account-number="{{ $paymentMethod->account_number }}"
                                >
                                    <input
                                        type="radio"
                                        name="payment_method_id"
                                        value="{{ $paymentMethod->id }}"
                                        class="hidden"
                                        required
                                    />

                                    <div
                                        class="flex items-center justify-center gap-3 py-3 transition border rounded-lg shadow-sm payment-card"
                                    >
                                        @if ($paymentMethod->logo)
                                            <img
                                                src="{{ $paymentMethod->logo }}"
                                                class="object-contain w-10 h-10"
                                                alt="{{ $paymentMethod->name }}"
                                            />
                                        @endif

                                        {{-- <span class="text-sm"> {{ $paymentMethod->name }} </span> --}}
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        {{-- Payment account information --}}
                        <div id="paymentAccountInfo" class="hidden p-4 mt-5 border rounded-lg">
                            <p class="text-sm text-black/50">Account Name</p>

                            <p id="accountName" class=""></p>

                            <p class="mt-3 text-sm text-black/50">Account Number</p>

                            <p id="accountNumber" class=""></p>
                        </div>

                        <div class="mt-10">
                            <label class="text-lg"> Payment Receipt </label>

                            <p class="mt-1 text-sm text-black/50">Upload a screenshot or photo of your payment receipt.</p>

                            <!-- Upload / Preview Container -->
                            <div
                                id="receipt-container"
                                class="relative flex flex-col items-center justify-center w-full mt-5 overflow-hidden transition duration-200 border rounded-lg h-72 sm:w-3/4 sm:h-80 lg:w-1/2 lg:h-96"
                            >
                                <!-- Upload UI -->
                                <label
                                    id="receipt-upload"
                                    for="payment_receipt"
                                    class="flex flex-col items-center justify-center w-full h-full px-4 text-center cursor-pointer"
                                >
                                    <i class="text-3xl text-gray-400 fa-solid fa-image"></i>

                                    <p class="text-sm font-medium text-gray-700">Click to upload your receipt</p>

                                    <p class="mt-1 text-xs text-gray-500">JPG, PNG or WEBP · Max 5MB</p>
                                </label>

                                <!-- File Input -->
                                <input
                                    type="file"
                                    name="payment_receipt"
                                    id="payment_receipt"
                                    accept="image/jpeg,image/png,image/webp,image/avif"
                                    required
                                    class="hidden"
                                />

                                <!-- Receipt Preview -->
                                <div id="receipt-preview" class="hidden w-full h-full p-3">
                                    <div class="flex flex-col w-full h-full">
                                        <!-- Header -->
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-sm font-medium text-gray-900">Selected Receipt</p>

                                            <button
                                                type="button"
                                                id="remove-receipt"
                                                class="flex items-center justify-center w-6 h-6 text-xs transition rounded-full bg-lime-400 hover:bg-lime-500"
                                            >
                                                <i class="fa-solid fa-x"></i>
                                            </button>
                                        </div>

                                        <!-- Image -->
                                        <div class="flex-1 min-h-0">
                                            <img
                                                id="receipt-image"
                                                src=""
                                                alt="Payment receipt preview"
                                                class="object-contain w-full h-full bg-white rounded-lg"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @error ('payment_receipt')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 mt-6 text-sm font-medium rounded bg-lime-400">
                        Place Order
                    </button>
                </form>
            </div>

            {{-- RIGHT SIDE --}}
            <div class="lg:col-span-1">
                <div class="p-4 border sm:p-6">
                    <h2 class="text-lg font-medium">Order Summary</h2>

                    <div class="mt-5">
                        @foreach ($cart as $item)
                            <div class="flex gap-3 py-4 border-b sm:gap-4">
                                {{-- Product Image --}}
                                <div class="w-16 h-16 shrink-0 sm:w-20 sm:h-20">
                                    @if ($item->variant->images->first()?->image)
                                        <img
                                            src="{{ $item->variant->images->first()->image }}"
                                            alt="{{ $item->variant->product->name }}"
                                            class="object-cover w-full h-full rounded-lg"
                                        />
                                    @else
                                        <img
                                            src="{{ asset('defaultImage/product.png') }}"
                                            alt="Product image"
                                            class="object-cover w-full h-full rounded-lg"
                                        />
                                    @endif
                                </div>

                                {{-- Product Information --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium truncate">{{ $item->variant->product->name }}</h3>

                                    <p class="mt-1 text-xs text-black/50">
                                        {{ $item->variant->capacity }} / {{ $item->variant->color }}
                                    </p>

                                    {{-- <p class="mt-2 text-sm">{{ number_format($item->variant->price) }} MMK</p> --}}

                                    <p class="mt-3 text-xs sm:mt-5">Qty: {{ $item->quantity }}</p>
                                </div>

                                {{-- Item Total --}}
                                <div class="text-xs font-medium whitespace-nowrap sm:text-sm">
                                    {{ number_format($item->variant->price) }} MMK
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6 space-y-3">
                        <div class="flex justify-between gap-4">
                            <span class="text-sm text-black/60"> Subtotal </span>

                            <span class="text-sm font-medium text-right"> {{ number_format($subtotal) }} MMK </span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-sm text-black/60"> Shipping </span>

                            <span class="text-sm font-medium text-right"> {{ number_format($shippingFee) }} MMK </span>
                        </div>

                        <div class="flex justify-between gap-4 pt-4 font-medium border-t">
                            <span> Total </span>

                            <span class="text-right"> {{ number_format($total) }} MMK </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const paymentMethods = document.querySelectorAll('.payment-method');

        const paymentAccountInfo = document.getElementById('paymentAccountInfo');
        const accountName = document.getElementById('accountName');
        const accountNumber = document.getElementById('accountNumber');

        paymentMethods.forEach((method) => {
            method.addEventListener('click', function () {
                // Remove selected style from all cards
                paymentMethods.forEach((item) => {
                    item.querySelector('.payment-card').classList.remove('ring-1', 'ring-lime-400', 'border-lime-400');
                });

                // Add selected style
                this.querySelector('.payment-card').classList.add('ring-1', 'ring-lime-400', 'border-lime-400');

                // Get account information
                const name = this.dataset.accountName;
                const number = this.dataset.accountNumber;

                // Show account information
                accountName.textContent = name;
                accountNumber.textContent = number;

                paymentAccountInfo.classList.remove('hidden');
            });
        });
    </script>

    <script>
        const receiptInput = document.getElementById('payment_receipt');
        const receiptUpload = document.getElementById('receipt-upload');
        const receiptPreview = document.getElementById('receipt-preview');
        const receiptImage = document.getElementById('receipt-image');
        const removeReceipt = document.getElementById('remove-receipt');
        const receiptContainer = document.getElementById('receipt-container');

        receiptInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                receiptImage.src = e.target.result;

                receiptContainer.classList.remove('h-96');
                receiptContainer.classList.add('h-auto');

                receiptUpload.classList.add('hidden');
                receiptPreview.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        });

        removeReceipt.addEventListener('click', function () {
            receiptInput.value = '';
            receiptImage.src = '';

            receiptContainer.classList.remove('h-auto');
            receiptContainer.classList.add('h-96');

            receiptPreview.classList.add('hidden');
            receiptUpload.classList.remove('hidden');
        });
    </script>

@endsection
