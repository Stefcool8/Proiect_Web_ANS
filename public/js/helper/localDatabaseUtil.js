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