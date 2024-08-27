The function `random.shuffle` relies on the mutability of the argument and mutability is a pain in the arse, so we propose an alternative.

===


# Mutability and `random.shuffle`

The function `random.shuffle` shuffles its argument in place, which means it relies on the mutability of the argument.
This can introduce bugs if care is not taken.
Consider the snippet of code that follows:

```py
def generate_maze(base_width, base_height):
    width = 2 * base_width + 1
    height = 2 * base_height + 1
    # Initialize the grid with walls
    maze = [[0 for _ in range(width)] for _ in range(height)]

    DIRECTIONS = [(-1, 0), (1, 0), (0, -1), (0, 1)]

    def carve_passages(x, y):
        # Mark the current cell as part of the maze.
        maze[y][x] = 1

        random.shuffle(DIRECTIONS)

        for dx, dy in DIRECTIONS:
            nx, ny = x + 2 * dx, y + 2 * dy
            if (
                0 <= nx < width
                and 0 <= ny < height
                and maze[ny][nx] == 0
            ):
                # Carve through the wall between the current cell and the neighbor
                maze[y + dy][x + dx] = 1
                carve_passages(nx, ny)

    carve_passages(
        2 * random.randint(0, base_width), 2 * random.randint(0, base_height)
    )

    return maze
```

The function `generate_maze` uses depth-first search to create a maze like the following, when `base_width = 2` and `base_height = 2`:

```txt
1 1 1 0 1
1 0 0 0 1
1 1 1 1 1
1 0 1 0 1
1 0 1 0 1
```

The path of the maze is represented by the `1`s and the idea of the algorithm is that it will create a fully connected region, connecting all of the cells marked with an `x`:

```txt
x . x . x
. . . . .
x . x . x
. . . . .
x . x . x
```

However, from time to time, the function `generate_maze` generates an incomplete maze, like this:

```txt
1 1 1 0 1
1 0 0 0 1
1 1 1 1 1
1 0 0 0 0
1 0 0 0 0
```

Notice how the bottom-right corner has too many `0`s in a row, and in particular there are two `x`s from the diagram above that were not added to the maze.

After some debugging, I understood the issue: the list `DIRECTIONS` was being shuffled in-place by the call to `random.shuffle` and when I called `carve_passages` recursively, the inner call would reshuffle the list `DIRECTIONS` while the outer call was still traversing the the list `DIRECTIONS`, which would introduce a bug in the loop.

You can fix this by creating a copy of the list every time you want to shuffle it, but a better alternative seems to be to use the function `random.sample` and set the parameter `k` to the size of the list, like this:

```py
def generate_maze(base_width, base_height):
    # ...

    DIRECTIONS = [(-1, 0), (1, 0), (0, -1), (0, 1)]

    def carve_passages(x, y):
        # Mark the current cell as part of the maze.
        maze[y][x] = 1

        for dx, dy in random.sample(DIRECTIONS, 4):  # <--
            # ...
```

Cheers to [Tushar who suggested this alternative on X](https://x.com/tusharisanerd/status/1828455547919855728).
