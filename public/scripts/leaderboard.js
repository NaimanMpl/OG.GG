const cardContainer = document.querySelector('.leaderboard-container');
const titleContainer = document.querySelector('.leaderboard--title');

const buildStatsContainer = (title, subtitle, className) => {
    const container = document.createElement('div');
    container.className = 'leaderboard-card--stats-container';

    const cardTitle = document.createElement('span');
    cardTitle.className = className;
    cardTitle.textContent = title;

    const cardSubtitle = document.createElement('p');
    cardSubtitle.className = 'leaderboard-card--stats-title';
    cardSubtitle.textContent = subtitle;

    container.appendChild(cardTitle);
    container.appendChild(cardSubtitle);

    return container;

}

const buildLeaderboardCard = (index, championName, wins, looses, rank) => {
    const card = document.createElement('div');
    card.className = 'leaderboard-card';

    const rankContainer = buildStatsContainer(`#${rank}`, 'Rang', 'leaderboard-card--rank');
    const winsContainer = buildStatsContainer(`${wins}`, 'Victoires', 'leaderboard-card--wins');
    const loosesContainer = buildStatsContainer(`${looses}`, 'Défaites', 'leaderboard-card--looses');
    const winRateContainer = buildStatsContainer(`${(wins / (wins + looses) * 100).toFixed(2)}%`, 'Winrate', 'leaderboard-card--winrate');

    const cardName = document.createElement('p');
    cardName.className = 'leaderboard-card--name';
    cardName.textContent = championName;

    const cardIcon = document.createElement('img');
    cardIcon.src = `https://ddragon.leagueoflegends.com/cdn/13.22.1/img/champion/${championName}.png`;
    cardIcon.alt = `${championName}`;
    cardIcon.className = 'leaderboard-card--icon';

    card.appendChild(rankContainer);
    card.appendChild(cardIcon);
    card.appendChild(cardName);
    card.appendChild(winsContainer);
    card.appendChild(loosesContainer);
    card.appendChild(winRateContainer);

    card.style.transitionDelay = `${index * 100}ms`;
    setTimeout(() => card.classList.add('active'), 100);

    return card;
}

const fetchLeaderboard = async () => {
    const response = await fetch(
        '/champions/leaderboard',
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            }
        }
    );
    const leaderboardData = await response.json();
    
    leaderboardData.map((champion, index) => { 
        const leaderboardCard = buildLeaderboardCard(index, champion.championName, champion.wins, champion.looses, champion.rank)
        cardContainer.appendChild(leaderboardCard);
    });
}

fetchLeaderboard();