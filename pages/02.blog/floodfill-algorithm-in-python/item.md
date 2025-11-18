Learn how to implement and use the floodfill algorithm in Python.

===

<link rel="stylesheet" href="https://pyscript.net/releases/2025.11.1/core.css" />
<script type="module" src="https://pyscript.net/releases/2025.11.1/core.js"></script>


## What is the floodfill algorithm?

Click the image below to randomly colour the region you click.

Go ahead, try it!

<canvas id="bitmap" width="320" height="320" style="display: block; margin: 0 auto;"></canvas>

<script>
function set_canvas_loading(canvas) {
    let ctx = canvas.getContext("2d");

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
}

set_canvas_loading(document.getElementById("bitmap"));
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


## Understanding the floodfill algorithm

The floodfill algorithm does not have a lot of moving parts and, because it can be visualised as paint filling up a region of a drawing, it is a great stepping stone for someone looking to learn more about graph algorithms.
Now, you'll understand how it works in the context of painting the Python logo.

The algorithm will be implemented as a function `floodfill` with two arguments:

 1. a grid representing the locations of the walls; and
 2. the `x` and `y` coordinates of the starting point (the place you clicked).

```py
def floodfill(walls, x, y):
    pass
```

Ok, I lied.
The function has three arguments, really.

If the position you click is a wall, you don't want to do anything and you return from the function right away:

```py
def floodfill(walls, x, y):
    if walls[y][x]:
        return
```

In here, you can assume that `walls` is a list of lists, where each list represents a row of the image.
So, the first piece of code checks if you clicked a wall, because you don't want to change the colour of walls.

Next, you need a way to represent the growing region of colour, and that's represented by a growing collection of all of the pixels you already painted.
To make it very efficient, you can use a set instead of a list, and this set starts empty because you haven't painted anything yet:

```py
def floodfill(walls, x, y):
    if walls[y][x]:
        return

    painted = set()  # <--
```

You also need another collection to keep track of all the points you still need to paint.
In the beginning, you already know you _have_ to paint your starting point:

```py
def floodfill(walls, x, y):
    if walls[y][x]:
        return

    painted = set()
    to_paint = [(x, y)]  # <--
```

Now comes the fun part, which is the `while` loop that ensures your painted region keeps growing to fill in every empty spot!
You want to write a loop that runs _while_ there are pixels _to be painted_:

```py
def floodfill(walls, x, y):
    if walls[y][x]:
        return

    painted = set()
    to_paint = [(x, y)]
    while to_paint:  # <--
        ...          # <--
```

Inside this loop you need to do four things:

 1. get a new pixel to process from the list of pixels to process;
 2. paint the pixel you're processing now;
 3. mark this pixel as having been painted; and
 4. find if the neighbours of the current pixel must be painted also.

To do the first thing, you can just pop from the list `to_paint`:

```py
def floodfill(walls, x, y):
    if walls[y][x]:
        return

    painted = set()
    to_paint = [(x, y)]
    while to_paint:
        this = to_paint.pop()  # <--
```

To paint this pixel, you can assume you have an auxiliary function that does that:

```py
def draw_pixel(p): pass  # Do-nothing; just so the code works.

def floodfill(walls, x, y):
    if walls[y][x]:
        return

    painted = set()
    to_paint = [(x, y)]
    while to_paint:
        this_pixel = to_paint.pop()
        draw_pixel(this_pixel)  # <--
```

And now that your pixel has been painted, you can mark it as having been painted:

```py

def draw_pixel(p): pass  # Do-nothing; just so the code works.

def floodfill(walls, x, y):
    if walls[y][x]:
        return

    painted = set()
    to_paint = [(x, y)]
    while to_paint:
        this_pixel = to_paint.pop()
        draw_pixel(this_pixel)
        painted.add(this_pixel)  # <--
```

The fourth and final step is the most important one, though: “find if the neighbours of the current pixel must be painted also”.

One of my favourite ways of looking at the neighbours of a pixel is realising that the neighbours of a pixel have coordinates that are _very similar_ to the original pixel; all I have to do is add or subtract 1 from either coordinate, as the diagram below shows:

![Diagram showing that the pixels next to a pixel have the same coordinates, up to a plus/minus 1 on one of the coordinates.](_grid_neighbours.excalidraw.webp "Neighbours of a pixel.")

To represent this, I usually create a list with the small offsets and then use a loop to go through the offsets and modify the coordinates of the base pixel:

