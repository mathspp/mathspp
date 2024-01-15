Can you cover all of the rational numbers in [0, 1] with tiny intervals?

===

This problem was inspired by an awesome video by [3blue1brown](https://www.youtube.com/channel/UCYO_jab_esuFRV4b17AJtAw).


# Problem statement

For a given $\epsilon > 0$, is there a way for you to cover all the rational numbers in the interval $[0, 1]$ with small intervals $I_k$, such that the sum of the lengths of the intervals $I_k$ is less than or equal to $\epsilon$?

In other words (with almost no words), for what values of $\epsilon > 0$ is there a collection $\{I_k\}$ of intervals such that

$$\left(\mathbb{Q}\cap [0,1]\right) \subseteq \left(\cup_k I_k \right) \wedge \sum_k |I_k| < \epsilon$$


# Solution

Such a family of intervals always exists, for any value of $\epsilon > 0$.
We start by noticing that the rational numbers in the interval $[0, 1]$ are countably many, which means I can order them as $q_1, q_2, q_3, \cdots$.
If you haven't solved the problem yet, take the hint I just gave you and try to solve it.

After enumerating the rationals inside $[0, 1]$, we define $I_k$ to be $[q_k - \epsilon2^{-k-1}, q_k + \epsilon2^{-k-1}]$.
By defining $I_k$ this way, we get that

$$|I_k| = (q_k + \epsilon2^{-k-1}) - (q_k - \epsilon2^{-k-1}) = \frac{\epsilon}{2^k}$$

We now are left with showing that with the intervals defined this way, we have the two desired properties.
In fact, we have that $\left(\mathbb{Q}\cap [0,1]\right) \subseteq \left(\cup_k I_k \right)$.

If $q$ is some rational number in $[0, 1]$, then because they are countably many and I listed all of them, $q$ is equal to some $q_k$, but then $q = q_k \in I_k \subset \cup_k I_k$.

As to whether the sum of the lengths is smaller than or equal to $\epsilon$, we just have to compute the series

$$\sum_{k=1}^\infty |I_k| = \sum_{k=1}^\infty \frac{\epsilon}{2^k} = \epsilon \sum_{k=1}^\infty \frac1{2^k} = \epsilon$$

And that was it!
Pretty simple proof, but then I feel like the result is absolutely amazing if we consider $\epsilon < 1$.

Don't forget that the rationals are **dense** in $[0, 1]$, which in a not-so-rigorous way, means that there are rationals _everywhere_ in the interval $[0, 1]$...
But we can cover them up with smaller intervals that clearly don't cover the whole interval, because the whole interval has size $1$ and the sizes of our intervals only add up to $\epsilon$...
And we didn't even take into account that some of the smaller $I_k$ intervals overlap with each other!

In case you are wondering, the video from which I got this problem is [this](https://www.youtube.com/watch?v=cyW5z-M2yzw) one.
