Learn about integers that have infinitely many digits and that are equal to their own square.

===

# Introduction

Have you ever noticed that if two integers end in $6$, their product also ends in $6$?
This is not an accident and can be verified with some calculations.

If an integer ends in $6$, then it can be written as $10n + 6$.
For example, the integer $376$ can be written as $10n + 6$ with $n = 37$.

So, two integers $a$ and $b$ ending in $6$ would be written as

$$
\begin{align}
a &= 10n + 6, \\
b &= 10m + 6.
\end{align}
$$

Therefore, their product will be

$$
ab = (10n + 6) \times (10m + 6) = 100nm + 60n + 60m + 36 ~ .
$$

The first three terms are all multiples of $10$ so it is the term $36$ that determines the units digit, which happens to be $6$.

This also happens with numbers ending in $1$ and ending in $5$ and the verification would be similar.

Now, can we extend this observation to longer numbers?
Are there numbers with two or more digits that satisfy a similar property?


# Two-digit trailing product match

As it turns out, there are.
Even if you have never noticed a two-digit number that satisfies a similar property, we can use a short Python script to find out possible contenders.

Let us look for two-digit numbers that show up at the end of their own squares:

```py
for n in range(10, 100):
    if str(n ** 2).endswith(str(n)):
        print(n)
```

If you run this piece of code, the numbers you get are $25$ and $76$ because they are the only two-digit numbers that match the trailing part of their squares:

$$
\begin{align}
25^2 &= 625,\\
76^2 &= 5776.
\end{align}
$$

We still have to verify if the product of _any_ two numbers $a$ and $b$ that end in $25$ will also end in $25$.
And the same thing for $76$.

Let us do the verification for $76$, which should be similar as the one for $6$.

If two numbers $a$ and $b$ end in $76$, then they can be written as

$$
\begin{align}
a &= 100n + 76,\text{ and} \\
b &= 100m + 76.
\end{align}
$$

For example, the number $9376$ can be written as $100n + 76$ if $n = 93$.

Now, the product $ab$ is

$$
ab = (100n + 76) \times (100m + 76) = 10000nm + 7600n + 7600m + 5776 ~ .
$$

The first three terms are $10000nm$, $7600n$, and $7600m$, and they are all multiples of $100$,
thus they do not affect the last two digits of the product $ab$.
This means that the last two digits of the product $ab$ are the last two digits of $5776$, which are $76$.

With this, we have shown that if two integers end in $76$, then their product will end in $76$.
As a challenge, try proving a similar statement for integers ending in $25$.

Notice that, as a consequence of what we have just proven, $76^2$ ends in $76$ and $25^2$ ends in $25$.

Can we keep this train of thought going?
Can we create longer integers that match the trailing part of their squares?


# Three-digit trailing product match

Maybe!

Let us work with $76$.
If we add a digit $k$ on the left of $76$, we get the number of $100k + 76$.
Can we pick a value of $k$ such that, if two numbers end in $100k + 76$, then their product also ends in $100k + 76$?
We can try to find such a number...

If two numbers $a$ and $b$ end in $100k + 76$, then they can be written as

$$
\begin{align}
a &= 1000n + 100k + 76, \text{ and} \\
b &= 1000m + 100k + 76 ~ .
\end{align}
$$

If we multiply $a$ and $b$ together, we get

$$
ab = (1000n + 100k + 76) \times (1000m + 100k + 76) = 10^6nm + 10^5(nk + mk) + 76000(n + m) + 10000k^2 + 15200k + 5776 ~ .
$$

That product contains a lot of terms...
Luckily, we don't care about most of them.
The terms that are divisible by $1000$ will not influence the last three digits of the product $ab$, which means we only care about $15200k + 5776$.

In other words, the numbers $ab$ and $15200k + 5776$ share the last three digits.

Recall that what we wanted originally was for $ab$ to end in $100k + 76$.
If $ab$ ends in $100k + 76$, then the difference between $15200k + 5776$ and $100k + 76$ is a multiple of $1000$.

In modular arithmetic, this can be written as

$$
\begin{align}
ab \equiv 100k + 76 \mod{1000} &\iff 15200k + 5776 \equiv 100k + 76 \mod{1000} \iff \\
&\iff 15100k + 5700 \equiv 0 \mod{1000} ~ .
\end{align}
$$
