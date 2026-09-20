@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        <div class="flex items-center gap-4 pb-3">
            {{-- Back --}}
            <a
                href="{{ route('admin#payment') }}"
                class="flex items-center justify-center w-10 h-10 text-gray-600 transition bg-white border border-gray-200 rounded-lg hover:bg-gray-100"
            >
                <i class="text-sm fa-solid fa-arrow-left"></i>
            </a>
            <div class="">
                <h1 class="text-2xl font-medium text-gray-800">Edit Payment Method</h1>

                <p class="text-sm text-black/60">Update payment information.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="p-4 mb-5 text-red-700 bg-red-100 rounded-lg">
                <ul class="pl-5 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>

                    @endforeach
                </ul>
            </div>

        @endif

        <form
            action="{{ route('admin#updatePayment', $paymentMethod->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="p-6 bg-white shadow rounded-xl"
        >
            @csrf
            @method ('PUT')

            <div class="mb-5">
                <label class="mt-4 text-sm"> Payment Method Name </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name', $paymentMethod->name) }}"
                    class="w-full px-4 py-2.5 mt-2 text-sm rounded-lg shadow-sm border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('name') outline outline-1 outline-red-500 @enderror"
                />

                @error ('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-2 gap-5">
                <div class="">
                    <label class="mt-4 text-sm"> Account Holder Name </label>

                    <input
                        type="text"
                        name="account_name"
                        value="{{ old('account_name', $paymentMethod->account_name) }}"
                        class="w-full px-4 py-2.5 mt-2 text-sm rounded-lg shadow-sm border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('account_name') outline outline-1 outline-red-500 @enderror"
                    />

                    @error ('account_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="">
                    <label class="mt-4 text-sm"> Account Number </label>

                    <input
                        type="text"
                        name="account_number"
                        value="{{ old('account_number', $paymentMethod->account_number) }}"
                        class="w-full px-4 py-2.5 mt-2 text-sm rounded-lg shadow-sm border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('account_number') outline outline-1 outline-red-500 @enderror"
                    />

                    @error ('account_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Payment Logo --}}
            <div class="mt-4">
                <label class="block mb-2 text-sm"> Payment Logo </label>

                <div class="flex items-center gap-4">
                    {{-- Logo Preview --}}
                    <div
                        id="logoPreview"
                        class="flex items-center justify-center w-24 h-24 overflow-hidden border rounded-lg shrink-0"
                    >
                        @if ($paymentMethod->logo)
                            <img
                                id="logoImage"
                                src="{{ $paymentMethod->logo }}"
                                alt="{{ $paymentMethod->name }} Logo"
                                class="object-contain w-full h-full p-2"
                            />
                        @else
                            <div id="logoPlaceholder" class="text-center text-gray-400">
                                <svg class="w-8 h-8 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 015.828 0L20 17
                                 m-2-2l-1.586-1.586a2 2 0 00-2.828 0L9 18
                                 m12-5V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12
                                 a2 2 0 002 2h14a2 2 0 002-2v-5"
                                    />
                                </svg>

                                <span class="text-xs">No logo</span>
                            </div>
                        @endif
                    </div>

                    {{-- Upload --}}
                    <div class="flex-1">
                        <label
                            for="logo"
                            class="flex flex-col items-center justify-center w-full h-24 px-5 transition border rounded-lg cursor-pointer"
                        >
                            <svg
                                class="mb-1 text-gray-400 w-7 h-7"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="1.5"
                                    d="M7 16a4 4 0 01-.88-7.903
                             A5.002 5.002 0 0116.9 6H17
                             a5 5 0 011 9.9
                             M15 13l-3-3m0 0l-3 3m3-3v9"
                                />
                            </svg>

                            <p class="text-sm font-medium text-gray-700">Choose new logo</p>

                            <p class="text-xs text-gray-400">PNG, JPG or WEBP · Max 2MB</p>

                            <input
                                id="logo"
                                type="file"
                                name="logo"
                                accept="image/png,image/jpeg,image/webp"
                                class="hidden"
                                onchange="previewLogo(event)"
                            />
                        </label>

                        @error ('logo')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <script>
                function previewLogo(event) {
                    const input = event.target;
                    const preview = document.getElementById('logoPreview');

                    if (input.files && input.files[0]) {
                        const file = input.files[0];

                        // Create image preview
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            preview.innerHTML = `
                    <img
                        src="${e.target.result}"
                        alt="New Logo Preview"
                        class="object-contain w-full h-full p-2"
                    >
                `;
                        };

                        reader.readAsDataURL(file);
                    }
                }
            </script>

            {{-- Active Status --}}
            <div class="flex items-center justify-between p-4 mt-5 border border-gray-200 rounded-xl">
                <div>
                    <p class="text-sm font-medium text-gray-800">Active</p>

                    <p class="mt-1 text-xs text-gray-500">Customers can use this payment method</p>
                </div>

                <label class="relative inline-flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="sr-only peer"
                        {{
                            old('is_active', $paymentMethod->is_active)
                                ? 'checked'
                                : ''
                        }}
                    />

                    <div
                        class="w-11 h-6 bg-gray-200 rounded-full peer-focus:ring-4 peer-focus:ring-gray-100 peer-checked:bg-green-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:w-5 after:h-5 after:bg-white after:border after:border-gray-300 after:rounded-full after:transition-all peer-checked:after:translate-x-full peer-checked:after:border-white"
                    ></div>
                </label>
            </div>

            <div class="flex gap-3 mt-8">
                <a
                    href="{{ route('admin#payment') }}"
                    class="px-6 py-3 text-sm duration-200 bg-gray-100 rounded-full hover:bg-gray-200"
                >
                    Cancel
                </a>

                <button type="submit" class="px-6 py-3 text-sm duration-200 rounded-full bg-lime-400 hover:bg-lime-500">
                    Update Payment
                </button>
            </div>
        </form>
    </div>

@endsection
