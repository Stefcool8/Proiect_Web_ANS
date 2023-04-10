pasteButton.addEventListener("click", () => {
    pasteButton.classList.add("special");
    dropButton.classList.remove("special");
    urlButton.classList.remove("special");
    tryButton.classList.remove("special");

    pasteForm.style.display = "block";
    dropForm.style.display = "none";
    urlForm.style.display = "none";
    tryForm.style.display = "none";
});
