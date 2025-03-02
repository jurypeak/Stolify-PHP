document.getElementById('search').addEventListener('keypress', function(event) {
    // Check if the pressed key is 'Enter' (keyCode 13 or event.key 'Enter')
    if (event.key === 'Enter') {
        const query = this.value;
        if (query.length > 0) {
            // Redirect to the search page with the query in the URL
            window.location.href = '../pages/searchAlbums.php?query=' + encodeURIComponent(query);
        }
    }
});

