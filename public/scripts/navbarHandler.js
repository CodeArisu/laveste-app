const currentUrl = window.location.pathname;
const links = document.querySelectorAll(".nav-links li a");

links.forEach((link) => link.classList.remove("active"));

if (currentUrl.includes("home")) {
    document.getElementById("home-link").classList.add("active");
} else if (currentUrl.includes("products")) {
    document.getElementById("products-link").classList.add("active");
} else if (currentUrl.includes("transactions")) {
    document.getElementById("transactions-link").classList.add("active");
}

links.forEach((link) => {
    link.addEventListener("click", function () {
        links.forEach((link) => link.classList.remove("active"));
        this.classList.add("active");
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const image = document.getElementById("cashierImage");
    const menu = document.getElementById("logoutMenu");

    // Make sure it's hidden when page loads
    menu.style.display = "none";

    image.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent event bubbling
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    });

    // Hide when clicking outside
    document.addEventListener("click", function (event) {
        if (!menu.contains(event.target) && !image.contains(event.target)) {
            menu.style.display = "none";
            P;
        }
    });
});
