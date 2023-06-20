const detailContainer = document.getElementById('detail-container');
const chartContainer = document.getElementById('chart-container');

const downloadCsvButton = document.getElementById('download-csv');
const downloadPngButton = document.getElementById('download-png');
const downloadJpegButton = document.getElementById('download-jpeg');
const downloadWebpButton = document.getElementById('download-webp');
const downloadSvgButton = document.getElementById('download-svg');

document.addEventListener('DOMContentLoaded', function() {
    const url = window.location.href;
    const uuid = url.substring(url.lastIndexOf('/') + 1);

    // Show an error message if there is no uuid parameter in the URL
    if (!uuid) {
        alert("No UUID specified");
        return;
    }

    fetchProjectDetails(uuid).then();
});

function populateProjectDetails(project) {
    // Populate the HTML fields with the fetched data
    document.getElementById("projectName").value = project.data.data.name;
    document.getElementById("chartType").value = chartCodeToName(project.data.data.chart);
    document.getElementById("years").value = project.data.data.years;

    // Add fields specific to the chart type
    if (project.data.data.chart === 0) {
        addBarChartFields(project);
    } else if (project.data.data.chart === 2) {
        addPieChartFields(project);
    } else if (project.data.data.chart === 3) {
        addMapChartFields(project);
    }
}

function drawChart(project) {
    const chartType = project.data.data.chart;
    console.log("ChartType: " + chartType);
    switch (chartType) {
        case 0:
            drawBarChart(project);
            break;
        case 2:
            drawPieChart(project);
            break;
        case 3:
            drawMapChart(project);
            break;
        default:
            console.log("Invalid chart type");
    }
}

function downloadCsv(project) {
    const json = JSON.parse(project.data.data.json);

    const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);

    const csv = d3.csvFormat(data);

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', `${project.data.data.name}.csv`);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    window.URL.revokeObjectURL(url);

    console.log("Downloaded CSV");
}

function addDownloadButtonListeners(project) {
    downloadCsvButton.addEventListener('click', function() {
        downloadCsv(project);
    });

    downloadPngButton.addEventListener('click', function() {
        downloadPng(project).then();
    });

    downloadJpegButton.addEventListener('click', function() {
        downloadJpeg(project).then();
    });

    downloadWebpButton.addEventListener('click', function() {
        downloadWebp(project).then();
    });

    downloadSvgButton.addEventListener('click', function() {
        downloadSvg(project);
    });
}

async function fetchProjectDetails(uuid) {
    const token = localStorage.getItem('jwt');
    if (!token) {
        window.location.href = "/login";
    }

    try {
        const response = await fetch(`/api/project/${uuid}`, {
            method: "GET",
            headers: {
                'Authorization': 'Bearer ' + token,
            },
        });
        if (response.ok) {
            const project = await response.json();

            console.log(project);

            // Populate the HTML fields with the fetched data
            populateProjectDetails(project);

            // Draw the chart
            drawChart(project);

            // Add event listeners to the download buttons
            addDownloadButtonListeners(project);
        } else {
            console.error("Failed to fetch project details");
        }
    } catch (error) {
        console.error(error);
    }
}
