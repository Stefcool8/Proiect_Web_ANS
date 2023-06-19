<!DOCTYPE html>
<html>
<head>
    <title>Pie Chart Example</title>
    <script src="https://d3js.org/d3.v7.min.js"></script>
    <style>
        svg {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
<div id="pie-chart"></div>

<script>
    const data = [
        { label: 'Category A', value: 30 },
        { label: 'Category B', value: 20 },
        { label: 'Category C', value: 50 },
    ];

    const width = 400;
    const height = 400;
    const radius = Math.min(width, height) / 2;

    const svg = d3.select('#pie-chart')
        .append('svg')
        .attr('width', width)
        .attr('height', height);

    const g = svg.append('g')
        .attr('transform', `translate(${width / 2}, ${height / 2})`);

    const pieLayout = d3.pie()
        .value(d => d.value);

    const pieData = pieLayout(data);

    const arcGenerator = d3.arc()
        .innerRadius(0)
        .outerRadius(radius);

    const slices = g.selectAll('path')
        .data(pieData)
        .enter()
        .append('path')
        .attr('d', arcGenerator)
        .attr('fill', (d, i) => `hsl(${i * 60}, 70%, 50%)`);
</script>
</body>
</html>