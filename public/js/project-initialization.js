const projectInitializationForm = document.getElementById("project-initialization-form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const chartTypeSelect = document.getElementById("chart-type");
let yearCheckboxContainer = null;
let barsSelect = null;

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
    // Hardcode the bars labels into an array
    const bars = [
        "JUDET",
        "CATEGORIE_NATIONALA",
        "CATEGORIA_COMUNITARA",
        "MARCA",
        "DESCRIERE_COMERCIALA",
        "TOTAL"
    ];

    // Iterate over the bars array and create an option for each bar
    for (let i = 0; i < bars.length; i++) {
        const barOption = document.createElement('option');
        barOption.value = bars[i];
        barOption.textContent = bars[i];
        barsSelect.appendChild(barOption);
    }
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

    inputGroup.appendChild(barsLabel);
    inputGroup.appendChild(barsSelect);

    // add the div to the form before the create button
    projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
}

chartTypeSelect.addEventListener("change", () => {
    if (yearCheckboxContainer == null) {
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
                        // populate the bars select
                        createBarsSelect();
                    }
                } else {
                    // Checkbox is deselected
                    // remove the bars select and its label if all years are deselected
                    const yearCheckboxes = document.querySelectorAll('input[type="checkbox"]');
                    let allUnchecked = true;
                    for (let i = 0; i < yearCheckboxes.length; i++) {
                        if (yearCheckboxes[i].checked) {
                            allUnchecked = false;
                            break;
                        }
                    }

                    if (barsSelect != null && allUnchecked) {
                        // remove the bars select and its label
                        barsSelect.remove();
                        barsSelect = null;
                        document.querySelector('label[for="bars"]').remove();
                    }
                }
            }
        });

        inputGroup.appendChild(label);
        inputGroup.appendChild(yearCheckboxContainer);

        // add the div to the form before the create button
        projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
    }
});

projectInitializationForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    // Check if at least one year is selected
    const yearCheckboxes = document.querySelectorAll('input[type="checkbox"]');
    let isYearSelected = false;
    for (let i = 0; i < yearCheckboxes.length; i++) {
        if (yearCheckboxes[i].checked) {
            isYearSelected = true;
            break;
        }
    }

    if (!isYearSelected) {
        showMessage(errorMessage, "Please select at least one year");
        return;
    }

    const projectName = document.getElementById("project-name").value;
    const chartType = document.getElementById("chart-type").value;

    console.log(projectName);
    console.log(chartType);

    const data = {
        name: projectName,
        chart: chartType,
    };

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
