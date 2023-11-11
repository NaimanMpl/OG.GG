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
        <span id="oggg-title">OG.GG</span>
        <h2>Se connecter</h2>
        <p>Pas encore de compte ? <a href="/register">S'inscrire</a></p>
        <p class="error"></p>
        <form id="login-container--form">
            <input type="email" name="email" id="email" placeholder="Adresse email">
            <input type="password" name="password" id="password" placeholder="Mot de passe">
            <button id="loginbtn" type="submit">Connexion</button>
        </form>
    </main>
    <script src="/scripts/login.js"></script>
</body>
</html>