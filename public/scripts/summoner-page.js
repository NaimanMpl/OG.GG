const profilCardContainer = document.querySelector('.profileCard--cardFollow-container');
const scrollTitles = document.querySelector('.scroll-titles-wrapper');
const rankCardContainer = document.querySelector('.ranked-cards--container');
const matchesHistoricCardsContainer = document.querySelector('.matches-historic-cards--container');
const playerMatchesRankedContainer = document.querySelector('.player-matches-ranked--container');
const postsBtn = document.querySelector('.posts-cta');
const overviewBtn = document.querySelector('.overview-cta');
const postsContainer = document.querySelector('.posts-container');
const postForm = document.querySelector('.post-form');
const postInput = document.querySelector('.post-form--input');

let currentSummoner = null;


let spellData = null;

const handleFollow = async (summonerName, summonerTag, button) => {

    button.textContent = 'Chargement...';
    const response = await fetch (
        `/user/follow/${summonerName}/${summonerTag}`,
        {
            method: 'GET',
        }
    )
    button.textContent = 'Suivi';
}

const follows = (user, summonerName) => {
    for (let i = 0; i < user.followers.length; i++) {
        if (user.followers[i].summonerName === summonerName) return true;
    }
    return false;
}

const buildProfilInfosContainer = async (summonerName, summonerTag, profilPicture, server, accountLevel) => {
    
    const summonerProfilePictureWrapper = document.createElement('div');
    summonerProfilePictureWrapper.className = 'profileCard-cardIcon--wrapper';
    const summonerProfilPicture = document.createElement('img');
    summonerProfilPicture.className = 'profilCard--icon';
    summonerProfilPicture.src = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/profileicon/${profilPicture}.png`;
    summonerProfilPicture.alt = 'Photo de profil du joueur';

    const forms = [
        { url : "/img/plus.svg", className: "plus" }, 
        { url : "/img/triangle.svg", className: "triangle" },
        { url : "/img/points.svg", className: "points" },
        { url : "/img/points.svg", className: "points2" }
    ];
    forms.forEach((form) => {
        const formImage = document.createElement('img');
        formImage.src = form.url;
        formImage.alt = form.className;
        formImage.classList.add('form-img');
        formImage.classList.add(form.className);

        if (form.className !== "triangle") {
            summonerProfilePictureWrapper.appendChild(formImage);
        } else {
            document.querySelector('body').appendChild(formImage);
        }
    });

    summonerProfilePictureWrapper.appendChild(summonerProfilPicture);

    const pseudo = document.createElement('h1');
    pseudo.className = 'profilCard--pseudo';
    pseudo.textContent = summonerName;

    const serverName = document.createElement('span');
    serverName.className = 'profilCard--server';
    serverName.textContent = server.toUpperCase();

    const levelContainer = document.createElement('div');
    levelContainer.style.display = "flex";
    levelContainer.style.gap = ".25rem"

    const levelText = document.createElement('span');
    levelText.className = 'profilCard--level';
    levelText.textContent = `Niveau`;

    const level = document.createElement('span');
    level.className = 'profilCard--level';
    level.style.fontWeight = 'bold';
    level.textContent = `${accountLevel}`;

    const userData = await fetchCurrentUser();

    const followBtnDesktop = document.createElement('button');
    followBtnDesktop.classList = 'profilCard--followBtn-desktop';
    if (userData !== null) {
        console.log(userData);
        if (follows(userData, summonerName)) {
            followBtnDesktop.textContent = 'Suivi';
            followBtnDesktop.disabled = true;
        } else {
            followBtnDesktop.textContent = 'Suivre';
        }
    } else {
        followBtnDesktop.textContent = 'Suivre';
    }
    
    followBtnDesktop.addEventListener('click', () => { handleFollow(summonerName, summonerTag, followBtnDesktop) });

    levelContainer.appendChild(levelText);
    levelContainer.appendChild(level);

    const levelFollowBtnContainer = document.createElement('div');
    levelFollowBtnContainer.className = 'profilCard--level-follow-btn-container-desktop';
    levelFollowBtnContainer.appendChild(levelContainer);
    levelFollowBtnContainer.appendChild(followBtnDesktop);


    const pseudoAndServer = document.createElement('div');
    pseudoAndServer.className = 'profilCard--pseudoServer-container';
    pseudoAndServer.appendChild(pseudo);
    pseudoAndServer.appendChild(serverName);

    const playerInfos = document.querySelector('.profilCard--playerInfos-container');
    playerInfos.appendChild(pseudoAndServer);
    playerInfos.appendChild(levelContainer);
    playerInfos.appendChild(levelFollowBtnContainer);

    const container = document.createElement('div');
    container.className = 'profilCard--playerDataCard-container';

    container.appendChild(summonerProfilePictureWrapper);
    container.appendChild(playerInfos);

    return new Promise(resolve => resolve(container));
}

const buildProfilCardContainer = async (summonerName, summonerTag, profilPicture, server, accountLevel) => {
    const followBtnNav = document.createElement('button');
    followBtnNav.className = 'profilCard--followBtn-nav';
    followBtnNav.textContent = 'Suivre';

    const profilCardInfos = await buildProfilInfosContainer(summonerName, summonerTag, profilPicture, server, accountLevel);
    const profilCard = document.createElement('div');
    profilCard.className = 'profilCard--cardFollow-container';
    profilCard.appendChild(profilCardInfos);
    profilCard.appendChild(followBtnNav);

    return new Promise(resolve => resolve(profilCard));
}

const buildScrollingTitles = (rankSoloQ, rankFlexQ) => {

    let rankSoloQText = 'Unranked';
    let rankFlexQText = 'Unranked';

    if (rankSoloQ != null) {
        rankSoloQText= `${rankSoloQ.tier} ${rankSoloQ.rank}`;
    }

    if (rankFlexQ != null) {
        rankFlexQText = `${rankFlexQ.tier} ${rankFlexQ.rank}`;
    }

    const scrollDiv = document.createElement('div');
    scrollDiv.className = 'scrolling-titles--scrollDiv';

    const scrollingTitleSoloQ = document.querySelector('.scrolling-titles--soloQ-container');
    const scrollingTitleFlexQ = document.querySelector('.scrolling-titles--flexQ-container');
    
    for (let i = 0; i < 30; i++){
        const pointSoloQ = document.createElement('span');
        pointSoloQ.textContent = '•';

        const scrollingTextSoloQ = document.createElement('span');
        scrollingTextSoloQ.textContent = rankSoloQText.toUpperCase();
        scrollingTitleSoloQ.appendChild(scrollingTextSoloQ);
        scrollingTitleSoloQ.appendChild(pointSoloQ);

        const pointFlexQ = document.createElement('span');
        pointFlexQ.textContent = '•';

        const scrollingTextFlexQ = document.createElement('span');
        scrollingTextFlexQ.textContent = rankFlexQText.toUpperCase();
        scrollingTitleFlexQ.appendChild(scrollingTextFlexQ);
        scrollingTitleFlexQ.appendChild(pointFlexQ);
    }

    const container = document.querySelector('.scroll-titles--container');
    container.appendChild(scrollingTitleSoloQ);
    container.appendChild(scrollingTitleFlexQ);

    return container;
}

const buildRankedCards = (rankSoloQ, rankFlexQ) => {

    const rankedCardData = {
        solo: {
            queueName: 'Classée Solo',
            wins: 0,
            losses: 0,
            rankName: 'Unranked',
            rankDiv: 1,
            lp: 0
        }, 
        flex: {
            queueName: 'Classée Flexible',
            wins: 0,
            losses: 0,
            rankName: 'Unranked',
            rankDiv: 1,
            lp: 0
        }
    }

    if (rankSoloQ != null){
        rankedCardData.solo.wins = rankSoloQ.wins;
        rankedCardData.solo.losses = rankSoloQ.losses;
        rankedCardData.solo.rankName = rankSoloQ.tier;
        rankedCardData.solo.rankDiv = rankSoloQ.rank;
        rankedCardData.solo.lp = rankSoloQ.leaguePoints;
    }

    if (rankFlexQ != null){
        rankedCardData.flex.wins = rankFlexQ.wins;
        rankedCardData.flex.losses = rankFlexQ.losses;
        rankedCardData.flex.rankName = rankFlexQ.tier;
        rankedCardData.flex.rankDiv = rankFlexQ.rank;
        rankedCardData.flex.lp = rankFlexQ.leaguePoints;
    }

    if (rankedCardData.solo.rankName == 'Challenger' || rankedCardData.solo.rankName == 'Unranked'){
        rankedCardData.solo.rankDiv = '';
    }

    if (rankedCardData.flex.rankName == 'Challenger' || rankedCardData.flex.rankName == 'Unranked'){
        rankedCardData.flex.rankDiv = '';
    }
    return [ buildRankedCard(rankedCardData.solo), buildRankedCard(rankedCardData.flex) ]
    
}

const getSummonerSpellImage = (id) => {
    if (spellData === null) return null;
    for (const [key, value] of Object.entries(spellData.data)) {
        if (parseInt(value.key) === id) {
            return value.id;
        }
    }
}

const winrateValue = (wins, losses) => {
    let winrate = 0;
    if (wins == 0 && losses == 0){
        return winrate;
    } 
    winrate = ((wins / (wins + losses)) * 100).toFixed(2);
    return winrate;
}

const buildRankedCard = (queue) => {
    const rankedCard = document.createElement('div');
    rankedCard.className = 'ranked-card--container';

    const titleCard = document.createElement('p');
    titleCard.className = 'ranked-card--title';
    titleCard.textContent = queue.queueName;

    const queueDataContainer = document.createElement('div');
    queueDataContainer.className = 'ranked-card--data-container';
    
    const rankIcon = document.createElement('img');
    rankIcon.src = `/img/rank-icon/${queue.rankName.toLowerCase()}.svg`;
    rankIcon.alt = 'Rang en Classée Solo';
    rankIcon.className = 'ranked-card--rank-icon';

    const rankAndLPContainer = document.createElement('div');
    rankAndLPContainer.className = 'ranked-card--rank-lp-container';

    const rankQueueName = document.createElement('span');
    rankQueueName.className = 'ranked-card--rank-lp';
    rankQueueName.textContent = `${queue.rankName} ${queue.rankDiv}`;

    const rankQueueLP = document.createElement('span');
    rankQueueLP.className = 'ranked-card--rank-lp';
    rankQueueLP.textContent = `${queue.lp} LP`;

    rankAndLPContainer.appendChild(rankQueueName);
    rankAndLPContainer.appendChild(rankQueueLP);

    const rankBarUnfilled = document.createElement('div');
    rankBarUnfilled.className = 'ranked-card--bar-unfilled';

    const rankBarFilled = document.createElement('div');
    rankBarFilled.className = 'ranked-card--bar-filled';

    if (queue.queueName != 'Challenger' && queue.queueName != 'Grand Maitre' && queue.queueName != 'Maitre'){
        if (queue.lp > 99){
            rankBarFilled.style.width = '100%';
        } else {
            rankBarFilled.style.width = `${queue.lp}%`;
        }
    }
    rankBarFilled.style.backgroundColor = '#222222';
    rankBarFilled.style.height = '.125rem';
    rankBarFilled.style.borderRadius = '.8rem';

    rankBarUnfilled.appendChild(rankBarFilled);

    const winrateContainer = document.createElement('div');
    winrateContainer.className = 'ranked-card--winrate-container';

    const winrate = document.createElement('span');
    winrate.className = 'ranked-card--winrate';
    winrate.textContent = `${winrateValue(queue.wins, queue.losses)}% Winrate`;

    const winsLosses = document.createElement('span');
    winsLosses.className = 'ranked-card--winrate';
    winsLosses.textContent = `${queue.wins}W / ${queue.losses}L`;

    winrateContainer.appendChild(winrate);
    winrateContainer.appendChild(winsLosses);

    queueDataContainer.appendChild(rankIcon);

    const dataContainer = document.createElement('div');
    dataContainer.className = 'ranked-card--data';

    dataContainer.appendChild(rankAndLPContainer);
    dataContainer.appendChild(rankBarUnfilled);
    dataContainer.appendChild(winrateContainer);

    queueDataContainer.appendChild(dataContainer);

    rankedCard.appendChild(titleCard);
    rankedCard.appendChild(queueDataContainer);

    return rankedCard;
}

function timeConversion(timeInSeconds) {
    const now = new Date();
    const futureTime = new Date(now.getTime() + timeInSeconds * 1000);

    const difference = futureTime - now;

    const months = Math.floor(difference / (1000 * 60 * 60 * 24 * 30.44));
    const days = Math.floor((difference % (1000 * 60 * 60 * 24 * 30.44)) / (1000 * 60 * 60 * 24));
    const hours = Math.floor((difference % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((difference % (1000 * 60 * 60)) / (1000 * 60));

    if (months >= 1) {
        return `Il y a ${months} mois`;
    } else if (days >= 1) {
        return `Il y a ${days} jours`;
    } else if (hours >= 1) {
        return `Il y a ${hours} heures`;
    } else {
        return `Il y a ${minutes} minutes`;
    }
}



const buildMatchesHistoric = () => {
    const matchesHistoricTitle = document.createElement('span');
    matchesHistoricTitle.className = 'matches-historic--title';
    matchesHistoricTitle.textContent = 'Historique des dernières parties';

    return matchesHistoricTitle;
}

const roleConversion = (role) => {
    return role.charAt(0).toUpperCase() + role.slice(1).toLowerCase();
}

const calculateCsMin = (minutesTotal, nbCsMax) => {
    const calcul = (nbCsMax/minutesTotal).toFixed(1);
    const res = calcul.toString();
    return res;
}

const buildProgressBar = (ratio, color) => {
    const progressBar = document.createElement('div');
    progressBar.className = "match-card--progress-bar";
    progressBar.style.width = `${ratio}%`;
    progressBar.style.height = '2px';
    progressBar.style.borderRadius = '2px';
    progressBar.style.background = color;

    return progressBar;
}

const buildTeamPlayerCard = (container, championName, summoner1Id, summoner2Id, summonerName, summonerLevel, damages, visionScore, golds, objectsIds, win, maxDamage, maxGolds) => {
    const summonerCard = document.createElement('div');
    summonerCard.className = "match-card--team-card";

    const summonerChampIcon = document.createElement('img');
    summonerChampIcon.src = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/champion/${championName}.png`;
    summonerChampIcon.className = "match-card-summoner--icon";

    if (!win) summonerChampIcon.style.border = '2px solid #FF4747';

    const summoner1Icon = document.createElement('img');
    summoner1Icon.src = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/spell/${getSummonerSpellImage(summoner1Id)}.png`;
    summoner1Icon.className = "match-card-summoner--summoner";

    const summoner2Icon = document.createElement('img');
    summoner2Icon.src = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/spell/${getSummonerSpellImage(summoner2Id)}.png`;
    summoner2Icon.className = "match-card-summoner--summoner";

    const summonerIconsContainer = document.createElement('div');
    summonerIconsContainer.className = 'match-card-summoners-icon--container';
    summonerIconsContainer.appendChild(summoner1Icon);
    summonerIconsContainer.appendChild(summoner2Icon);

    const summonerNameText = document.createElement('span');
    summonerNameText.className = 'match-card--summonername';
    summonerNameText.textContent = summonerName;

    const summonerLevelText = document.createElement('span');
    summonerLevelText.textContent = `Niveau ${summonerLevel}`;

    const summonerVisionScore = document.createElement('span');
    summonerVisionScore.textContent = visionScore;

    const summonerDamages = document.createElement('span');
    summonerDamages.textContent = damages;

    const damageBar = buildProgressBar((damages / maxDamage) * 100, "var(--default-dark)");
    const fullBar = buildProgressBar(100, "var(--light-gray)");

    const goldBar = buildProgressBar((golds / maxGolds) * 100, "var(--default-dark)");
    const fullGoldBar = buildProgressBar(100, "var(--light-gray)");

    damageBar.style.position = 'absolute';
    damageBar.style.left = '0';

    fullBar.style.position = 'absolute';
    fullBar.style.left = '0';

    fullGoldBar.style.position = 'absolute';
    fullGoldBar.style.left = '0';

    goldBar.style.position = 'absolute';
    goldBar.style.left = '0';

    const damageBarContainer = document.createElement('div');
    damageBarContainer.className = 'match-card-bar--container';
    damageBarContainer.appendChild(fullBar);
    damageBarContainer.appendChild(damageBar);

    summonerDamages.appendChild(damageBarContainer);
    
    const summonerGolds = document.createElement('span');
    summonerGolds.textContent = golds;
    
    const goldsBarContainer = document.createElement('div');
    goldsBarContainer.className = 'match-card-bar--container';
    goldsBarContainer.appendChild(fullGoldBar);
    goldsBarContainer.appendChild(goldBar);

    summonerGolds.appendChild(goldsBarContainer);

    const itemsContainer = document.createElement('div');
    itemsContainer.className = 'match-card--items-container';

    objectsIds.forEach((id) => {
        if (id !== 0) {
            const item = document.createElement('img');
            item.className = "match-card--item";
            item.src = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/item/${id}.png`;
            itemsContainer.appendChild(item);
        }
    });

    const summonerInfos = document.createElement('div');
    summonerInfos.className = 'match-card-team-player--infos';

    const summonerNameContainer = document.createElement('div');
    summonerNameContainer.className = 'match-card-summoner-infos--container';
    summonerNameContainer.appendChild(summonerNameText);
    summonerNameContainer.appendChild(summonerLevelText);

    summonerInfos.appendChild(summonerChampIcon);
    summonerInfos.appendChild(summonerIconsContainer);
    summonerInfos.appendChild(summonerNameContainer);

    container.appendChild(summonerInfos);
    container.appendChild(summonerDamages);
    container.appendChild(summonerVisionScore);
    container.appendChild(summonerGolds);
    container.appendChild(itemsContainer);

    const line = document.createElement('div');
    line.className = 'line';
    container.appendChild(line);

}

const buildTeamTable = (match, start, end, title) => {
    const teamHeader = document.createElement('div');
    teamHeader.className = 'match-card--team-container';

    const teamTitle = document.createElement('span');
    teamTitle.className = 'match-card--team-title';
    if (title.includes('Défaite')) {
        const victoryTitle = document.createElement('span');
        victoryTitle.textContent = title.slice(0, 7);
        victoryTitle.style.color = '#FF4747';
        teamTitle.textContent = title.split('Défaite')[1];
        teamTitle.prepend(victoryTitle);
        teamTitle.style.display = 'flex';
        teamTitle.style.gap = '.3rem';
    } else {
        teamTitle.textContent = title;
    }

    const damageTitle = document.createElement('span');
    damageTitle.textContent = "Dégats";
    damageTitle.className = 'match-card--team-title';

    const wardTitle = document.createElement('span');
    wardTitle.textContent = "Score de vision";
    wardTitle.className = 'match-card--team-title';

    const goldTitle = document.createElement('span');
    goldTitle.textContent = "Or gagné";
    goldTitle.className = 'match-card--team-title';

    const objectsTitle = document.createElement('span');
    objectsTitle.textContent = "Objets";
    objectsTitle.className = 'match-card--team-title';

    teamHeader.appendChild(teamTitle);
    teamHeader.appendChild(damageTitle);
    teamHeader.appendChild(wardTitle);
    teamHeader.appendChild(goldTitle);
    teamHeader.appendChild(objectsTitle);

    for (let i = start; i <= end; i++) {
        const summoner = match.participants[i];
        if (summoner === undefined) continue;
        buildTeamPlayerCard(
            teamHeader,
            summoner.championName,
            summoner.summoner1Id,
            summoner.summoner2Id,
            summoner.summonerName,
            summoner.summonerLevel,
            summoner.damages,
            summoner.visionScore,
            summoner.golds,
            summoner.items,
            summoner.win,
            match.maxDamage,
            match.maxGolds
        );
    }

    return teamHeader;
    
}

const buildMatchCard = (summonerName, match, rankAverageGame) => {

    //Mobile version
    const matchCardData = document.createElement('div');
    matchCardData.className = 'match-card--data';

    let indexSummoner = -1;
    for (let i = 0; i < match.participants.length; i++) {
        if (match.participants[i].riotIdGameName === summonerName) {
            indexSummoner = i;
            break;
        }
    }

    if (indexSummoner === -1) {
        return null;
    }

    const winOrLoss = document.createElement('span');
    winOrLoss.className = 'match-card--win-loss';
    if (match.participants[indexSummoner].win != true) {
        winOrLoss.textContent = 'Défaite';
        matchCardData.classList.add('lossing');
    } else {
        winOrLoss.textContent = 'Victoire';
        matchCardData.classList.add('winning');
    }

    const playedChamp = document.createElement('img');
    playedChamp.src = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/champion/${match.participants[indexSummoner].championName}.png`;
    playedChamp.alt = 'Image du champion joué';
    playedChamp.className = 'match-card--played-champ';

    const rolePlayed = document.createElement('span');
    rolePlayed.className = 'match-card--role';

    const rolePlayedDesktop = document.createElement('span');
    rolePlayedDesktop.className = 'match-card--role-desktop';
    
    if (match.participants[indexSummoner].role == ""){
        rolePlayed.textContent = 'Aucun';
        rolePlayedDesktop.textContent = 'Aucun';
    } else {
        if (match.participants[indexSummoner].role == "UTILITY"){
            rolePlayed.textContent = 'Support';
            rolePlayedDesktop.textContent = 'Support';
        } else {
            rolePlayed.textContent = roleConversion(match.participants[indexSummoner].role);
            rolePlayedDesktop.textContent = roleConversion(match.participants[indexSummoner].role);
        }
    }

    const queuePlayed = document.createElement('span');
    queuePlayed.className = 'match-card--queue-played';

    const queuePlayedDesktop = document.createElement('span');
    queuePlayedDesktop.className = 'match-card--queue-played-desktop';

    let queueName = 'Normal';

    switch (match.queueId) {
        case '0':
            queueName = 'Custom';
            break;
        case '440':
            queueName = 'Classée Flexible';
            break;
        case '420':
            queueName = 'Classée Solo';
            break;
        case '1700':
        case '1710':
            queueName = 'Arena';
            break;
        case '100':
            queueName = 'ARAM';
            break;
        case '450':
            queueName = 'ARAM';
            break;
    }

    queuePlayed.textContent = queueName;
    queuePlayedDesktop.textContent = queueName;

    const happenedTime = document.createElement('span');
    happenedTime.className = 'match-card--happened-time';

    const happenedTimeDesktop = document.createElement('span');
    happenedTimeDesktop.className = 'match-card--happened-time-desktop';

    happenedTime.textContent = timeConversion(match.matchHappened);
    happenedTimeDesktop.textContent = timeConversion(match.matchHappened);

    const gameDuration = document.createElement('span');
    gameDuration.className = 'match-card--game-duration';

    const gameDurationDesktop = document.createElement('span');
    gameDurationDesktop.className = 'match-card--game-duration-desktop';

    gameDuration.textContent = `${match.matchDuration.minutes}:${match.matchDuration.seconds}`;
    gameDurationDesktop.textContent = `${match.matchDuration.minutes}:${match.matchDuration.seconds}`;

    const kda = document.createElement('span');
    kda.className = 'match-card--kda';
    const tempKDA = match.participants[indexSummoner].kda;
    kda.textContent = tempKDA.toFixed(1) + ' KDA';

    const kdaContainer = document.createElement('div');
    kdaContainer.className = 'match-card--kda-container-desktop';

    const kdaDesktop = document.createElement('span');
    kdaDesktop.className = 'match-card--kda-desktop';
    kdaDesktop.textContent = `${match.participants[indexSummoner].kda.toFixed(1)} KDA`;

    const killsDeathsAssistsDesktop = document.createElement('span');
    killsDeathsAssistsDesktop.className = 'match-card--kills-deaths-assists-desktop';
    killsDeathsAssistsDesktop.textContent = `${match.participants[indexSummoner].kills}/${match.participants[indexSummoner].deaths}/${match.participants[indexSummoner].assists}`;

    kdaContainer.appendChild(kdaDesktop);
    kdaContainer.appendChild(killsDeathsAssistsDesktop);

    const creeps = document.createElement('span');
    creeps.className = 'match-card--cs';
    creeps.textContent = `${match.participants[indexSummoner].totalCs} CS`;

    const creepsContainerDesktop = document.createElement('div');
    creepsContainerDesktop.className = 'match-card--creeps-container-desktop';

    const creepsDesktop = document.createElement('span');
    creepsDesktop.className = 'match-card--cs-desktop';
    creepsDesktop.textContent = `${match.participants[indexSummoner].totalCs} CS`;

    const csMinutes = document.createElement('span');
    csMinutes.className = 'match-card--cs-min-desktop';
    csMinutes.textContent = calculateCsMin(match.matchDuration.minutes, match.participants[indexSummoner].totalCs) + ' CS/min';

    creepsContainerDesktop.appendChild(csMinutes);
    creepsContainerDesktop.appendChild(creepsDesktop);



    const averageRank = document.createElement('span');
    averageRank.className = 'match-card--average-rank';
    // averageRank.textContent = 'Rang moyen';
    averageRank.textContent = rankAverageGame;

    const averageRankContainerDesktop = document.createElement('div');
    averageRankContainerDesktop.className = 'match-card--average-rank-container-desktop';

    const averageRankDesktop = document.createElement('span');
    averageRankDesktop.className = 'match-card--average-rank-desktop';
    averageRankDesktop.textContent = 'Niveau moyen';

    const averageRankGameDesktop = document.createElement('span');
    averageRankGameDesktop.className = 'match-card--average-rank-game-desktop';
    averageRankGameDesktop.textContent = rankAverageGame;

    averageRankContainerDesktop.appendChild(averageRankDesktop);
    averageRankContainerDesktop.appendChild(averageRankGameDesktop);


    const pointGameInfos1 = document.createElement('span');
    pointGameInfos1.textContent = '•';

    const pointGameInfos2 = document.createElement('span');
    pointGameInfos2.textContent = '•';

    const pointGameInfos3 = document.createElement('span');
    pointGameInfos3.textContent = '•';

    const pointGameInfos1Desktop = document.createElement('span');
    pointGameInfos1Desktop.textContent = '•';

    const pointGameInfos2Desktop = document.createElement('span');
    pointGameInfos2Desktop.textContent = '•';

    const pointGameInfos3Desktop = document.createElement('span');
    pointGameInfos3Desktop.textContent = '•';

    const gameInfosDesktop = document.createElement('div');
    gameInfosDesktop.className = 'match-card--game-infos-desktop';
    gameInfosDesktop.appendChild(rolePlayedDesktop);
    gameInfosDesktop.appendChild(pointGameInfos1Desktop);
    gameInfosDesktop.appendChild(queuePlayedDesktop);
    gameInfosDesktop.appendChild(pointGameInfos2Desktop);
    gameInfosDesktop.appendChild(happenedTimeDesktop);
    gameInfosDesktop.appendChild(pointGameInfos3Desktop);
    gameInfosDesktop.appendChild(gameDurationDesktop);


    const gameInfos = document.createElement('div');
    gameInfos.className = 'match-card--game-infos';
    gameInfos.appendChild(rolePlayed);
    gameInfos.appendChild(pointGameInfos1);
    gameInfos.appendChild(queuePlayed);
    gameInfos.appendChild(pointGameInfos2);
    gameInfos.appendChild(happenedTime);
    gameInfos.appendChild(pointGameInfos3);
    gameInfos.appendChild(gameDuration);

    const playerStats = document.createElement('div');
    playerStats.className = 'match-card--player-stats';
    playerStats.appendChild(kda);
    playerStats.appendChild(creeps);
    playerStats.appendChild(averageRank);

    const playerStatsDesktop = document.createElement('div');
    playerStatsDesktop.className = 'match-card--player-stats-desktop';
    playerStatsDesktop.appendChild(kdaContainer);
    playerStatsDesktop.appendChild(creepsContainerDesktop);
    playerStatsDesktop.appendChild(averageRankContainerDesktop);

    const gameStatsContainer = document.createElement('div');
    gameStatsContainer.className = 'match-card--game-stats-container';
    winOrLoss.appendChild(gameInfosDesktop);
    gameStatsContainer.appendChild(winOrLoss);
    gameStatsContainer.appendChild(gameInfos);
    gameStatsContainer.appendChild(playerStats);
    gameStatsContainer.appendChild(playerStatsDesktop);


    const teams = [];

    if (match.queueId === "1710" || match.queueId === "1700") {
        let rank = 1;
        for (let i = 0; i < 8; i += 2) {
            teams.push(buildTeamTable(match, i, i + 1, `${rank === 1 ? 'Victoire' : 'Défaite'} ${rank}${rank === 1 ? 'er' : 'eme'} place`));
            rank++;
        }
    } else {
        teams.push(buildTeamTable(match, 0, 4, (match.winnerTeam === 100 ? 'Victoire' : 'Défaite') + '(Equipe bleue)'));
        teams.push(buildTeamTable(match, 5, 9, (match.winnerTeam === 200 ? 'Victoire' : 'Défaite') + '(Equipe rouge)'));
    }

    const teamWrapper = document.createElement('div');
    teamWrapper.className = 'team-wrapper';
    teamWrapper.style.display = 'none';

    teams.forEach(team => teamWrapper.appendChild(team));

    const detailsIcon = document.createElement('img');
    detailsIcon.className = 'match-card--details-icon';
    detailsIcon.src = '/img/arrow-down.svg';

    detailsIcon.addEventListener('click', () => { 
        if (teamWrapper.style.display === 'grid') {
            detailsIcon.classList.remove('toggle');
            teamWrapper.style.display = 'none';
            return;
        }
        detailsIcon.classList.add('toggle');
        teamWrapper.style.display = 'grid';
    });

    matchCardData.appendChild(detailsIcon);
    matchCardData.appendChild(playedChamp);
    matchCardData.appendChild(gameStatsContainer);


    const matchWrapper = document.createElement('div');
    matchWrapper.className = 'match-card--wrapper';

    matchWrapper.appendChild(matchCardData);
    matchWrapper.appendChild(teamWrapper);

    return matchWrapper;
}


const fetchSummonerData = async () => {

    const urlInfos = window.location.href.split("/");
    const summonerName = urlInfos[urlInfos.length - 2];
    const summonerTag = urlInfos[urlInfos.length - 1];

    const response = await fetch(
        `/summoners/${summonerName}/${summonerTag}`,
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            }
        }
    );

    const responseSpell = await fetch(
        'https://ddragon.leagueoflegends.com/cdn/13.24.1/data/en_US/summoner.json',
        {
            method: 'GET'
        }
    );

    spellData = await responseSpell.json();

    const summonerData = await response.json();
    

    currentSummoner = summonerData;
    const serv = summonerData.tag;

    const summonerCard = await buildProfilCardContainer(summonerData.name, summonerData.tag, summonerData.profileIconId, serv, summonerData.level);
    profilCardContainer.appendChild(summonerCard);
    
    document.querySelector('.tqt').classList.add('visible');
    document.querySelector('.spinner-container').classList.remove('spinner-visible');
    document.querySelector('.spinner-container').classList.add('spinner-hidden');

    const summonerTitles = buildScrollingTitles(summonerData.queues.soloQueue, summonerData.queues.flexQueue);
    scrollTitles.appendChild(summonerTitles);

    const summonerRankedCards = buildRankedCards(summonerData.queues.soloQueue, summonerData.queues.flexQueue);
    rankCardContainer.appendChild(summonerRankedCards[0]);
    rankCardContainer.appendChild(summonerRankedCards[1]);

    const fetchSummonerMatches = async () => {


        const matchesID = summonerData.matches;
        let promises = [];
        const spinnerHistoric = document.createElement('div');
        spinnerHistoric.className = 'spinner';
        document.querySelector('.spinner--historic-container').appendChild(spinnerHistoric);
        
        matchesID.forEach( matchID => {
            promises.push( fetch (
                `/matches/${matchID}`,
                {
                    method: 'GET',
                    headers: {
                        'Content-Type' : 'application/json',
                        'Accept' : 'application/json'
                    }
                }
            )
            )   
        }
        );

        const responseMatches = await Promise.all(promises);
        promises = [];

        responseMatches.forEach( (match) => {
            promises.push(match.json());
        }
        );

        const titleMatchHistoric = buildMatchesHistoric();
        matchesHistoricCardsContainer.appendChild(titleMatchHistoric);

        const matchesData = await Promise.all(promises);

        matchesData.forEach( (match) => {
            console.log(match);

            document.querySelector('.spinner--historic-container').classList.add('spinner-visible');

            document.querySelector('.spinner--historic-container').classList.remove('spinner-visible');
            document.querySelector('.spinner--historic-container').classList.add('spinner-hidden');

            const matchCard = buildMatchCard(summonerData.name, match, summonerData.queues.soloQueue === null ? 'Unranked' : summonerData.queues.soloQueue.tier);
            
            console.log(matchCard)

            if (matchCard != null) {
                matchesHistoricCardsContainer.appendChild(matchCard);
            }
        }
        );
        document.querySelector('.footer-container').style.display = 'grid';

        
    }
    fetchSummonerMatches();
    fetchPosts(summonerData.name, summonerData.tag);
    playerMatchesRankedContainer.appendChild(rankCardContainer);
    playerMatchesRankedContainer.appendChild(matchesHistoricCardsContainer);
}

const registerSummoner = async () => {
    const urlInfos = window.location.href.split("/");
    const summonerName = urlInfos[urlInfos.length - 2];
    const summonerTag = urlInfos[urlInfos.length - 1];
    
    await fetch(
        `/summoners/register/${summonerName}/${summonerTag}`,
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            }
        }
    );
}

