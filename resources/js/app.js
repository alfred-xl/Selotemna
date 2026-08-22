const menuRoot = document.querySelector('[data-menu-root]');
const menuPanel = document.querySelector('[data-menu-panel]');
const menuOpen = document.querySelector('[data-menu-open]');
const menuClose = document.querySelector('[data-menu-close]');
const menuBackdrop = document.querySelector('[data-menu-backdrop]');
const mobileContactBar = document.querySelector('[data-mobile-contact-bar]');

let footerIsVisible = false;
let menuIsOpen = false;

const updateMobileContactBar = () => {
    if (!mobileContactBar) return;

    const shouldHide = footerIsVisible || menuIsOpen;
    mobileContactBar.classList.toggle('translate-y-full', shouldHide);
    mobileContactBar.classList.toggle('pointer-events-none', shouldHide);
    mobileContactBar.setAttribute('aria-hidden', shouldHide ? 'true' : 'false');
    mobileContactBar.toggleAttribute('inert', shouldHide);
};

if (menuRoot && menuPanel && menuOpen && menuClose && menuBackdrop) {
    let closeTimer;

    const desktopBreakpoint = window.matchMedia('(min-width: 64rem)');
    const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

    const openMenu = () => {
        window.clearTimeout(closeTimer);
        menuIsOpen = true;
        menuRoot.classList.remove('hidden');
        menuRoot.setAttribute('aria-hidden', 'false');
        menuOpen.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
        updateMobileContactBar();

        window.requestAnimationFrame(() => {
            menuPanel.classList.remove('translate-x-full');
            menuClose.focus();
        });
    };

    const closeMenu = ({ immediate = false, returnFocus = true } = {}) => {
        window.clearTimeout(closeTimer);
        menuIsOpen = false;
        menuPanel.classList.add('translate-x-full');
        menuOpen.setAttribute('aria-expanded', 'false');
        menuRoot.setAttribute('aria-hidden', 'true');
        document.body.style.removeProperty('overflow');
        updateMobileContactBar();

        if (returnFocus && !desktopBreakpoint.matches) {
            menuOpen.focus();
        }

        if (immediate) {
            menuRoot.classList.add('hidden');
            return;
        }

        closeTimer = window.setTimeout(() => {
            menuRoot.classList.add('hidden');
        }, 300);
    };

    menuOpen.addEventListener('click', openMenu);
    menuClose.addEventListener('click', () => closeMenu());
    menuBackdrop.addEventListener('click', () => closeMenu());
    menuRoot.querySelectorAll('[data-menu-link]').forEach((link) => {
        link.addEventListener('click', () => closeMenu());
    });

    menuRoot.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMenu();
            return;
        }

        if (event.key !== 'Tab') return;

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

    desktopBreakpoint.addEventListener('change', (event) => {
        if (event.matches && menuIsOpen) {
            closeMenu({ immediate: true, returnFocus: false });
        }
    });
}

document.querySelectorAll('[data-divisions-dropdown]').forEach((dropdown) => {
    const toggle = dropdown.querySelector('[data-divisions-toggle]');
    const menu = dropdown.querySelector('[data-divisions-menu]');
    const icon = dropdown.querySelector('[data-divisions-icon]');

    if (!toggle || !menu) return;

    const links = [...menu.querySelectorAll('[data-divisions-link]')];

    const closeDropdown = ({ returnFocus = false } = {}) => {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        icon?.classList.remove('rotate-180');
        if (returnFocus) toggle.focus();
    };

    const openDropdown = ({ focusFirst = false } = {}) => {
        menu.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
        icon?.classList.add('rotate-180');
        if (focusFirst) links[0]?.focus();
    };

    toggle.addEventListener('click', () => {
        if (toggle.getAttribute('aria-expanded') === 'true') closeDropdown();
        else openDropdown();
    });

    toggle.addEventListener('keydown', (event) => {
        if (event.key !== 'ArrowDown') return;
        event.preventDefault();
        openDropdown({ focusFirst: true });
    });

    dropdown.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            event.preventDefault();
            closeDropdown({ returnFocus: true });
        }
    });

    links.forEach((link) => link.addEventListener('click', () => closeDropdown()));
    document.addEventListener('click', (event) => {
        if (!dropdown.contains(event.target)) closeDropdown();
    });
});

document.querySelectorAll('[data-project-tabs]').forEach((tabGroup) => {
    const tabs = [...tabGroup.querySelectorAll('[data-project-tab]')];
    const panels = [...tabGroup.querySelectorAll('[data-project-panel]')];

    if (!tabs.length || tabs.length !== panels.length) return;

    const activateTab = (activeTab, moveFocus = false) => {
        tabs.forEach((tab) => {
            const isActive = tab === activeTab;
            const panel = document.getElementById(tab.getAttribute('aria-controls'));

            tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
            tab.tabIndex = isActive ? 0 : -1;
            if (panel) panel.hidden = !isActive;
        });

        if (moveFocus) activeTab.focus();
    };

    tabs.forEach((tab, index) => {
        tab.addEventListener('click', () => activateTab(tab));
        tab.addEventListener('keydown', (event) => {
            let nextIndex;

            if (event.key === 'ArrowLeft') nextIndex = (index - 1 + tabs.length) % tabs.length;
            if (event.key === 'ArrowRight') nextIndex = (index + 1) % tabs.length;
            if (event.key === 'Home') nextIndex = 0;
            if (event.key === 'End') nextIndex = tabs.length - 1;

            if (nextIndex === undefined) return;

            event.preventDefault();
            activateTab(tabs[nextIndex], true);
        });
    });

    tabGroup.setAttribute('data-tabs-enhanced', 'true');
    activateTab(tabs.find((tab) => tab.getAttribute('aria-selected') === 'true') ?? tabs[0]);
});

document.querySelectorAll('[data-faq-group]').forEach((group) => {
    const triggers = [...group.querySelectorAll('[data-faq-trigger]')];

    triggers.forEach((trigger) => {
        const panel = document.getElementById(trigger.getAttribute('aria-controls'));
        trigger.setAttribute('aria-expanded', 'false');
        trigger.querySelector('[data-faq-icon]')?.classList.remove('rotate-45');
        if (panel) panel.hidden = true;

        trigger.addEventListener('click', () => {
            const willOpen = trigger.getAttribute('aria-expanded') !== 'true';

            triggers.forEach((otherTrigger) => {
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

if (footer && mobileContactBar && 'IntersectionObserver' in window) {
    const footerObserver = new IntersectionObserver(([entry]) => {
        footerIsVisible = entry.isIntersecting;
        updateMobileContactBar();
    }, { threshold: 0.05 });

    footerObserver.observe(footer);
}

updateMobileContactBar();
