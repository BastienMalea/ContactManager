document.addEventListener('DOMContentLoaded', function() {
    var container = document.getElementById('custom-fields-container');
    var prototype = container.getAttribute('data-prototype');

    document.getElementById('add-custom-field').addEventListener('click', function() {
        var newForm = prototype.replace(/__name__/g, container.children.length);
        container.insertAdjacentHTML('beforeend', newForm);
    });

    container.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-custom-field')) {
            event.target.closest('.custom-field-item').remove();
        }
    });
});