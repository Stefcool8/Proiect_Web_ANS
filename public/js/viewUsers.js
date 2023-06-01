var deleteButtons = document.querySelectorAll(".user-list .button-area a:last-child");

deleteButtons.forEach(function(button) {
    button.addEventListener("click", async(event) => {
        event.preventDefault();
        var userRow = event.target.closest(".project");
        var userData = JSON.parse(userRow.dataset.user);
        var uuid = userData.uuid;
        console.log("Deleted button clicked");
        console.log("UUID:", uuid);

        try {
            const apiUrl = '/api/user/' + uuid;
            const response = await fetch(apiUrl, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: uuid,
            });

            /*let result;
            try {
                result = await response.json();

            } catch (error) {
                console.error("Failed to parse JSON response", error);
                return;
            }
            */
            if (response.ok) {
                // The delete was successful
                //localStorage.setItem('jwt', result.data.token);

                // Redirect to the dashboard
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