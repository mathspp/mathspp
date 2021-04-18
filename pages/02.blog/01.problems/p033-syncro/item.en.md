---
title: "Problem #033 - syncro"
---

Syncro is a beautiful game where you have to unite all the petals
in a single flower.
In how many moves can you do it?

===

![](thumbnail.png)

### Problem statement

Look at the image above.
There are four “flowers”, each one with four “petals”.
Notice that each of the flowers has one coloured petal.
There are also arrows going from one flower to the other:
this means that the corresponding shape sends the coloured
petals in that direction.

For example, the square makes all coloured petals rotate once
in the clockwise direction, or if you take the image above
and do “circle + square” then the coloured petals end up like this:

![](_syncro.png "Petal distribution after doing the sequence “circle + square”.")

Your objective is to find a sequence of circles and squares that
put all coloured petals in a single flower, in the smallest
number of steps possible.

!!! Give it some thought...

If you need any clarification whatsoever, feel free to ask in the comment section below.

### Syncro

Syncro is a desktop/mobile game developed by some friends of mine, and the objective
of the game is the same as that of this problem, except the game itself has several
levels.

<p>This game can be played <a class="external-link no-image" href="https://rawegg.itch.io/syncro">online</a> and there is also an
<a class="external-link no-image" href="https://play.google.com/store/apps/details?id=com.RawEgg.Syncro">Android app</a>.</p>

If you complete the game, you can even end up in the [hall of fame][hof]!

### Solution

You can read the solution [here][sol] to compare with your own solution.
You can also use that link to post your own solution in the comments! Please avoid posting spoilers in the comments here.

---

If you enjoyed the problem and would like to get new problems directly in your inbox, be sure to [subscribe to the Problems newsletter][subscribe].

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[sol]: ../../solutions/{{ page.slug }}
[hof]: /syncro
