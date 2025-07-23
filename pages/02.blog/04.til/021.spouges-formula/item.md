Today I learned about Spouge's formula to approximate the factorial.

===

![](thumbnail.png "Photo by Scott Graham on Unsplash (cropped).")


## Spouge's formula

Spouge's formula allows one to approximate the value of the gamma function.
In case you don't know, the gamma function is like a generalisation of the factorial.

In fact, the following equality is true:

$$
\Gamma(z + 1) = z!
$$

where $\Gamma$ is the gamma function.

What Spouge's formula tells us is that

$$
\Gamma(z + 1) = (z + a)^{z + \frac12}e^{-z-a}\left( c_0 + \sum_{k=1}^{a-1} \frac{c_k}{z+k} + \epsilon_a(z) \right)
$$

In the equality above, $a$ is an arbitrary positive integer and $\epsilon_a(z)$ is the error term.
Thus, if we drop $\epsilon_a(z)$, we get

$$
\Gamma(z + 1) = z! \approx (z + a)^{z + \frac12}e^{-z-a}\left( c_0 + \sum_{k=1}^{a-1} \frac{c_k}{z+k} \right)
$$

The coefficients $c_k$ are given by:

$$
\begin{cases}
c_0 = \sqrt{2\pi} \\
c_k = \frac{(-1)^{k-1}}{(k - 1)!}(-k + a)^{k - \frac12}e^{-k+a}, ~ k \in \{1, 2, \cdots, a-1\}
\end{cases}
$$

By picking a suitable value of $a$, one can approximate the value of $z!$ up to a desired number of decimal places.
Although we need the factorial function to compute the coefficients $c_k$,
those coefficients only need the factorial of numbers up to $a - 2$.
If we are approximating $z!$, where $a << z$, then this approximation saves us some work.

In order to determine the number of correct decimal places of the result,
one needs to control the error term $\epsilon_a(z)$.
If $a > 2$ and the $Re(z) > 0$ (which is always true if $z$ is a positive integer), then

$$
\epsilon_a(z) \leq a^{-\frac12}(2\pi)^{-a-\frac12}
$$

By determining the value of $a^{-\frac12}(2\pi)^{-a-\frac12}$,
we can tell how many digits of the result will be correct.
For example, with $a = 10$, we get

$$
a^{-\frac12}(2\pi)^{-a-\frac12} \approx 1.31556 \times 10^{-9} ~ ,
$$

meaning we will get 8 correct digits.

! However, notice that the approximating formula must, itself,
! be computed with enough precision for the final result to hold
! as many correct digits as expected.
! In other words, if a higher value of $a$ is picked so that the
! final result is more accurate, then we need to control the accuracy used when
! computing the coefficients $c_k$ and the formula itself.

I'll leave it as an exercise for you, the reader,
to implement this approximation in your favourite programming language.


## Spouge's formula in APL

In APL, (and disregarding the accuracy issues) it can look something like this:

```APL
      ⍝ Computes the `c_k` coefficients:
      Cks ← {(.5*⍨○2),((!ks-1)÷⍨¯1*ks-1)×((⍵-ks)*ks-.5)×*⍵-ks←1+⍳⍵-1}

      ⍝ Computes the approximation of the gamma function:
      GammaApprox ← {((⍵+⍺)*⍵+.5)×(*-⍵+⍺)×(⊢÷1,⍵+1↓⍳∘≢)Cks ⍺}

      ⍝ Computes an upper bound for the error term:
      Err ← {(⍵*¯.5)×(○2)*-⍵+.5}

      a ← 10
      Err a
1.315562187E¯9  ⍝ Thus, we expect 8 decimal places to be correct.
      z ← 100
      a GammaApprox z
9.332621544E157
      !z
9.332621544E157
```


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[wiki-file-descriptor]: https://en.wikipedia.org/wiki/File_descriptor
