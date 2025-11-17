Learn how to implement and use the floodfill algorithm in Python.

===

<link rel="stylesheet" href="https://pyscript.net/releases/2025.11.1/core.css" />
<script type="module" src="https://pyscript.net/releases/2025.11.1/core.js"></script>


## What is the floodfill algorithm?

<canvas id="bitmap" width="320" height="320"></canvas>

<py-script>
from pyscript import display
import js
from js import fetch

URL = "https://mathspp.com/blog/floodfill-algorithm-in-python/_python.txt"

async def load_bitmap(url: str) -> list[list[int]]:
    # Fetch the text file from the URL
    response = await fetch(url)
    text = await response.text()

    bitmap: list[list[int]] = []
    for line in text.splitlines():
        line = line.strip()
        if not line:
            continue
        # Line is like "0010101" -> [0,0,1,0,1,0,1]
        row = [int(ch) for ch in line if ch in "01"]
        if row:
            bitmap.append(row)
    return bitmap

async def draw_bitmap():
    bitmap = await load_bitmap(URL)

    # Get canvas and context
    canvas = js.document.getElementById("bitmap")
    ctx = canvas.getContext("2d")

    rows = len(bitmap)
    cols = len(bitmap[0]) if rows > 0 else 0

    if rows == 0 or cols == 0:
        print("Empty bitmap!")
        return

    pixel_size = 1

    # Optionally adjust canvas size to match bitmap exactly
    canvas.width = cols * pixel_size
    canvas.height = rows * pixel_size

    for y, row in enumerate(bitmap):
        for x, value in enumerate(row):
            if value == 1:
                ctx.fillStyle = "black"
            else:
                ctx.fillStyle = "white"
            ctx.fillRect(x * pixel_size, y * pixel_size, pixel_size, pixel_size)

# Run the drawing when the page / PyScript is ready
await draw_bitmap()
</py-script>
