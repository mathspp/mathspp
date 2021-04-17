---
metadata:
    description: This blog post contains my proposed solution to one of the problems of this blog.
title: 'Solution #023 - guess the polynomial'
---

This post contains my proposed solution to [Problem #023 - guess the polynomial][prob]. Please do not read this solution before making a serious attempt [at the problem][prob].

===

### Solution

The best strategy works for *any* polynomial in just $2$ steps.

If you haven't solved the problem yet, go ahead and try to figure out a strategy that works in just $2$ steps!
It is easier to do that than to devise a good strategy **and** at the same time try to optimise the number of steps taken.

Let

$$
p(n) = \sum_{k = 1}^N c_k n^k
$$

be the polynomial we need to find, where $N$ is the (unknown) degree of the polynomial and some of the $c_k$, $k < N$ are non-negative integers.

The first thing we do is ask for $p(1)$, as that gives us an upper bound for the size of each coefficient:

$$
p(1) = \sum_{k = 1}^N c_k
$$

and all the $c_k$ are non-negative integers, so we know for sure that for any $i$,

$$
c_i \leq \sum_{k = 1}^N c_k = p(1) ~ .
$$

Now that we know that no coefficient is larger than $p(1)$, let $b = p(1) + 1$ and ask for $p(b)$.
If we write down $p(b)$ in base $b$, then the digits of $p(b)$ in base $b$ give us the coefficients $c_k$.
This works because $b$ is larger than any of the coefficients $c_k$, so asking for $p(b)$ effectively gives

$$
p(b) = \sum_{k = 1}^N c_k b^k ~ ,
$$

which is the way one writes a number in another base, by definition.

I'll walk you through a couple of examples to make it clear for you.
If you've understood by now, feel free to skip to the bottom of the blog post to show your appreciation for this post,
either by leaving an emoji reaction or by commenting the post!

### Worked examples

**Example 1**: suppose $p(n) = x^3$.

 1. We ask for $p(1) = 1$.
 2. We set $b = p(1) + 1 = 2$.
 3. We ask for $p(b) = p(2) = 8$.
 4. We write $p(b) = 8$ in base $b = 2$, which is $1000_2$.
 5. We read the coefficients as $1, 0, 0, 0$, so that we have $c_3 = 1$
and $c_2 = c_1 = c_0 = 0$.

**Example 2**: suppose $p(n) = x^3 + 2x$.

 1. We ask for $p(1) = 3$.
 2. We set $b = p(1) + 1 = 4$.
 3. We ask for $p(b) = p(4) = 72$.
 4. We write $p(b) = 72$ in base $b = 4$, which is $1020_4$.
 5. We read the coefficients as $1, 0, 2, 0$, so that $c_3 = 1$, $c_1 = 2$
and $c_2 = c_0 = 0$.

**Example 3**: suppose $p(n) = 4x^2 + 2x + 3$.

 1. We ask for $p(1) = 9$.
 2. We set $b = p(1) + 1 = 10$.
 3. We ask for $p(b) = p(10) = 423$.
 4. We write $p(b) = 423$ in base $b = 10$, which is $423_{10}$!
 5. We read the coefficients as $4, 2, 3$ so that $c_2 = 4$, $c_1 = 2$
and $c_0 = 3$.

I hope these examples made it clearer how this works!
If you need to convert a number to another base,
you can always [ask WolframAlpha][wa-convert].

If you have any questions about my solution, found any mistakes (whoops!) or would like to share *your* solution, be sure to leave a comment below.

[prob]: ../../problems/{{ page.slug }}
[wa-convert]: https://www.wolframalpha.com/input/?i=72+in+base+4
