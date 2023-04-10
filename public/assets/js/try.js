tryButton.addEventListener("click", () => {
    pasteButton.classList.remove("special");
    dropButton.classList.remove("special");
    urlButton.classList.remove("special");
    tryButton.classList.add("special");

    pasteForm.style.display = "none";
    dropForm.style.display = "none";
    urlForm.style.display = "none";
    tryForm.style.display = "flex";
});
