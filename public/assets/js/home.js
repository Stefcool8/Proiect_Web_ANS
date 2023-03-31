document.addEventListener("DOMContentLoaded", () => {
    let slides = document.querySelectorAll(".slide");
    let currentSlide = 0;

    function nextSlide() {
        slides[currentSlide].style.opacity = 0;
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].style.opacity = 1;
    }

    setInterval(nextSlide, 3000); // Change the slideshow image every 3 seconds
});
