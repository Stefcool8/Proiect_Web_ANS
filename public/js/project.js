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
    } else if (project.data.data.chart === 1) {
        addLineChartFields(project);
    } else if (project.data.data.chart === 2) {
        addPieChartFields(project);
    } else if (project.data.data.chart === 3) {
        addMapChartFields(project);
    }
}

function drawChart(project) {
    const chartType = project.data.data.chart;
    switch (chartType) {
        case 0:
            drawBarChart(project);
            break;
        case 1:
            drawLineChart(project);
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

function getExportSvg(project) {
    switch (project.data.data.chart) {
        case 0:
            return drawEnlargedBarChart(project);
        case 1:
            // return drawEnlargedLineChart(project);
        case 2:
            return drawEnlargedPieChart(project);
    }
}

function getExportDimensions(project) {
    switch (project.data.data.chart) {
        case 0:
            return [barExportWidth, barExportHeight];
        case 1:
            // return [lineExportWidth, lineExportHeight];
        case 2:
            return [pieExportWidth, pieExportHeight];
    }
}

function addDownloadButtonListeners(project) {
    downloadCsvButton.addEventListener('click', function() {
        downloadCsv(project).then();
    });

    downloadPngButton.addEventListener('click', function() {
        downloadPng(project, getExportSvg(project),
            getExportDimensions(project)[0], getExportDimensions(project)[1]).then();
    });

    downloadJpegButton.addEventListener('click', function() {
        downloadJpeg(project, getExportSvg(project),
            getExportDimensions(project)[0], getExportDimensions(project)[1]).then();
    });

    downloadWebpButton.addEventListener('click', function() {
        downloadWebp(project, getExportSvg(project),
            getExportDimensions(project)[0], getExportDimensions(project)[1]).then();
    });

    downloadSvgButton.addEventListener('click', function() {
        downloadSvg(project, getExportSvg(project),
            getExportDimensions(project)[0], getExportDimensions(project)[1]).then();
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
