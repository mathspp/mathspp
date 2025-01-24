---
author: Eric Matthes
date: 24-01-2025 14:56
link: https://www.mostlypython.com/using-any/
taxonomy:
    category: link
title: "Using `any()`"
# via:
---

In this article the author introduces the built-in `any`.
However, this isn't really a “tutorial on how to use `any`”; instead, Eric shares his experience with using `any` and reflects on the fact that apparently he is never able to use it when first implementing something.
Eric only thinks of using it on a refactoring pass.

I liked this write-up because it's a very candid demonstration of what Eric considers to be a suboptimal behaviour of his, and then gets us to think about the simple things, like `any`, that are not so simple if you think about the big picture.
For example, to use `any` in the real world you will hardly ever be given a list of Boolean values to which you can apply `any`.
In the context of making a decision based on whether any item in a collection satisfies a given predicate, you have to learn to recognise the pattern:

```py
any(predicate(element) for element in iterable)
```

This line of code answers the question “does any element of the given iterable satisfy my predicate?”.
