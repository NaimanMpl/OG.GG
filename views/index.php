<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body>
    <div id="wrapper">
        <?php include "header.php" ?>
        <main>
            <h1>Lorem ipsum dolor sit amet, consectetur adipisicing.</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laudantium veritatis vel voluptatibus aut, eveniet facilis officiis minima. Nobis, officia? Asperiores.</p>
            <div class="hero--cta-container">
                <a href="#" class="hero--cta-chat">
                    <img src="/img/chat-icon.svg" alt="Chat Icon">
                    Chat
                </a>
                <a href="#" class="hero--cta-knowmore">En savoir plus</a>
            </div>
        </main>
    </div>
    <script src="/scripts/script.js"></script>
</body>
</html>