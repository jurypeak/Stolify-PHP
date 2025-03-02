// Declare global variables
let albums = {}; // Store albums dynamically
let currentAlbum = null;
let currentSongIndex = 0;
let currentAudio = new Audio();
let isPlaying = false;

// Get elements for controlling playback
const playButton = $('.play-btn');
const nextButton = $('.next-btn');
const prevButton = $('.prev-btn');
const songTitle = $('#song-title');
const songArtist = $('#song-artist');

// Fetch albums from the database
function loadAlbums() {
    $.ajax({
        url: '../handlers/handleAlbums.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            albums = data; // Store fetched albums
            console.log('Albums loaded:', albums); // Debugging
        },
        error: function (xhr, status, error) {
            console.error('Error loading albums:', error);
        }
    });
}

// Function to start playing an album
function startAlbumPlayback(albumName) {
    if (!albums[albumName]) {
        console.error('Album not found:', albumName);
        return;
    }

    if (currentAlbum === albumName) return; // Prevent reloading the same album

    currentAlbum = albumName;
    currentSongIndex = 0;
    const albumSongs = albums[albumName];

    setSongInfo(albumSongs[currentSongIndex]);
    playSong(albumSongs[currentSongIndex]);

    currentAudio.onended = function () {
        nextSong();
    };
}

// Function to play a song
function playSong(song) {
    currentAudio.src = song.audioFile;
    currentAudio.load();
    currentAudio.play().catch(error => console.error('Error playing audio:', error));
    playButton.find('i').removeClass('fa-play').addClass('fa-pause');
    isPlaying = true;
}

// Function to set song info in the control panel
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

// Function to play the next song
function nextSong() {
    if (!currentAlbum || !albums[currentAlbum]) return;

    currentSongIndex = (currentSongIndex + 1) % albums[currentAlbum].length; // Loop back to the start
    setSongInfo(albums[currentAlbum][currentSongIndex]);
    playSong(albums[currentAlbum][currentSongIndex]);
}

// Function to play the previous song
function prevSong() {
    if (!currentAlbum || !albums[currentAlbum]) return;

    currentSongIndex = (currentSongIndex - 1 + albums[currentAlbum].length) % albums[currentAlbum].length; // Loop back to the last song
    setSongInfo(albums[currentAlbum][currentSongIndex]);
    playSong(albums[currentAlbum][currentSongIndex]);
}

// Event listeners for next/prev buttons
nextButton.on('click', nextSong);
prevButton.on('click', prevSong);

// Listen for album clicks
$(document).on('click', '.hover-play-btn', function (event) {
    event.stopPropagation(); // Prevent triggering the parent .album-grid-item click

    const albumName = $(this).closest('.album-grid-item').data('album'); // Get album name from parent
    startAlbumPlayback(albumName);
});


// Load albums when the page loads
$(document).ready(function () {
    loadAlbums();
});



