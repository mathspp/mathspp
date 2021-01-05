const DARK = "#191919";
const LIGHT = "#adb3bd";
const BLUE = "#3085ee";
const DARK_BLUE = "#3c72b2";
const YELLOW = "#e6d70b";
const SQUARE_SIZE = 80;
const WIDTH = 8*SQUARE_SIZE;
const NPC_TIME = 1000;

const strings = {
    "you_lost": {
        "en": "You lost!",
        "pt": "Perdeste!"
    },
    "you_won": {
        "en": "You won!",
        "pt": "Ganhaste!"
    },
    "your_turn": {
        "en": "Your turn.",
        "pt": "É a tua vez."
    },
    "your_initial_turn": {
        "en": "It is your turn. Pick <i>any</i> square.",
        "pt": "É a tua vez. Escolhe um quadrado <i>qualquer</i>."
    },
    "commputers_turn": {
        "en": "The computer is thinking...",
        "pt": "O computador está a pensar..."
    },
    "pick_valid_move": {
        "en": "Pick any <i>valid</i> move (any of the circumferences).",
        "pt": "Por favor escolhe uma jogada <i>válida</i> (qualquer uma das circunferências)."
    }
};

/* Several variables useful for the game. */
var playerStarts = true; // flag if the user is the first player or not.
var visited;     // 8x8 matrix with visited positions.
var knightPosition; // the a1 notational position of where the knight stands.
var possibleMoves; // array with positions to which the knight can go next, in a1 notation.
var canMove;     // boolean determining whether the user can move the knight or not.
var strategy;    // array that the NPC uses to determine its moves.

/* Convert chessboard positional notation to (x, y) coordinates.
 * e.g. "a1" => (0, 7), "h8" => (7, 0)
 */
function a1ToXY(a1) {
    return ["abcdefgh".indexOf(a1[0]), "87654321".indexOf(a1[1])];
}

/* Convert (x, y) board coordinates to chessboard A1 positional notation.
 * e.g. (0, 0) => "a8", (7, 7) => "h1"
 */
function xyToA1(xy) {
    return "abcdefgh"[xy[0]] + "87654321"[xy[1]];
}

/* Get the pixel position of a mouse click inside the canvas. */
function getCursorPosition(canvas, event) {
    // cf. https://stackoverflow.com/a/18053642/2828287 (accessed 15-12-2020)
    const rect = canvas.getBoundingClientRect();
    const x = event.clientX - rect.left;
    const y = event.clientY - rect.top;
    return {x: x, y: y}
}

/* Mouse listener that should capture user clicks and dispatches them. */
function mouseListener(e) {
    if (e.buttons == 1 && e.target == canvas) {
        if (!canMove) return;
        const pos = getCursorPosition(canvas, e);
        const a1 = xyToA1([
            Math.floor(pos.x / SQUARE_SIZE),
            Math.floor(pos.y / SQUARE_SIZE)
        ])
        if (-1 < possibleMoves.indexOf(a1)) moveKnight(a1);
        else alertsParagraph.innerHTML = strings["pick_valid_move"][language];
    }
}

function moveNPC() {
    cleanupKnightPosition();

    var move;
    if (knightPosition !== undefined) {
        const currXY = a1ToXY(knightPosition);
        const rect = strategy[currXY[1]][currXY[0]];
        // Try to use the strategy to pick a move.
        possibleMoves.forEach(function (a1) {
            var xy = a1ToXY(a1);
            if (rect === strategy[xy[1]][xy[0]]) {
                move = a1;
            }
        });
    }
    if (undefined === move) {
        move = possibleMoves[Math.floor(Math.random() * possibleMoves.length)]
    }

    console.log(move);
    knightPosition = move;
    const pos = a1ToXY(move);
    visited[pos[1]][pos[0]] = true;
    drawKnight(move);
    registerNPCMove(move);
    syncPossibleMoves();
    canMove = true;
    alertsParagraph.innerHTML = strings["your_turn"][language];
    highlightPossibleMoves();
}

