document.addEventListener('DOMContentLoaded', function() {

    const url = window.location.href;
    const uuid = url.substring(url.lastIndexOf('/') + 1);

    // Show an error message if there is no uuid parameter in the URL
    if (!uuid) {
        alert("No UUID specified");
        return;
    }
    fetchProjectDetails(uuid);
});

async function fetchProjectDetails(uuid) {
    const token = localStorage.getItem('jwt');
    if (!token) {
        window.location.href = "/login";
    }

    try {
        const apiURL ="/api/project/"+uuid;
        const response = await fetch(apiURL, {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + token,
            },
        });


        if (response.ok) {
            const project = await response.json();

            console.log(project);
            console.log(project.data.name);
            console.log(project.data.chart);
            // Populate the HTML fields with the fetched data
            document.getElementById("projectName").value = project.data.data.name;
            document.getElementById("projectType").value = project.data.data.chart;
        } else {
            console.error("Failed to fetch project details");
        }
    } catch (error) {
        console.error(error);
    }
}
