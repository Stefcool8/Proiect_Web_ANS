const projectInitializationForm = document.getElementById("project-initialization-form");

projectInitializationForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    console.log("projectInitializationForm submit");
    const projectName = document.getElementById("project-name").value;
    const chartType = document.getElementById("chart-type").value;

    const data = {
        projectName: projectName,
        chartType: chartType,
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
            console.error("Failed to parse JSON response", error);
            return;
        }

        if (response.ok) {
            // The reset was successful
            // Redirect to the login page
            window.location.href = "/dashboard";
        } else {
            // Handle the error
            console.error(result.message);
        }
    }
    catch (error) {
        // Handle any errors
        console.error(error);
    }
});
