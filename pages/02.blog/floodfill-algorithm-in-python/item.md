Learn how to implement and use the floodfill algorithm in Python.

===

<link rel="stylesheet" href="https://pyscript.net/releases/2025.11.1/core.css" />
<script type="module" src="https://pyscript.net/releases/2025.11.1/core.js"></script>


## What is the floodfill algorithm?

Click the image below to randomly colour the region you click.

Go ahead, try it!

<canvas id="bitmap" width="320" height="320" style="display: block; margin: 0 auto;"></canvas>

<script>
const canvas = document.getElementById("bitmap");
const ctx = canvas.getContext("2d");
ctx.fillStyle = "var(--bg)";
ctx.fillRect(0, 0, canvas.width, canvas.height);
ctx.fillStyle = "var(--accent)";
ctx.font = "24px sans-serif";
ctx.textAlign = "center";
ctx.textBaseline = "middle";
ctx.fillText("Loading…", canvas.width / 2, canvas.height / 2);

</script>

<py-script>
IMG_WIDTH = 160
IMG_HEIGHT = 160
PIXEL_SIZE = 2

import asyncio
import collections
import random

from pyscript import display
from pyodide.ffi import create_proxy
import js
from js import fetch

canvas = js.document.getElementById("bitmap")
ctx = canvas.getContext("2d")

def show_loading():
    ctx.fillStyle = "white"
    ctx.fillRect(0, 0, canvas.width, canvas.height)
    ctx.fillStyle = "black"
    ctx.font = "24px sans-serif"
    ctx.textAlign = "center"
    ctx.textBaseline = "middle"
    ctx.fillText("Loading…", canvas.width // 2, canvas.height // 2)
show_loading()  # Set the canvas.

URL = "/blog/floodfill-algorithm-in-python/_python.txt"

async def load_bitmap(url: str) -> list[list[int]]:
    # Fetch the text file from the URL
    response = await fetch(url)
    text = await response.text()

    bitmap: list[list[int]] = []
    for line in text.splitlines():
        line = line.strip()
        if not line:
            continue
        row = [int(ch) for ch in line if ch in "01"]
        if row:
            bitmap.append(row)
    return bitmap

def draw_bitmap(bitmap):
    # Get canvas and context
    canvas = js.document.getElementById("bitmap")
    ctx = canvas.getContext("2d")

    rows = len(bitmap)
    cols = len(bitmap[0]) if rows > 0 else 0

    if rows == 0 or cols == 0:
        return

    for y, row in enumerate(bitmap):
        for x, value in enumerate(row):
            if value == 1:
                ctx.fillStyle = "black"
            else:
                ctx.fillStyle = "white"
            ctx.fillRect(x * PIXEL_SIZE, y * PIXEL_SIZE, PIXEL_SIZE, PIXEL_SIZE)
    canvas.style.backgroundImage = ""  # Clear “thumbnail preview”.

_neighbours = [(1, 0), (-1, 0), (0, 1), (0, -1)]

async def fill_bitmap(bitmap, x, y):
    if bitmap[y][x] == 1:
        return

    ctx = canvas.getContext("2d")
    r, g, b = (random.randint(0, 255) for _ in range(3))
    ctx.fillStyle = f"rgb({r}, {g}, {b})"
    def draw_pixel(x, y):
        ctx.fillRect(x * PIXEL_SIZE, y * PIXEL_SIZE, PIXEL_SIZE, PIXEL_SIZE)

    pixels = collections.deque([(x, y)])
    seen = set((x, y))
    while pixels:
        nx, ny = pixels.pop()
        draw_pixel(nx, ny)
        for dx, dy in _neighbours:
            x_, y_ = nx + dx, ny + dy
            if x_ &lt; 0 or x_ &gt;= IMG_WIDTH or y_ &lt; 0 or y_ &gt;= IMG_HEIGHT or (x_, y_) in seen:
                continue
            if bitmap[y_][x_] == 0:
                seen.add((x_, y_))
                pixels.appendleft((x_, y_))
        await asyncio.sleep(0.0001)

# Run the drawing when the page / PyScript is ready
bitmap = await load_bitmap(URL)
draw_bitmap(bitmap)

is_running = False
async def on_canvas_click(event):
    global is_running
    if is_running:
        return
    is_running = True

    # Compute canvas-relative coordinates
    rect = canvas.getBoundingClientRect()
    x = event.clientX - rect.left
    y = event.clientY - rect.top

    # Call the Python function
    await fill_bitmap(bitmap, x // PIXEL_SIZE, y // PIXEL_SIZE)
    is_running = False

proxied_on_canvas_click = create_proxy(on_canvas_click)
# Attach event listener
canvas.addEventListener("click", proxied_on_canvas_click)
</py-script>
