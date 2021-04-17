---
metadata:
    description: In this post I go over three common proof methods in mathematics.
title: The CCC of proof methods
---

In this post we will talk about three different, all very common, ways of writing proofs: proofs by construction, by contrapositive and by contradiction.

===

![a board with some mathematical squiggles](board.jpg "Photo by Roman Mager on Unsplash")

### Proof by construction

Proofs by construction are probably the proofs that make more sense or are more intuitive in nature. When you prove something by construction, you explicitly build the thing that you described to exist, or give an explicit way of verifying what you described. This is very important because more often than not, mathematics can prove things like _"an object $x$ satisfying this and that property exists"_, but without providing any sensible means to find such object.

A good example of a proof by construction is the proof that every function $f: \mathbb{R}\to\mathbb{R}$ can be decomposed into a sum $f(x) = O(x) + E(x)$ where $O(x)$ is an odd function and $E(x)$ is an even function, i.e.

$$
    \begin{cases}
        O(-x) = -O(x)\\
        E(-x) = E(x)
    \end{cases}\ \forall x \in \mathbb{R}
$$

To prove this statement, we will build $O$ and $E$ directly from the function $f$ and then we show that $O$ is odd, $E$ is even, and that $f(x) = O(x) + E(x)$. For this matter, define

$$
    \begin{cases}
        E(x) = \frac{f(x) + f(-x)}{2}\\
        O(x) = \frac{f(x) - f(-x)}{2}
    \end{cases}
$$

We start by showing that they sum up to $f$:

$$
\begin{align}
    O(x) + E(x) &= \frac{f(x) - f(-x)}{2}&nbsp;+ \frac{f(x) + f(-x)}{2} \\
    &= \frac{f(x) - f(-x) + f(x) + f(-x)}{2} \\
    &= \frac{2f(x)}{2} = f(x)
\end{align}
$$

We are now left with showing that $O$ is odd and $E$ is even... In fact,

$$
    O(-x) = \frac{f(-x) - f(-(-x))}{2} = \frac{f(-x) - f(x)}{2} = -\frac{f(x) - f(-x)}{2} = -O(x)
$$

and

$$
    E(-x) = \frac{f(-x) + f(-(-x))}{2} = \frac{f(-x) + f(x)}{2} = \frac{f(x) + f(-x)}{2} = E(x)
$$

Proofs by construction are my favourites and whenever I can I try to follow this proof method, as I find it very rewarding to build the object(s) described by a theorem or proposition or whatnot, instead of just proving they exist. Another neat example of a proof by construction can be read in [this brief twitter proof of mine][tp-irrationals].

Another example of a proof by construction is this: prove that given pairs $\{(x_1, y_1), (x_2, y_2), \cdots, (x_{n+1}, y_{n+1})\}$ where $x_i \neq x_j\ \forall i\neq j$ there exists a polynomial $p(x)$ of degree at most $n$ such that $p(x_i) = y_i\ \forall 1\leq i\leq n+1$. To prove this we will show something easier: for the same set of points, we can find a set $\{l_1, l_2,\cdots, l_{n+1}\}$ of polynomials of degrees at most $n$ such that

$$
    l_i(x_j) = 
    \begin{cases}
        1, i = j\\
        0\ \text{otherwise}
    \end{cases}
$$

If we can find that set of polynomials, then we just need to set

$$
    p(x) = y_1l_1(x) + y_2l_2(x) + \cdots + y_{n+1}l_{n+1}(x)
$$

We prove the existence of that set of polynomials by providing a way of building them. Notice how $l_1$ is supposed to be $0$ at $x_2$. So $(x-x_2)$ should definitely be part of the factorization of $l_1(x)$; following the same reasoning, so should $(x-x_3), \cdots, (x-x_{n+1})$. Let us try setting

$$
    l_1(x) = (x-x_2)\times(x-x_3)\times\cdots\times(x-x_{n+1}) = \prod_{j\neq1} (x-x_j)
$$

It is already true that $l_1(x_j) = 0$ if $j \neq 1$. But as of now,

$$
    l_1(x_1) = \prod_{j\neq 1} (x_1-x_j) \neq 1
$$

We can fix that easily by dividing $l_1$ by that number! Hence

$$
    l_1(x) = \left(\prod_{j\neq1} (x - x_j)\right)\big/\left(\prod_{j\neq1}(x_1 - x_j)\right) = \prod_{j\neq1} \frac{x-x_j}{x_1-x_j}
$$

satisfies the required property of being $1$ at $x_1$ and $0$ at any other $x_i$ from the list.

We can do a similar construction for any other $l_i$, where the general expression becomes

$$
    l_i(x) = \prod_{j\neq i}\frac{x-x_j}{x_i - x_j}
$$

and thus we have showed that we can build the required polynomials. Because each $l_i$ is the product of $n$ factors, each of degree $1$, it follows that each $l_i$ has degree $n$. The weighted sum

$$
    p(x) = y_1l_1(x) + y_2l_2(x) + \cdots + y_{n+1}l_{n+1}(x)
$$

has therefore degree $n$ or less. 

The two other methods we will see are indirect methods, in the sense that they prove statements but they do not provide a direct way of building the objects described.


### Proving the contrapositive

