<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG.GG | Se connecter</title>
    <link rel="stylesheet" href="/styles/login.css">
</head>
<body>
    <main class="login-container">
        <h1>Se connecter.</h1>
        <p class="error"></p>
        <form id="login-container--form">
            <input type="text" name="email" id="email" placeholder="Adresse email">
            <p style="font-size: 0.7rem; margin-top: .2rem" class="login-container--email-dialog"></p>
            <input type="password" name="password" id="password" placeholder="Mot de passe">
            <p style="font-size: 0.7rem; margin-top: .2rem" class="login-container--password-dialog"></p>
            <button id="loginbtn" type="submit">
                <span class="loginbtn--text">Connexion</span>
            </button>
        </form>
        <p class="already-registered">Pas encore de compte ? <a href="/register">S'inscrire</a></p>
    </main>
    <img src="/img/points.svg" alt="points" id="points">
    <img src="/img/points.svg" alt="points" id="points2">
    <svg id="plus" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <line x1="50" y1="0" x2="50" y2="100" stroke="#222222" stroke-width="10" />
        <line x1="0" y1="50" x2="100" y2="50" stroke="#222222" stroke-width="10" />
    </svg>
    <svg id="square" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <rect x="0" y="0" width="100" height="100" fill="#222222" />
    </svg>
    <svg id="circle" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="50" fill="#222222" />
    </svg>
    <svg id="triangle" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <polygon points="0,0 0,100 100,0" fill="#222222" />
    </svg>
    <span id="oggg-title">OG.GG</span>
    <script src="/scripts/login.js"></script>
</body>
</html>