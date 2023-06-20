//admin page
document.addEventListener('DOMContentLoaded', async() => {
    const token = localStorage.getItem('jwt');

    if (!token) {
        window.location.href = "/login";
    }

    try {
        console.log(token);
        const response = await fetch('/api/auth/admin', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        if (response.status === 401) {
            // Unauthorized access, redirect to home page
            window.location.href = "/home";
            return;
        }

        const result = await response.json();
        console.log(result)

        if (response.ok) {
            console.log(result.data.username)
                // Populate the dashboard with the user data
            document.querySelector('.page-name p').textContent = 'Admin page, Hello ' + result.data.data.username;
            // Fill in other parts of the page using result.data
        } else {
            // Handle the error
            console.error(result.message);
        }

    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});