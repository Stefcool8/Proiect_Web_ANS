const loginForm = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");

async function handleLoginFormSubmit(event) {
    event.preventDefault();

    const loginData = {
        username: document.getElementById('username').value,
        password: document.getElementById('password').value
    };

    try {
        const result = await sendLoginData(loginData);
        handleSuccessfulLogin(result);
    } catch (error) {
        console.error(error);
    }
}

async function sendLoginData(data) {
    const response = await fetch('/api/login', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data),
    });

    const result = await response.json();

    if (response.ok) {
        return result;
    } else {
        showError(result.data.error);
        throw new Error(result.data.error);
    }
}

function handleSuccessfulLogin(result) {
    localStorage.setItem("jwt", result.data.token);
    localStorage.setItem("user", JSON.stringify(result.data.user));

    showSuccess("Successfully logged in! Redirecting to dashboard...");

    setTimeout(() => {
        window.location.href = "/dashboard";
    }, 3000);
}

function showError(message) {
    errorMessage.textContent = message;
    errorMessage.classList.add("visible");

    setTimeout(() => {
        errorMessage.classList.remove("visible");
    }, 3000);
}

function showSuccess(message) {
    successMessage.textContent = message;
    successMessage.classList.add("visible");

    setTimeout(() => {
        successMessage.classList.remove("visible");
    }, 3000);
}

loginForm.addEventListener('submit', handleLoginFormSubmit);
