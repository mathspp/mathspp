---
metadata:
    description: In this problem you have to beat the computer in a guessing game.
title: 'Problem #023 - guess the polynomial'
---

In this problem you have to devise a strategy to beat the computer in a "guess the polynomial" game.

===

<script>
    var max_degree = 3;
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
        document.getElementById("polyHint").innerHTML = "";
        document.getElementById("polyTimes").innerHTML = 0;
        document.getElementById("polyResult").innerHTML = "";
        reset_test_coefs();
        set_disables(guessing = true);
    }

    /* set the disabled status of inputs and buttons, depending on whether
     * the user is currently guessing the poly or not. */
    set_disables = function(guessing) {
        document.getElementById("newPolyBtn").disabled = guessing;
        document.getElementById("verifyPolyBtn").disabled = !guessing;
        document.getElementById("giveUpPolyBtn").disabled = !guessing;
        set_disabled_test_coefs(disabled = !guessing);
    }

    reset_test_coefs = function() {
        for (var i = 0; i <= max_degree; ++i) {
            document.getElementById(`c${i}`).value = 0;
        }
    }

    set_disabled_test_coefs = function(disabled) {
        for (var i = 0; i <= max_degree; ++i) {
            document.getElementById(`c${i}`).disabled = disabled;
        }
    }

    var poly = new Array(max_degree + 1);
    generate_poly = function() {
        for (var i = 0; i <= max_degree; ++i) {
            poly[i] = randint(0, max_coef);
        }
        reset_poly();
    }

    evaluate_poly = function() {
        var a = parseInt(document.getElementById("polyAt").value);
        var value = 0;
        for (var i = 0; i <= max_degree; ++i) {
            value += poly[i]*a**i;
        }
        document.getElementById("polyHint").innerHTML = `p(${a}) = ${value}`;
        if (-1 === evaluated_at.indexOf(a)) {
            evaluated_at.push(a);
            ++poly_times;
            document.getElementById("polyTimes").innerHTML = poly_times;
        }
    }

    verify_poly = function() {
        var right = true;
        for (var i = 0; i <= max_degree; ++i) {
            right &= document.getElementById(`c${i}`).value === `${poly[i]}`;
        }
        if (right) {
            document.getElementById("polyResult").innerHTML = "Correct!";
            set_disables(guessing = false);
        } else {
            document.getElementById("polyResult").innerHTML = "Wrong!";
        }
    }

    give_up_poly = function() {
        set_disables(guessing = false);
        polyResult = `The polynomial was p(n) = ${poly[0]}`
        for (var i = 1; i<= max_degree; ++i) {
            polyResult += ` + ${poly[i]}n^${i}`;
        }
        document.getElementById("polyResult").innerHTML = polyResult;
    }

    window.onload = generate_poly;
</script>

![A question mark in a neon light](thumbnail.jpg "Photo by Emily Morter on Unsplash")


# Problem statement

I want you to play a little game with the computer.
The computer is going to think of a polynomial with non-negative, integer coefficients.
Let's say $p(n)$ is the secret polynomial the computer is thinking of.

I want you to find out what $p(n)$ is, and the only thing you can do is to ask for hints in the form of values of $p(n)$ for $n \geq 0$ integer.
For example, you can ask what $p(0)$ or $p(49)$ is, but you can't ask for the value of $p(-1)$ or $p(0.5)$.
You have to come up with the best strategy possible, that allows you to determine $p(n)$ with the least number of hints possible.

You can test your strategy with the computer below.
The computer will only think of polynomials with degree at most $3$
and the coefficients will be at most $3$ as well, but that is just to make testing your strategy easier.
The strategy should work for higher degrees and larger coefficients.

With the restrictions for the computer test, we have

$$
p(n) = c_0 + c_1n + c_2n^2 + c_3n^3, 0 \leq c_i \leq 3
$$

!!! Good luck!

---

