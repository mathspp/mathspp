---
metadata:
    description: In this problem you have to beat the computer in a guessing game
title: 'Problem #023 - guess the polynomial'
---

In this problem you have to devise a strategy to beat the computer in a "guess the polynomial" game.

===

<script>
    var max_degree = 5;
    var max_coef = 3;
    var poly_times = 0;
    var evaluated_at = [];

    // Generate a random integer between a and b, inclusive.
    randint = function(a, b) {
        return Math.floor(Math.random()*(1+b-a)) + a;
    }

    reset_poly = function() {
        poly_times = 0;
        evaluated_at = [];
        document.getElementById("polyHint").value = "";
    }

    var poly = new Array(max_degree + 1);
    generate_poly = function() {
        reset_poly();
        for (var i = 0; i <= max_degree; ++i) {
            poly[i] = randint(0, max_coef);
        }
    }

    evaluate = function() {
        var a = parseInt(document.getElementById("polyAt").value);
        var value = 0;
        for (var i = 0; i <= max_degree; ++i) {
            value += poly[i]*a**i;
        }
        document.getElementById("polyHint").value = `The polynomial is ${value} when evaluated at ${a}.`;
        if (-1 === evaluated_at.indexOf(a)) {
            evaluated_at.push(a);
            ++poly_times;
            document.getElementById("polyTimes").value = poly_times;
        }
    }

    window.onload = generate_poly;
</script>

![]()

### Problem statement

I want you to play a little game with the computer. The computer is going to think of a polynomial of degree at most $5$ with non-negative, integer coefficients.
All the coefficients will be less than $4$.
Let's say $p(n)$ is the secret polynomial the computer is thinking of.

With the restrictions I imposed, we have

$$
p(n) = c_0 + c_1n + c_2n^2 + c_3n^3 + c_4n^4 + c_5n^5
$$

with $0 \leq c_i \leq 3$.

You can ask the computer to compute $p(n)$ for any non-negative integer you'd like.
Your task is to devise a strategy that allows you to determine $c_0, \cdots, c_5$ after a sequence of questions you ask the computer.
In particular, I want you to devise the *best* strategy possible for this game.
You can play with the computer below, to test your strategy.

!!! Give it a try!

---

You evaluated the current polynomial a total of <span id="polyTimes">0</span> times.

<button onclick="generate_poly()">Generate a new polynomial</button>

<br />

<label>Ask the computer to evaluate the polynomial at</label> &nbsp; <input id="polyAt" type="number" step="1" min="0" size="6" value="0">. &nbsp; <button onclick="evaluate()">Evaluate</button>

<p id="polyHint"></p>

<br />

---

If you need any clarification whatsoever, feel free to ask in the comment section below.

### Solution

The solution to this problem will be posted [here][sol] after this problem has been live for 2 weeks. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.
<!--You can read the solution [here][sol] to compare with your own solution. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.-->

---

If you enjoyed the problem and would like to get new problems directly in your inbox, be sure to [subscribe to the Problems newsletter][subscribe].

[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
