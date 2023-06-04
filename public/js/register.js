// Select the form
const form = document.querySelector("form");

// Listen for form submit
form.addEventListener("submit", async (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Create a FormData instance from the form
    const formData = new FormData(form);

    // Convert FormData to JSON
    const data = Object.fromEntries(formData.entries());

    console.log(data);
    // Send POST request
    try {
        const response = await fetch("/api/user", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(data),
        });
        // Get the JSON response
        const result = await response.json();

        console.log(result);

        // Handle response
        if (response.ok) {
            console.log("Registration successful:", result);
            // redirect or update UI as necessary

            // Redirect to the login page
            window.location.href = "/login";
        } else {
            console.error("Registration error:", result);
            // show error to the user
        }
    } catch (err) {
        console.error("Fetch error:", err);
        // show error to the user
    }
});
