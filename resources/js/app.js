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
                panel.setAttribute('aria-hidden', isActive ? 'false' : 'true');
                if ('inert' in panel) panel.inert = !isActive;
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

document.querySelectorAll('[data-page-section-nav]').forEach((navigation) => {
    const links = [...navigation.querySelectorAll('[data-page-section-link]')];
    const currentLabel = navigation.querySelector('[data-page-section-current]');
    const sections = [...new Set(links.map((link) => link.hash))]
        .map((hash) => document.querySelector(hash))
        .filter((section) => section instanceof HTMLElement);

    if (links.length === 0 || sections.length === 0) return;

    const setActiveSection = (sectionId) => {
        const activeLink = links.find((link) => link.hash === `#${sectionId}`);

        links.forEach((link) => {
            if (link.hash === `#${sectionId}`) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        });

        if (currentLabel && activeLink) currentLabel.textContent = activeLink.textContent.trim();
    };

    links.forEach((link) => {
        link.addEventListener('click', () => {
            setActiveSection(link.hash.slice(1));
            link.closest('details')?.removeAttribute('open');
        });
    });

    const hashTarget = sections.find((section) => `#${section.id}` === window.location.hash);
    setActiveSection(hashTarget?.id ?? sections[0].id);

    if (!('IntersectionObserver' in window)) return;

    const sectionObserver = new IntersectionObserver(
        (entries) => {
            const visibleSection = entries
                .filter((entry) => entry.isIntersecting)
                .sort((first, second) => first.boundingClientRect.top - second.boundingClientRect.top)[0];

            if (visibleSection) setActiveSection(visibleSection.target.id);
        },
        { rootMargin: '-28% 0px -62% 0px', threshold: [0, 0.1] },
    );

    sections.forEach((section) => sectionObserver.observe(section));
});

document.querySelectorAll('[data-faq-group]').forEach((group) => {
    const triggers = [...group.querySelectorAll('[data-faq-trigger]')];
    const panelAnimations = new WeakMap();
    const singleOpen = group.dataset.faqSingleOpen !== 'false';

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

            if (singleOpen && willOpen) {
                triggers.forEach((otherTrigger) => {
                    if (otherTrigger === trigger) return;
                    closeItem(otherTrigger, document.getElementById(otherTrigger.getAttribute('aria-controls')));
                });
            }

            if (willOpen) openItem(trigger, panel);
            else closeItem(trigger, panel);
        });
    });

    group.setAttribute('data-faq-enhanced', 'true');
});

document.querySelectorAll('[data-faq-category-nav]').forEach((navigation) => {
    const links = [...navigation.querySelectorAll('[data-faq-category-link]')];
    const sections = links
        .map((link) => document.querySelector(link.hash))
        .filter((section) => section instanceof HTMLElement);

    if (links.length === 0 || sections.length === 0) return;

    const setActiveCategory = (sectionId) => {
        links.forEach((link) => {
            if (link.hash === `#${sectionId}`) link.setAttribute('aria-current', 'location');
            else link.removeAttribute('aria-current');
        });
    };

    links.forEach((link) => {
        link.addEventListener('click', () => setActiveCategory(link.hash.slice(1)));
    });

    const hashTarget = sections.find((section) => `#${section.id}` === window.location.hash);
    if (hashTarget) setActiveCategory(hashTarget.id);

    if (!('IntersectionObserver' in window)) return;

    const categoryObserver = new IntersectionObserver(
        (entries) => {
            const visibleSection = entries
                .filter((entry) => entry.isIntersecting)
                .sort((first, second) => first.boundingClientRect.top - second.boundingClientRect.top)[0];

            if (visibleSection) setActiveCategory(visibleSection.target.id);
        },
        { rootMargin: '-18% 0px -68% 0px', threshold: [0, 0.1] },
    );

    sections.forEach((section) => categoryObserver.observe(section));
});

