import asyncio
import itertools

import js
from pyodide.ffi import create_proxy

# --- configuration ----------------------------------------------------
FF3_CELL_SIZE = 20
FF3_GRID_LINE_WIDTH = 2
FF3_GRID = [
    [1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,1,0],
    [0,1,1,1,1,1,0,0,1,0,1,1,1,1,1,1,0,0,1,0,1,1,1,1,1,1,0,1,0],
    [0,0,0,0,0,1,0,0,0,0,1,0,0,0,0,1,1,0,0,0,1,0,0,0,0,1,0,0,0],
    [1,1,1,1,0,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1],
    [0,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,1,0,0,0,1,0,0,0,0,0,0,0,0],
    [0,1,1,1,1,1,0,1,1,1,1,1,1,0,1,1,1,1,1,0,1,1,1,1,1,1,1,0,1],
    [0,0,0,0,0,0,0,1,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,1,0,0],
    [1,0,1,1,1,0,0,1,1,0,1,1,1,1,1,0,1,1,1,1,1,1,0,1,1,1,1,1,1],
    [0,0,0,0,1,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0],
    [0,1,1,0,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,0,1,1,1,1,1,1,0,1],
    [0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,1,0,0],
    [1,0,1,1,1,0,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,1,1,1,1,0,1,1,1],
    [0,0,0,0,0,0,1,0,0,0,0,0,0,0,1,0,0,0,0,0,0,0,0,0,1,0,0,0,0],
    [0,1,1,1,1,1,1,0,1,1,1,1,1,1,1,0,1,1,1,1,1,1,1,0,1,1,1,1,1],
]

FF3_ROWS = len(FF3_GRID)
FF3_COLS = len(FF3_GRID[0])

FF3_CANVAS_WIDTH = FF3_COLS * FF3_CELL_SIZE + (FF3_COLS + 1) * FF3_GRID_LINE_WIDTH
FF3_CANVAS_HEIGHT = FF3_ROWS * FF3_CELL_SIZE + (FF3_ROWS + 1) * FF3_GRID_LINE_WIDTH

# Read CSS custom properties from :root
root = js.document.documentElement
computed = js.window.getComputedStyle(root)

BG_COLOR = computed.getPropertyValue("--bg").strip()
FG_COLOR = computed.getPropertyValue("--tx").strip()
UI_COLOR = computed.getPropertyValue("--ui").strip()
AC_COLOR = computed.getPropertyValue("--accent").strip()
AC2_COLOR = computed.getPropertyValue("--accent-2").strip()
RE_COLOR = computed.getPropertyValue("--re").strip()
BL_COLOR = computed.getPropertyValue("--bl").strip()
GR_COLOR = computed.getPropertyValue("--gr").strip()
OR_COLOR = computed.getPropertyValue("--or").strip()

CONTRAST = {
    BG_COLOR: FG_COLOR,
    FG_COLOR: BG_COLOR,
    UI_COLOR: FG_COLOR,
    AC_COLOR: FG_COLOR,
    AC2_COLOR: FG_COLOR,
    RE_COLOR: FG_COLOR,
    BL_COLOR: FG_COLOR,
    GR_COLOR: FG_COLOR,
    OR_COLOR: FG_COLOR,
}

REGION_COLOURS = itertools.cycle([RE_COLOR, BL_COLOR, GR_COLOR, OR_COLOR])

# --- drawing helpers --------------------------------------------------
def ff3_draw_cells(ctx):
    for row in range(FF3_ROWS):
        for col in range(FF3_COLS):
            value = FF3_GRID[row][col]
            color = FG_COLOR if value else BG_COLOR
            ctx.fillStyle = color
            ctx.fillRect(
                col * FF3_CELL_SIZE + (col + 1) * FF3_GRID_LINE_WIDTH,
                row * FF3_CELL_SIZE + (row + 1) * FF3_GRID_LINE_WIDTH,
                FF3_CELL_SIZE,
                FF3_CELL_SIZE,
            )

def ff3_draw_gridlines(ctx):
    ctx.lineWidth = FF3_GRID_LINE_WIDTH
    ctx.fillStyle = UI_COLOR

    for c in range(FF3_COLS + 2):
        x = c * (FF3_CELL_SIZE + FF3_GRID_LINE_WIDTH)
        ctx.fillRect(
            x,
            0,
            FF3_GRID_LINE_WIDTH,
            FF3_CANVAS_HEIGHT,
        )

    for r in range(FF3_ROWS + 2):
        y = r * (FF3_CELL_SIZE + FF3_GRID_LINE_WIDTH)
        ctx.fillRect(
            0,
            y,
            FF3_CANVAS_WIDTH,
            FF3_GRID_LINE_WIDTH,
        )

