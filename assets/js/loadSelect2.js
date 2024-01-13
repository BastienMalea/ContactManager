document.addEventListener('DOMContentLoaded', function() {

    $('.select2-multiple').each(function() {
        var placeholderText = "Sélectionnez des options";
        if ($(this).hasClass('group-select')) {
            placeholderText = "Sélectionnez des groupes";
        } else if ($(this).hasClass('contact-select')) {
            placeholderText = "Sélectionnez des contacts";
        }

        $(this).select2({
            placeholder: placeholderText,
            allowClear: true
        });
    });
});