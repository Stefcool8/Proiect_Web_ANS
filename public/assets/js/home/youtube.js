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

// Check if YouTube API is ready, if not then retry after a delay
onYouTubePlayerReady();
