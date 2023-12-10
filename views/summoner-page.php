<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/img/og-gg.ico" type="image/x-icon">
    <title>
        <?php
            $link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
            $arrayLink = explode("/", $link);
            echo $arrayLink[count($arrayLink) -1];
            
        ?>
    </title>
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="/styles/darkheader.css">
    <link rel="stylesheet" href="/styles/search.css">
    <link rel="stylesheet" href="/styles/summoner-page.css">
</head>
<body>
    <?php include "search.php" ?>
    <div id="blur-wrapper">
        <?php include "header.php" ?>
        <main>
            <div class="profileCard--cardFollow-container">
                <div class="profilCard--playerInfos-container">

                </div>
            </div>
        </main>
        <div class="scroll-titles-wrapper">
            <div class="scroll-titles--container">
                <div class="scrolling-titles--soloQ-container">
    
                </div>
                <div class="scrolling-titles--flexQ-container">
    
                </div>
            </div>
        </div>
        <div class="grid-container">
            <div class="tqt hidden">
                <div class="links-container">
                    <span class="overview-cta active-link">Aperçu</span>
                    <span class="posts-cta">Commentaires</span>
                </div>
            </div>
            <?php
            if (isset($_SESSION['userId']) && !empty($_SESSION['userId'])) {
                echo "
                    <div class='form-container' style='display: none;'>
                        <img src='data:image/png;base64,".$_SESSION['profilepicture']."' alt='Profile picture'>
                        <form class='post-form'>
                            <input class='post-form--input' type='text' name='message' id='message' placeholder='Ajouter un commentaire...'>
                            <div class='button-container'>
                                <button type='submit'>Envoyer</button>
                            </div>
                        </form>
                    </div>
                ";
            }
            ?>
            
            <div class="player-matches-ranked--container">
                <div class="ranked-cards--container">
    
                </div>
                <div class="matches-historic-cards--container">

                    <div class="spinner--historic-container">
                        
                    </div>
                </div>
                <div class="posts-container hidden">
                    <div class="spinner--posts-container">
                        
                    </div>
                </div>
            </div>
            <div class="spinner-container spinner-visible">
                <div class="spinner"></div>
            </div>
        </div>
    <?php include "footer.php" ?>
    <script src="/scripts/nav.js"></script>
    <script src="/scripts/search.js"></script>
    <script src="/scripts/summoner-page.js"></script>
</body>
</html>