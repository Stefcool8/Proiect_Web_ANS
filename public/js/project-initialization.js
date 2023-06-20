const projectInitializationForm = document.getElementById("project-initialization-form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const chartTypeSelect = document.getElementById("chart-type");
const types = ['bars', 'slices', 'lines'];
let yearCheckboxContainer = null;
let seriesSelect = null;
let seriesInput = null;
let workersSelect = null;
const allColumns = [
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIA_COMUNITARA",
    "MARCA",
    "DESCRIERE_COMERCIALA",
    "VALUE_NAME"
];

const workerPieColumns = [
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIE_COMUNITARA",
    "VALUE_NAME"
];

const workerMapColumns = [
    "CATEGORIE_NATIONALA",
    "CATEGORIE_COMUNITARA",
    "MARCA",
    "DESCRIERE_COMERCIALA",
    "VALUE_NAME"
];

function showMessage(element, message) {
    element.textContent = message;
    element.classList.add("visible");

    setTimeout(() => {
        hideMessage(element);
    }, 3000);
}

function hideMessage(element) {
    element.classList.remove("visible");
}

function yearsAreSelected() {
    // Check if at least one year is selected
    const yearCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    let isYearSelected = false;
    for (let i = 0; i < yearCheckboxes.length; i++) {
        if (yearCheckboxes[i].checked) {
            isYearSelected = true;
            break;
        }
    }
    return isYearSelected;
}

function getRightWorkersColumns(type) {
    if (type === 'barChart')
        return allColumns;
    else if (type === 'pieChart')
        return workerPieColumns;
    else if (type === 'lineChart')
        return allColumns;
    return null;
}

function getRightSeriesColumns(type) {
    if (type === 'barChart')
        return allColumns;
    else if (type === 'pieChart')
        return allColumns;
    else if (type === 'lineChart')
        return allColumns;
    else if (type === 'mapChart')
        return workerMapColumns;
    return null;
}

function getSelectedYears() {
    // Get the selected years
    const selectedYears = [];
    const yearCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    for (let i = 0; i < yearCheckboxes.length; i++) {
        if (yearCheckboxes[i].checked) {
            selectedYears.push(yearCheckboxes[i].value);
        }
    }
    return selectedYears;
}

function getChartCode() {
    switch (chartTypeSelect.value) {
        case 'barChart':
            return 0;
        case 'lineChart':
            return 1;
        case 'pieChart':
            return 2;
        case 'mapChart':
            return 3;
        default:
            return -1;
    }
}

function getRightTextContentForSelect(type) {
    if (type === 'barChart')
        return 'Select a bar';
    else if (type === 'pieChart')
        return 'Select a slice';
    else if (type === 'lineChart')
        return 'Select a line';
    else if (type === 'mapChart')
        return 'Select county-level data'
    return null;
}

function getRightHtmlFor(type) {
    if (type === 'barChart')
        return "bars";
    else if (type === 'pieChart')
        return "slices";
    else if (type === 'lineChart')
        return "lines";
    return 'unKnown';
}

function getRightTextContent(type) {
    if (type === 'barChart')
        return 'Bars:';
    else if (type === 'pieChart')
        return 'Slices:';
    else if (type === 'lineChart')
        return 'Lines:';
    return 'unKnown';

}

function getRightTagName(type) {
    if (type === 'barChart')
        return 'bars-select';
    else if (type === 'pieChart')
        return 'slices-select';
    else if (type === 'lineChart')
        return 'lines-select';
    return 'unKnown';
}

function removeSeriesInput() {
    if (seriesInput != null) {
        seriesInput.remove();
        seriesInput = null;
        document.querySelector('label[for="series-input"]').remove();
    }
}

function removeAllWorkers() {
    types.forEach(type => {
        let labelType = document.querySelector(`label[for="${type}"]`);
        if (labelType) {
            labelType.remove();
        }
    });
    if (workersSelect != null) {
        workersSelect.remove();
        workersSelect = null;
    }

    if (seriesSelect != null) {
        seriesSelect.remove();
        seriesSelect = null;
        document.querySelector('label[for="series"]').remove();
    }
    removeSeriesInput();
}

function addYearsToCheckboxContainer() {
    // Generate checkboxes for years 2012 to 2021
    for (let year = 2012; year <= 2021; year++) {
        const yearItem = document.createElement('div');
        yearItem.classList.add('year-item');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `year-${year}`;
        checkbox.name = 'year';
        checkbox.value = year.toString();

        const label = document.createElement('label');
        label.htmlFor = `year-${year}`;
        label.textContent = year.toString();

        yearItem.appendChild(checkbox);
        yearItem.appendChild(label);
        yearCheckboxContainer.appendChild(yearItem);
    }
}

function addOptionsToSelectMenu(parentSelect, rightColumns, emptyOptionText, emptyOptionDisabled) {
    // add an empty option to the select menu
    const emptyOption = document.createElement('option');
    emptyOption.value = '';
    emptyOption.textContent = emptyOptionText;
    emptyOption.disabled = emptyOptionDisabled;
    emptyOption.selected = true;
    parentSelect.appendChild(emptyOption);

    // Iterate over the series array and create an option for each series
    for (let i = 0; i < rightColumns.length; i++) {
        const selectOption = document.createElement('option');
        selectOption.value = rightColumns[i];
        selectOption.textContent = rightColumns[i];
        parentSelect.appendChild(selectOption);
    }
}

function createSeriesInput() {
    // create the div for the series
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    const seriesLabel = document.createElement('label');
    seriesLabel.htmlFor = 'series-input';
    seriesLabel.textContent = 'Value:';

    seriesInput = document.createElement('input');
    seriesInput.classList.add('series-input');
    seriesInput.id = 'series-input';
    seriesInput.name = 'series-input';
    seriesInput.type = 'text';
    seriesInput.placeholder = 'Enter a series';
    seriesInput.required = true;

    inputGroup.appendChild(seriesLabel);
    inputGroup.appendChild(seriesInput);

    // add the div to the form before the create button
    projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
}

function createSeriesSelect(type) {
    // create the div for the series
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    const seriesLabel = document.createElement('label');
    seriesLabel.htmlFor = 'series';
    seriesLabel.textContent = 'Series:';

    seriesSelect = document.createElement('select');
    seriesSelect.classList.add('series-select');
    seriesSelect.id = 'series-select';
    seriesSelect.name = 'series';
    addOptionsToSelectMenu(seriesSelect, getRightSeriesColumns(type), 'No series', false);

    // add an event listener to the series select
    seriesSelect.addEventListener('change', () => {
        if (seriesSelect.value === '') {
            // remove the series input
            removeSeriesInput();
        } else if (seriesInput == null) {
            // create the input for the series
            createSeriesInput();
        }
    });

    inputGroup.appendChild(seriesLabel);
    inputGroup.appendChild(seriesSelect);

    // add the div to the form before the create button
    projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
}

function createWorkersSelect(type) {
    // create the div for the bars
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    const workersLabel = document.createElement('label');
    workersLabel.htmlFor = getRightHtmlFor(type);
    workersLabel.textContent = getRightTextContent(type);

    workersSelect = document.createElement('select');
    workersSelect.classList.add(getRightTagName(type));
    workersSelect.id = getRightTagName(type);
    workersSelect.name = getRightHtmlFor(type);
    workersSelect.required = true; // Set the required attribute to true
    addOptionsToSelectMenu(workersSelect, getRightWorkersColumns(type), getRightTextContentForSelect(type), true);

    // add an event listener to the bars select
    workersSelect.addEventListener('change', () => {
        if (seriesSelect == null) {
            // populate the series select
            createSeriesSelect(type);
        }
    });

    inputGroup.appendChild(workersLabel);
    inputGroup.appendChild(workersSelect);

    // add the div to the form before the create button
    projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
}

function createYearCheckboxContainer() {
    // create the div for the years
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    const label = document.createElement('label');
    label.htmlFor = 'years';
    label.textContent = 'Years:';

    yearCheckboxContainer = document.createElement('div');
    yearCheckboxContainer.classList.add('year-checkbox-container');
    yearCheckboxContainer.id = 'year-checkboxes';
    addYearsToCheckboxContainer();

    // add an event listener to the year checkbox container
    yearCheckboxContainer.addEventListener('change', (event) => {
        if (event.target.matches('input[type="checkbox"]')) {
            if (event.target.checked) {
                if (getChartCode() === 3) {
                    // worker select for map is implicitly set to 'workers' so we don't need to create it
                    if (seriesSelect == null) {
                        // populate the series select if it doesn't exist
                        createSeriesSelect(chartTypeSelect.value);
                    }
                } else {
                    // for all other chart types, the workers select is required
                    if (workersSelect == null) {
                        // populate the bars select if it doesn't exist
                        createWorkersSelect(chartTypeSelect.value);
                    }
                }
            } else {
                // Checkbox is deselected
                // remove the bars select and its label if all years are deselected
                if (!yearsAreSelected()) {
                    removeAllWorkers();
                }
            }
        }
    });

    inputGroup.appendChild(label);
    inputGroup.appendChild(yearCheckboxContainer);

    // add the div to the form before the create button
    projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
}

chartTypeSelect.addEventListener("change", () => {
    if (yearCheckboxContainer == null) {
        createYearCheckboxContainer();
    }
    // remove all selects and inputs if the chart type is changed
    removeAllWorkers();
    if (yearsAreSelected()) {
        // populate the workers select if at least one year is selected

        if (getChartCode() === 3) {
            // worker select for map is implicitly set to 'county'
            // so we only need to create the optional series select
            createSeriesSelect(chartTypeSelect.value);
        } else {
            // create the workers select for the other chart types
            createWorkersSelect(chartTypeSelect.value);
        }
    }
});

/*TODO: make the series select have an option "No series", that makes the input label disappear*/
projectInitializationForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Check if at least one year is selected
    if (!yearsAreSelected()) {
        showMessage(errorMessage, "Please select at least one year");
        return;
    }

    const projectName = document.getElementById("project-name").value;
    const chartCode = getChartCode();
    const years = getSelectedYears();

    console.log(projectName);
    console.log(chartCode);
    console.log(years);

    const data = {
        name: projectName,
        chart: chartCode,
        years: years
    };

    // add the bars to the data object if the chart type is not a map
    if (chartCode !== 3) {
        // add the bars to the data object
        data.bars = allColumns.indexOf(workersSelect.value);
        console.log(data.bars);
    }

    if (seriesSelect.value !== '') {
        const seriesCode = allColumns.indexOf(seriesSelect.value);
        const seriesValue = seriesInput.value;

        console.log(seriesCode);
        console.log(seriesValue);

        // add the series to the data object
        data.seriesCode = seriesCode;
        data.seriesValue = seriesValue;
    }

    const token = localStorage.getItem('jwt');
    if (!token) {
        window.location.href = "/login";
    }

    try {
        const response = await fetch("/api/project", {
            method: "POST",
            headers: {
                'Authorization': 'Bearer ' + token,
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        let result;
        try {
            result = await response.json();
            console.log(result);
        } catch (error) {
            showMessage(errorMessage, "Failed to parse JSON response");
            return;
        }

        if (response.ok) {
            // The project creation was successful
            showMessage(successMessage, "Project successfully created. Redirecting...");
            //setTimeout(() => {
            // window.location.href = "/home";
            //}, 3000);
        } else {
            // Handle the error
            showMessage(errorMessage, result.data.error);
        }
    } catch (error) {
        // Handle any errors
        showMessage(errorMessage, "An error occurred. Please try again later.");
        console.error(error);
    }
});
