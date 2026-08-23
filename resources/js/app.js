const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
const rootStyles = getComputedStyle(document.documentElement);

const cssTimeToMilliseconds = (property, fallback) => {
    const value = rootStyles.getPropertyValue(property).trim();

    if (value.endsWith('ms')) return Number.parseFloat(value);
    if (value.endsWith('s')) return Number.parseFloat(value) * 1000;

    return fallback;
};

const motion = {
    fast: cssTimeToMilliseconds('--motion-fast', 160),
    standard: cssTimeToMilliseconds('--motion-standard', 240),
    reveal: cssTimeToMilliseconds('--motion-reveal', 520),
    heroSettle: cssTimeToMilliseconds('--motion-hero-settle', 760),
    stagger: cssTimeToMilliseconds('--motion-stagger', 80),
    distance: rootStyles.getPropertyValue('--motion-distance').trim() || '16px',
    easeStandard: rootStyles.getPropertyValue('--motion-ease-standard').trim() || 'ease-out',
    easeEmphasised: rootStyles.getPropertyValue('--motion-ease-emphasised').trim() || 'ease-out',
};

const canAnimate = () => !reducedMotion.matches && typeof Element.prototype.animate === 'function';
const activeHeroAnimations = new Set();

document.querySelectorAll('[data-hero-sequence]').forEach((sequence) => {
    if (!canAnimate()) return;

    const heading = sequence.querySelector('h1');
    const candidates = [
        sequence.querySelector('.eyebrow'),
        heading,
        heading?.nextElementSibling?.matches('p') ? heading.nextElementSibling : null,
        ...sequence.querySelectorAll('[data-hero-item]'),
    ];
    const items = [...new Set(candidates.filter(Boolean))];

    items.forEach((item, index) => {
        const isMedia = item.hasAttribute('data-hero-media');
        const animation = item.animate(
            isMedia
                ? [
                      { opacity: 0, transform: 'scale(0.985)' },
                      { opacity: 1, transform: 'scale(1)' },
                  ]
                : [
                      { opacity: 0, transform: `translateY(${motion.distance})` },
                      { opacity: 1, transform: 'translateY(0)' },
                  ],
            {
                duration: isMedia ? motion.heroSettle : motion.reveal,
                delay: Math.min(index, 4) * motion.stagger,
                easing: isMedia ? motion.easeEmphasised : motion.easeStandard,
                fill: 'backwards',
            },
        );

        activeHeroAnimations.add(animation);
        animation.addEventListener('finish', () => activeHeroAnimations.delete(animation), { once: true });
        animation.addEventListener('cancel', () => activeHeroAnimations.delete(animation), { once: true });
    });
});

let revealObserver;
const revealElements = [...document.querySelectorAll('[data-reveal]')].filter(
    (element) => !element.closest('[data-hero-sequence]'),
);

document.querySelectorAll('[data-reveal-group]').forEach((group) => {
    [...group.querySelectorAll(':scope > [data-reveal]')].slice(0, 6).forEach((element, index) => {
        element.style.setProperty('--motion-order', index);
    });
});

const settleReveal = (element) => {
    element.setAttribute('data-reveal-state', 'settled');
};

if ('IntersectionObserver' in window && !reducedMotion.matches) {
    revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                revealObserver.unobserve(entry.target);
                const handleRevealEnd = (event) => {
                    if (event.propertyName !== 'opacity') return;

                    entry.target.removeEventListener('transitionend', handleRevealEnd);
                    settleReveal(entry.target);
                };
                entry.target.addEventListener('transitionend', handleRevealEnd);

                window.requestAnimationFrame(() => {
                    entry.target.setAttribute('data-reveal-state', 'visible');
                });
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -8% 0px' },
    );

    revealElements.forEach((element) => {
        const bounds = element.getBoundingClientRect();
        const isInitiallyVisible = bounds.bottom >= 0 && bounds.top <= window.innerHeight * 0.92;

        if (isInitiallyVisible) {
            settleReveal(element);
            return;
        }

        element.setAttribute('data-reveal-state', 'pending');
        revealObserver.observe(element);
    });
}

const siteHeader = document.querySelector('[data-site-header]');
const headerSentinel = document.querySelector('[data-header-sentinel]');

if (siteHeader && headerSentinel && 'IntersectionObserver' in window) {
    const headerObserver = new IntersectionObserver(([entry]) => {
        siteHeader.setAttribute('data-scrolled', entry.isIntersecting ? 'false' : 'true');
    });

    headerObserver.observe(headerSentinel);
}

