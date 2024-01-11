document.addEventListener('DOMContentLoaded', function() {
    console.log("Load select2");

    $('.select2-multiple').select2({
        placeholder: "Sélectionnez des groupes",
        allowClear: true
    });
});