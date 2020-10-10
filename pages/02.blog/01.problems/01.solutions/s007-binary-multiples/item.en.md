---
metadata: This blog post contains my proposed solution to one of the problems of this blog.
title: 'Solution #007 - binary multiples'
---

This post contains my proposed solution to [Problem #007 - binary multiples][prob]. Please do not read this solution before making a serious attempt [at the problem][prob].

===

### Solution

The answer is _yes_, any integer $k$ has a "binary multiple" $n$. To show this is true, we will build $n$ starting from $k$.

Assume $k$ is positive, and consider the following $k$ integers:

\[
    \big\{ 1, 11, 111, \cdots, \underbrace{1\cdots1}_{k\ 1\text{s}} \big\}
\]

(which can be formally written out as taking $\{c_i\}_{i = 1}^k$ with $c_1 = 1$ and $c_{i+1} = 10*c_i + 1$).

Then only one of two things can happen. Either one of $c_i$ is a multiple of $k$ (in which case all is good) or not. But if no $c_i$ is a multiple of $k$, then we can consider the remainders of the $c_i$ modulo $k$:

\[
    \{ c_1\ \text{mod}\ k, c_2\ \text{mod}\ k, \cdots, c_k\ \text{mod}\ k \} \subseteq \{ 1, \cdots, k - 1 \}
\]

We say that the remainders of the $c_i$ are contained in the set to the right because none of the remainders is $0$, otherwise one of the $c_i$ would be a multiple of $k$.

Notice the left-hand set is built by taking the remainders of the $k$ different $c_i$ but the right-hand set only has $k - 1$ elements. The [pigeonhole principle][pigeonhole-principle-wiki] then says that there are at least two different $c_i$, $c_j$ being mapped to the same element in the right-hand set, i.e. $c_i \equiv c_j \ \text{mod}\ k$. Assume we have $j > i$, meaning $c_j > c_i$ and, in particular:

\[
    \begin{cases}
        c_j - c_i \equiv 0\ \text{mod}\ k \\
        c_j - c_i = \underbrace{1\cdots 1}_{j-i\ 1\text{s}} \underbrace{0\cdots 0}_{i\ 0\text{s}}
    \end{cases}
\]

Thus $n = c_j - c_i$ is a "binary multiple" of $k$.

If $k$ is negative, we repeat the above for $-k$. If $k = 0$, then $n = 0$.

**Example:**

If $k = 4$ we consider $c_1 = 1$, $c_2 = 11$, $c_3 = 111$, $c_4 = 1111$ and realize none of these numbers is a multiple of $4$.

Now we take the remainders:

\[
    \begin{cases}
        1 \equiv 1\ \text{mod}\ 4 \\
        11 \equiv 3\ \text{mod}\ 4 \\
        111 \equiv 3\ \text{mod}\ 4 \\
        1111 \equiv 3\ \text{mod}\ 4
    \end{cases}
\]

and see that, for example, $c_3 \equiv c_2\ \text{mod}\ 4$, implying that $c_3 - c_2 = 100 \equiv 0\ \text{mod}\ 4$.

If you have any questions about my solution, found any mistakes (whoops!) or would like to share *your* solution, be sure to leave a comment below.

[pigeonhole-principle-wiki]: https://en.wikipedia.org/wiki/Pigeonhole_principle
[prob]: ../../{{ page.slug }}
