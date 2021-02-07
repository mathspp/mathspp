---
metadata:
    description: "This blog post contains my proposed solution to problem #028 of this blog."
title: "Solution #028 - hidden key 🗝️"
---

This post contains my proposed solution to [Problem #028 - hidden key][prob]. Please do not read this solution before making a serious attempt [at the problem][prob].

===

### Solution

There are multiple solutions to this problem, but the most simple and elegant one (that I know of) is as follows:
flip a coin, so that the box with the key is under a coin whose face
up is different from the other two coins.

What is interesting is that this is always possible,
regardless of how the coins are originally placed on top of the boxes.
I invite you to have a go at demonstrating that the solution
I just described actually works, before you keep on reading
my solution.

If you number the boxes $1$ to $3$, with the box holding the key being
box number $1$, then there's $8$ different ways the coins could be
placed on top of the three boxes.
However, we don't care about the exact coin faces facing up!
Much like “[Problem #021 - predicting coin tosses][p21]”, we only
care about the relationships of the outcomes, which are three
and can be identified by the number of boxes whose coin face up is the same
as the coin face that is up on top of the box with the key.
That was a long sentence, reread it if you must!

For example, if the box with the key has a coin with tails face up, then one
of these three must be true:

 1. all coins have tails face up.
 2. two coins have tails face up, one on top of the box with the key and another
on top of some other box.
 3. only the coin on top of the box with the key has tails face up.

If we are in situation 1., then we turn the coin on top of the box with the key,
so that its face up is now heads (and it is the only coin with heads facing up).
If we are in situation 2., then we turn the coin that has tails face up and that
is **not** on top of the box with the key, so that the box with the key is now
the only box with a coin whose face up is tails.
If we are in situation 3., then we do not need to do anything, as the box with
the key is the only box with a coin whose face up is tails.

If the box with the key had the coin with heads facing up, then we just have to
follow the instructions above, except we start by swapping all occurrences of
"heads" with "tails" and vice versa.

If you have any questions about my solution, found an error (woops!) or want to share
*your* solution, please **leave a comment** below!
Otherwise just leave an “upvote” reaction!

Also, [don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox!

[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
[p21]: ../../problems/predicting-coin-tosses
