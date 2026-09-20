const toggleBtn = document.getElementById('toggle-btn');
const toggleCloseIcon = document.getElementById('toggle-close-icon');
const toggleOpenIcon = document.getElementById('toggle-open-icon');
const sidebar = document.getElementById('sidebar');
const mainContent = document.getElementById('main-content');
const sidebarTexts = document.querySelectorAll('.sidebar-text');

// Apply sidebar state
function applySidebarState(collapsed) {
    if (collapsed) {
        // Collapse sidebar
        sidebar.classList.remove('w-60');
        sidebar.classList.add('w-24');

        mainContent.classList.remove('ml-60');
        mainContent.classList.add('ml-24');

        sidebarTexts.forEach((text) => {
            text.classList.add('hidden');
        });

        toggleCloseIcon.classList.add('hidden');
        toggleOpenIcon.classList.remove('hidden');
    } else {
        // Expand sidebar
        sidebar.classList.remove('w-24');
        sidebar.classList.add('w-60');

        mainContent.classList.remove('ml-24');
        mainContent.classList.add('ml-60');

        sidebarTexts.forEach((text) => {
            text.classList.remove('hidden');
        });

        toggleCloseIcon.classList.remove('hidden');
        toggleOpenIcon.classList.add('hidden');
    }
}

// Load saved state when page loads
const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

applySidebarState(sidebarCollapsed);

// Toggle sidebar
if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
        const isCollapsed = sidebar.classList.contains('w-24');

        const newState = !isCollapsed;

        // Save state
        localStorage.setItem('sidebarCollapsed', newState);

        // Apply state
        applySidebarState(newState);
    });
}
