---
metadata:
    description: In this problem you have to beat the computer in a guessing game
title: 'Problem #023 - guess the polynomial'
---

In this problem you have to devise a strategy to beat the computer in a "guess the polynomial" game.

===

<script>
    var max_degree = 5;
    var max_coef = 10;

    // Generate a random integer between a and b, inclusive.
    randint = function(a, b) {
        return Math.floor(Math.random()*(1+b-a)) + a;
    }

    var poly = [];
    generate_poly = function() {
        poly = new Array(max_degree + 1).map(_ => randint(0, max_coef - 1));
    }

    window.onload = generate_poly;
</script>

![]()

### Problem statement

I want you to play a little game with the computer. The computer is going to think of a polynomial of degree at most $5$ with non-negative, integer coefficients.
All the coefficients will be less than $10$.
Let's say $p(n)$ is the secret polynomial the computer is thinking of.

With the restrictions I imposed, we have

$$
p(n) = c_0 + c_1n + c_2n^2 + c_3n^3 + c_4n^4 + c_5n^5
$$

with $0 \leq c_i < 10$.

You can ask the computer to compute $p(n)$ for any non-negative integer you'd like.
Your task is to devise a strategy that allows you to determine $c_0, \cdots, c_5$ after a sequence of questions you ask the computer.
In particular, I want you to devise the *best* strategy possible for this game.
You can play with the computer here:

<button onclick="generate_poly">Create a new polynomial</button>

!!! Give it a try!

If you need any clarification whatsoever, feel free to ask in the comment section below.

### Solution

The solution to this problem will be posted [here][sol] after this problem has been live for 2 weeks. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.
<!--You can read the solution [here][sol] to compare with your own solution. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.-->

---

If you enjoyed the problem and would like to get new problems directly in your inbox, be sure to [subscribe to the Problems newsletter][subscribe].

[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
