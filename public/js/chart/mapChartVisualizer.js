const mapExportWidth = 1000;
const mapExportHeight = 500;

function addMapChartFields(project) {
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

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

function drawMapChart(project) {
    // create a div for the map
    const mapDiv = document.createElement('div');
    mapDiv.id = 'map-container';
    mapDiv.classList.add('container');
    mapDiv.style.maxWidth = '50rem';
    chartContainer.style.display = 'none';
    document.getElementById('main-content').insertBefore(mapDiv, chartContainer);

    // load mapdata.js script dynamically
    loadJS("/public/js/svg/mapdata.js", false, function () {
        // parse the json
        const json = JSON.parse(project.data.data.json);

        // populate the map
        Object.entries(json).forEach(([name, value]) => {
            name = name.toLowerCase();
            console.log(name + ": " + value);
            setDescription(name, 'Total: ' + value);
        });
    });

    // load countrymap.js script dynamically
    loadJS("/public/js/svg/countrymap.js", false);

    // remove png, jpeg, webp, and svg export buttons
    document.getElementById('download-png').style.display = 'none';
    document.getElementById('download-jpeg').style.display = 'none';
    document.getElementById('download-webp').style.display = 'none';
    document.getElementById('download-svg').style.display = 'none';
}
