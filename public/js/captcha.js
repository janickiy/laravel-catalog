(() => {
    'use strict';

    document.querySelectorAll('[data-captcha]').forEach((block) => {
        const button = block.querySelector('[data-captcha-refresh]');
        const image = block.querySelector('.captcha-block__image img');
        const input = block.querySelector('input[name="captcha"]');
        const status = block.querySelector('[data-captcha-status]');
        if (!button || !image || !input || !status || !input.form) return;

        const endpoint = new URL(image.src, window.location.href);
        let busy = false;
        let failed = false;
        let sequence = 0;
        button.hidden = false;
        if (!image.complete) {
            // Let the initial image finish writing its challenge to the session.
            button.disabled = true;
            const initialImageReady = () => {
                button.disabled = false;
                image.removeEventListener('load', initialImageReady);
                image.removeEventListener('error', initialImageReady);
            };
            image.addEventListener('load', initialImageReady);
            image.addEventListener('error', initialImageReady);
        }

        input.form.addEventListener('submit', (event) => {
            if (busy || failed) {
                event.preventDefault();
                input.reportValidity();
            }
        });

        button.addEventListener('click', async () => {
            if (busy) return;
            busy = true;
            failed = false;
            button.disabled = true;
            block.setAttribute('aria-busy', 'true');
            input.value = '';
            input.setCustomValidity(block.dataset.loading);
            status.textContent = block.dataset.loading;
            status.classList.remove('is-error');
            status.hidden = false;

            let objectUrl = null;
            try {
                const url = new URL(endpoint);
                url.searchParams.set('refresh', `${Date.now()}-${++sequence}`);
                // Fetch exactly once: another GET would invalidate this challenge.
                const response = await fetch(url, {credentials: 'same-origin', cache: 'no-store'});
                if (!response.ok || !(response.headers.get('content-type') || '').startsWith('image/')) {
                    throw new Error('Captcha image unavailable');
                }
                objectUrl = URL.createObjectURL(await response.blob());
                await new Promise((resolve, reject) => {
                    image.onload = resolve;
                    image.onerror = reject;
                    image.src = objectUrl;
                });
                image.hidden = false;
                input.setCustomValidity('');
                input.classList.remove('is-invalid');
                input.setAttribute('aria-invalid', 'false');
                const previousError = block.querySelector('#captcha-error');
                if (previousError) previousError.hidden = true;
                status.textContent = block.dataset.updated;
                input.focus();
            } catch (_) {
                // The server may already have replaced the old challenge.
                failed = true;
                image.hidden = true;
                input.setCustomValidity(block.dataset.error);
                input.setAttribute('aria-invalid', 'true');
                status.textContent = block.dataset.error;
                status.classList.add('is-error');
            } finally {
                image.onload = null;
                image.onerror = null;
                if (objectUrl) URL.revokeObjectURL(objectUrl);
                busy = false;
                button.disabled = false;
                block.setAttribute('aria-busy', 'false');
            }
        });
    });
})();
