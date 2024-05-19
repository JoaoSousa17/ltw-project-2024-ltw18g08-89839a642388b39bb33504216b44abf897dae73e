document.addEventListener('DOMContentLoaded', function() {
    var menuButton = document.getElementById('userButton');
    var menuDropdownContent = document.getElementById('userDropdownContent');

    menuButton.addEventListener('click', function() {
        if (menuDropdownContent.style.display === 'inline-flex') {
            menuDropdownContent.style.display = 'none';
        } else {
            menuDropdownContent.style.display = 'inline-flex';
        }
    });

    document.getElementById('icon-update-button').addEventListener('change', function(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(event) {
            document.getElementById('current-photo').src = event.target.result;
        };

        reader.readAsDataURL(file);
    });
});
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('icon-update-button').addEventListener('change', handleFileSelect);
});

function handleFileSelect(event) {
    const files = event.target.files;
    const container = document.getElementById('container-photo-item');

    // Clear previous images
    container.innerHTML = '';

    // Loop through the selected files
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function(event) {
            // Create a new image element
            const img = document.createElement('img');
            img.src = event.target.result;
            img.alt = 'product-photo';

            // Append the image to the container
            container.appendChild(img);
        };

        // Read the file as a data URL
        reader.readAsDataURL(file);
    }
}
