const BASE_URL = document.currentScript.getAttribute("data-base-url");

// pages that require authentication
const needPermissionPages = [
    'dashboard',
];

// pages that do not require authentication
const guestPages = [
    'auth/login',
    'auth/register',
];

// loads a script
async function loadScript(url) {
    return new Promise((resolve, reject) => {
        let script = document.createElement("script");
        script.src = url;
        script.onload = resolve;
        script.onerror = reject;
        document.body.appendChild(script);
    });
}

async function loadContent() {
    // headers for the fetch request
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + localStorage.getItem('jwt'),
    };

    console.log(headers);
    const path = window.location.pathname.substring(1) || "home";

    // show the loading spinner
    showLoadingSpinner();

    try {

        // if the user is not logged in and the page requires authentication, redirect to the login page
        if (needPermissionPages.includes(path) && !isLoggedIn()) {
            window.location.href = "/auth/login";
            return;
        }

        // if the user is logged in and the page does not require authentication, redirect to the dashboard page
        if (guestPages.includes(path) && isLoggedIn()) {
            window.location.href = "/dashboard";
            return;
        }

        console.log(`/api/${path}`);
        // fetches the content of the requested page from the server
        let response = await fetch(`/api/${path}`, {
            headers: headers,
        });
        if (response.ok) {
            let data = await response.json();
            document.getElementById("app").innerHTML = data.content;

            // load the scripts required by the page
            for (const [key, scripts] of Object.entries(data.scripts)) {
                for (let script of scripts) {
                    console.log("Loading script: ", script);
                    await loadScript(`${BASE_URL}/public/assets/js/${key}/${script}`);
                    console.log("Script loaded: ", script);
                }
            }
        } else if (response.status === 404) {
            window.location.href = "/404.php";
        } else if (response.status === 401) {
            console.log("Authorization");
        } 
        else {
            throw new Error("Something went wrong");
        }
    } catch (error) {
        if (error instanceof TypeError) {
            // network error (possibly offline)
            console.error("Network error:", error);
        } else {
            // unknown error
            console.error(error);
        }
        window.location.href = "/";
    }
}

// Add a function to show the loading spinner
function showLoadingSpinner() {
    document.getElementById("app").innerHTML = "<div id='loading-spinner'></div>";
}

// checks if the user is logged in, frontend verification
function isLoggedIn() {
    const jwtToken = localStorage.getItem('jwt');
    return jwtToken !== null;
}

//  the event is dispatched to the window every time the active history entry changes
window.addEventListener("popstate", loadContent);

// the event is dispatched to the window when the page has finished loading
window.addEventListener("load", loadContent);
