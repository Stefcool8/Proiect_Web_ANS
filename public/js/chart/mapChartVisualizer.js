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