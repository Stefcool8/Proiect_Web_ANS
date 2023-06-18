const detailContainer = document.getElementById('detail-container');
const chartContainer = document.getElementById('chart-container');

const downloadCsvButton = document.getElementById('download-csv');
const downloadPngButton = document.getElementById('download-png');
const downloadJpegButton = document.getElementById('download-jpeg');
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
    }
}

function drawChart(project) {
    const chartType = project.data.data.chart;

    switch (chartType) {
        case 0:
            drawBarChart(project);
            break;
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

function dataURItoBlob(dataURI) {
    // https://stackoverflow.com/questions/12168909/blob-from-dataurl
    const byteString = atob(dataURI.split(',')[1]);
    const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]

    const ab = new ArrayBuffer(byteString.length);
    const ia = new Uint8Array(ab);

    for (let i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ab], {type: mimeString});
}

function downloadPng(project) {
    // download a png, the same way as the csv (with a popup)
    // https://stackoverflow.com/questions/23218174/how-do-i-save-export-an-svg-file-after-creating-an-svg-with-d3-js-ie-safari-an
    const svg = document.querySelector('svg');
    const svgData = new XMLSerializer().serializeToString(svg);

    const canvas = document.createElement("canvas");
    const svgSize = svg.getBoundingClientRect();
    canvas.width = svgSize.width;
    canvas.height = svgSize.height;

    const ctx = canvas.getContext("2d");

    const img = document.createElement("img");
    img.setAttribute("src", "data:image/svg+xml;base64," + btoa(svgData));

    img.onload = function() {
        ctx.drawImage(img, 0, 0);

        const pngFile = canvas.toDataURL("image/png");

        const blob = dataURItoBlob(pngFile);
        const url = window.URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', `${project.data.data.name}.png`);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        window.URL.revokeObjectURL(url);
    };

    console.log("Downloaded PNG");
}

function downloadJpeg(project) {
    // download a jpeg, the same way as the csv (with a popup)

    const svg = document.querySelector('svg');
    const svgData = new XMLSerializer().serializeToString(svg);

    const canvas = document.createElement("canvas");
    const svgSize = svg.getBoundingClientRect();
    canvas.width = svgSize.width;
    canvas.height = svgSize.height;

    const ctx = canvas.getContext("2d");

    const img = document.createElement("img");
    img.setAttribute("src", "data:image/svg+xml;base64," + btoa(svgData));

    img.onload = function() {
        ctx.drawImage(img, 0, 0);

        const jpegFile = canvas.toDataURL("image/jpeg");

        const blob = dataURItoBlob(jpegFile);
        const url = window.URL.createObjectURL(blob);

        const a = document.createElement('a');
        a.setAttribute('hidden', '');
        a.setAttribute('href', url);
        a.setAttribute('download', `${project.data.data.name}.jpeg`);
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        window.URL.revokeObjectURL(url);
    }

    console.log("Downloaded JPEG");
}

function downloadSvg(project) {
    const svg = document.querySelector('svg');

    // get the svg data
    const svgData = new XMLSerializer().serializeToString(svg);

    const blob = new Blob([svgData], { type: 'image/svg+xml' });
    const url = window.URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', `${project.data.data.name}.svg`);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    window.URL.revokeObjectURL(url);

    console.log("Downloaded SVG");
}

function addDownloadButtonListeners(project) {
    downloadCsvButton.addEventListener('click', function() {
        downloadCsv(project);
    });

    downloadPngButton.addEventListener('click', function() {
        downloadPng(project);
    });

    downloadJpegButton.addEventListener('click', function() {
        downloadJpeg(project);
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
