document.addEventListener("DOMContentLoaded", () => {
    const themeToggleBtn = document.getElementById("theme-toggle");
    const mobileThemeToggleBtn = document.getElementById("mobile-theme-toggle");
    const themeIcon = document.getElementById("theme-icon");
    const mobileThemeIcon = document.getElementById("mobile-theme-icon");
    const currentTheme = localStorage.getItem("theme") || "dark";

    // Apply the saved theme
    document.documentElement.setAttribute("data-theme", currentTheme);
    updateIcon(currentTheme);

    const toggleTheme = () => {
        let newTheme = document.documentElement.getAttribute("data-theme") === "light" ? "dark" : "light";

        document.documentElement.setAttribute("data-theme", newTheme);
        localStorage.setItem("theme", newTheme);

        updateIcon(newTheme);
    }

    themeToggleBtn.addEventListener("click", toggleTheme);
    if (mobileThemeToggleBtn) {
        mobileThemeToggleBtn.addEventListener("click", toggleTheme);
        mobileThemeToggleBtn.addEventListener("touchend", toggleTheme);
    }

    function updateIcon(theme) {
        if (theme === "dark") {
            themeIcon.classList.replace("fa-moon", "fa-sun");
            if (mobileThemeToggleBtn) mobileThemeIcon.classList.replace("fa-moon", "fa-sun");
        } else {
            themeIcon.classList.replace("fa-sun", "fa-moon");
            if (mobileThemeToggleBtn) mobileThemeIcon.classList.replace("fa-sun", "fa-moon");
        }
    }
});
