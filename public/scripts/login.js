const loginForm = document.getElementById('login-form');
const loginButton = document.getElementById('login-button');

const handleLogin = async (e) => {
    e.preventDefault();

    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    const response = await fetch(
        'http://localhost:8888/login',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                username: usernameInput.value,
                password: passwordInput.value
            })
        }
    );

    

}

loginForm.addEventListener('submit', handleLogin);