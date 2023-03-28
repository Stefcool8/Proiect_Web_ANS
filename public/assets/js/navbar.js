const toggleButton = document.querySelector(".navbar-toggle");
const navbarMenu = document.querySelector(".navbar-menu");

// Add a click event listener to the toggle button
toggleButton.addEventListener("click", () => {
    // Toggle the 'active' class on the navbar menu
    navbarMenu.classList.toggle("active");
});
