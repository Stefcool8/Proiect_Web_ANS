function validateResetData(email) {
    if (!email) {
        return "Email is required";
    }
    return null;
}

async function fetchResetData(data) {
    try {
        const response = await fetch('/api/password/reset', {
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

function handleResetResponse(response) {
    if (response && response.hasOwnProperty("status_code") && response.status_code === 200) {
        // the reset was successful
        showMessage(successMessage, response.data.message);
        submitButton.disabled = true;
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

const resetForm = document.querySelector("form");
const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");
const submitButton = document.querySelector("button");

resetForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    // get the email
    const email = document.getElementById('email').value;

    const data = {
        email: email
    };

    const error = validateResetData(email);
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