/* Moves the knight to the appropriate position on the chessboard. */
async function moveKnight(a1) {
    // Check if there is cleanup that needs to be done.
    cleanupKnightPosition();
    // Update the knight position and visual representation.
    knightPosition = a1;
    const pos = a1ToXY(a1);
    visited[pos[1]][pos[0]] = true;
    drawKnight(a1);
    registerUserMove(knightPosition);
    syncPossibleMoves();

    canMove = false;
    if (0 === possibleMoves.length) {
        alertsParagraph.innerHTML = strings["you_won"][language];
        return;
    }

    // cf. https://stackoverflow.com/a/39914235/2828287, visited on 18-12-2020
    alertsParagraph.innerHTML = strings["commputers_turn"][language];
    await new Promise(r => setTimeout(r, NPC_TIME)); // sleep for a bit.
    moveNPC();

    if (0 === possibleMoves.length) {
        alertsParagraph.innerHTML = strings["you_lost"][language];
        return;
    }
}

/* Function to register the user move. */
function registerUserMove(a1) {
    console.log(a1);
    movesTextarea.value = (movesTextarea.value ? movesTextarea.value + ", " + a1 : a1);
}

/* Function to register the NPC move. */
function registerNPCMove(a1) {
    registerUserMove(a1);
}

/* Cleanup current knight position. */
function cleanupKnightPosition() {
    if (knightPosition !== undefined) {
        // Erase previous knight and leave a shade behind.
        drawSquare(knightPosition);
        drawShadedKnight(knightPosition);
        // Erase previous markers of legal moves.
        possibleMoves.map(drawSquare);
    }
}

/* Draw the knight in position a1. */
function drawKnight(a1) {
    _drawKnight(a1, BLUE);
}

/* Draw the shadow of a knight in position a1. */
function drawShadedKnight(a1) {
    _drawKnight(a1, DARK_BLUE + "80"); // Also set some transparency;
}

/* Helper function that draws a knight with the given colour in the given position. */
function _drawKnight(a1, colour) {
    const pos = a1ToXY(a1);
    ctx.beginPath();
    ctx.arc(
        pos[0]*SQUARE_SIZE + SQUARE_SIZE/2,
        pos[1]*SQUARE_SIZE + SQUARE_SIZE/2,
        4*SQUARE_SIZE/10,
        0, Math.PI*2, false
    )
    ctx.fillStyle = colour;
    ctx.fill();
    ctx.closePath();
}

/* Draws the chessboard square indicated. */
function drawSquare(a1) {
    const xy = a1ToXY(a1);
    ctx.beginPath();
    ctx.rect(
        xy[0]*SQUARE_SIZE, xy[1]*SQUARE_SIZE,
        SQUARE_SIZE, SQUARE_SIZE
    )
    ctx.fillStyle = (xy[0]+xy[1])%2 ? LIGHT : DARK;
    ctx.fill();
    ctx.closePath();
}

/* Highlights a square. */
function drawHighlighting(a1) {
    const xy = a1ToXY(a1);
    ctx.beginPath();
    ctx.arc(
        xy[0]*SQUARE_SIZE + SQUARE_SIZE/2,
        xy[1]*SQUARE_SIZE + SQUARE_SIZE/2,
        4*SQUARE_SIZE/10,
        0, Math.PI*2, false
    )
    ctx.lineWidth = 3;
    ctx.strokeStyle = DARK_BLUE;
    ctx.stroke();
    ctx.closePath();
}

/* Computes legal moves for the next play and sets the global variable. */
function syncPossibleMoves() {
    if (knightPosition === undefined) {
        const f = (a, b) => [].concat(...a.map(d => b.map(e => [].concat(d, e))));
        // Apply the cartesian product and then build a string out of the pairs.
        possibleMoves = f([..."abcdefgh"], [..."12345678"]).map(pair => pair[0]+pair[1]);
        return;
    }
    possibleMoves = [];
    var pos = a1ToXY(knightPosition);
    [[1, 2], [2, 1]].forEach(function(ds) {
        [[1, 1], [1, -1], [-1, 1], [-1, -1]].forEach(function(signs) {
            possibleMoves.push([
                pos[0] + ds[0]*signs[0], pos[1] + ds[1]*signs[1]
            ])
        });
    });
    possibleMoves = possibleMoves.filter(function(pos) {
        return pos[0] >= 0 && pos[0] < 8 && pos[1] >= 0 && pos[1] < 8 && !visited[pos[1]][pos[0]];
    }).map(xyToA1);
    console.log(possibleMoves);
}

