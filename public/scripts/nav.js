const mobileNav = document.querySelector('.nav--mobile-link-container');
const navBtn = document.querySelector('.nav--cta-mobile-burger');
const closeNavBtn = document.getElementById('closenav-btn');

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
navBtn.addEventListener('click', handleNav);