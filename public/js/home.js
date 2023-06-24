const HomePage = (() => {
    let player;

    function onYouTubePlayerReady() {
        if (!window.YT || !YT.Player) {
            setTimeout(onYouTubePlayerReady, 100);
            return;
        }

        player = new YT.Player("video-player", {
            height: "360",
            width: "640",
            videoId: "zwxHwc_BP1w",
            events: {
                onReady: onPlayerReady,
                onStateChange: onPlayerStateChange,
                onError: onPlayerError
            }
        });
    }

    function onPlayerReady(event) {
    }

    function onPlayerStateChange(event) {
    }

    function onPlayerError(event) {
        console.error("Error occurred: " + event.data);
    }

    function stopVideo() {
        if (player) {
            player.stopVideo();
        }
    }

    return {
        onYouTubePlayerReady: onYouTubePlayerReady,
        stopVideo: stopVideo
    }
})();

document.addEventListener("DOMContentLoaded", async () => {
    HomePage.onYouTubePlayerReady();

    const response = await fetch('/api/home', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    });

    const result = await response.json();

    if (response.ok) {
        const homeData = document.querySelector(".intro-heading");
        homeData.textContent = result.data.title;
    } else {
        showError(result.data.error);
        throw new Error(result.data.error);
    }
});