/* Highlight legal moves in the chessboard. */
function highlightPossibleMoves() {
    possibleMoves.map(drawHighlighting);
}

/* Update the global variable that holds the NPC strategy.
 * A strategy is an 8x8 array with integers 0-7.
 * Each cell in the array contains an id that identifies the 2x4 or 4x2 rectangle
 * that that cell belongs to.
 * Such a rectangle is to be taken as the area within which the knight should remain
 * when the NPC plays the perfect strategy to win.
 * To find a strategy, try to randomly generate the rectangles in horizontal or vertical
 * position, according to the edges of the board and nearby rectangles that have
 * already been assigned.
 * Sometimes, an impossible configuration is reached (because the checks for directions
 * do not look ahead to see if we are creating 2x2 areas where no rectangle fits)
 * and when that happens we start over. */
function generateNPCStrategy() {
    var y = 0, x = 0, rec = 0;
    var dir; // `true` represents horizontal and `false` vertical.
    var maxdy, maxdx;
    var placing = true;

    // Brute-force-y approach to generate a valid strategy.
    while (placing) {
        console.log("attempt");
        // Reset strategy array and rectangle counter.
        strategy = new Array(8);
        for (y = 0; y < 8; ++y) {
            strategy[y] = new Array(8);
        }
        x = y = rec = 0;

        while (rec < 8) {
            // Find next upper-left corner of a strategy rectangle.
            while (y < 8 && strategy[y][x] !== undefined) {
                while (x < 8 && strategy[y][x] !== undefined) {
                    x += 2;
                }
                if (x >= 8) {
                    x = 0;
                    y += 2;
                }
            }

            // Check if it looks like the rectangle can go in both directions or not.
            if (y + 2 < 8 && strategy[y + 2][x] === undefined && x + 2 < 8 && strategy[y][x + 2] === undefined) {
                dir = Math.random() < 0.5;
            } else {
                dir = x + 2 < 8 && strategy[y][x + 2] === undefined;
            }
            maxdx = dir ? 4 : 2;
            maxdy = dir ? 2 : 4;
            if (y + maxdy > 8 || x + maxdx > 8 || strategy[y + maxdy - 1][x + maxdx - 1] !== undefined) {
                break
            }

            for (var dy = 0; dy < maxdy; ++dy) {
                for (var dx = 0; dx < maxdx; ++dx) {
                    strategy[y+dy][x+dx] = rec;
                }
            }
            ++rec;
        }
        placing = rec < 8;
    }

}

/* Reset the game state. */
function resetGame() {
    // Initialise the visited array to all empty positions.
    visited = Array(8);
    possibleMoves = [];
    for (var y = 0; y < 8; ++y) {
        visited[y] = Array(8);
        for (var x = 0; x < 8; ++x) {
            visited[y][x] = false;
            const a1 = xyToA1([x, y]);
            drawSquare(a1);
            possibleMoves.push(a1);
        }
    }
    knightPosition = undefined;
    syncPossibleMoves();
    canMove = playerStarts;
    movesTextarea.value = "";
    generateNPCStrategy();
    if (!playerStarts) {
        moveNPC();
    } else {
        alertsParagraph.innerHTML = strings["your_initial_turn"][language];
    }
}

document.getElementById("gameContainer").style.width = `${8*SQUARE_SIZE}px`;
// Initialise the canvas.
var canvas = document.getElementById("myCanvas");
canvas.setAttribute("width", 8*SQUARE_SIZE);
canvas.setAttribute("height", 8*SQUARE_SIZE);
var ctx = canvas.getContext("2d");
// Get the reference to the textarea that registers the moves.
var movesTextarea = document.getElementById("movesTextarea");
movesTextarea.style.width = `${8*SQUARE_SIZE-2}px`;
// Get the reference to the paragraph that displays information.
var alertsParagraph = document.getElementById("alertsParagraph");
var language = alertsParagraph.getAttribute("language");

document.addEventListener("mousedown", mouseListener, false);

document.getElementById("computerGoesFirstCheckbox").addEventListener("change", () => {
    playerStarts = !playerStarts;
    // Force a game reset (and a NPC move) if the player hasn't made the first move yet.
    if (knightPosition === undefined) {
        resetGame();
    }
})

// Initialise the game.
resetGame();
