// Set a random background image on page load
window.onload = function() {
    const backgroundImages = [
        'media/wallpaper1.jpg',
        'media/wallpaper2.png',
        'media/wallpaper3.png'
    ];

    const randomIndex = Math.floor(Math.random() * backgroundImages.length);
    document.body.style.backgroundImage = `url('${backgroundImages[randomIndex]}')`;

    // Prevent background image from repeating
    document.body.style.backgroundRepeat = 'no-repeat';
    document.body.style.backgroundAttachment = 'fixed';
    document.body.style.backgroundSize = 'cover';  // Ensures the image covers the full screen
};

// jQuery Ready Function
$(document).ready(function () {
    // Albums with multiple songs
    const albums = {
        Sublime: [
            {title: 'Garden Grove', artist: 'Sublime', year: '1996', audioFile: 'media/Sublime-Sublime/Garden-Grove.mp3'},
            {title: 'What I Got', artist: 'Sublime', year: '1996', audioFile: 'media/Sublime-Sublime/What-I-Got.mp3'},
            {title: 'Santeria', artist: 'Sublime', year: '1996', audioFile: 'media/Sublime-Sublime/Santeria.mp3'}
        ],
        Bleach: [
            {title: 'Blew', artist: 'Nirvana', year: '1989', audioFile: 'media/Bleach-Nirvana/Blew.mp3'},
        ],
        "Cheshire Cat": [
            {title: 'Carousel', artist: 'blink-182', year: '1995', audioFile: 'media/Cheshire Cat-blink182/'},
            {title: "M+M's", artist: 'blink-182', year: '1995', audioFile: 'media/Cheshire Cat-blink182/'}
        ],
        "40oz. To Freedom": [
            {title: 'Waiting For My Ruca', artist: 'Sublime', year: '1992', audioFile: 'media/FortyOz-Sublime/Waiting For My Ruca.mp3'},
            {title: "40oz. To Freedom", artist: 'Sublime', year: '1992', audioFile: 'media/FortyOz-Sublime/40 oz. To Freedom.mp3'}
        ]
    };

    let currentAlbum = null;
    let currentSongIndex = 0;
    let currentAudio = new Audio();
    let isPlaying = false;

    const playButton = $('.play-btn');
    const nextButton = $('.next-btn');
    const prevButton = $('.prev-btn');
    const songTitle = $('#song-title');
    const songArtist = $('#song-artist');

    // Handle album selection
    $('.album-grid-item').on('click', function () {
        const albumName = $(this).data('album');
        startAlbumPlayback(albumName);
    });

    // Handle album selection from search results
    $('#album-results').on('click', '.album-grid-item', function () {
        const albumName = $(this).data('album');
        startAlbumPlayback(albumName);
    });

    // Start playing an album
    function startAlbumPlayback(albumName) {
        if (currentAlbum === albumName) return;

        currentAlbum = albumName;
        currentSongIndex = 0; // Start from the first song
        const albumSongs = albums[albumName];

        setSongInfo(albumSongs[currentSongIndex]);
        currentAudio.src = albumSongs[currentSongIndex].audioFile;
        currentAudio.load();
        currentAudio.play().catch((error) => {
            console.error('Error playing audio:', error);
        });

        playButton.find('i').removeClass('fa-play').addClass('fa-pause');
        isPlaying = true;

        currentAudio.onended = function () {
            nextSong();
        };
    }

    // Set song title and artist in the control panel
    function setSongInfo(song) {
        songTitle.text(song.title);
        songArtist.text(song.artist);
    }

    // Play/Pause button toggle
    playButton.on('click', function () {
        if (isPlaying) {
            currentAudio.pause();
            playButton.find('i').removeClass('fa-pause').addClass('fa-play');
        } else {
            currentAudio.play();
            playButton.find('i').removeClass('fa-play').addClass('fa-pause');
        }
        isPlaying = !isPlaying;
    });

    // Play next song in the album
    function nextSong() {
        const albumSongs = albums[currentAlbum];
        currentSongIndex++;
        if (currentSongIndex < albumSongs.length) {
            setSongInfo(albumSongs[currentSongIndex]);
            currentAudio.src = albumSongs[currentSongIndex].audioFile;
            currentAudio.load();
            currentAudio.play().catch((error) => {
                console.error('Error playing audio:', error);
            });
        } else {
            currentSongIndex = 0;
            setSongInfo(albumSongs[currentSongIndex]);
            currentAudio.src = albumSongs[currentSongIndex].audioFile;
            currentAudio.load();
            currentAudio.play().catch((error) => {
                console.error('Error playing audio:', error);
            });
        }
    }

    // Play previous song in the album
    function prevSong() {
        const albumSongs = albums[currentAlbum];
        currentSongIndex--;
        if (currentSongIndex >= 0) {
            setSongInfo(albumSongs[currentSongIndex]);
            currentAudio.src = albumSongs[currentSongIndex].audioFile;
            currentAudio.load();
            currentAudio.play().catch((error) => {
                console.error('Error playing audio:', error);
            });
        } else {
            currentSongIndex = albumSongs.length - 1;
            setSongInfo(albumSongs[currentSongIndex]);
            currentAudio.src = albumSongs[currentSongIndex].audioFile;
            currentAudio.load();
            currentAudio.play().catch((error) => {
                console.error('Error playing audio:', error);
            });
        }
    }

    // Event listeners for next/prev buttons
    nextButton.on('click', nextSong);
    prevButton.on('click', prevSong);

    // Volume control
    $('#volume-slider').on('input', function () {
        currentAudio.volume = $(this).val();
    });

    // Handle the search input on the home page
    $('#search').on('keypress', function (e) {
        if (e.which === 13) {  // Enter key
            const query = $(this).val().trim();
            if (query) {
                // Redirect to the search-results page with the query parameter
                window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
            }
        }
    });

    $('#search-btn').on('click', function () {
        const query = $('#search').val().trim();
        if (query) {
            // Redirect to the search-results page with the query parameter
            window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
        }
    });

    // --- Search Results Page Functionality ---
    if (window.location.pathname.includes('search_results.php')) {
        const urlParams = new URLSearchParams(window.location.search);
        const query = urlParams.get('query') || '';

        // Set the search input value to the query parameter if available
        $('#search').val(query);

        // Function to display search results
        function displaySearchResults(query) {
            if (!query) return;

            let foundResults = false;
            const albums = {
                Sublime: [
                    {title: 'Garden Grove', artist: 'Sublime', year: '1996', audioFile: 'media/Sublime-Sublime/Garden-Grove.mp3'},
                    {title: 'What I Got', artist: 'Sublime', year: '1996', audioFile: 'media/Sublime-Sublime/What-I-Got.mp3'},
                    {title: 'Santeria', artist: 'Sublime', year: '1996', audioFile: 'media/Sublime-Sublime/Santeria.mp3'}
                ],
                Bleach: [
                    {title: 'Blew', artist: 'Nirvana', year: '1989', audioFile: 'media/Bleach-Nirvana/Blew.mp3'},
                ],
                "Cheshire Cat": [
                    {title: 'Carousel', artist: 'blink-182', year: '1995', audioFile: 'media/Cheshire Cat-blink182/'},
                    {title: "M+M's", artist: 'blink-182', year: '1995', audioFile: 'media/Cheshire Cat-blink182/'}
                ],
                "40oz. To Freedom": [
                    {title: 'Waiting For My Ruca', artist: 'Sublime', year: '1992', audioFile: 'media/FortyOz-Sublime/Waiting For My Ruca.mp3'},
                    {title: "40oz. To Freedom", artist: 'Sublime', year: '1992', audioFile: 'media/FortyOz-Sublime/40 oz. To Freedom.mp3'}
                ]
            };

            // Clear previous results
            $('#album-results').empty();
            $('#no-results').hide();

            // Iterate through the albums and check if they match the search query
            $.each(albums, function (albumName, songs) {
                // Check if query matches album name or artist name only
                if (albumName.toLowerCase().includes(query.toLowerCase()) || songs[0].artist.toLowerCase().includes(query.toLowerCase())) {
                    foundResults = true;

                    // Create the album result HTML and append it
                    const albumHTML = `
                        <div class="album-grid-item" data-album="${albumName}" data-artist="${songs[0].artist}" data-year="${songs[0].year}">
                            <img src="media/${albumName}_Cover.jpg" alt="Album cover" class="album-cover">
                            <button class="hover-play-btn">
                                <i class="fa fa-play"></i> <!-- Play Icon -->
                            </button>
                            <h3>${albumName}</h3>
                            <p class="artist-name">${songs[0].artist}</p>
                            <p class="album-description">${songs[0].year} • Album</p>
                        </div>
                    `;
                    $('#album-results').append(albumHTML);
                }
            });

            // If no results were found, show the "No results found" message
            if (!foundResults) {
                $('#no-results').show();
            }
        }

        // Display the search results based on the query parameter
        displaySearchResults(query);

        // Handle Enter key press for search input
        $('#search').on('keypress', function (e) {
            if (e.which === 13) {  // Enter key
                const query = $(this).val().toLowerCase().trim();
                if (query) {
                    window.location.href = `search_results.php?query=${encodeURIComponent(query)}`;
                }
            }
        });
    }
});

