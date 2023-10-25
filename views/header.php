<header>
    <a href="/" class="header--title">OG.GG</a>
    <nav>
        <ul class="nav--desktop-link-container">
            <li><a href="#">Classements</a></li>
            <li><a href="#">Chat</a></li>
        </ul>
        <div class="nav--cta">
            <div class="nav--search-container">
                <img src="/img/search.svg" alt="Rechercher">
                <input type="search" name="search" placeholder="Rechercher un joueur" id="nav--cta--search">
            </div>
            <?php
            if (isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
                echo '
                    <a class="nav--cta-desktop-login loggedin" href="/account">
                        <img src="/img/account-icon.svg" alt="Compte">
                        '.$_SESSION["username"].'
                    </a>
                ';
            } else {
                echo '
                    <a class="nav--cta-desktop-login not-loggedin" href="/login">
                        <img src="/img/account-logo.svg" alt="Compte">
                        Connexion
                    </a>
                ';
            }
            ?>
            
            <img src="/img/hamburger-menu.png" alt="Menu" class="nav--cta-mobile-burger">
        </div>
        <div class="nav--mobile-link-container hidden">
            <img src="/img/close.svg" alt="Fermer" id="closenav-btn">
            <ul>
                <li><a href="#">Connexion</a></li>
                <li><a href="#">Classements</a></li>
                <li><a href="#">Chat</a></li>
            </ul>
        </div>
    </nav>
</header>