document.addEventListener("DOMContentLoaded", () => {
    console.log("Hey");
    let slides = document.querySelectorAll(".slide-image");
    let currentSlide = 0;

    // Hide all the slides initially except the first one
    for (let i = 1; i < slides.length; i++) {
        slides[i].style.opacity = 0;
    }
    slides[currentSlide].style.opacity = 1; // Show the first slide

    function nextSlide() {
        console.log("Next slide");
        slides[currentSlide].style.opacity = 0;
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].style.opacity = 1;
    }

    setInterval(nextSlide, 6000); // Change the slideshow image every 3 seconds
});
