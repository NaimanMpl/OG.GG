const hiddenElements = document.querySelectorAll('.hidden-section');
const followDescContainer = document.querySelector('.follow-section--desc');

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting && !entry.target.classList.contains('show')) {
            entry.target.classList.add('show');
        }
    });
});

const createWord = (text, index) => {
    const word = document.createElement('span');
    word.textContent = `${text} `;
    word.classList.add('follow-section-desc--word');
    word.style.transitionDelay = `${index * 60}ms`;
    return word;
}

const addWord = (text, index) => followDescContainer.appendChild(createWord(text, index));

const createSubtitle = text => text.split(' ').map(addWord);

createSubtitle("J'adore jouer Kai'Sa tellement que ça fait 2 saisons que je suis stuck Gold IV .");

hiddenElements.forEach((element) => observer.observe(element));