---
metadata:
    description: "This blog post contains my proposed solution to one of the problems of this blog."
title: "Solution #032 - restaurant roundtable"
---

This post contains a proposed solution to [Problem #032 - restaurant roundtable][prob].
Please do not read this solution
before making a serious attempt [at the problem][prob].

===

### Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
that submitted correct solutions:

 - Attila K.;
 - Filippo M.;
 - Luís S.;
 - André S..

(The list is in no particular order.)


### Solution

I received some really interesting solutions and I'll be sharing a paraphrasing
of the one I think is the most elegant.

Yes, it is always possible to turn the table some number of places
in order to fix the dishes of at least two people at the same time.
Let's see how to do it.

Each person sitting at the table will look to its left and count the number of
plates until its own plate.

For example, if we consider this placement:

![](thumbnail.png)

Then the corresponding numbers would be the following:

![](_roundtable_counts.png)

If there are $n$ people sitting at the table, that number is an integer
between $1$ and $n - 1$, inclusive.
It is not $0$ because that would mean that person got the correct plate
and it is not $n$ because that is a whole turn around the table, also
meaning that person would've gotten its own plate.

We have seen that each person has a number inside the set

$$
\{ 1, 2, \cdots, n-1 \} ~~~,
$$

and there are $n$ people, but that set only contains $n - 1$ distinct numbers.
Therefore, the pigeonhole principle tells you that at least two persons
have the same number $d$, which means you can turn the table $d$ times
in the counter-clockwise direction to deliver the correct plate to them.


If you have any questions about my solution, found an error (woops!) or want to share
*your* solution, please **leave a comment** below!
Otherwise just leave an “upvote” reaction!-->

[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox!

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[prob]: ../../problems/{{ page.slug }}
