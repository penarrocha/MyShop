function bindRecaptchaToken(formSelector, action) {
    const form = document.querySelector(formSelector);
    if (!form || form.dataset.recaptchaRequired !== '1') return;

    const siteKey = document.querySelector('meta[name="recaptcha-site-key"]')?.content;
    const tokenInput = form.querySelector('#recaptcha_token');

    if (!siteKey || !tokenInput || typeof grecaptcha === 'undefined') return;

    console.log('recaptchaRequired=', form.dataset.recaptchaRequired);


    form.addEventListener('submit', async (e) => {
        // Evita doble submit
        if (form.dataset.recaptchaReady === '1') return;

        e.preventDefault();

        grecaptcha.ready(async () => {
            try {
                const token = await grecaptcha.execute(siteKey, { action });
                tokenInput.value = token;

                form.dataset.recaptchaReady = '1';
                form.submit();
            } catch (err) {
                // si falla, deja que backend lo rechace con mensaje genérico
                form.dataset.recaptchaReady = '0';
                form.submit();
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    bindRecaptchaToken('#registerForm', 'register');
    bindRecaptchaToken('#loginForm', 'login');
});

export { };
