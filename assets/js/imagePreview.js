document.addEventListener("DOMContentLoaded", function() {
    var imageInput = document.querySelector('#contact_imageFile_file');
    var imagePreview = document.querySelector('#image-preview');

    function createDeleteButton() {
        var deleteButton = document.createElement('button');
        deleteButton.innerText = 'Supprimer l\'image';
        deleteButton.className = 'btn btn-warning btn-custom-margin';
        deleteButton.onclick = function() {
            imageInput.value = '';
            imagePreview.innerHTML = '';
            deleteButton.remove();
        };
        return deleteButton;
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

    // Observer les modifications du DOM pour détecter l'ajout de l'image
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length > 0) {
                var addedImage = mutation.addedNodes[0];
                if (addedImage.tagName === 'IMG') {
                    imagePreview.appendChild(createDeleteButton());
                }
            }
        });
    });

    observer.observe(imagePreview, { childList: true });
});