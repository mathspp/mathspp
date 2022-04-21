How can you swap the coloured pegs if they can only march forward?

===

![](thumbnail.png "Photo of the game that inspired this post.")


# Problem statement

Imagine seven round slots:

```txt
O O O O O O O
```

In the three left slots, you have Yellow pegs:

```txt
Y Y Y O O O O
```

In the three right slots, you have Green pegs:

```txt
Y Y Y O G G G
```

You have to swap the yellow pegs and the green ones,
but of course there are some restrictions to what you can do:

 - pegs can only move forward (that is, yellow pegs can only move to the right and green pegs can only move to the left); and
 - pegs can only move:
   - to the next (adjacent) slot if it is available;
   - to the second next slot, if that means the peg “jumps over” a peg of another colour.

So, for example, in the configuration below, the green peg can move to the marked slot by jumping over a yellow peg:

```txt
G Y O
^   ^
```

As another example, in the situation below, no peg can move:

```txt
O G G Y Y O
```

With these restrictions, can you swap all pegs?
That is, can you move the pegs so that all green pegs end up on the left and all yellow pegs end up on the right?

!!! Give it some thought!
!!!
!!! You can try playing this out yourself: grab 6 coins and lay them in a straight line on a table.
!!! 3 coins on the left with the tails face up and the other 3 coins on the right with the other face up.

If you need any clarification whatsoever, feel free to ask in the comment section below.

This problem is based off of the puzzle you can see in the thumbnail picture,
which you can play at [Icon Park's Museum of Illusions][museum-illusions].


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - David H., Taiwan;
 - Michael W., US;
 - Zech Z., US;
 - Michael H., US;

Know how to solve this?
Join the list of solvers by [emailing me][email] your solution!


# Solution

I'll post the solution here once this problem has been live for 2 weeks,
which will be in early May.


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe

[museum-illusions]: https://iconparkorlando.com/attractions/museum-of-illusions-orlando/
