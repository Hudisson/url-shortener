import './bootstrap';
import './password-toggle';

const copyButton = document.getElementById('copy-button');
const shortUrlInput = document.getElementById('short-url');

if (copyButton && shortUrlInput) {
    copyButton.addEventListener('click', async () => {

        try {
            await navigator.clipboard.writeText(shortUrlInput.value);

            copyButton.textContent = 'Copiado!';
            copyButton.classList.add('copied');

            setTimeout(() => {
                copyButton.textContent = 'Copiar URL';
                copyButton.classList.remove('copied');
            }, 2000);

        } catch (error) {
            console.error('Não foi possível copiar a URL:', error);
        }
    });
}
