<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG.GG | S'inscrire</title>
    <link rel="stylesheet" href="/styles/register.css">
</head>
<body>
    <main class="register-container">
        <span id="oggg-title">OG.GG</span>
        <div class="register-container--bg">

        </div>
        <section class="register-container--section">
            <div class="register-container--wrapper">
                <h2>Créer un compte</h2>
                <p class="error"></p>
                <p>Déjà un compte ? <a href="/login">Se connecter</a></p>
                <form id="register-container--form">
                    <input type="text" name="email" id="email" placeholder="Adresse email">
                    <p style="font-size: 0.7rem; margin-top: .5rem" class="register-container--email-dialog"></p>
                    <input type="text" name="username" id="username" placeholder="Nom d'utilisateur">
                    <p style="font-size: 0.7rem; margin-top: .5rem" class="register-container--username-dialog"></p>
                    <input type="password" name="password" id="password" placeholder="Mot de passe">
                    <button id="registerbtn" type="submit">
                        <span class="registerbtn--text">S'inscrire</span>
                    </button>
                </form>
            </div>
        </section>
    </main>
    <script src="/scripts/register.js"></script>
</body>
</html>