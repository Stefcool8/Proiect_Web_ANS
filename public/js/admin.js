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

        if (response.status === 401) {
            // Unauthorized access, redirect to home page
            window.location.href = "/home";
            return;
        }

        const result = await response.json();

        if (response.ok) {
            document.querySelector('.page-name p').textContent = 'Admin page, Hello ' + result.data.data.username;
        } else {
            // Handle the error
            console.error(result.message);
        }

    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});