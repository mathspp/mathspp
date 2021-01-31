const WIDTH = Math.min(window.screen.width, 800);

document.getElementById("gameContainer").style.width = `${WIDTH}px`;
// Get the reference to the textarea that registers the events.
var movesTextarea = document.getElementById("mainTextarea");
movesTextarea.style.width = `${WIDTH}px`;
