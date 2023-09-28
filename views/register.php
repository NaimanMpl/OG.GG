<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG.GG | S'inscrire</title>
    <link rel="stylesheet" href="/styles/register.css">
</head>
<body>
    <main>
        <section class="register-form-container">
            <p class="error"></p>
            <h2>Inscription</h2>
            <form id="register-form">
                <label for="username">Nom d'utilisateur</label>
                <input type="text" name="username" id="username">
                <label for="username">Email</label>
                <input type="text" name="username" id="email">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
                <button type="submit" id="register-cta">
                    <span class="cta-button-text">S'inscrire</span>
                </button>
            </form>
        </section>
    </main>
    <script src="/scripts/register.js"></script>
</body>
</html>