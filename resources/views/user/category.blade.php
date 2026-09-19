@extends ('layouts.user.app')

@section ('content')
    <div class="w-[92%] mx-auto pt-6 pb-16 sm:w-[90%] sm:pt-8 sm:pb-20 md:w-[85%] lg:w-[80%] xl:w-[70%]">
        {{-- Page Header --}}
        <div class="mb-8 text-center sm:mb-10">
            <p class="text-xs font-medium tracking-wide uppercase sm:text-sm text-lime-600">Explore techVerse</p>

            <h1 class="mt-2 text-2xl font-semibold text-gray-800 sm:text-3xl md:text-4xl">Shop by Category</h1>

            <p class="max-w-xl px-3 mx-auto mt-3 text-xs leading-5 text-gray-500 sm:text-sm sm:leading-6">Find the products you are looking for by browsing our categories.</p>
        </div>

        {{-- Categories --}}
        @if ($categories->count())
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-3 lg:gap-6">
                {{-- All Products --}}
                <a href="{{ route('user#productList') }}" class="group">
                    <div
                        class="overflow-hidden duration-300 bg-white border border-gray-100 rounded-xl hover:-translate-y-1 hover:shadow-xl"
                    >
                        <div
                            class="flex items-center justify-center h-56 p-6 bg-gray-50 sm:h-64 sm:p-7 md:h-72 lg:h-80"
                        >
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="flex items-center justify-center w-16 h-16 duration-300 bg-gray-200 rounded-full sm:w-20 sm:h-20 group-hover:bg-lime-400"
                                >
                                    <i class="text-2xl duration-300 sm:text-3xl fa-solid fa-layer-group"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between px-4 py-4 sm:px-5 sm:py-5 md:px-6">
                            <div class="min-w-0">
                                <h2 class="text-base font-medium text-gray-800 truncate sm:text-lg">All Products</h2>

                                <p class="mt-1 text-xs text-gray-400">Explore all products</p>
                            </div>

                            <div
                                class="flex items-center justify-center flex-shrink-0 ml-3 duration-200 bg-gray-100 rounded-full w-9 h-9 sm:w-10 sm:h-10 group-hover:bg-lime-400"
                            >
                                <i
                                    class="text-xs duration-200 sm:text-sm fa-solid fa-arrow-right group-hover:translate-x-0.5"
                                ></i>
                            </div>
                        </div>
                    </div>
                </a>

                @foreach ($categories as $category)
                    <a href="{{ route('user#productList', ['category' => $category->id]) }}" class="group">
                        <div
                            class="relative overflow-hidden duration-300 border border-gray-100 bg-gray-50 rounded-xl hover:-translate-y-1 hover:shadow-xl"
                        >
                            {{-- Category Image --}}
                            <div
                                class="flex items-center justify-center h-56 p-5 overflow-hidden sm:h-64 sm:p-6 md:h-72 lg:h-80"
                            >
                                @if ($category->image)
                                    <img
                                        src="{{ $category->image }}"
                                        alt="{{ $category->name }}"
                                        class="object-contain w-full h-full duration-500 group-hover:scale-105"
                                    />

                                @else
                                    <div class="flex flex-col items-center justify-center text-gray-300">
                                        <i class="text-4xl fa-solid fa-image sm:text-5xl"></i>

                                        <span class="mt-2 text-xs sm:text-sm"> No Image </span>
                                    </div>
                                @endif
                            </div>

                            {{-- Category Information --}}
                            <div class="flex items-center justify-between px-4 py-4 bg-white sm:px-5 sm:py-5 md:px-6">
                                <div class="min-w-0">
                                    <h2 class="text-base font-medium text-gray-800 truncate sm:text-lg">
                                        {{ $category->name }}
                                    </h2>

                                    <p class="mt-1 text-xs text-gray-400">Explore products</p>
                                </div>

                                {{-- Arrow --}}
                                <div
                                    class="flex items-center justify-center flex-shrink-0 ml-3 duration-200 bg-gray-100 rounded-full w-9 h-9 sm:w-10 sm:h-10 group-hover:bg-lime-400"
                                >
                                    <i
                                        class="text-xs duration-200 sm:text-sm fa-solid fa-arrow-right group-hover:translate-x-0.5"
                                    ></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center px-4 py-16 text-center sm:py-20">
                <div class="flex items-center justify-center w-16 h-16 mb-5 bg-gray-100 rounded-full sm:w-20 sm:h-20">
                    <i class="text-2xl text-gray-300 sm:text-3xl fa-solid fa-layer-group"></i>
                </div>

                <h2 class="text-lg font-medium text-gray-700 sm:text-xl">No Categories Available</h2>

                <p class="max-w-md mt-2 text-xs text-gray-400 sm:text-sm">Categories will appear here when they are added.</p>
            </div>

        @endif
    </div>

@endsection