```py
neighbour_offsets = [(+1, 0), (0, +1), (-1, 0), (0, -1)]  # <--

def draw_pixel(p): pass  # Do-nothing; just so the code works.

def floodfill(walls, x, y):
    if walls[y][x]:
        return

    painted = set()
    to_paint = [(x, y)]
    while to_paint:
        this_pixel = to_paint.pop()
        draw_pixel(this_pixel)
        painted.add(this_pixel)
        tx, ty = this_pixel               # <--
        for dx, dy in neighbour_offsets:  # <--
            nx, ny = tx + dx, ty + dy     # <--
```

The coordinates `tx, ty` represent the pixel you just painted; the offsets `dx, dy` represent the small jump you need to take from the base pixel to a neighbouring pixel; and the coordinates `nx, ny` represent the coordinates of the neighbour pixel.

For each of the neighbours, you need to check if that's still inside the grid and, if it is, if it's a wall or an empty space.
If the neighbour is outside of the grid or a wall, you don't want to do anything with that neighbour and you skip to the next one:

```py
neighbour_offsets = [(+1, 0), (0, +1), (-1, 0), (0, -1)]

def draw_pixel(p): pass  # Do-nothing; just so the code works.

def floodfill(walls, x, y):
    if walls[y][x]:
        return

    HEIGHT = len(walls)
    WIDTH = len(walls[0])

    painted = set()
    to_paint = [(x, y)]
    while to_paint:
        this_pixel = to_paint.pop()
        draw_pixel(this_pixel)
        painted.add(this_pixel)
        tx, ty = this_pixel
        for dx, dy in neighbour_offsets:
            nx, ny = tx + dx, ty + dy

            if (
                nx < 0 or nx >= WIDTH  # Is nx too big/small?
                or ny < 0 or ny >= HEIGHT  # Is ny too big/small?
                or walls[ny][nx]  # Is this a wall?
            ):
                continue
```

If the neighbour pixel is a valid pixel that is _not_ a wall, then you can add it to the list of pixels to paint next!
As long as this pixel hasn't been painted yet, of course:

```py
neighbour_offsets = [(+1, 0), (0, +1), (-1, 0), (0, -1)]

def draw_pixel(p): pass  # Do-nothing; just so the code works.

def floodfill(walls, x, y):
    if walls[y][x]:
        return

    HEIGHT = len(walls)
    WIDTH = len(walls[0])

    painted = set()
    to_paint = [(x, y)]
    while to_paint:
        print(to_paint)
        this_pixel = to_paint.pop()
        draw_pixel(this_pixel)
        painted.add(this_pixel)
        tx, ty = this_pixel
        for dx, dy in neighbour_offsets:
            nx, ny = tx + dx, ty + dy

            if (
                nx < 0 or nx >= WIDTH
                or ny < 0 or ny >= HEIGHT
                or walls[ny][nx]
            ):
                continue

            if (nx, ny) not in painted:    # <--
                to_paint.append((nx, ny))  # <--
```

That's it!
This is enough to use the floodfill algorithm and this is _very_ close to what I actually used to paint the Python logo above.


## Optimising the floodfill algorithm to avoid duplicated work

There is a key difference between the algorithm I'm using to paint the Python logo and the algorithm you just used, and it has to do with the role that the set `painted` has.
The main objective of the set `painted` is to avoid wasting time painting the same pixel more than once, but what you _really_ want is to not waste any time whatsoever.

If you modify the function `floodfill` to add a couple of calls to `print` and if you call it with a small grid, you will find that you can end up with duplicated points in the list `to_paint`:

```py
neighbour_offsets = [(+1, 0), (0, +1), (-1, 0), (0, -1)]

def draw_pixel(p): pass  # Do-nothing; just so the code works.

def floodfill(walls, x, y):
    # ...

    painted = set()
    to_paint = [(x, y)]
    while to_paint:
        print(to_paint)  # <--
        # ...


grid = [[0, 0, 0], [0, 0, 0], [0, 0, 0]]
floodfill(grid, 1, 1)
```
```txt
[(1, 1)]
[(2, 1), (1, 2), (0, 1), (1, 0)]
[(2, 1), (1, 2), (0, 1), (2, 0), (0, 0)]
[(2, 1), (1, 2), (0, 1), (2, 0), (0, 1)]
# ...
```

The first four lines of output are shown above and the fourth line of output has the pixel `(0, 1)` repeated in the third and fifth positions.
This means we'll process this pixel twice.
For this small 3 x 3 grid, this isn't a big problem...
But for big grids, these overlaps will be costly and waste a lot of your time.

Instead of keeping track of the pixels that have been painted already, you can keep track of the pixels that you already queued up for painting.
This means you add a new pixel to the set _at the same time_ as you add it to the list of pixels to paint:

