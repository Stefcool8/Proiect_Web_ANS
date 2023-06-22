// Function to convert SVG to Canvas
function svgToCanvas(svg, exportWidth, exportHeight, backgroundColor = 'transparent') {
    return new Promise((resolve) => {
        const canvas = document.createElement('canvas');
        canvas.width = exportWidth;
        canvas.height = exportHeight;
        const ctx = canvas.getContext('2d');

        const img = new Image();
        img.onload = function() {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, exportWidth, exportHeight);
            ctx.drawImage(img, 0, 0, exportWidth, exportHeight);
            svg.remove();
            resolve(canvas);
        };

        const svgData = new XMLSerializer().serializeToString(svg.node());
        const svgBlob = new Blob([svgData], { type: 'image/svg+xml;charset=utf-8' });
        img.src = URL.createObjectURL(svgBlob);
    });
}

async function downloadCsv(project) {
    const json = JSON.parse(project.data.data.json);

    const data = Object.entries(json)
        .map(([name, value]) => ({ name, value }))
        .sort((a, b) => b.value - a.value);

    const csv = d3.csvFormat(data);

    const blob = new Blob([csv], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.setAttribute('hidden', '');
    a.setAttribute('href', url);
    a.setAttribute('download', `${project.data.data.name}.csv`);
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);

    console.log("Downloaded CSV");
}

// Function to download enlarged SVG
async function downloadSvg(project, svg) {
    const svgData = new XMLSerializer().serializeToString(svg.node());
    const blob = new Blob([svgData], { type: 'image/svg+xml' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');

    document.body.appendChild(a);
    a.style.display = 'none';
    a.href = url;
    a.download = `${project.data.data.name}.svg`;
    a.click();
    document.body.removeChild(a);
    svg.remove();
    window.URL.revokeObjectURL(url);

    console.log("Downloaded SVG");
}

// Function to download enlarged PNG
async function downloadPng(project, svg, exportWidth, exportHeight) {
    const canvas = await svgToCanvas(svg, exportWidth, exportHeight);
    const pngUrl = canvas.toDataURL('image/png');
    const a = document.createElement('a');

    document.body.appendChild(a);
    a.style.display = 'none';
    a.href = pngUrl;
    a.download = `${project.data.data.name}.png`;
    a.click();
    document.body.removeChild(a);

    console.log("Downloaded PNG");
}

// Function to download enlarged JPEG
async function downloadJpeg(project, svg, exportWidth, exportHeight) {
    const canvas = await svgToCanvas(svg, exportWidth, exportHeight, 'white');

    const jpegData = canvas.toDataURL('image/jpeg');
    const a = document.createElement('a');

    document.body.appendChild(a);
    a.style.display = 'none';
    a.href = jpegData;
    a.download = `${project.data.data.name}.jpeg`;
    a.click();
    document.body.removeChild(a);

    console.log("Downloaded JPEG");
}

// Function to download enlarged PDF
async function downloadWebp(project, svg, exportWidth, exportHeight) {
    const canvas = await svgToCanvas(svg, exportWidth, exportHeight, 'white');
    const webpUrl = canvas.toDataURL('image/webp');
    const a = document.createElement('a');

    document.body.appendChild(a);
    a.style.display = 'none';
    a.href = webpUrl;
    a.download = `${project.data.data.name}.webp`;
    a.click();
    document.body.removeChild(a);

    console.log("Downloaded WEBP");
}
