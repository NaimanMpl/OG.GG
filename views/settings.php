<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="/styles/darkheader.css">
    <link rel="stylesheet" href="/styles/search.css">
    <link rel="stylesheet" href="/styles/settings.css">
    <link rel="shortcut icon" href="/img/og-gg.ico" type="image/x-icon">
</head>
<body>
    <?php include "search.php" ?>
    <div id="blur-wrapper">
        <div id="settings-wrapper">
            <?php include "header.php" ?>
            <main>
                <div class="container">
                    <h1>Paramètres</h1>
                    <div class="user">
                        <img class="user-profile-icon" src="data:image/png;base64,<?= $_SESSION["profilepicture"] ?>" alt="Profile Picture">
                        <div class="user--infos">
                            <span class="user-infos--username"><?= $_SESSION['username'] ?></span>
                            <span class="user-infos--email"><?= $_SESSION['email'] ?></span>
                        </div>
                    </div>
                    <section class="security">
                        <h2>Sécurité</h2>
                        <form class="security-form">
                            <label for="current-password">Mot de passe actuel</label>
                            <input type="password" name="current-password" id="current-password">
                            <label for="password">Nouveau mot de passe</label>
                            <input type="password" name="password" id="password">
                            <label for="confirm-password">Mot de passe actuel</label>
                            <input type="password" name="confirm-password" id="confirm-password">
                            <button id="save-btn" type="submit">Sauvegarder</button>
                        </form>
                    </section>
                </div>
                <section class="profile-pictures">
                    <h2>Photos de profil disponibles</h2>
                    <div class="pp-container">
                        <div class="spinner-container spinner-visible">
                            <div class="spinner"></div>
                        </div>
                    </div>
                </section>
            </main>
            <?php include "footer.php" ?>
        </div>
    </div>
    <script src="/scripts/search.js"></script>
    <script src="/scripts/nav.js"></script>
    <script src="/scripts/settings.js"></script>
</body>
</html>