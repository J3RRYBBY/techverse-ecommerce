function openModal(id) {
    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function openEditModal(name, url, image) {
    const modal = document.getElementById('editCategoryModal');

    if (!modal) return;

    // Open modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Set category name
    document.getElementById('editCategoryName').value = name;

    // Set form action
    document.getElementById('editForm').action = url;

    // Get image elements inside edit modal
    const container = modal.querySelector('.category-image-container');
    const preview = modal.querySelector('.category-image-preview');
    const placeholder = modal.querySelector('.category-image-placeholder');
    const input = modal.querySelector('.category-image-input');

    // Reset file input
    input.value = '';

    if (image) {
        // Show existing Cloudinary image
        preview.src = image;

        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');

        // Image exists → auto height
        container.classList.remove('h-40');
        container.classList.add('h-auto');
    } else {
        // No image
        preview.src = '';

        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');

        // No image → fixed height
        container.classList.remove('h-auto');
        container.classList.add('h-40');
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);

    if (!modal) return;

    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

document.addEventListener('click', function (e) {
    const modal = e.target.closest('.modal-overlay');

    if (modal && e.target === modal) {
        closeModal(modal.id);
    }
});

// Image preview for BOTH Create and Edit
document.querySelectorAll('.category-image-input').forEach((input) => {
    input.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) return;

        const container = this.closest('.category-image-container');

        const preview = container.querySelector('.category-image-preview');

        const placeholder = container.querySelector('.category-image-placeholder');

        const reader = new FileReader();

        reader.onload = function (e) {
            preview.src = e.target.result;

            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');

            // Image exists → auto height
            container.classList.remove('h-40');
            container.classList.add('h-auto');
        };

        reader.readAsDataURL(file);
    });
});

// Expose globally
window.openModal = openModal;
window.closeModal = closeModal;
window.openEditModal = openEditModal;
