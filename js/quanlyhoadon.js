function toggleClasses(button){
    button.classList.toggle('opened');
    document.querySelector('.sidebar').classList.toggle('open');
}
document.querySelector('.search-bar').addEventListener('input', function() {
    var filter = this.value.toLowerCase();
    var rows = document.querySelectorAll('#results-body tr');

    rows.forEach(function(row) {
        var cells = row.querySelectorAll('td');
        var match = false;

        cells.forEach(function(cell) {
            if (cell.textContent.toLowerCase().indexOf(filter) > -1) {
                match = true;
            }
        });

        if (match) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});