def ff3_draw_grid():
    canvas = js.document.getElementById("ff3-grid-canvas")
    ctx = canvas.getContext("2d")
    canvas.width = FF3_CANVAS_WIDTH
    canvas.height = FF3_CANVAS_HEIGHT

    ff3_draw_cells(ctx)
    ff3_draw_gridlines(ctx)

# --- animation --------------------------------------------------------
class FF3Animation:
    def __init__(self, ctx, status_p):
        self.ctx = ctx
        self.status_p = status_p
        self.running = False
        self.current_task = None
        self.painted = set()  # cells already assigned to a region
        self.region_count = 0

    def draw_cell(self, x, y, colour):
        self.ctx.fillStyle = colour
        self.ctx.fillRect(
            x * FF3_CELL_SIZE + (x + 1) * FF3_GRID_LINE_WIDTH,
            y * FF3_CELL_SIZE + (y + 1) * FF3_GRID_LINE_WIDTH,
            FF3_CELL_SIZE,
            FF3_CELL_SIZE,
        )

    def reset(self):
        if self.current_task is not None:
            self.current_task.cancel()
            self.current_task = None
        self.running = False
        self.painted.clear()
        self.region_count = 0
        ff3_draw_grid()
        self.status_p.innerHTML = "Click “Count regions” to start the demo."

    async def floodfill_region(self, start, region_colour):
        neighbour_offsets = [(+1, 0), (0, +1), (-1, 0), (0, -1)]
        stack = [start]
        tracked = {start}

        sx, sy = start

        self.draw_cell(sx, sy, AC2_COLOR)

        while stack:
            x, y = stack.pop()

            # mark as being processed
            self.draw_cell(x, y, AC_COLOR)
            await asyncio.sleep(0.05)

            for dx, dy in neighbour_offsets:
                nx, ny = x + dx, y + dy
                if nx &lt; 0 or nx &gt;= FF3_COLS or ny &lt; 0 or ny &gt;= FF3_ROWS:
                    continue
                if (nx, ny) in tracked:
                    continue
                if FF3_GRID[ny][nx]:
                    continue

                tracked.add((nx, ny))
                stack.append((nx, ny))
                # queued cell
                self.draw_cell(nx, ny, AC2_COLOR)

            await asyncio.sleep(0.02)

        # repaint region cells with region colour, except the starting cell
        for (x, y) in tracked:
            if (x, y) == start:
                continue
            self.draw_cell(x, y, region_colour)

        # mark all cells as painted (including the start cell)
        print("Updating ")
        self.painted.update(tracked)

    async def run_all_regions(self):
        self.running = True
        self.painted.clear()
        self.region_count = 0
        ff3_draw_grid()

        try:
            for y in range(FF3_ROWS):
                for x in range(FF3_COLS):
                    print("@@@")
                    print(x, y)
                    print(self.painted)
                    print((x, y) in self.painted)
                    print("@@@")
                    if (x, y) in self.painted or FF3_GRID[y][x]:
                        continue
                    next_start = (x, y)
                    self.region_count += 1
                    region_colour = next(REGION_COLOURS)
                    self.status_p.innerHTML = (
                        f"Region {self.region_count}: starting at {next_start}."
                    )
                    await self.floodfill_region(next_start, region_colour)
                    await asyncio.sleep(0.1)

            self.status_p.innerHTML = f"Finished. Found {self.region_count} disconnected regions."
        except asyncio.CancelledError:
            self.status_p.innerHTML = "Cancelled."
        finally:
            self.running = False
            self.current_task = None

    def start_count(self):
        if self.running:
            return
        self.status_p.innerHTML = "Running region floodfills..."
        self.current_task = asyncio.create_task(self.run_all_regions())

# --- setup ------------------------------------------------------------
ff3_canvas = js.document.getElementById("ff3-grid-canvas")
ff3_draw_grid()

animator3 = FF3Animation(ff3_canvas.getContext("2d"), js.document.getElementById("ff3-grid-status"))

def ff3_handle_count_click(evt):
    animator3.start_count()

def ff3_handle_reset_click(evt):
    animator3.reset()

# attach event listeners
ff3_count_proxy = create_proxy(ff3_handle_count_click)
js.document.getElementById("ff3-count-button").addEventListener("click", ff3_count_proxy)

ff3_reset_proxy = create_proxy(ff3_handle_reset_click)
js.document.getElementById("ff3-reset-button").addEventListener("click", ff3_reset_proxy)
