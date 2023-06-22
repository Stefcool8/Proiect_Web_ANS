function validateResetData(password, confirmPassword) {
    if (!password) {
        return "New password is required";
    }
    if (!confirmPassword) {
        return "Confirm password is required";
    }
    if (password !== confirmPassword) {
        return "Passwords do not match";
    }
    return null;
}

async function fetchResetData(data) {
    const token = document.getElementById('token').value;
    try {
        const response = await fetch('/api/password/reset', {
            method: 'PUT',
            headers: {
                Authorization: `Bearer ${token}`,
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

function handleResetResponse(response) {
    if (response && response.hasOwnProperty("status_code") && response.status_code === 200) {
        showMessage(successMessage, "Password reset successful. Redirecting...");
        submitButton.disabled = true;
        setTimeout(() => {
            window.location.href = "/login";
        }, 3000);

    } else {
        showMessage(errorMessage, response.data.error);
    }
}

const resetForm = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const submitButton = document.querySelector("button");

resetForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    const data = {
        password: password,
    };

    const error = validateResetData(password, confirmPassword);
    if (error) {
        showMessage(errorMessage, error);
        return;
    }

    const response = await fetchResetData(data);

    if (!response) {
        showMessage(errorMessage, "An error occurred. Please try again later.");
        return;
    }

    handleResetResponse(response);
});
