function validateEmail(email) {
    const re = /\S+@\S+\.\S+/;
    return re.test(email);
}

const contactForm = document.querySelector('#contact-form');
const errorMessage = document.querySelector('.error-message');
const successMessage = document.querySelector('.success-message');

contactForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    const nickname = document.getElementById('nickname').value;

    errorMessage.classList.add('hidden');
    successMessage.classList.add('hidden');

    if (validateEmail(email) === false) {
        errorMessage.textContent = 'Invalid email address';
        errorMessage.classList.remove('hidden');
        return;
    }

    if (name === '') {
        errorMessage.textContent = 'Name is required';
        errorMessage.classList.remove('hidden');
        return;
    }

    if (subject === '') {
        errorMessage.textContent = 'Subject is required';
        errorMessage.classList.remove('hidden');
        return;
    }

    if (message === '') {
        errorMessage.textContent = 'Message is required';
        errorMessage.classList.remove('hidden');
        return;
    }

    const data = {
        name: name, email: email, subject: subject, message: message, nickname: nickname
    };

    try {
        const response = await fetch('/api/contact', {
            method: 'POST', headers: {
                'Content-Type': 'application/json'
            }, body: JSON.stringify(data)
        });

        let result;
        try {
            result = await response.json();
        } catch (error) {
            errorMessage.textContent = 'Failed to parse JSON response';
            errorMessage.classList.remove('hidden');

            setTimeout(() => {
                errorMessage.classList.add('hidden');
            }, 5000);
            return;
        }

        if (response.ok) {
            successMessage.textContent = result.data.message;
            successMessage.classList.remove('hidden');

            setTimeout(() => {
                window.location.href = '/';
            }, 3000);
        } else {
            errorMessage.textContent = result.data.error;
            errorMessage.classList.remove('hidden');

            setTimeout(() => {
                errorMessage.classList.add('hidden');
            }, 5000);
        }
    } catch (error) {
        errorMessage.textContent = 'An error occurred while sending the email.';
        errorMessage.classList.remove('hidden');

        setTimeout(() => {
            errorMessage.classList.add('hidden');
        }, 5000);
    }
});
