function addMapChartFields(project) {
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('input-group');

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
            setDescription(name, 'Total: ' + value);
        });

        // if the json is empty, display a message
        if (Object.keys(json).length === 0) {
            counties.forEach(county => {
                setDescription(county, 'No data');
            })
        }
    });

    // load countrymap.js script dynamically
    loadJS("/public/js/svg/countrymap.js", false);

    // remove png, jpeg, webp, and svg export buttons
    document.getElementById('download-png').style.display = 'none';
    document.getElementById('download-jpeg').style.display = 'none';
    document.getElementById('download-webp').style.display = 'none';
    document.getElementById('download-svg').style.display = 'none';
}
