const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.main-navigation');

if (menuButton && navigation) {
    menuButton.addEventListener('click', () => {
        const isOpen = menuButton.getAttribute('aria-expanded') === 'true';
        menuButton.setAttribute('aria-expanded', String(!isOpen));
        menuButton.setAttribute('aria-label', isOpen ? 'Open navigation' : 'Close navigation');
        navigation.classList.toggle('is-open', !isOpen);
    });
}

const animatedCards = document.querySelectorAll('.image-card');
if ('IntersectionObserver' in window) {
    const cardObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.16 });
    animatedCards.forEach((card) => cardObserver.observe(card));
} else {
    animatedCards.forEach((card) => card.classList.add('is-visible'));
}
