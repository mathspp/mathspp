---
date: 30-06-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "In this short article I describe how to use conditional expressions in list comprehensions."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: conditional-expressions-in-list-comprehensions
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Conditional expressions in list comprehensions"
---

In this short article I describe how to use conditional expressions in list comprehensions.

===


Conditional expressions in [list comprehensions][list-comps-101] can be quite powerful because they let us write more flexible comprehensions.

List comprehensions start with an expression that modifies values.
Typically, you always modify values in the same way, with the same function or formula.
However, sometimes you may want to adapt the modification to the value itself.

Here's an example using the Collatz sequence:

```py
# Google for the Collatz conjecture;
# Take a number n, and do a step:
# odd numbers go n -> 3 * n + 1
# even numbers go n -> n // 2

# Compute 1 step for some initial numbers:
steps = []
for n in range(100):
    if n % 2:
        steps.append(3 * n + 1)
    else:
        steps.append(n // 2)
```

Notice how, in the example above, we always want to append to the final list.
It's just that we want to append one of two different things, depending on `n % 2`.
This is where a conditional expression comes in:

```py
steps = []
for n in range(100):
    # Compute next value depending on n % 2
    step = 3 * n + 1 if n % 2 else n // 2
    steps.append(step)
```

At this point, the classic list comprehension pattern arises and you can rewrite the loop above as a list comprehension.
To give you a hand, I did it for you below.
When using a conditional expression, I like to have the conditional expression by itself:

```py
steps = [
    3 * n + 1 if n % 2 else n // 2  # How to modify n
    for n in range(100)             # Where to get n from
]
```

To conclude, beware of a source of confusion:

 - conditional expressions in the beginning; vs
 - using `if` after a `for` to filter values.

They do different things, and

 - the former always looks like `... if ... else ...`; while
 - the latter _cannot_ have an `else`.


[list-comps-101]: /blog/pydonts/list-comprehensions-101


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1542633129999056897) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
