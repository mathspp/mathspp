---
date: 06-04-2026 18:16
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to make the most out of your type variables."
    og:image: "https://mathspp.com/insider/archive/bounds-vs-constraints/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/bounds-vs-constraints/thumbnail.webp"
title: "Bounds vs constraints"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Bounds vs constraints

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Type variables

As you saw [in a previous email](https://mathspp.com/insider/archive/why-do-type-variables-matter), type variables are a very neat mechanism in type hinting.

They allow you to establish relationships between the types of arguments and the return value of a function or of methods in classes.

But type variables are more flexible than that.

As a matter of fact, type variables can be defined with:

 1. an upper bound; or
 2. a constraint.

These two features let you write more flexible type variables that allow you to specify better relationships in your function types.

Let me show you what both features do.

## Resolving a type variable

Before understanding upper bounds and constraints, you need to understand what it means for a type variable to **resolve** to a certain type.

When you use a type variable, you're specifying a relationship between two or more types.

For example, the function `add` below uses a type variable to specify that the two arguments and the return value of the function `add` all have the same type:

```py
def add[T](a: T, b: T) -> T:
    return a + b
```

At this point, the type variable `T` is just a _variable_.

When you use the function `add`, you'll be using some values as arguments to the function `add`.

And the function `add` will return a value.

When a type checker looks at the _usage site_ of the function, it tries to infer the type of the return from the types of the arguments.

Which is only possible because of the type variable.

The type that the type checker uses is what the type variable **resolved** to.

Suppose you call the function with two floats:

```py
from typing import reveal_type

result = add(1.5, 1.6)
reveal_type(result)
```

A type checker will reveal the type of `result` to be `float`.

We say that the type variable **resolved** to the type `float`.

The constraints you'll learn about have to do with this _resolution_ process.

## Type variables with upper bounds

A type variable with an upper bound is a type variable that can only resolve to the type given as the upper bound or to a subclass of that type.

A bound type variable is defined by including a colon `:` and then a single type in front of the colon.

This single type can be a concrete type (like `str`) or an abstract type (an abstract base class or a protocol).

In fact, two weeks ago you [learned how to bind type variables to protocols](https://mathspp.com/insider/archive/type-variables-and-protocols), which is one of the most useful practical use cases of a bound type variable.

As an example, look at the function `deduplicate` shown below and at the type variable `H`, which is bound to the protocol `Hashable`:

```py
from collections.abc import Hashable, Iterable

def deduplicate[H: Hashable](sequence: Iterable[H]) -> set[H]:
    return set(sequence)
```

Why is this useful?

From the function implementation you _know_ that whatever goes in is what comes out.

For example, if a list of _integers_ goes in, a set of _integers_ comes out.

So you want a type variable to specify this relationship.

But you also need to specify that what goes in must be hashable.

Otherwise you can't create a set of those elements...

The fix?

A type variable with a bound.

## Constrained type variables

A bound type variable is defined with a single extra type.

On the other hand, a constrained type variable is defined with two or more extra types.

A type variable is defined with constraints by writing the type variable, a colon `:`, and then a tuple of two or more constraints, which are types.

Something like this:

```py
def do_nothing[T: (str, int)](value: T) -> T
    return value
```

A type variable that is constrained can only ever resolve to one of the constraints given.

So, using subclasses is legal and the type checker won't complain.

But the type is **resolved** to be the super type.

Here's an example using a string subclass:

```py
from typing import reveal_type

class SubStr(str):
    def first(self) -> str:
        return self[0]

s1 = SubStr("heya!")
s2 = do_nothing(s1)
print(s2.first())
```

We know that, at runtime, `s2` and `s1` are the exact same object and that `print(s2.first())` will print the letter `h`.

But the type checker resolves the type variable `T`, in `do_nothing`, to be exactly `str`.

This means you get a type checker error when you try to print `s2.first()`.

Because objects of the type `str` don't have the method `first`.

This small example should help you understand one of the key differences between bound and constrained type variables.

When you write constraints, you know you _will_ get one of the constraints as the resolved type.

## Upper bounds can be unions

Type variables can get even more useful.

You just need to know that upper bounds _can_ be type unions.

This solves an apparent conundrum:

If you write a single type in front of the type variable, it's an upper bound.

If you write two or more, it's a constrained type variable.

But what if you want the _behaviour_ of an upper bound with two or more options?

Well, you can get that by using a type union.

## Bound type variables are more useful

If you're unsure what constrained type variables are useful for, so am I.

I [looked through the `typeshed` repository](https://github.com/python/typeshed) and found hundreds of type variables but only one constrained.

I immediately conclude that bound type variables are more useful, more commonly.

So, make sure you understand _what_ a constrained type variable does.

But don't worry if you can't see how you'd use one in the future.

## Comparison summary

Here's a quick summary comparison:

| | Bound | Constrained |
| Syntax | `[T: Hashable]` | `[T: (int, str)]` |
| Number of types listed | 1 | 2+ |
| Resolves to | Any subclass of the bound | Exactly one of the constraints |

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