The first indirect method I want to describe is when you prove an implication by proving its contrapositive. You can apply this method when you want to prove that some conditions $C $ imply a result $R $. That is, you want to show that whenever all conditions in $C $ are met, then you can observe result $R $; in symbols, $C \implies R $. The contrapositive of $C \implies R $ is $\neg R \implies \neg C $. In words, if you didn't observe result $R$ then not all conditions were met.

If you think about it for a second, it is intuitive that $C\implies R $ and $\neg R \implies \neg C $ are the same... Meeting the conditions gives you $R $ ($C \implies R $) so if you didn't get the result you surely didn't have the conditions ($\neg R \implies \neg C $)! Because if you had them, then you would also have the result... But you don't!

Let us now prove something by proving its contrapositive instead. We will show that if $s $ is the sum of two consecutive integers, then $s $ is odd. To prove the contrapositive we need to show that if $s $ is even, $s$ can't be the sum of two consecutive integers. This statement is fairly trivial, but we will do it anyway:

Let $a$, $b$ be integers with $a+b = s $. Because $s $ is even, there is another integer $k $ with $2k = s $ and so we get:

$$
    \begin{cases}
        a + b = 2k \iff b = 2k - a\\
        a - b = a - (2k - a) = 2a - 2k = 2(a - k)
    \end{cases}
$$

which means the difference between $a $ and $b $ is also even. But if $a$, $b$ were consecutive integers we would have $a-b = \pm1$, which is odd whether we have $+1$ or $-1$. Hence it cannot be the case that $a$, $b$ are consecutive integers.

As another example, we will show that if $A, B \subset \mathbb{Z}$ are finite subsets of the integers and if $A \subset B$, then $\max A \leq \max B$. To prove this, we will show that $\max A > \max B \implies A \not\subset B $. In fact, let $a = \max A > \max B = b$ and we show that $a \not \in B$. If $a \in B$, then $b \geq a$ because $b = \max B$, but we know that $a > b$ so $a \not \in B$.


### Arriving at a contradiction

When you wish to prove something by contradiction, all you have to do is assume the contrary to what you want to prove, and then build some train of thought that eventually leads you to an absurd conclusion!

If your train of thought isn't flawed, then the error must have been in your assumption.

As an example, let us prove that if you pick $11$ integers from $\{1, 2, 3, \cdots, 30\}$, there are at least two of them, call them $x$ and $y$, with $|x - y| \leq 2$. To prove this, we will start by assuming that we picked $11$ integers and no two of them are sufficiently close, and we will proceed to reach a contradiction:

Imagine ordering the $11$ integers we picked and writing them down in this way:

$$
    a_0 < a_1 < a_2 < a_3 < a_4 < a_5 < a_6 < a_7 < a_8 < a_9 < a_{10}
$$

Now we replace each $a_i$ with $a_i - a_{i-1},\ \forall i &gt; 0$, ending up with this:

$$
    \begin{align}
        &a_0,\ a_1-a_0,\ a_2-a_1,\ a_3-a_2,\ a_4-a_3,\ a_5-a_4,\\
        &a_6-a_5,\ a_7-a_6,\ a_8-a_7,\ a_9-a_8,\ a_{10}-a_9
    \end{align}
$$

Now we have $a_{10} = a_0 + \sum_{i=1}^{10}(a_i - a_{i-1})$ and $ \sum_{i=1}^{10} a_i - a_{i-1} \geq 30$, meaning that $a_{10} \geq a_0 + 30 \implies a_{10} \geq 31$ because $a_0 \geq 1$ which contradicts the fact that $a_{10}$ was picked from within the set $\{1, 2, 3, \cdots, 30\}$, hence we were wrong in supposing said thing was possible.

Another good example is a proof that $\sqrt{2}$ is irrational. To show that, suppose $\sqrt2$ was rational and set $\sqrt2 = \frac{m}{n}$, where $\frac{m}{n}$ has been reduced to its lowest terms, i.e. $m$ and $n$ share no divisors. Now consider

$$
    \sqrt{2} = \frac{m}{n} \iff \sqrt{2}^2 = \left(\frac{m}{n}\right)^2 \iff 2 = \frac{m^2}{n^2}
$$

From this, one concludes that $2$ divides $m$ so we actually have $\sqrt{2} = \frac{m}{n} = \frac{2m'}{n} \iff \frac{\sqrt{2}}{2} = \frac{m'}{n}$. Now we square both sides again:

$$
    \left(\frac{\sqrt2}{2}\right)^2 = \left(\frac{m'}{n}\right)^2 \iff \frac{1}{2} = \frac{m'^2}{n^2}
$$

From this we conclude that $2$ divides $n$, and so actually we have $\sqrt{2} = \frac{m}{n} = \frac{2m'}{2n'} = \frac{m'}{n'}$ that contradicts our hypothesis that $\frac{m}{n}$ was already in its lowest terms, hence it is false that $\sqrt{2}$ is rational.

A proof by contrapositive and a proof by contradiction are quite similar, but they are not the same thing. In a proof by contrapositive you take the negation of the result and you show it implies the negation of the initial assumptions. In a proof by contradiction you take your initial assumptions and the negation of your result and then try to reach an absurd conclusion.

And this concludes my post on these three proof methods. Which one if your favourite?

[tp-irrationals]: https://mathspp.com/blog/twitter-proofs/irrational-rationality
