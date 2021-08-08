---
title: "Problem #042 – mine captcha"
metadata:
    description: "In this problem we tackle a puzzle posted by the infamous xkcd cartoon website."
---

Can you solve this little minesweeper puzzle?

===

![4 by 4 minesweeper grid containing, from left to right, top to bottom: 2, gray, 1, gray, gray, gray, 3, gray, 3, gray, gray, gray, gray, 1, gray, 1](thumbnail.png)

# Problem statement

The [xkcd] website published its cartoon [#2496][source], containing a minesweeper grid,
and that is the problem for this post:
to locate all of the mines in the minesweeper grid above.

For those of you who don't know the minesweeper game,
here are the rules:

 - some squares contain mines, others don't;
 - squares that do not contain mines contain numbers instead,
and those numbers indicate how many mines are neighbours to that numbered square
(the neighbourhood of a square contains the squares that are directly
adjacent to it, plus the squares that share a vertex in the diagonal).
 - gray squares can contain mines or other numbers.

You must find all the mines by reasoning about the numbers you see.

You can play minesweeper online or you can play [my minesweeper remake][minesweeper-blog].

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
[subscribe]: https://mathspp.com/subscribe
[source]: https://xkcd.com/2496/
[xkcd]: https://xkcd.com
[minesweeper-blog]: /blog/minesweeper-remake
