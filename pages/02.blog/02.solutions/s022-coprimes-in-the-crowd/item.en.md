---
metadata:
    description: This blog post contains my proposed solution to one of the problems of this blog.
title: 'Solution #022 - coprimes in the crowd'
---

This post contains my proposed solution to [Problem #022 - coprimes in the crowd][prob]. Please do not read this solution before making a serious attempt [at the problem][prob].

===

### Solution

The solution is a simple application of the pigeonhole principle.

!!! The *pigeonhole principle* says that, if you have $k$ pigeons and $n < k$ holes, then if you put all pigeons in the holes there will be at least one hole with more than one pigeon.

For some $n \geq 2$, consider the following $n-1$ pairs of integers:

$$
\{3, 4\}, \{5, 6\}, \cdots, \{2n-1, 2n\}
$$

which together make up for the whole set $\{3, 4, \cdots, 2n-1, 2n\}$.
If we pick $n$ numbers from this set (the pigeons) and if we look for the pairs from where they came (the holes) then we see we **must** have picked two consecutive integers from one of the pairs. Those two numbers that came from the same pair are consecutive integers, and hence are coprime!

To see that two consecutive integers are coprime, you can [read this twitter proof][tp-coprimes].

If you have any questions about my solution, found any mistakes (whoops!) or would like to share *your* solution, be sure to leave a comment below.

[tp-coprimes]: /blog/twitter-proofs/consecutive-integers-are-coprime
[prob]: ../../problems/{{ page.slug }}
