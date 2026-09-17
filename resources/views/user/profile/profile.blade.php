@extends ('layouts.user.app')

@section ('content')
    <div class="mt-10 w-[70%] mx-auto pb-20">
        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-semibold text-gray-900">Account Settings</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your profile information and account details.</p>
        </div>

        <form action="{{ route('user#updateProfile') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                {{-- Left Profile Card --}}
                <div class="lg:col-span-1">
                    <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                        <div class="mb-6">
                            <h2 class="text-base font-semibold text-gray-900">Profile Photo</h2>

                            <p class="mt-1 text-xs leading-5 text-gray-500">Update your profile picture.</p>
                        </div>

                        {{-- Profile Image --}}
                        <div class="flex flex-col items-center">
                            <div class="relative">
                                <img
                                    id="profilePreview"
                                    src="{{ $user->profile ?? asset('defaultImage/user.png') }}"
                                    alt="Profile"
                                    class="object-cover border-4 border-white rounded-full shadow-md w-28 h-28 ring-1 ring-gray-200"
                                />

                                {{-- Camera Button --}}
                                <label
                                    for="profile"
                                    class="absolute bottom-0 right-0 flex items-center justify-center text-gray-900 transition duration-200 rounded-full shadow-sm cursor-pointer w-9 h-9 bg-lime-400 hover:bg-lime-500"
                                >
                                    <x-heroicon-o-camera class="w-5 h-5" />
                                </label>

                                <input type="file" name="profile" id="profile" accept="image/*" class="hidden" />
                            </div>

                            <h3 class="mt-4 text-base font-semibold text-gray-900">{{ $user->name }}</h3>
                        </div>

                        {{-- Photo Info --}}
                        <div class="p-4 mt-6 rounded-xl bg-gray-50">
                            <div class="flex gap-3">
                                <div
                                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg bg-lime-100"
                                >
                                    <x-heroicon-o-information-circle class="w-5 h-5 text-lime-600" />
                                </div>

                                <div>
                                    <p class="text-xs font-medium text-gray-700">Profile photo</p>

                                    <p class="mt-1 text-[11px] leading-4 text-gray-500">JPG, JPEG, PNG or WEBP. Choose a clear square image.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Information Card --}}
                <div class="lg:col-span-2">
                    <div class="p-6 bg-white border border-gray-100 shadow-sm rounded-2xl">
                        {{-- Card Header --}}
                        <div class="flex items-center justify-between pb-5 mb-6 border-b border-gray-100">
                            <div>
                                <h2 class="text-base font-semibold text-gray-900">Personal Information</h2>

                                <p class="mt-1 text-xs text-gray-500">Update your personal details below.</p>
                            </div>

                            <div class="flex items-center justify-center rounded-lg w-9 h-9 bg-lime-100">
                                <x-heroicon-o-user class="w-5 h-5 text-lime-600" />
                            </div>
                        </div>

                        {{-- Form --}}
                        <div class="space-y-5">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block mb-1.5 text-xs font-medium text-gray-700">
                                    Full Name
                                </label>

                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                        <x-heroicon-o-user class="w-5 h-5 text-gray-400" />
                                    </span>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        placeholder="Enter your name"
                                        class="px-4 py-2.5 pl-11 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('name') outline outline-1 outline-red-500  @enderror"
                                    />
                                </div>

                                @error ('name')
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block mb-1.5 text-xs font-medium text-gray-700">
                                    Email Address
                                </label>

                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                        <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
                                    </span>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $user->email) }}"
                                        placeholder="Enter your email"
                                        class="px-4 py-2.5 pl-11 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('email') outline outline-1 outline-red-500  @enderror"
                                    />
                                </div>

                                @error ('email')
                                    <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone + Address --}}
                            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                                {{-- Phone --}}
                                <div>
                                    <label for="phone" class="block mb-1.5 text-xs font-medium text-gray-700">
                                        Phone Number
                                    </label>

                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                            <x-heroicon-o-phone class="w-5 h-5 text-gray-400" />
                                        </span>

                                        <input
                                            type="text"
                                            id="phone"
                                            name="phone"
                                            value="{{ old('phone', $user->phone) }}"
                                            placeholder="Enter phone number"
                                            class="px-4 py-2.5 pl-11 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('phone') outline outline-1 outline-red-500  @enderror"
                                        />
                                    </div>

                                    @error ('phone')
                                        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Address --}}
                                <div>
                                    <label for="address" class="block mb-1.5 text-xs font-medium text-gray-700">
                                        Address
                                    </label>

                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                            <x-heroicon-o-map-pin class="w-5 h-5 text-gray-400" />
                                        </span>

                                        <input
                                            type="text"
                                            id="address"
                                            name="address"
                                            value="{{ old('address', $user->address) }}"
                                            placeholder="Enter your address"
                                            class="px-4 py-2.5 pl-11 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                                        />
                                    </div>
                                </div>
                            </div>

                            {{-- Account Role --}}
                            {{-- <div>
                                <label class="block mb-1.5 text-xs font-medium text-gray-700"> Account Type </label>

                                <div
                                    class="flex items-center justify-between px-4 py-3 border border-gray-100 rounded-xl bg-gray-50"
                                >
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center rounded-lg w-9 h-9 bg-lime-100">
                                            <x-heroicon-o-shield-check class="w-5 h-5 text-lime-600" />
                                        </div>

                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ ucfirst($user->role) }}</p>

                                            <p class="text-[11px] text-gray-500">Your current account role</p>
                                        </div>
                                    </div>

                                    <span
                                        class="px-2.5 py-1 text-[10px] font-medium rounded-full bg-lime-100 text-lime-700"
                                    >
                                        Active
                                    </span>
                                </div>
                            </div> --}}
                        </div>

                        {{-- Footer --}}
                        <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-100">
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-gray-900 transition duration-200 rounded-xl bg-lime-400 hover:bg-lime-500 focus:outline-none focus:ring-2 focus:ring-lime-200"
                            >
                                <x-heroicon-o-check class="w-4 h-4" />
                                Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Success Alert --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Profile Updated',
                    text: @json (session('success')),
                    confirmButtonColor: '#84cc16',
                    confirmButtonText: 'Done',
                    customClass: {
                        popup: 'rounded-2xl',
                        confirmButton: 'rounded-lg px-5 py-2.5',
                    },
                });
            });
        </script>
    @endif

    {{-- Profile Preview --}}
    <script>
        document.getElementById('profile').addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (!file) {
                return;
            }

            // Only preview images
            if (!file.type.startsWith('image/')) {
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {
                document.getElementById('profilePreview').src = e.target.result;
            };

            reader.readAsDataURL(file);
        });
    </script>
@endsection
