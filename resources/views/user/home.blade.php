@extends ('layouts.user.app')

@section ('content')
    <div class="flex flex-col justify-center w-[92%] sm:w-[90%] lg:w-[80%] xl:w-[70%] mx-auto pb-12 sm:pb-16 lg:pb-20">
        {{-- Featured Categories --}}
        <div>
            <div class="flex items-center justify-between mt-6 mb-4 sm:mt-8 sm:mb-5 lg:mt-10">
                <h1 class="text-lg font-semibold text-gray-900 sm:text-xl lg:text-2xl">Featured Categories</h1>

                <a href="{{ route('user#category') }}" class="px-2 py-2 text-xs font-medium sm:px-4 sm:text-sm lg:px-5">
                    View All
                    <i class="ml-1 text-[10px] fa-solid fa-arrow-right sm:ml-2 sm:text-xs"></i>
                </a>
            </div>

            <div class="flex gap-3 mb-5 overflow-x-auto text-sm sm:gap-4 lg:gap-5 scrollbar-hide">
                @forelse ($categories as $category)
                    <a href="{{ route('user#productList', ['category' => $category->id]) }}" class="flex-shrink-0">
                        <div
                            class="flex flex-col items-center justify-center w-32 p-4 rounded-lg shadow-sm sm:w-36 sm:p-6 lg:w-auto lg:p-8 bg-gray-50"
                        >
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="" />

                            <p class="mt-2 text-xs text-center sm:text-sm">{{ $category->name }}</p>
                        </div>

                        {{-- <div class="flex flex-col items-center justify-center p-8 rounded-lg shadow-sm bg-gray-50">
                            <img src="{{ $category->image }}" alt="{{ $category->name }}" />
                            <p>{{ $category->name }}</p>
                        </div> --}}
                    </a>
                @empty
                    {{-- No Featured Categories Yet --}}
                    <div class="w-full py-12 text-center border border-gray-100 rounded-xl sm:py-16 bg-gray-50">
                        <i class="text-3xl text-gray-300 sm:text-4xl fa-solid fa-layer-group"></i>

                        <h3 class="mt-4 text-base font-medium text-gray-700 sm:text-lg">No featured categories yet</h3>

                        <p class="px-4 mt-1 text-xs text-gray-500 sm:text-sm">Categories will appear here once they are added.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Trending Products --}}
        <div class="mt-12 sm:mt-16 lg:mt-20">
            <div class="flex items-center justify-between mb-5 sm:mb-6 lg:mb-8">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 sm:text-xl lg:text-2xl">Trending Products</h2>

                    <p class="mt-1 text-xs text-gray-500 sm:text-sm">Popular products based on recent sales</p>
                </div>

                <a
                    href="{{ route('user#productList') }}"
                    class="px-2 py-2 text-xs font-medium sm:px-4 sm:text-sm lg:px-5"
                >
                    View All
                    <i class="ml-1 text-[10px] fa-solid fa-arrow-right sm:ml-2 sm:text-xs"></i>
                </a>
            </div>

            @if ($trendingProducts->count())
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-5 lg:grid-cols-4 lg:gap-6">
                    @foreach ($trendingProducts as $product)
                        @php
                            // First available variant
                            $variant = $product->variants->first();

                            // First image of that variant
                            $image = $variant?->images->first();

                            // Price
                            $price = $variant?->price;

                            // Total stock of all variants
                            $totalStock = $product->variants->sum('stock');
                        @endphp

                        <div class="pb-2 overflow-hidden rounded-lg shadow-sm bg-gray-50">
                            {{-- Product Image --}}
                            <a href="{{ route('user#productDetails', $product->id) }}">
                                <div class="flex items-center justify-center">
                                    @if ($image)
                                        <img
                                            src="{{ asset('productImage/' . $image->image) }}"
                                            alt="{{ $product->name }}"
                                            class="object-cover w-full rounded-t-lg aspect-square"
                                        />
                                    @else
                                        <div
                                            class="flex flex-col items-center justify-center w-full text-gray-400 aspect-square"
                                        >
                                            <i class="text-3xl sm:text-4xl fa-solid fa-image"></i>

                                            <span class="mt-2 text-xs sm:text-sm"> No Image </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Information --}}
                                <div class="p-3 space-y-1.5 text-sm sm:p-4 sm:space-y-2 sm:text-base lg:p-5 lg:text-lg">
                                    {{-- Product Name --}}
                                    <p class="text-black/70 line-clamp-2">{{ $product->name }}</p>

                                    {{-- Rating --}}
                                    <div class="flex items-center gap-1.5 mt-2 sm:gap-2">
                                        <div class="flex gap-0.5 text-xs sm:text-sm text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="text-xs sm:text-sm fa-solid fa-star {{ $i <= round($product->ratings_avg_rating ?? 0)
                                                        ? 'text-yellow-400'
                                                        : 'text-gray-300' }}"
                                                ></i>
                                            @endfor
                                        </div>

                                        <p class="text-xs text-gray-500 sm:text-sm">
                                            {{ number_format($product->ratings_avg_rating, 1) }}
                                        </p>

                                        <span class="text-[10px] text-gray-400 sm:text-xs">
                                            ({{ $product->ratings_count ?? 0 }} {{ $product->ratings_count > 1 ? 'reviews' : 'review' }})
                                        </span>
                                    </div>

                                    {{-- Price --}}
                                    @if ($price)
                                        <div class="mt-2 sm:mt-3">
                                            <p class="text-sm sm:text-base lg:text-lg">
                                                {{ number_format($price) }} MMK
                                            </p>
                                        </div>
                                    @else
                                        <div class="mt-2 sm:mt-3">
                                            <p class="text-sm sm:text-base">Price unavailable</p>
                                        </div>
                                    @endif

                                    {{-- Stock --}}
                                    <div class="mt-1.5 sm:mt-2">
                                        @if ($totalStock > 0)
                                            <span class="text-[10px] font-medium text-green-600 sm:text-xs">
                                                <i class="mr-1 fa-solid fa-circle-check"></i>
                                                In Stock
                                            </span>

                                        @else
                                            <span class="text-[10px] font-medium text-red-500 sm:text-xs">
                                                <i class="mr-1 fa-solid fa-circle-xmark"></i>
                                                Out of Stock
                                            </span>

                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>

                    @endforeach
                </div>

            @else
                {{-- No Trending Products Yet --}}
                <div class="py-12 text-center border border-gray-100 rounded-xl sm:py-16 bg-gray-50">
                    <i class="text-3xl text-gray-300 sm:text-4xl fa-solid fa-fire"></i>

                    <h3 class="mt-4 text-base font-medium text-gray-700 sm:text-lg">No trending products yet</h3>

                    <p class="px-4 mt-1 text-xs text-gray-500 sm:text-sm">Products will appear here once customers start placing orders.</p>
                </div>

            @endif
        </div>
    </div>
@endsection
