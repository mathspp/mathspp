---
title: On recursive functions and Kleene's approach
date: 17-10-2017
slug: recursion-by-kleene
taxonomy:
    category: blogpost
    tag: [mathematics, recursion]
---

In this post I just ramble a bit through some mathematician's definition of what a recursive function is...

===

In Computation Theory it is of interest to study properties about functions, and what functions satisfy said properties. We then consider the set of all functions that satisfy those properties. One of those sets is the set $R$ of recursive functions, as defined by Kleene. To define $R$, Kleene gives a series of different primitive functions that are known to be in $R$ and then defines some operations that preserve functions in $R$.

For the purposes of what I will be sharing next, I will just enumerate said primitive functions and constructions, so that the reader is aware of what will be used (notice that what comes below is almost identical to what you can see [here](https://en.wikipedia.org/wiki/%CE%9C-recursive_function).

The primitive functions are:

 - The constant functions of arity 0, one for each natural number;
 - The zero function of arity 1, that always returns 0;
 - The successor function of arity 1, that sends $x \mapsto x + 1$;
 - The $projection(a, b)$ of arity $a$, that returns the $b$-th argument unchanged. For example, $projection(3,2)(a, b, c) = b$.

After that, some constructions are considered, such that functions from $R$ are sent to functions in $R$. Said constructions are:

 - Usual function composition;
 - Aggregation, that we represent with $<>$, and that can be applied to $n$ functions all with the same arity. For example, $<f, g>(a, b) = (f(a,b), g(a,b))$, provided $f$ and $g$ have arity 2.
 - Recursion: if the arity of $g$ is $n + 2$ and the arity of $f$ is $n$, then $h = rec(f, g)$ is defined in such a way that $h(X, 0) = f(X), h(X, k+1) = g(X, k, h(X, k))$ (if $X$ is a 'vector' of $n$ arguments);
 - Minimization: $h = \min(f)$, with $f$ having arity $n+1$, is a function of arity $n$ such that $h(X) = i \iff f(X, i) = 0$ and if for all $k < i$, $f(X, k) \neq 0$.

When I learned this I was prompted to define some usual functions in terms of this, for example the addition, the predecessor, multiplication, the factorial, etc. I decided to implement these constructs in Python and then build the non-primitive functions in terms of those. The basic constructs can be found [here](https://github.com/RojerGS/projects/blob/master/kleeneRecursion/basicFunctions.py) and the other definitions can be found implemented [here](https://github.com/RojerGS/projects/blob/master/kleeneRecursion/arithmetics.py).

For the more interested reader, I suggest trying to build some of the non-primitive functions before checking the code. Here is a complete list of the functions I implemented:

 - usual binary addition;
 - predecessor;
 - binary zero_sub: $zero\_sub(x, y) = \max(x-y, 0)$;
 - binary mod_sub: $mod\_sub(x, y) = |x - y|$;
 - sgn: $sgn(x) = 1$ if $x > 0$, if $x$ is 0 returns 0;
 - neq: $neq(x, y) = 1 \iff x \neq y$ and 0 otherwise;
 - eq: $eq(x, y) = 1 - neq(x, y)$;
 - geq: $geq(x, y) = 1 \iff x \geq y$, 0 otherwise;
 - usual binary greater: $greater(x, y) = 1 \iff x > y$, 0 otherwise;
 - leq: $leq(x, y) = 1 \iff x \leq y$ and 0 otherwise;
 - usual binary less: $less(x, y) = 1 \iff x < y$ and 0 otherwise;
 - subtraction: $subtraction(x, y) = x-y \iff x \geq y$, otherwise it is undefined;
 - dup: $dup(x) = 2x$;
 - usual binary multiplication;
 - usual unary factorial;
 - usual quotient: $quotient(n, d)$ returns the quotient of the integer division of $n$ by $d$;
 - usual remainder: $remainder(n, d)$ returns the remainder of the division of $n$ by $d$.