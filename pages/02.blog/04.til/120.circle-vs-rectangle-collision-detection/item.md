Today I learned how to detect collisions between circles and rectangles with 100% accuracy.

===


# Circle vs rectangle collision detection

In a [JavaScript tutorial I published recently](/blog/javascript-2d-scrolling-game-tutorial), the game that I presented included some very basic collision detection between a circle and a rectangle, and it used the circle's bounding box.
This meant that the collision detection sucked if the circle was close to the corners of the rectangle.

Today I sat down to think about it for a second and figured out how to implement pixel-perfect collision detection between a rectangle and a circle, as the demo below demonstrates:

<canvas id="mainCanvas1" style="background:var(--ui); margin: auto; display: block;"></canvas>

<script>
    const canvas = document.getElementById("mainCanvas1");
    const ctx = canvas.getContext("2d");

    const WIDTH = Math.min(600, 0.95 * document.documentElement.clientWidth);
    const HEIGHT = 400;
    canvas.width = WIDTH;
    canvas.height = HEIGHT;

    var style = window.getComputedStyle(document.body);
    const RED = style.getPropertyValue("--re");
    const GREEN = style.getPropertyValue("--gr");
    const CIRCLE_COLOUR = style.getPropertyValue("--tx");

    const radius = 25;

    function draw1(evt) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Get mouse position.
        var rect = canvas.getBoundingClientRect();
        var x = evt.clientX - rect.left;
        var y = evt.clientY - rect.top;

        // Draw the rectangle.
        ctx.fillStyle = collision(x, y) ? RED : GREEN;
        ctx.fillRect(WIDTH / 4, HEIGHT / 4, WIDTH / 2, HEIGHT / 2);

        // Draw the circle.
        ctx.fillStyle = CIRCLE_COLOUR;
        ctx.beginPath();
        ctx.arc(x, y, radius, 0, 2 * Math.PI);
        ctx.fill();
    }

    function collision(x, y) {
        var left = WIDTH / 4, right = 3 * WIDTH / 4;
        var top = HEIGHT / 4, bottom = 3 * HEIGHT / 4;
        var corners = [
            { x: left, y: top },
            { x: right, y: top },
            { x: left, y: bottom },
            { x: right, y: bottom },
        ];
        for (var c of corners) {
            if ((c.x - x) ** 2 + (c.y - y) ** 2 <= radius ** 2) {
                return true;
            }
        }
        return (x >= left - radius && x <= right + radius && y >= top && y <= bottom) || (x >= left && x <= right && y >= top - radius && y <= bottom + radius);
    }

    document.addEventListener(
        "mousemove",
        draw1,
    );
</script>


The first thing I implemented was pixel-perfect collision detection with the _corners_ of the rectangle.
This is easy to check because each corner is a single point and to check if a single point is inside a circle amounts to checking if the distance between the point and the centre of the circle is less than the radius of the circle:

$$
(p_x - c_x)^2 + (p_y - c_y)^2 \leq r^2\ ?
$$

In the demo below, try getting the circle close to the corners of the rectangle: that's when a collision is detected.

<canvas id="mainCanvas2" style="background:var(--ui); margin: auto; display: block;"></canvas>

<script>
    const canvas = document.getElementById("mainCanvas2");
    const ctx = canvas.getContext("2d");

    const WIDTH = Math.min(600, 0.95 * document.documentElement.clientWidth);
    const HEIGHT = 400;
    canvas.width = WIDTH;
    canvas.height = HEIGHT;

    var style = window.getComputedStyle(document.body);
    const RED = style.getPropertyValue("--re");
    const GREEN = style.getPropertyValue("--gr");
    const CIRCLE_COLOUR = style.getPropertyValue("--tx");

    const radius = 25;

    function draw2(evt) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Get mouse position.
        var rect = canvas.getBoundingClientRect();
        var x = evt.clientX - rect.left;
        var y = evt.clientY - rect.top;

        // Draw the rectangle.
        ctx.fillStyle = collision(x, y) ? RED : GREEN;
        ctx.fillRect(WIDTH / 4, HEIGHT / 4, WIDTH / 2, HEIGHT / 2);

        // Draw the circle.
        ctx.fillStyle = CIRCLE_COLOUR;
        ctx.beginPath();
        ctx.arc(x, y, radius, 0, 2 * Math.PI);
        ctx.fill();
    }

    function collision(x, y) {
        var left = WIDTH / 4, right = 3 * WIDTH / 4;
        var top = HEIGHT / 4, bottom = 3 * HEIGHT / 4;
        var corners = [
            { x: left, y: top },
            { x: right, y: top },
            { x: left, y: bottom },
            { x: right, y: bottom },
        ];
        for (var c of corners) {
            if ((c.x - x) ** 2 + (c.y - y) ** 2 <= radius ** 2) {
                return true;
            }
        }
        return false;
    }

    document.addEventListener(
        "mousemove",
        draw2,
    );
</script>

The corners are the hardest part.
After taking care of that, it's easy to check if the circle is inside the rectangle or too close to its edges.
The diagram below helps to understand how to check if the rectangle is too close to the top or bottom edges of the rectangle: the coordinate $y$ of the rectangle needs to be below the value `top - radius` and above the value `bottom + radius`, while also being horizontally aligned: $left \leq c_x \leq right$.

![Diagram showing how to do the calculations to check if the ball is colliding with the rectangle along the vertical direction.](_ball_rectangle_collision.webp "Checking if the ball is colliding with the top or bottom edges.")
