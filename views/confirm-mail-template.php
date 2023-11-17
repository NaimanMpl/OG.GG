<html>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    * {
        padding: 0;
        margin: 0;
        font-family: 'Inter';
    }

    h1, p {
        text-align: center;
    }

    p {
        font-weight: 500;
    }

    body {
        height: 100vh;
        background: #F4F4F4;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        background: #FFFFFF;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 3rem;
        width: 500px;
        position: relative;
    }

    .container::before {
        content: "";
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: 100%;
        height: 100%;
        background: #C2C2C2;
        z-index: -1;
    }

    a {
        text-decoration: none;
        background: #222222;
        color: #F4F4F4;
        font-weight: 500;
        padding: 1rem 2rem;
        border-radius: .3rem;
    }

    img {
        max-width: 200px;
    }
</style>
<body>
    <div class="container">
        <h1>Hello, World!</h1>
        <p>Merci d'avoir créer un compte chez nous. Vous pouvez l'activer en cliquant sur le lien ci-dessous.</p>
        <a href="<?= $_ENV["MAIL_REDIRECT"].$token ?>">Activer mon compte</a>
        <p>Si vous n'êtes pas à l'origine de cette requête, pas de panique ! Vous pouvez simplement l'ignorer.</p>
    </div>
</body>
</html>