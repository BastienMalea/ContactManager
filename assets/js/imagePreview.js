
document.addEventListener("DOMContentLoaded", function() {
    console.log('Le script imagePreview est chargé');

    var imageInput = document.querySelector('#contact_imageFile_file');
    var imagePreview = document.querySelector('#image-preview');
    var deleteButton = null; // Bouton de suppression

    if(imageInput != null){
        imageInput.addEventListener('change', function(event) {
            var file = event.target.files[0];
            if (file) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    imagePreview.innerHTML = '<img src="' + e.target.result + '" alt="Aperçu de l\'image" style="max-width: 200px; max-height: 200px;"/>';
                    // Créer et ajouter le bouton de suppression
                    deleteButton = document.createElement('button');
                    deleteButton.innerText = 'Supprimer l\'image selectionné'
                    deleteButton.onclick = function() {
                        // Réinitialiser l'input de fichier et enlever l'aperçu
                        imageInput.value = '';
                        imagePreview.innerHTML = '';
                        deleteButton.remove();
                    };
                    imagePreview.appendChild(deleteButton);
                };

                reader.readAsDataURL(file);
            }
        });
    }
});