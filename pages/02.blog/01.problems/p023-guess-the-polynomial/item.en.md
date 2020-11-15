---
metadata:
    description: In this problem you have to beat the computer in a guessing game
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
        document.getElementById("polyResult").innertHTML = "";
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
        document.getElementById("polyHint").innerHTML = `The polynomial is ${value} when evaluated at ${a}.`;
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

![]()

### Problem statement

I want you to play a little game with the computer. The computer is going to think of a polynomial with non-negative, integer coefficients.
Let's say $p(n)$ is the secret polynomial the computer is thinking of.

I want you to find out what $p(n)$ is, and the only thing you can do is to ask for the value of $p(n)$ at non-negative integers.
For example, you can ask what $p(49)$ is.
You have to come up with the best strategy possible, that allows you to determine $p(n)$ in the least number of guesses possible.

You can test your strategy with the computer below.
The computer will only think of polynomials with degree at most $3$
and the coefficients will be at most $3$ as well, but that is just to make testing your strategy easier.
The strategy should work for higher degrees and larger coefficients.

With the restrictions for the computer test, we have

$$
p(n) = c_0 + c_1n + c_2n^2 + c_3n^3, 0 \leq c_i \leq 3
$$

!!! Give it a try!

---

<div>
    <br />
    You evaluated the current polynomial for <span id="polyTimes">0</span> different value(s).
    <br />
    <button id="newPolyBtn" onclick="generate_poly()">Generate a new polynomial</button>
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

### Solution

The solution to this problem will be posted [here][sol] after this problem has been live for 2 weeks. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.
<!--You can read the solution [here][sol] to compare with your own solution. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.-->

---

If you enjoyed the problem and would like to get new problems directly in your inbox, be sure to [subscribe to the Problems newsletter][subscribe].

[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