const buildPost = (username, message, date, profilePicture) => {
    const post = document.createElement('div');
    post.className = 'post';
    const profileIcon = document.createElement('img');
    profileIcon.src = `data:image/png;base64,${profilePicture}`;
    profileIcon.alt = 'Profile Picture';

    const postInfosContainer = document.createElement('div');
    postInfosContainer.style.display = 'flex';
    postInfosContainer.style.gap = '.5rem';

    const usernameText = document.createElement('span');
    usernameText.className = 'post-username';
    usernameText.textContent = username;

    const dateText = document.createElement('span');
    dateText.className = 'post-date';
    dateText.textContent = date;

    const messageText = document.createElement('p');
    messageText.className = 'post-message';
    messageText.textContent = message;

    postInfosContainer.appendChild(usernameText);
    postInfosContainer.appendChild(dateText);

    const postContent = document.createElement('div');
    postContent.className = 'post-content';

    postContent.appendChild(postInfosContainer);
    postContent.appendChild(messageText);

    post.appendChild(profileIcon);
    post.appendChild(postContent);

    return post;

}

const timeSince = (date) => {
    const now = new Date();
    const msDifference = now - date;

    // Convertir en différentes unités de temps
    let seconds = Math.floor(msDifference / 1000);
    let minutes = Math.floor(seconds / 60);
    let hours = Math.floor(minutes / 60);
    let days = Math.floor(hours / 24);

    // Calculer les restes pour chaque unité
    seconds %= 60;
    minutes %= 60;
    hours %= 24;

    // Construction de la chaîne de résultat
    let resultat = "";
    if (days > 0) {
        resultat += days + " jours ";
    } else if (hours > 0) {
        resultat += hours + " heures ";
    } else if (minutes > 0) {
        resultat += minutes + " minutes ";
    } else if (seconds > 0) {
        resultat += seconds + " secondes ";
    }

    return "Il y a " + resultat.trim();
}

