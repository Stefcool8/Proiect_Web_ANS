const response = fetch("/api/auth", {
    method: "GET",
    headers: {
        Authorization: "Bearer " + localStorage.getItem("jwt"),
        "Content-Type": "application/json",
    }
});

response.then((response) => {
    if (response.ok) {
        return response.json();
    }
}).then((data) => {
    if (data) {
        window.location.href = "/dashboard";
    }
});