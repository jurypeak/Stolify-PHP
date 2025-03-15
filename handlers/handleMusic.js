let albums = {};
let currentAlbum = null;
let currentSongIndex = 0;
let currentAudio = new Audio();
let isPlaying = false;

const playButton = $('.play-btn');
const nextButton = $('.next-btn');
const prevButton = $('.prev-btn');
const songTitle = $('#song-title');
const songArtist = $('#song-artist');

// Load albums from the database/handlers.
function loadAlbums() {
    $.ajax({
        url: '../handlers/handleAlbums.php',
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            albums = data;
            console.log('Albums loaded:', albums);
        },
        error: function (xhr, status, error) {
            console.error('Error loading albums:', error);
        }
    });
}

// Start playing an album.
function startAlbumPlayback(albumName) {
    // Check if the album exists.
    if (!albums[albumName]) {
        console.error('Album not found:', albumName);
        return;
    }

    // Prevent restarting the same album if it's already playing.
    if (currentAlbum === albumName) return;

    currentAlbum = albumName;
    currentSongIndex = 0;
    const albumSongs = albums[albumName];

    // Set the song info and play the current song.
    setSongInfo(albumSongs[currentSongIndex]);
    playSong(albumSongs[currentSongIndex]);

    // When the current song ends, play the next song.
    currentAudio.onended = function () {
        nextSong();
    };
}

function playSong(song) {
    // Set the audio source, load the audio, and play it.
    currentAudio.src = song.audioFile;
    currentAudio.load();
    currentAudio.play().catch(error => console.error('Error playing audio:', error));
    playButton.find('i').removeClass('fa-play').addClass('fa-pause');
    isPlaying = true;
}

function setSongInfo(song) {
    songTitle.text(song.title);
    songArtist.text(song.artist);
}

// Play or pause the current song when play button is clicked.
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

// Play the next song when the skip button are clicked.
function nextSong() {
    if (!currentAlbum || !albums[currentAlbum]) return;

    currentSongIndex = (currentSongIndex + 1) % albums[currentAlbum].length;
    setSongInfo(albums[currentAlbum][currentSongIndex]);
    playSong(albums[currentAlbum][currentSongIndex]);
}

// Play the previous song when the back button is clicked.
function prevSong() {
    if (!currentAlbum || !albums[currentAlbum]) return;

    setSongInfo(albums[currentAlbum][currentSongIndex]);
    playSong(albums[currentAlbum][currentSongIndex]);
}

nextButton.on('click', nextSong);
prevButton.on('click', prevSong);

// Play the album when the hover play button is clicked.
$(document).on('click', '.hover-play-btn', function (event) {
    event.stopPropagation();

    const albumName = $(this).closest('.album-grid-item').data('album');
    startAlbumPlayback(albumName);
});

$(document).ready(function () {
    loadAlbums();
});



