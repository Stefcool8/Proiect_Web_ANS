dropButton.addEventListener("click", () => {
    pasteForm.style.display = "none";
    dropForm.style.display = "flex";
    urlForm.style.display = "none";
    tryForm.style.display = "none";
});

var dropzone = document.getElementById('drag-drop-form');

dropzone.addEventListener('dragenter', handleDragEnter, false);
dropzone.addEventListener('dragover', handleDragOver, false);
dropzone.addEventListener('dragleave', handleDragLeave, false);
dropzone.addEventListener('drop', handleDrop, false);

function handleDragEnter(e) {
    this.classList.add('over');
}

function handleDragOver(e) {
    e.stopPropagation();
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
}

function handleDragLeave(e) {
    this.classList.remove('over');
}

function handleDrop(e) {
    e.stopPropagation();
    e.preventDefault();

    var files = e.dataTransfer.files;
    var count = files.length;

    // Only call the handler if 1 or more files was dropped.
    if (count > 0)
        handleFiles(files);
}

function handleFiles(files) {
    dropzone.textContent = '';
    
    for (var i = 0; i < files.length; i++) {
        var fileName = files[i].name;
        var fileElement = document.createElement('div');
        fileElement.textContent = fileName;
        dropzone.appendChild(fileElement);
    }
}
