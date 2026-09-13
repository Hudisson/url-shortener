import './bootstrap';
import './password-toggle';
import QRCode from 'qrcode';

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

    const qrModal = document.getElementById('qr-modal');
    const qrCanvas = document.getElementById('qr-code-canvas');
    const qrModalUrl = document.getElementById('qr-modal-url');
    const qrModalClose = document.getElementById('qr-modal-close');

    if (qrModal && qrCanvas && qrModalUrl && qrModalClose) {
        let currentShortUrl = '';

        document.querySelectorAll('.btn-qr-url').forEach((qrButton) => {
            qrButton.addEventListener('click', async () => {
                currentShortUrl = qrButton.dataset.shortUrl ?? '';

                if (!currentShortUrl) {
                    return;
                }

                qrModalUrl.textContent = currentShortUrl;
                const qrCanvasImage = document.createElement('canvas');
                await QRCode.toCanvas(qrCanvasImage, currentShortUrl, {
                    width: 280,
                    margin: 2,
                    errorCorrectionLevel: 'M',
                });

                const context = qrCanvas.getContext('2d');

                if (!context) {
                    return;
                }

                context.fillStyle = '#ffffff';
                context.fillRect(0, 0, qrCanvas.width, qrCanvas.height);
                context.drawImage(qrCanvasImage, 0, 0);
                context.fillStyle = '#18181b';
                context.font = 'bold 18px Arial, sans-serif';
                context.textAlign = 'center';
                context.textBaseline = 'middle';
                context.fillText('URL Shortener', qrCanvas.width / 2, 300);

                qrModal.classList.add('is-open');
                qrModal.setAttribute('aria-hidden', 'false');
            });
        });

        document.getElementById('qr-download-png')?.addEventListener('click', () => {
            downloadQrCode('png');
        });

        document.getElementById('qr-download-jpeg')?.addEventListener('click', () => {
            downloadQrCode('jpeg');
        });

        qrModalClose.addEventListener('click', closeQrModal);

        qrModal.addEventListener('click', (event) => {
            if (event.target === qrModal) {
                closeQrModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && qrModal.classList.contains('is-open')) {
                closeQrModal();
            }
        });

        function downloadQrCode(format) {
            const link = document.createElement('a');
            link.download = `qr-code-${new URL(currentShortUrl).hostname}.${format}`;
            link.href = qrCanvas.toDataURL(`image/${format}`);
            link.click();
        }

        function closeQrModal() {
            qrModal.classList.remove('is-open');
            qrModal.setAttribute('aria-hidden', 'true');
            currentShortUrl = '';
        }
    }

});
