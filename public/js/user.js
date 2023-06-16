document.addEventListener('DOMContentLoaded', async() => {
    const token = localStorage.getItem('jwt');
    const url = window.location.href;
    const urlParts = url.split("/");
    const uuid = urlParts[urlParts.length - 1];
    const data = { uuid: uuid };
    if (!token) {
        window.location.href = "/login";
    }

    try {
        console.log("BEFORE");
        console.log(JSON.stringify(data));
        console.log('Bearer ' + token);
        const response = await fetch('/api/auth/verifyAccess', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
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