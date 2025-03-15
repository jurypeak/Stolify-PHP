// Picks a random background image from a list of images and sets it as the background image of the page.
function setRandomBackground() {
    const backgroundImages = [
        '../media/wallpaper1.jpg',
        '../media/wallpaper2.png',
        '../media/wallpaper3.png'
    ];

    const randomIndex = Math.floor(Math.random() * backgroundImages.length);
    document.body.style.backgroundImage = `url('${backgroundImages[randomIndex]}')`;

    document.body.style.backgroundRepeat = 'no-repeat';
    document.body.style.backgroundAttachment = 'fixed';
    document.body.style.backgroundSize = 'cover';
}

window.onload = setRandomBackground;
