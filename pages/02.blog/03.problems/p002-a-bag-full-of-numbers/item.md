---
title: 'Problem #002 - a bag full of numbers'
---

Two friends were bored and decided to play a game... a mathematical game with a paper bag!

<!-- v -->

===

![a photo of a paper bag](paperbag.jpg "Photo by B S K from FreeImages")

<!-- ^ -->

## Problem statement

John and Mary have a bag full of integer numbers. In fact, the bag has $10^{10^{10}}$ integers, each written on a plastic card, and the sum of all the $10^{10^{10}}$ integers in the bag is $0$. In turns, Mary and John are going to play with the bag by doing the following:

 - Picking two cards from the bag with numbers $a$ and $b$ and removing them from the bag;
 - Inserting a new card in the bag with the number $a^3 + b^3$.

Is there any initial number configuration and/or set of moves for which it is possible that, after $10^{10^{10}} - 1$ moves, the only card in the bag has the number $73$?

!!! Give it some thought... and most important of all, try it for real! Let me know how it went in the comment section below ;)

!!!! **Hint**: the answer is "no". Can you show why?

!!!! **Hint**: look for an invariant of the game! That is, find a property of the game that does _not_ change when Mary and John play it.


## Solution

(If you spot a mistake in my solution _please_ let me know in the comments below.)

No, there is no initial configuration nor set of moves that allows us to reach the goal. Note how $x \equiv x^3 \mod 2$. That is, $x $ has the same parity as $x^3$. Let us assume that, at a given point, all the integers in the bag add up to $S $. We show that the parity of the sum of all integers in the bag doesn't change when we remove the cards $a,b $ and then add the card $a^3+b^3$:

$$
    x \equiv x^3 \mod 2 \implies S \equiv S - a - b + a^3 + b^3 \iff S + a + b \equiv S + a^3 + b^3 \mod 2
$$

Thus we can't end up only with $73$ in the bag, as everything in the bag should add up to an even number and $73$ is odd.

Bonus question: find a solution that would still work for $2, 74, 308$ (and for an infinity of even numbers), even though those are even.
