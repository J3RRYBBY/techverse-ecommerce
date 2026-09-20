document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('toggle-btn');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const closeIcon = document.getElementById('toggle-close-icon');
    const openIcon = document.getElementById('toggle-open-icon');

    if (!toggleBtn || !sidebar || !mainContent) {
        return;
    }

    function updateSidebar() {
        const collapsed = document.documentElement.classList.contains('sidebar-collapsed');

        if (collapsed) {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');

            mainContent.classList.remove('ml-64');
            mainContent.classList.add('ml-20');

            closeIcon?.classList.add('hidden');
            openIcon?.classList.remove('hidden');
        } else {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');

            mainContent.classList.remove('ml-20');
            mainContent.classList.add('ml-64');

            closeIcon?.classList.remove('hidden');
            openIcon?.classList.add('hidden');
        }
    }

    // Apply saved state immediately
    updateSidebar();

    toggleBtn.addEventListener('click', () => {
        document.documentElement.classList.toggle('sidebar-collapsed');

        const collapsed = document.documentElement.classList.contains('sidebar-collapsed');

        localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');

        updateSidebar();
    });
});
