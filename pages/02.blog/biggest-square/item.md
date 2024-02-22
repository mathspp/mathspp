How can you find the biggest free square in a 2D map with obstacles?

===

# Biggest square

## Problem statement

I just got a call from a government I cannot name.
They recently mapped out a sea to the Southeast of their coast.
They want to build a sea platform to study the ecosystems of that sea.
They need my help to figure out where to build that platform.

To show you what I'm working with, here is a portion of the map they created:

```
..o..
.o..o
.....
```

Each character represents a small square section of the sea.
The dots `.` represent sections where the sea bed is uniform whereas the circles `o` represent sections where the sea bed is not uniform, where there are shipwrecks, corals, and other things.

Their engineers said the platform must be square, so my job is to find the largest square region where the platform could be built.
For example, in the map below I'm marking the largest square region with crosses `x`:

```
..o..
.oxxo
..xx.
```

They also told me that if there are two or more square regions of the same size, they'll prefer the one that's closer to the top, and then to the left, of the map.

So, if the mapped out region were

```
ooo..
..o..
..ooo
..o..
..o..
```

They'd prefer to build their platform here:

```
oooxx
..oxx
..ooo
..o..
..o..
```


## Input test map

They're sending me the sea map in a file with the format shown below:

```
...........................
....o......................
............o..............
...........................
....o......................
...............o...........
...........................
......o..............o.....
..o.......o................
```

Save this map in a file and try to solve this problem.
You should be able to find this solution:

```
.....xxxxxxx...............
....oxxxxxxx...............
.....xxxxxxxo..............
.....xxxxxxx...............
....oxxxxxxx...............
.....xxxxxxx...o...........
.....xxxxxxx...............
......o..............o.....
..o.......o................
```

For bonus points, use the function below to generate more maps and test your solution on them:

```py
import random

def create_map(map_path: str, width: int, height: int, obstacle_prob: float) -> None:
    """Creates a map with the given size."""
    with open(map_path, "w") as file:
        for _ in range(height):
            for _ in range(width):
                char = "o" if random.random() < obstacle_prob else "."
                file.write(char)
            file.write("\n")
```

Also, can your solution handle maps of all sizes?
What if the maps are completely empty?
What if the maps are completely full?

After you've solved the problem yourself you can keep reading to see how I did it.


## Solution

The first thing I did was create a function that takes in a file path and retrieves the map from that file:

```py
def read_map(pathname: str) -> list[str]:
    """Reads the map from a file."""
    with open(pathname, "r") as file:
        return [line.strip() for line in file]
```

We can do this by opening the file for reading and then using a [list comprehension][list-comp] to go over the lines in the file and stripping them of the trailing newline.

Now, we must think about the way in which we'll approach the problem.
What I'll do is traverse the whole map and I'll look at each position as the possible top-left corner for the platform.
Then, I'll incrementally try to expand the size of the platform until I reach an obstacle.
When I do, I take note of how large the platform grew and then I keep testing new locations.

Before implementing the actual solving algorithm I need a way to represent a square location, so I'll use a `dataclass`:

```py
from dataclasses import dataclass

@dataclass
class Square:
    """Represents a possible square region for the platform."""

    x: int
    y: int
    length: int
```

This will hold the information regarding the biggest square I've found so far as I traverse the map.

Next, I implement the logic of checking how big of a platform I can build in a given position `(x, y)`:

```py
def expand(x: int, y: int, map: list[str]) -> int:
    """Finds the largest possible square with top-left corner at (x, y)."""
    length = 2
    while y + length - 1 < len(map) and x + length - 1 < len(map[0]):
        for dx in range(length):
            if map[y + length - 1][x + dx] != ".":
                return length - 1
        for dy in range(length):
            if map[y + dy][x + length - 1] != ".":
                return length - 1
        length += 1
    return length - 1
```

Suppose we have a map that looks like the one below and we call the function with `expand(0, 0, map)`.

```
...o..
......
......
......
```

When we call the function, we're setting the top-left corner of the square we'll test for:

```
x..o..
......
......
......
```

Then, we set `length = 2` and we try to fit a square of length `2` in the map.
The two `for` loops check the positions immediately below and immediately to the right of the current square.
These new positions are marked with a capital `X` below:

```
xX.o..
XX....
......
......
```

Because the bigger square was successfully built, we increment `length += 1` to check for a square of length `3`.
Again, the two `for` loops will check the positions to the right and below the square we already built.
The positions we check at this point are marked with a capital `X` below:

```
xxXo..
xxX...
XXX...
......
```

Again, this succeeds, so we go to `length = 4`.
At this point, we hit an obstacle `@`:

```
xxx@..
xxxX..
xxxX..
XXXX..
```

So, we get out of the loop returning `length - 1`, which is `3`.

Finally, I need to make use of the function `expand` inside a loop that traverses the whole map:

```py
def find_biggest_square(map: list[str]) -> Square:
    """Find the biggest free square region in the given map."""
    bsq_so_far = Square(0, 0, 0)
    for y in range(len(map)):
        for x in range(len(map[0])):
            if map[y][x] != ".":
                continue

            length = expand(x, y, map)
            if length > bsq_so_far.length:
                bsq_so_far.length = length
                bsq_so_far.x = x
                bsq_so_far.y = y

            if x + bsq_so_far.length >= len(map[0]):
                break

        if y + bsq_so_far.length >= len(map):
            break

    return bsq_so_far
```

This code has nothing too magical about it.
The only clever thing we do is avoid looking for big squares too close to the right and bottom margins of the map.
If we already found a square of length 5, there is no point in looking for a bigger square with a top-left corner 3 columns off of the right margin of the map, as it wouldn't fit the map.

For example, if the map is as shown below (with the biggest square marked already), it wouldn't make sense to consider testing the position marked with a question mark `?`:

```
xxxxxoooo
xxxxx..o.
xxxxx.?..
xxxxx....
...o.....
.o.o.o...
```

If we did, we'd be saying that we think a square of size 6 or larger could be there, but that wouldn't fit the map:

```
xxxxxoooo
xxxxx..o.
xxxxx.xxxxxx
xxxxx.xxxxxx
...o..xxxxxx
.o.o.oxxxxxx
      xxxxxx
      xxxxxx
```


To wrap up, we create a small function that prints the map with its biggest square and we run the whole thing:

```py
def print_map_with_square(map: list[str], bsq: Square) -> None:
    """Prints the map with the largest square marked."""
    for y in range(bsq.y):
        print(map[y])
    for y in range(bsq.y, bsq.y + bsq.length):
        print(map[y][: bsq.x] + "x" * bsq.length + map[y][bsq.x + bsq.length :])
    for y in range(bsq.y + bsq.length, len(map)):
        print(map[y])


if __name__ == "__main__":
    map = read_map("map.txt")
    bsq = find_biggest_square(map)
    print_map_with_square(map, bsq)
```

If you run the whole thing with the input map shown above, you'll get the expected output.

This is the type of problem that you have to solve when you join the [Python problem-solving bootcamp](/pythonbootcamp), except that in the bootcamp I provide an in-depth analysis of different solutions instead of providing just one solution.
If you come up with a different solution, comment it below!


[list-comp]: /blog/pydonts/list-comprehensions-101
