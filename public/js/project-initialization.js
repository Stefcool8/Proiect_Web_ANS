const projectInitializationForm = document.getElementById("project-initialization-form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");

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
