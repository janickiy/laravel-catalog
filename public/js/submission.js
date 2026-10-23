(() => {
    'use strict';

    document.querySelectorAll('[data-screenshot-upload]').forEach((upload) => {
        const input = upload.querySelector('[data-screenshot-input]');
        const image = upload.querySelector('[data-screenshot-image]');
        const preview = upload.querySelector('[data-screenshot-preview]');
        const selection = upload.querySelector('[data-screenshot-selection]');
        const filename = upload.querySelector('[data-screenshot-filename]');
        const error = upload.querySelector('[data-screenshot-error]');
        const clear = upload.querySelector('[data-screenshot-clear]');
        if (!input || !image || !preview || !selection || !filename || !error || !clear) return;

        let objectUrl = null;
        let revision = 0;
        const serverInvalid = input.getAttribute('aria-invalid') === 'true';
        const releaseUrl = () => {
            if (objectUrl) URL.revokeObjectURL(objectUrl);
            objectUrl = null;
        };
        const resetPreview = () => {
            revision++;
            image.onload = null;
            image.onerror = null;
            image.removeAttribute('src');
            releaseUrl();
            preview.hidden = true;
            selection.hidden = true;
            filename.textContent = '';
            error.textContent = '';
            error.hidden = true;
            input.setCustomValidity('');
            input.setAttribute('aria-invalid', serverInvalid ? 'true' : 'false');
        };
        const report = (message) => {
            error.textContent = message;
            error.hidden = false;
            preview.hidden = true;
            input.setCustomValidity(message);
            input.setAttribute('aria-invalid', 'true');
        };
        input.addEventListener('change', () => {
            resetPreview();
            const file = input.files && input.files[0];
            if (!file) return;
            selection.hidden = false;
            filename.textContent = file.name;
            if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type)) {
                report(upload.dataset.errorType);
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                report(upload.dataset.errorSize);
                return;
            }
            const currentRevision = revision;
            objectUrl = URL.createObjectURL(file);
            image.onload = () => {
                if (revision !== currentRevision) return;
                if (!image.naturalWidth || !image.naturalHeight) report(upload.dataset.errorRead);
                else if (image.naturalWidth > 6000 || image.naturalHeight > 6000) report(upload.dataset.errorDimensions);
                else {
                    preview.hidden = false;
                    input.setAttribute('aria-invalid', 'false');
                }
                releaseUrl();
            };
            image.onerror = () => {
                if (revision !== currentRevision) return;
                report(upload.dataset.errorRead);
                releaseUrl();
            };
            image.src = objectUrl;
        });
        clear.addEventListener('click', () => {
            input.value = '';
            resetPreview();
            input.focus();
        });
        if (input.form) input.form.addEventListener('reset', resetPreview);
        window.addEventListener('pagehide', releaseUrl);
    });
})();