```py
neighbour_offsets = [(+1, 0), (0, +1), (-1, 0), (0, -1)]

def draw_pixel(p): pass  # Do-nothing; just so the code works.

def floodfill(walls, x, y):
    if walls[y][x]:
        return

    HEIGHT = len(walls)
    WIDTH = len(walls[0])

    tracked = set((x, y))  # <-- The starting point starts in the set.
    to_paint = [(x, y)]
    while to_paint:
        this_pixel = to_paint.pop()
        draw_pixel(this_pixel)
        tx, ty = this_pixel
        for dx, dy in neighbour_offsets:
            nx, ny = tx + dx, ty + dy

            if (
                nx < 0 or nx >= WIDTH
                or ny < 0 or ny >= HEIGHT
                or walls[ny][nx]
            ):
                continue

            if (nx, ny) not in tracked:  # <--
                tracked.add((nx, ny))    # <-- Add it to the set right away.
                to_paint.append((nx, ny))
```


## Visualising the floodfill algorithm step by step

Hopefull you understood the prose that explains the algorithm...
But there's nothing like seeing it in action.
The widget below lets you step through the floodfill algorithm as it tries to paint the middle region in pink.
The cells shown in purple are cells that have been added to the list `to_paint`, but haven't been painted yet.


<p>
  <span style="color: var(--accent);">█</span> <code>tracked</code>;&nbsp;
  <span style="color: var(--accent-2);">█</span> <code>to_paint</code>:&nbsp;
  <code id="ff-grid-to_paint-values"></code>
</p>
<canvas id="ff-grid" width="690" height="438" style="display: block; margin: 0 auto;"></canvas>
<p id="ff-grid-status">Starting the floodfill algorithm from the centre square. Press “Next”.</p>
<div style="display:flex; justify-content:center; gap: 1em;">
<button id="reset" class="button">Reset</button>
<button id="next" class="button">Next</button>
</div>

<script>
set_canvas_loading(document.getElementById("ff-grid"))
</script>

<py-script>
import js
from pyodide.ffi import create_proxy  # you'll likely use this later

# --- configuration ----------------------------------------------------
CELL_SIZE = 60
GRID_LINE_WIDTH = 3
GRID = [
    [0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0],
    [0, 0, 0, 1, 1, 0, 1, 0, 0, 0, 0],
    [0, 0, 1, 1, 1, 0, 1, 1, 0, 0, 0],
    [1, 1, 1, 0, 0, 0, 0, 1, 1, 0, 0],
    [1, 1, 0, 0, 0, 0, 0, 0, 1, 0, 0],
    [1, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0],
    [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0],
]
START = (5, 3)

ROWS = len(GRID)
COLS = len(GRID[0])

CANVAS_WIDTH = COLS * CELL_SIZE + (COLS + 1) * GRID_LINE_WIDTH
CANVAS_HEIGHT = ROWS * CELL_SIZE + (ROWS + 1) * GRID_LINE_WIDTH

# Read CSS custom properties from :root
root = js.document.documentElement
computed = js.window.getComputedStyle(root)

BG_COLOR = computed.getPropertyValue("--bg").strip()
FG_COLOR = computed.getPropertyValue("--tx").strip()
UI_COLOR = computed.getPropertyValue("--ui").strip()
AC_COLOR = computed.getPropertyValue("--accent").strip()
AC2_COLOR = computed.getPropertyValue("--accent-2").strip()
RE_COLOR = computed.getPropertyValue("--re").strip()

# --- drawing helpers --------------------------------------------------
def draw_cells(ctx):
    for row in range(ROWS):
        for col in range(COLS):
            value = GRID[row][col]
            color = BG_COLOR if value == 0 else FG_COLOR
            ctx.fillStyle = color
            ctx.fillRect(
                col * CELL_SIZE + (col + 1) * GRID_LINE_WIDTH,
                row * CELL_SIZE + (row + 1) * GRID_LINE_WIDTH,
                CELL_SIZE,
                CELL_SIZE,
            )

def draw_gridlines(ctx):
    ctx.lineWidth = 3
    ctx.fillStyle = UI_COLOR

    # I'm drawing the lines as rectangles because it's easier to control
    # the position of the corners of the “thick lines” this way.
    for c in range(COLS + 2):
        x = c * (CELL_SIZE + GRID_LINE_WIDTH)
        ctx.fillRect(
            x,
            0,
            GRID_LINE_WIDTH,
            CANVAS_HEIGHT,
        )

    for r in range(ROWS + 2):
        y = r * (CELL_SIZE + GRID_LINE_WIDTH)
        ctx.fillRect(
            0,
            y,
            CANVAS_WIDTH,
            GRID_LINE_WIDTH,
        )

