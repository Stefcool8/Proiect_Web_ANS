// dashboard.js

document.addEventListener('DOMContentLoaded', async() => {
    const token = localStorage.getItem('jwt');
    if (!token) {
        window.location.href = "/login";
    }

    try {
        const response = await fetch('/api/dashboard', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        if (response.status === 401) {
            // Unauthorized access, redirect to login page
            window.location.href = "/login";
            return;
        }

        const result = await response.json();
        //console.log(result)

        if (response.ok) {
            console.log(result);
            // Populate the dashboard with the user data
            document.querySelector('.page-name p').textContent = 'Dashboard, Hello ' + result.data.data.username;
            // Fill in other parts of the page using result.data
            //let user = JSON.parse(localStorage.getItem("user"));
            //console.log(user);
            //console.log(user.uuid);
            const userLink = document.querySelector(".view-profile-btn");
            //userLink.href = "/user/" + user.uuid;
            userLink.href = "/user/" + result.data.data.uuid;

        } else {
            // Handle the error
            console.error(result.message);
        }

    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});