document.querySelectorAll('.toggle-details-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        var nextRow = this.closest('tr').nextElementSibling;
        if (nextRow && nextRow.classList.contains('group-custom-field-row')) {
            var isHidden = nextRow.style.display === 'none';
            nextRow.style.display = isHidden ? '' : 'none';
            this.textContent = isHidden ? '-' : '+';
        }
    });
});