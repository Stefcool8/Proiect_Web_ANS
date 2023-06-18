const container = document.getElementById('project-container');
const columns = [
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIA_COMUNITARA",
    "MARCA",
    "DESCRIERE_COMERCIALA",
    "TOTAL"
];

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

function chartCodeToName(chartCode) {
    switch (chartCode) {
        case 0:
            return "Bar Chart";
        case 1:
            return "Line Chart";
        case 2:
            return "Pie Chart";
    }
    return "Unknown";
}

function columnCodeToName(columnCode) {
    return columns[columnCode];
}

function addLabelAndTextInput(parent, htmlFor, labelText, inputValue, readOnly) {
    const label = document.createElement('label');
    label.htmlFor = htmlFor;
    label.textContent = labelText;
    const input = document.createElement('input');
    input.classList.add(htmlFor);
    input.id = htmlFor;
    input.name = htmlFor;
    input.type = "text";
    input.value = inputValue;
    input.readOnly = readOnly;

    parent.appendChild(label);
    parent.appendChild(input);
}

function addBarChartFields(project) {
    const bars = project.data.data.bars;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    addLabelAndTextInput(inputGroup, 'bars', 'Bars', columnCodeToName(bars), true);

    // verify if seriesCode and seriesValue exist
    // if they do, add them to the input group
    if (project.data.data.seriesCode) {
        const seriesCode = project.data.data.seriesCode;
        const seriesValue = project.data.data.seriesValue;

        addLabelAndTextInput(inputGroup, 'seriesCode', 'Series Column', columnCodeToName(seriesCode), true);
        addLabelAndTextInput(inputGroup, 'seriesValue', 'Series Value', seriesValue, true);
    }

    container.appendChild(inputGroup);
}

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
            console.log(project.data.data.name);
            console.log(project.data.data.chart);
            console.log(project.data.data.years);
            console.log(project.data.data.bars);
            console.log(project.data.data.seriesCode);
            console.log(project.data.data.seriesValue);

            populateProjectDetails(project);

        } else {
            console.error("Failed to fetch project details");
        }
    } catch (error) {
        console.error(error);
    }
}
