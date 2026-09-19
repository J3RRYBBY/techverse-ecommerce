@extends ('layouts.admin.app')

@section ('content')
    <div class="px-10 py-7">
        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-medium text-gray-800">Admin Management</h1>

            <p class="text-sm text-gray-500">Update administrator account information.</p>
        </div>

        {{-- Main Card --}}
        <div class="max-w-4xl overflow-hidden bg-white border border-gray-100 shadow-sm rounded-xl">
            {{-- Header --}}
            <div class="px-8 py-6 border-b border-gray-100 bg-gray-50/50">
                <div class="flex items-center gap-4">
                    {{-- Profile Icon --}}
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-lime-100">
                        <x-heroicon-o-user class="w-6 h-6 text-lime-500" />
                    </div>

                    <div>
                        <h2 class="text-xl font-medium text-gray-800">Edit Admin Account</h2>

                        <p class="text-sm text-gray-500">Update {{ $admin->name }}'s account information.</p>
                    </div>
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin#updateAdmin', $admin->id) }}" method="POST">
                @csrf
                @method ('PUT')

                <div class="px-8 py-8">
                    {{-- Personal Information --}}
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-5">
                            {{-- <div class="w-1 h-5 rounded-full bg-lime-400"></div> --}}

                            <h3 class="text-sm font-semibold tracking-wide text-gray-800 uppercase">
                                Personal Information
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block mb-1.5 text-sm font-medium text-gray-700"> Name </label>

                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    value="{{ old('name', $admin->name) }}"
                                    placeholder="Enter admin name"
                                    class="w-full px-4 py-2.5 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400
                                @error('name') outline outline-1 outline-red-500 @enderror"
                                />

                                @error ('name')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block mb-1.5 text-sm font-medium text-gray-700">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone', $admin->phone) }}"
                                    placeholder="Enter phone number"
                                    class="w-full px-4 py-2.5 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400
                                @error('phone') outline outline-1 outline-red-500 @enderror"
                                />

                                @error ('phone')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="md:col-span-2">
                                <label for="email" class="block mb-1.5 text-sm font-medium text-gray-700">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email', $admin->email) }}"
                                    placeholder="Enter email address"
                                    class="w-full px-4 py-2.5 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400
                                @error('email') outline outline-1 outline-red-500 @enderror"
                                />

                                @error ('email')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center gap-2 mb-5">
                            {{-- <div class="w-1 h-5 rounded-full bg-lime-400"></div> --}}

                            <div>
                                <h3 class="text-sm font-semibold tracking-wide text-gray-800 uppercase">
                                    Change Password
                                </h3>

                                <p class="text-xs text-gray-500">Leave these fields empty if you don't want to change the password.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            {{-- New Password --}}
                            <div>
                                <label for="password" class="block mb-1.5 text-sm font-medium text-gray-700">
                                    New Password
                                </label>

                                <div class="relative">
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        placeholder="Enter new password"
                                        class="w-full px-4 py-2.5 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400
                                    @error('password') outline outline-1 outline-red-500 @enderror"
                                    />

                                    <button
                                        type="button"
                                        id="togglePassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-700 focus:outline-none"
                                    >
                                        <x-heroicon-o-eye id="eyeIcon" class="size-5" />

                                        <x-heroicon-o-eye-slash id="eyeOffIcon" class="hidden size-5" />
                                    </button>
                                </div>

                                @error ('password')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label
                                    for="password_confirmation"
                                    class="block mb-1.5 text-sm font-medium text-gray-700"
                                >
                                    Confirm New Password
                                </label>

                                <div class="relative">
                                    <input
                                        id="repeatPassword"
                                        type="password"
                                        name="password_confirmation"
                                        placeholder="Confirm new password"
                                        class="w-full px-4 py-2.5 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                    />

                                    <button
                                        type="button"
                                        id="toggleRepeatPassword"
                                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-700 focus:outline-none"
                                    >
                                        <x-heroicon-o-eye id="repeatEyeIcon" class="size-5" />

                                        <x-heroicon-o-eye-slash id="repeatEyeOffIcon" class="hidden size-5" />
                                    </button>
                                </div>

                                @error ('password_confirmation')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div
                    class="flex flex-col-reverse gap-3 px-8 py-5 border-t border-gray-100 bg-gray-50/50 sm:flex-row sm:justify-end"
                >
                    <a
                        href="{{ route('admin#management') }}"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-gray-600 transition bg-white border border-gray-200 rounded-lg hover:bg-gray-50 hover:text-gray-800"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-gray-900 transition rounded-lg bg-lime-400 hover:bg-lime-500 focus:outline-none focus:ring-2 focus:ring-lime-200"
                    >
                        Update Admin
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Password Toggle --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            function setupPasswordToggle(inputId, buttonId, eyeId, eyeOffId) {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);
                const eye = document.getElementById(eyeId);
                const eyeOff = document.getElementById(eyeOffId);

                if (!input || !button || !eye || !eyeOff) {
                    return;
                }

                button.addEventListener('click', function () {
                    if (input.type === 'password') {
                        input.type = 'text';

                        eye.classList.add('hidden');
                        eyeOff.classList.remove('hidden');
                    } else {
                        input.type = 'password';

                        eye.classList.remove('hidden');
                        eyeOff.classList.add('hidden');
                    }
                });
            }

            setupPasswordToggle('password', 'togglePassword', 'eyeIcon', 'eyeOffIcon');

            setupPasswordToggle(
                'password_confirmation',
                'toggleConfirmPassword',
                'confirmEyeIcon',
                'confirmEyeOffIcon',
            );
        });
    </script> --}}

@endsection