// Automatically update the year in the footer
document.getElementById('year').textContent = new Date().getFullYear();

$(document).ready(function() {
    $('#resetPasswordForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission behavior

        var email = $('#email').val();

        if (!validateEmail(email)) {
            showNotification('Invalid email format.', 'error');
            return;
        }

        // Disable the button and change text to "Sending..."
        var submitButton = $('input[type="submit"]');
        submitButton.prop('disabled', true).val('Sending...');

        // Perform AJAX request
        $.ajax({
            type: 'POST',
            url: 'forgotPassword.php',  // Your PHP script
            data: { email: email },  // Sending the email data
            dataType: 'json',  // Expecting JSON response
            success: function(response) {
                console.log(response);  // Log the response for debugging

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Send Link');

                if (response.status === 'success') {
                    // Display success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                    });
                } else {
                    // Display error notification
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: response.message,
                        timer: 3000,
                        showConfirmButton: false,
                        backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                    });
                }
            },
            error: function(xhr, status, error) {
                // Log error if AJAX request fails
                console.log("AJAX Error: ", error);

                // Reset the button text and enable it again
                submitButton.prop('disabled', false).val('Send Link');

                // Show error notification
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'There was an issue sending your request. Please try again.',
                    timer: 3000,
                    showConfirmButton: false,
                    backdrop: 'rgba(0, 0, 0, 0.5)'  // Lighter backdrop with less opacity
                });
            }
        });
    });

    // Validate email function
    function validateEmail(email) {
        var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
        return emailPattern.test(email);
    }
});

// Toggle password visibility function
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';  // Show the password as text
        eyeIcon.innerHTML = '<i class="fa fa-eye-slash"></i>';  // Change the icon
    } else {
        passwordInput.type = 'password';  // Hide the password again
        eyeIcon.innerHTML = '<i class="fa fa-eye"></i>';  // Change the icon
    }
}
