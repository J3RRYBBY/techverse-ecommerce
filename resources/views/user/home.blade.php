@extends ('layouts.user.app')

@section ('content')
    <div class="flex flex-col justify-center pb-20 w-[70%] mx-auto">
        <div class="">
            <div class="flex items-center justify-between mt-10 mb-5">
                <h1 class="text-2xl font-semibold text-gray-900">Featured Categories</h1>

                <a href="{{ route('user#category') }}" class="px-5 py-2 text-sm font-medium">
                    View All
                    <i class="ml-2 text-xs fa-solid fa-arrow-right"></i>
                </a>
            </div>

            <div class="flex gap-5 mb-5 text-sm">
                @forelse ($categories as $category)
                    <div class="flex flex-col items-center justify-center p-8 rounded-lg shadow-sm bg-gray-50">
                        <img src="{{ $category->image }}" alt="{{ $category->name }}" />

                        <p>{{ $category->name }}</p>
                    </div>
                @empty
                    {{-- No Featured Categories Yet --}}
                    <div class="w-full py-16 text-center border border-gray-100 rounded-xl bg-gray-50">
                        <i class="text-4xl text-gray-300 fa-solid fa-layer-group"></i>

                        <h3 class="mt-4 text-lg font-medium text-gray-700">No featured categories yet</h3>

                        <p class="mt-1 text-sm text-gray-500">Categories will appear here once they are added.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Trending Products --}}
        <div class="mt-20">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900">Trending Products</h2>

                    <p class="mt-1 text-sm text-gray-500">Popular products based on recent sales</p>
                </div>

                <a href="{{ route('user#productList') }}" class="px-5 py-2 text-sm font-medium">
                    View All
                    <i class="ml-2 text-xs fa-solid fa-arrow-right"></i>
                </a>
            </div>

            @if ($trendingProducts->count())
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
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

                        <div class="pb-2 rounded-lg shadow-sm bg-gray-50">
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
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="text-4xl fa-solid fa-image"></i>

                                            <span class="mt-2 text-sm"> No Image </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Information --}}
                                <div class="p-5 space-y-2 text-lg">
                                    {{-- Product Name --}}
                                    {{-- <a href="{{ route('user#productDetails', $product->id) }}" class="block">
                                    <h3 class="font-medium text-gray-900 truncate">{{ $product->name }}</h3>
                                </a> --}}

                                    <p class="text-black/70">{{ $product->name }}</p>

                                    {{-- Rating --}}
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="flex gap-0.5 text-sm text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="text-sm fa-solid fa-star {{ $i <= round($product->ratings_avg_rating ?? 0) ? 'text-yellow-400'
                                                : 'text-gray-300' }}"
                                                ></i>
                                            @endfor
                                        </div>

                                        <p class="text-sm text-gray-500">
                                            {{ number_format($product->ratings_avg_rating, 1) }}
                                        </p>

                                        <span class="text-xs text-gray-400">
                                            ({{ $product->ratings_count ?? 0 }} {{ $product->ratings_count > 1 ? 'reviews' : 'review' }})
                                        </span>
                                    </div>

                                    {{-- Price --}}
                                    @if ($price)
                                        <div class="mt-3">
                                            <p class="">{{ number_format($price) }} MMK</p>
                                        </div>
                                    @else
                                        <div class="mt-3">
                                            <p class="">Price unavailable</p>
                                        </div>
                                    @endif

                                    {{-- Stock --}}
                                    <div class="mt-2">
                                        @if ($totalStock > 0)
                                            <span class="text-xs font-medium text-green-600">
                                                <i class="mr-1 fa-solid fa-circle-check"></i>
                                                In Stock
                                            </span>
                                        @else
                                            <span class="text-xs font-medium text-red-500">
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
                <div class="py-16 text-center border border-gray-100 rounded-xl bg-gray-50">
                    <i class="text-4xl text-gray-300 fa-solid fa-fire"></i>

                    <h3 class="mt-4 text-lg font-medium text-gray-700">No trending products yet</h3>

                    <p class="mt-1 text-sm text-gray-500">Products will appear here once customers start placing orders.</p>
                </div>

            @endif
        </div>
    </div>
@endsection
