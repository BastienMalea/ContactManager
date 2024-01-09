document.addEventListener('DOMContentLoaded', function() {
    let container = document.getElementById('custom-fields-container');
    if (!container) {
        return;
    }

    let addCustomFieldButton = document.getElementById('add-custom-field');
    if (!addCustomFieldButton) {
        return;
    }

    addCustomFieldButton.addEventListener('click', function() {
        let prototype = container.getAttribute('data-prototype');
        let index = container.querySelectorAll('.custom-field-item').length;
        let newFieldHtml = prototype.replace(/__name__/g, index);
        let newField = document.createElement('div');
        newField.classList.add('custom-field-item');
        newField.innerHTML = newFieldHtml;

        // Ajout des labels
        var inputs = newField.querySelectorAll('input');
        if (inputs && inputs.length >= 2) {
            var labelTitle = document.createElement('label');
            labelTitle.textContent = 'Titre du champ';
            var labelValue = document.createElement('label');
            labelValue.textContent = 'Valeur du champ';

            inputs[0].parentNode.insertBefore(labelTitle, inputs[0]);
            inputs[1].parentNode.insertBefore(labelValue, inputs[1]);
        }

        // Ajout du bouton de suppression
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.textContent = 'Supprimer';
        removeButton.classList.add('remove-custom-field', 'btn', 'btn-danger');
        newField.appendChild(removeButton);

        container.appendChild(newField);
    });

    container.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-custom-field')) {
            event.target.closest('.custom-field-item').remove();
        }
    });
});