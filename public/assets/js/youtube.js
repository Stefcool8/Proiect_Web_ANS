var player;
function onYouTubeIframeAPIReady() {
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
    // Uncomment the following line to automatically start playing the video
    // event.target.playVideo();
}

function onPlayerStateChange(event) {
    // Add custom code here for handling state changes
}

function stopVideo() {
    player.stopVideo();
}
