document.addEventListener('DOMContentLoaded', function() {
    var label = document.querySelector('label[for="contact_imageFile_delete"]');
    var checkbox = document.querySelector('#contact_imageFile_delete');

    if (label && checkbox) {
        label.textContent = 'Supprimer l\'image';
        label.classList.add('form-check-label'); // Ajoute une classe Bootstrap au label
        checkbox.classList.add('form-check-input'); // Ajoute une classe Bootstrap à la checkbox
    }
});