reducedMotion.addEventListener('change', (event) => {
    if (!event.matches) return;

    revealObserver?.disconnect();
    revealElements.forEach(settleReveal);
    activeHeroAnimations.forEach((animation) => animation.cancel());
});

document.querySelector('[data-form-error-summary]')?.focus();

document.querySelectorAll('[data-submit-once]').forEach((form) => {
    const contactMethod = form.querySelector('[data-contact-method]');
    const conditionalContactFields = [...form.querySelectorAll('[data-conditional-contact]')];
    const asyncErrorSummary = form.querySelector('[data-async-error-summary]');
    const formKind = form.dataset.asyncForm;
    const successState = form.parentElement?.querySelector(`[data-async-success="${formKind}"]`);
    const submitButton = form.querySelector('[data-submit-button]');
    const submitLabel = submitButton?.querySelector('[data-submit-label]');
    const submitStatus = form.querySelector('[data-submit-status]');
    const originalSubmitLabel = submitLabel?.textContent;

    const fieldErrorElement = (field) => {
        const errorId = field.id ? `${field.id}_error` : `${formKind ?? 'form'}_${field.name}_error`;
        let error = form.querySelector(`#${errorId}`);

        if (!error) {
            error = document.createElement('p');
            error.id = errorId;
            error.className = 'form-error';
            error.dataset.generatedFieldError = field.name;
            const target = field.type === 'radio' ? field.closest('[role="radiogroup"]') : field;
            target?.insertAdjacentElement('afterend', error);
        }

        return error;
    };

    const fieldsForName = (name) => [...form.elements].filter((field) => field.name === name);

    const clearFieldError = (field) => {
        fieldsForName(field.name).forEach((item) => item.removeAttribute('aria-invalid'));
        const generated = form.querySelector(`[data-generated-field-error="${field.name}"]`);
        generated?.remove();

        if (field.id) {
            const serverError = form.querySelector(`#${field.id}_error`);
            if (serverError && !serverError.dataset.generatedFieldError) serverError.hidden = true;
        }
    };

    const setFieldError = (field, message) => {
        if (!field) return;
        const error = fieldErrorElement(field);
        error.hidden = false;
        error.textContent = message;

        fieldsForName(field.name).forEach((item) => {
            item.setAttribute('aria-invalid', 'true');
            const describedBy = new Set((item.getAttribute('aria-describedby') ?? '').split(/\s+/).filter(Boolean));
            describedBy.add(error.id);
            item.setAttribute('aria-describedby', [...describedBy].join(' '));
        });
    };

    const validationMessage = (field) => {
        if (field.validity.valueMissing) return field.dataset.requiredMessage ?? 'This field is required.';
        if (field.validity.typeMismatch) return field.dataset.invalidMessage ?? 'Please enter a valid value.';
        if (field.validity.tooShort) return field.dataset.invalidMessage ?? 'Please enter a complete value.';
        if (field.validity.rangeUnderflow) return 'Please choose today or a future date.';
        return field.dataset.invalidMessage ?? field.validationMessage;
    };

    const showSummary = (title, messages = []) => {
        if (!asyncErrorSummary) return;
        asyncErrorSummary.replaceChildren();
        const heading = document.createElement('p');
        heading.className = 'font-semibold text-ink-950';
        heading.textContent = title;
        asyncErrorSummary.append(heading);

        if (messages.length) {
            const list = document.createElement('ul');
            list.className = 'mt-2 list-disc space-y-1 pl-5 text-sm leading-6';
            messages.forEach((message) => {
                const item = document.createElement('li');
                item.textContent = message;
                list.append(item);
            });
            asyncErrorSummary.append(list);
        }

        asyncErrorSummary.hidden = false;
        asyncErrorSummary.focus();
    };

    const clearSummary = () => {
        if (asyncErrorSummary) asyncErrorSummary.hidden = true;
    };

    const updateConditionalContactFields = () => {
        const selectedMethod = contactMethod?.value;

        conditionalContactFields.forEach((field) => {
            const method = field.dataset.conditionalContact;
            const isRequired = selectedMethod === method;
            const requirement = form.querySelector(`[data-contact-requirement][data-method="${method}"]`);
            const group = field.closest('[data-conditional-contact-group]');

            field.required = isRequired;
            field.disabled = !isRequired;
            field.setAttribute('aria-required', isRequired ? 'true' : 'false');
            if (group) {
                group.hidden = !isRequired;
                group.setAttribute('aria-hidden', isRequired ? 'false' : 'true');
            }

            if (requirement) {
                requirement.textContent = isRequired
                    ? '(required for your selected contact method)'
                    : `(required when ${method} is selected)`;
            }
        });
    };

    contactMethod?.addEventListener('change', updateConditionalContactFields);
    updateConditionalContactFields();

    const enquiryType = form.querySelector('[data-enquiry-type]');
    const engineeringFields = form.querySelector('[data-engineering-fields]');
    const engineeringInputs = [...(engineeringFields?.querySelectorAll('[data-engineering-input]') ?? [])];

    const updateEngineeringFields = () => {
        if (!engineeringFields) return;

        const isEngineeringEnquiry = enquiryType?.value === 'Engineering & Construction';

        engineeringFields.hidden = !isEngineeringEnquiry;
        engineeringFields.setAttribute('aria-hidden', isEngineeringEnquiry ? 'false' : 'true');
        engineeringInputs.forEach((input) => {
            input.disabled = !isEngineeringEnquiry;
        });
    };

    enquiryType?.addEventListener('change', updateEngineeringFields);
    updateEngineeringFields();

    const stepElements = [...form.querySelectorAll('[data-form-step]')];
    const stepNumbers = [...new Set(stepElements.map((step) => Number(step.dataset.formStep)))].sort((a, b) => a - b);
    let activeStepIndex = 0;

    const stepIsConditionallyHidden = (step) =>
        step.hasAttribute('data-engineering-fields') && enquiryType?.value !== 'Engineering & Construction';

    const activateStep = (index, { focus = false } = {}) => {
        if (!stepNumbers.length) return;
        activeStepIndex = Math.max(0, Math.min(index, stepNumbers.length - 1));
        const activeNumber = stepNumbers[activeStepIndex];

        stepElements.forEach((step) => {
            step.hidden = Number(step.dataset.formStep) !== activeNumber || stepIsConditionallyHidden(step);
        });
        updateConditionalContactFields();

        const progress = form.querySelector('[data-step-progress]');
        const label = form.querySelector('[data-step-label]');
        const name = form.querySelector('[data-step-name]');
        const bar = form.querySelector('[data-step-progress-bar]');
        const activeElements = stepElements.filter((step) => Number(step.dataset.formStep) === activeNumber);
        const stepTitle = activeElements.find((step) => step.dataset.stepTitle)?.dataset.stepTitle ?? '';

        if (progress) progress.hidden = false;
        if (label) label.textContent = `Step ${activeStepIndex + 1} of ${stepNumbers.length}`;
        if (name) name.textContent = stepTitle;
        if (bar) bar.style.width = `${((activeStepIndex + 1) / stepNumbers.length) * 100}%`;
        if (focus) activeElements[0]?.querySelector('input, select, textarea, button')?.focus();
    };

    const validateStep = (stepIndex) => {
        const stepNumber = stepNumbers[stepIndex];
        const candidates = stepElements
            .filter((step) => Number(step.dataset.formStep) === stepNumber && !step.hidden)
            .flatMap((step) => [...step.querySelectorAll('input, select, textarea')])
            .filter((field) => !field.disabled && field.type !== 'hidden');
        const checkedRadioNames = new Set();
        const errors = [];

        candidates.forEach((field) => {
            if (field.type === 'radio') {
                if (checkedRadioNames.has(field.name)) return;
                checkedRadioNames.add(field.name);
                const radioGroup = fieldsForName(field.name);
                if (field.required && !radioGroup.some((radio) => radio.checked)) {
                    const message = field.dataset.requiredMessage ?? 'Please choose an option.';
                    setFieldError(field, message);
                    errors.push(message);
                } else clearFieldError(field);
                return;
            }

            if (!field.checkValidity()) {
                const message = validationMessage(field);
                setFieldError(field, message);
                errors.push(message);
            } else clearFieldError(field);
        });

        if (errors.length) showSummary('Please check the highlighted fields.', [...new Set(errors)]);
        else clearSummary();

        return errors.length === 0;
    };

    if (stepNumbers.length > 1) {
        form.setAttribute('data-steps-enhanced', 'true');

        stepNumbers.forEach((stepNumber, index) => {
            const elements = stepElements.filter((step) => Number(step.dataset.formStep) === stepNumber);
            const lastElement = elements.at(-1);
            if (!lastElement) return;

            const navigation = document.createElement('div');
            navigation.className = 'mt-7 flex flex-col-reverse gap-3 border-t border-ink-200 pt-6 sm:flex-row sm:justify-between';
            navigation.dataset.stepNavigation = '';

            if (index > 0) {
                const back = document.createElement('button');
                back.type = 'button';
                back.className = 'inline-flex min-h-12 items-center justify-center rounded-xl border border-ink-200 bg-white px-5 py-3 text-sm font-semibold hover:border-brand-700';
                back.textContent = 'Back';
                back.addEventListener('click', () => activateStep(index - 1, { focus: true }));
                navigation.append(back);
            } else navigation.append(document.createElement('span'));

            if (index < stepNumbers.length - 1) {
                const next = document.createElement('button');
                next.type = 'button';
                next.className = 'inline-flex min-h-12 items-center justify-center rounded-xl bg-brand-700 px-5 py-3 text-sm font-semibold text-white hover:bg-brand-800';
                next.textContent = 'Continue';
                next.addEventListener('click', () => {
                    if (validateStep(index)) activateStep(index + 1, { focus: true });
                });
                navigation.append(next);
            }

            lastElement.append(navigation);
        });

        const firstServerError = form.querySelector('[aria-invalid="true"]');
        const errorStep = Number(firstServerError?.closest('[data-form-step]')?.dataset.formStep);
        const initialIndex = stepNumbers.includes(errorStep) ? stepNumbers.indexOf(errorStep) : 0;
        activateStep(initialIndex);
    }

    form.querySelectorAll('input, select, textarea').forEach((field) => {
        field.addEventListener('input', () => {
            if (field.checkValidity()) clearFieldError(field);
        });
        field.addEventListener('change', () => {
            if (field.checkValidity()) clearFieldError(field);
        });
    });

    const setSubmitting = (isSubmitting) => {
        if (!submitButton) return;
        form.setAttribute('aria-busy', isSubmitting ? 'true' : 'false');
        submitButton.disabled = isSubmitting;
        submitButton.setAttribute('aria-disabled', isSubmitting ? 'true' : 'false');
        submitButton.setAttribute('aria-busy', isSubmitting ? 'true' : 'false');
        if (submitLabel) submitLabel.textContent = isSubmitting ? submitButton.dataset.pendingLabel : originalSubmitLabel;
        if (submitStatus) submitStatus.textContent = isSubmitting ? submitButton.dataset.pendingLabel : '';
    };

    const showSuccess = (payload) => {
        if (!successState) return;
        const receipt = payload.receipt ?? {};
        const assign = (selector, value) => {
            const element = successState.querySelector(selector);
            if (element) element.textContent = value ?? '';
        };
        assign('[data-success-message]', payload.message);
        assign('[data-success-reference]', receipt.reference);
        assign('[data-success-date]', receipt.preferred_date);
        assign('[data-success-time]', receipt.preferred_time);
        assign('[data-success-interest]', receipt.interest_type);
        assign('[data-success-enquiry]', receipt.enquiry_type);
        form.hidden = true;
        successState.hidden = false;
        successState.focus();
    };

    const showServerErrors = (errors) => {
        const messages = [];
        let firstErrorStep;

        Object.entries(errors).forEach(([name, fieldMessages]) => {
            const field = fieldsForName(name)[0];
            const message = fieldMessages[0];
            if (field) {
                setFieldError(field, message);
                const stepNumber = Number(field.closest('[data-form-step]')?.dataset.formStep);
                if (stepNumbers.includes(stepNumber)) {
                    const index = stepNumbers.indexOf(stepNumber);
                    firstErrorStep = firstErrorStep === undefined ? index : Math.min(firstErrorStep, index);
                }
            }
            messages.push(message);
        });

        if (firstErrorStep !== undefined) activateStep(firstErrorStep);
        showSummary('Please check the highlighted fields.', messages);
    };

    form.addEventListener('submit', async (event) => {
        if (!form.dataset.asyncForm) return;
        event.preventDefault();
        if (stepNumbers.length && !stepNumbers.every((_, index) => validateStep(index))) return;
        if (!submitButton || submitButton.disabled) return;

        clearSummary();
        setSubmitting(true);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const payload = await response.json().catch(() => ({}));

            if (response.status === 422) {
                showServerErrors(payload.errors ?? {});
                return;
            }
            if (response.status === 429) {
                showSummary('Too many requests were sent. Please wait a minute and try again.');
                return;
            }
            if (!response.ok) throw new Error('Submission failed');

            showSuccess(payload);
        } catch {
            showSummary('We could not send your request. Please check your connection and try again. Your entries are still here.');
        } finally {
            setSubmitting(false);
        }
    });

    successState?.querySelector('[data-form-reset]')?.addEventListener('click', () => {
        form.reset();
        const token = form.querySelector('[data-submission-token], [name="submission_token"]');
        if (token && crypto.randomUUID) token.value = crypto.randomUUID();
        successState.hidden = true;
        form.hidden = false;
        updateConditionalContactFields();
        updateEngineeringFields();
        activateStep(0, { focus: true });
    });
});