<div>
    <br />
    You asked for <span id="polyTimes">0</span> hint(s).
    <br />
    <button id="newPolyBtn" onclick="generate_poly()">New polynomial</button>
    <br />
    <br />
    <label>Ask the computer to evaluate the polynomial at</label> &nbsp; <input id="polyAt" type="number" step="1" min="0" size="6" value="0">. &nbsp; <button onclick="evaluate_poly()">Evaluate</button>
    <p id="polyHint"></p>
    <br>
    Your guess: p(n) = 
    <input id="c0" type="number" step="1" min="0" max="3" size="1" value="0">
    &nbsp; + &nbsp;
    <input id="c1" type="number" step="1" min="0" max="3" size="1" value="0">
    n &nbsp; + &nbsp;
    <input id="c2" type="number" step="1" min="0" max="3" size="1" value="0">
    n^2 &nbsp; + &nbsp;
    <input id="c3" type="number" step="1" min="0" max="3" size="1" value="0">
    n^3
    <br />
    <button id="verifyPolyBtn" onclick="verify_poly()">Verify</button> <button id="giveUpPolyBtn" onclick="give_up_poly()">Give up</button>
    <p id="polyResult"></p>
</div>

---

If you need any clarification whatsoever, feel free to ask in the comment section below.

This problem was brought to my attention by [MathGurl].


# Solution

The best strategy works for *any* polynomial in just $2$ steps.

If you haven't solved the problem yet, go ahead and try to figure out a strategy that works in just $2$ steps!
It is easier to do that than to devise a good strategy **and** at the same time try to optimise the number of steps taken.

Let

$$
p(n) = \sum_{k = 1}^N c_k n^k
$$

be the polynomial we need to find, where $N$ is the (unknown) degree of the polynomial and some of the $c_k$, $k < N$ are non-negative integers.

The first thing we do is ask for $p(1)$, as that gives us an upper bound for the size of each coefficient:

$$
p(1) = \sum_{k = 1}^N c_k
$$

and all the $c_k$ are non-negative integers, so we know for sure that for any $i$,

$$
c_i \leq \sum_{k = 1}^N c_k = p(1) ~ .
$$

Now that we know that no coefficient is larger than $p(1)$, let $b = p(1) + 1$ and ask for $p(b)$.
If we write down $p(b)$ in base $b$, then the digits of $p(b)$ in base $b$ give us the coefficients $c_k$.
This works because $b$ is larger than any of the coefficients $c_k$, so asking for $p(b)$ effectively gives

$$
p(b) = \sum_{k = 1}^N c_k b^k ~ ,
$$

which is the way one writes a number in another base, by definition.

I'll walk you through a couple of examples to make it clear for you.
If you've understood by now, feel free to skip to the bottom of the blog post to show your appreciation for this post,
either by leaving an emoji reaction or by commenting the post!

## Worked examples

**Example 1**: suppose $p(n) = x^3$.

 1. We ask for $p(1) = 1$.
 2. We set $b = p(1) + 1 = 2$.
 3. We ask for $p(b) = p(2) = 8$.
 4. We write $p(b) = 8$ in base $b = 2$, which is $1000_2$.
 5. We read the coefficients as $1, 0, 0, 0$, so that we have $c_3 = 1$
and $c_2 = c_1 = c_0 = 0$.

**Example 2**: suppose $p(n) = x^3 + 2x$.

 1. We ask for $p(1) = 3$.
 2. We set $b = p(1) + 1 = 4$.
 3. We ask for $p(b) = p(4) = 72$.
 4. We write $p(b) = 72$ in base $b = 4$, which is $1020_4$.
 5. We read the coefficients as $1, 0, 2, 0$, so that $c_3 = 1$, $c_1 = 2$
and $c_2 = c_0 = 0$.

**Example 3**: suppose $p(n) = 4x^2 + 2x + 3$.

 1. We ask for $p(1) = 9$.
 2. We set $b = p(1) + 1 = 10$.
 3. We ask for $p(b) = p(10) = 423$.
 4. We write $p(b) = 423$ in base $b = 10$, which is $423_{10}$!
 5. We read the coefficients as $4, 2, 3$ so that $c_2 = 4$, $c_1 = 2$
and $c_0 = 3$.

I hope these examples made it clearer how this works!
If you need to convert a number to another base,
you can always [ask WolframAlpha][wa-convert].


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox and to add your reaction below.

[MathGurl]: https://www.youtube.com/channel/UC5RV_s1Jh-jQI4HfexEIb2Q
[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
