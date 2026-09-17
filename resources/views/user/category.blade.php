@extends ('layouts.user.app')

@section ('content')
    <div class="w-[70%] mx-auto pt-10 pb-20">
        {{-- Page Header --}}
        <div class="mb-10 text-center">
            <p class="text-sm font-medium tracking-wide uppercase text-lime-600">Explore techVerse</p>

            <h1 class="mt-2 text-3xl font-semibold text-gray-800 md:text-4xl">Shop by Category</h1>

            <p class="max-w-xl mx-auto mt-3 text-sm leading-6 text-gray-500">Find the products you are looking for by browsing our categories.</p>
        </div>

        {{-- Categories --}}
        @if ($categories->count())
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                {{-- All Products --}}
                <a href="{{ route('user#productList') }}" class="group">
                    <div
                        class="overflow-hidden duration-300 bg-white border border-gray-100 rounded-xl hover:-translate-y-1 hover:shadow-xl"
                    >
                        <div class="flex items-center justify-center p-8 bg-gray-50 h-80">
                            <div class="flex flex-col items-center justify-center">
                                <div
                                    class="flex items-center justify-center w-20 h-20 duration-300 bg-gray-200 rounded-full group-hover:bg-lime-400"
                                >
                                    <i class="text-3xl duration-300 fa-solid fa-layer-group"></i>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between px-6 py-5">
                            <div>
                                <h2 class="text-lg font-medium text-gray-800">All Products</h2>

                                <p class="mt-1 text-xs text-gray-400">Explore all products</p>
                            </div>

                            <div
                                class="flex items-center justify-center w-10 h-10 duration-200 bg-gray-100 rounded-full group-hover:bg-lime-400"
                            >
                                <i class="text-sm duration-200 fa-solid fa-arrow-right group-hover:translate-x-0.5"></i>
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
                            <div class="flex items-center justify-center p-6 overflow-hidden h-80">
                                @if ($category->image)
                                    <img
                                        src="{{ $category->image }}"
                                        alt="{{ $category->name }}"
                                        class="object-contain w-full h-full duration-500 group-hover:scale-105"
                                    />

                                @else
                                    <div class="flex flex-col items-center justify-center text-gray-300">
                                        <i class="text-5xl fa-solid fa-image"></i>

                                        <span class="mt-2 text-sm"> No Image </span>
                                    </div>

                                @endif
                            </div>

                            {{-- Category Information --}}
                            <div class="flex items-center justify-between px-6 py-5 bg-white">
                                <div>
                                    <h2 class="text-lg font-medium text-gray-800">{{ $category->name }}</h2>

                                    <p class="mt-1 text-xs text-gray-400">Explore products</p>
                                </div>

                                {{-- Arrow --}}
                                <div
                                    class="flex items-center justify-center w-10 h-10 duration-200 bg-gray-100 rounded-full group-hover:bg-lime-400"
                                >
                                    <i
                                        class="text-sm duration-200 fa-solid fa-arrow-right group-hover:translate-x-0.5"
                                    ></i>
                                </div>
                            </div>
                        </div>
                    </a>

                @endforeach
            </div>

        @else
            {{-- Empty State --}}
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <div class="flex items-center justify-center w-20 h-20 mb-5 bg-gray-100 rounded-full">
                    <i class="text-3xl text-gray-300 fa-solid fa-layer-group"></i>
                </div>

                <h2 class="text-xl font-medium text-gray-700">No Categories Available</h2>

                <p class="mt-2 text-sm text-gray-400">Categories will appear here when they are added.</p>
            </div>

        @endif
    </div>

@endsection
