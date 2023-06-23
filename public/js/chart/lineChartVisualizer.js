const lineHeight = 500;
const lineWidth = 1500;
const lineExportWidth = 5120;
const lineExportHeight = 2880;
const axisFontSize = 16;
const labelFontSize = 16;
const exportAxisFontSize = 64;
const exportLabelFontSize = 64;
let lineMargin = {top: 10, right: 0, bottom: 10, left: 40};

function generalLineChart(project, width, height, axisSize, labelSize) {
    let svg;
    lineMargin = {top: lineMargin.top + axisSize, right: lineMargin.right + axisSize,
                  bottom: lineMargin.bottom + axisSize, left: lineMargin.left + axisSize};
    const json = JSON.parse(project.data.data.json);

    const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => a.name - b.name);

    // Extract the names and values into separate arrays.
    const names = data.map((entry) => entry.name);
    const values = data.map((entry) => entry.value);

    // Set up the SVG element and chart dimensions based on the container size.
    svg = d3.create("svg")
        .attr("viewBox", [-3/2 * lineMargin.left, -lineMargin.top, width + 3*lineMargin.left, height + 2*lineMargin.top])
        .attr("preserveAspectRatio", "xMidYMid meet")
        .classed("svg-content", true);

    const g = svg.append("g")
        .attr("transform", "translate(" + lineMargin.left + "," + lineMargin.top + ")");

    // Set the x and y scales.
    const x = d3.scaleBand()
        .rangeRound([0, width - lineMargin.left - lineMargin.right])
        .padding(0.1)
        .domain(names);

    const y = d3.scaleLinear()
        .rangeRound([height - lineMargin.top - lineMargin.bottom, 0])
        .domain([0, d3.max(values)]);

    // Draw the x-axis.
    g.append("g")
        .attr("transform", "translate(0," + (height - lineMargin.top - lineMargin.bottom) + ")")
        .call(d3.axisBottom(x))
        .selectAll("text")
        .style("font-size", axisSize);

    // Draw the y-axis.
    g.append("g")
        .call(d3.axisLeft(y))
        .selectAll("text")
        .style("font-size", axisSize);

    // Draw the line chart.
    g.append("path")
        .datum(data)
        .attr("fill", "none")
        .attr("stroke", "steelblue")
        .attr("stroke-width", 1.5)
        .attr("d", d3.line()
            .x((d) => x(d.name) + x.bandwidth() / 2)
            .y((d) => y(d.value)));

    // Draw the thickened points and labels.
    g.selectAll(".point")
        .data(data)
        .enter()
        .append("circle")
        .attr("class", "point")
        .attr("cx", (d) => x(d.name) + x.bandwidth() / 2)
        .attr("cy", (d) => y(d.value))
        .attr("r", 4)
        .attr("fill", "steelblue");

    g.selectAll(".label")
        .data(data)
        .enter()
        .append("text")
        .attr("class", "label")
        .attr("x", (d) => x(d.name) + x.bandwidth() / 2)
        .attr("y", (d) => y(d.value) - 10)
        .attr("text-anchor", "middle")
        .style("font-size", labelSize)
        .text((d) => d.value);

    return svg;
}

function drawLineChart(project) {
    let lineSvg = generalLineChart(project, lineWidth, lineHeight, axisFontSize, labelFontSize);
    document.getElementById("chart-container").appendChild(lineSvg.node());
}

function exportLineChart(project) {
    // Return an enlarged version of the chart. (using the export dimensions)
    return generalLineChart(project, lineExportWidth, lineExportHeight, exportAxisFontSize, exportLabelFontSize);
}

function addLineChartFields(project) {
    const slices = project.data.data.dataColumn;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    addLabelAndTextInput(inputGroup, 'lines', 'Lines', columnCodeToName(slices,columns), true);
    addLabelAndTextInput(inputGroup,'lines-input','Line Value',project.data.data.lineValue,true);
    // verify if seriesCode and seriesValue exist
    // if they do, add them to the input group
    if (project.data.data.seriesCode != null) {
        const seriesCode = project.data.data.seriesCode;
        const seriesValue = project.data.data.seriesValue;

        addLabelAndTextInput(inputGroup, 'seriesCode', 'Series Column', columnCodeToName(seriesCode,columns), true);
        addLabelAndTextInput(inputGroup, 'seriesValue', 'Series Value', seriesValue, true);
    }

    detailContainer.appendChild(inputGroup);
}
