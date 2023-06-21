function drawPieChart(project) {
    // Specify the chart’s dimensions.
    const json = JSON.parse(project.data.data.json);

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

    const paths = chartGroup.selectAll("path")
        .data(arcs)
        .join("path")
        .attr("fill", d => color(d.data.name))
        .attr("d", arc)
        .append("title")
        .text(d => `${d.data.name}: ${d.data.value} (${(d.data.value / total * 100).toFixed(2)}%)`);

    const labels = chartGroup.selectAll("text")
        .data(arcs)
        .join("text")
        .attr("transform", d => `translate(${arc.centroid(d)})`)
        .attr("dy", "0.35em")
        .attr("text-anchor", "start")
        .attr("font-size", "6px") // Adjust the font size here
        .text(d => `${d.data.name}: (${(d.data.value / total * 100).toFixed(2)}%)`)
        .text(d => `${d.data.name} : (${(d.data.value / total * 100).toFixed(2)}%)`)
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



    let rotation = 0;
    svg.call(d3.drag()
        .on("drag", function (event) {
            const [x, y] = d3.pointer(event);
            const angle = Math.atan2(y, x) * (180 / Math.PI);
            rotation += angle * 0.04;
            svg.attr("transform", `rotate(${rotation})`);
        }));

    document.getElementById('chart-container').appendChild(svg.node());
}

function addPieChartFields(project) {
    const slices = project.data.data.dataColumn;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    addLabelAndTextInput(inputGroup, 'slices', 'Slices', columnCodeToName(slices, columns), true);

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
