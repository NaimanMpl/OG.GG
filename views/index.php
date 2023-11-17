<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="/styles/style.css">
    <link rel="stylesheet" href="/styles/search.css">
</head>
<body>
    <?php include "search.php" ?>
    <div id="blur-wrapper">
        <div id="wrapper">
            <?php include "header.php" ?>
            <section id="hero">
                <div class="hero-title--container">
                    <span>OG.GG</span>
                    <h1>Don't search anymore, it's here</h1>
                </div>
                <div class="hero-background--wrapper">
                    <div class="hero-background--container">
                        <img class="hero-background--text" src="/img/akali-circle-text.png" alt="Akali Circle Text">
                        <img class="hero-background" src="/img/akali-hero.png" alt="Akali">
                    </div>
                </div>
            </section>
            <button class="hero-scroll-down--cta">
                Scroll Down
                <img src="/img/double-arrow-down.svg" alt="Double flèche">
            </button>
        </div>
        <main>
            <section class="follow-section hidden-section">
                <div class="follow-wrapper">
                    <div class="follow-section-profile-icon--container">
                        <svg id="plus" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                            <line x1="50" y1="0" x2="50" y2="100" stroke="#222222" stroke-width="10" />
                            <line x1="0" y1="50" x2="100" y2="50" stroke="#222222" stroke-width="10" />
                        </svg>
                        <svg id="square" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                            <rect x="0" y="0" width="100" height="100" fill="#222222" />
                        </svg>
                        <img class="follow-section--profile-icon" src="/img/kaisa-icon.png" alt="Kaisa Icon">
                    </div>
                    <div class="follow-section--wrapper">
                        <div class="follow-section-title--container">
                            <h2 class="follow-section--title">Hey</h2>
                            <img src="/img/waving-hand.png" alt="Waving Hand">
                        </div>
                        <h2 class="follow-section--title">Moi c'est ZelphiiX</h2>
                        <div class="follow-section--desc">
                        </div>
                        <a class="follow-section--show-profile cta-btn" href="/summoner/ZelphiiX">
                            <span>Voir mon profil</span>
                        </a>
                    </div>
                </div>
                <svg id="circle" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="50" cy="50" r="50" fill="#222222" />
                </svg>
                <svg id="triangle" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                    <polygon points="0,0 0,100 100,0" fill="#222222" />
                </svg>
                <div class="svg-lines--container">
                    <svg class="bar" width="10" height="100" xmlns="http://www.w3.org/2000/svg">
                        <line x1="0" y1="0" y2="50" x2="0" stroke="#222222" stroke-width="10" />
                    </svg>
                    <svg class="bar" width="10" height="100" xmlns="http://www.w3.org/2000/svg">
                        <line x1="0" y1="0" y2="50" x2="0" stroke="#222222" stroke-width="10" />
                    </svg>
                    <svg class="bar" width="10" height="100" xmlns="http://www.w3.org/2000/svg">
                        <line x1="0" y1="0" y2="50" x2="0" stroke="#222222" stroke-width="10" />
                    </svg>
                </div>
            </section>
        </main>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/TextPlugin.min.js"></script>
    <script src="/scripts/script.js"></script>
    <script src="/scripts/search.js"></script>
    <script src="/scripts/nav.js"></script>
</body>
</html>