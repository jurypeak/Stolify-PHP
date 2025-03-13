document.getElementById('search').addEventListener('keypress', function(event) {
    if (event.key === 'Enter') {
        const query = this.value;
        if (query.length > 0) {
            window.location.href = '../pages/searchAlbums.php?query=' + encodeURIComponent(query);
        }
    }
});

