const mobileNav = document.querySelector('.nav--mobile-link-container');
const navBtn = document.querySelector('.nav--cta-mobile-burger');
const closeNavBtn = document.getElementById('closenav-btn');


const handleNav = () => {
    if (!mobileNav.classList.contains('visible')) {
        mobileNav.classList.remove('hidden');
        mobileNav.classList.add('visible');
    }
}

document.addEventListener('click', (e) => {

    if (navBtn.contains(e.target)) return;
    
    const outsideClick = mobileNav.classList.contains('visible') && !mobileNav.contains(e.target);
    if (!outsideClick) return;
    
    mobileNav.classList.remove('visible');
    mobileNav.classList.add('hidden');

});

navBtn.addEventListener('click', handleNav);