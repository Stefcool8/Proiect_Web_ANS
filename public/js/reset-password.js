const resetForm = document.querySelector("form");

resetForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    console.log("resetForm submit");
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm-password").value;
    const token = document.getElementById("token").value;

    if (password !== confirmPassword) {
        console.error("Passwords do not match");
        return;
    }

    const data = {
        token: token,
        password: password,
    };

    try {
        const response = await fetch("/api/password/reset", {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
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
            // The reset was successful
            // Redirect to the login page
            window.location.href = "/login";
        } else {
            // Handle the error
            console.error(result.message);
        }
    }
    catch (error) {
        // Handle any errors
        console.error(error);
    }
});
