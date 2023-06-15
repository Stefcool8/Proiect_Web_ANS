const loginForm = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
let result;


loginForm.addEventListener('submit', async(event) => {

    event.preventDefault();

    // get the username and password
    console.log("loginForm submit");
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const data = {
        username: username,
        password: password
    };

    try {
        // send the username and password to the backend
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });

        result = await response.json();

        if (response.ok) {
            // the login was successful
            localStorage.setItem("jwt", result.data.token);

            //localStorage.setItem("user", JSON.stringify(result.data.user));

            // redirect to dashboard
            window.location.href = "/dashboard";
        } else {
            showError(result.data.error);
        }

    } catch (error) {
        console.error(error);
        showError("An error occurred. Please try again later.");
    }
});

function showError(message) {
    const errorDiv = document.querySelector(".error-message");
    errorDiv.textContent = message;
    errorDiv.classList.add("visible");

    setTimeout(() => {
        errorDiv.classList.remove("visible");
    }, 3000);
}