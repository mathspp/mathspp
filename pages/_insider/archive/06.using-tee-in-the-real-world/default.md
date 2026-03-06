---
date: 26-01-2026
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how itertools.tee can be used in the real world."
    og:image: "https://mathspp.com/insider/archiveusing-tee-in-the-real-world/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archiveusing-tee-in-the-real-world/thumbnail.webp"
title: Using tee in the real world

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Using `tee` in the real world

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!


## `tee` recap

[Two weeks ago you learned about `itertools.tee`](https://mathspp.com/insider/archive/breaking-a-fundamental-law-of-python) and [last week you saw how to implement it yourself](https://mathspp.com/insider/archive/implementing-itertools-tee).

In case you don't recall, `tee` takes an **iterator** and splits it into 2+ _independent_ **iterators**:

```py
from itertools import tee

squares = (x ** 2 for x in range(1, 3))
squares1, squares2 = tee(squares, 2)

for sq in squares1:
    print(sq)
# 1
# 4

for sq in squares2:
    print(sq)
# 1
# 4
```

## `itertools.pairwise`

One example use case of `tee` is to implement `pairwise` from the module `itertools`.
`pairwise` works like this:

```py
from itertools import pairwise

names = ["Harry", "Anne", "George"]

for left, right in pairwise(names):
    print(f"{left} says hi to {right}")

"""Output:
Harry says hi to Anne
Anne says hi to George
"""
```

It produces overlapping pairs of consecutive items.

One friend of mine replied to my email with an attempt at implementing `pairwise` in terms of `tee`:

```py
def my_pairwise(iterable):
    orig_it = iter(iterable)
    it_1, it_2 = tee(orig_it, 2)
    next(it_2)
    while True:
        yield next(it_1), next(it_2)
```

Can you spot the bug?

## A generator raising a runtime error

I will give you a hint!

If you take `my_pairwise` and use it above, in the example where I show how `pairwise` works, you get an interesting `RuntimeError`:

```py
RuntimeError: generator raised StopIteration
```

That's funky, hun?

## The goal of a generator

To figure out the bug in `my_pairwise`, it is helpful to remember what a generator function is supposed to be.

A **generator function** is a convenient way to define **iterators**, which are lazy iterables that only compute values on demand.

The keyword here is _convenient_.

If you learn about the **iterator protocol**, you can define classes that implement iterators.

For that, you need to implement two special methods.

But generator functions make it easier for you.

By using a generator function, you just write a function and you use the keyword `yield` whenever you want to produce a value.

When the generator is done, the function should just finish.

And that's the problem!

## Exhausting one of the iterators

The problem lies in the fact that the code has `yield next(it_1), next(it_2)`.

What happens if `it_2` is finished?

If it doesn't have any more values?

In that case, `next(it_2)` raises an exception.

More specifically, a `StopIteration` exception.

Now, `StopIteration` exceptions are very special.
They are part of the **iterator protocol** that tells Python that an iterator is done.

So the generator sees the `StopIteration` and becomes confused.

Is the generator itself finished..?
Or did something else raise `StopIteration` by mistake?

The generator can't tell what is happening...
And it raises a `RuntimeError`!

To fix this, you need to handle the `StopIteration` and just use a plain `return` when the generator is done.

## Fixing `my_pairwise`

With this knowledge, my friend fixed his implementation of `my_pairwise`:

```py
def my_pairwise(iterable):
    orig_it = iter(iterable)
    it_1, it_2 = tee(orig_it, 2)
    next(it_2)
    try:
        while True:
            yield next(it_1), next(it_2)
    except StopIteration:
        return
```

Note how the `StopIteration` is being handled, and instead replaced with `return`, which is the correct way to tell the generator that it's done.

But now, note how there's something super interesting going on.

The `while` loop is running until we get to the end of `it_1` or `it_2`...

But that's exactly what a `for` loop is for!

## Implementing a shorter generator function

If you use `for` loop, the implementation becomes simpler:

```py
def my_pairwise(iterable):
    orig_it = iter(iterable)
    it1, it2 = tee(orig_it, 2)
    next(it2)
    yield from zip(it1, it2)
```

That's pretty cool, right?

By using `zip`, you express the idea of going through both iterators at the same time.

You just needed to skip one value ahead for `it2` with `next(it2)`.

This creates the necessary offset.

Then, by using `yield from ...`, you are saying that your generator depends on the values from `zip(...)`, and when the `zip` is over, your generator also stops gracefully.

Without having to manage exceptions manually.

## Enjoyed reading? 🐍🚀

Get a _free_ Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
