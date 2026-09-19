@extends ('layouts.user.app')

@section ('content')
    @php
        $initialVariant = $product->variants->first();
        $initialImage = $initialVariant?->images->first();
    @endphp

    <div class="w-[92%] mx-auto mt-6 sm:w-[90%] sm:mt-8 md:w-[85%] lg:w-[80%] xl:w-[70%]">
        {{-- Back --}}
        <a
            href="{{ route('user#productList') }}"
            class="inline-flex items-center gap-2 mb-5 text-sm text-gray-500 transition hover:text-gray-900 sm:mb-6"
        >
            <i class="fa-solid fa-arrow-left"></i>
            Back to Products
        </a>

        {{-- =========================================================
            PRODUCT SECTION
        ========================================================== --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 lg:gap-12 xl:gap-16">
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
                            class="object-contain w-full h-[300px] xs:h-[340px] sm:h-[400px] md:h-[480px] lg:h-[430px] xl:h-[520px] p-4 sm:p-6 md:p-8 lg:p-6 xl:p-10 transition duration-300"
                            alt="{{ $product->name }}"
                        />
                    @else
                        <div
                            class="flex items-center justify-center w-full h-[300px] sm:h-[400px] md:h-[480px] lg:h-[430px] xl:h-[520px] text-gray-300"
                        >
                            <i class="text-5xl sm:text-6xl fa-regular fa-image"></i>
                        </div>
                    @endif

                    {{-- Previous --}}
                    <button
                        id="detailPrevBtn"
                        type="button"
                        class="absolute flex items-center justify-center transition -translate-y-1/2 bg-white border border-gray-200 rounded-full shadow-sm w-9 h-9 sm:w-11 sm:h-11 left-2 sm:left-4 top-1/2 hover:bg-lime-400 hover:border-lime-400"
                    >
                        <i class="text-xs sm:text-sm fa-solid fa-chevron-left"></i>
                    </button>

                    {{-- Next --}}
                    <button
                        id="detailNextBtn"
                        type="button"
                        class="absolute flex items-center justify-center transition -translate-y-1/2 bg-white border border-gray-200 rounded-full shadow-sm w-9 h-9 sm:w-11 sm:h-11 right-2 sm:right-4 top-1/2 hover:bg-lime-400 hover:border-lime-400"
                    >
                        <i class="text-xs sm:text-sm fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                {{-- =================================================
                    THUMBNAILS
                ================================================== --}}
                <div
                    id="detailThumbnailContainer"
                    class="flex items-center gap-2 mt-2 overflow-x-auto scroll-smooth scrollbar-hide sm:gap-3"
                >
                    @foreach ($product->variants as $variant)
                        @foreach ($variant->images as $image)
                            <button
                                type="button"
                                class="flex-shrink-0 w-16 h-16 overflow-hidden transition border-2 border-transparent rounded-lg sm:w-20 sm:h-20 sm:rounded-xl detail-thumb-btn hover:border-lime-300"
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
                <div class="flex items-center gap-2 mt-4 sm:gap-3 sm:mt-5">
                    <button
                        type="button"
                        id="detailThumbPrev"
                        class="flex items-center justify-center flex-shrink-0 transition bg-white border border-gray-200 rounded-full w-9 h-9 sm:w-10 sm:h-10 hover:bg-lime-400 hover:border-lime-400"
                    >
                        <i class="text-xs fa-solid fa-chevron-left"></i>
                    </button>

                    <button
                        type="button"
                        id="detailThumbNext"
                        class="flex items-center justify-center flex-shrink-0 transition bg-white border border-gray-200 rounded-full w-9 h-9 sm:w-10 sm:h-10 hover:bg-lime-400 hover:border-lime-400"
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
                <div class="pb-5 border-b border-gray-100 sm:pb-6">
                    <h1 class="text-2xl font-semibold leading-tight sm:text-3xl md:text-4xl">{{ $product->name }}</h1>

                    {{-- Rating --}}
                    @php
                        $averageRating = $product->ratings->avg('rating') ?? 0;
                        $ratingCount = $product->ratings->count();
                    @endphp

                    <div class="flex flex-wrap items-center gap-2 mt-4 sm:gap-3">
                        <div class="flex gap-1">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="text-xs sm:text-sm fa-solid fa-star
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
                <div class="py-5 sm:py-6">
                    <p id="productPrice" class="text-2xl font-medium sm:text-3xl">
                        {{ number_format($initialVariant->price) }} MMK
                    </p>

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
                <div class="mb-6 sm:mb-7">
                    <div class="flex flex-wrap items-center gap-2 mb-3 sm:gap-3">
                        <p class="text-sm font-semibold">Capacity :</p>

                        <span id="selectedCapacity" class="text-sm font-medium text-gray-500">
                            {{ $initialVariant->capacity }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($product->variants->pluck('capacity')->unique() as $capacity)
                            <button
                                type="button"
                                class="px-4 py-2 text-sm font-medium transition border border-gray-200 rounded-lg sm:px-5 sm:py-2.5 hover:border-lime-400 hover:bg-lime-50"
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
                <div class="mb-6 sm:mb-7">
                    <div class="flex flex-wrap items-center gap-2 mb-3 sm:gap-3">
                        <p class="text-sm font-semibold">Color :</p>

                        <span id="selectedColor" class="text-sm font-medium text-gray-500">
                            {{ $initialVariant->color }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @foreach ($product->variants->pluck('color')->unique() as $color)
                            <button
                                type="button"
                                class="px-4 py-2 text-sm font-medium transition border border-gray-200 rounded-lg sm:px-5 sm:py-2.5 hover:border-lime-400 hover:bg-lime-50"
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
                <div class="flex flex-row items-center gap-2 pb-6 border-b border-gray-100 sm:gap-3 sm:pb-7">
                    {{-- Quantity --}}
                    <div
                        class="flex items-center justify-between flex-shrink-0 w-[120px] h-14 px-1.5 border border-gray-200 rounded-lg sm:w-36 sm:h-12 sm:px-2"
                    >
                        <button
                            id="decrease"
                            type="button"
                            class="flex items-center justify-center flex-shrink-0 text-lg text-gray-500 transition rounded-md w-9 h-9 hover:bg-gray-100 hover:text-gray-900"
                        >
                            <i class="text-xs fa-solid fa-minus"></i>
                        </button>

                        <input
                            type="text"
                            id="quantity"
                            value="1"
                            class="w-8 text-sm font-semibold text-center text-gray-900 outline-none sm:w-10"
                            readonly
                        />

                        <button
                            id="increase"
                            type="button"
                            class="flex items-center justify-center flex-shrink-0 text-lg text-gray-500 transition rounded-md w-9 h-9 hover:bg-gray-100 hover:text-gray-900"
                        >
                            <i class="text-xs fa-solid fa-plus"></i>
                        </button>
                    </div>

                    {{-- Add To Cart --}}
                    <button
                        id="addToCartBtn"
                        type="button"
                        class="flex items-center justify-center flex-1 gap-2 px-3 text-sm font-medium transition rounded-lg h-14 sm:h-12 sm:px-6 sm:text-base bg-lime-400 hover:bg-lime-500 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed"
                    >
                        <i class="text-sm fa-solid fa-cart-shopping"></i>
                        <span class="whitespace-nowrap">Add To Cart</span>
                    </button>
                </div>

                {{-- =================================================
                    DESCRIPTION
                ================================================== --}}
                <div class="pt-6 sm:pt-7">
                    <h2 class="mb-3 text-lg font-semibold sm:text-xl">Product Description</h2>

                    <p class="text-sm leading-7 whitespace-pre-line sm:text-base text-black/60">
                        {{ $product->description }}
                    </p>
                </div>
            </div>
        </div>

        {{-- =========================================================
            RELATED PRODUCTS
        ========================================================== --}}
        @if ($relatedProducts->count() > 0)
            <section class="mt-16 md:mt-24 lg:mt-28">
                <div class="flex flex-col gap-4 mb-7 sm:flex-row sm:items-center sm:justify-between sm:mb-8">
                    <div>
                        <p class="mb-2 text-xs font-semibold tracking-[0.2em] uppercase text-lime-600">You may also like</p>

                        <h2 class="text-2xl font-semibold text-gray-900 md:text-3xl">Related Products</h2>
                    </div>

                    <a href="{{ route('user#productList') }}" class="self-start text-sm font-medium sm:self-auto">
                        View All
                        <i class="ml-1 fa-solid fa-arrow-right"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 sm:gap-5">
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
                                <div class="p-4 space-y-2 text-base sm:p-5 sm:text-lg">
                                    <p class="text-black/70">{{ $relatedProduct->name }}</p>

                                    {{-- Rating --}}
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <div class="flex gap-0.5 text-sm text-yellow-400">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="text-sm fa-solid fa-star {{ $i <= round($relatedProduct->ratings_avg_rating ?? 0) ? 'text-yellow-400' : 'text-gray-300' }}"
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
                                            <p>{{ number_format($relatedVariant->price) }} MMK</p>
                                        </div>
                                    @else
                                        <div class="mt-3">
                                            <p>Price unavailable</p>
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
            </section>
        @endif

        {{-- =========================================================
            REVIEWS
        ========================================================== --}}
        <section class="pb-16 mt-16 md:mt-24 lg:mt-28 sm:pb-20">
            {{-- Review Header --}}
            <div class="mb-7 sm:mb-8">
                <p class="mb-2 text-xs font-semibold tracking-[0.2em] uppercase text-lime-600">Customer Feedback</p>

                <h2 class="text-2xl font-semibold text-gray-900 md:text-3xl">Reviews</h2>
            </div>

            {{-- Review Tabs --}}
            <div class="flex gap-6 overflow-x-auto border-b border-gray-200 scrollbar-hide sm:gap-8">
                <button
                    type="button"
                    id="myReviewBtn"
                    onclick="showReviewTab('myReview')"
                    class="flex-shrink-0 px-1 pb-4 text-sm font-semibold text-gray-900 border-b-2 border-lime-400"
                >
                    My Review
                </button>

                <button
                    type="button"
                    id="allReviewsBtn"
                    onclick="showReviewTab('allReviews')"
                    class="flex-shrink-0 px-1 pb-4 text-sm font-medium text-gray-400 border-b-2 border-transparent hover:text-gray-900"
                >
                    All Reviews
                </button>
            </div>

            {{-- =====================================================
                MY REVIEW
            ====================================================== --}}
            <div id="myReview" class="mt-7 sm:mt-8">
                <div class="w-full max-w-2xl p-5 mx-auto bg-gray-50 sm:p-8 md:p-10 rounded-2xl">
                    <div class="text-center">
                        <div class="flex items-center justify-center w-12 h-12 mx-auto mb-4 rounded-full bg-lime-100">
                            <i class="text-lime-600 fa-solid fa-star"></i>
                        </div>

                        <h3 class="text-xl font-semibold text-gray-900 sm:text-2xl">Give Feedback</h3>

                        <p class="max-w-md mx-auto mt-2 text-sm leading-6 text-gray-500">What do you think about this product and your experience?</p>
                    </div>

                    <form action="{{ route('user#feedback', $product) }}" method="POST" class="mt-7 sm:mt-8">
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
                                            class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 transition rounded-lg star
                                            {{
                                                $userRating && $i <= $userRating->rating
                                                    ? 'text-yellow-400 bg-yellow-50'
                                                    : 'text-gray-300 bg-white'
                                            }}"
                                            data-value="{{ $i }}"
                                        >
                                            <i class="text-base sm:text-lg fa-solid fa-star"></i>
                                        </span>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        {{-- Comment --}}
                        <div class="mt-6 sm:mt-7">
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
                                class="w-full py-3 text-sm font-semibold transition rounded-lg px-7 sm:w-auto bg-lime-400 hover:bg-lime-500"
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
            <div id="allReviews" class="hidden mt-7 sm:mt-8">
                <div class="w-full max-w-3xl mx-auto">
                    @forelse ($ratings as $rating)
                        @php
                            $comment = $comments->where('user_id', $rating->user_id)->first();
                        @endphp

                        <div class="p-4 mb-4 bg-white border border-gray-100 sm:p-6 rounded-2xl">
                            {{-- User --}}
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-3">
                                    <img
                                        src="{{ $rating->user->profile_image
                                            ? $rating->user->profile_image
                                            : asset('defaultImage/user.png') }}"
                                        alt="{{ $rating->user->name }}"
                                        class="object-cover w-10 h-10 border border-gray-100 rounded-full sm:w-11 sm:h-11"
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
                        <div class="py-12 text-center bg-gray-50 sm:py-16 rounded-2xl">
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

        window.cartConfig = {
            isLoggedIn: @json (Auth::check()),
            addToCartUrl: @json (route('user#addToCart')),
            loginUrl: @json (route('user#loginRedirect')),
        };
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
