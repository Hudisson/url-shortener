document.addEventListener('DOMContentLoaded', () => {

    const accountMenu = document.querySelector('.account-menu');

    if (!accountMenu) {
        return;
    }

    const accountButton = accountMenu.querySelector('.account-button');

    if (!accountButton) {
        return;
    }

    accountButton.addEventListener('click', (event) => {

        event.stopPropagation();

        const isOpen = accountMenu.classList.toggle('is-open');

        accountButton.setAttribute(
            'aria-expanded',
            String(isOpen)
        );
    });


    document.addEventListener('click', (event) => {

        if (!accountMenu.contains(event.target)) {

            accountMenu.classList.remove('is-open');

            accountButton.setAttribute(
                'aria-expanded',
                'false'
            );
        }
    });

});
