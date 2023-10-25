const loginForm = document.getElementById('login-container--form');
const loginButton = document.getElementById('loginbtn');

const handleLogin = async (e) => {
    e.preventDefault();

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');

    const response = await fetch(
        'http://localhost:8888/user/login',
        {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: emailInput.value,
                password: passwordInput.value
            })
        }
    );
    
    if (response.status === 401) {
        const responseData = await response.json();
        document.querySelector('.error').textContent = responseData.error;
        return;
    }

    window.location.href = response.url;
}

loginForm.addEventListener('submit', handleLogin);