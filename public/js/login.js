const loginForm = document.querySelector('form');


loginForm.addEventListener('submit', async(event) => {

    event.preventDefault();

    console.log("loginForm submit");
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const data = {
        username: username,
        password: password
    };

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        let result;
        try {
            result = await response.json();

        } catch (error) {
            console.error("Failed to parse JSON response", error);
            return;
        }

        if (response.ok) {
            // The login was successful
            localStorage.setItem('jwt', result.data.token);

            // if (result.data.user['isAdmin'] == true) {
            // Redirect to the dashboard
            if (!result.data.user['isAdmin']) {
                window.location.href = "/dashboard";
            } else {
                window.location.href = "/admin";
            }
            //}
        } else {
            // Handle the error
            console.error(result.message);
        }

    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});