document.addEventListener("DOMContentLoaded", function() {
    var imageInput = document.querySelector('#contact_imageFile_file');
    var imagePreview = document.querySelector('#image-preview');
    var deleteButton = null;

    function createDeleteButton() {
        deleteButton = document.createElement('button');
        deleteButton.innerText = 'Supprimer l\'image';
        deleteButton.className = 'btn btn-warning btn-custom-margin';
        deleteButton.onclick = function() {
            imageInput.value = '';
            imagePreview.innerHTML = '';
            deleteButton.remove();
        };
        return deleteButton;
    }

    // Afficher l'image existante et le bouton de suppression (pour le formulaire d'édition)
    if (imagePreview && imagePreview.innerHTML.trim() !== '') {
        imagePreview.appendChild(createDeleteButton());
    }

    if (imageInput) {
        imageInput.addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.innerHTML = '<img src="' + e.target.result + '" alt="Aperçu de l\'image" style="max-width: 200px; max-height: 200px;"/>';
                    imagePreview.appendChild(createDeleteButton());
                };
                reader.readAsDataURL(file);
            }
        });
    }
});