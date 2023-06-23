const projectInitializationForm = document.getElementById("project-initialization-form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const chartTypeSelect = document.getElementById("chart-type");
const types = ['bars', 'slices', 'lines'];
let yearCheckboxContainer = null;
let seriesCheckboxContainer = null;
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
    const yearCheckboxes = document.querySelectorAll('input[name="year"]');
    let isYearSelected = false;
    for (let i = 0; i < yearCheckboxes.length; i++) {
        if (yearCheckboxes[i].checked) {
            isYearSelected = true;
            break;
        }
    }
    return isYearSelected;
}

function seriesAreSelected() {
    let seriesSelected = false;
    const seriesCheckboxes = document.querySelectorAll('input[name="series"]');
    seriesCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            seriesSelected = true;
        }
    });
    return seriesSelected;
}

function getSelectedSeriesCodes() {
    const seriesCodes = [];
    const seriesCheckboxes = document.querySelectorAll('input[name="series"]');
    seriesCheckboxes.forEach(checkbox => {
        if (checkbox.checked) {
            seriesCodes.push(checkbox.value);
        }
    });
    return seriesCodes;
}

function getSelectedSeriesValues() {
    const seriesValues = [];
    const seriesCheckboxes = document.querySelectorAll('input[name="series-input"]');
    seriesCheckboxes.forEach(input => {
        seriesValues.push(input.value.toUpperCase());
    });
    return seriesValues;
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
    const yearCheckboxes = document.querySelectorAll('input[name="year"]');
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

function removeSeriesCheckboxContainer() {
    let seriesLabel = document.querySelector('label[for="series"]');
    if (seriesLabel != null) {
        seriesLabel.remove();
    }
    if (seriesCheckboxContainer != null) {
        seriesCheckboxContainer.remove();
        seriesCheckboxContainer = null;
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

    removeSeriesCheckboxContainer();
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

function createSeriesInput(checkboxId) {
    const seriesItem = document.getElementById(checkboxId).closest('.series-item');

    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'series-input';
    input.required = true;

    seriesItem.appendChild(input);
}

function addSeriesToCheckboxContainer(seriesCheckboxContainer) {
    // generate checkboxes for the series
    for (let i = 0; i < allColumns.length; i++) {
        // if i = 0 and the chart is a map chart, skip the first column
        if (i === 0 && getChartCode() === 3) {
            continue;
        }

        const seriesItem = document.createElement('div');
        seriesItem.classList.add('series-item');

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.id = `series-${i}`;
        checkbox.name = 'series';
        checkbox.value = i.toString();

        checkbox.addEventListener('change', function () {
            if (checkbox.checked) {
                createSeriesInput(checkbox.id);
            } else {
                const seriesItem = document.getElementById(checkbox.id).closest('.series-item');
                const input = seriesItem.querySelector('input[type="text"]');
                seriesItem.removeChild(input);
            }
        });

        const label = document.createElement('label');
        label.htmlFor = `series-${i}`;
        label.textContent = allColumns[i];

        seriesItem.appendChild(checkbox);
        seriesItem.appendChild(label);
        seriesCheckboxContainer.appendChild(seriesItem);
    }
}

function createSeriesCheckboxes() {
    // create the div for the series
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    const label = document.createElement('label');
    label.htmlFor = 'series';
    label.textContent = 'Series:';

    seriesCheckboxContainer = document.createElement('div');
    seriesCheckboxContainer.classList.add('series-checkbox-container');
    seriesCheckboxContainer.id = 'series-checkboxes';
    addSeriesToCheckboxContainer(seriesCheckboxContainer);

    inputGroup.appendChild(label);
    inputGroup.appendChild(seriesCheckboxContainer);

    // add the div to the form before the create button
    projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
}

function createWorkersSelect(type) {
    // create the div for the data column select
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

    // add an event listener to the workers select
    workersSelect.addEventListener('change', () => {
        if (seriesCheckboxContainer == null) {
            // populate the series checkboxes
            createSeriesCheckboxes();
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
                if (getChartCode() === 1 || getChartCode() === 3) {
                    // worker select for line is not required, the chart is build on years
                    // worker select for map is implicitly set to 'workers' so we don't need to create it
                    if (seriesCheckboxContainer == null) {
                        // populate the series checkboxes
                        createSeriesCheckboxes();
                    }
                } else {
                    // for all other chart types, the workers select is required
                    if (workersSelect == null) {
                        // populate the worker select if it doesn't exist
                        createWorkersSelect(chartTypeSelect.value);
                    }
                }
            } else {
                // Checkbox is deselected
                // remove the worker select and its label if all years are deselected
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

        if (getChartCode() === 1 || getChartCode() === 3) {
            // worker select for line is not required, the chart is build on years
            // worker select for map is implicitly set to 'county'
            // so we only need to create the optional series select
            createSeriesCheckboxes();
        } else {
            // create the workers select for the other chart types
            createWorkersSelect(chartTypeSelect.value);
        }
    }
});

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

    // add data column to the data object if the chart type is not a map or a line chart
    if (chartCode !== 1 && chartCode !== 3) {
        // add the data column to the data object
        data.dataColumn = allColumns.indexOf(workersSelect.value);
        console.log(data.dataColumn);
    }

    // add the series
    if (seriesAreSelected()) {
        data.seriesCodes = getSelectedSeriesCodes();
        data.seriesValues = getSelectedSeriesValues();

        console.log(data.seriesCodes);
        console.log(data.seriesValues);
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
            setTimeout(() => {
                window.location.href = "/dashboard";
            }, 3000);
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
