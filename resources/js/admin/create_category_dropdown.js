const categoryButton = document.getElementById("categoryButton");
const categoryMenu = document.getElementById("categoryMenu");
const selectedLabel = document.getElementById("selectedLabel");
const categoryId = document.getElementById("category_id");

// Open/Close dropdown
if (categoryButton && categoryMenu) {
    categoryButton.addEventListener("click", () => {
        categoryMenu.classList.toggle("hidden");
    });
}

// Select category
document.querySelectorAll(".category-item").forEach(item => {
    item.addEventListener("click", function () {

        // Show selected name
        selectedLabel.textContent = this.dataset.name;
        selectedLabel.classList.remove('text-gray-400');

        // Save selected id into hidden input
        categoryId.value = this.dataset.id;

        // Close menu
        categoryMenu.classList.add("hidden");
    });
});

window.addEventListener("DOMContentLoaded", () => {

    if (!categoryId || !selectedLabel) return;

    const oldId = document.getElementById("category_id").value;

    if (oldId) {
        const item = document.querySelector(
            `.category-item[data-id="${oldId}"]`
        );

        if (item) {
            selectedLabel.textContent = item.dataset.name;
            selectedLabel.classList.remove('text-gray-400');
        }
    }
});
