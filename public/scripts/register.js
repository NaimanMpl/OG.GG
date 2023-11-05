const registerForm = document.getElementById('register-container--form');
const registerBtn = document.getElementById('registerbtn');
const usernameInput = document.getElementById("username");
const emailInput = document.getElementById("email");

const handleRegister = async (e) => {
    e.preventDefault();

    const passwordInput = document.getElementById("password");

    registerBtn.classList.add('loading');

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

const updateEmailDialog = async (e) => {
    const emailDialog = document.querySelector('.register-container--email-dialog');
    if (e.target.value.length === 0) {
        emailDialog.textContent = "";
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

usernameInput.addEventListener('input', updateUsernameDialog);
emailInput.addEventListener('input', updateEmailDialog);
registerForm.addEventListener('submit', handleRegister);