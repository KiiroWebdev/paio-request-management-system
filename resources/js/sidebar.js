document.addEventListener("DOMContentLoaded", () => {

    /*
    |--------------------------------------------------------------------------
    | Sidebar Collapse
    |--------------------------------------------------------------------------
    */

    const sidebar = document.getElementById("sidebar");
    const collapseBtn = document.getElementById("sidebarToggle");

    // If this page doesn't have a sidebar, do nothing.
    if (!sidebar || !collapseBtn) {
        return;
    }

    // Restore previous state
    if (localStorage.getItem("sidebarCollapsed") === "true") {
        sidebar.classList.add("collapsed");
    }

    collapseBtn.addEventListener("click", () => {

        sidebar.classList.toggle("collapsed");

        localStorage.setItem(
            "sidebarCollapsed",
            sidebar.classList.contains("collapsed")
        );

    });

    /*
    |--------------------------------------------------------------------------
    | Collapsible Menu Groups
    |--------------------------------------------------------------------------
    */

    const menuGroups = document.querySelectorAll(".menu-group");

    menuGroups.forEach((group, index) => {

        const button = group.querySelector(".menu-toggle");

        if (!button) return;

        const storageKey = "menuGroup" + index;

        // Restore previous state
        if (localStorage.getItem(storageKey) === "open") {
            group.classList.add("open");
        }

        button.addEventListener("click", () => {

            group.classList.toggle("open");

            localStorage.setItem(
                storageKey,
                group.classList.contains("open") ? "open" : "closed"
            );

        });

    });

    /*
    |--------------------------------------------------------------------------
    | Auto Open Current Section
    |--------------------------------------------------------------------------
    */

    const activeLink = document.querySelector(".submenu-group a.active");

    if (activeLink) {

        const parentGroup = activeLink.closest(".menu-group");

        if (parentGroup) {
            parentGroup.classList.add("open");
        }

    }

});