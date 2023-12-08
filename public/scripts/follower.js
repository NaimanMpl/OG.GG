const buildFollowerCard = (profileIconId, summonerName, description, date) => {
    const followCard = document.createElement('a');
    followCard.className = 'follow-card';
    followCard.href = `/summoner/${summonerName}`;
    const profileIcon = document.createElement('img');
    profileIcon.src = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/profileicon/${profileIconId}.png`;

    const followCardInfos = document.createElement('div');
    followCardInfos.className = 'follow-card--infos';
    const followCardInfosWrapper = document.createElement('div');
    followCardInfosWrapper.className = 'follow-card-infos--wrapper';

    const followCardInfosContainer = document.createElement('div');
    followCardInfosContainer.className = 'follow-card-infos--container';

    const username = document.createElement('span');
    username.className = 'follow-card--username';
    username.textContent = summonerName;

    const region = document.createElement('span');
    region.className = 'follow-card--region';
    region.textContent = 'EUW';

    const followDate = document.createElement('span');
    followDate.className = 'follow-card--date';
    followDate.textContent = '2 heures';

    const followDescription = document.createElement('p');
    followDescription.className = 'follow-card--description';
    followDescription.textContent = "T'inquiète";

    followCardInfosContainer.appendChild(username);
    followCardInfosContainer.appendChild(region);

    followCardInfosWrapper.appendChild(followCardInfosContainer);
    followCardInfosWrapper.appendChild(followDate);

    followCardInfos.appendChild(followCardInfosWrapper);
    followCardInfos.appendChild(followDescription);

    followCard.appendChild(profileIcon);
    followCard.appendChild(followCardInfos);

    return followCard;
}

const fetchFollowers = async () => {
    const response = await fetch(
        '/user/me',
        {
            method: 'GET'
        }
    );

    if (response.status !== 200) {
        return;
    }

    const userData = await response.json();

    userData.followers.forEach((follower) => {
        const followCard = buildFollowerCard(follower.profileIconId, follower.summonerName, '', '');
        
        document.querySelector('.followers-container').appendChild(followCard);
    });

}

fetchFollowers();