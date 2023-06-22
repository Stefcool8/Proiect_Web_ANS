let barHeight = 500; // default height
let barWidth = 1500; // default width
const barExportWidth = 5120; // default export width
const barExportHeight = 2880; // default export height
let margin = ({top: 20, right: 0, bottom: 30, left: 40}); // default margin
let barChartData = []; // default data
const barFontSize = 16; // default font size

function zoom(svg, x, y, xAxis) {
    const extent = [[margin.left, margin.top], [barWidth - margin.right, barHeight - margin.top]];

    svg.call(d3.zoom()
        .scaleExtent([1, 8])
        .translateExtent(extent)
        .extent(extent)
        .on("zoom", zoomed));

    function zoomed(event) {
        x.range([margin.left, barWidth - margin.right].map(d => event.transform.applyX(d)));
        svg.selectAll(".bars rect").attr("x", d => x(d.name)).attr("width", x.bandwidth());
        svg.selectAll(".x-axis").call(xAxis);
    }
}

function addSvgAttributes(svg, x, y, xAxis, yAxis) {
    svg.append("g")
        .attr("class", "bars")
        .attr("fill", "steelblue")
        .selectAll("rect")
        .data(barChartData)
        .join("rect")
        .attr("x", d => x(d.name))
        .attr("y", d => y(d.value))
        .attr("height", d => y(0) - y(d.value))
        .attr("width", x.bandwidth());

    svg.append("g")
        .attr("class", "x-axis")
        .call(xAxis)
        .selectAll("text")
        .style("font-size", barFontSize)

    svg.append("g")
        .attr("class", "y-axis")
        .call(yAxis)
        .selectAll("text")
        .style("font-size", barFontSize);
}

function drawBarChart(project) {
    const json = JSON.parse(project.data.data.json);

    barChartData = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);

    const x = d3.scaleBand()
        .domain(barChartData.map(d => d.name))
        .range([margin.left, barWidth - margin.right])
        .padding(0.1);

    const y = d3.scaleLinear()
        .domain([0, d3.max(barChartData, d => d.value)])
        .nice()
        .range([barHeight - margin.bottom, margin.top]);

    const xAxis = g => g
        .attr("transform", `translate(0,${barHeight - margin.bottom})`)
        .call(d3.axisBottom(x).tickSizeOuter(0))
        .selectAll("text")
        .style("font-size", barFontSize);

    const yAxis = g => g
        .attr("transform", `translate(${margin.left},0)`)
        .call(d3.axisLeft(y))
        .call(g => g.select(".domain").remove())
        .selectAll("text")
        .style("font-size", barFontSize);

    // Create the chart
    const svg = d3.select("#chart-container")
        .append("svg")
        .attr("viewBox", [-3/2 * margin.left, 0, barWidth + 3*margin.left, barHeight])
        .call(zoom, x, y, xAxis);

    addSvgAttributes(svg, x, y, xAxis, yAxis);
}

function addBarChartFields(project) {
    const bars = project.data.data.dataColumn;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    addLabelAndTextInput(inputGroup, 'bars', 'Bars', columnCodeToName(bars, columns), true);

    // verify if seriesCode and seriesValue exist
    // if they do, add them to the input group
    if (project.data.data.seriesCode) {
        const seriesCode = project.data.data.seriesCode;
        const seriesValue = project.data.data.seriesValue;

        addLabelAndTextInput(inputGroup, 'seriesCode', 'Series Column', columnCodeToName(seriesCode, columns), true);
        addLabelAndTextInput(inputGroup, 'seriesValue', 'Series Value', seriesValue, true);
    }

    detailContainer.appendChild(inputGroup);
}

function drawEnlargedBarChart(project) {
    const json = JSON.parse(project.data.data.json);
    const chartData = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);

    const newWidth = barExportWidth;
    const newHeight = barExportHeight;

    const x = d3.scaleBand()
        .domain(chartData.map(d => d.name))
        .range([margin.left, newWidth - margin.right])
        .padding(0.1);

    const y = d3.scaleLinear()
        .domain([0, d3.max(chartData, d => d.value)])
        .nice()
        .range([newHeight - margin.bottom, margin.top]);

    const xAxis = g => g
        .attr("transform", `translate(0,${newHeight - margin.bottom})`)
        .call(d3.axisBottom(x).tickSizeOuter(0))
        .selectAll("text")
        .style("font-size", barFontSize);

    const yAxis = g => g
        .attr("transform", `translate(${margin.left},0)`)
        .call(d3.axisLeft(y))
        .call(g => g.select(".domain").remove())
        .selectAll("text")
        .style("font-size", barFontSize);

    const svg = d3.select('body')
        .append("svg")
        .attr("viewBox", [-3/2 * margin.left, 0, newWidth + 3*margin.left, newHeight])
        .style("display", "none");

    addSvgAttributes(svg, x, y, xAxis, yAxis);

    return svg;
}
