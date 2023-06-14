function toggleReadMore(dotsId, moreTextId, buttonId) {
    var dots = document.getElementById(dotsId);
    var moreText = document.getElementById(moreTextId);
    var button = document.getElementById(buttonId);

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
