document.addEventListener('DOMContentLoaded', async() => {
    try {
        const token = localStorage.getItem('jwt');

        if (!token) {
            redirectTo("/login");
            return;
        }

        const uuid = getCurrentPageUuid();
        const data = { uuid: uuid };

        const accessVerified = await verifyAccess(token, data);
        if (!accessVerified) {
            redirectTo("/home");
            return;
        }

        const userResult = await fetchUser(token, uuid);
        displayUser(userResult);

        setupButtons(uuid, token);

    } catch (error) {
        console.error(error);
    }
});

function getCurrentPageUuid() {
    const urlParts = window.location.href.split("/");
    return urlParts[urlParts.length - 1];
}

async function verifyAccess(token, data) {
    const response = await fetch('/api/auth/verifyAccess', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    });
    return response.status !== 401;
}

async function fetchUser(token, uuid) {
    const response = await fetch(`/api/user/${uuid}`, {
        method: 'GET',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
        },
    });
    return await response.json();
}

function displayUser(userResult) {
    document.querySelector('.header-username').textContent = userResult.data.data.username;
    document.querySelector('.header-email').textContent = userResult.data.data.email;
    document.querySelector('.data.first-name').textContent = userResult.data.data.firstName;
    document.querySelector('.data.last-name').textContent = userResult.data.data.lastName;
    document.querySelector('.data.email').textContent = userResult.data.data.email;
    document.querySelector('.data.username').textContent = userResult.data.data.username;
    document.querySelector('.data.bio').textContent = userResult.data.data.bio;
}

function setupButtons(uuid, token) {
    document.querySelector('.modify').addEventListener('click', () => {
        redirectTo("/user/edit/" + uuid);
    });

    document.querySelector('.change-pass').addEventListener('click', () => {
        redirectTo("/user/change-password/" + uuid);
    });

    document.querySelector('.delete').addEventListener('click', async() => {
        if (confirm("Are you sure you want to delete your account? This action cannot be undone.")) {
            await deleteUserAccount(uuid, token);
        }
    });
}

async function deleteUserAccount(uuid, token) {
    try {
        const response = await fetch(`/api/user/${uuid}`, {
            method: 'DELETE',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
        });

        if (response.ok) {
            localStorage.removeItem('jwt');
            localStorage.removeItem('user');
            redirectTo("/home");
        } else {
            console.error('Failed to delete user account');
        }
    } catch (error) {
        console.error(error);
    }
}

function redirectTo(path) {
    window.location.href = path;
}
