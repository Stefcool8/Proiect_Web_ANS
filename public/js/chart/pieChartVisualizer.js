
/*
function addZoomBehavior(svg, g, labels) {
    const zoom = d3.zoom()
        .scaleExtent([1, 10])
        .on("zoom", zoomed);

    svg.call(zoom);

    const initialScale = svg.node().getBoundingClientRect().width / svg.attr("width");
    const initialTransform = d3.zoomIdentity.translate(svg.attr("width") / 2, svg.attr("height") / 2);

    function zoomed(event) {
        const currentScale = event.transform.k;
        const newTransform = event.transform.translate(
            initialTransform.x * currentScale,
            initialTransform.y * currentScale
        );
        g.attr("transform", newTransform);
        labels.attr("font-size", 12 / (currentScale * initialScale));
    }
}
*/


function drawPieChart(project) {
    // Specify the chart’s dimensions.
    const json = JSON.parse(project.data.data.json);

    /*const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);
     */
    const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => a.name.localeCompare(b.name));
    const total = data.reduce((sum, d) => sum + d.value, 0);

    data.forEach(d => {
        d.percentage = (d.value / total) * 100;
    });

    console.log(data);

    const color = d3.scaleOrdinal()
        .domain(data.map(d => d.name))
        .range(d3.quantize(t => d3.interpolateSpectral(t * 0.8 + 0.1), data.length).reverse());

    const pie = d3.pie()
        .sort(null)
        .value(d => d.value);

    const arcs = pie(data);

    const width = 600; // Adjust the width here
    const height = 400; // Adjust the height here
    const arc = d3.arc()
        .innerRadius(0)
        .outerRadius(Math.min(width, height) / 2 - 1);

    const svg = d3.create("svg")
        .attr("viewBox", [-width / 2, -height / 2, width, height]);

    const chartGroup = svg.append("g")
        .attr("stroke", "white");

    /*const paths = chartGroup.selectAll("path")
        .data(arcs)
        .join("path")
        .attr("fill", d => color(d.data.name))
        .attr("d", arc)
        .append("title")
        .text(d => `${d.data.name} : ${d.data.value}`);
     */

    const paths = chartGroup.selectAll("path")
        .data(arcs)
        .join("path")
        .attr("fill", d => color(d.data.name))
        .attr("d", arc)
        .append("title")
        .text(d => `${d.data.name}: ${d.data.value} (${(d.data.value / total * 100).toFixed(2)}%)`);

    /*
    // Adding legend
    const legendGroup = svg.append("g")
        .attr("transform", `translate(${width / 2}, ${height / 2 + 150})`); // Adjust the position of the legend

    const legendItems = legendGroup.selectAll("g")
        .data(data)
        .join("g")
        .attr("transform", (d, i) => `translate(0, ${i * 20})`); // Adjust the spacing between legend items

    legendItems.append("rect")
        .attr("width", 18)
        .attr("height", 18)
        .attr("fill", d => color(d.name));

    legendItems.append("text")
        .attr("x", 24)
        .attr("y", 9)
        .attr("dy", "0.35em")
        .text(d => d.name);


     */
    document.getElementById('chart-container').appendChild(svg.node());
}

function addPieChartFields(project) {
    const slices = project.data.data.bars;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    addLabelAndTextInput(inputGroup, 'slices', 'Slices', columnCodeToName(slices,columnsPieChart), true);

    // verify if seriesCode and seriesValue exist
    // if they do, add them to the input group
    if (project.data.data.seriesCode) {
        const seriesCode = project.data.data.seriesCode;
        const seriesValue = project.data.data.seriesValue;

        addLabelAndTextInput(inputGroup, 'seriesCode', 'Series Column', columnCodeToName(seriesCode,columnsPieChart), true);
        addLabelAndTextInput(inputGroup, 'seriesValue', 'Series Value', seriesValue, true);
    }

    detailContainer.appendChild(inputGroup);
}
