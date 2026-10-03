document.addEventListener('click', (event) => {
    if (!(event.target instanceof Element)) {
        return;
    }

    const closeButton = event.target.closest('[data-dismiss-alert]');
    closeButton?.closest('[role="alert"]')?.remove();
});
