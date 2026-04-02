document.addEventListener("DOMContentLoaded", function () {
    const burger = document.getElementById("burger");
    const menu = document.getElementById("navMenu");

    burger.addEventListener("click", () => {
        menu.classList.toggle("active");
    });
});