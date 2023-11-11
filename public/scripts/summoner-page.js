const profilCardContainer = document.querySelector('.profileCard--cardFollow-container');
const scrollTitles = document.querySelector('.scroll-titles-wrapper');
const rankCardContainer = document.querySelector('.ranked-cards--container');

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

    const pseudo = document.createElement('span');
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

    levelContainer.appendChild(levelText);
    levelContainer.appendChild(level);

    const pseudoAndServer = document.createElement('div');
    pseudoAndServer.className = 'profilCard--pseudoServer-container';
    pseudoAndServer.appendChild(pseudo);
    pseudoAndServer.appendChild(serverName);

    const playerInfos = document.querySelector('.profilCard--playerInfos-container');
    playerInfos.appendChild(pseudoAndServer);
    playerInfos.appendChild(levelContainer);

    const container = document.createElement('div');
    container.className = 'profilCard--playerDataCard-container';

    container.appendChild(summonerProfilePictureWrapper);
    container.appendChild(playerInfos);

    return container;
}

const buildProfilCardContainer = (summonerName, profilPicture, server, accountLevel) => {
    const followBtn = document.createElement('button');
    followBtn.className = 'profilCard--followBtn';
    followBtn.textContent = 'Suivre';

    const profilCardInfos = buildProfilInfosContainer(summonerName, profilPicture, server, accountLevel);
    const profilCard = document.createElement('div');
    profilCard.className = 'profilCard--cardFollow-container';
    profilCard.appendChild(profilCardInfos);
    profilCard.appendChild(followBtn);

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
    
    for (let i = 0; i < 10; i++){
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
}

const registerSummoner = async () => {
    const summonerName = window.location.href.split("/").slice(-1);
    
    await fetch(
        `/summoners/register/${summonerName}`,
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            }
        }
    );
}

fetchSummonerData();
registerSummoner();