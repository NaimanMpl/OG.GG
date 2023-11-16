<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OG.GG | Se connecter</title>
    <link rel="stylesheet" href="/styles/login.css">
    <style>
        main {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        a {
            background: var(--default-dark);
            color: var(--white);
            padding: .8rem 2rem;
            border-radius: .3rem;
        }
    </style>
</head>
<body>
    <main class="login-container">
        <img src="/img/check-symbol.svg" alt="Valid">
        <h1>Ça y'est, <?= $username ?></h1>
        <p style="font-weight: 500;">Votre compte est désormais actif.</a></p>
        <a href="/login">Se connecter</a>
    </main>
    <img src="/img/points.svg" alt="points" id="points">
    <img src="/img/points.svg" alt="points" id="points2">
    <svg id="plus" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <line x1="50" y1="0" x2="50" y2="100" stroke="#222222" stroke-width="10" />
        <line x1="0" y1="50" x2="100" y2="50" stroke="#222222" stroke-width="10" />
    </svg>
    <svg id="square" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <rect x="0" y="0" width="100" height="100" fill="#222222" />
    </svg>
    <svg id="circle" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <circle cx="50" cy="50" r="50" fill="#222222" />
    </svg>
    <svg id="triangle" width="100" height="100" xmlns="http://www.w3.org/2000/svg">
        <polygon points="0,0 0,100 100,0" fill="#222222" />
    </svg>
    <span id="oggg-title">OG.GG</span>
</body>
</html>