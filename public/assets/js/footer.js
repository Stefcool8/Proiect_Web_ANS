document.querySelectorAll(".footer-item").forEach((item, index) => {
    item.style.animationDelay = `${index * 200}ms`;
});
