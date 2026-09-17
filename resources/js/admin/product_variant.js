document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("variants-container");
    const addButton = document.getElementById("addVariant");

    if (!container) {
        return;
    }

    // Create Variant
    function createVariant(index) {
        return `
            <div class="p-4 bg-white border border-gray-200 rounded-xl variant-row">

                <!-- Header -->
                <div class="flex items-center justify-between">

                    <div class="flex items-center gap-3">

                        <div
                            class="flex items-center justify-center w-8 h-8 text-sm font-semibold text-gray-800 rounded-full bg-lime-400 variant-number"
                        >
                            ${index + 1}
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-800 variant-title">
                                Variant ${index + 1}
                            </p>

                            <p class="text-xs text-gray-500">
                                Configure this product variation
                            </p>
                        </div>

                    </div>

                    <!-- Remove -->
                    <button
                        type="button"
                        class="flex items-center justify-center w-8 h-8 text-red-500 rounded-full remove-variant bg-red-50"
                    >
                        <i class="text-sm fa-solid fa-trash"></i>
                    </button>

                </div>


                <!-- Fields -->
                <div class="grid grid-cols-1 gap-5 mt-5 md:grid-cols-2">

                    <!-- Capacity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Capacity
                        </label>

                        <input
                            type="text"
                            name="variants[${index}][capacity]"
                            placeholder="Enter capacity (eg. 128GB)"
                            class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                        >
                    </div>


                    <!-- Color -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Color
                        </label>

                        <input
                            type="text"
                            name="variants[${index}][color]"
                            placeholder="Enter color (eg. Black)"
                            class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                        >
                    </div>


                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Price
                        </label>

                        <input
                            type="text"
                            name="variants[${index}][price]"
                            placeholder="Enter product price"
                            class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                        >
                    </div>


                    <!-- Stock -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Stock
                        </label>

                        <input
                            type="text"
                            name="variants[${index}][stock]"
                            placeholder="Enter stock quantity"
                            class="w-full px-4 py-2.5 mt-2 text-sm border rounded-lg shadow-sm focus:outline-none focus:border-lime-400 focus:ring-1 focus:ring-lime-400"
                        >
                    </div>

                </div>


                <!-- Variant Images -->
                <div class="mt-5">

                    <p class="text-sm font-medium text-gray-700">
                        Upload Img
                    </p>

                    <div
                        class="flex items-center h-24 gap-3 mt-2 overflow-x-auto thumbnail-container"
                    >

                        <!-- Upload Button -->
                        <label
                            class="flex items-center justify-center flex-shrink-0 w-20 h-20 border-2 border-dashed rounded-lg cursor-pointer upload-box"
                        >
                            <i class="text-xl text-black/70 fa-solid fa-plus"></i>

                            <input
                                type="file"
                                name="variants[${index}][images][]"
                                multiple
                                accept="image/*"
                                class="hidden image-input"
                            />
                        </label>

                    </div>


                    <!-- Scroll Buttons -->
                    <div class="flex items-center gap-2 mt-2">

                        <button
                            type="button"
                            class="items-center justify-center hidden w-10 h-10 bg-white border rounded-full shadow prev-thumb"
                        >
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>

                        <button
                            type="button"
                            class="items-center justify-center hidden w-10 h-10 bg-white border rounded-full shadow next-thumb"
                        >
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>

                    </div>

                </div>

            </div>
        `;
    }

    // Add Variant
    if (addButton) {
        addButton.addEventListener("click", () => {
            const index = container.querySelectorAll(".variant-row").length;

            container.insertAdjacentHTML("beforeend", createVariant(index));

            updateVariantNumbers();
        });
    }

    // Remove Variant
    container.addEventListener("click", (event) => {
        const removeButton = event.target.closest(".remove-variant");

        if (!removeButton) {
            return;
        }

        const rows = container.querySelectorAll(".variant-row");

        // Don't allow zero variants
        if (rows.length === 1) {
            return;
        }

        const row = removeButton.closest(".variant-row");

        if (row) {
            row.remove();
        }

        updateVariantNumbers();
    });

    // Update Variant Numbers + Input Names
    function updateVariantNumbers() {
        const rows = container.querySelectorAll(".variant-row");

        rows.forEach((row, index) => {
            // Update number
            const number = row.querySelector(".variant-number");

            if (number) {
                number.textContent = index + 1;
            }

            // Update title
            const title = row.querySelector(".variant-title");

            if (title) {
                title.textContent = `Variant ${index + 1}`;
            }

            // Update capacity name
            const capacity = row.querySelector('input[name*="[capacity]"]');

            if (capacity) {
                capacity.name = `variants[${index}][capacity]`;
            }

            // Update color name
            const color = row.querySelector('input[name*="[color]"]');

            if (color) {
                color.name = `variants[${index}][color]`;
            }

            // Update price name
            const price = row.querySelector('input[name*="[price]"]');

            if (price) {
                price.name = `variants[${index}][price]`;
            }

            // Update stock name
            const stock = row.querySelector('input[name*="[stock]"]');

            if (stock) {
                stock.name = `variants[${index}][stock]`;
            }

            // Update image input name
            const imageInput = row.querySelector("input.image-input");

            if (imageInput) {
                imageInput.name = `variants[${index}][images][]`;
            }
        });
    }
});
