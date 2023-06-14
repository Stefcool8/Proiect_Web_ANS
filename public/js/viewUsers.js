document.addEventListener('DOMContentLoaded', async() => {
    const token = localStorage.getItem('jwt');
    if (!token) {
        window.location.href = "/login";
    }
    try {
        const response = await fetch('/api/auth/admin', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        const result = await response.json();
        console.log(result);

        if (response.status === 401) {
            // Unauthorized access, redirect to login page
            window.location.href = "/home";
            return;
        }
    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});

var deleteButtons = document.querySelectorAll(
    ".user-list .button-area a:last-child"
);
var viewProfileButtons = document.querySelectorAll(
    ".user-list .button-area a:first-child"
);

deleteButtons.forEach(function(button) {
    button.addEventListener("click", async(event) => {
        event.preventDefault();
        var userRow = event.target.closest(".project");
        var userData = JSON.parse(userRow.dataset.user);
        var uuid = userData.uuid;
        console.log("Deleted button clicked");
        console.log("UUID:", uuid);

        try {
            const apiUrl = "/api/user/" + uuid;
            const response = await fetch(apiUrl, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                },
                body: uuid,
            });

            if (response.ok) {
                window.location.href = "/viewUsers";
            } else {
                // Handle the error
                console.error(result.message);
            }
        } catch (error) {
            // Handle any errors
            console.error(error);
        }
    });
});

// Iterate over each "View Profile" button
viewProfileButtons.forEach(function(button) {
    // Add a click event listener to each button
    button.addEventListener("click", async(event) => {
        event.preventDefault();
        var userRow = event.target.closest(".project");
        var userData = JSON.parse(userRow.dataset.user);
        var uuid = userData.uuid;
        console.log("View Button clicked");
        console.log("UUID:", uuid);

        try {
            const apiUrl = "/api/user/" + uuid;
            const response = await fetch(apiUrl, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            });

            if (response.ok) {
                // The view was successful
                const URL = "/user/" + uuid;
                window.location.href = URL;
            } else {
                // Handle the error
                console.error(result.message);
            }
        } catch (error) {
            // Handle any errors
            console.error(error);
        }
    });
});

var userlistContainer = document.querySelector(".user-list");

userlistContainer.addEventListener("click", function(event) {
    // Check if the click occurred within the "user-list" region
    if (event.target.closest(".user-list")) {
        // Code to handle the click on the "user-list" region

        // Example: Log a message to the console
        console.log("Clicked on the user-list region");
    }
});