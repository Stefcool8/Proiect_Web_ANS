function validateEmail(email) {
    const re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function validateContactForm(name, email, subject, message) {
    if (!validateEmail(email)) {
        return 'Invalid email address';
    }
    if (!name) {
        return 'Name is required';
    }
    if (!subject) {
        return 'Subject is required';
    }
    if (!message) {
        return 'Message is required';
    }
    return null;
}

async function handleFormSubmit(event) {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    const nickname = document.getElementById('nickname').value;

    const validationError = validateContactForm(name, email, subject, message);
    if (validationError) {
        showError(validationError);
        return;
    }

    const data = {
        name: name,
        email: email,
        subject: subject,
        message: message,
        nickname: nickname
    };

    try {
        const result = await sendContactData(data);
        showSuccess(result.data.message);
        setTimeout(() => {
            window.location.href = '/';
        }, 3000);
    } catch (error) {
        showError(error);
    }
}

async function sendContactData(data) {
    const response = await fetch('/api/contact', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });

    if (response.ok) {
        return await response.json();
    } else {
        const result = await response.json();
        throw new Error(result.data.error);
    }
}

function showError(message) {
    errorMessage.textContent = message;
    errorMessage.classList.remove('hidden');

    setTimeout(() => {
        errorMessage.classList.add('hidden');
    }, 3000);
}

function showSuccess(message) {
    successMessage.textContent = message;
    successMessage.classList.remove('hidden');
}

const contactForm = document.querySelector('#contact-form');
const errorMessage = document.querySelector('.error-message');
const successMessage = document.querySelector('.success-message');

contactForm.addEventListener('submit', handleFormSubmit);
