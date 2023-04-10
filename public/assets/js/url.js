urlButton.addEventListener("click", () => {
    pasteButton.classList.remove("special");
    dropButton.classList.remove("special");
    urlButton.classList.add("special");
    tryButton.classList.remove("special");

    pasteForm.style.display = "none";
    dropForm.style.display = "none";
    urlForm.style.display = "grid";
    tryForm.style.display = "none";
});
