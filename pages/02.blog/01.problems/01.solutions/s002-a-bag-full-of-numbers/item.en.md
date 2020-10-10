---
title: 'Problem #002 - a bag full of numbers'
---

This post contains my proposed solution to [Problem #002 - a bag full of numbers][p002]. Please do not read this solution before making a serious attempt [at the problem][p002].

{{ "https://mathspp.com/blog/problems/s/bag-full-of-numbers"|to_problem }}

===

![a photo of a paper bag](paperbag.jpg "Photo by B S K from FreeImages")

### Problem statement

### Solution

(If you spot a mistake in my solution _please_ let me know in the comments below.)

No, there is no initial configuration nor set of moves that allows us to reach the goal. Note how $x \equiv x^3 \mod 2$. That is, $x $ has the same parity as $x^3$. Let us assume that, at a given point, all the integers in the bag add up to $S $. We show that the parity of the sum of all integers in the bag doesn't change when we remove the cards $a,b $ and then add the card $a^3+b^3$:

\[
    x \equiv x^3 \mod 2 \implies S \equiv S - a - b + a^3 + b^3 \iff S + a + b \equiv S + a^3 + b^3 \mod 2
\]

Thus we can't end up only with $73$ in the bag, as everything in the bag should add up to an even number and $73$ is odd.

Bonus question: find a solution that would still work for $2, 74, 308$ (and for an infinity of even numbers), even though those are even.

Let me know what you think in the comment section below!

[p002]: https://mathspp.com/blog/problems/bag-full-of-numbers
