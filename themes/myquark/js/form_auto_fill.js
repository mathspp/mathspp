document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);

    for (const [key, value] of urlParams.entries()) {
        const selector = `form [name="data[${key}]"]`;
        const field = document.querySelector(selector);
        if (field) {
            field.value = value;
        }
    }
});
