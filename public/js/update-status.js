(() => {
    'use strict';

    const badge = document.querySelector('[data-update-status]');
    if (!badge || badge.dataset.statusKnown === 'true' || !badge.dataset.statusUrl) return;

    const controller = new AbortController();
    const timeout = window.setTimeout(() => controller.abort(), 20000);

    fetch(badge.dataset.statusUrl, {
        method: 'GET',
        credentials: 'same-origin',
        headers: { Accept: 'application/json' },
        cache: 'no-store',
        redirect: 'error',
        signal: controller.signal,
    })
        .then((response) => {
            if (!response.ok) throw new Error('Update status unavailable');
            return response.json();
        })
        .then((status) => {
            const available = status && status.available === true
                && typeof status.version === 'string' && /^\d+\.\d+\.\d+$/.test(status.version);
            badge.hidden = !available;
            if (available) {
                const label = badge.dataset.titleTemplate.replace('{version}', status.version);
                badge.setAttribute('title', label);
                badge.setAttribute('aria-label', label);
            }
        })
        .catch(() => { badge.hidden = true; })
        .finally(() => window.clearTimeout(timeout));
})();
