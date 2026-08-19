const passwordToggleButtons = document.querySelectorAll('.password-toggle');

passwordToggleButtons.forEach((button) => {

    button.addEventListener('click', () => {

        const targetId = button.dataset.target;
        const input = document.getElementById(targetId);

        if (!input) {
            return;
        }

        const passwordVisible = input.type === 'text';

        input.type = passwordVisible ? 'password' : 'text';
        
        button.innerHTML = passwordVisible ? '&#128065;' : '&#128584;';

        button.setAttribute(
            'aria-label',
            passwordVisible ? 'Mostrar senha' : 'Ocultar senha'
        );
    });

});
