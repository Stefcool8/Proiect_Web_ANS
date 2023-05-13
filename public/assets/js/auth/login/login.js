const loginForm = document.querySelector('form');

console.log("login.js loaded")

loginForm.addEventListener('submit', async (event) => {

  event.preventDefault();

  console.log("loginForm submit")
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  const data = {
    username: username,
    password: password
  };

  try {
    const response = await fetch('/api/auth/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'Accept': 'application/json',
      },
      body: new URLSearchParams(data)
    });

    console.log("HEY");

    let result;
    try {
      
      result = await response.json();
      console.log("RESULT: " + result.status_code);
      console.log(result.data);

    } catch (error) {
      console.error("Failed to parse JSON response", error);
      return;
    }

    console.log("RESULT: " + result);

    if (response.ok) {
      // The login was successful
      localStorage.setItem('jwt', result.data);

      // Redirect to the dashboard
      window.location.href = "/dashboard";
    } else {
      // Handle the error
      console.error(result.message);
    }

  } catch (error) {
    // Handle any errors
    console.error(error);
  }
});
