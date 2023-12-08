const form = document.getElementById('search-form');
const blurWrapper = document.getElementById('blur-wrapper');
const formWrapper = document.getElementById('search-form-wrapper');
const searchBtn = document.getElementById('nav--search-btn');
const summonerInput = document.getElementById('summoner-name');
const searchDialog = document.querySelector('.search-form--dialog');
const resultsContainer = document.querySelector('.search-results-container');
const results = document.querySelectorAll('.search-result');
const closeNav = document.querySelector('.close-nav');

const handleSearchClick = () => {
    if (blurWrapper.classList.contains('blur')) {
        form.style.opacity = '0';
        form.classList.remove('active');
        blurWrapper.classList.remove('blur');
        formWrapper.style.zIndex = '-1';
        return;
    }
    formWrapper.style.zIndex = '999';
    form.style.opacity = '1';
    form.classList.add('active');
    blurWrapper.classList.add('blur');
    document.querySelector('body').classList.add('black-body');
}

const buildSummonerCard = (summonerName, profileIconID) => {
    const resultAction = document.createElement('a');
    resultAction.href = `/summoner/${summonerName}`;
    resultAction.style.display = 'block';
    resultAction.className = 'result-card--wrapper';
    const resultCardWrapper = document.createElement('div');
    const resultCard = document.createElement('div');
    resultCard.className = 'search-result';
    resultCard.classList.add('active');
    resultCard.classList.add('real-result');
    const profileIconLink = `https://ddragon.leagueoflegends.com/cdn/13.24.1/img/profileicon/${profileIconID}.png`;
    resultCard.style.background = `url(${profileIconLink})`;
    resultCard.style.backgroundPosition = 'center';
    resultCard.style.backgroundSize = 'cover';
    resultCard.style.zIndex = '999';
    const summonerNameText = document.createElement('span');
    const summonerRegion = document.createElement('span');
    summonerNameText.className = 'result-card--summoner-name';
    summonerNameText.textContent = summonerName;
    summonerRegion.className = 'result-card--region';
    summonerRegion.textContent = "EUW";
    const summonerInfoContainer = document.createElement('div');
    summonerInfoContainer.className = 'result-card--infos';
    summonerInfoContainer.appendChild(summonerNameText);
    summonerInfoContainer.appendChild(summonerRegion);
    resultCardWrapper.appendChild(resultCard);
    resultCardWrapper.appendChild(summonerInfoContainer);
    resultAction.appendChild(resultCardWrapper);
    return resultAction;
}

const handleTyping = async (e) => {
    if (e.target.value.length <= 2) {
        resultsContainer.style.justifyContent = 'space-between';
        resultsContainer.classList.remove('active');
        
        const resultWrappers = document.querySelectorAll('.result-card--wrapper');
        resultWrappers.forEach(element => element.remove());
        
        results.forEach((element) => element.classList.remove('hidden-result'));
        results.forEach((result) => result.classList.remove('active'));
        
        searchDialog.classList.remove('active');
        searchDialog.textContent = "";
        return;
    }
    if (!searchDialog.classList.contains('active')) {
        searchDialog.classList.add('active');
        searchDialog.textContent = "Recherche en cours";
        resultsContainer.classList.add('active');
        results.forEach((result) => result.classList.add('active'));
        for (let i = 0; i < 3; i++) {
            const dotSpan = document.createElement('span');
            dotSpan.textContent = '.';
            dotSpan.style.animationDelay = `${i * 120}ms`;
            searchDialog.appendChild(dotSpan);
        }
    }

    const summonerName = e.target.value;
    console.log(`/summoners/search/${encodeURIComponent(summonerName)}`)
    const response = await fetch(
        `/summoners/search/${encodeURIComponent(summonerName)}`,
        {
            method: 'GET',
            headers: {
                'Content-Type' : 'application/json',
                'Accept' : 'application/json'
            }
        }
    );
    const data = await response.json();
    const fakeResults = document.querySelectorAll('.fake-result');
    fakeResults.forEach((fakeResult) => {
        fakeResult.classList.add('hidden-result');
        fakeResult.classList.remove('active');
    });
    
    if (data.results.length == 0) {
        return;
    }
    resultsContainer.style.justifyContent = 'flex-start';
    resultsContainer.style.gap = '1.5rem';

    const resultWrappers = document.querySelectorAll('.result-card--wrapper')
    document.querySelectorAll('.search-result').forEach((element) => element.classList.add('hidden-result'));
    resultWrappers.forEach((element) => element.remove());

    data.results.forEach(result => {
        const resultCard = buildSummonerCard(result.name, result.profileIconId);
        resultsContainer.appendChild(resultCard);
    });

    document.querySelectorAll('.result-card--wrapper > div > .real-result').forEach((element) => element.classList.remove('active'));

}

const closeNavMenu = () => {
    form.style.opacity = '0';
    form.classList.remove('active');
    blurWrapper.classList.remove('blur');
    formWrapper.style.zIndex = '-1';
    document.querySelector('body').classList.remove('black-body');
}

const handleSubmit = (e) => {
    e.preventDefault();

    window.location.href = `/summoner/${summonerInput.value}`;
}

searchBtn.addEventListener('click', handleSearchClick);
summonerInput.addEventListener('input', handleTyping);
closeNav.addEventListener('click', closeNavMenu);
form.addEventListener('submit', handleSubmit);
