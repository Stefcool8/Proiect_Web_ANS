function validateEmail(email) {
    const re = /\S+@\S+\.\S+/;
    return re.test(email);
}

const contactForm = document.querySelector('#contact-form');

contactForm.addEventListener('submit', async (event) => {
    event.preventDefault();

    console.log('contactForm submit');
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    const nickname = document.getElementById('nickname').value;

    if (validateEmail(email) === false) {
        alert('Invalid email address')
        console.error('Invalid email address');
        return;
    }
    alert('Email sent successfully')

    const data = {
        name: name,
        email: email,
        subject: subject,
        message: message,
        nickname: nickname
    };

    try {
        const response = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        let result;
        try {
            result = await response.json();
        } catch (error) {
            console.error('Failed to parse JSON response', error);
            return;
        }

        if (response.ok) {
            // The contact was successful
            // Redirect to the home page
            window.location.href = '/';
        } else {
            // Handle the error
            console.error(result.message);
        }
    } catch (error) {
        // Handle any errors
        console.error(error);
    }
});
