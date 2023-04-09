const data = [
    { car: "Audi", count: 800 },
    { car: "BMW", count: 1000 },
    { car: "Ford", count: 300 },
    { car: "Mercedes-Benz", count: 1200 },
    { car: "Renault", count: 2100 },
    { car: "Volkswagen", count: 4000 },
];

const margin = { top: 40, right: 20, bottom: 30, left: 40 };

function createBarChart() {
    d3.select("#chart svg").remove();

    const chartContainer = d3.select("#chart-container");
    const width =
        chartContainer.node().getBoundingClientRect().width -
        margin.left -
        margin.right;
    const height = 0.6 * width;

    const x = d3
        .scaleBand()
        .range([0, width])
        .padding(width < 400 ? 0.2 : 0.1);
    const y = d3.scaleLinear().range([height, 0]);

    const svg = d3
        .select("#chart")
        .append("svg")
        .attr("width", "100%")
        .attr("height", "100%")
        .attr(
            "viewBox",
            `0 0 ${width + margin.left + margin.right} ${
                height + margin.top + margin.bottom
            }`
        )
        .append("g")
        .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

    x.domain(
        data.map(function (d) {
            return d.car;
        })
    );
    y.domain([
        0,
        d3.max(data, function (d) {
            return d.count;
        }),
    ]);

    svg.selectAll(".bar")
        .data(data)
        .enter()
        .append("rect")
        .attr("class", "bar")
        .attr("x", function (d) {
            return x(d.car);
        })
        .attr("width", x.bandwidth())
        .attr("y", function (d) {
            return y(d.count);
        })
        .attr("height", function (d) {
            return height - y(d.count);
        });

    svg.append("g")
        .attr("transform", "translate(0," + height + ")")
        .call(d3.axisBottom(x));

    svg.append("text")
        .attr("x", width / 2)
        .attr("y", 0 - margin.top / 2)
        .attr("text-anchor", "middle")
        .style("font-size", "16px")
        .style("text-decoration", "underline")
        .text("Project name 1");

    svg.append("g").call(d3.axisLeft(y));
}

function downloadPNG() {
    const svgElement = document.querySelector("svg");

    domtoimage
        .toPng(svgElement)
        .then(function (dataUrl) {
            triggerDownload(dataUrl);
        })
        .catch(function (error) {
            console.error("Error occurred while converting SVG to PNG:", error);
        });
}

function downloadSVG() {
    const svgElement = document.querySelector("svg");

    domtoimage
        .toSvg(svgElement)
        .then(function (dataUrl) {
            const link = document.createElement("a");
            link.href = dataUrl;
            link.download = "chart.svg";
            link.click();
        })
        .catch(function (error) {
            console.error(
                "Error occurred while converting SVG to SVG file:",
                error
            );
        });
}

function downloadCSV() {
    const csvData = Papa.unparse(data);
    const csvBlob = new Blob([csvData], { type: "text/csv;charset=utf-8" });
    const url = URL.createObjectURL(csvBlob);
    const link = document.createElement("a");
    link.href = url;
    link.download = "data.csv";
    link.click();
}

function downloadJPEG() {
    const svgElement = document.querySelector("svg");

    domtoimage
        .toJpeg(svgElement, { quality: 0.95 })
        .then(function (dataUrl) {
            const link = document.createElement("a");
            link.href = dataUrl;
            link.download = "chart.jpeg";
            link.click();
        })
        .catch(function (error) {
            console.error(
                "Error occurred while converting SVG to JPEG:",
                error
            );
        });
}

function triggerDownload(imgURI) {
    const evt = new MouseEvent("click", {
        view: window,
        bubbles: false,
        cancelable: true,
    });

    const a = document.createElement("a");
    a.setAttribute("download", "chart.png");
    a.setAttribute("href", imgURI);
    a.setAttribute("target", "_blank");

    a.dispatchEvent(evt);
}

window.addEventListener("resize", createBarChart);
document.getElementById("download-png").addEventListener("click", downloadPNG);
document.getElementById("download-svg").addEventListener("click", downloadSVG);
document.getElementById("download-csv").addEventListener("click", downloadCSV);
document
    .getElementById("download-jpeg")
    .addEventListener("click", downloadJPEG);
createBarChart();
