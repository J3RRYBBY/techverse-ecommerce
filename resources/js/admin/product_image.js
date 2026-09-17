document.addEventListener("change", function (e) {
    if (!e.target.classList.contains("image-input")) return;

    const input = e.target;

    const variantRow = input.closest(".variant-row");

    const files = Array.from(input.files);

    files.forEach((file) => {
        const existingImages = variantRow.querySelectorAll(
            ".thumbnail-container img",
        );

        const duplicate = Array.from(existingImages).some(
            (img) =>
                img.dataset.name === file.name && img.dataset.size == file.size,
        );

        if (duplicate) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function (event) {
            createThumbnail(event.target.result, file, variantRow);
        };

        reader.readAsDataURL(file);
    });
});

document.addEventListener("click", function (e) {
    const button = e.target.closest(".remove-existing-image");

    if (!button) return;

    const imageId = button.dataset.imageId;

    const variantRow = button.closest(".variant-row");

    const deletedImagesContainer = variantRow.querySelector(".deleted-images");

    // Create hidden input for Laravel
    const input = document.createElement("input");

    input.type = "hidden";
    input.name = `variants[${variantRow.dataset.index}][deleteImages][]`;
    input.value = imageId;

    deletedImagesContainer.appendChild(input);

    // Remove image from the UI
    button.closest(".existing-image").remove();
});

function createThumbnail(imageUrl, source, variantRow) {
    const thumbnailContainer = variantRow.querySelector(".thumbnail-container");

    const wrapper = document.createElement("div");

    wrapper.className = "relative flex-shrink-0 w-20 h-20";

    wrapper.innerHTML = `

        <img
            data-name="${source.name}"
            data-size="${source.size}"
            src="${imageUrl}"
            class="object-cover w-20 h-20 border rounded-lg"
        />

        <button
            type="button"
            class="absolute flex items-center justify-center w-5 h-5 bg-lime-400 rounded-full -top-2 -right-2 delete-image">

            <i class="text-[8px] fa-solid fa-x"></i>

        </button>

    `;

    const deleteBtn = wrapper.querySelector(".delete-image");

    deleteBtn.addEventListener("click", () => {
        const input = variantRow.querySelector(".image-input");

        const dt = new DataTransfer();

        Array.from(input.files).forEach((file) => {
            if (file.name !== source.name || file.size !== source.size) {
                dt.items.add(file);
            }
        });

        input.files = dt.files;

        wrapper.remove();
    });

    const uploadBox = thumbnailContainer.querySelector(".upload-box");

    thumbnailContainer.insertBefore(wrapper, uploadBox);
}
