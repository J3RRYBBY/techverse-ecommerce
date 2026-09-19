document.addEventListener('DOMContentLoaded', () => {
    // ============================================================
    // CHECK PRODUCT DETAIL PAGE
    // ============================================================

    const addToCartBtn = document.getElementById('addToCartBtn');

    // If this page is not the product detail page, stop this script.
    if (!addToCartBtn) {
        return;
    }

    // ============================================================
    // VARIANT + QUANTITY
    // ============================================================

    const decreaseBtn = document.getElementById('decrease');
    const increaseBtn = document.getElementById('increase');
    const quantityInput = document.getElementById('quantity');
    const stockStatus = document.getElementById('stockStatus');
    const productPrice = document.getElementById('productPrice');
    const previewImage = document.getElementById('detailPreviewImage');

    let quantity = 1;
    let currentStock = 0;

    // ============================================================
    // GET SELECTED VARIANT
    // ============================================================

    function getSelectedVariant() {
        if (!window.productData || !Array.isArray(window.productData.variants)) {
            return null;
        }

        return window.productData.variants.find(
            (variant) =>
                variant.capacity === window.productData.selectedCapacity &&
                variant.color === window.productData.selectedColor,
        );
    }

    // ============================================================
    // UPDATE QUANTITY
    // ============================================================

    function updateQuantity() {
        if (!quantityInput || !decreaseBtn || !increaseBtn) {
            return;
        }

        quantityInput.value = quantity;

        decreaseBtn.disabled = quantity <= 1;

        increaseBtn.disabled = quantity >= currentStock;

        decreaseBtn.classList.toggle('opacity-40', decreaseBtn.disabled);

        increaseBtn.classList.toggle('opacity-40', increaseBtn.disabled);

        decreaseBtn.classList.toggle('cursor-not-allowed', decreaseBtn.disabled);

        increaseBtn.classList.toggle('cursor-not-allowed', increaseBtn.disabled);
    }

    // ============================================================
    // UPDATE STOCK STATUS
    // ============================================================

    function updateStockStatus(stock) {
        if (!stockStatus) {
            return;
        }

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

    // ============================================================
    // UPDATE VARIANT
    // ============================================================

    function updateVariant() {
        const variant = getSelectedVariant();

        // --------------------------------------------------------
        // No matching variant
        // --------------------------------------------------------

        if (!variant) {
            currentStock = 0;

            quantity = 0;

            updateQuantity();

            if (productPrice) {
                productPrice.textContent = 'Unavailable';
            }

            updateStockStatus(0);

            addToCartBtn.disabled = true;

            addToCartBtn.innerHTML = `
                <i class="fa-solid fa-ban"></i>
                <span>Unavailable</span>
            `;

            return;
        }

        // --------------------------------------------------------
        // Update price
        // --------------------------------------------------------

        if (productPrice) {
            productPrice.textContent = Number(variant.price).toLocaleString() + ' MMK';
        }

        // --------------------------------------------------------
        // Update stock
        // --------------------------------------------------------

        currentStock = Number(variant.stock);

        updateStockStatus(currentStock);

        // --------------------------------------------------------
        // Reset quantity
        // --------------------------------------------------------

        quantity = currentStock > 0 ? 1 : 0;

        updateQuantity();

        // --------------------------------------------------------
        // Update image
        // --------------------------------------------------------

        if (previewImage && variant.images && variant.images.length > 0) {
            previewImage.src = variant.images[0];
        }

        // --------------------------------------------------------
        // Out of stock
        // --------------------------------------------------------

        if (currentStock <= 0) {
            addToCartBtn.disabled = true;

            addToCartBtn.innerHTML = `
                <i class="fa-solid fa-ban"></i>
                <span>Out of Stock</span>
            `;

            return;
        }

        // --------------------------------------------------------
        // In stock
        // --------------------------------------------------------

        addToCartBtn.disabled = false;

        addToCartBtn.innerHTML = `
            <i class="fa-solid fa-cart-shopping"></i>
            <span>Add To Cart</span>
        `;
    }

    // ============================================================
    // INCREASE QUANTITY
    // ============================================================

    if (increaseBtn) {
        increaseBtn.addEventListener('click', () => {
            if (quantity < currentStock) {
                quantity++;

                updateQuantity();
            }
        });
    }

    // ============================================================
    // DECREASE QUANTITY
    // ============================================================

    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', () => {
            if (quantity > 1) {
                quantity--;

                updateQuantity();
            }
        });
    }

    // ============================================================
    // CAPACITY
    // ============================================================

    const capacityButtons = document.querySelectorAll('.capacity-btn');

    capacityButtons.forEach((button) => {
        button.addEventListener('click', function () {
            window.productData.selectedCapacity = this.dataset.capacity;

            const selectedCapacity = document.getElementById('selectedCapacity');

            if (selectedCapacity) {
                selectedCapacity.textContent = this.dataset.capacity;
            }

            // Reset all capacity buttons

            capacityButtons.forEach((btn) => {
                btn.classList.remove('bg-lime-400', 'border-lime-400', 'text-gray-900');

                btn.classList.add('bg-white', 'border-gray-200');
            });

            // Active capacity

            this.classList.remove('bg-white', 'border-gray-200');

            this.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');

            updateVariant();
        });
    });

    // ============================================================
    // INITIAL SELECTED CAPACITY
    // ============================================================

    capacityButtons.forEach((button) => {
        if (button.dataset.capacity === window.productData.selectedCapacity) {
            button.classList.remove('bg-white', 'border-gray-200');

            button.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');
        }
    });

    // ============================================================
    // COLOR
    // ============================================================

    const colorButtons = document.querySelectorAll('.color-btn');

    colorButtons.forEach((button) => {
        button.addEventListener('click', function () {
            window.productData.selectedColor = this.dataset.color;

            const selectedColor = document.getElementById('selectedColor');

            if (selectedColor) {
                selectedColor.textContent = this.dataset.color;
            }

            // Reset all color buttons

            colorButtons.forEach((btn) => {
                btn.classList.remove('bg-lime-400', 'border-lime-400', 'text-gray-900');

                btn.classList.add('bg-white', 'border-gray-200');
            });

            // Active color

            this.classList.remove('bg-white', 'border-gray-200');

            this.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');

            updateVariant();
        });
    });

    // ============================================================
    // INITIAL SELECTED COLOR
    // ============================================================

    colorButtons.forEach((button) => {
        if (button.dataset.color === window.productData.selectedColor) {
            button.classList.remove('bg-white', 'border-gray-200');

            button.classList.add('bg-lime-400', 'border-lime-400', 'text-gray-900');
        }
    });

    // ============================================================
    // IMAGE GALLERY
    // ============================================================

    const thumbnails = document.querySelectorAll('.detail-thumb-btn');

    const thumbnailContainer = document.getElementById('detailThumbnailContainer');

    const detailPrevBtn = document.getElementById('detailPrevBtn');

    const detailNextBtn = document.getElementById('detailNextBtn');

    const detailThumbPrev = document.getElementById('detailThumbPrev');

    const detailThumbNext = document.getElementById('detailThumbNext');

    let currentImageIndex = 0;

    // ============================================================
    // ACTIVE THUMBNAIL
    // ============================================================

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

    // ============================================================
    // THUMBNAIL CLICK
    // ============================================================

    thumbnails.forEach((thumbnail, index) => {
        thumbnail.addEventListener('click', function () {
            if (!previewImage) {
                return;
            }

            previewImage.src = this.dataset.src;

            currentImageIndex = index;

            setActiveThumbnail(index);
        });
    });

    // ============================================================
    // PREVIOUS IMAGE
    // ============================================================

    if (detailPrevBtn) {
        detailPrevBtn.addEventListener('click', () => {
            if (!thumbnails.length || !previewImage) {
                return;
            }

            currentImageIndex = (currentImageIndex - 1 + thumbnails.length) % thumbnails.length;

            previewImage.src = thumbnails[currentImageIndex].dataset.src;

            setActiveThumbnail(currentImageIndex);
        });
    }

    // ============================================================
    // NEXT IMAGE
    // ============================================================

    if (detailNextBtn) {
        detailNextBtn.addEventListener('click', () => {
            if (!thumbnails.length || !previewImage) {
                return;
            }

            currentImageIndex = (currentImageIndex + 1) % thumbnails.length;

            previewImage.src = thumbnails[currentImageIndex].dataset.src;

            setActiveThumbnail(currentImageIndex);
        });
    }

    // ============================================================
    // THUMBNAIL SCROLL LEFT
    // ============================================================

    if (detailThumbPrev) {
        detailThumbPrev.addEventListener('click', () => {
            if (!thumbnailContainer) {
                return;
            }

            thumbnailContainer.scrollBy({
                left: -250,
                behavior: 'smooth',
            });
        });
    }

    // ============================================================
    // THUMBNAIL SCROLL RIGHT
    // ============================================================

    if (detailThumbNext) {
        detailThumbNext.addEventListener('click', () => {
            if (!thumbnailContainer) {
                return;
            }

            thumbnailContainer.scrollBy({
                left: 250,
                behavior: 'smooth',
            });
        });
    }

    // ============================================================
    // INITIAL THUMBNAIL
    // ============================================================

    if (thumbnails.length > 0) {
        setActiveThumbnail(0);
    }

    // ============================================================
    // ADD TO CART
    // ============================================================

    addToCartBtn.addEventListener('click', async () => {
        // --------------------------------------------------------
        // Guest
        // --------------------------------------------------------

        if (!window.cartConfig || !window.cartConfig.isLoggedIn) {
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
                    window.location.href = window.cartConfig.loginUrl;
                }
            });

            return;
        }

        // --------------------------------------------------------
        // Selected variant
        // --------------------------------------------------------

        const capacity = window.productData.selectedCapacity;

        const color = window.productData.selectedColor;

        const variant = window.productData.variants.find((item) => item.capacity === capacity && item.color === color);

        // --------------------------------------------------------
        // Invalid variant
        // --------------------------------------------------------

        if (!variant) {
            showCartAlert('warning', 'Please select a valid variant.');

            return;
        }

        // --------------------------------------------------------
        // Quantity
        // --------------------------------------------------------

        const selectedQuantity = quantityInput ? Number(quantityInput.value) : 0;

        // --------------------------------------------------------
        // Invalid quantity
        // --------------------------------------------------------

        if (selectedQuantity <= 0) {
            showCartAlert('warning', 'Invalid quantity.');

            return;
        }

        // --------------------------------------------------------
        // Stock
        // --------------------------------------------------------

        if (selectedQuantity > variant.stock) {
            showCartAlert('error', 'Not enough stock.');

            return;
        }

        // --------------------------------------------------------
        // Add to cart request
        // --------------------------------------------------------

        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');

            if (!csrfMeta) {
                console.error('CSRF token meta tag not found.');

                showCartAlert('error', 'Security token not found. Please refresh the page.');

                return;
            }

            const csrfToken = csrfMeta.getAttribute('content');

            const response = await fetch(window.cartConfig.addToCartUrl, {
                method: 'POST',

                headers: {
                    'Content-Type': 'application/json',

                    Accept: 'application/json',

                    'X-CSRF-TOKEN': csrfToken,
                },

                body: JSON.stringify({
                    variant_id: variant.id,

                    quantity: selectedQuantity,
                }),
            });

            // ----------------------------------------------------
            // Session expired
            // ----------------------------------------------------

            if (response.status === 419) {
                showCartAlert('warning', 'Your session expired. Please refresh the page.');

                return;
            }

            // ----------------------------------------------------
            // Validation error
            // ----------------------------------------------------

            if (response.status === 422) {
                const data = await response.json();

                console.error('Validation response:', data);

                showCartAlert('error', data.message ?? 'Unable to add this item to your cart.');

                return;
            }

            // ----------------------------------------------------
            // Server error
            // ----------------------------------------------------

            if (!response.ok) {
                console.error('Server error:', response.status, await response.text());

                showCartAlert('error', 'Unable to add this item to your cart.');

                return;
            }

            const data = await response.json();

            // ----------------------------------------------------
            // Success
            // ----------------------------------------------------

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

    // ============================================================
    // INITIAL VARIANT
    // ============================================================

    updateVariant();
});
