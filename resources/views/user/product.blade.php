@extends ('layouts.user.app')

@section ('content')
    <div class="flex w-[70%] mx-auto pt-10 pb-20 items-start">
        {{-- Filters/sidebar --}}
        <div class="px-4 rounded-lg shadow-sm w-80 bg-gray-50">
            <div class="flex items-center gap-3 py-3 mb-5 border-b">
                <x-letsicon-filter class="w-5 h-5" />
                <h1 class="text-lg">Filters</h1>
            </div>

            <form method="GET" action="{{ route('user#productList') }}">
                {{-- Price --}}
                <div class="mb-10">
                    <h3>Price Range</h3>

                    <div>
                        {{-- Price values --}}
                        <div class="flex justify-between mb-4">
                            <span id="minPriceText" class="hidden">0 MMK</span>
                            <span id="maxPriceText" class="hidden">{{ number_format($maxPrice) }} MMK</span>
                        </div>

                        {{-- Slider --}}
                        <div class="relative h-3">
                            {{-- Gray track --}}
                            <div class="absolute w-full h-1 bg-gray-200 rounded-full top-2"></div>

                            {{-- Selected lime range --}}
                            <div id="priceRange" class="absolute h-1 rounded-full top-2 bg-lime-400"></div>

                            {{-- Minimum --}}
                            <input
                                id="minPrice"
                                name="min_price"
                                type="range"
                                min="0"
                                max="{{ $maxPrice }}"
                                value="{{ request('min_price', 0) }}"
                                class="absolute inset-0 w-full h-6 appearance-none pointer-events-none slider"
                            />

                            {{-- Maximum --}}
                            <input
                                id="maxPrice"
                                name="max_price"
                                type="range"
                                min="0"
                                max="{{ $maxPrice }}"
                                value="{{ request('max_price', $maxPrice) }}"
                                class="absolute inset-0 w-full h-6 appearance-none pointer-events-none slider"
                            />
                        </div>

                        {{-- Slider Styling --}}
                        <style>
                            .slider::-webkit-slider-thumb {
                                appearance: none;
                                width: 18px;
                                height: 18px;
                                margin-top: -6px;
                                border: 3px solid white;
                                border-radius: 9999px;
                                background: #a3e635;
                                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25);
                                cursor: pointer;
                                pointer-events: auto;
                            }
                            .slider::-moz-range-thumb {
                                width: 18px;
                                height: 18px;
                                border: 3px solid white;
                                border-radius: 9999px;
                                background: #a3e635;
                                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.25);
                                cursor: pointer;
                                pointer-events: auto;
                            }
                            .slider::-webkit-slider-runnable-track {
                                height: 6px;
                                background: transparent;
                            }
                            .slider::-moz-range-track {
                                height: 6px;
                                background: transparent;
                            }
                        </style>

                        <div class="mt-5 text-xs text-black/60">
                            <span id="selectedPrice"></span>
                        </div>
                    </div>
                </div>

                {{-- Category --}}
                <div class="mb-10">
                    <h3 class="mb-3">Category</h3>

                    @forelse ($categories as $category)
                        <label class="flex justify-between mb-3 text-sm cursor-pointer text-black/60">
                            <div class="flex items-center gap-2 w-[90%]">
                                <input
                                    type="checkbox"
                                    name="category[]"
                                    value="{{ $category->id }}"
                                    {{
                                        in_array(
                                            $category->id,
                                            is_array(request('category')) ? request('category') : [request('category')],
                                        )
                                            ? 'checked'
                                            : ''
                                    }}
                                />

                                <span>{{ $category->name }}</span>
                            </div>

                            <span class="flex items-center justify-center w-[10%]">
                                {{ $category->products_count }}
                            </span>
                        </label>

                    @empty
                        <div class="px-3 py-4 text-sm text-center bg-white border border-gray-100 rounded-lg">
                            <i class="mb-2 text-gray-300 fa-solid fa-folder-open"></i>

                            <p class="text-black/50">No categories available</p>
                        </div>
                    @endforelse
                </div>

                <div class="mb-8">
                    <h3 class="mb-3">Rating</h3>

                    <div class="">
                        @for ($star = 5; $star >= 1; $star--)
                            <label class="flex items-center justify-between mb-3 cursor-pointer">
                                <div class="flex items-center gap-2 w-[90%]">
                                    <input
                                        type="radio"
                                        name="rating"
                                        value="{{ $star }}"
                                        {{ request('rating') == $star ? 'checked' : '' }}
                                    />

                                    <div class="flex text-sm">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $star)
                                                <i class="text-yellow-400 fa-solid fa-star"></i>
                                            @else
                                                <i class="text-gray-300 fa-solid fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>

                                    @if ($star < 5)
                                        <span class="text-sm text-black/60"> and up </span>
                                    @endif
                                </div>

                                <span class="text-sm text-black/60 w-[10%] flex items-center justify-center">
                                    {{ $ratingCounts[$star] ?? 0 }}
                                </span>
                            </label>
                        @endfor
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pb-5 mr-3">
                    <a
                        href="{{ route('user#productList') }}"
                        class="px-6 py-2 text-sm text-center border rounded text-black/60"
                    >
                        reset
                    </a>

                    <button type="submit" class="px-6 py-2 text-sm rounded bg-lime-400">Apply</button>
                </div>
            </form>
        </div>

        <div class="grid flex-1 grid-cols-1 gap-5 ml-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse ($products as $product)
                @php
                    $variant = $product->variants->first();
                    $images = $variant?->images ?? [];

                    $image1 = $images->get(0);
                    $image2 = $images->get(1);
                @endphp

                <div class="pb-2 rounded-lg shadow-sm bg-gray-50">
                    <a href="{{ route('user#productDetails', $product->id) }}">
                        {{-- Images --}}
                        <div class="relative group">
                            @if ($image1)
                                <img
                                    src="{{ $image1->image }}"
                                    alt="{{ $product->name }}"
                                    class="object-cover w-full transition-opacity duration-300 rounded-t-lg aspect-square group-hover:opacity-0"
                                />
                            @endif

                            @if ($image2)
                                <img
                                    src="{{ $image2->image }}"
                                    alt="{{ $product->name }}"
                                    class="absolute inset-0 object-cover w-full transition-opacity duration-300 rounded-t-lg opacity-0 aspect-square group-hover:opacity-100"
                                />
                            @endif
                        </div>

                        {{-- Product information --}}
                        <div class="p-5 space-y-2 text-lg">
                            <p class="text-black/70">{{ $product->name }}</p>

                            <p>{{ number_format($variant?->price ?? 0) }} MMK</p>

                            @php
                                $averageRating = $product->ratings_avg_rating ?? 0;
                            @endphp

                            <div class="flex items-center gap-1 pb-1 text-xs">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i
                                        class="fa-solid fa-star
                            {{ $i <= round($averageRating)
                                ? 'text-yellow-400'
                                : 'text-gray-300' }}"
                                    >
                                    </i>
                                @endfor

                                <span class="ml-2 text-sm"> {{ number_format($averageRating, 1) }} </span>

                                <span class="ml-2 text-sm text-black/50"> {{ $product->ratings_count }} reviews </span>
                            </div>
                        </div>
                    </a>

                    <div class="flex gap-3 px-5 pb-5">
                        <button type="button" class="px-3 py-2 text-sm border-2 rounded text-black/50">
                            <i class="mr-2 fa-solid fa-cart-arrow-down"></i>
                            Add To Cart
                        </button>

                        <button type="button" class="py-2 text-sm text-black rounded px-7 bg-lime-400">Buy Now</button>
                    </div>
                </div>

            @empty
                @php
                    $hasFilters =
                        request()->filled('category') ||
                        request()->filled('rating') ||
                        (request()->filled('min_price') && request('min_price') > 0) ||
                        (request()->filled('max_price') && request('max_price') < $maxPrice);
                @endphp

                <div class="px-6 py-16 text-center bg-gray-50 col-span-full">
                    <div class="flex flex-col items-center justify-center">
                        <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full">
                            @if ($hasFilters)
                                <i class="text-xl text-gray-400 fa-solid fa-filter-circle-xmark"></i>
                            @else
                                <i class="text-xl text-gray-400 fa-solid fa-box-open"></i>
                            @endif
                        </div>

                        @if ($hasFilters)
                            <h3 class="font-medium text-gray-700">No matching products</h3>

                            <p class="max-w-md mt-1 text-sm text-gray-400">We couldn't find any products matching your current search or filters. Try changing your filters or clearing them to see all products.</p>

                            <a
                                href="{{ route('user#productList') }}"
                                class="inline-flex items-center gap-2 px-4 py-2 mt-5 text-sm font-medium text-gray-700 transition rounded-lg bg-lime-400 hover:bg-lime-500"
                            >
                                <i class="text-xs fa-solid fa-rotate-left"></i>
                                Clear Filters
                            </a>
                        @else
                            <h3 class="font-medium text-gray-700">No products yet</h3>

                            <p class="max-w-md mt-1 text-sm text-gray-400">There are no products available to display yet. Products will appear here when they are added.</p>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @if (session('orderSuccess'))
        <div id="order-success-modal" class="fixed inset-0 z-50 flex items-center justify-center px-4 bg-black/50">
            <div class="w-full max-w-md p-8 text-center bg-white shadow-2xl rounded-2xl">
                {{-- Success Icon --}}
                <div class="flex items-center justify-center w-16 h-16 mx-auto rounded-full bg-lime-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>

                {{-- Title --}}
                <h2 class="mt-5 text-2xl font-medium text-gray-900">Order Successful!</h2>

                {{-- Message --}}
                <p class="mt-3 text-sm text-gray-500">Thank you for your purchase. Your order has been successfully placed.</p>

                {{-- Order ID --}}
                {{-- <div class="px-4 py-3 mt-5 rounded-lg bg-gray-50"> --}}
                <p class="mt-5 text-sm text-gray-500">Your order number is <span class="font-semibold text-gray-900">{{ session('orderId') }}</span></p>
                {{-- </div> --}}

                {{-- Buttons --}}
                <div class="flex items-center gap-3 mt-6">
                    <a
                        href="{{ route('user#myOrder') }}"
                        class="w-1/2 px-6 py-2 text-sm text-center transition border rounded hover:bg-gray-100"
                    >
                        View Order
                    </a>

                    <a
                        href="{{ route('user#productList') }}"
                        class="w-1/2 px-6 py-2 text-sm transition rounded bg-lime-400 hover:bg-lime-500"
                    >
                        Continue Shopping
                    </a>
                </div>
            </div>
        </div>
    @endif

    <script>
        function closeOrderSuccessModal() {
            const modal = document.getElementById('order-success-modal');

            if (modal) {
                modal.remove();
            }
        }
    </script>

    <script>
        const minPrice = document.getElementById('minPrice');
        const maxPrice = document.getElementById('maxPrice');

        const minPriceText = document.getElementById('minPriceText');
        const maxPriceText = document.getElementById('maxPriceText');
        const selectedPrice = document.getElementById('selectedPrice');
        const priceRange = document.getElementById('priceRange');

        const max = Number(minPrice.max);

        function formatPrice(price) {
            return Number(price).toLocaleString() + ' MMK';
        }

        function updatePriceSlider() {
            let min = Number(minPrice.value);
            let maxValue = Number(maxPrice.value);

            if (min > maxValue) {
                min = maxValue;
                minPrice.value = min;
            }

            if (maxValue < min) {
                maxValue = min;
                maxPrice.value = maxValue;
            }

            const left = (min / max) * 100;
            const right = (maxValue / max) * 100;

            priceRange.style.left = left + '%';
            priceRange.style.width = right - left + '%';

            minPriceText.textContent = formatPrice(min);
            maxPriceText.textContent = formatPrice(max);

            selectedPrice.textContent = formatPrice(min) + ' - ' + formatPrice(maxValue);
        }

        minPrice.addEventListener('input', updatePriceSlider);
        maxPrice.addEventListener('input', updatePriceSlider);

        updatePriceSlider();
    </script>

@endsection
