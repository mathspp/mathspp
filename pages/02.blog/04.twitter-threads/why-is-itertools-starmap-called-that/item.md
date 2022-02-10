---
date: 10-02-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "An introductory example to `itertools.starmap` and an explanation of why it is called “starmap”."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: why-is-itertools-starmap-called-that
taxonomy:
    category: twitter thread
    tag:
      - code review
      - programming
      - python
title: "Why is itertools.starmap called that?"
---

Imagine you have a series of tuples with arguments for a Python 🐍 function.

For example, tuples of base and exponent:
[(2, 3), (2, 4), (2, 5)]
to represent 2³, 2⁴, 2⁵.

How can you map a function into those arguments?

Use itertools.starmap!

```py
>>> args = [
...     (2, 3),
...     (2, 4),
...     (2, 5),
... ]

>>> from itertools import starmap 
>>> list(starmap(pow, args))
[8, 16, 32]
```


_Why_ is it called `starmap`?

I'll show you!

How would you do this if you didn't know about `starmap`?

You would probably do this by mapping a lambda with `*args` ("star-args"):

```py
>>> args = [
...     (2, 3),
...     (2, 4),
...     (2, 5),
... ]

>>> list(map(lambda args: pow(*args), args)) 
[8, 16, 32]
```


Another _interesting_ alternative could be to use `zip` (with star) to “unzip” the arguments.

Then, you could use a plain `map`:

```py
>>> args = [
...     (2, 3),
...     (2, 4),
...     (2, 5),
... ]

>>> bases, exponents = zip(*args)
>>> bases
(2, 2, 2)
>>> exponents
(3, 4, 5)
>>> list(map(pow, bases, exponents))
[8, 16, 32]
```

What alternative do you often use?
Will you start using `starmap` now?
