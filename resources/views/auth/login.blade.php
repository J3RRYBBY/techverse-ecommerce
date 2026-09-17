@extends ('layouts.user.guest')

@section ('content')
    <div class="flex items-center justify-center min-h-screen">
        <div class="flex flex-col w-1/4 pt-10 pb-20 bg-white shadow px-7">
            <div class="mx-auto">
                <a href="" class="text-2xl font-gugi"
                    >tech<span class="font-bold text-lime-400">V</span>erse</a
                >
            </div>

            <div class="mt-10">
                <h3 class="text-3xl font-medium uppercase">Welcome Back</h3>

                <p class="text-sm text-black/50">Please enter your details</p>

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="flex flex-col mt-8">
                        <label for="email" class="mb-1 text-sm">Email</label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            {{-- w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error("variants.$index.capacity") outline outline-1 outline-red-500 @enderror --}}
                            class="px-4 py-2.5 text-sm rounded-lg shadow-sm w-full border focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('email') outline outline-1 outline-red-500 @enderror"
                        />
                        @error ('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <label for="password" class="mt-5 mb-1 text-sm">Password</label>
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
                                <x-heroicon-o-eye-slash
                                    id="eyeOffIcon"
                                    class="hidden text-gray-600 size-5"
                                />
                            </button>
                        </div>
                        @error ('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <a href="" class="flex justify-end mt-3 text-sm text-blue-400"
                        >Forgot Password?</a
                    >

                    <button
                        class="w-full px-3 py-2.5 mt-3 text-sm text-black rounded-sm bg-lime-400"
                    >
                        Sign Up
                    </button>
                </form>

                <p class="flex items-baseline justify-center gap-2 mt-3 text-xs">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-sm text-blue-400">Register</a>
                </p>

                <div class="inline-flex items-center justify-center w-full">
                    <hr class="w-full h-px my-8 border-0 bg-black/30" />
                    <span class="absolute px-3 text-sm bg-white">or</span>
                </div>

                <a href="{{ route('social#redirect', 'google') }}">
                    <button
                        class="w-full px-4 py-2.5 mb-5 text-sm text-black border border-gray-300 rounded-sm"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('icons/google.png') }}" alt="" class="size-5" />
                            Login with Google
                        </div>
                    </button>
                </a>

                <a href="{{ route('social#redirect', 'github') }}">
                    <button
                        class="w-full px-4 py-2.5 text-sm text-black border border-gray-300 rounded-sm"
                    >
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('icons/github.png') }}" alt="" class="size-5" />
                            Login with Github
                        </div>
                    </button>
                </a>
            </div>
        </div>
    </div>
@endsection
