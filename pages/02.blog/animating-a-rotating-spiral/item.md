With a couple of loops and a bit of maths you can create a rotating spiral.

===


<video width="400" height="400" poster="/blog/animating-a-rotating-spiral/_rotating.mp4.thumb.png" controls>
  <source src="/blog/animating-a-rotating-spiral/_rotating.mp4" type="video/mp4">
  A video animation of a colourful rotating spiral that keeps expanding and contracting and changing colour.
</video>

# Animating a rotating spiral

Following up on the concepts covered in my previous article [“Animations from first principles in 5 minutes”](/blog/animations-from-first-principles-in-5-minutes) and [“More animations from first principles in 5 minutes”](/blog/more-animations-from-first-principles-in-5-minutes), in this article we will create the animation you can see above.

We start by modifying the parametrisation of the circle to create a spiral:

```py
SIDE = 600

def spiral(percentage):
    return (
        SIDE // 2
        * percentage
        * cos(10 * pi * percentage),
        SIDE // 2
        * percentage
        * sin(10 * pi * percentage),
    )
```

The `10` inside `cos`/`sin` dictate how many turns the spiral does, all you have to do is divide that number by `2`, so a `10` means we do `5` turns around the centre of the spiral.

You can “easily” put the spiral on the screen:

```py
from itertools import product
from math import sin, cos, pi

import pygame


SIDE = 600
WHITE = (255, 255, 255)
BLACK = (0, 0, 0)

screen = pygame.display.set_mode((SIDE, SIDE))
screen.fill(WHITE)


def draw_pixel(screen, x, y, colour):
    x, y = round(x), round(y)
    for dx, dy in product(range(-1, 2), repeat=2):
        screen.set_at((x + dx, y + dy), colour)


def spiral(percentage):
    return (
        SIDE // 2 * percentage * cos(10 * pi * percentage),
        SIDE // 2 * percentage * sin(10 * pi * percentage),
    )


STEPS = 3000
for step in range(STEPS + 1):
    percentage = step / STEPS
    x, y = rotating_spiral(percentage, tick / 10)
    draw_pixel(screen, x, y, BLACK)
pygame.display.flip()
input()
```

By modifying the function `spiral` to accept an argument that represents time and by creating an outer loop that emulates ticking of time, we can rotate this spiral:

```py
# ...

def spiral(percentage, time):
    return (
        SIDE // 2 * percentage * cos(10 * pi * percentage + time),
        SIDE // 2 * percentage * sin(10 * pi * percentage + time),
    )

# ...

STEPS = 3000
for tick in count():
    screen.fill(WHITE)
    for step in range(STEPS + 1):
        percentage = step / STEPS
        x, y = rotating_spiral(percentage, tick / 10)
        draw_pixel(screen, x, y, BLACK)
    pygame.display.flip()
```

To make the spiral expand and contract, we must make it so that the radius has to change as time ticks:

```py
def rotating_spiral(percentage, time):
    return (
        SIDE // 2
        + (1 + sin(time) / 10)  # <-- new
        * percentage * (SIDE // 3) * cos(10 * pi * percentage + time),
        SIDE // 2
        + (1 + sin(time) / 10)  # <-- new
        * percentage * (SIDE // 3) * sin(10 * pi * percentage + time),
    )
```

Finally, to add colour, we create two functions that generate the background and foreground colours for each frame instead of using the constants `WHITE` / `BLACK`:

```py
# ...

def bg(time):
    return (
        40 + int(abs(30 * sin(0.05 * time))),
        40 + int(abs(30 * sin(0.05 * time))),
        40 + int(abs(30 * sin(0.05 * time))),
    )


def fg(time):
    return (
        255 - int(abs(15 * sin(0.1 * time))),
        85 + int(abs(160 * sin(0.1 * time))),
        85 + int(abs(60 * sin(0.1 * time))),
    )

STEPS = 3000
for tick in count(step=0.1):  # <-- smaller tick
    screen.fill(bg(tick))
    clr = fg(tick)
    for step in range(STEPS + 1):
        percentage = step / STEPS
        x, y = rotating_spiral(percentage, tick)
        draw_pixel(screen, x, y, clr)
    pygame.display.flip()
```

Running the code above _should_ produce the following animation:


<video width="400" height="400" poster="/blog/animating-a-rotating-spiral/_rotating.mp4.thumb.png" controls>
  <source src="/blog/animating-a-rotating-spiral/_rotating.mp4" type="video/mp4">
  A video animation of a colourful rotating spiral that keeps expanding and contracting and changing colour.
</video>
