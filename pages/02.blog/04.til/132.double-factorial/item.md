Today I learned about the double factorial.

===

The double factorial, $n!!$, is defined as the product of all positive integers less than or equal to $n$ that have the same parity as $n$.

Some examples:

 - $5!! = 5 \times 3 \times 1$
 - $8!! = 8 \times 6 \times 4 \times 2$

When reading about it in a book authored by a friend of mine, the identity $(2n)!! = 2^n n!$ was also presented, and I'll prove it now by induction.
For $n = 1$, we have $2!! = 2 = 2^1 \times 1!$, which is true.
Now, assuming the identity holds up to $n$, we show it holds for $n + 1$:

$$\begin{align}
(2(n + 1))!! &= (2(n + 1)) \times (2n)!! \\
&= (2(n + 1)) \times 2^n n! \\
&= (n + 1) \times 2^{n + 1} n! \\
&= 2^{n + 1} (n + 1)!
\end{align}$$

Done!
