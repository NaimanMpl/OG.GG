<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG.GG | S'inscrire</title>
    <link rel="stylesheet" href="/styles/register.css">
    <link rel="shortcut icon" href="/img/og-gg.ico" type="image/x-icon">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <main class="register-container">
        <h1>Créer un compte.</h1>
        <p class="error"></p>
        <p class="success"></p>
        <form id="register-container--form">
            <input type="text" name="email" id="email" placeholder="Adresse email">
            <p style="font-size: 0.7rem; margin-top: .2rem" class="register-container--email-dialog"></p>
            <input type="text" name="username" id="username" placeholder="Nom d'utilisateur">
            <p style="font-size: 0.7rem; margin-top: .2rem" class="register-container--username-dialog"></p>
            <input type="password" name="password" id="password" placeholder="Mot de passe">
            <p style="font-size: 0.7rem; margin-top: .2rem" class="register-container--password-dialog"></p>
            <input type="password" name="confirm-password" id="confirm-password" placeholder="Confirmer le mot de passe">
            <p style="font-size: 0.7rem; margin-top: .2rem" class="register-container--confirm-password-dialog"></p>
            <div class="g-recaptcha" data-sitekey="6LeRzCopAAAAAB-cLmVbvGzS0-vRVr_HN42fHvs_"></div>
            <button id="registerbtn" type="submit">
                <span class="registerbtn--text">S'inscrire</span>
            </button>
        </form>
        <p class="already-registered">Déjà un compte ? <a href="/login">Se connecter</a></p>
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
    <script src="/scripts/register.js"></script>
</body>
</html>