const inspectionDialog = document.querySelector('[data-inspection-dialog]');

if (inspectionDialog && typeof inspectionDialog.showModal === 'function') {
    const inspectionPath = new URL(inspectionDialog.dataset.inspectionPath, window.location.href).pathname;
    let returnFocus;
    let previousBodyOverflow = '';

    const openInspectionDialog = (trigger) => {
        returnFocus = trigger;
        previousBodyOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        inspectionDialog.showModal();
        inspectionDialog.querySelector('form input:not([type="hidden"]), form select, form textarea')?.focus();
    };

    const closeInspectionDialog = () => inspectionDialog.close();

    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href]');
        if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) return;
        const destination = new URL(link.href, window.location.href);
        if (destination.origin !== window.location.origin || destination.pathname !== inspectionPath) return;
        event.preventDefault();
        openInspectionDialog(link);
    });

    inspectionDialog.querySelectorAll('[data-inspection-dialog-close]').forEach((button) => {
        button.addEventListener('click', closeInspectionDialog);
    });
    inspectionDialog.addEventListener('click', (event) => {
        if (event.target === inspectionDialog) closeInspectionDialog();
    });
    inspectionDialog.addEventListener('close', () => {
        document.body.style.overflow = previousBodyOverflow;
        const successState = inspectionDialog.querySelector('[data-async-success="inspection"]');
        if (successState && !successState.hidden) successState.querySelector('[data-form-reset]')?.click();
        const focusTarget = returnFocus?.closest('[data-menu-root]') ? menuOpen : returnFocus;
        focusTarget?.focus();
    });
}

document.querySelectorAll('[data-autoplay-preview]').forEach((video) => {
    if (reducedMotion.matches) {
        video.pause();
        return;
    }
    video.play().catch(() => {});
});
