const profilCardContainer = document.querySelector('.profileCard--cardFollow-container');
const scrollTitles = document.querySelector('.scroll-titles-wrapper');
const rankCardContainer = document.querySelector('.ranked-cards--container');
const matchesHistoricCardsContainer = document.querySelector('.matches-historic-cards--container');
const playerMatchesRankedContainer = document.querySelector('.player-matches-ranked--container');


const buildProfilInfosContainer = (summonerName, profilPicture, server, accountLevel) => {
    
    const summonerProfilePictureWrapper = document.createElement('div');
    summonerProfilePictureWrapper.className = 'profileCard-cardIcon--wrapper';
    const summonerProfilPicture = document.createElement('img');
    summonerProfilPicture.className = 'profilCard--icon';
    summonerProfilPicture.src = `https://ddragon.leagueoflegends.com/cdn/13.20.1/img/profileicon/${profilPicture}.png`;
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

    const followBtnDesktop = document.createElement('button');
    followBtnDesktop.className = 'profilCard--followBtn-desktop';
    followBtnDesktop.textContent = 'Suivre';

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

    return container;
}

const buildProfilCardContainer = (summonerName, profilPicture, server, accountLevel) => {
    const followBtnNav = document.createElement('button');
    followBtnNav.className = 'profilCard--followBtn-nav';
    followBtnNav.textContent = 'Suivre';

    const profilCardInfos = buildProfilInfosContainer(summonerName, profilPicture, server, accountLevel);
    const profilCard = document.createElement('div');
    profilCard.className = 'profilCard--cardFollow-container';
    profilCard.appendChild(profilCardInfos);
    profilCard.appendChild(followBtnNav);

    return profilCard;
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

// const rankAverage = (match) => {
//     for 
// }

const roleConversion = (role) => {
    return role.charAt(0).toUpperCase() + role.slice(1).toLowerCase();
}

const calculateCsMin = (minutesTotal, nbCsMax) => {
    const calcul = (nbCsMax/minutesTotal).toFixed(1);
    const res = calcul.toString();
    return res;
}

const buildMatchCard = (summonerName, match, rankAverageGame) => {

    //Mobile version

    const matchCardData = document.createElement('div');
    matchCardData.className = 'match-card--data';

    let indexSummoner = -1;
    for (let i = 0; i < match.participants.length; i++) {
        if (match.participants[i].summonerName === summonerName) {
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
    playedChamp.src = `https://ddragon.leagueoflegends.com/cdn/13.22.1/img/champion/${match.participants[indexSummoner].championName}.png`;
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

    if (match.queueId == 440) {
        queuePlayed.textContent = 'Classée Flexible';
        queuePlayedDesktop.textContent = 'Classée Flexible';
    } else if (match.queueId == 420) {
        queuePlayed.textContent = 'Classée Solo';
        queuePlayedDesktop.textContent = 'Classée Solo';
    } else {
        queuePlayed.textContent = 'Non classée';
        queuePlayedDesktop.textContent = 'Non classée';
    }



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

    matchCardData.appendChild(playedChamp);
    matchCardData.appendChild(gameStatsContainer);

    return matchCardData;
}

const fetchSummonerData = async () => {

    const response = await fetch(
        `/summoners/${window.location.href.split("/").slice(-1)}`,
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            }
        }
    );

    const summonerData = await response.json();

    document.querySelector('.spinner-container').classList.remove('spinner-visible');
    document.querySelector('.spinner-container').classList.add('spinner-hidden');

    console.log(summonerData);

    const serv = 'EUW';

    const summonerCard = buildProfilCardContainer(summonerData.name, summonerData.profileIconId, serv, summonerData.level);
    profilCardContainer.appendChild(summonerCard);

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

            const matchCard = buildMatchCard(summonerData.name, match, summonerData.queues.soloQueue.tier);
            if (matchCard != null) {
                matchesHistoricCardsContainer.appendChild(matchCard);
            }
        }


        );
        
    }
    fetchSummonerMatches();
    playerMatchesRankedContainer.appendChild(rankCardContainer);
    playerMatchesRankedContainer.appendChild(matchesHistoricCardsContainer);
}

fetchSummonerData();