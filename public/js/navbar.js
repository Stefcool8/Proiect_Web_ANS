document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('jwt');
    
    if (!token) {
        return;
    }

    try {
        const response = await fetch('/api/auth', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        if (response.ok) {
            // If the token is valid, change the navbar links
            const signInLink = document.querySelector('.navigation-links .nav-item a[href="/login"]');
            
            if (!signInLink) {
                console.error('Sign in link not found');
                return;
            }
            
            signInLink.href = "/dashboard";
            signInLink.textContent = "Dashboard";

            // Assuming there's an 'i' element within the 'a' element
            const icon = signInLink.querySelector('i');
            if (icon) {
                icon.className = "fas fa-tachometer-alt";
            }

            // Add sign out link
            const signOutLink = document.createElement('li');
            signOutLink.className = "nav-item";
            signOutLink.innerHTML = '<a href="/logout">Sign Out<i class="fas fa-sign-out-alt"></i></a>';
            
            const navLinks = document.querySelector('.navigation-links');
            if (!navLinks) {
                console.error('Navigation links not found');
                return;
            }
            navLinks.appendChild(signOutLink);

            // Add event listener to the Sign Out link
            signOutLink.querySelector('a').addEventListener('click', (e) => {
                e.preventDefault();
                // Remove the JWT token from the localStorage
                localStorage.removeItem('jwt');
                // Redirect to the home page
                window.location.href = '/home';
            });
        }

    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});
