This tutorial walks you through implementing a 2D scrolling game in JavaScript.

===


# JavaScript 2D scrolling game tutorial

This JavaScript tutorial is for people who know programming (for example, in Python) but have no JavaScript knowledge.
In this tutorial we will build a 2D scrolling game where the player (a red ball) will jump to avoid obstacles (black rectangles) that come toward the player at increasing speeds.


## JavaScript and the web

JavaScript is a language that runs directly in your browser without you having to do anything else.
In fact, if you click the address bar (where you usually type the URL addresses of the websites you want to visit) and type something like `javascript:alert("Hello, world!");`, the browser should display a pop-up with the message “Hello, world!”.

JavaScript is tightly integrated with your browser.
This means you can also use JavaScript to manipulate web pages.
In JavaScript, `document` is a variable that refers to the web page (document) you're currently on, and it lets you interact with the page programmatically.
Here's a fun example:

 1. open the Wikipedia page at <wikipedia.org>; and
 2. type `javascript:document.getElementById("www-wikipedia-org").remove()` in your address bar.

If all goes well[^1] and you copy that code correctly, it should look like the page disappeared.
In fact, if you look at the end of the code you pasted, there's a `remove` in there.
What we did was ask the web page for an element with the unique ID `www-wikipedia-org`, which for the main Wikipedia page refers to the whole visible page.

[^1]: Web pages evolve and change with time; if enough time has passed, this tutorial may be outdated and the reference `www-wikipedia-org` that I'm trying to use may no longer be there. If that's the case, please [email me](mailto:rodrigo@mathspp.com) so I can update this tutorial.

Don't worry, you didn't hack Wikipedia and you aren't going to jail.
If you refresh the page, you should get a brand new copy of the page you were at, and everything should be back to normal.
That's because the JavaScript code you ran when you pasted it in the address bar was running _locally_, on your browser, on your copy of the Wikipedia main page.
It ran on the “client side”.


## Developer console

Next, I want to tell you about the developer console.
The developer console is like a debugger, but instead of being built into your IDE of choice, it's built into your web browser.
All major web browsers have a developer console: Safari, Chrome, Firefox, Opera, Brave, etc.

Each browser has a different shortcut to open the developer console, so you may want to look up “Open developer console INSERT_BROWSER_HERE”.

In most browsers, a roundabout way of achieving this effect is right-clicking _anywhere_ on a page and then looking for the option that says something like “Inspect” or “Inspect element”.
That should open a window that lets you peek at the source code of the page you're currently on, and it should have a tab that says something like “Console” or “JavaScript console”.

If you haven't been able to open the console yet, look up how to do it in your preferred browser.

When you manage to open the console, it won't look _exactly_ like mine, but it should be somewhat similar to this:

![A console opened at the bottom of the Wikipedia page, showing a large number of different tabs. The leftmost says 'elements' and the second to the left says 'console' and is selected, showing a mostly empty pane with a code prompt at the bottom.](_dev_console.webp "JavaScript console on Safari")

The highlight is the prompt at the bottom, which is where you get to write JavaScript code and interact with the browser of the page you're in.
You can try creating another alert, as before, but now you can also use `console.log`, which is like Python's `print`, but that writes to the console.
Try typing this in the prompt at the bottom: `console.log("Hello, world!")`.

If you do it correctly, you should see the message “Hello, world!” appear in the console:

![The developer console showing the message “Hello, world!” after running a line of JavaScript code that logged that message.](_console_log.webp "A message logged to the console.")

When you're writing JavaScript code, you can use `console.log` as a basic debugging tool.


## Game setup

We will write the game in a plain page with nothing but our game.
To do this, you will need to write the basic skeleton of a web page in HTML, and then you'll write the JavaScript code that actually makes your game run.

Go ahead and create a file `game.html` and put this in it:

```html
<html>

<body>
    <h1>Best game ever</h1>
</body>
</html>
```

If you double-click your file (and make sure its extension is `.html`), it should open your default web browser and show an exciting web page:

![A web page that is all white except for a black title that says “Best game every”.](_empty_page.webp "A blank page with a title.")

I'm cutting some corners here, but the code you typed in your file `game.html` is some basic HTML that is just enough for the browser to display _something_.
You'll be working on top of this over the next few sections.


## Game canvas

To make our game, you will create what's called a “canvas”, which is an HTML element that provides functionality for you to draw on.
HTML elements are created with tags, which are the things that start with `<`, have a name, and then end with `>`.
Many tags are created in pairs (like `html`, `body`, and `h1` above) and the content usually goes between the tags.
The tag `html` sets the whole page, and virtually everything goes inside it; the tag `body` is for the main content of the page; and the tag `h1` creates a heading.

Now, you will add a tag to create a canvas:

```html
<html>

<body>
    <h1>Best game ever</h1>

    <canvas></canvas>
</body>
</html>
```

If you modify your file `game.html`, save it, and reload the browser page...
Nothing should happen!
At least, nothing visible.
That's because you created an empty canvas with the default colour, which means you can't see it.
To make the canvas visible, go ahead and style it by modifying its HTML code:

```html
<html>

<body>
    <h1>Best game ever</h1>

    <canvas style="background: lightblue"></canvas>
</body>
</html>
```

That should make the canvas visible:

![The same white page showing a light blue rectangle under the title.](_canvas.webp "A light blue canvas.")


## Interacting with the canvas with JavaScript

Now you will use some JavaScript to interact with the game canvas.
Remember the little demo where you used the function `document.getElementById` to access a part of the Wikipedia page and then delete it?
You'll use a similar mechanism to access the canvas, so you need to give the canvas a unique ID.
Then, you can create a `script` tag that contains JavaScript code to interact with your canvas:

