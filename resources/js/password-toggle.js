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

        button.innerHTML = passwordVisible ? '<i class="fa-solid fa-eye"></i>' : '<i class="fa-solid fa-eye-slash"></i>';

        button.setAttribute(
            'aria-label',
            passwordVisible ? 'Mostrar senha' : 'Ocultar senha'
        );
    });

});
