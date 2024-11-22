document.querySelectorAll('input[type="file"]').forEach((input, index) => {
    input.addEventListener('change', function() {
        const fileName = this.files.length > 0 ? this.files[0].name : 'Geen file geselecteerd';
        document.getElementById(`file-name${index + 1}`).textContent = fileName;
    });
});