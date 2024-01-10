// document.addEventListener("DOMContentLoaded", function() {
//     var imageInput = document.querySelector('#contact_imageFile_file');
//     var imagePreview = document.querySelector('#image-preview');
//     var deleteButton = null;
//
//     function createDeleteButton() {
//         var deleteButton = document.createElement('button');
//         deleteButton.innerText = 'Supprimer l\'image';
//         deleteButton.className = 'btn btn-warning';
//         deleteButton.onclick = function() {
//             imageInput.value = '';
//             imagePreview.innerHTML = '';
//             deleteButton.remove();
//             deleteButton = null; // Réinitialiser la référence au bouton
//         };
//         return deleteButton;
//     }
//
//     imageInput.addEventListener('change', function(event) {
//         var file = event.target.files[0];
//         if (file) {
//             var reader = new FileReader();
//             reader.onload = function(e) {
//                 imagePreview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;"/>';
//
//                 // Retirer l'ancien bouton de suppression s'il existe
//                 if (deleteButton) {
//                     deleteButton.remove();
//                 }
//
//                 // Créer un nouveau bouton de suppression
//                 deleteButton = createDeleteButton();
//                 imagePreview.appendChild(deleteButton);
//             };
//             reader.readAsDataURL(file);
//         }
//     });
//
//     // Afficher le bouton de suppression pour une image existante dans le formulaire d'édition
//     if (imagePreview && imagePreview.innerHTML.trim() !== '') {
//         deleteButton = createDeleteButton();
//         imagePreview.appendChild(deleteButton);
//     }
// });

document.addEventListener("DOMContentLoaded", function() {
    var imageInput = document.querySelector('#contact_imageFile_file');
    var imagePreview = document.querySelector('#image-preview');
    var deleteButton = null;
    var imageDeletedField = document.querySelector('#image_deleted');

    function createDeleteButton() {
        var deleteButton = document.createElement('button');
        console.log("Création du bouton");
        deleteButton.innerText = 'Supprimer l\'image';
        deleteButton.className = 'btn btn-warning';
        deleteButton.onclick = function() {
            console.log("Click sur bouton");
            imageInput.value = '';
            imagePreview.innerHTML = '';
            deleteButton.remove();
            deleteButton = null;
            imageDeletedField.value = '1';
            console.log("Valeur du champ imageDeleted : " + imageDeletedField.value);
        };
        return deleteButton;
    }

    function updateImagePreview(file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.innerHTML = '<img src="' + e.target.result + '" style="max-width: 200px; max-height: 200px;"/>';
            if (deleteButton) {
                deleteButton.remove();
            }
            deleteButton = createDeleteButton();
            imagePreview.appendChild(deleteButton);
        };
        reader.readAsDataURL(file);
    }

    imageInput.addEventListener('change', function(event) {
        var file = event.target.files[0];
        if (file) {
            updateImagePreview(file);
        }
    });

    // Gérer l'affichage de l'image existante
    var existingImageUrl = imagePreview.getAttribute('data-image-url');
    if (existingImageUrl) {
        imagePreview.innerHTML = '<img src="' + existingImageUrl + '" style="max-width: 200px; max-height: 200px;"/>';
        deleteButton = createDeleteButton();
        imagePreview.appendChild(deleteButton);
    }
});

