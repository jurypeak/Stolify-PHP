// background.js
function setRandomBackground() {
    const backgroundImages = [
        '../media/wallpaper1.jpg',
        '../media/wallpaper2.png',
        '../media/wallpaper3.png'
    ];

    const randomIndex = Math.floor(Math.random() * backgroundImages.length);
    document.body.style.backgroundImage = `url('${backgroundImages[randomIndex]}')`;

    // Prevent background image from repeating
    document.body.style.backgroundRepeat = 'no-repeat';
    document.body.style.backgroundAttachment = 'fixed';
    document.body.style.backgroundSize = 'cover';  // Ensures the image covers the full screen
}

window.onload = setRandomBackground;
