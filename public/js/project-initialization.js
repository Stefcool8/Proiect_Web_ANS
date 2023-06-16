const projectInitializationForm = document.getElementById("project-initialization-form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const chartTypeSelect = document.getElementById("chart-type");
/*const yearCheckboxContainer = document.getElementById('year-checkboxes');*/

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

function addYearsToCheckboxContainer(yearCheckboxContainer) {
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

chartTypeSelect.addEventListener("change", () => {
    if (chartTypeSelect.value !== "") {
        // create the div for the years
        const inputGroup = document.createElement('div');
        inputGroup.classList.add('input-group');

        const label = document.createElement('label');
        label.htmlFor = 'years';
        label.textContent = 'Years:';

        const yearCheckboxContainer = document.createElement('div');
        yearCheckboxContainer.classList.add('year-checkbox-container');
        yearCheckboxContainer.id = 'year-checkboxes';
        addYearsToCheckboxContainer(yearCheckboxContainer);

        inputGroup.appendChild(label);
        inputGroup.appendChild(yearCheckboxContainer);

        // add the div to the form before the create button
        projectInitializationForm.insertBefore(inputGroup, projectInitializationForm.lastElementChild);
    }
});

projectInitializationForm.addEventListener("submit", async (event) => {
    event.preventDefault();

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
