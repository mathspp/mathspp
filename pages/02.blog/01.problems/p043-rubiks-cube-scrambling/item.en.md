---
title: "Problem #043 – Rubik's cube scrambling"
metadata:
    description: "This problem explores what happens when you try to scramble a Rubik's cube systematically."
---

If I scramble a Rubik's cube for long enough,
will it solve itself?

===

![A scrambled Rubik's cube.](thumbnail.png "Photo by Serg Antonov on Unsplash.")

# Problem statement

A Rubik's cube is a toy like the one you can see in the picture above.
It's a 3 by 3 by 3 cube, where each face has one of six colours.
The cube can be scrambled, and at that point the colours of the faces
no longer match.

The challenge I have for you involves proving something.
I want you to prove the following statement:

 > “If you take a solved Rubik's cube and start scrambling it
 by following a fixed set of steps, you eventually end up with a solved
 Rubik's cube again.”

A silly example of how this is true is if you start turning the top face.
You rotate it once.
Twice.
Thrice.
A final turn, and it's back at the initial position!

But this also applies to more complicated sequences of turns!

If you have one, go grab a Rubik's cube and give it a go!
(It's not the same thing, but you can also try [this online simulator][cube-simulator].)

For your convenience, here is a short GIF of me scrambling
a cube by repeating the same set of steps.
(It took me 3 minutes in real life, the GIF was sped up.)

![](_rubiks_scrambling.gif)

I started out with a solved cube and ended up with a solved cube.

Why?

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solvers

Know how to solve this?
Be the first to join the list of solvers by [emailing me][email] your solution!

<!--
Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - 

Join the list of solvers by [emailing me][email] your solution!
-->


# Solution

The solution to this problem will be posted here after this problem has been live for 2 weeks.

[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe
[cube-simulator]: https://ruwix.com/online-puzzle-simulators/