const menuRoot = document.querySelector('[data-menu-root]');
const menuPanel = document.querySelector('[data-menu-panel]');
const menuOpen = document.querySelector('[data-menu-open]');
const menuClose = document.querySelector('[data-menu-close]');
const menuBackdrop = document.querySelector('[data-menu-backdrop]');

if (menuRoot && menuPanel && menuOpen && menuClose && menuBackdrop) {
    let closeTimer;
    let closeTransitionHandler;
    let menuIsOpen = false;
    let previousBodyOverflow = '';

    const desktopBreakpoint = window.matchMedia('(min-width: 64rem)');
    const focusableSelector = 'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])';

    const cancelPendingClose = () => {
        window.clearTimeout(closeTimer);
        if (closeTransitionHandler) menuPanel.removeEventListener('transitionend', closeTransitionHandler);
        closeTransitionHandler = undefined;
    };

    const hideMenu = () => {
        cancelPendingClose();
        menuRoot.classList.add('hidden');
        menuRoot.setAttribute('inert', '');
    };

    const openMenu = () => {
        cancelPendingClose();
        if (menuIsOpen) return;

        menuIsOpen = true;
        previousBodyOverflow = document.body.style.overflow;
        menuRoot.classList.remove('hidden');
        menuRoot.removeAttribute('inert');
        menuRoot.setAttribute('aria-hidden', 'false');
        menuOpen.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';

        window.requestAnimationFrame(() => {
            menuRoot.setAttribute('data-open', 'true');
            menuClose.focus();
        });
    };

    const closeMenu = ({ immediate = false, returnFocus = true } = {}) => {
        cancelPendingClose();
        if (!menuIsOpen && !immediate) return;

        menuIsOpen = false;
        menuRoot.setAttribute('data-open', 'false');
        menuOpen.setAttribute('aria-expanded', 'false');
        menuRoot.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = previousBodyOverflow;

        if (returnFocus && !desktopBreakpoint.matches) menuOpen.focus();

        if (immediate || reducedMotion.matches) {
            hideMenu();
            return;
        }

        closeTransitionHandler = (event) => {
            if (event.propertyName === 'transform') hideMenu();
        };
        menuPanel.addEventListener('transitionend', closeTransitionHandler);
        closeTimer = window.setTimeout(hideMenu, motion.standard + 80);
    };

    menuOpen.addEventListener('click', openMenu);
    menuClose.addEventListener('click', () => closeMenu());
    menuBackdrop.addEventListener('click', () => closeMenu());
    menuRoot.querySelectorAll('[data-menu-link]').forEach((link) => {
        link.addEventListener('click', () => closeMenu({ returnFocus: false }));
    });

    menuRoot.addEventListener('keydown', (event) => {
        if (!menuIsOpen) return;

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
        if (event.matches && menuIsOpen) closeMenu({ immediate: true, returnFocus: false });
    });
}

document.querySelectorAll('[data-divisions-dropdown]').forEach((dropdown) => {
    const toggle = dropdown.querySelector('[data-divisions-toggle]');
    const menu = dropdown.querySelector('[data-divisions-menu]');
    const icon = dropdown.querySelector('[data-divisions-icon]');

    if (!toggle || !menu) return;

    const links = [...menu.querySelectorAll('[data-divisions-link]')];
    let menuAnimation;

    const stopMenuAnimation = () => {
        menuAnimation?.cancel();
        menuAnimation = undefined;
    };

    const closeDropdown = ({ returnFocus = false } = {}) => {
        if (menu.classList.contains('hidden')) return;

        stopMenuAnimation();
        toggle.setAttribute('aria-expanded', 'false');
        icon?.classList.remove('rotate-180');
        if (returnFocus) toggle.focus();

        if (!canAnimate()) {
            menu.classList.add('hidden');
            return;
        }

        menuAnimation = menu.animate(
            [
                { opacity: 1, transform: 'translateY(0)' },
                { opacity: 0, transform: 'translateY(-6px)' },
            ],
            { duration: motion.fast, easing: motion.easeStandard },
        );
        menuAnimation.addEventListener(
            'finish',
            () => {
                if (toggle.getAttribute('aria-expanded') === 'false') menu.classList.add('hidden');
                stopMenuAnimation();
            },
            { once: true },
        );
    };

    const openDropdown = ({ focusFirst = false } = {}) => {
        stopMenuAnimation();
        menu.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
        icon?.classList.add('rotate-180');

        if (canAnimate()) {
            menuAnimation = menu.animate(
                [
                    { opacity: 0, transform: 'translateY(-6px)' },
                    { opacity: 1, transform: 'translateY(0)' },
                ],
                { duration: motion.fast, easing: motion.easeStandard },
            );
            menuAnimation.addEventListener('finish', stopMenuAnimation, { once: true });
        }

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

    const activateTab = (activeTab, { moveFocus = false, animate = true } = {}) => {
        let activePanel;

        tabs.forEach((tab) => {
            const isActive = tab === activeTab;
            const panel = document.getElementById(tab.getAttribute('aria-controls'));

            tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
            tab.tabIndex = isActive ? 0 : -1;
            if (panel) {
                panel.hidden = !isActive;
                if (isActive) activePanel = panel;
            }
        });

        if (animate && activePanel && canAnimate()) {
            activePanel.animate(
                [
                    { opacity: 0, transform: 'translateY(4px)' },
                    { opacity: 1, transform: 'translateY(0)' },
                ],
                { duration: motion.standard, easing: motion.easeStandard },
            );
        }

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
            activateTab(tabs[nextIndex], { moveFocus: true });
        });
    });

    tabGroup.setAttribute('data-tabs-enhanced', 'true');
    activateTab(tabs.find((tab) => tab.getAttribute('aria-selected') === 'true') ?? tabs[0], { animate: false });
});