const fetchCurrentUser = async () => {
    const userResponse = await fetch(
        '/user/me',
        {
            method: 'GET'
        }
    );

    if (userResponse.status !== 200) {
        return new Promise((resolve) => resolve(null));
    }

    const user = await userResponse.json();

    return new Promise((resolve) => resolve(user));
}

const sendPost = async (e) => {
    e.preventDefault();


    if (postInput.value.length === 0 || postInput.value.length > 100 || currentSummoner.name === null) return;

    const response = await fetch(
        '/post',
        {
            method: 'POST',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            },
            body: JSON.stringify({ message: postInput.value, summonerName: currentSummoner.name, summonerTag: currentSummoner.tag })
        }
    );
    
    console.log(response);
    console.log({ message: postInput.value, summonerName: currentSummoner });

    if (response.status !== 200) return;

    const user = await fetchCurrentUser();

    if (user === null) return;
    
    const postCard = buildPost(user.username, postInput.value, "Maintenant", user.profilepicture);
    document.querySelector('.posts-container').prepend(postCard);
    postInput.value = "";
}

const fetchPosts = async (summoner, tag) => {

    const spinnerHistoric = document.createElement('div');
    spinnerHistoric.className = 'spinner';
    document.querySelector('.spinner--posts-container').appendChild(spinnerHistoric);

    const response = await fetch(
        `/summoners/${summoner}/${tag}/posts`,
        {
            method: 'GET'
        }
    );
    
    if (response.status !== 200) return;

    const posts = await response.json();
    
    posts.forEach((post) => { 
        const postCard = buildPost(post.username, post.message, timeSince(new Date(post.date).getTime()), post.profilepicture);
        document.querySelector('.posts-container').appendChild(postCard);
    });

    document.querySelector('.spinner--posts-container').classList.add('spinner-visible');

    document.querySelector('.spinner--posts-container').classList.remove('spinner-visible');
    document.querySelector('.spinner--posts-container').classList.add('spinner-hidden');
}


