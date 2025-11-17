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

// Get computed values of CSS variables
const styles = getComputedStyle(document.documentElement);
const bg = styles.getPropertyValue("--bg").trim();
const fg = styles.getPropertyValue("--accent").trim();

ctx.fillStyle = bg;
ctx.fillRect(0, 0, canvas.width, canvas.height);
ctx.fillStyle = fg;
ctx.font = "36px Atkinson Hyperlegible";
ctx.textAlign = "center";
ctx.textBaseline = "middle";
ctx.fillText("Loading...", canvas.width / 2, canvas.height / 2);
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

is_running = False

def get_event_coords(event):
    """Return (clientX, clientY) for mouse/pointer/touch events."""
    # PointerEvent / MouseEvent: clientX/clientY directly available
    if hasattr(event, "clientX") and hasattr(event, "clientY") and event.clientX is not None:
        return event.clientX, event.clientY

    # TouchEvent: use the first touch point
    if hasattr(event, "touches") and event.touches.length &gt; 0:
        touch = event.touches.item(0)
        return touch.clientX, touch.clientY

    # Fallback: try changedTouches
    if hasattr(event, "changedTouches") and event.changedTouches.length &gt; 0:
        touch = event.changedTouches.item(0)
        return touch.clientX, touch.clientY

    return None, None

async def on_canvas_press(event):
    global is_running

    if is_running:
        return

    is_running = True
    try:
        # Avoid scrolling / zooming taking over on touch
        if hasattr(event, "preventDefault"):
            event.preventDefault()

        clientX, clientY = get_event_coords(event)
        if clientX is None:
            # Could not read coordinates; bail out gracefully
            return

        rect = canvas.getBoundingClientRect()

        # Account for CSS scaling: map from displayed size to canvas units
        scale_x = canvas.width / rect.width
        scale_y = canvas.height / rect.height

        x_canvas = (clientX - rect.left) * scale_x
        y_canvas = (clientY - rect.top) * scale_y

        x_idx = int(x_canvas // PIXEL_SIZE)
        y_idx = int(y_canvas // PIXEL_SIZE)

        # Bounds check just to be safe
        if 0 &lt;= x_idx &lt; IMG_WIDTH and 0 &lt;= y_idx &lt; IMG_HEIGHT:
            await fill_bitmap(bitmap, x_idx, y_idx)
    finally:
        # Ensure the flag is always reset, even if something raises
        is_running = False

bitmap = await load_bitmap(URL)
draw_bitmap(bitmap)

proxied_on_canvas_press = create_proxy(on_canvas_press)
# Attach event listener
canvas.addEventListener("pointerdown", proxied_on_canvas_press)
canvas.addEventListener("touchstart", proxied_on_canvas_press)
</py-script>
<br />


If you click the image, you will see colour spread out from the place you clicked, filling in the region you clicked on.
If you click one of the eyes of the snakes, the eye fills pretty quickly and you can barely see it...
If you click one of the snakes, you can see the colour spread to fill the entire snake...
And if you click the outside area, you see the colour spread in weird ways, going around the snakes and into the tight corners between the two snakes.

But regardless of where you click, you see that the colour spreading will always stay _inside_ the region you clicked.
And the floodfill algorithm is the algorithm that allows you to implement this behaviour:

 1. spread out from the starting point; but
 2. remain constrained inside a region.


## Implementing the floodfill algorithm

The floodfill algorithm does not have a lot of moving parts and, because it can be visualised as paint filling up a region of a drawing, it is a great stepping stone for someone looking to learn more about graph algorithms.
The floodfill algorithm that is used to paint the Python logo looks like this:

```py
import collections

async def fill_bitmap(bitmap, x, y):
    pixels = collections.deque([(x, y)])
    seen = set((x, y))
    while pixels:
        nx, ny = pixels.pop()
        draw_pixel(nx, ny)
        for dx, dy in _neighbours:
            x_, y_ = nx + dx, ny + dy
            if x_ <= 0 or x_ >= IMG_WIDTH or y_ < 0 or y_ >= IMG_HEIGHT or (x_, y_) in seen:
                continue
            if bitmap[y_][x_] == 0:
                seen.add((x_, y_))
                pixels.appendleft((x_, y_))
        await asyncio.sleep(0.0001)
```
