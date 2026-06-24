import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Disable autocomplete globally on all form inputs (both static and dynamically added ones)
(function() {
    const disableAutocomplete = (el) => {
        if (el.tagName === 'INPUT' || el.tagName === 'FORM') {
            el.setAttribute('autocomplete', 'off');
        }
        if (el.querySelectorAll) {
            el.querySelectorAll('input, form').forEach(child => {
                child.setAttribute('autocomplete', 'off');
            });
        }
    };

    // Run on initial load
    if (document.readyState === 'loading') {
        document.addEventListener("DOMContentLoaded", () => disableAutocomplete(document.body));
    } else {
        disableAutocomplete(document.body);
    }

    // Monitor for dynamically added forms/inputs (such as those rendered via Alpine.js)
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    disableAutocomplete(node);
                }
            });
        });
    });
    observer.observe(document.documentElement, { childList: true, subtree: true });
})();

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
