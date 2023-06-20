var simplemaps_countrymap_mapdata = {
    main_settings: {
        //General settings
        width: "700", //'700' or 'responsive'
        background_color: "#FFFFFF",
        background_transparent: "yes",
        border_color: "#ffffff",

        //State defaults
        state_description: "State description",
        state_color: "#88A4BC",
        state_hover_color: "#3B729F",
        state_url: "",
        border_size: 1.5,
        all_states_inactive: "no",
        all_states_zoomable: "yes",

        //Location defaults
        location_description: "Location description",
        location_url: "",
        location_color: "#FF0067",
        location_opacity: 0.8,
        location_hover_opacity: 1,
        location_size: 25,
        location_type: "square",
        location_image_source: "frog.png",
        location_border_color: "#FFFFFF",
        location_border: 2,
        location_hover_border: 2.5,
        all_locations_inactive: "no",
        all_locations_hidden: "no",

        //Label defaults
        label_color: "#d5ddec",
        label_hover_color: "#d5ddec",
        label_size: 22,
        label_font: "Arial",
        hide_labels: "no",
        hide_eastern_labels: "no",

        //Zoom settings
        zoom: "yes",
        manual_zoom: "yes",
        back_image: "no",
        initial_back: "no",
        initial_zoom: "-1",
        initial_zoom_solo: "no",
        region_opacity: 1,
        region_hover_opacity: 0.6,
        zoom_out_incrementally: "yes",
        zoom_percentage: 0.99,
        zoom_time: 0.5,

        //Popup settings
        popup_color: "white",
        popup_opacity: 0.9,
        popup_shadow: 1,
        popup_corners: 5,
        popup_font: "12px/1.5 Verdana, Arial, Helvetica, sans-serif",
        popup_nocss: "no",

        //Advanced settings
        div: "map",
        auto_load: "yes",
        url_new_tab: "no",
        images_directory: "default",
        fade_time: 0.1,
        link_text: "View Website",
        popups: "detect",
        state_image_url: "",
        state_image_position: "",
        location_image_url: ""
    },
    state_specific: {
        ROU122: {
            name: "Dolj",

        },
        ROU123: {
            name: "Gorj"
        },
        ROU124: {
            name: "Mehedinti"
        },
        ROU126: {
            name: "Olt"
        },
        ROU127: {
            name: "Teleorman"
        },
        ROU128: {
            name: "Bucharest"
        },
        ROU129: {
            name: "Calarasi"
        },
        ROU130: {
            name: "Dâmbovita"
        },
        ROU131: {
            name: "Giurgiu"
        },
        ROU132: {
            name: "Ialomita"
        },
        ROU133: {
            name: "Constanta"
        },
        ROU276: {
            name: "Arad"
        },
        ROU277: {
            name: "Bihor"
        },
        ROU278: {
            name: "Caras-Severin"
        },
        ROU280: {
            name: "Timis"
        },
        ROU287: {
            name: "Botosani"
        },
        ROU294: {
            name: "Alba"
        },
        ROU295: {
            name: "Bistrita-Nasaud"
        },
        ROU296: {
            name: "Cluj"
        },
        ROU297: {
            name: "Hunedoara"
        },
        ROU298: {
            name: "Maramures"
        },
        ROU299: {
            name: "Mures"
        },
        ROU300: {
            name: "Salaj"
        },
        ROU301: {
            name: "Satu Mare"
        },
        ROU302: {
            name: "Arges"
        },
        ROU303: {
            name: "Sibiu"
        },
        ROU304: {
            name: "Vâlcea"
        },
        ROU305: {
            name: "Brasov"
        },
        ROU306: {
            name: "Covasna"
        },
        ROU307: {
            name: "Harghita"
        },
        ROU308: {
            name: "Iasi"
        },
        ROU309: {
            name: "Neamt"
        },
        ROU310: {
            name: "Prahova"
        },
        ROU311: {
            name: "Suceava",
        },
        ROU312: {
            name: "Bacau"
        },
        ROU313: {
            name: "Braila"
        },
        ROU314: {
            name: "Buzau"
        },
        ROU315: {
            name: "Galati"
        },
        ROU316: {
            name: "Vaslui"
        },
        ROU317: {
            name: "Vrancea"
        },
        ROU4844: {
            name: "Ilfov"
        },
        ROU4847: {
            name: "Tulcea"
        }
    },
    locations: {
        "0": {
            lat: "44.433333",
            lng: "26.1",
            name: "Bucharest"
        }
    },
    labels: {},
    legend: {
        entries: []
    },
    regions: {},
};

