// select the form
const form = document.querySelector("form");

// listen for form submit
form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const firstName = document.getElementById("first-name").value;
    const lastName = document.getElementById("last-name").value;
    const email = document.getElementById("email").value;
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (password !== confirmPassword) {
        showError("Passwords do not match.");
        return;
    }

    const data = {
        firstName: firstName,
        lastName: lastName,
        email: email,
        username: username,
        password: password,
    };

    try {
        const response = await fetch("/api/user", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });

        // get the JSON response
        const result = await response.json();

        if (response.ok) {
            // redirect to the login page
            window.location.href = "/login";
        } else {
            showError(result.data.error);
        }
    } catch (err) {
        showError(err.message);
    }
});

// show the error message
function showError(message) {
    const errorDiv = document.querySelector(".error-message");
    errorDiv.textContent = message;
    errorDiv.classList.add("visible");

    setTimeout(() => {
        errorDiv.classList.remove("visible");
    }, 3000);
}
