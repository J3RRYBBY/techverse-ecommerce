// PRODUCT DETAIL ELEMENTS

const detailPreviewImage = document.getElementById("detailPreviewImage");

const detailPrevBtn = document.getElementById("detailPrevBtn");

const detailNextBtn = document.getElementById("detailNextBtn");

const detailThumbnailContainer = document.getElementById(
    "detailThumbnailContainer",
);

const detailThumbPrev = document.getElementById("detailThumbPrev");

const detailThumbNext = document.getElementById("detailThumbNext");

let detailThumbnails = document.querySelectorAll(".detail-thumb-btn");

let currentIndex = 0;

const thumbnailScrollAmount = 92;

// SHOW IMAGE

function showDetailImage(index) {
    if (!detailPreviewImage || detailThumbnails.length === 0) {
        return;
    }

    // Previous
    if (index < 0) {
        index = detailThumbnails.length - 1;
    }

    // Next
    if (index >= detailThumbnails.length) {
        index = 0;
    }

    currentIndex = index;

    const thumbnail = detailThumbnails[currentIndex];

    // Change main image
    detailPreviewImage.src = thumbnail.dataset.src;

    // Remove active style
    detailThumbnails.forEach(function (item) {
        item.classList.remove("border-2", "border-lime-400");
    });

    // Add active style
    thumbnail.classList.add("border-2", "border-lime-400");

    // Scroll selected thumbnail
    thumbnail.scrollIntoView({
        behavior: "smooth",
        block: "nearest",
        inline: "center",
    });
}

// THUMBNAIL EVENTS

function addThumbnailEvents() {
    detailThumbnails = document.querySelectorAll(".detail-thumb-btn");

    detailThumbnails.forEach(function (thumbnail, index) {
        thumbnail.addEventListener("click", function () {
            showDetailImage(index);
        });
    });
}

// PREVIOUS IMAGE

detailPrevBtn?.addEventListener("click", function () {
    showDetailImage(currentIndex - 1);
});

// NEXT IMAGE

detailNextBtn?.addEventListener("click", function () {
    showDetailImage(currentIndex + 1);
});

// THUMBNAIL SCROLL LEFT

detailThumbPrev?.addEventListener("click", function () {
    detailThumbnailContainer?.scrollBy({
        left: -thumbnailScrollAmount,
        behavior: "smooth",
    });
});

// THUMBNAIL SCROLL RIGHT

detailThumbNext?.addEventListener("click", function () {
    detailThumbnailContainer?.scrollBy({
        left: thumbnailScrollAmount,
        behavior: "smooth",
    });
});

// PRODUCT VARIANTS

