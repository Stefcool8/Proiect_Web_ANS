function validateProjectData(projectName, chartType) {
    if (!projectName) {
        return "Project name is required";
    }
    if (!chartType) {
        return "Chart type is required";
    }
    return null;
}

async function fetchProjectData(data) {
    try {
        const token = localStorage.getItem("jwt");
        const response = await fetch('/api/project', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token,
            },
            body: JSON.stringify(data),
        });
        return await response.json();
    } catch (error) {
        console.error(error);
        return null;
    }
}

function handleProjectResponse(response) {

    if (response && response.hasOwnProperty("status_code") && response.status_code === 200) {
        // the project creation was successful
        showMessage(successMessage, "Project successfully created. Redirecting...");
        submitButton.disabled = true;
        setTimeout(() => {
            window.location.href = "/dashboard";
        }, 3000);

    } else {
        showMessage(errorMessage, response.data.error);
    }
}

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

const projectForm = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const submitButton = document.querySelector("button");

projectForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    // get the project name and chart type
    const projectName = document.getElementById('project-name').value;
    const chartType = document.getElementById('chart-type').value;

    const data = {
        project_name: projectName,
        chart_type: chartType
    };

    const error = validateProjectData(projectName, chartType);
    if (error) {
        showMessage(errorMessage, error);
        return;
    }

    const response = await fetchProjectData(data);

    if (!response) {
        showMessage(errorMessage, "An error occurred. Please try again later.");
        return;
    }

    handleProjectResponse(response);
});
