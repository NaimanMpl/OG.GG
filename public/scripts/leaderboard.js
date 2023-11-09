const cardContainer = document.querySelector('.leaderboard-container');

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

const buildLeaderboardCard = (championName, wins, looses, rank) => {
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
    cardIcon.className = 'leaderboard-card--icon';

    card.appendChild(rankContainer);
    card.appendChild(cardIcon);
    card.appendChild(cardName);
    card.appendChild(winsContainer);
    card.appendChild(loosesContainer);
    card.appendChild(winRateContainer);

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
    
    leaderboardData.forEach((champion) => { 
        const leaderboardCard = buildLeaderboardCard(champion.championName, champion.wins, champion.looses, champion.rank)
        cardContainer.appendChild(leaderboardCard);
    });
}

fetchLeaderboard();