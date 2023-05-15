let player;

function onYouTubePlayerReady() {
    if (!window.YT || !YT.Player) {
        // YouTube API script not ready, retry after a delay
        setTimeout(onYouTubePlayerReady, 100);
        return;
    }

    player = new YT.Player("player", {
        height: "360",
        width: "640",
        videoId: "aChw3aOVpNs",
        events: {
            onReady: onPlayerReady,
            onStateChange: onPlayerStateChange,
        },
    });
}

function onPlayerReady(event) {
    // event.target.playVideo();
}

function onPlayerStateChange(event) {}

function stopVideo() {
    player.stopVideo();
}

function initializeSlider() {
    let slides = document.querySelectorAll(".slide-image");
    let currentSlide = 0;

    // Hide all the slides initially except the first one
    for (let i = 1; i < slides.length; i++) {
        slides[i].style.opacity = 0;
    }
    slides[currentSlide].style.opacity = 1; // Show the first slide

    function nextSlide() {
        slides[currentSlide].style.opacity = 0;
        currentSlide = (currentSlide + 1) % slides.length;
        slides[currentSlide].style.opacity = 1;
    }

        setInterval(nextSlide, 6000); // Change the slideshow image every 6 seconds
}

document.addEventListener("DOMContentLoaded", function() {
    //initializeSlider();

    // check if YouTube API is ready, if not then retry after a delay
    onYouTubePlayerReady();

    
});
