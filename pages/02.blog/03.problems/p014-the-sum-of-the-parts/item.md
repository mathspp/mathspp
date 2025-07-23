Split the numbers 0, 1, ..., 15 into two sets with sum interesting properties!

===

I got the idea for this problem after attending the [Recreational Maths Colloquium VI](http://ludicum.org/ev/rm/19), where I heard about the [Thue-Morse sequence](https://en.wikipedia.org/wiki/Thue%E2%80%93Morse_sequence) for the first time in my life.


## Problem statement

Given the integers $0, 1, \cdots, 15$, find a way to split them into two sets $A$ and $B$ such that

$$\sum_{a \in A} a^k = \sum_{b \in B} b^k,\quad k = 0,\cdots,3$$

where we define $0^0 = 1$.


## Solution

This problem is trivial if we know what we are looking for; if not, I would assume this can only be solved by _very resilient people_ or by those with the help of a computer.
Notice that the restriction above, for $k = 0$, just tells us that the sets $A$ and $B$ have the same number of elements.

Consider this sequence of length $16$:

$$ABBABAABBAABABBA$$

If we identify the sequence with the first $16$ non-negative integers, we get our two sets:

$$A = \{0, 3, 5, 6, 9, 10, 12, 15\}, B = \{1, 2, 4, 7, 8, 11, 13, 14\}$$

Some calculations suffice to show that this is the partition we are looking for:

$$
\begin{align*}
\sum_{a \in A} a^0 &= \sum_{b \in B} b^0 = 8 \\
\sum_{a \in A} a^1 &= \sum_{b \in B} b^1 = 60 \\
\sum_{a \in A} a^2 &= \sum_{b \in B} b^2 = 620 \\
\sum_{a \in A} a^3 &= \sum_{b \in B} b^3 = 7200 \\
\end{align*}
$$


## Bonus question

Find a partition of $0, 1, \cdots, 31$ into $A$ and $B$ with

$$\sum_{a \in A} a^k = \sum_{b \in B} b^k,\quad k = 0,\cdots,4$$

(Notice that now $k$ goes up to $4$).

Try to generalize your method, so that you can partition the numbers $0, 1, \cdots, 2^{n+1}-1$ into $A$ and $B$ with

$$\sum_{a \in A} a^k = \sum_{b \in B} b^k,\quad k = 0,\cdots,n$$

On the one hand, I feel like this should always possible and I did some calculations that seem to support my claim.
On the other hand, I couldn't find any bibliography to support my claim and I wouldn't be surprised if this broke for some value of $k$.
