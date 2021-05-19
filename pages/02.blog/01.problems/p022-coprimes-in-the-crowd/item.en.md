---
metadata:
    description: A simple problem showing that there is always some order among chaos.
title: 'Problem #022 - coprimes in the crowd'
---

This simple problem is an example of a very interesting phenomenon: if you have a large enough "universe" to consider, even randomly picked parts exhibit structured properties.

===

![A crowd of people, photo by Rob Curran on Unsplash](./crowd.jpg)


# Problem statement

Let $n \geq 2$ be an integer. Then, consider the integers

$$
\{3, 4, \cdots, 2n-1, 2n\}\ .
$$

Show that, if you pick $n$ from those, you always have two numbers that will share no divisors whatsoever.

In other words, show that there's two of those $n$ numbers that are coprime.

!!! Give it some thought...

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solution

The solution is a simple application of the pigeonhole principle.

!!! The *pigeonhole principle* says that, if you have $k$ pigeons and $n < k$ holes, then if you put all pigeons in the holes there will be at least one hole with more than one pigeon.

For some $n \geq 2$, consider the following $n-1$ pairs of integers:

$$
\{3, 4\}, \{5, 6\}, \cdots, \{2n-1, 2n\}
$$

which together make up for the whole set $\{3, 4, \cdots, 2n-1, 2n\}$.
If we pick $n$ numbers from this set (the pigeons) and if we look for the pairs from where they came (the holes) then we see we **must** have picked two consecutive integers from one of the pairs. Those two numbers that came from the same pair are consecutive integers, and hence are coprime!

To see that two consecutive integers are coprime, you can [read this twitter proof][tp-coprimes].


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox and to add your reaction below.

[subscribe]: https://mathspp.com/subscribe
[tp-coprimes]: /blog/twitter-proofs/consecutive-integers-are-coprime
