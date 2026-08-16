const menuRoot = document.querySelector('[data-menu-root]');
const menuPanel = document.querySelector('[data-menu-panel]');
const menuOpen = document.querySelector('[data-menu-open]');
const menuClose = document.querySelector('[data-menu-close]');
const menuBackdrop = document.querySelector('[data-menu-backdrop]');

if (menuRoot && menuPanel && menuOpen && menuClose && menuBackdrop) {
    let closeTimer;

    const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

    const openMenu = () => {
        window.clearTimeout(closeTimer);
        menuRoot.classList.remove('hidden');
        menuRoot.setAttribute('aria-hidden', 'false');
        menuOpen.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';

        window.requestAnimationFrame(() => {
            menuPanel.classList.remove('translate-x-full');
            menuClose.focus();
        });
    };

    const closeMenu = () => {
        menuPanel.classList.add('translate-x-full');
        menuOpen.setAttribute('aria-expanded', 'false');
        menuRoot.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        menuOpen.focus();

        closeTimer = window.setTimeout(() => {
            menuRoot.classList.add('hidden');
        }, 300);
    };

    menuOpen.addEventListener('click', openMenu);
    menuClose.addEventListener('click', closeMenu);
    menuBackdrop.addEventListener('click', closeMenu);
    menuRoot.querySelectorAll('[data-menu-link]').forEach((link) => link.addEventListener('click', closeMenu));

    menuRoot.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMenu();
            return;
        }

        if (event.key !== 'Tab') {
            return;
        }

        const focusable = [...menuPanel.querySelectorAll(focusableSelector)];
        const first = focusable[0];
        const last = focusable.at(-1);

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last?.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first?.focus();
        }
    });
}

document.querySelectorAll('[data-faq-group]').forEach((group) => {
    group.querySelectorAll('[data-faq-trigger]').forEach((trigger) => {
        trigger.addEventListener('click', () => {
            const panel = document.getElementById(trigger.getAttribute('aria-controls'));
            const willOpen = trigger.getAttribute('aria-expanded') !== 'true';

            group.querySelectorAll('[data-faq-trigger]').forEach((otherTrigger) => {
                const otherPanel = document.getElementById(otherTrigger.getAttribute('aria-controls'));
                otherTrigger.setAttribute('aria-expanded', 'false');
                otherTrigger.querySelector('[data-faq-icon]')?.classList.remove('rotate-45');
                if (otherPanel) otherPanel.hidden = true;
            });

            trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
            trigger.querySelector('[data-faq-icon]')?.classList.toggle('rotate-45', willOpen);
            if (panel) panel.hidden = !willOpen;
        });
    });
});

const footer = document.querySelector('[data-site-footer]');
const mobileContactBar = document.querySelector('[data-mobile-contact-bar]');

if (footer && mobileContactBar && 'IntersectionObserver' in window) {
    const footerObserver = new IntersectionObserver(([entry]) => {
        mobileContactBar.classList.toggle('translate-y-full', entry.isIntersecting);
        mobileContactBar.classList.toggle('pointer-events-none', entry.isIntersecting);
    }, { threshold: 0.05 });

    footerObserver.observe(footer);
}
