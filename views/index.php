<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Profil joueur world!</h1>
    <img src="https://ddragon.leagueoflegends.com/cdn/13.18.1/img/profileicon/<?= $summonerData['profileIconId'] ?>.png" alt="">
    <p>Nom : <?= $summonerData['name'] ?></p>
    <p>Niveau : <?= $summonerData['summonerLevel'] ?></p>
</body>
</html>