document.querySelectorAll('[data-faq-group]').forEach((group) => {
    const triggers = [...group.querySelectorAll('[data-faq-trigger]')];
    const panelAnimations = new WeakMap();

    const stopPanelAnimation = (panel) => {
        panelAnimations.get(panel)?.cancel();
        panelAnimations.delete(panel);
    };

    const closeItem = (trigger, panel, animate = true) => {
        trigger.setAttribute('aria-expanded', 'false');
        trigger.querySelector('[data-faq-icon]')?.classList.remove('rotate-45');
        if (!panel || panel.hidden) return;

        stopPanelAnimation(panel);

        if (!animate || !canAnimate()) {
            panel.hidden = true;
            return;
        }

        const animation = panel.animate(
            [
                { opacity: 1, transform: 'translateY(0)' },
                { opacity: 0, transform: 'translateY(-4px)' },
            ],
            { duration: motion.fast, easing: motion.easeStandard },
        );
        panelAnimations.set(panel, animation);
        animation.addEventListener(
            'finish',
            () => {
                if (trigger.getAttribute('aria-expanded') === 'false') panel.hidden = true;
                panelAnimations.delete(panel);
            },
            { once: true },
        );
    };

    const openItem = (trigger, panel) => {
        if (!panel) return;

        stopPanelAnimation(panel);
        trigger.setAttribute('aria-expanded', 'true');
        trigger.querySelector('[data-faq-icon]')?.classList.add('rotate-45');
        panel.hidden = false;

        if (!canAnimate()) return;

        const animation = panel.animate(
            [
                { opacity: 0, transform: 'translateY(-4px)' },
                { opacity: 1, transform: 'translateY(0)' },
            ],
            { duration: motion.standard, easing: motion.easeStandard },
        );
        panelAnimations.set(panel, animation);
        animation.addEventListener('finish', () => panelAnimations.delete(panel), { once: true });
    };

    triggers.forEach((trigger) => {
        const panel = document.getElementById(trigger.getAttribute('aria-controls'));
        closeItem(trigger, panel, false);

        trigger.addEventListener('click', () => {
            const willOpen = trigger.getAttribute('aria-expanded') !== 'true';

            triggers.forEach((otherTrigger) => {
                if (otherTrigger === trigger) return;
                closeItem(otherTrigger, document.getElementById(otherTrigger.getAttribute('aria-controls')));
            });

            if (willOpen) openItem(trigger, panel);
            else closeItem(trigger, panel);
        });
    });

    group.setAttribute('data-faq-enhanced', 'true');
});

reducedMotion.addEventListener('change', (event) => {
    if (!event.matches) return;

    revealObserver?.disconnect();
    revealElements.forEach(settleReveal);
    activeHeroAnimations.forEach((animation) => animation.cancel());
});

document.querySelector('[data-form-error-summary]')?.focus();

document.querySelectorAll('[data-submit-once]').forEach((form) => {
    form.addEventListener('submit', () => {
        const button = form.querySelector('[data-submit-button]');
        const label = button?.querySelector('[data-submit-label]');

        if (!button || button.disabled) return;

        button.disabled = true;
        button.setAttribute('aria-disabled', 'true');

        if (label && button.dataset.pendingLabel) label.textContent = button.dataset.pendingLabel;
    });
});