if (window.productData) {
    const variants = window.productData.variants;

    let selectedCapacity = window.productData.selectedCapacity;
    let selectedColor = window.productData.selectedColor;

    // CAPACITY BUTTON

    document.querySelectorAll(".capacity-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            selectedCapacity = button.dataset.capacity;

            // Update available colors first
            updateAvailableColors();

            // Update capacity/color button styles
            updateSelectedButtons();

            // Find the current capacity + color combination
            const variant = findVariant();

            if (variant) {
                updateProduct(variant);
            } else {
                // Current color is not available for this capacity
                updateCartButton(null);
            }
        });
    });

    // COLOR BUTTON

    document.querySelectorAll(".color-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            const color = button.dataset.color;

            // Check whether this capacity + color combination exists
            const variant = variants.find(function (variant) {
                return (
                    String(variant.capacity).trim() ===
                        String(selectedCapacity).trim() &&
                    String(variant.color).trim() === String(color).trim()
                );
            });

            // Always update selected color
            selectedColor = color;

            // Update button styles
            updateSelectedButtons();

            // Update product/cart
            if (variant) {
                updateProduct(variant);
            } else {
                updateCartButton(null);
            }
        });
    });

    // FIND VARIANT

    function findVariant() {
        return variants.find(function (variant) {
            return (
                String(variant.capacity).trim() ===
                    String(selectedCapacity).trim() &&
                String(variant.color).trim() === String(selectedColor).trim()
            );
        });
    }

    // UPDATE PRODUCT

    function updateProduct(variant) {
        if (!variant) {
            updateCartButton(null);
            return;
        }

        const price = document.getElementById("productPrice");

        if (price) {
            price.textContent = Number(variant.price).toLocaleString() + " MMK";
        }

        const capacity = document.getElementById("selectedCapacity");

        if (capacity) {
            capacity.textContent = selectedCapacity;
        }

        const color = document.getElementById("selectedColor");

        if (color) {
            color.textContent = selectedColor;
        }

        // UPDATE VARIANT IMAGES
        if (variant.images && variant.images.length > 0) {
            detailThumbnailContainer.innerHTML = "";

            variant.images.forEach(function (imageUrl) {
                const button = document.createElement("button");

                button.type = "button";

                button.className =
                    "flex-shrink-0 w-20 h-20 overflow-hidden rounded-md detail-thumb-btn";

                button.dataset.src = imageUrl;

                button.innerHTML = `
            <img
                src="${imageUrl}"
                class="object-cover w-full h-full"
                alt=""
            />
        `;

                detailThumbnailContainer.appendChild(button);
            });

            // Refresh thumbnails
            detailThumbnails = document.querySelectorAll(".detail-thumb-btn");

            // Add click events
            addThumbnailEvents();

            // Show first image of selected variant
            currentIndex = 0;

            showDetailImage(0);
        }

        // Update Add to Cart
        updateCartButton(variant);
    }

    // UPDATE SELECTED BUTTON STYLE

    function updateSelectedButtons() {
        // Capacity
        document.querySelectorAll(".capacity-btn").forEach(function (button) {
            const selected = button.dataset.capacity === selectedCapacity;

            button.classList.toggle("bg-lime-400", selected);
            button.classList.toggle("text-black", selected);
            button.classList.toggle("text-black/60", !selected);
        });

        // Color
        document.querySelectorAll(".color-btn").forEach(function (button) {
            const selected = button.dataset.color === selectedColor;

            button.classList.toggle("bg-lime-400", selected);
            button.classList.toggle("text-black", selected);
            button.classList.toggle("text-black/60", !selected);
        });
    }

    // DISABLE INVALID COLORS

    function updateAvailableColors() {
        document.querySelectorAll(".color-btn").forEach(function (button) {
            const color = button.dataset.color;

            const exists = variants.some(function (variant) {
                return (
                    String(variant.capacity).trim() ===
                        String(selectedCapacity).trim() &&
                    String(variant.color).trim() === String(color).trim()
                );
            });

            if (exists) {
                button.disabled = false;
                button.classList.remove("line-through");
            } else {
                button.disabled = false; // keep clickable so we can show Out of Stock
                button.classList.add("line-through");
            }
        });
    }

    function updateCartButton(variant) {
        const cartButton = document.getElementById("addToCartBtn");

        if (!cartButton) {
            return;
        }

        if (!variant) {
            cartButton.textContent = "Out of Stock";
            cartButton.disabled = true;

            cartButton.classList.add("bg-gray-100", "cursor-not-allowed");

            cartButton.classList.remove("bg-lime-400", "hover:bg-lime-500");
        } else {
            cartButton.textContent = "Add To Cart";
            cartButton.disabled = false;

            cartButton.classList.remove("cursor-not-allowed");

            cartButton.classList.add("bg-lime-400", "hover:bg-lime-500");
        }
    }

    // INITIALIZE

    updateAvailableColors();
    updateSelectedButtons();

    const initialVariant = findVariant();

    if (initialVariant) {
        updateProduct(initialVariant);
    }
}

// INITIALIZE IMAGE

addThumbnailEvents();

showDetailImage(0);