function setDescription(countyName, description) {
    switch (countyName) {
        case "dolj":
            simplemaps_countrymap_mapdata.state_specific.ROU122.description = description;
            break;
        case "gorj":
            simplemaps_countrymap_mapdata.state_specific.ROU123.description = description;
            break;
        case "mehedinti":
            simplemaps_countrymap_mapdata.state_specific.ROU124.description = description;
            break;
        case "olt":
            simplemaps_countrymap_mapdata.state_specific.ROU126.description = description;
            break;
        case "teleorman":
            simplemaps_countrymap_mapdata.state_specific.ROU127.description = description;
            break;
        case "bucuresti":
            simplemaps_countrymap_mapdata.state_specific.ROU128.description = description;
            break;
        case "calarasi":
            simplemaps_countrymap_mapdata.state_specific.ROU129.description = description;
            break;
        case "dambovita":
            simplemaps_countrymap_mapdata.state_specific.ROU130.description = description;
            break;
        case "giurgiu":
            simplemaps_countrymap_mapdata.state_specific.ROU131.description = description;
            break;
        case "ialomita":
            simplemaps_countrymap_mapdata.state_specific.ROU132.description = description;
            break;
        case "constanta":
            simplemaps_countrymap_mapdata.state_specific.ROU133.description = description;
            break;
        case "arad":
            simplemaps_countrymap_mapdata.state_specific.ROU276.description = description;
            break;
        case "bihor":
            simplemaps_countrymap_mapdata.state_specific.ROU277.description = description;
            break;
        case "caras-severin":
            simplemaps_countrymap_mapdata.state_specific.ROU278.description = description;
            break;
        case "timis":
            simplemaps_countrymap_mapdata.state_specific.ROU280.description = description;
            break;
        case "botosani":
            simplemaps_countrymap_mapdata.state_specific.ROU287.description = description;
            break;
        case "alba":
            simplemaps_countrymap_mapdata.state_specific.ROU294.description = description;
            break;
        case "bistrita-nasaud":
            simplemaps_countrymap_mapdata.state_specific.ROU295.description = description;
            break;
        case "cluj":
            simplemaps_countrymap_mapdata.state_specific.ROU296.description = description;
            break;
        case "hunedoara":
            simplemaps_countrymap_mapdata.state_specific.ROU297.description = description;
            break;
        case "maramures":
            simplemaps_countrymap_mapdata.state_specific.ROU298.description = description;
            break;
        case "mures":
            simplemaps_countrymap_mapdata.state_specific.ROU299.description = description;
            break;
        case "salaj":
            simplemaps_countrymap_mapdata.state_specific.ROU300.description = description;
            break;
        case "satu mare":
            simplemaps_countrymap_mapdata.state_specific.ROU301.description = description;
            break;
        case "arges":
            simplemaps_countrymap_mapdata.state_specific.ROU302.description = description;
            break;
        case "sibiu":
            simplemaps_countrymap_mapdata.state_specific.ROU303.description = description;
            break;
        case "valcea":
            simplemaps_countrymap_mapdata.state_specific.ROU304.description = description;
            break;
        case "brasov":
            simplemaps_countrymap_mapdata.state_specific.ROU305.description = description;
            break;
        case "covasna":
            simplemaps_countrymap_mapdata.state_specific.ROU306.description = description;
            break;
        case "harghita":
            simplemaps_countrymap_mapdata.state_specific.ROU307.description = description;
            break;
        case "iasi":
            simplemaps_countrymap_mapdata.state_specific.ROU308.description = description;
            break;
        case "neamt":
            simplemaps_countrymap_mapdata.state_specific.ROU309.description = description;
            break;
        case "prahova":
            simplemaps_countrymap_mapdata.state_specific.ROU310.description = description;
            break;
        case "suceava":
            console.log("suceava")
            simplemaps_countrymap_mapdata.state_specific.ROU311.description = description;
            break;
        case "bacau":
            simplemaps_countrymap_mapdata.state_specific.ROU312.description = description;
            break;
        case "braila":
            simplemaps_countrymap_mapdata.state_specific.ROU313.description = description;
            break;
        case "buzau":
            simplemaps_countrymap_mapdata.state_specific.ROU314.description = description;
            break;
        case "galati":
            simplemaps_countrymap_mapdata.state_specific.ROU315.description = description;
            break;
        case "vaslui":
            simplemaps_countrymap_mapdata.state_specific.ROU316.description = description;
            break;
        case "vrancea":
            simplemaps_countrymap_mapdata.state_specific.ROU317.description = description;
            break;
        case "ilfov":
            simplemaps_countrymap_mapdata.state_specific.ROU4844.description = description;
            break;
        case "tulcea":
            simplemaps_countrymap_mapdata.state_specific.ROU4847.description = description;

    }
}

setDescription("suceava", "Suceava: 1.000 de cazuri");