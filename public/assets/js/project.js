const data = [
    { make: "Audi", count: 800 },
    { make: "BMW", count: 1000 },
    { make: "Ford", count: 300 },
    { make: "Mercedes-Benz", count: 1200 },
    { make: "Renault", count: 2100 },
    { make: "Volkswagen", count: 4000 },
];

const margin = { top: 40, right: 20, bottom: 30, left: 40 };
const width = 960 - margin.left - margin.right;
const height = 600 - margin.top - margin.bottom;

const x = d3.scaleBand().range([0, width]).padding(0.1);
const y = d3.scaleLinear().range([height, 0]);

const svg = d3
    .select("#chart")
    .append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

x.domain(
    data.map(function (d) {
        return d.make;
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
        return x(d.make);
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
