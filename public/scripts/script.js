const navBtn = document.querySelector('.nav--cta-mobile-burger');
const closeNavBtn = document.getElementById('closenav-btn');
const mobileNav = document.querySelector('.nav--mobile-link-container');
const hiddenElements = document.querySelectorAll('.hidden-section');
const followDescContainer = document.querySelector('.follow-section--desc');

const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting && !entry.target.classList.contains('show')) {
            entry.target.classList.add('show');
        }
    });
});

let navVisible = false;

const handleNav = () => {
    if (!navVisible) {
        mobileNav.classList.remove('hidden');
        mobileNav.classList.add('visible');
    } else {
        mobileNav.classList.remove('visible');
        mobileNav.classList.add('hidden');
    }
    navVisible = !navVisible;
}

const createWord = (text, index) => {
    const word = document.createElement('span');
    word.textContent = `${text} `;
    word.classList.add('follow-section-desc--word');
    word.style.transitionDelay = `${index * 60}ms`;
    return word;
}

const addWord = (text, index) => followDescContainer.appendChild(createWord(text, index));

const createSubtitle = text => text.split(' ').map(addWord);

navBtn.addEventListener('click', handleNav);
closeNavBtn.addEventListener('click', handleNav);
hiddenElements.forEach((element) => observer.observe(element));

createSubtitle("J'adore jouer Kai'Sa tellement que ça fait 2 saisons que je suis stuck Gold IV .");