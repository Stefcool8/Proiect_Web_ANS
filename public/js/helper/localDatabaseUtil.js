const columns = [
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIA_COMUNITARA",
    "MARCA",
    "DESCRIERE_COMERCIALA",
    "TOTAL"
];

function chartCodeToName(chartCode) {
    switch (chartCode) {
        case 0:
            return "Bar Chart";
        case 1:
            return "Line Chart";
        case 2:
            return "Pie Chart";
        case 3:
            return "Map Chart";
    }
    return "Unknown";
}

function columnCodeToName(columnCode,columnsSource) {
    return columnsSource[columnCode];
}

function addLabelAndTextInput(parent, htmlFor, labelText, inputValue, readOnly) {
    const label = document.createElement('label');
    label.htmlFor = htmlFor;
    label.textContent = labelText;
    const input = document.createElement('input');
    input.classList.add(htmlFor);
    input.id = htmlFor;
    input.name = htmlFor;
    input.type = "text";
    input.value = inputValue;
    input.readOnly = readOnly;

    parent.appendChild(label);
    parent.appendChild(input);
}

function loadJS(FILE_URL, async = true) {
    let scriptEle = document.createElement("script");

    scriptEle.setAttribute("src", FILE_URL);
    scriptEle.setAttribute("type", "text/javascript");
    scriptEle.setAttribute("async", async.toString());

    document.body.appendChild(scriptEle);

    // success event
    scriptEle.addEventListener("load", () => {
        console.log("File loaded")
    });
    // error event
    scriptEle.addEventListener("error", (ev) => {
        console.log("Error on loading file", ev);
    });
}
