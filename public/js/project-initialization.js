const projectInitializationForm = document.getElementById("project-initialization-form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const chartTypeSelect = document.getElementById("chart-type");
let yearCheckboxContainer = null;
let barsSelect = null;
let seriesSelect = null;
let seriesInput = null;
const columns = [
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIA_COMUNITARA",
    "MARCA",
    "DESCRIERE_COMERCIALA",
    "TOTAL"
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

function removeBarsSelect() {
    if (barsSelect != null) {
        barsSelect.remove();
        barsSelect = null;
        document.querySelector('label[for="bars"]').remove();
    }
}

function removeSeriesSelect() {
    if (seriesSelect != null) {
        seriesSelect.remove();
        seriesSelect = null;
        document.querySelector('label[for="series"]').remove();
    }
}

function removeSeriesInput() {
    if (seriesInput != null) {
        seriesInput.remove();
        seriesInput = null;
        document.querySelector('label[for="series-input"]').remove();
    }
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
    }
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

function addBarsToSelectMenu() {
    // add an empty option to the select menu
    const emptyOption = document.createElement('option');
    emptyOption.value = '';
    emptyOption.textContent = 'Select a bar';
    emptyOption.disabled = true;
    emptyOption.selected = true;
    barsSelect.appendChild(emptyOption);

    // Iterate over the bars array and create an option for each bar
    for (let i = 0; i < columns.length; i++) {
        const barOption = document.createElement('option');
        barOption.value = columns[i];
        barOption.textContent = columns[i];
        barsSelect.appendChild(barOption);
    }
}

function addSeriesToSelectMenu() {
    // Hardcode the series labels into an array
    const series = [columns[0], columns[1]];

    // add an empty option to the select menu
    const emptyOption = document.createElement('option');
    emptyOption.value = '';
    emptyOption.textContent = 'No series';
    emptyOption.selected = true;
    seriesSelect.appendChild(emptyOption);

    // Iterate over the series array and create an option for each series
    for (let i = 0; i < series.length; i++) {
        const seriesOption = document.createElement('option');
        seriesOption.value = series[i];
        seriesOption.textContent = series[i];
        seriesSelect.appendChild(seriesOption);
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

function createSeriesSelect() {
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
    addSeriesToSelectMenu();

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

function createBarsSelect() {
    // create the div for the bars
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    const barsLabel = document.createElement('label');
    barsLabel.htmlFor = 'bars';
    barsLabel.textContent = 'Bars:';

    barsSelect = document.createElement('select');
    barsSelect.classList.add('bars-select');
    barsSelect.id = 'bars-select';
    barsSelect.name = 'bars';
    barsSelect.required = true; // Set the required attribute to true
    addBarsToSelectMenu();

    // add an event listener to the bars select
    barsSelect.addEventListener('change', () => {
        if (seriesSelect == null) {
            // populate the series select
            createSeriesSelect();
        }
    });

    inputGroup.appendChild(barsLabel);
    inputGroup.appendChild(barsSelect);

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
                // Checkbox is selected
                if (barsSelect == null) {
                    // TODO: add a select depending on the selected chart type
                    if (chartTypeSelect.value === 'barChart') {
                        // populate the bars select
                        createBarsSelect();
                    }
                }
            } else {
                // Checkbox is deselected
                // remove the bars select and its label if all years are deselected
                if (!yearsAreSelected()) {
                    // remove the bars select and series select if they exist
                    removeBarsSelect();
                    removeSeriesSelect();
                    removeSeriesInput();
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
    if (chartTypeSelect.value !== 'barChart') {
        // remove the bars select and series select if they exist
        removeBarsSelect();
        removeSeriesSelect();
        removeSeriesInput();
    }
    if (chartTypeSelect.value === 'barChart') {
        // create the bars select if there are selected years
        if (yearsAreSelected() && barsSelect == null) {
            createBarsSelect();
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
    const chartCode = getChartCode(chartTypeSelect.value);
    const years = getSelectedYears();

    console.log(projectName);
    console.log(chartCode);
    console.log(years);

    const data = {
        name: projectName,
        chart: chartCode,
        years: years
    };

    if (chartCode === 0) {
        const barCode = columns.indexOf(barsSelect.value);
        // add the bars to the data object
        data.bars = barCode;
        console.log(barCode);

        if (seriesSelect.value !== '') {
            const seriesCode = columns.indexOf(seriesSelect.value);
            const seriesValue = seriesInput.value;

            console.log(seriesCode);
            console.log(seriesValue);

            // add the series to the data object
            data.seriesCode = seriesCode;
            data.seriesValue = seriesValue;
        }
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
        } catch (error) {
            showMessage(errorMessage, "Failed to parse JSON response");
            return;
        }

        if (response.ok) {
            // The project creation was successful
            showMessage(successMessage, "Project successfully created. Redirecting...");
            setTimeout(() => {
                window.location.href = "/home";
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
