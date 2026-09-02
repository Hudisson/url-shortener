const deleteModal = document.getElementById('delete-modal');
const cancelButton = document.getElementById('delete-modal-cancel');
const confirmButton = document.getElementById('delete-modal-confirm');

let formToDelete = null;

document.querySelectorAll('.delete-url-form').forEach((form) => {
    const button = form.querySelector('.btn-delete-url');

    button.addEventListener('click', () => {
        formToDelete = form;

        deleteModal.classList.add('is-open');
        deleteModal.setAttribute('aria-hidden', 'false');
    });
});

cancelButton.addEventListener('click', () => {
    closeDeleteModal();
});

confirmButton.addEventListener('click', () => {
    if (formToDelete !== null) {
        formToDelete.submit();
    }
});

deleteModal.addEventListener('click', (event) => {
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
});

function closeDeleteModal() {
    deleteModal.classList.remove('is-open');
    deleteModal.setAttribute('aria-hidden', 'true');

    formToDelete = null;
}
