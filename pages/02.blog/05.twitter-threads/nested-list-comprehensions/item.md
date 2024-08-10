---
date: 20-06-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "In this short article I show you how to create nested list comprehensions."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: nested-list-comprehensions
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Nested list comprehensions"
---

In this short article I show you how to create nested list comprehensions.

===

Did you know that [list comprehensions][list-comps-101] can be nested?
It's not as bad as it sounds, I'll show you.

List comprehensions are expressions: it's code that evaluates to a result.
And list comprehensions can contain arbitrary expressions on the left...
So, list comprehensions can be nested!

To nest list comprehensions, focus on one list comprehension at a time!
Here is an example:

```py
# Want to produce this pattern:
# [[0, 1, 4], [9, 16, 25], [36, 49, 64]]

# Double loop:
result = []
for i in range(3):
    inner = []                          # Convert the
    for j in range(3):                  # inner loop
        inner.append((3 * i + j) ** 2)  # first.
    result.append(inner)
```

To convert the double loop into a list comprehension, we start by focusing solely on the inner loop:

```py
# Outer loop, inner list comprehension:
result = []
for i in range(3):
    inner = [(3 * i + j) ** 2 for j in range(3)]
    result.append(inner)

# Without intermediate `inner` variable:
result = []
for i in range(3):
    result.append([(3 * i + j) ** 2 for j in range(3)])
#                 ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
```

Now that we converted the inner loop, we have the core loop pattern that hints at a list comprehension:

 1. initialisation of an empty list;
 2. a `for` loop; and
 3. appending to the same list over and over again.

Thus, this can be converted to a list comprehension,
as long as whatever is inside the call to `.append` goes in the beginning of the final list comprehension.

```py
# Whatever was inside the `.append` goes in the beginning.
result = [[(3 * i + j) ** 2 for j in range(3)] for i in range(3)]
#         ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
```

Nested list comprehensions are at the frontier of what are acceptable list comprehensions.
They are great when all the list comprehensions involved are short and straightforward.

By the way, when nesting list comprehensions, you may want to split them across multiple lines:

```py
# This list comp...
result = [[(3 * i + j) ** 2 for j in range(3)] for i in range(3)]

# ... is the same as this one:
result = [
    [(3 * i + j) ** 2 for j in range(3)]
    for i in range(3)
]
```

[list-comps-101]: /blog/pydonts/list-comprehensions-101

!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1539018549989187584) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
