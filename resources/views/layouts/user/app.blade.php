<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>E-commerce</title>

        <!-- Scripts -->
        @vite (['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="flex flex-col min-h-screen font-inter">
        <main class="flex-1">
            <!-- Navbar -->
            <nav class="relative z-50 py-3">
                <div class="w-[90%] sm:w-[85%] lg:w-[70%] mx-auto">
                    @php
                        $cartCount = auth()->check() ? auth()->user()->carts()->sum('quantity') : 0;
                    @endphp

                    <!-- Desktop / Mobile Header -->
                    <div class="flex items-center justify-between">
                        <!-- Logo -->
                        <a href="{{ route('user#home') }}" class="text-2xl font-gugi">
                            tech<span class="font-bold text-lime-400">V</span>erse
                        </a>

                        <!-- Desktop Navigation -->
                        <div class="items-center hidden gap-10 lg:flex">
                            <a href="{{ route('user#home') }}">Home</a>
                            <a href="{{ route('user#productList') }}">Product</a>
                            <a href="{{ route('user#category') }}">Category</a>
                            <a href="{{ route('user#myOrder') }}">My Order</a>
                            <a href="{{ route('user#contact') }}">Contact</a>
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center gap-3">
                            <!-- Cart -->
                            <button id="openCart" type="button" class="flex items-center gap-2">
                                <i class="text-xl fa-solid fa-bag-shopping text-lime-400"></i>

                                <span class="hidden lg:inline"> CART </span>

                                <span
                                    id="cartCount"
                                    class="{{ $cartCount > 0 ? 'flex' : 'hidden' }} items-center justify-center text-sm border rounded-full size-6 border-lime-400"
                                >
                                    {{ $cartCount }}
                                </span>
                            </button>

                            <!-- User -->
                            @if (Auth::check())
                                <el-dropdown class="flex items-center">
                                    <button class="border rounded-full">
                                        <img
                                            src="{{ auth()->user()->profile ?? asset('defaultImage/user.png') }}"
                                            class="rounded-full w-9 h-9 lg:w-10 lg:h-10"
                                            aria-hidden="true"
                                        />
                                    </button>

                                    <el-menu
                                        anchor="bottom end"
                                        popover
                                        class="m-0 w-56 border border-gray-300 origin-top-right rounded-md bg-gray-50 p-0 outline outline-1 -outline-offset-1 outline-white/10 transition [--anchor-gap:theme(spacing.2)] [transition-behavior:allow-discrete] data-[closed]:scale-95 data-[closed]:transform data-[closed]:opacity-0 data-[closed]:duration-75"
                                    >
                                        <div>
                                            <a
                                                href="{{ route('user#profile') }}"
                                                class="block px-4 py-3 text-sm text-black focus:bg-gray-200"
                                            >
                                                Account settings
                                            </a>

                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf

                                                <button
                                                    type="submit"
                                                    class="block w-full px-4 py-3 text-sm text-left text-black focus:bg-gray-200"
                                                >
                                                    Sign out
                                                </button>
                                            </form>
                                        </div>
                                    </el-menu>
                                </el-dropdown>

                            @else
                                <a href="{{ route('login') }}" class="hidden sm:block">
                                    <div class="flex items-center">
                                        <i class="mr-1 text-xl fa-regular fa-circle-user"></i>

                                        <p class="uppercase">Sign Up</p>
                                    </div>
                                </a>

                            @endif

                            <!-- Mobile Menu Button -->
                            <button
                                id="mobileMenuButton"
                                type="button"
                                class="flex items-center justify-center w-10 h-10 lg:hidden"
                                aria-label="Open menu"
                            >
                                <i id="mobileMenuIcon" class="text-xl fa-solid fa-bars"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Mobile Menu -->
                    <div
                        id="mobileMenu"
                        class="absolute left-0 z-50 hidden w-full mt-4 border border-t shadow-xl bg-white/65 border-white/20 backdrop-blur-xl lg:hidden"
                    >
                        <div class="flex flex-col px-3 py-3">
                            <a href="{{ route('user#home') }}" class="px-3 py-3 transition hover:bg-gray-100"> Home </a>

                            <a href="{{ route('user#productList') }}" class="px-3 py-3 transition hover:bg-gray-100">
                                Product
                            </a>

                            <a href="{{ route('user#category') }}" class="px-3 py-3 transition hover:bg-gray-100">
                                Category
                            </a>

                            <a href="{{ route('user#myOrder') }}" class="px-3 py-3 transition hover:bg-gray-100">
                                My Order
                            </a>

                            <a href="{{ route('user#contact') }}" class="px-3 py-3 transition hover:bg-gray-100">
                                Contact
                            </a>

                            @if (!Auth::check())
                                <a href="{{ route('login') }}" class="px-3 py-3 transition hover:bg-gray-100">
                                    Sign Up
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Cart Drawer -->
                    <div>
                        <div id="cartOverlay" class="fixed inset-0 z-40 hidden bg-black/40"></div>

                        <div
                            id="cartDrawer"
                            class="fixed top-0 right-0 z-50 flex flex-col w-full h-full max-w-[400px] translate-x-full bg-white transition-transform duration-300"
                        >
                            <div class="flex items-center justify-between p-5 border-b">
                                <h2 class="text-lg font-medium">Shopping Cart</h2>

                                <button id="closeCart" type="button">✕</button>
                            </div>

                            <div id="cartItems" class="flex-1 p-5 overflow-y-auto">
                                <!-- Cart items will go here -->
                            </div>

                            <div id="cartFooter" class="p-5 border-t"></div>
                        </div>
                    </div>
                </div>
            </nav>

            @yield ('content')
        </main>

        <!-- Footer -->
        <footer class="w-full bg-footer">
            <div class="text-white">
                <div
                    class="grid w-[90%] sm:w-[85%] lg:max-w-[80%] grid-cols-1 gap-10 py-10 mx-auto lg:grid-cols-2 lg:py-14"
                >
                    <!-- Left Side -->
                    <div class="grid grid-cols-1 gap-8 sm:grid-cols-2">
                        <!-- Logo -->
                        <div class="flex justify-center sm:justify-start">
                            <a href="{{ route('user#home') }}" class="text-2xl font-gugi">
                                tech<span class="font-bold text-lime-400">V</span>erse
                            </a>
                        </div>

                        <!-- Categories + Quick Links -->
                        <div class="grid grid-cols-2 gap-6 sm:flex sm:justify-around">
                            <!-- Category -->
                            <div>
                                <h3 class="text-xl">Category</h3>

                                <ul class="mt-3 text-sm text-white/50">
                                    @foreach ($footerCategories as $category)
                                        <li class="mb-1">
                                            <a
                                                href="{{ route('user#productList', ['category' => $category->id]) }}"
                                                class="transition hover:text-white"
                                            >
                                                {{ $category->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <!-- Quick Links -->
                            <div>
                                <h3 class="text-xl">Quick Links</h3>

                                <ul class="mt-3 text-sm text-white/50">
                                    <li class="mb-1">About Us</li>

                                    <li class="mb-1">
                                        <a href="{{ route('user#contact') }}" class="transition hover:text-white">
                                            Contact Us
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="flex flex-col items-center lg:items-center">
                        <div class="text-center lg:text-left">
                            <h3 class="text-xl">FOLLOW US</h3>

                            <div
                                class="flex flex-wrap justify-center mt-3 text-sm gap-x-5 gap-y-2 text-white/50 lg:justify-start"
                            >
                                <a href=""> Instagram </a>

                                <a href=""> Youtube </a>

                                <a href=""> X </a>

                                <a href=""> Facebook </a>

                                <a href=""> LinkedIn </a>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-none bg-white/20" style="height: 1px" />

                <div
                    class="w-[90%] sm:w-[85%] lg:max-w-[80%] mx-auto py-6 text-sm text-center sm:text-left sm:py-8 text-white/50"
                >
                    <span> &copy; 2026 techVerse. All rights reserved </span>
                </div>
            </div>
        </footer>
    </body>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

    <script>
        const openCart = document.getElementById('openCart');
        const closeCart = document.getElementById('closeCart');
        const cartDrawer = document.getElementById('cartDrawer');
        const cartOverlay = document.getElementById('cartOverlay');

        function openCartDrawer() {
            cartDrawer.classList.remove('translate-x-full');

            cartOverlay.classList.remove('hidden');
        }

        function closeCartDrawer() {
            cartDrawer.classList.add('translate-x-full');

            cartOverlay.classList.add('hidden');
        }

        openCart.addEventListener('click', async () => {
            await loadCart();

            openCartDrawer();
        });

        closeCart.addEventListener('click', () => {
            closeCartDrawer();
        });

        cartOverlay.addEventListener('click', () => {
            closeCartDrawer();
        });
    </script>

    <script>
        async function loadCart() {
            try {
                const response = await fetch('{{ route('user#cart') }}', {
                    method: 'GET',
                    headers: {
                        Accept: 'application/json',
                    },
                });

                console.log('Cart status:', response.status);

                if (!response.ok) {
                    const text = await response.text();

                    console.error('Cart request failed:', response.status, text);

                    return;
                }

                const contentType = response.headers.get('content-type');

                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();

                    console.error('Cart returned HTML instead of JSON:', text);

                    return;
                }

                const data = await response.json();

                console.log('Cart data:', data);

                const cartItems = document.getElementById('cartItems');
                const cartFooter = document.getElementById('cartFooter');

                if (!cartItems) {
                    console.error('cartItems element not found.');

                    return;
                }

                cartItems.innerHTML = '';

                const cart = data.cart ?? [];

                // Empty cart
                if (cart.length === 0) {
                    cartItems.innerHTML = `
                        <div class="py-10 text-center">
                            <p class="text-black/50">
                                Your cart is empty.
                            </p>
                        </div>
                    `;

                    cartFooter.innerHTML = '';

                    return;
                }

                // Start subtotal
                let subtotal = 0;

                // Display cart items
                cart.forEach((item) => {
                    const price = Number(item.price);
                    const quantity = Number(item.quantity);

                    // Calculate subtotal
                    subtotal += price * quantity;

                    const image = item.image ? item.image : `{{ asset('defaultImage/product.png') }}`;

                    cartItems.innerHTML += `
                        <div class="flex gap-3 py-4 border-b last:border-b-0">

                            <img
                                src="${image}"
                                class="flex-shrink-0 object-cover w-16 h-16 rounded-md sm:w-20 sm:h-20"
                                alt="${item.name}"
                            >

                            <div class="flex-1 min-w-0">

                                <div class="flex justify-between gap-3">

                                    <div class="min-w-0">
                                        <h3 class="text-sm font-medium truncate">
                                            ${item.name}
                                        </h3>

                                        <p class="mt-1 text-xs text-black/50">
                                            ${item.capacity} / ${item.color}
                                        </p>
                                    </div>

                                    <p class="text-sm font-medium text-right whitespace-nowrap">
                                        ${price.toLocaleString()} MMK
                                    </p>

                                </div>

                                <div class="flex justify-between mt-5">

                                    <p class="text-xs">
                                        Qty: ${quantity}
                                    </p>

                                    <button
                                        type="button"
                                        onclick="removeFromCart(${item.variant_id})"
                                        class="text-xs text-red-500"
                                    >
                                        Remove
                                    </button>

                                </div>

                            </div>

                        </div>
                    `;
                });

                // Add subtotal to cart drawer
                const continueShoppingUrl = '{{ route('user#productList') }}';

                cartFooter.innerHTML = `
                    <div class="flex items-start justify-between gap-4 mb-4">

                        <span>
                            Subtotal

                            <p class="mt-1 text-xs text-black/60">
                                Shipping and taxes calculated at checkout.
                            </p>
                        </span>

                        <span class="mt-1 text-sm whitespace-nowrap">
                            ${subtotal.toLocaleString()} MMK
                        </span>

                    </div>

                    <a
                        href="{{ route('user#checkout') }}"
                        class="block w-full py-3 mt-2 text-sm font-medium text-center rounded bg-lime-400"
                    >
                        Checkout
                    </a>

                    <a
                        href="${continueShoppingUrl}"
                        class="flex items-center justify-center gap-1 mt-4 text-sm"
                    >
                        <span class="text-black/60">
                            or
                        </span>

                        Continue Shopping

                        <x-heroicon-o-arrow-long-right class="size-4" />
                    </a>
                `;
            } catch (error) {
                console.error('Load cart error:', error);
            }
        }
    </script>

    <script>
        async function removeFromCart(variantId) {
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const response = await fetch(`{{ url('/cart/remove') }}/${variantId}`, {
                    method: 'DELETE',

                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                console.log('Remove status:', response.status);

                if (response.status === 419) {
                    alert('Your session has expired.');

                    location.reload();

                    return;
                }

                if (!response.ok) {
                    const text = await response.text();

                    console.error('Remove cart error:', response.status, text);

                    return;
                }

                const contentType = response.headers.get('content-type');

                if (!contentType || !contentType.includes('application/json')) {
                    const text = await response.text();

                    console.error('Expected JSON but received:', text);

                    return;
                }

                const data = await response.json();

                console.log('Remove response:', data);

                if (data.success) {
                    // Update cart count
                    const cartCount = document.getElementById('cartCount');

                    cartCount.textContent = data.cartCount;

                    if (data.cartCount > 0) {
                        cartCount.classList.remove('hidden');

                        cartCount.classList.add('flex');
                    } else {
                        cartCount.classList.add('hidden');

                        cartCount.classList.remove('flex');
                    }

                    // Refresh drawer contents
                    await loadCart();
                } else {
                    alert(data.message ?? 'Could not remove item.');
                }
            } catch (error) {
                console.error('Remove from cart error:', error);
            }
        }
    </script>

    <script>
        const mobileMenuButton = document.getElementById('mobileMenuButton');
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileMenuIcon = document.getElementById('mobileMenuIcon');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');

            if (mobileMenu.classList.contains('hidden')) {
                mobileMenuIcon.classList.remove('fa-xmark');
                mobileMenuIcon.classList.add('fa-bars');
            } else {
                mobileMenuIcon.classList.remove('fa-bars');
                mobileMenuIcon.classList.add('fa-xmark');
            }
        });
    </script>
</html>
