const registerForm = document.getElementById('register-form');
const registerBtn = document.getElementById('register-cta');

const handleRegister = async (e) => {
    e.preventDefault();

    const usernameInput = document.getElementById("username");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    registerBtn.classList.add('loading');

    const response = await fetch(
        'http://localhost:8888/register',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: emailInput.value, 
                username: usernameInput.value, 
                password: passwordInput.value
            })
        }
    );
    const data = await response.json();
    
    registerBtn.classList.remove("loading");

    const errorMessage = document.querySelector('.error');

    if ([400, 500].includes(response.status)) {
        errorMessage.textContent = data.error;
        errorMessage.className = 'error';
        return;
    }

    if (data.success) {
        errorMessage.textContent = "Inscription finalisée avec succès";
        errorMessage.className = 'success';
    }


}

registerForm.addEventListener('submit', handleRegister);