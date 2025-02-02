<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js" defer></script>
</head>
<body>

<!-- Color Block Layer (Wrapper) -->
<div class="color-block">

    <div class="logo-search-container">
        <div class="logo-title logo-small">
            <img src="media/logo.svg" alt="Logo" class="logo">
        </div>
        <!-- Search Bar Section -->
        <div class="search-bar-container">
            <input type="text" id="search" placeholder="What would you like to play?" class="search-bar">
            <i class="fa fa-search search-icon"></i> <!-- Search Icon -->
            <div id="no-results" style="display:none;">No results found.</div>
        </div>
    </div>

    <section id="results-container">
        <!-- This is where the search results will be dynamically inserted -->
        <div id="no-results" style="display: none;">No results found.</div>
        <div id="album-results">
            <!-- Albums will be appended here dynamically using JavaScript -->
        </div>
    </section>
</div>

<div class="music-control-panel">
    <div class="music-content">
        <div class="music-info">
            <p class="song-title" id="song-title">Song Title</p>
            <p class="song-artist" id="song-artist">Artist Name</p>
        </div>

        <div class="controls">
            <button class="control-btn prev-btn">
                <i class="fa fa-backward"></i> <!-- Previous song -->
            </button>
            <button class="control-btn play-btn">
                <i class="fa fa-play"></i> <!-- Play/Pause -->
            </button>
            <button class="control-btn next-btn">
                <i class="fa fa-forward"></i> <!-- Next song -->
            </button>
        </div>

        <!-- Volume Control -->
        <div class="volume-control">
            <input type="range" class="volume-slider" id="volume-slider" min="0" max="1" step="0.01" value="0.5">
        </div>
    </div>
</div>

<!-- Footer Section -->
<footer>
    <a href="mailto:support@stolify.com" class="footer-email">support@stolify.com</a> |
    <p>&copy; <span id="year"></span> Stolify. All rights reserved.</p>
</footer>

</body>
</html>

