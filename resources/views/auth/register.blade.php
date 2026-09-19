@extends ('layouts.user.guest')

@section ('content')
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex flex-col w-1/4 pt-10 pb-20 bg-white shadow px-7">
            <div class="mx-auto">
                <a href="{{ route('user#home') }}" class="text-2xl font-gugi"
                    >tech<span class="font-bold text-lime-400">V</span>erse</a
                >
            </div>

            <div class="mt-10">
                <h3 class="text-3xl font-medium uppercase">Create An Account</h3>

                <p class="text-sm text-black/50">Please enter your details</p>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="flex flex-col mt-8">
                        <label for="name" class="mb-1 text-sm">Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your name"
                            class="px-4 py-2.5 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('name') outline outline-1 outline-red-500  @enderror"
                        />
                        @error ('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <label for="phone" class="mt-3 mb-1 text-sm">Phone Number</label>
                        <input
                            type="text"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="Enter your phone"
                            class="px-4 py-2.5 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('phone') outline outline-1 outline-red-500 @enderror"
                        />
                        @error ('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <label for="email" class="mt-3 mb-1 text-sm">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            class="px-4 py-2.5 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('email') outline outline-1 outline-red-500 @enderror"
                        />
                        @error ('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <label for="password" class="mt-3 mb-1 text-sm">Password</label>
                        <div class="relative">
                            <input
                                id="password"
                                type="password"
                                name="password"
                                placeholder="Enter your password"
                                class="px-4 py-2.5 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('password') outline outline-1 outline-red-500 @enderror"
                            />
                            <button
                                type="button"
                                id="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                            >
                                <x-heroicon-o-eye id="eyeIcon" class="text-gray-600 size-5" />
                                <x-heroicon-o-eye-slash id="eyeOffIcon" class="hidden text-gray-600 size-5" />
                            </button>
                        </div>
                        @error ('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <label for="repeatPassword" class="mt-3 mb-1 text-sm">Repeat Password</label>
                        <div class="relative">
                            <input
                                id="repeatPassword"
                                type="password"
                                name="password_confirmation"
                                placeholder="Confirm your password"
                                class="px-4 py-2.5 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('password_confirmation') outline outline-1 outline-red-500 @enderror"
                            />
                            <button
                                type="button"
                                id="toggleRepeatPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 focus:outline-none"
                            >
                                <x-heroicon-o-eye id="repeatEyeIcon" class="text-gray-600 size-5" />
                                <x-heroicon-o-eye-slash id="repeatEyeOffIcon" class="hidden text-gray-600 size-5" />
                            </button>
                        </div>
                        @error ('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full px-4 py-2.5 mt-5 text-sm text-black rounded-sm bg-lime-400">
                        Create Account
                    </button>
                </form>

                <p class="flex items-baseline justify-center gap-2 mt-3 text-xs">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-sm text-blue-400">Login</a>
                </p>
            </div>
        </div>
    </div>
@endsection
