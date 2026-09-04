import './bootstrap';
import './password-toggle';

document.querySelectorAll('.copy-url-button').forEach((copyButton) => {

    copyButton.addEventListener('click', async () => {

        let shortUrl = null;

        const shortUrlInput = document.getElementById('short-url');

        if (shortUrlInput) {
            shortUrl = shortUrlInput.value;
        }

        if (!shortUrl) {
            const shortUrlItem =
                copyButton.closest('.short-url-item');

            const shortUrlLink =
                shortUrlItem?.querySelector('.short-url-code');

            if (shortUrlLink) {
                shortUrl = shortUrlLink.href;
            }
        }

        if (!shortUrl) {
            return;
        }

        try {
            await navigator.clipboard.writeText(shortUrl);

            copyButton.innerHTML =
                'Copiado! <i class="fa-solid fa-check"></i>';

            copyButton.classList.add('copied');

            setTimeout(() => {

                if (copyButton.id === 'copy-button') {
                    copyButton.innerHTML =
                        'Copiar URL <i class="fa-solid fa-copy"></i>';
                } else {
                    copyButton.innerHTML =
                        'Copiar <i class="fa-solid fa-copy"></i>';
                }

                copyButton.classList.remove('copied');

            }, 2000);

        } catch (error) {
            console.error(
                'Não foi possível copiar a URL:',
                error
            );
        }
    });

});

