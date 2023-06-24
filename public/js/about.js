function toggleReadMore(dotsId, moreTextId, buttonId) {
    const dots = document.getElementById(dotsId);
    const moreText = document.getElementById(moreTextId);
    const button = document.getElementById(buttonId);

    if (dots.style.display === "none") {
        dots.style.display = "inline";
        button.innerHTML = "Read more";
        moreText.style.display = "none";
    } else {
        dots.style.display = "none";
        button.innerHTML = "Read less";
        moreText.style.display = "inline";
    }
}

document.getElementById("myBtn").onclick = function () {
    toggleReadMore("dots", "more", "myBtn");
};

document.getElementById("myBtn1").onclick = function () {
    toggleReadMore("dots1", "more1", "myBtn1");
};

document.getElementById("myBtn2").onclick = function () {
    toggleReadMore("dots2", "more2", "myBtn2");
};

document.getElementById("myBtn3").onclick = function () {
    toggleReadMore("dots3", "more3", "myBtn3");
};

document.addEventListener("DOMContentLoaded", async () => {

    const response = await fetch('/api/about', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        },
    });

    const result = await response.json();

    if (response.ok) {
        const aboutData = document.querySelector(".my-element p");
        aboutData.textContent = result.data.title;
    } else {
        showError(result.data.error);
        throw new Error(result.data.error);
    }
});

