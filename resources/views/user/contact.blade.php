@extends ('layouts.user.app')

@section ('content')
    <div class="w-[92%] mx-auto pt-8 pb-16 sm:w-[90%] sm:pt-10 sm:pb-20 md:w-[85%] lg:w-[75%] xl:w-[70%]">
        {{-- Header --}}
        <div class="mb-8 text-center sm:mb-10 md:mb-12">
            <h1 class="text-3xl font-semibold text-gray-900 sm:text-4xl">Contact Us</h1>

            <p class="max-w-xl mx-auto mt-3 text-sm text-gray-500 sm:text-base">Have a question or need help? We'd love to hear from you.</p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-2 md:gap-10">
            {{-- Contact Information --}}
            <div class="p-5 rounded-xl bg-gray-50 sm:p-6 md:p-8">
                <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">Get in Touch</h2>

                <p class="mt-3 text-sm leading-7 text-gray-500 sm:text-base">If you have any questions about our products, orders, payments, or anything else, feel free to contact us.</p>

                <div class="mt-6 space-y-5 sm:mt-8 sm:space-y-6">
                    {{-- Email --}}
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full sm:w-11 sm:h-11 bg-lime-100"
                        >
                            <i class="text-sm text-lime-600 sm:text-base fa-solid fa-envelope"></i>
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-medium text-gray-900">Email</h3>

                            <p class="mt-1 text-sm text-gray-500 break-words">support@techverse.com</p>
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full sm:w-11 sm:h-11 bg-lime-100"
                        >
                            <i class="text-sm text-lime-600 sm:text-base fa-solid fa-phone"></i>
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-medium text-gray-900">Phone</h3>

                            <p class="mt-1 text-sm text-gray-500 break-words">+95 9 123 456 789</p>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full sm:w-11 sm:h-11 bg-lime-100"
                        >
                            <i class="text-sm text-lime-600 sm:text-base fa-solid fa-location-dot"></i>
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-medium text-gray-900">Address</h3>

                            <p class="mt-1 text-sm text-gray-500 break-words">Yangon, Myanmar</p>
                        </div>
                    </div>

                    {{-- Opening Hours --}}
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div
                            class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full sm:w-11 sm:h-11 bg-lime-100"
                        >
                            <i class="text-sm text-lime-600 sm:text-base fa-solid fa-clock"></i>
                        </div>

                        <div class="min-w-0">
                            <h3 class="font-medium text-gray-900">Office Hours</h3>

                            <p class="mt-1 text-sm leading-6 text-gray-500">Monday - Saturday, 9:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Form --}}
            <div class="p-5 bg-white border rounded-xl sm:p-6 md:p-8">
                <h2 class="text-xl font-semibold text-gray-900 sm:text-2xl">Send Us a Message</h2>

                <form
                    action="{{ route('user#contactStore') }}"
                    method="POST"
                    class="mt-5 space-y-4 sm:mt-6 sm:space-y-5"
                >
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700"> Name </label>

                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your name"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-lime-400 shadow-sm focus:ring-1 focus:ring-lime-400 @error('name') border-none outline outline-1 outline-red-500 @enderror"
                        />

                        @error ('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700"> Email </label>

                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-lime-400 shadow-sm focus:ring-1 focus:ring-lime-400 @error('email') border-none outline outline-1 outline-red-500 @enderror"
                        />

                        @error ('email')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subject --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700"> Subject </label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            placeholder="What is your message about?"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg focus:outline-none focus:border-lime-400 shadow-sm focus:ring-1 focus:ring-lime-400 @error('subject') border-none outline outline-1 outline-red-500 @enderror"
                        />

                        @error ('subject')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Message --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700"> Message </label>

                        <textarea
                            name="message"
                            rows="5"
                            placeholder="Write your message..."
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-lg resize-none focus:outline-none focus:border-lime-400 shadow-sm focus:ring-1 focus:ring-lime-400 @error('message') border-none outline outline-1 outline-red-500 @enderror"
                            >{{ old('message') }}</textarea
                        >

                        @error ('message')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full py-3 font-medium transition rounded-lg bg-lime-400 hover:bg-lime-500"
                    >
                        <i class="mr-2 fa-solid fa-paper-plane"></i>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if (session('contactSuccess'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    toast: true,
                    position: 'top',
                    icon: 'success',
                    title: @json (session('contactSuccess')),
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                });
            });
        </script>

    @endif
@endsection
