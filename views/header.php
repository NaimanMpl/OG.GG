<header>
    <a href="/" class="header--title">OG.GG</a>
    <?php
    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
        echo '<div class="nav-logged--info" style="display: flex; gap: .7rem; align-items: center;">';
        echo '<img style="max-width: 30px; border-radius: 50%;" class="nav-profile-icon" src=data:image/png;base64,'.$_SESSION['profilepicture'].' alt="Paramètres">';
        echo "<span style='color: var(--white); font-weight: 500;'>".$_SESSION["username"]."</span>";
        echo "</div>";
    }
    ?>
    <nav>
        <div class="nav--cta">
            <img id="nav--search-btn" src="/img/search.svg" alt="Rechercher" class="nav--cta-search nav-cta--icon">
            <img src="/img/hamburger-menu.svg" alt="Menu" class="nav--cta-mobile-burger nav-cta--icon">
        </div>
        <div class="nav--mobile-link-container hidden">
            <div class="nav-profile">
                <div class="nav-profile--infos">
                    <?php
                    if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                       echo '
                       <img class="nav-profile-icon" style="border-radius: 50%;" src="data:image/png;base64,'.$_SESSION['profilepicture'].'" alt="Account Icon">
                       <div class="nav-profile-credentials">
                           <p class="nav-profile--username">'.$_SESSION['username'].'</p>
                           <p class="nav-profile--email">'.$_SESSION['email'].'</p>
                       </div>
                       '; 
                    } else {
                        echo '
                       <img class="nav-profile-icon" src="/img/user.svg" alt="Account Icon">
                       <div class="nav-profile-credentials">
                           <p class="nav-profile--username">Non connecté</p>
                           <a href="/login" class="nav-profile--email">Se connecter</a>
                       </div>
                       '; 
                    }
                    ?>
                </div>
                <?php
                if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                    echo '<a href="/settings"><img class="nav-profile--settings" src="/img/settings.svg" alt="Paramètres"></a>';
                }
                ?>
            </div>
            <ul>
                <li><a href="/leaderboard">Classement</a></li>
                <li><a href="/chat">Chat</a></li>
                <li><a href="/followers">Compte suivis</a></li>
                <li><a href="/about">À propos</a></li>
            </ul>
            <?php
            if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
                echo '<a class="nav-profile-cta--logout" href="/logout">Déconnexion</a>';
            }
            ?>
        </div>
    </nav>
    
</header>