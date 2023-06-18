const errorMessage = document.getElementById("error-message");
const successMessage = document.getElementById("success-message");

function showMessage(element, message) {
    element.textContent = message;
    element.classList.remove("hidden");

    setTimeout(() => {
        element.classList.add("hidden");
    }, 3000);
}

document.querySelector('form').addEventListener('submit', async event => {
    event.preventDefault();

    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const repeatNewPassword = document.getElementById('repeatNewPassword').value;

    if (newPassword !== repeatNewPassword) {
        showMessage(errorMessage, 'New passwords do not match');
        return;
    }

    const uuid = getCurrentPageUuid();
    const token = localStorage.getItem('jwt');
    const passwordUpdate = { currentPassword, newPassword };

    try {
        await changePassword(uuid, token, passwordUpdate);
    } catch (error) {
        showMessage(errorMessage, error);
    }
});

async function changePassword(uuid, token, passwordUpdate) {
    const response = await fetch(`/api/password/change/${uuid}`, {
        method: 'PUT',
        headers: {
            'Authorization': 'Bearer ' + token,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(passwordUpdate),
    });

    const result = await response.json();
    if (response.ok) {
        showMessage(successMessage, 'Password updated successfully');
        setTimeout(() => {
            redirectTo(`/user/${uuid}`);
        }, 3000);
    } else {
        showMessage(errorMessage, result.data.error);
    }
}

function getCurrentPageUuid() {
    const urlParts = window.location.href.split("/");
    return urlParts[urlParts.length - 1];
}

function redirectTo(path) {
    window.location.href = path;
}

document.querySelector('.back-to-profile').addEventListener('click', function(e) {
    e.preventDefault();
    const uuid = getCurrentPageUuid();
    window.location.href = '/user/' + uuid;
});