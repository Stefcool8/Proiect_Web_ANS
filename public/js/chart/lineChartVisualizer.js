const defaultHeight = 500;
const defaultWidth = 1500;

function drawLineChart(project) {
    // Specify the chart's dimensions.
    const json = JSON.parse(project.data.data.json);

    const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => a.name - b.name);

    // Extract the names and values into separate arrays.
    const names = data.map((entry) => entry.name);
    const values = data.map((entry) => entry.value);

    // Remove existing chart if any.
    d3.select("#chart-container").select("svg").remove();

    // Get the width and height of the container element or use default values.
    const containerWidth = defaultWidth;
    const containerHeight = defaultHeight;

    // Set up the SVG element and chart dimensions based on the container size.
    const svg = d3.select("#chart-container")
        .append("svg")
        .attr("viewBox", `0 0 ${containerWidth} ${containerHeight}`)
        .attr("preserveAspectRatio", "xMidYMid meet")
        .classed("svg-content", true);

    const margin = { top: 20, right: 20, bottom: 30, left: 50 };
    const width = containerWidth - margin.left - margin.right;
    const height = containerHeight - margin.top - margin.bottom;

    const g = svg.append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    // Set the x and y scales.
    const x = d3.scaleBand()
        .rangeRound([0, width])
        .padding(0.1)
        .domain(names);

    const y = d3.scaleLinear()
        .rangeRound([height, 0])
        .domain([0, d3.max(values)]);

    // Draw the x-axis.
    g.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x));

    // Draw the y-axis.
    g.append("g")
        .call(d3.axisLeft(y))
        .append("text")
        .attr("fill", "#000")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", "0.71em")
        .attr("text-anchor", "end")
        .style("font-size", "14px")
        .text("Value");

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
        .style("font-size", "11px")
        .text((d) => d.value);
    
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