def draw_grid():
    canvas = js.document.getElementById("ff-grid")
    ctx = canvas.getContext("2d")
    # Ensure canvas has the correct internal size
    canvas.width = CANVAS_WIDTH
    canvas.height = CANVAS_HEIGHT

    draw_cells(ctx)
    draw_gridlines(ctx)

class Animation:
    def __init__(self, ctx, status_p):
        self.ctx = ctx
        self.status_p = status_p
        self.tracked = set()
        self.to_paint = []
        self.animation_ff = None

    def mark_cell(self, x, y):
        self.ctx.strokeStyle = RE_COLOR
        cx = x * CELL_SIZE + (x + 1) * GRID_LINE_WIDTH + CELL_SIZE // 2
        cy = y * CELL_SIZE + (y + 1) * GRID_LINE_WIDTH + CELL_SIZE // 2
        self.ctx.beginPath()
        self.ctx.arc(cx, cy, 3 * CELL_SIZE // 10, 0, 2 * js.Math.PI)
        self.ctx.stroke()

    def clear_cell(self, x, y):
        if (x, y) in self.to_paint:
            colour = AC2_COLOR
        elif (x, y) in self.tracked:
            colour = AC_COLOR
        elif GRID[y][x]:
            colour = FG_COLOR
        else:
            colour = BG_COLOR
        self.draw_cell(x, y, colour)

    def draw_cell(self, x, y, colour):
        self.ctx.fillStyle = colour
        self.ctx.fillRect(
            x * CELL_SIZE + (x + 1) * GRID_LINE_WIDTH,
            y * CELL_SIZE + (y + 1) * GRID_LINE_WIDTH,
            CELL_SIZE,
            CELL_SIZE,
        )

    def start(self):
        draw_grid()
        self.status_p.innerHTML = "Starting the floodfill algorithm from the centre square. Press “Next”."
        self.tracked = {START}
        self.to_paint = [START]
        self.draw_cell(*START, AC2_COLOR)
        self.sync_to_paint()
        self.animation_ff = self.floodfill()

    def sync_to_paint(self):
        elem = js.document.getElementById("ff-grid-to_paint-values")
        elem.innerHTML = ", ".join(map(str, self.to_paint))

    def animation_step(self):
        if self.animation_ff is None:
            self.start()
        try:
            msg = next(self.animation_ff)
        except StopIteration:
            msg = "Done"
        self.status_p.innerHTML = msg
        print(msg)

    def floodfill(self):
        print("starting ff")
        neighbour_offsets = [(+1, 0), (0, +1), (-1, 0), (0, -1)]
        neighbour_msgs = {
            (1, 0): "Checking the cell on the right...",
            (-1, 0): "Checking the cell on the left...",
            (0, 1): "Checking the cell below...",
            (0, -1): "Checking the cell above...",
        }

        while self.to_paint:
            this_pixel = self.to_paint.pop()
            self.sync_to_paint()
            print(f"Processing {this_pixel = }")
            tx, ty = this_pixel
            self.mark_cell(tx, ty)
            yield f"Will now process {this_pixel}."
            self.draw_cell(tx, ty, AC_COLOR)
            self.mark_cell(tx, ty)
            yield f"The cell {this_pixel} has now been coloured. Now, we check its neighbours."

            for dx, dy in neighbour_offsets:
                nx, ny = tx + dx, ty + dy

                # Produce nice message about neighbour to process.
                if not (nx &lt; 0 or nx &gt;= COLS or ny &lt; 0 or ny &gt;= ROWS):
                    self.mark_cell(nx, ny)
                yield neighbour_msgs[(dx, dy)]

                if nx &lt; 0 or nx &gt;= COLS or ny &lt; 0 or ny &gt;= ROWS:
                    yield f"... oh wait, there's no cell there because the grid ends here."
                    continue
                elif GRID[ny][nx]:
                    yield f"Will skip this neighbour because it's a wall!"
                    self.clear_cell(nx, ny)
                    continue

                if (nx, ny) not in self.tracked:
                    self.tracked.add((nx, ny))
                    self.to_paint.append((nx, ny))
                    self.sync_to_paint()
                    self.draw_cell(nx, ny, AC2_COLOR)
                    yield f"Tracked and set neighbour to paint later."
                else:
                    yield f"Skipped because it was tracked already!"
            self.clear_cell(tx, ty)

animator = Animation(
    js.document.getElementById("ff-grid").getContext("2d"),
    js.document.getElementById("ff-grid-status"),
)

proxied_start = create_proxy(lambda evt: animator.start())
js.document.getElementById("reset").addEventListener("click", proxied_start)

proxied_animation_step = create_proxy(lambda evt: animator.animation_step())
js.document.getElementById("next").addEventListener("click", proxied_animation_step)

# Initial reset
animator.start()
</py-script>
