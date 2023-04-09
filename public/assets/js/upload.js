const pasteButton = document.getElementById("paste");
const uploadButton = document.getElementById("upload");
const urlButton = document.getElementById("url");
const tryButton = document.getElementById("try");

const pasteForm = document.getElementById("paste-form");
const uploadForm = document.getElementById("upload-form");
const urlForm = document.getElementById("url-form");
const tryForm = document.getElementById("try-form");

pasteButton.addEventListener("click", () => {
    pasteForm.style.display = "block";
    uploadForm.style.display = "none";
    urlForm.style.display = "none";
    tryForm.style.display = "none";
});

uploadButton.addEventListener("click", () => {
    pasteForm.style.display = "none";
    uploadForm.style.display = "block";
    urlForm.style.display = "none";
    tryForm.style.display = "none";
});

urlButton.addEventListener("click", () => {
    pasteForm.style.display = "none";
    uploadForm.style.display = "none";
    urlForm.style.display = "block";
    tryForm.style.display = "none";
});

tryButton.addEventListener("click", () => {
    pasteForm.style.display = "none";
    uploadForm.style.display = "none";
    urlForm.style.display = "none";
    tryForm.style.display = "block";
});
