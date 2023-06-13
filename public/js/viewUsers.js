var deleteButtons = document.querySelectorAll(
    ".user-list .button-area a:last-child"
);
var viewProfileButtons = document.querySelectorAll(
    ".user-list .button-area a:first-child"
);

deleteButtons.forEach(function (button) {
    button.addEventListener("click", async (event) => {
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
viewProfileButtons.forEach(function (button) {
    // Add a click event listener to each button
    button.addEventListener("click", async (event) => {
        event.preventDefault();
        var userRow = event.target.closest(".project");
        var userData = JSON.parse(userRow.dataset.user);
        var uuid = userData.uuid;
        console.log("View Button clicked");
        console.log("UUID:", uuid);

        try {
            // Redirect to the dashboard
            console.log("/user/" + encodeURIComponent(uuid));
            window.location.href = "/user/" + encodeURIComponent(uuid);
            //window.location.href = "/profile." + encodeURIComponent(uuid);
        } catch (error) {
            // Handle any errors
            console.error(error);
        }
    });
});