const showComments = () => {
    postsContainer.classList.remove('hidden');
    postsContainer.classList.add('visible');
    playerMatchesRankedContainer.style.flexDirection = 'row-reverse';
    matchesHistoricCardsContainer.classList.remove('visible');
    matchesHistoricCardsContainer.classList.add('hidden');
    const formContainer = document.querySelector('.form-container')
    if (formContainer !== null) formContainer.style.display = 'flex';
    overviewBtn.classList.remove('active-link');
    postsBtn.classList.add('active-link');
        
}

const showProfile = () => {
    const formContainer = document.querySelector('.form-container')
    if (formContainer !== null) formContainer.style.display = 'none';
    playerMatchesRankedContainer.style.flexDirection = 'row';
    matchesHistoricCardsContainer.classList.add('visible');
    matchesHistoricCardsContainer.classList.remove('hidden');
    postsContainer.classList.remove('visible');
    postsContainer.classList.add('hidden');
    overviewBtn.classList.add('active-link');
    postsBtn.classList.remove('active-link');
}

const handleType = (e) => {
    if (e.target.value.length > 100) {
        e.target.value = e.target.value.substring(0, 10);
    }
}



if (postInput !== null) postInput.addEventListener('input', handleType);
overviewBtn.addEventListener('click', showProfile);
postsBtn.addEventListener('click', showComments);

if (postForm !== null) postForm.addEventListener('submit', sendPost);

fetchSummonerData();
registerSummoner();