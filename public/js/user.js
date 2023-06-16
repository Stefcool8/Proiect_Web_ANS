document.addEventListener('DOMContentLoaded', async() => {
    let token = localStorage.getItem('jwt');
    let urlParts = window.location.href.split("/");
    let uuid = urlParts[urlParts.length - 1];
    let data = { uuid: uuid };

    if (!token) {
        window.location.href = "/login";
    }

    try {
        let verifyAccessResponse = await fetch('/api/auth/verifyAccess', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        if (verifyAccessResponse.status === 401) {
            // Unauthorized access, redirect to home page
            window.location.href = "/home";
            return;
        }

        // After verifying access, make a request to get the user information
        let userResponse = await fetch(`/api/user/${uuid}`, {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        let userResult = await userResponse.json();

        console.log(userResult.data)
        // Update user data on the webpage
        document.querySelector('.username1').textContent = userResult.data.data.username;
        document.querySelector('.email1').textContent = userResult.data.data.email;
        document.querySelector('.data.first-name').textContent = userResult.data.data.firstName;
        document.querySelector('.data.last-name').textContent = userResult.data.data.lastName;
        document.querySelector('.data.email').textContent = userResult.data.data.email;
        document.querySelector('.data.username').textContent = userResult.data.data.username;

    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});
