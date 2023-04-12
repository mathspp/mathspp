---
title: "TIL #007 – math.nextafter"
metadata:
    description: "Today I learned about the `math.nextafter` method."
---

Today I learned about the `math.nextafter` method.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![Code snippet showing the `nextafter` method.](thumbnail.webp)


# `math.nextafter`

0 is a neat number, isn't it?

Perhaps one of the greatest discoveries of mankind.

But what's the number that comes after 0?
That would be the smallest number in the set $]0, +\inf[$,
if you're familiar with the mathematical notation for sets.

In short, $[a, b]$ is the contiguous set of numbers $x$ that satisfy
the restriction $a \leq x \leq b$.
Notice how $a$ and $b$ are included inside $[a, b]$ because the brackets
are closed.
If the brackets are open, then that number is not included.

For the intervals below, $x$ belongs to it if...

 - $[a, b]$ → $a \leq x \leq b$;
 - $[a, b[$ → $a \leq x < b$;
 - $]a, b]$ → $a < x \leq b$; and
 - $]a, b[$ → $a < x < b$.

So, in $]0, +\infty[$, nor 0, nor $+\infty$ are included.
Thus, what's the minimum element of this interval?
Well, there isn't any!

Mathematically speaking, there is no minimum in the interval $]0, +\infty[$.
Why not?
Whatever you pick as a potential minimum $m$, $m/2$ will be smaller than $m$
and still be greater than $0$, that is, $0 < m/2 < m < +\infty$,
and so $m/2$ is in $]0, +\infty[$.

That's interesting, right?

But this is a whole other story if we go into the programming real!
Because of how floats are represented,
Python _has_ a number that comes immediately after `0`.
So, what is it?

Here it is:

```py
>>> import math
>>> math.nextafter(0, 1)
5e-324
```

That's $5 \times 10^{-324}$, it's freakishly small!

(Your result may differ from mine, although I'm not sure if it will.
Leave a comment below if it does!)

So, what's the role of the `math.nextafter` method?

```py
>>> help(math.nextafter)
Help on built-in function nextafter in module math:

nextafter(x, y, /)
    Return the next floating-point value after x towards y.
```

Hence, `nextafter` looks at `x` and then checks what's the float that's immediately next to `x`,
if you walk in the direction of `y`.
If I set `x` to zero and `y` to one, I get the smallest float that Python can represent on my machine.

So, what's the next float that Python can represent after `1`?

Give it some thought.

Here it is:

```py
>>> math.nextafter(1, 999)
1.0000000000000002
```

I'll be honest, for a second I thought it should've been `1 + 5e-324`,
but it makes sense it wasn't that.
Floating point numbers have limited precision, right?
And one thing that's limited is the size of the mantissa:
the stuff that comes after the decimal point.

Above, we can see that the mantissa has 16 digits,
and that's the size of the mantissa in Python.

So, what's the next number after `10`?

Give it some thought.

Here it is:

```py
>>> math.nextafter(10, 999)
10.000000000000002
```

If you count, now there's only 15 digits to the right of the decimal point...
But I thought the size of the mantissa was 16 digits..?

And it is!

The mantissa is the size of the decimal part
_when the number is written in scientific notation_!

Using string formatting, we can see that the value above
has indeed 16 digits in its mantissa:

```py
>>> f"{math.nextafter(10, 999):.16E}"
'1.0000000000000002E+01'        # ← 10.000000000000002 in scientific notation.
```

! If you are not sure what just happened, don't worry.
! I'll soon write a [Pydon't][pydont] about string formatting!


That's it for now! [Stay tuned][subscribe] and I'll see you around!

By the way, here's the tweet I learned this from:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Another thread might be on writing a validation suite to verify that any given sign() function gives correct results (including type) for -1.5, 0, 1.5, and the smallest numbers around 0, math.nextafter(0, 1) and math.nextafter(0, -1), to check your round vs int question.</p>&mdash; Paul McGuire - pyparsing guy (@ptmcguire) <a href="https://twitter.com/ptmcguire/status/1443165807589081100?ref_src=twsrc%5Etfw">September 29, 2021</a></blockquote>


[subscribe]: /subscribe
[pydont]: /blog/pydonts
