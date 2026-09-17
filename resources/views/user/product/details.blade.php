@extends ('layouts.user.app')

@section ('content')
    @php
        $initialVariant = $product->variants->first();
        $initialImage = $initialVariant?->images->first();
    @endphp

    <div class="w-[70%] mx-auto mt-10">
        {{-- Back --}}
        <a
            href="{{ route('user#productList') }}"
            class="inline-flex items-center gap-2 mb-6 text-sm text-gray-500 hover:text-gray-900"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to Products
        </a>

        {{-- =========================================================
            PRODUCT SECTION
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-10 lg:grid-cols-2 lg:gap-16">
            {{-- =====================================================
                LEFT - PRODUCT IMAGES
            ====================================================== --}}
            <div>
                {{-- Main Image Card --}}
                <div class="relative overflow-hidden">
                    @if ($initialImage)
                        <img
                            id="detailPreviewImage"
                            src="{{ $initialImage->image }}"
                            class="object-contain w-full h-[420px] md:h-[520px] p-6 md:p-10 transition duration-300"
                            alt="{{ $product->name }}"
                        />
                    @else
                        <div class="flex items-center justify-center w-full h-[420px] md:h-[520px] text-gray-300">
                            <i class="text-6xl fa-regular fa-image"></i>
                        </div>
                    @endif

                    {{-- Previous --}}
                    <button
                        id="detailPrevBtn"
                        type="button"
                        class="absolute flex items-center justify-center transition -translate-y-1/2 bg-white border border-gray-200 rounded-full shadow-sm w-11 h-11 left-4 top-1/2 hover:bg-lime-400 hover:border-lime-400"
                    >
                        <i class="text-sm fa-solid fa-chevron-left"></i>
                    </button>

                    {{-- Next --}}
                    <button
                        id="detailNextBtn"
                        type="button"
                        class="absolute flex items-center justify-center transition -translate-y-1/2 bg-white border border-gray-200 rounded-full shadow-sm w-11 h-11 right-4 top-1/2 hover:bg-lime-400 hover:border-lime-400"
                    >
                        <i class="text-sm fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                {{-- =================================================
                    THUMBNAILS
                ================================================== --}}
                <div
                    id="detailThumbnailContainer"
                    class="flex items-center gap-3 overflow-x-auto scroll-smooth scrollbar-hide"
                >
                    @foreach ($product->variants as $variant)
                        @foreach ($variant->images as $image)
                            <button
                                type="button"
                                class="flex-shrink-0 w-20 h-20 overflow-hidden transition border-2 border-transparent rounded-xl detail-thumb-btn hover:border-lime-300"
                                data-src="{{ $image->image }}"
                            >
                                <img
                                    src="{{ $image->image }}"
                                    class="object-cover w-full h-full"
                                    alt="{{ $product->name }}"
                                />
                            </button>

                        @endforeach
                    @endforeach
                </div>

                {{-- Thumbnail Buttons --}}
                <div class="flex items-center gap-3 mt-5">
                    <button
                        type="button"
                        id="detailThumbPrev"
                        class="flex items-center justify-center flex-shrink-0 w-10 h-10 transition bg-white border border-gray-200 rounded-full hover:bg-lime-400 hover:border-lime-400"
                    >
                        <i class="text-xs fa-solid fa-chevron-left"></i>
                    </button>

                    <button
                        type="button"
                        id="detailThumbNext"
                        class="flex items-center justify-center flex-shrink-0 w-10 h-10 transition bg-white border border-gray-200 rounded-full hover:bg-lime-400 hover:border-lime-400"
                    >
                        <i class="text-xs fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            {{-- =====================================================
                RIGHT - PRODUCT INFORMATION
            ====================================================== --}}
            <div class="flex flex-col">
                {{-- Product Name --}}
                <div class="pb-6 border-b border-gray-100">
                    {{-- <p class="mb-3 text-xs font-semibold tracking-[0.2em] uppercase text-lime-600">techVerse</p> --}}

                    <h1 class="text-3xl md:text-4xl">{{ $product->name }}</h1>

                    {{-- Rating --}}
                    @php
                        $averageRating = $product->ratings->avg('rating') ?? 0;
                        $ratingCount = $product->ratings->count();
                    @endphp

                    <div class="flex items-center gap-3 mt-4">
                        <div class="flex gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="text-sm fa-solid fa-star
                                    {{ $i <= round($averageRating)
                                        ? 'text-yellow-400'
                                        : 'text-gray-300' }}"
                                ></i>
                            @endfor
                        </div>

                        <span class="text-sm text-gray-500"> {{ number_format($averageRating, 1) }} </span>

                        <span class="text-sm text-gray-400">
                            ({{ $ratingCount }} {{ $ratingCount < 1 ? 'review' : 'reviews' }})
                        </span>
                    </div>
                </div>

                {{-- Price --}}
                <div class="py-6">
                    <p id="productPrice" class="text-3xl font-medium">{{ number_format($initialVariant->price) }} MMK</p>

                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-2 h-2 rounded-full bg-lime-500"></span>

                        <span id="stockStatus" class="text-sm font-medium text-lime-600">
                            {{
                                $initialVariant->stock > 0
                                    ? 'In Stock'
                                    : 'Out of Stock'
                            }}
                        </span>
                    </div>
                </div>

                {{-- =================================================
                    CAPACITY
                ================================================== --}}
                <div class="mb-7">
                    <div class="flex items-center gap-3 mb-3">
                        <p class="text-sm font-semibold">Capacity :</p>

                        <span id="selectedCapacity" class="text-sm font-medium text-gray-500">
                            {{ $initialVariant->capacity }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($product->variants->pluck('capacity')->unique() as $capacity)
                            <button
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium transition border border-gray-200 rounded-lg capacity-btn hover:border-lime-400 hover:bg-lime-50"
                                data-capacity="{{ $capacity }}"
                            >
                                {{ $capacity }}
                            </button>

                        @endforeach
                    </div>
                </div>

                {{-- =================================================
                    COLOR
                ================================================== --}}
                <div class="mb-7">
                    <div class="flex items-center gap-3 mb-3">
                        <p class="text-sm font-semibold">Color :</p>

                        <span id="selectedColor" class="text-sm font-medium text-gray-500">
                            {{ $initialVariant->color }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($product->variants->pluck('color')->unique() as $color)
                            <button
                                type="button"
                                class="px-5 py-2.5 text-sm font-medium transition border border-gray-200 rounded-lg color-btn hover:border-lime-400 hover:bg-lime-50"
                                data-color="{{ $color }}"
                            >
                                {{ $color }}
                            </button>

                        @endforeach
                    </div>
                </div>

                {{-- =================================================
                    QUANTITY + ADD TO CART
                ================================================== --}}
                <div class="flex flex-col gap-3 sm:flex-row">
                    {{-- Quantity --}}
                    <div class="flex items-center justify-between h-12 px-2 border border-gray-200 rounded-lg sm:w-36">
                        <button
                            id="decrease"
                            type="button"
                            class="flex items-center justify-center text-lg text-gray-500 transition rounded-md w-9 h-9 hover:bg-gray-100 hover:text-gray-900"
                        >
                            <i class="text-xs fa-solid fa-minus"></i>
                        </button>

                        <input
                            type="text"
                            id="quantity"
                            value="1"
                            class="w-10 text-sm font-semibold text-center text-gray-900 outline-none"
                            readonly
                        />

                        <button
                            id="increase"
                            type="button"
                            class="flex items-center justify-center text-lg text-gray-500 transition rounded-md w-9 h-9 hover:bg-gray-100 hover:text-gray-900"
                        >
                            <i class="text-xs fa-solid fa-plus"></i>
                        </button>
                    </div>

                    {{-- Add To Cart --}}
                    <button
                        id="addToCartBtn"
                        type="button"
                        class="flex items-center justify-center flex-1 h-12 gap-2 px-6 font-medium transition rounded-lg bg-lime-400 hover:bg-lime-500 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed"
                    >
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Add To Cart</span>
                    </button>
                </div>

                {{-- =================================================
                    PRODUCT FEATURES
                ================================================== --}}
                <div class="grid grid-cols-3 gap-3 py-6 border-gray-100 mt-7 border-y">
                    <div class="flex flex-col items-center gap-2 text-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-lime-50">
                            <i class="text-sm fa-solid fa-truck text-lime-600"></i>
                        </div>

                        <span class="text-xs font-medium text-gray-600"> Fast Delivery </span>
                    </div>

                    <div class="flex flex-col items-center gap-2 text-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-lime-50">
                            <i class="text-sm fa-solid fa-shield-halved text-lime-600"></i>
                        </div>

                        <span class="text-xs font-medium text-gray-600"> Secure Payment </span>
                    </div>

                    <div class="flex flex-col items-center gap-2 text-center">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-lime-50">
                            <i class="text-sm fa-solid fa-headset text-lime-600"></i>
                        </div>

                        <span class="text-xs font-medium text-gray-600"> Support </span>
                    </div>
                </div>

                {{-- =================================================
                    DESCRIPTION
                ================================================== --}}
                <div class="pt-7">
                    <h2 class="mb-3 text-xl font-semibold">Product Description</h2>

                    <p class="leading-7 whitespace-pre-line text-black/60">{{ $product->description }}</p>
                </div>
            </div>
        </div>

        {{-- =========================================================
            RELATED PRODUCTS
        ========================================================== --}}
        @if ($relatedProducts->count() > 0)
            <section class="mt-20 md:mt-28">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <p class="mb-2 text-xs font-semibold tracking-[0.2em] uppercase text-lime-600">You may also like</p>

                        <h2 class="text-2xl font-semibold text-gray-900 md:text-3xl">Related Products</h2>
                    </div>

                    <a href="{{ route('user#productList') }}" class="text-sm font-medium">
                        View All
                        <i class="ml-1 fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        @php
                            $relatedVariant = $relatedProduct->variants->first();
                            $relatedImage = $relatedVariant?->images->first();
                            $totalStock = $relatedProduct->variants->sum('stock');
                        @endphp

                        <div class="pb-2 rounded-lg shadow-sm bg-gray-50">
                            {{-- Product Image --}}
                            <a href="{{ route('user#productDetails', $relatedProduct->id) }}" class="block">
                                <div class="flex items-center justify-center">
                                    @if ($relatedImage)
                                        <img
                                            src="{{ $relatedImage->image }}"
                                            alt="{{ $relatedProduct->name }}"
                                            class="object-cover w-full rounded-t-lg aspect-square"
                                        />
                                    @else
                                        <div class="flex items-center justify-center w-full h-full text-gray-300">
                                            <i class="text-5xl fa-solid fa-image"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Product Information --}}
                                <div class="p-5 space-y-2 text-lg">
                                    {{-- <a href="{{ route('user#productDetails', $relatedProduct->id) }}" class="block">
                                    <h3
                                        class="font-medium text-gray-900 truncate hover:text-lime-600"
                                        title="{{ $relatedProduct->name }}"
                                    >
                                        {{ $relatedProduct->name }}
                                    </h3>
                                </a> --}}

                                    <p class="text-black/70">{{ $relatedProduct->name }}</p>

                                    {{-- Rating --}}
                                    <div class="flex items-center gap-2 mt-2">
                                        <div class="flex gap-0.5 text-sm text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="text-sm fa-solid fa-star {{ $i <= round($relatedProduct->ratings_avg_rating ?? 0) ? 'text-yellow-400'
                                                : 'text-gray-300' }}"
                                                ></i>
                                            @endfor
                                        </div>

                                        <p class="text-sm text-gray-500">
                                            {{ number_format($relatedProduct->ratings_avg_rating, 1) }}
                                        </p>

                                        <span class="text-xs text-gray-400">
                                            ({{ $relatedProduct->ratings_count ?? 0 }} {{ $relatedProduct->ratings_count > 1 ? 'reviews' : 'review' }})
                                        </span>
                                    </div>

                                    {{-- Price --}}
                                    @if ($relatedVariant)
                                        <div class="mt-3">
                                            <p class="">{{ number_format($relatedVariant->price) }} MMK</p>
                                        </div>
                                    @else
                                        <div class="mt-3"><p>Price unavailable</p></div>
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
            </section>
        @endif

        {{-- =========================================================
            REVIEWS
        ========================================================== --}}
        <section class="pb-20 mt-20 md:mt-28">
            {{-- Review Header --}}
            <div class="mb-8">
                <p class="mb-2 text-xs font-semibold tracking-[0.2em] uppercase text-lime-600">Customer Feedback</p>

                <h2 class="text-2xl font-semibold text-gray-900 md:text-3xl">Reviews</h2>
            </div>

            {{-- Review Tabs --}}
            <div class="flex gap-8 border-b border-gray-200">
                <button
                    type="button"
                    id="myReviewBtn"
                    onclick="showReviewTab('myReview')"
                    class="px-1 pb-4 text-sm font-semibold text-gray-900 border-b-2 border-lime-400"
                >
                    My Review
                </button>

                <button
                    type="button"
                    id="allReviewsBtn"
                    onclick="showReviewTab('allReviews')"
                    class="px-1 pb-4 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-gray-900"
                >
                    All Reviews
                </button>
            </div>

            {{-- =====================================================
                MY REVIEW
            ====================================================== --}}
            <div id="myReview" class="mt-8">
                <div class="max-w-2xl p-6 mx-auto bg-gray-50 md:p-10 rounded-2xl">
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-lime-100">
                            <i class="text-lime-600 fa-solid fa-star"></i>
                        </div>

                        <h3 class="text-2xl font-semibold text-gray-900">
                            Give Feedback
                            </3>

                            <p class="max-w-md mx-auto mt-2 text-sm leading-6 text-gray-500">What do you think about this product and your experience?</p>
                    </div>

                    <form action="{{ route('user#feedback', $product) }}" method="POST" class="mt-8">
                        @csrf

                        {{-- Rating --}}
                        <div>
                            <p class="mb-3 text-sm font-semibold text-gray-800">Your Rating</p>

                            <div class="flex gap-2" id="ratingStars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input
                                            type="radio"
                                            name="rating"
                                            value="{{ $i }}"
                                            class="hidden rating-input"
                                            {{
                                                $userRating && $userRating->rating == $i
                                                    ? 'checked'
                                                    : ''
                                            }}
                                        />

                                        <span
                                            class="flex items-center justify-center w-10 h-10 transition rounded-lg star
                                            {{
                                                $userRating && $i <= $userRating->rating
                                                    ? 'text-yellow-400 bg-yellow-50'
                                                    : 'text-gray-300 bg-white'
                                            }}"
                                            data-value="{{ $i }}"
                                        >
                                            <i class="text-lg fa-solid fa-star"></i>
                                        </span>
                                    </label>

                                @endfor
                            </div>
                        </div>

                        {{-- Comment --}}
                        <div class="mt-7">
                            <label for="message" class="block mb-2 text-sm font-semibold text-gray-800">
                                What are the main reasons for your rating?
                            </label>

                            <textarea
                                id="message"
                                name="message"
                                rows="6"
                                class="resize-none w-full px-4 py-2.5 mt-2 text-sm rounded-lg shadow-sm border transition focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400 @error('message') outline outline-1 outline-red-500 @enderror"
                                placeholder="Share your experience..."
                                >{{ old('message') }}</textarea
                            >

                            @error ('message')
                                <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Submit --}}
                        <div class="flex justify-end mt-5">
                            <button
                                type="submit"
                                class="py-3 text-sm font-semibold transition rounded-lg px-7 bg-lime-400 hover:bg-lime-500"
                            >
                                Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- =====================================================
                ALL REVIEWS
            ====================================================== --}}
            <div id="allReviews" class="hidden mt-8">
                <div class="max-w-3xl mx-auto">
                    @forelse ($ratings as $rating)
                        @php
                            $comment = $comments->where('user_id', $rating->user_id)->first();
                        @endphp

                        <div class="p-6 mb-4 bg-white border border-gray-100 rounded-2xl">
                            {{-- User --}}
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <img
                                        src="{{ $rating->user->profile_image
                                            ? $rating->user->profile_image
                                            : asset('defaultImage/user.png') }}"
                                        alt="{{ $rating->user->name }}"
                                        class="object-cover border border-gray-100 rounded-full w-11 h-11"
                                    />

                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ $rating->user->name }}</p>

                                        <p class="mt-0.5 text-xs text-gray-400">
                                            {{ $rating->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>

                                {{-- Stars --}}
                                <div class="flex gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i
                                            class="text-xs fa-solid fa-star
                                            {{ $i <= $rating->rating
                                                ? 'text-yellow-400'
                                                : 'text-gray-300' }}"
                                        ></i>

                                    @endfor
                                </div>
                            </div>

                            {{-- Comment --}}
                            @if ($comment)
                                <div class="pt-5 mt-5 border-t border-gray-100">
                                    <p class="text-sm leading-7 text-gray-600">{{ $comment->message }}</p>
                                </div>

                            @endif
                        </div>

                    @empty
                        <div class="py-16 text-center bg-gray-50 rounded-2xl">
                            <div class="flex items-center justify-center mx-auto bg-white rounded-full w-14 h-14">
                                <i class="text-xl text-gray-300 fa-regular fa-comment"></i>
                            </div>

                            <p class="mt-4 text-sm font-medium text-gray-500">No reviews yet.</p>

                            <p class="mt-1 text-xs text-gray-400">Be the first to review this product.</p>
                        </div>

                    @endforelse
                </div>
            </div>
        </section>
    </div>

    {{-- =============================================================
        PRODUCT DATA
    ============================================================== --}}
    <script>
        window.productData = {
            selectedCapacity: @json ($initialVariant->capacity),

            selectedColor: @json ($initialVariant->color),

            variants: [
                @foreach ($product->variants as $variant)

                {
                    id: {{ $variant->id }},

                    capacity: @json ($variant->capacity),

                    color: @json ($variant->color),

                    price: {{ $variant->price }},

                    stock: {{ $variant->stock }},

                    images: [
                        @foreach ($variant->images as $image)

                        @json ($image->image),

                        @endforeach
                    ],
                },

                @endforeach
            ],
        };
    </script>

    {{-- =============================================================
        VARIANT + QUANTITY
    ============================================================== --}}
    <script>
        const decreaseBtn = document.getElementById('decrease');
        const increaseBtn = document.getElementById('increase');
        const quantityInput = document.getElementById('quantity');
        const addToCartBtn = document.getElementById('addToCartBtn');
        const stockStatus = document.getElementById('stockStatus');

        let quantity = 1;
        let currentStock = 0;

        function getSelectedVariant() {
            return window.productData.variants.find(
                (variant) =>
                    variant.capacity === window.productData.selectedCapacity &&
                    variant.color === window.productData.selectedColor,
            );
        }

        function updateQuantity() {
            quantityInput.value = quantity;

            decreaseBtn.disabled = quantity <= 1;

            increaseBtn.disabled = quantity >= currentStock;

            decreaseBtn.classList.toggle('opacity-40', decreaseBtn.disabled);

            increaseBtn.classList.toggle('opacity-40', increaseBtn.disabled);

            decreaseBtn.classList.toggle('cursor-not-allowed', decreaseBtn.disabled);

            increaseBtn.classList.toggle('cursor-not-allowed', increaseBtn.disabled);
        }

        function updateStockStatus(stock) {
            if (stock > 0) {
                stockStatus.textContent = 'In Stock';

                stockStatus.classList.remove('text-red-500');

                stockStatus.classList.add('text-lime-600');
            } else {
                stockStatus.textContent = 'Out of Stock';

                stockStatus.classList.remove('text-lime-600');

                stockStatus.classList.add('text-red-500');
            }
        }

        function updateVariant() {
            const variant = getSelectedVariant();

            {{-- No matching variant --}}
            if (!variant) {
                currentStock = 0;

                quantity = 0;

                updateQuantity();

                document.getElementById('productPrice').textContent = 'Unavailable';

                updateStockStatus(0);

                addToCartBtn.disabled = true;

                addToCartBtn.innerHTML = `
                    <i class="fa-solid fa-ban"></i>
                    <span>Unavailable</span>
                `;

                return;
            }

            {{-- Update price --}}
            document.getElementById('productPrice').textContent = Number(variant.price).toLocaleString() + ' MMK';

            {{-- Update stock --}}
            currentStock = Number(variant.stock);

            updateStockStatus(currentStock);

            {{-- Reset quantity --}}
            quantity = currentStock > 0 ? 1 : 0;

            updateQuantity();

            {{-- Update image --}}
            if (variant.images && variant.images.length > 0) {
                document.getElementById('detailPreviewImage').src = variant.images[0];
            }

            {{-- Out of stock --}}
            if (currentStock <= 0) {
                addToCartBtn.disabled = true;

                addToCartBtn.innerHTML = `
                    <i class="fa-solid fa-ban"></i>
                    <span>Out of Stock</span>
                `;

                return;
            }

            {{-- In stock --}}
            addToCartBtn.disabled = false;

            addToCartBtn.innerHTML = `
                <i class="fa-solid fa-cart-shopping"></i>
                <span>Add To Cart</span>
            `;
        }

        increaseBtn.addEventListener('click', () => {
            if (quantity < currentStock) {
                quantity++;

                updateQuantity();
            }
        });

        decreaseBtn.addEventListener('click', () => {
            if (quantity > 1) {
                quantity--;

                updateQuantity();
            }
        });

        updateVariant();
    </script>

    {{-- =============================================================
        CAPACITY
    ============================================================== --}}
    <script>
        document.querySelectorAll('.capacity-btn').forEach((button) => {
            button.addEventListener('click', function () {
                window.productData.selectedCapacity = this.dataset.capacity;

                document.getElementById('selectedCapacity').textContent = this.dataset.capacity;

                document.querySelectorAll('.capacity-btn').forEach((btn) => {
                    btn.classList.remove('bg-lime-400', 'border-lime-400', 'text-gray-900');

                    btn.classList.add('bg-white', 'border-gray-200');
                });

                this.classList.remove('bg-white', 'border-gray-200');

                this.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');

                updateVariant();
            });
        });

        {{-- Initial selected capacity --}}
        document.querySelectorAll('.capacity-btn').forEach((button) => {
            if (button.dataset.capacity === window.productData.selectedCapacity) {
                button.classList.remove('bg-white', 'border-gray-200');

                button.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');
            }
        });
    </script>

    {{-- =============================================================
        COLOR
    ============================================================== --}}
    <script>
        document.querySelectorAll('.color-btn').forEach((button) => {
            button.addEventListener('click', function () {
                window.productData.selectedColor = this.dataset.color;

                document.getElementById('selectedColor').textContent = this.dataset.color;

                document.querySelectorAll('.color-btn').forEach((btn) => {
                    btn.classList.remove('bg-lime-400', 'border-lime-400', 'text-gray-900');

                    btn.classList.add('bg-white', 'border-gray-200');
                });

                this.classList.remove('bg-white', 'border-gray-200');

                this.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');

                updateVariant();
            });
        });

        {{-- Initial selected color --}}
        document.querySelectorAll('.color-btn').forEach((button) => {
            if (button.dataset.color === window.productData.selectedColor) {
                button.classList.remove('bg-white', 'border-gray-200');

                button.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');
            }
        });
    </script>

    {{-- =============================================================
        IMAGE GALLERY
    ============================================================== --}}
    <script>
        const previewImage = document.getElementById('detailPreviewImage');

        const thumbnails = document.querySelectorAll('.detail-thumb-btn');

        const thumbnailContainer = document.getElementById('detailThumbnailContainer');

        let currentImageIndex = 0;

        function setActiveThumbnail(index) {
            thumbnails.forEach((thumb, i) => {
                thumb.classList.remove('border-lime-400');

                thumb.classList.add('border-transparent');

                if (i === index) {
                    thumb.classList.remove('border-transparent');

                    thumb.classList.add('border-lime-400');
                }
            });
        }

        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', function () {
                previewImage.src = this.dataset.src;

                currentImageIndex = index;

                setActiveThumbnail(index);
            });
        });

        document.getElementById('detailPrevBtn').addEventListener('click', () => {
            if (!thumbnails.length) return;

            currentImageIndex = (currentImageIndex - 1 + thumbnails.length) % thumbnails.length;

            previewImage.src = thumbnails[currentImageIndex].dataset.src;

            setActiveThumbnail(currentImageIndex);
        });

        document.getElementById('detailNextBtn').addEventListener('click', () => {
            if (!thumbnails.length) return;

            currentImageIndex = (currentImageIndex + 1) % thumbnails.length;

            previewImage.src = thumbnails[currentImageIndex].dataset.src;

            setActiveThumbnail(currentImageIndex);
        });

        document.getElementById('detailThumbPrev').addEventListener('click', () => {
            thumbnailContainer.scrollBy({
                left: -250,
                behavior: 'smooth',
            });
        });

        document.getElementById('detailThumbNext').addEventListener('click', () => {
            thumbnailContainer.scrollBy({
                left: 250,
                behavior: 'smooth',
            });
        });

        setActiveThumbnail(0);
    </script>

    {{-- =============================================================
        ADD TO CART
    ============================================================== --}}
    <script>
        const isLoggedIn = @json (Auth::check());

        addToCartBtn.addEventListener('click', async () => {
            {{-- Guest --}}
            if (!isLoggedIn) {
                Swal.fire({
                    icon: 'warning',

                    title: 'Login Required',

                    text: 'Please login before adding products to your cart.',

                    confirmButtonText: 'Login',

                    showCancelButton: true,

                    cancelButtonText: 'Cancel',

                    confirmButtonColor: '#a3e635',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('user#loginRedirect') }}';
                    }
                });

                return;
            }

            const capacity = window.productData.selectedCapacity;

            const color = window.productData.selectedColor;

            const variant = window.productData.variants.find((item) => item.capacity === capacity && item.color === color);

            {{-- Invalid variant --}}
            if (!variant) {
                showCartAlert('warning', 'Please select a valid variant.');

                return;
            }

            const quantity = Number(document.getElementById('quantity').value);

            {{-- Invalid quantity --}}
            if (quantity <= 0) {
                showCartAlert('warning', 'Invalid quantity.');

                return;
            }

            {{-- Stock --}}
            if (quantity > variant.stock) {
                showCartAlert('error', 'Not enough stock.');

                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch('{{ route('user#addToCart') }}', {
                    method: 'POST',

                    headers: {
                        'Content-Type': 'application/json',

                        Accept: 'application/json',

                        'X-CSRF-TOKEN': csrfToken,
                    },

                    body: JSON.stringify({
                        variant_id: variant.id,

                        quantity: quantity,
                    }),
                });

                {{-- Session expired --}}
                if (response.status === 419) {
                    showCartAlert('warning', 'Your session expired. Please refresh the page.');

                    return;
                }

                {{-- Validation error --}}
                if (response.status === 422) {
                    const data = await response.json();

                    console.error('Validation response:', data);

                    showCartAlert('error', data.message ?? 'Unable to add this item to your cart.');

                    return;
                }

                {{-- Server error --}}
                if (!response.ok) {
                    console.error('Server error:', response.status, await response.text());

                    showCartAlert('error', 'Unable to add this item to your cart.');

                    return;
                }

                const data = await response.json();

                {{-- Success --}}
                if (data.success) {
                    showCartAlert('success', 'Added item to your cart');

                    const cartCount = document.getElementById('cartCount');

                    if (cartCount) {
                        cartCount.textContent = data.cartCount;

                        if (data.cartCount > 0) {
                            cartCount.classList.remove('hidden');

                            cartCount.classList.add('flex');
                        } else {
                            cartCount.classList.add('hidden');

                            cartCount.classList.remove('flex');
                        }
                    }
                } else {
                    console.error('Cart error:', data);

                    showCartAlert('error', 'Unable to add this item to your cart.');
                }
            } catch (error) {
                console.error('Add to cart error:', error);

                showCartAlert('error', 'Unable to add this item to your cart.');
            }
        });
    </script>

    {{-- =============================================================
        RATING STARS
    ============================================================== --}}
    <script>
        const stars = document.querySelectorAll('.star');

        const inputs = document.querySelectorAll('.rating-input');

        stars.forEach((star) => {
            star.addEventListener('click', function () {
                const value = this.dataset.value;

                inputs.forEach((input) => {
                    input.checked = input.value === value;
                });

                stars.forEach((item) => {
                    if (Number(item.dataset.value) <= Number(value)) {
                        item.classList.remove('text-gray-300', 'bg-white');

                        item.classList.add('text-yellow-400', 'bg-yellow-50');
                    } else {
                        item.classList.remove('text-yellow-400', 'bg-yellow-50');

                        item.classList.add('text-gray-300', 'bg-white');
                    }
                });
            });
        });
    </script>

    {{-- =============================================================
        SWEET ALERT
    ============================================================== --}}
    <script>
        function showCartAlert(icon, message) {
            Swal.fire({
                toast: true,

                position: 'top',

                icon: icon,

                text: message,

                showConfirmButton: false,

                timer: 2500,

                timerProgressBar: true,
            });
        }
    </script>

    {{-- =============================================================
        REVIEW TABS
    ============================================================== --}}
    <script>
        function showReviewTab(tab) {
            document.getElementById('myReview').classList.add('hidden');

            document.getElementById('allReviews').classList.add('hidden');

            document.getElementById('myReviewBtn').classList.remove('border-lime-400', 'text-gray-900');

            document.getElementById('myReviewBtn').classList.add('border-transparent', 'text-gray-400');

            document.getElementById('allReviewsBtn').classList.remove('border-lime-400', 'text-gray-900');

            document.getElementById('allReviewsBtn').classList.add('border-transparent', 'text-gray-400');

            document.getElementById(tab).classList.remove('hidden');

            if (tab === 'myReview') {
                document.getElementById('myReviewBtn').classList.remove('border-transparent', 'text-gray-400');

                document.getElementById('myReviewBtn').classList.add('border-lime-400', 'text-gray-900');
            } else {
                document.getElementById('allReviewsBtn').classList.remove('border-transparent', 'text-gray-400');

                document.getElementById('allReviewsBtn').classList.add('border-lime-400', 'text-gray-900');
            }
        }
    </script>

    {{-- =============================================================
        SUCCESS ALERT
    ============================================================== --}}
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',

                    title: 'Success',

                    text: @json (session('success')),

                    confirmButtonColor: '#a3e635',
                });
            });
        </script>

    @endif

    {{-- =============================================================
        RATING ERROR
    ============================================================== --}}
    @if ($errors->has('rating'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'warning',

                    title: 'Rating Required',

                    text: @json ($errors->first('rating')),

                    confirmButtonColor: '#a3e635',
                });
            });
        </script>

    @endif

    {{-- Small scrollbar utility --}}
    <style>
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

@endsection
