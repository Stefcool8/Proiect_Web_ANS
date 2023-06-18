const errorMessage = document.querySelector(".error-message");
const successMessage = document.querySelector(".success-message");

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

function getInputValue(selector) {
    return document.querySelector(selector).value;
}

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
        if (userResult && userResult.data) {
            populateFormFields(userResult);
        } else {
            showMessage(errorMessage, 'Error retrieving user data');
        }
    } catch (error) {
        showMessage(errorMessage, error);
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

function populateFormFields(userResult) {
    document.querySelector('.header-username').textContent = userResult.data.data.username;
    document.querySelector('.header-email').textContent = userResult.data.data.email;
    document.querySelector('.data.first-name').value = userResult.data.data.firstName;
    document.querySelector('.data.last-name').value = userResult.data.data.lastName;
    document.querySelector('.data.email').value = userResult.data.data.email;
    document.querySelector('.data.username').value = userResult.data.data.username;
    document.querySelector('.data.bio').value = userResult.data.data.bio;
}

document.querySelector('form').addEventListener('submit', async(event) => {
    event.preventDefault();

    const userUpdate = {
        firstName: getInputValue('.data.first-name'),
        lastName: getInputValue('.data.last-name'),
        email: getInputValue('.data.email'),
        username: getInputValue('.data.username'),
        bio: getInputValue('.data.bio'),
    };

    const token = localStorage.getItem('jwt');
    const uuid = getCurrentPageUuid();

    try {
        await updateUser(uuid, token, userUpdate);
    } catch (error) {
        showMessage(errorMessage, error);
    }
});

async function updateUser(uuid, token, userUpdate) {
    try {
        const response = await fetch(`/api/user/${uuid}`, {
            method: 'PUT',
            headers: {
                'Authorization': 'Bearer ' + token,
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(userUpdate),
        });
        const result = await response.json();
        if (response.ok) {
            showMessage(successMessage, 'User profile updated successfully');

            let user = JSON.parse(localStorage.getItem('user'));
            if (!user['isAdmin'] || user['uuid'] === uuid) {
                localStorage.setItem('jwt', result.data.token);
                localStorage.setItem('user', JSON.stringify(result.data.user));
            }
            setTimeout(() => {
                redirectTo(`/user/${uuid}`);
            }, 3000);
        } else {
            showMessage(errorMessage, result.data.error);
        }
    } catch (error) {
        showMessage(errorMessage, error);
    }
}

function redirectTo(path) {
    window.location.href = path;
}
