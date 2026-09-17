@extends ('layouts.admin.app')

@section ('content')
    <div class="px-6 py-7 lg:px-10">
        <form action="{{ route('admin#updateProfile') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="">
                {{-- Page Header --}}
                <div class="mb-7">
                    <h1 class="text-2xl font-semibold text-gray-900">Profile</h1>

                    <p class="mt-1 text-sm text-gray-500">Manage your account information and profile photo.</p>
                </div>

                {{-- Profile Card --}}
                <div class="overflow-hidden bg-white border border-gray-100 shadow-sm rounded-2xl">
                    {{-- Profile Header --}}
                    <div class="px-6 border-b border-gray-100 py-7 sm:px-8">
                        <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                            {{-- Profile Image --}}
                            <div class="relative shrink-0">
                                <img
                                    id="profilePreview"
                                    src="{{ $admin->profile ?? asset('defaultImage/user.png') }}"
                                    alt="Profile"
                                    class="object-cover w-24 h-24 border-4 border-white rounded-full shadow-md ring-1 ring-gray-200"
                                />

                                {{-- Camera Button --}}
                                <label
                                    for="profile"
                                    class="absolute bottom-0 right-0 flex items-center justify-center w-8 h-8 transition duration-200 border-2 border-white rounded-full cursor-pointer bg-lime-400 hover:bg-lime-500"
                                >
                                    <x-heroicon-o-camera class="w-4 h-4 text-gray-900" />
                                </label>

                                <input type="file" name="profile" id="profile" accept="image/*" class="hidden" />
                            </div>

                            {{-- User Information --}}
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h2 class="text-xl font-semibold text-gray-900">{{ $admin->name }}</h2>

                                    <span
                                        class="px-2.5 py-1 text-[11px] font-medium rounded-full bg-lime-100 text-lime-700"
                                    >
                                        {{ ucfirst($admin->role) }}
                                    </span>
                                </div>

                                <p class="mt-1 text-sm text-gray-500">{{ $admin->email }}</p>

                                <p class="mt-3 text-xs text-gray-400">Click the camera icon to change your profile photo.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Personal Information --}}
                    <div class="px-6 py-7 sm:px-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-base font-semibold text-gray-900">Personal Information</h3>

                                <p class="mt-1 text-xs text-gray-500">Update your personal account details.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                            {{-- Name --}}
                            <div>
                                <label for="name" class="block mb-2 text-sm font-medium text-gray-700"> Name </label>

                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                        <x-heroicon-o-user class="w-5 h-5 text-gray-400" />
                                    </span>

                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        value="{{ old('name', $admin->name) }}"
                                        placeholder="Enter your name"
                                        class="px-4 py-2.5 pl-11 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('name') outline outline-1 outline-red-500 @enderror"
                                    />
                                </div>

                                @error ('name')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-700"> Email </label>

                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                                        <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
                                    </span>

                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value="{{ old('email', $admin->email) }}"
                                        placeholder="Enter your email"
                                        class="px-4 py-2.5 pl-11 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('email') outline outline-1 outline-red-500 @enderror"
                                    />
                                </div>

                                @error ('email')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block mb-2 text-sm font-medium text-gray-700">
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
                                        value="{{ old('phone', $admin->phone) }}"
                                        placeholder="Enter your phone number"
                                        class="px-4 py-2.5 pl-11 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('phone') outline outline-1 outline-red-500 @enderror"
                                    />
                                </div>

                                @error ('phone')
                                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Role --}}
                            <div>
                                <label for="role" class="block mb-2 text-sm font-medium text-gray-700"> Role </label>

                                <div class="relative">
                                    <i
                                        class="absolute text-sm text-gray-400 -translate-y-1/2 left-4 top-1/2 fa-solid fa-shield-halved"
                                    ></i>

                                    <input
                                        type="text"
                                        id="role"
                                        value="{{ ucfirst($admin->role) }}"
                                        disabled
                                        class="w-full px-4 py-2.5 text-sm shadow-sm text-gray-500 border border-gray-200 rounded-lg outline-none cursor-not-allowed pl-11 bg-gray-50"
                                    />
                                </div>

                                <p class="mt-1.5 text-xs text-gray-400">Your account role cannot be changed here.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div
                        class="flex flex-col-reverse gap-3 px-6 py-5 border-t border-gray-100 sm:flex-row sm:items-center sm:justify-end sm:px-8"
                    >
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3 text-sm font-medium text-gray-900 transition duration-200 rounded-lg bg-lime-400 hover:bg-lime-500"
                        >
                            <i class="text-xs fa-solid fa-check"></i>
                            Save Changes
                        </button>
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
                    title: 'Success',
                    text: @json (session('success')),
                    confirmButtonColor: '#84cc16',
                });
            });
        </script>
    @endif

    {{-- Profile Image Preview --}}
    <script>
        document.getElementById('profile').addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (!file) {
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
