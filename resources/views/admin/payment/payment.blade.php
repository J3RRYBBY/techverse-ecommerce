@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        <div class="flex items-center justify-between pb-3">
            <div>
                <h1 class="text-2xl font-medium text-gray-800">Payment Methods</h1>

                <p class="text-sm text-black/60">Manage payment methods available during checkout.</p>
            </div>

            <a
                href="{{ route('admin#createPayment') }}"
                class="px-6 py-3 text-sm duration-200 rounded-full bg-lime-400 hover:bg-lime-500"
            >
                + Add Payment Method
            </a>
        </div>

        @if (session('createSuccess'))
            <div class="p-4 mb-5 text-green-700 bg-green-100 rounded-lg">{{ session('createSuccess') }}</div>
        @endif

        @if (session('updateSuccess'))
            <div class="p-4 mb-5 text-green-700 bg-green-100 rounded-lg">{{ session('updateSuccess') }}</div>
        @endif

        @if (session('deleteSuccess'))
            <div class="p-4 mb-5 text-green-700 bg-green-100 rounded-lg">{{ session('deleteSuccess') }}</div>
        @endif

        <div class="overflow-x-auto">
            <div class="min-h-screen py-2 bg-white rounded-lg">
                <div class="px-2">
                    <div
                        class="grid grid-cols-5 px-8 text-sm font-medium tracking-widest uppercase bg-gray-100 rounded-md text-black/70"
                    >
                        <div class="px-6 py-3">Payment</div>

                        <div class="px-6 py-3">Account</div>

                        <div class="px-6 py-3">logo</div>

                        <div class="px-6 py-3">Status</div>

                        <div class="px-6 py-3">Action</div>
                    </div>

                    <div>
                        @forelse ($paymentMethods as $payment)
                            <div class="grid items-center grid-cols-5 px-8 text-sm font-medium border-b">
                                <div class="px-6 py-3">
                                    <div class="font-medium">{{ $payment->name }}</div>
                                </div>

                                <div class="px-6 py-3">
                                    <div>{{ $payment->account_name }}</div>

                                    <div class="text-sm text-black/60">{{ $payment->account_number }}</div>
                                </div>

                                <div class="px-4 py-3">
                                    @if ($payment->logo)
                                        <img
                                            src="{{ $payment->logo }}"
                                            alt="{{ $payment->name }}"
                                            class="object-cover w-16 h-16"
                                        />

                                    @else
                                        <span class="text-sm text-black/40"> No logo </span>
                                    @endif
                                </div>

                                <div class="px-6 py-3">
                                    @if ($payment->is_active)
                                        <span class="px-4 py-1 text-xs text-green-700 bg-green-100 rounded-full">
                                            Active
                                        </span>

                                    @else
                                        <span class="px-3 py-1 text-xs text-gray-500 bg-gray-100 rounded-full">
                                            Inactive
                                        </span>

                                    @endif
                                </div>

                                <div class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        {{-- Edit --}}
                                        <a
                                            href="{{ route('admin#editPayment', $payment->id) }}"
                                            class="inline-flex items-center justify-center text-gray-600 transition bg-gray-100 rounded-lg w-9 h-9 hover:bg-gray-200"
                                            title="Edit"
                                        >
                                            <x-feathericon-edit class="w-5 h-5" />
                                        </a>

                                        {{-- Toggle --}}
                                        <form action="{{ route('admin#togglePayment', $payment->id) }}" method="POST">
                                            @csrf
                                            @method ('PATCH')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center text-blue-600 transition bg-blue-100 rounded-lg w-9 h-9 hover:bg-blue-200"
                                                title="{{ $payment->is_active ? 'Deactivate' : 'Activate' }}"
                                            >
                                                @if ($payment->is_active)
                                                    <x-heroicon-o-eye class="w-5 h-5" />
                                                @else
                                                    <x-heroicon-o-eye-slash class="w-5 h-5" />
                                                @endif
                                            </button>
                                        </form>

                                        {{-- Delete --}}
                                        <form
                                            action="{{ route('admin#deletePayment', $payment->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this payment method?');"
                                        >
                                            @csrf
                                            @method ('DELETE')

                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center text-red-600 transition bg-red-100 rounded-lg w-9 h-9 hover:bg-red-200"
                                                title="Delete"
                                            >
                                                <x-heroicon-o-trash class="w-5 h-5" />
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="px-6 py-16">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div
                                        class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full"
                                    >
                                        <i class="text-xl text-gray-400 fa-solid fa-credit-card"></i>
                                    </div>

                                    <h3 class="font-medium text-gray-700">No payment methods found</h3>

                                    <p class="mt-1 text-sm text-gray-400">Start by adding your first payment method.</p>

                                    <a
                                        href="{{ route('admin#createPayment') }}"
                                        class="inline-flex items-center gap-2 px-4 py-2 mt-5 text-sm font-medium transition rounded-lg bg-lime-400 hover:bg-lime-500"
                                    >
                                        <i class="text-xs fa-solid fa-plus"></i>
                                        Add Payment Method
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
