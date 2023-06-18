const detailContainer = document.getElementById('detail-container');
const chartContainer = document.getElementById('chart-container');
const columns = [
    "JUDET",
    "CATEGORIE_NATIONALA",
    "CATEGORIA_COMUNITARA",
    "MARCA",
    "DESCRIERE_COMERCIALA",
    "TOTAL"
];
let height = 500; // default height
let width = 1500; // default width
let margin = ({top: 20, right: 0, bottom: 30, left: 40}); // default margin

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

    detailContainer.appendChild(inputGroup);
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

function zoom(svg, x, y, xAxis) {
    const extent = [[margin.left, margin.top], [width - margin.right, height - margin.top]];

    svg.call(d3.zoom()
        .scaleExtent([1, 8])
        .translateExtent(extent)
        .extent(extent)
        .on("zoom", zoomed));

    function zoomed(event) {
        x.range([margin.left, width - margin.right].map(d => event.transform.applyX(d)));
        svg.selectAll(".bars rect").attr("x", d => x(d.name)).attr("width", x.bandwidth());
        svg.selectAll(".x-axis").call(xAxis);
    }
}

function drawBarChart(project) {
    const json = JSON.parse(project.data.data.json);

    const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);

    const x = d3.scaleBand()
        .domain(data.map(d => d.name))
        .range([margin.left, width - margin.right])
        .padding(0.1);

    const y = d3.scaleLinear()
        .domain([0, d3.max(data, d => d.value)])
        .nice()
        .range([height - margin.bottom, margin.top]);

    const xAxis = g => g
        .attr("transform", `translate(0,${height - margin.bottom})`)
        .call(d3.axisBottom(x).tickSizeOuter(0));

    const yAxis = g => g
        .attr("transform", `translate(${margin.left},0)`)
        .call(d3.axisLeft(y))
        .call(g => g.select(".domain").remove())

    // Create the chart
    const svg = d3.select("#chart-container")
        .append("svg")
        .attr("viewBox", [0, 0, width, height])
        .call(zoom, x, y, xAxis);

    svg.append("g")
        .attr("class", "bars")
        .attr("fill", "steelblue")
        .selectAll("rect")
        .data(data)
        .join("rect")
        .attr("x", d => x(d.name))
        .attr("y", d => y(d.value))
        .attr("height", d => y(0) - y(d.value))
        .attr("width", x.bandwidth());

    svg.append("g")
        .attr("class", "x-axis")
        .call(xAxis);

    svg.append("g")
        .attr("class", "y-axis")
        .call(yAxis);
}

function drawChart(project) {
    const chartType = project.data.data.chart;

    switch (chartType) {
        case 0:
            drawBarChart(project);
            break;
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

            // Populate the HTML fields with the fetched data
            populateProjectDetails(project);

            // Draw the chart
            drawChart(project);
        } else {
            console.error("Failed to fetch project details");
        }
    } catch (error) {
        console.error(error);
    }
}
