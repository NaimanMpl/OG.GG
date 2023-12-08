const registerForm = document.getElementById('register-container--form');
const registerBtn = document.getElementById('registerbtn');
const usernameInput = document.getElementById("username");
const emailInput = document.getElementById("email");
const passwordInput = document.getElementById('password');
const confirmPasswordInput = document.getElementById('confirm-password');

const validateEmail = (email) => {
    return email.match(/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
}

const validatePassword = (password) => {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,}$/;
    return passwordRegex.test(password);
}

const handleRegister = async (e) => {
    e.preventDefault();

    if (confirmPasswordInput.value !== passwordInput.value || !validatePassword(passwordInput.value)) return;
    if (!validateEmail(emailInput.value)) return;

    registerBtn.classList.add('loading');

    const captchaResponse = document.getElementById('g-recaptcha-response').value;

    if (captchaResponse.length === 0) {
        return;
    }

    const response = await fetch(
        '/user/register',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: emailInput.value, 
                username: usernameInput.value, 
                password: passwordInput.value,
                confirmPassword: confirmPasswordInput.value,
                captcha: captchaResponse
            })
        }
    );
    
    const data = await response.json();
    const errorMessage = document.querySelector('.error');
    const successMessage = document.querySelector('.success');

    if (!response.ok) {
        errorMessage.textContent = data.error;
        errorMessage.className = 'error';
        registerBtn.classList.remove("loading");
        return;
    }

    registerBtn.classList.remove("loading");

    errorMessage.textContent = '';
    successMessage.textContent = data.message;
}

const updateEmailDialog = async (e) => {
    const emailDialog = document.querySelector('.register-container--email-dialog');
    
    if (e.target.value.length === 0) {
        emailDialog.textContent = "";
        return;
    }

    if (!validateEmail(e.target.value)) {
        emailDialog.textContent = "Veuillez renseigner une adresse mail valide !";
        return;
    }

    const response = await fetch(
        `/users/by-email?email=${encodeURIComponent(e.target.value)}`,
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            },
        }
    );

    const responseData = await response.json();
    
    if (responseData.length === 0) {
        emailDialog.classList.remove('dialog-error');
        emailDialog.classList.add('dialog-success');
        emailDialog.textContent = 'Super ! Cette adresse email est diponible !';
        registerBtn.disabled = false;
    } else {
        emailDialog.classList.remove('dialog-success');
        emailDialog.classList.add('dialog-error');
        emailDialog.textContent = 'Cette adresse email est déjà utilisée.';
        registerBtn.disabled = true;
    }
}

const updateUsernameDialog = async (e) => {
    const usernameDialog = document.querySelector('.register-container--username-dialog');
    if (e.target.value.length === 0) {
        usernameDialog.textContent = "";
        return;
    }
    const response = await fetch(
        `/users/by-name/${encodeURIComponent(e.target.value)}`,
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            },
        }
    );
    const responseData = await response.json();
    if (responseData.length === 0) {
        usernameDialog.classList.remove('dialog-error');
        usernameDialog.classList.add("dialog-success");
        usernameDialog.textContent = "Super ! Ce nom d'utilisateur est diponible !";
        registerBtn.disabled = false;
    } else {
        usernameDialog.classList.remove('dialog-success');
        usernameDialog.classList.add("dialog-error");
        usernameDialog.textContent = "Ce nom d'utilisateur est déjà utilisé.";
        registerBtn.disabled = true;
    }
}

const updateConfirmPasswordDialog = (input) => {
    const confirmPasswordDialog = document.querySelector('.register-container--confirm-password-dialog');
    if (input.value.length === 0) {
        confirmPasswordDialog.textContent = "";
        return;
    }

    if (confirmPasswordInput.value !== passwordInput.value) {
        confirmPasswordDialog.textContent = "Les 2 mots de passes doivent être identiques !";
        return;
    }

    confirmPasswordDialog.textContent = "Super ! Les mots de passes sont identiques !";
}

const updatePasswordDialog = (e) => {

    const passwordDialog = document.querySelector('.register-container--password-dialog');

    if (e.target.value.length === 0) {
        passwordDialog.textContent = "";
        return;
    }

    if (!validatePassword(e.target.value)) {
        passwordDialog.textContent = "Le mot de passe doit contenir au moins 8 caractères, une majuscule et un caractère spécial !";
        return;
    }

    passwordDialog.textContent = "C'est carré";
}

usernameInput.addEventListener('input', updateUsernameDialog);
emailInput.addEventListener('input', updateEmailDialog);
passwordInput.addEventListener('input', updatePasswordDialog);
passwordInput.addEventListener('input', () => {
    updateConfirmPasswordDialog(passwordInput);
});
confirmPasswordInput.addEventListener('input', () => {
    updateConfirmPasswordDialog(confirmPasswordInput);
});

registerForm.addEventListener('submit', handleRegister);