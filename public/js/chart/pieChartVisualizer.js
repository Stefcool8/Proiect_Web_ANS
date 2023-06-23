let pieHeight = 500; // default height
let pieWidth = 1200; // default width
let pieExportWidth = 5120; // default export width
let pieExportHeight = 2880; // default export height
let pieFontSize = 6; // default font size
let pieExportFontSize = 40; // default export font size
let pieFontColor = "#000000"; // default font color
let currentRotation = 0; // default rotation
let transform = "translate(0, 0)"; // default transform
let chartGroup, pieSvg, pieZoom;

function zoomed(event) {
    transform = event.transform;
    chartGroup.attr("transform", `${transform} rotate(${currentRotation})`);
}

function reset() {
    currentRotation = 0; // Reset the rotation angle
    transform = "translate(0, 0)"; // Reset the transform
    // chartGroup.attr("transform", `${transform} rotate(${currentRotation})`);
    pieSvg.call(pieZoom.transform, d3.zoomIdentity);
}

function rotateClockwise() {
    currentRotation += 45; // Update the rotation angle
    chartGroup.attr("transform", `${transform} rotate(${currentRotation})`);
}

function rotateCounterClockwise() {
    currentRotation -= 45; // Update the rotation angle
    chartGroup.attr("transform", `${transform} rotate(${currentRotation})`);
}

function addControlButtons() {
    const buttonDiv = document.createElement("div");
    buttonDiv.id = "rotation-buttons";
    buttonDiv.classList.add("button-container");
    chartContainer.appendChild(buttonDiv);

    const resetButton = document.createElement("button");
    resetButton.innerHTML = "Reset";
    resetButton.onclick = reset;

    const clockwiseButton = document.createElement("button");
    clockwiseButton.innerHTML = "Rotate Clockwise";
    clockwiseButton.onclick = rotateClockwise;

    const counterClockwiseButton = document.createElement("button");
    counterClockwiseButton.innerHTML = "Rotate Counter-Clockwise";
    counterClockwiseButton.onclick = rotateCounterClockwise;

    buttonDiv.appendChild(resetButton);
    buttonDiv.appendChild(counterClockwiseButton);
    buttonDiv.appendChild(clockwiseButton);
}

function generalPieChart(project, width, height, zoom, fontSize) {
    let svg;
    const json = JSON.parse(project.data.data.json);

    const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => a.name.localeCompare(b.name));
    const total = data.reduce((sum, d) => sum + d.value, 0);

    data.forEach(d => {
        d.percentage = (d.value / total) * 100;
    });

    const color = d3.scaleOrdinal()
        .domain(data.map(d => d.name))
        .range(d3.quantize(t => d3.interpolateSpectral(t * 0.8 + 0.1), data.length).reverse());

    const pie = d3.pie()
        .sort(null)
        .value(d => d.value);

    const arcs = pie(data);

    const arc = d3.arc()
        .innerRadius(0)
        .outerRadius(Math.min(width, height) / 2 - 1);

    if (zoom) {
        pieZoom = d3.zoom().on("zoom", zoomed);

        svg = d3.create("svg")
            .attr("viewBox", [-width / 2, -height / 2, width, height])
            .attr("id", "pie-chart") // Add an ID to the SVG element
            .call(pieZoom);
    } else {
        svg = d3.create("svg")
            .attr("viewBox", [-width / 2, -height / 2, width, height])
            .attr("id", "pie-chart"); // Add an ID to the SVG element
    }

    let generalChartGroup = svg.append("g")
        .attr("fill", pieFontColor);

    generalChartGroup.selectAll("path")
        .data(arcs)
        .join("path")
        .attr("fill", d => color(d.data.name))
        .attr("stroke", "#ffffff") // Set the stroke color
        .attr("stroke-width", 1) // Set the stroke width
        .attr("d", arc)
        .append("title")
        .text(d => `${d.data.name}: ${d.data.value} (${(d.data.value / total * 100).toFixed(2)}%)`);

    generalChartGroup.selectAll("text")
        .data(arcs)
        .join("text")
        .attr("transform", d => `translate(${arc.centroid(d)})`)
        .attr("dy", "0.35em")
        .attr("text-anchor", "middle")
        .attr("font-size", fontSize)
        .style("fill", pieFontColor)
        .text(d => `${d.data.name}: (${(d.data.value / total * 100).toFixed(2)}%)`)
        .each(function (d) {
            const bbox = this.getBBox();
            d.textWidth = bbox.width;
        })
        .attr("transform", function (d) {
            const centroid = arc.centroid(d);
            const x = centroid[0];
            const y = centroid[1];
            const offset = d.textWidth / 2; // Offset text position based on text width
            const angle = Math.atan2(y, x) * (180 / Math.PI) - 90; // Calculate angle for text rotation
            return `translate(${x}, ${y}) rotate(${angle}) translate(${offset}, 0) rotate(90)`;
        });

    // return svg and chartGroup
    return [svg, generalChartGroup];
}

function drawPieChart(project) {
    [pieSvg, chartGroup] = generalPieChart(project, pieWidth, pieHeight, true, pieFontSize);

    document.getElementById('chart-container').appendChild(pieSvg.node());

    // add the control buttons
    addControlButtons();
}

function addPieChartFields(project) {
    const slices = project.data.data.dataColumn;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    addLabelAndTextInput(inputGroup, 'slices', 'Slices', columnCodeToName(slices, columns), true);

    // verify if seriesCode and seriesValue exist
    // if they do, add them to the input group
    if (project.data.data.seriesCodes != null) {
        for (let i = 0; i < project.data.data.seriesCodes.length; i++) {
            const seriesCode = project.data.data.seriesCodes[i];
            const seriesValue = project.data.data.seriesValues[i];

            addLabelAndTextInput(inputGroup, 'seriesCode', 'Series Column',
                columnCodeToName(seriesCode, columns) + ': ' + seriesValue, true);
        }
    }

    detailContainer.appendChild(inputGroup);
}

function exportPieChart(project) {
    // create an enlarged pie chart for export
    return generalPieChart(project, pieExportWidth, pieExportHeight, false, pieExportFontSize)[0];
}
