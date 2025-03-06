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

    const WIDTH = Math.min(600, window.innerWidth);
    const HEIGHT = 400;
    canvas.width = WIDTH;
    canvas.height = HEIGHT;

    var style = window.getComputedStyle(document.body);
    const RED = style.getPropertyValue("--re");
    const ORANGE = style.getPropertyValue("--or");
    const CIRCLE_COLOUR = style.getPropertyValue("--tx");

    const radius = 25;

    function draw(evt) {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Get mouse position.
        var rect = canvas.getBoundingClientRect();
        var x = evt.clientX - rect.left;
        var y = evt.clientY - rect.top;

        // Draw the rectangle.
        ctx.fillStyle = collision(x, y) ? RED : ORANGE;
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
        draw,
    );
</script>
