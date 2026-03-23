---
date: 23-03-2026 18:49
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn about bound type variables and their main use case."
    og:image: "https://mathspp.com/insider/archive/type-variables-and-protocols/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/type-variables-and-protocols/thumbnail.webp"
title: "Type variables and protocols"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Type variables and protocols

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Writing a type variable

[A type variable](https://mathspp.com/insider/archive/why-do-type-variables-matter) is a typing feature that allows you to create relationships between the types in your functions.

For example, the function `deduplicate`, shown below, uses a type variable to specify that, given a list of whatever values, you'll get back a set of the same type of values:

```py
from typing import reveal_type

def deduplicate[T](sequence: list[T]) -> set[T]:
    return set(sequence)

reveal_type(deduplicate([1, 1, 2, 2, 3, 3]))  # set[int]
```

But there's a problem with this function.

If you try to call `deduplicate` with a list of dictionaries, for example, the function doesn't _run_ because dictionaries are not hashable.

Which means they can't be used as set elements.

But the type hints don't convey this restriction.

So, let's fix that.

And for that, we'll need protocols.

## Protocols

In short, protocols allow you to define _behaviours_ that you care about without having to bake them into the class hierarchy of an object.

For example, you can say that lists are iterable, but if you check their class hierarchy, the class `Iterable` doesn't show up in there.

Similarly, integers are hashable, but if you check their class hierarchy, the class `Hashable` doesn't show up in there.

And what's the point?

For one, think about strings.

They're a _fundamentally_ different type from lists or integers.

And yet, strings are also `Iterable`.

It'd be a bit weird if lists and strings explicitly inherited from `Iterable`.

And even weirder if you think about the fact that strings are also hashable.

So strings would also need to inherit from `Hashable`, like integers.

And this would lead to a really weird class hierarchy diagram.

Instead, protocols let you _define_ these behaviours in abstract without having to worry about explicit inheritance.

## `collections.abc`

The module `collections.abc`, from the standard library, is where many of the protocols that Python uses live.

For example, you can find `Iterable` and `Hashable` there.

And by doing so, you can add better types to the function `deduplicate`:

```py
from collections.abc import Hashable, Iterable
from typing import reveal_type

def deduplicate(sequence: Iterable[Hashable]) -> set[Hashable]:
    return set(sequence)
```

Now, the type hints say that the iterable that is given as the argument must contain hashable elements.

But now, you lost some information.

Now, the return type is too broad: `set[Hashable]`.

For example, if you pass it a list of integers, your static type checker can't infer that you're getting a set of integers back:

```py
reveal_type(deduplicate([1, 1, 2, 2, 3, 3]))  # set[Hashable]
```

What you want to do is combine both things:

 1. You want to be able to use a type variable to create a connection between the argument and the return.
 2. You want to be able to use the protocol `Hashable` to specify that the values you receive must be hashable.

That's what **bound type variables** are for.

## Bound type variables

A **bound type variable** is defined inside square brackets, after the function name, and it's composed of a type variable, a colon (`:`), and a type.

This type can be _any type whatsoever_, including a protocol.

Here's what this looks like in the function `deduplicate`:

```py
from collections.abc import Hashable, Iterable
from typing import reveal_type

def deduplicate[H: Hashable](sequence: Iterable[H]) -> set[H]:
    return set(sequence)
```

The `H` in `[H: Hashable]` means that the type variable `H` is _bound_ to the type `Hashable`.

This means that the only acceptable types for `H` must be subclasses of `Hashable`.

If you call the function with a list of integers, your static type checker can tell you the result will be a set of integers:

```py
reveal_type(deduplicate([1, 1, 2, 2, 3, 3]))  # set[int]
```

This is the main use case for bound type variables:

Use them in conjunction with protocols when you need the flexibility of a type variable to create a relationship between different types _while also_ restricting the allowed types to types that have know a specific behaviour.

## The little book of typing

You'll learn this, and more, in “The little book of typing”, a book I'm currently working on.

Stay tuned for updates!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
