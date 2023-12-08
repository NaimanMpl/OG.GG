<?php

function getRoom(string $url) {
    $url = explode("/", $url);
    $room = strtolower($url[count($url) - 1]);
    switch ($room) {
        case "iron":
            return "Fer";
        case "bronze":
            return "Bronze";
        case "silver":
            return "Argent";
        case "gold":
                return "Or";
        case "platinium":
            return "Platine";
        case "emerald":
            return "Émeraude";
        case "diamond":
            return "Diamant";
        case "master":
            return "Maître";
        case "grandmaster":
            return "Grand Maître";
        case "challenger":
            return "Challenger";
        default:
            return "General";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= getRoom($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']) ?></title>
    <script src="https://cdn.socket.io/4.7.2/socket.io.min.js"></script>
    <script defer src="/scripts/chat.js"></script>
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="/styles/darkheader.css">
    <link rel="stylesheet" href="/styles/chat.css">
</head>
<body>
    <div id="messages-wrapper">
        <?php include 'header.php' ?>
        <div class="chat-container">
            <section id="chat">
                <h1><?= getRoom($_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']) ?></h1>
                <p class="chat-description">Ça doit surement être à cause de la botlane, on sait.</p>
                <div class="messages-wrapper">
                    <div class="messages-container">
                        <ul class="messages-history">
                        </ul>
                        <form class="message-form">
                            <img class="message-form--pp" src="/img/default-pp.png" alt="Profile Picture">
                            <input type="text" name="message" id="message" placeholder="Envoyer un message...">
                        </form>
                    </div>
                    <article class="chat-online-members">
                        <h3>Membres connectés</h3>
                        <div class="chat-online-members--container">
                        </div>
                    </article>
                </div>
            </section>
        </div>
    </div>
</body>
</html>