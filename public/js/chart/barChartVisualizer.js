let height = 500; // default height
let width = 1500; // default width
let margin = ({top: 20, right: 0, bottom: 30, left: 40}); // default margin
let chartData = []; // default data

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

function addSvgAttributes(svg, x, y, xAxis, yAxis) {
    svg.append("g")
        .attr("class", "bars")
        .attr("fill", "steelblue")
        .selectAll("rect")
        .data(chartData)
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

function drawBarChart(project) {
    const json = JSON.parse(project.data.data.json);

    chartData = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);

    const x = d3.scaleBand()
        .domain(chartData.map(d => d.name))
        .range([margin.left, width - margin.right])
        .padding(0.1);

    const y = d3.scaleLinear()
        .domain([0, d3.max(chartData, d => d.value)])
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
        .attr("viewBox", [-3/2 * margin.left, 0, width+3*margin.left, height])
        .call(zoom, x, y, xAxis);

    addSvgAttributes(svg, x, y, xAxis, yAxis);
}

function addBarChartFields(project) {
    const bars = project.data.data.dataColumn;

    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

    addLabelAndTextInput(inputGroup, 'bars', 'Bars', columnCodeToName(bars,columns), true);

    // verify if seriesCode and seriesValue exist
    // if they do, add them to the input group
    if (project.data.data.seriesCode) {
        const seriesCode = project.data.data.seriesCode;
        const seriesValue = project.data.data.seriesValue;

        addLabelAndTextInput(inputGroup, 'seriesCode', 'Series Column', columnCodeToName(seriesCode,columns), true);
        addLabelAndTextInput(inputGroup, 'seriesValue', 'Series Value', seriesValue, true);
    }

    detailContainer.appendChild(inputGroup);
}

function drawEnlargedBarChart(project, scaleFactor = 4) {
    const json = JSON.parse(project.data.data.json);
    const chartData = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);

    const newWidth = width * scaleFactor;
    const newHeight = height * scaleFactor;

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
        .call(d3.axisBottom(x).tickSizeOuter(0));

    const yAxis = g => g
        .attr("transform", `translate(${margin.left},0)`)
        .call(d3.axisLeft(y))
        .call(g => g.select(".domain").remove());

    const svg = d3.select('body')
        .append("svg")
        .attr("width", newWidth)
        .attr("height", newHeight)
        .style("display", "none");

    addSvgAttributes(svg, x, y, xAxis, yAxis);

    return svg;
}

// Function to download enlarged SVG
function downloadSvg(project) {
    const svg = drawEnlargedBarChart(project);
    const svgData = new XMLSerializer().serializeToString(svg.node());
    const blob = new Blob([svgData], { type: 'image/svg+xml' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');

    document.body.appendChild(a);
    a.style.display = 'none';
    a.href = url;
    a.download = `${project.data.data.name}_enlarged.svg`;
    a.click();
    document.body.removeChild(a);
    svg.remove();
    window.URL.revokeObjectURL(url);
}

// Function to convert SVG to Canvas
function svgToCanvas(svg, scaleFactor) {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        canvas.width = width * scaleFactor;
        canvas.height = height * scaleFactor;
        const ctx = canvas.getContext('2d');

        const img = new Image();
        img.onload = function() {
            ctx.drawImage(img, 0, 0, width * scaleFactor, height * scaleFactor);
            svg.remove();
            resolve(canvas);
        };

        const svgData = new XMLSerializer().serializeToString(svg.node());
        const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
        img.src = URL.createObjectURL(svgBlob);
    });
}

// Function to download enlarged PNG
async function downloadPng(project) {
    const svg = drawEnlargedBarChart(project);
    const canvas = await svgToCanvas(svg, 4);
    const pngUrl = canvas.toDataURL('image/png');
    const a = document.createElement('a');

    document.body.appendChild(a);
    a.style.display = 'none';
    a.href = pngUrl;
    a.download = `${project.data.data.name}_enlarged.png`;
    a.click();
    document.body.removeChild(a);
}

async function downloadWebp(project) {
    const svg = drawEnlargedBarChart(project);
    const canvas = await svgToCanvas(svg, 4);
    const webpUrl = canvas.toDataURL('image/webp');
    const a = document.createElement('a');

    document.body.appendChild(a);
    a.style.display = 'none';
    a.href = webpUrl;
    a.download = `${project.data.data.name}_enlarged.webp`;
    a.click();
    document.body.removeChild(a);
}

// Function to download enlarged JPEG
async function downloadJpeg(project) {
    const svg = drawEnlargedBarChart(project);
    const canvas = await svgToCanvas(svg, 4);
    const pngUrl = canvas.toDataURL('image/png');

    // Create a new canvas with a white background
    const pngCanvas = document.createElement('canvas');
    pngCanvas.width = canvas.width;
    pngCanvas.height = canvas.height;
    const pngCtx = pngCanvas.getContext('2d');
    pngCtx.fillStyle = '#ffffff';
    pngCtx.fillRect(0, 0, pngCanvas.width, pngCanvas.height);

    // Load the PNG image and draw it on the canvas
    const img = new Image();
    img.onload = function () {
        pngCtx.drawImage(img, 0, 0);

        // Convert the PNG to JPEG
        const jpegCanvas = document.createElement('canvas');
        jpegCanvas.width = pngCanvas.width;
        jpegCanvas.height = pngCanvas.height;
        const jpegCtx = jpegCanvas.getContext('2d');
        jpegCtx.fillStyle = '#ffffff';
        jpegCtx.fillRect(0, 0, jpegCanvas.width, jpegCanvas.height);
        jpegCtx.drawImage(pngCanvas, 0, 0);

        const jpegUrl = jpegCanvas.toDataURL('image/jpeg', 1.0);
        const a = document.createElement('a');
        document.body.appendChild(a);
        a.style.display = 'none';
        a.href = jpegUrl;
        a.download = `${project.data.data.name}_enlarged.jpeg`;
        a.click();
        document.body.removeChild(a);
    };
    img.src = pngUrl;
}
