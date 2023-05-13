document.getElementById("contact-form").addEventListener("submit", async (event) => {
    event.preventDefault();

    console.log("Post");

    const formAlert = document.getElementById("form-alert");
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const subject = document.getElementById("subject").value;
    const message = document.getElementById("message").value;

    try {
        const response = await fetch("/api/contact", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                Accept: "application/json",
            },
            body: new URLSearchParams({ name, email, subject, message }),
        });
        const jsonResponse = await response.json();
        formAlert.style.display = "block";
        formAlert.style.opacity = "1";

        if (response.status === 200) {
            formAlert.className = "alert alert-success";
        } else {
            formAlert.className = "alert alert-danger";
        }

        formAlert.textContent = jsonResponse.message;

        // Fade out after 3 seconds
        setTimeout(() => {
            formAlert.style.opacity = "0";
            // Hide the element after it's faded out
            setTimeout(() => {
                formAlert.style.display = "none";
            }, 1000); // Matches the transition duration
        }, 3000);
    } catch (error) {
        formAlert.style.display = "block";
        formAlert.style.opacity = "1";
        formAlert.className = "alert alert-danger";
        formAlert.textContent = "An error occurred. Please try again later.";
    }
});
