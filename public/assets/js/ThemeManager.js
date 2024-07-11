/**
 * ThemeManager.js
 * Author: Emanuel Tin Rukavina
 * Contact: emanuel@uncuni.com
 * 
 * This script is designed to manage and apply theme settings.
 * 
 */
$(document).ready(function () {
    function saveSettings(theme) {
        document.cookie = "theme=" + theme + "; max-age=315360000; path=/";
        document.documentElement.setAttribute('data-bs-theme', theme);
    }
    function loadSettings() {
        const cookieValue = document.cookie.replace(/(?:(?:^|.*;\s*)theme\s*=\s*([^;]*).*$)|^.*$/, "$1");

        if (cookieValue) {
            document.documentElement.setAttribute('data-bs-theme', cookieValue);
        }
    }
    loadSettings();
    $(".theme-manager").on("click", function (e) {
        e.preventDefault();
        const selectedTheme = $(this).data("theme");
        saveSettings(selectedTheme);
    });
});