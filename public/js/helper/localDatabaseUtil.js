const columns = [
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIA_COMUNITARA",
    "MARCA",
    "DESCRIERE_COMERCIALA",
    "TOTAL"
];

const columnsPieChart =[
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIE_COMUNITARA"
];


function chartCodeToName(chartCode) {
    switch (chartCode) {
        case 0:
            return "Bar Chart";
        case 1:
            return "Line Chart";
        case 2:
            return "Pie Chart";
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