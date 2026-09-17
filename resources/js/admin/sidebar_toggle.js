const toggleBtn = document.getElementById("toggle-btn");
const toggleCloseIcon = document.getElementById("toggle-close-icon");
const toggleOpenIcon = document.getElementById("toggle-open-icon");
const sidebar = document.getElementById("sidebar");
const sidebarTexts = document.querySelectorAll(".sidebar-text");

if (toggleBtn) {
    toggleBtn.addEventListener("click", () => {
        if (sidebar.classList.contains("w-60")) {
            // Collapse sidebar
            sidebar.classList.replace("w-60", "w-24");
            sidebarTexts.forEach((text) => text.classList.add("hidden"));

            // Change icon
            toggleCloseIcon.classList.add("hidden");
            toggleOpenIcon.classList.remove("hidden");
        } else {
            // Expand sidebar
            sidebar.classList.replace("w-24", "w-60");

            setTimeout(() => {
                sidebarTexts.forEach((text) => text.classList.remove("hidden"));
            }, 150);

            // Change icon
            toggleCloseIcon.classList.remove("hidden");
            toggleOpenIcon.classList.add("hidden");
        }
    });
}
