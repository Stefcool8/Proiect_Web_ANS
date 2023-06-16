function validateLoginData(username, password) {
    if (!username) {
        return "Username is required";
    }
    if (!password) {
        return "Password is required";
    }
    return null;
}

async function fetchLoginData(data) {
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return await response.json();
    } catch (error) {
        console.error(error);
        return null;
    }
}

function handleLoginResponse(response) {

    if (response && response.hasOwnProperty("status_code") && response.status_code === 200) {
        // the login was successful
        localStorage.setItem("jwt", response.data.token);
        localStorage.setItem("user", JSON.stringify(response.data.user));
        showMessage(successMessage, "Successfully logged in. Redirecting...");
        submitButton.disabled = true;
        setTimeout(() => {
            window.location.href = "/dashboard";
        }, 3000);

    } else {
        showMessage(errorMessage, response.data.error);
    }
}

function showMessage(element, message) {
    element.textContent = message;
    element.classList.add("visible");

    setTimeout(() => {
        hideMessage(element);
    }, 3000);
}

function hideMessage(element) {
    element.classList.remove("visible");
}

const loginForm = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const submitButton = document.querySelector("button");

loginForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    // get the username and password
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    const data = {
        username: username,
        password: password
    };

    const error = validateLoginData(username, password);
    if (error) {
        showMessage(errorMessage, error);
        return;
    }

    const response = await fetchLoginData(data);

    if (!response) {
        showMessage(errorMessage, "An error occurred. Please try again later.");
        return;
    }

    handleLoginResponse(response);
});