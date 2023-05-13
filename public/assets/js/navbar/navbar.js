console.log("IN NAVBAR.JS");
const jwtToken = localStorage.getItem('jwt');
if (jwtToken) {
    const navLinks = document.getElementById('nav-links');
    const logoutLink = document.createElement('li');
    logoutLink.className = 'nav-item';
    logoutLink.innerHTML = `<a href="/home" onclick="logout()">Logout<i class="fas fa-sign-out-alt"></i></a>`;
    navLinks.appendChild(logoutLink);
}

function logout() {
    localStorage.removeItem('jwt');
}
