const loginForm = document.querySelector('form');


loginForm.addEventListener('submit', async(event) => {

    event.preventDefault();

    console.log("loginForm submit")
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

            // Redirect to the dashboard
            window.location.href = "/dashboard";
        } else {
            // Handle the error
            console.error(result.message);
        }

    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});