```html
<html>

<body>
    <h1>Best game ever</h1>

    <canvas id="gameCanvas" style="background: lightblue"></canvas>

    <script>
        const canvas = document.getElementById("gameCanvas");

        canvas.width = 600;
        canvas.height = 300;
    </script>
</body>
</html>
```

This should create a larger canvas.
Save your code and reload the page, and you should see a larger canvas on the screen.


## Live demos

The beauty of writing your game in the file `game.html` is that it acts as a standalone web page (that you can view offline) and that you can easily share with others.
Additionally, I can embed it on this article or link you to it, so you can see an example of exactly what you should be seeing, so I'll start doing that from now on.
At each step, I will embed the state of the game for you to take a look at and I will link to the standalone page for you to check your progress.

The only difference will be that I will require you to click the demo once for it to start, otherwise all demos will run without you actually seeing them.
I will also get rid of the title “Best game ever” because it's in the way.

Here's [the first demo I'm linking to](/blog/javascript-2d-scrolling-game-tutorial/game04.html), which shows the larger canvas:

<iframe style="border: 0;" width="100%" height="400" src="/blog/javascript-2d-scrolling-game-tutorial/game04.html"></iframe>


## Drawing the player

The first thing you'll do is draw the player on the canvas.
The player will be a red ball, so we need to create a data structure to hold information about the player position and then we need to somehow draw the player on the screen.

To hold player info we will create a JavaScript object, which looks a bit like a Python dictionary but allows accessing information with the dot syntax we are used to.
This is the code to create the player object:

```js
const canvas = document.getElementById("gameCanvas");

canvas.width = 600;
canvas.height = 300;

const player = {  // Create an object with values for x, y, and radius.
    x: 50,
    y: 50,
    radius: 15,
}
```

(From now on I am only showing the content that should go inside the tag `script`, since we don't need to change anything else.)

The keyword `const` is used by JavaScript to declare a constant variable.
You used it to create a constant reference to the canvas, and now a reference to the player object.
You will also use it to create a reference to the drawing context, an object that lets you draw on the canvas:


```js
// ...

const drawing_ctx = canvas.getContext("2d");
```

By using this `drawing_ctx`, you can draw things on the canvas.
For now, we want to draw a red circle.
First, you tell the drawing context that what you'll be drawing is red.
Then, you tell the drawing context that you want to start drawing.
After, you use the function `arc` to draw a complete arc.
Finally, you tell the drawing context to fill the line you drew.
That's a lot...
Here's the corresponding code:

```js
// ...

const drawing_ctx = canvas.getContext("2d");

// You want to draw in red.
drawing_ctx.fillStyle = "red";
// Tell the drawing context you want to start drawing.
drawing_ctx.beginPath();
// Draw an arc centred at the coordinates given by `player.x` and `player.y`,
// with the radius given by `player.radius`.
// Since you're drawing an arc, you need to specify the starting and ending angle.
// Starting at `0` and ending at `2 * Math.PI` creates a full circle.
drawing_ctx.arc(player.x, player.y, player.radius, 0, 2 * Math.PI);
// Tell the drawing context to fill the line you drew.
drawing_ctx.fill();
```

Save the code and reload the page.
You should see a red circle near the top-left corner of the canvas.
[Demo of the player drawn near the top-left corner of the canvas](/blog/javascript-2d-scrolling-game-tutorial/game05.html).

<iframe style="border: 0;" width="100%" height="400" src="/blog/javascript-2d-scrolling-game-tutorial/game05.html"></iframe>


## Game loop

The next thing to do is add gravity to the game, so that the ball falls to the ground.
To do this, we need to update the position of the player ball over time to change its vertical position little by little.
This requires the concept of a game loop.

In games, movement is created by drawing the screen repeatedly over time, a couple of dozen times per second, so that the sequence of images shown in quick succession create the idea of movement.
This is something you'll know if you've read my book [“The little book of pygame”](/books/the-little-book-of-pygame), where you create your own minigames with Python and pygame.
It's the same idea in JavaScript, but you use a slightly different mechanism because you're using different tools.

To animate the game and to create movement you will do two actions in sequence and repeatedly:

 1. update the entities of the game (move them around, etc); and
 2. draw the canvas based on the current information about the entities of the game.

And this needs to happen repeatedly.
Updating the entities of the game will be done in a function `update` and the drawing will happen in a function `draw`.
Then, a central function `gameLoop` will call those two functions and will use the mechanism that JavaScript provides to allow this to happen multiple times per second.

For now, you will reorganise your code slightly:

```js
const canvas = document.getElementById("gameCanvas");

canvas.width = 600;
canvas.height = 300;

const player = {
    x: 50,
    y: 50,
    radius: 15,
}

const drawing_ctx = canvas.getContext("2d");

function update() {  // Update the game entities.
}

function draw() {  // Draw the current game state.
    // Draw the player:
    drawing_ctx.fillStyle = "red";
    drawing_ctx.beginPath();
    drawing_ctx.arc(player.x, player.y, player.radius, 0, 2 * Math.PI);
    drawing_ctx.fill();
}

function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

gameLoop();
```

Note that the code that drew the player now has been moved into the function `draw`.
If you run your game now, [everything should look the same](/blog/javascript-2d-scrolling-game-tutorial/game06.html):

<iframe style="border: 0;" width="100%" height="400" src="/blog/javascript-2d-scrolling-game-tutorial/game06.html"></iframe>
