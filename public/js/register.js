function validateRegisterData(firstName, lastName, email, username, password, confirmPassword) {
    if (!firstName || !lastName || !email || !username || !password) {
        return "All fields are required";
    }
    if (password !== confirmPassword) {
        return "Passwords do not match";
    }
    return null;
}

async function fetchRegisterData(data) {
    try {
        const response = await fetch('/api/user', {
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

function handleRegisterResponse(response) {
    if (response && response.hasOwnProperty("status_code") && response.status_code === 200) {
        showMessage(successMessage, "Successfully registered. Redirecting...");
        registerButton.disabled = true;
        setTimeout(() => {
            window.location.href = "/login";
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

const registerForm = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const registerButton = document.querySelector("button");

registerForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const firstName = document.getElementById("first-name").value;
    const lastName = document.getElementById("last-name").value;
    const email = document.getElementById("email").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    const data = {
        firstName: firstName,
        lastName: lastName,
        email: email,
        username: username,
        password: password,
    };

    const error = validateRegisterData(firstName, lastName, email, username, password, confirmPassword);
    if (error) {
        showMessage(errorMessage, error);
        return;
    }

    const response = await fetchRegisterData(data);

    if (!response) {
        showMessage(errorMessage, "An error occurred. Please try again later");
        return;
    }

    handleRegisterResponse(response);
});
