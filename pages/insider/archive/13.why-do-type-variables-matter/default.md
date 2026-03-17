---
date: 16-03-2026 18:36
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn what type variabbles are and what problem they solve."
    og:image: "https://mathspp.com/insider/archive/why-do-type-variables-matter/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/why-do-type-variables-matter/thumbnail.webp"
title: "Why do type variables matter?"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Why do type variables matter?

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## A function that does nothing

Try to think about how you would add type hints to the function `do_nothing`, defined below:

```py
def do_nothing(x):
    return x
```

The function `do_nothing` accepts an argument `x` and returns it unchanged.

Since the function does _nothing_ with its argument, it work with values of any type whatsoever.

So you might feel like the correct way to add type hints to `do_nothing` is by using `typing.Any`:

```py
from typing import Any

def do_nothing(x: Any) -> Any:
    return x
```

## `Any` isn't the solution

But there's a problem with using `Any`.

Once you call the function, you **lose** the information about the type of the argument.

Suppose you call the function with a tuple as an argument:

```py
from typing import Any, reveal_type

def do_nothing(x: Any) -> Any:
    return x

t: tuple[int, str] = (42, "Hey!")
reveal_type(t)  # tuple[builtins.int, builtins.str]
reveal_type(do_nothing(t))  # Any
```

You're using `reveal_type` to compare what the static type checker knows _before_ and _after_ calling the function.

Your static type checker knows that the value passed in was a tuple with an integer and a string...

But it doesn't know anything about the returned value, as you can see from the `Any`:

```text
$ mypy do_nothing_tuple.py
do_nothing_tuple.py:7: note: Revealed type is "tuple[builtins.int, builtins.str]"
do_nothing_tuple.py:8: note: Revealed type is "Any"
Success: no issues found in 1 source file
```

This shows that using `Any` leads to the loss of information.

That's something you _don't_ want.

The idea of using type hints is to provide _more_ useful information that makes your code clearer.

## Preserving information without specifics

**Type variables** come in handy in these types of situations.

They allow you to establish relationships between the types of arguments and the return value of a function.

In a function signature, a type variable is introduced in square brackets, right after the function name.

Then, it can be used as a legal type in type hints in the function signature.

Using `T` as a generic name for a type variable, the function `do_nothing` can be typed with a type variable:

```py
def do_nothing[T](x: T) -> T:
    return x
```

The new function signature says that `x` can be of whatever type you want.

But then, the return type of the function will _match_ the type of `x`.

And the type variable `T` is what is used to establish this correspondence.

You can try adding the calls to `reveal_type` again:

```py
from typing import reveal_type

def do_nothing[T](x: T) -> T:
    return x

tup: tuple[int, str] = (42, "Hey!")
reveal_type(tup)  # tuple[builtins.int, builtins.str]
reveal_type(do_nothing(tup))  # tuple[builtins.int, builtins.str]
```

This snippet calls `do_nothing` again with a tuple and it will let you verify that the type variable is doing its job.

You can check that mypy produces more helpful output this time:

```text
$ mypy do_nothing_t.py
do_nothing_t.py:7: note: Revealed type is "tuple[builtins.int, builtins.str]"
do_nothing_t.py:8: note: Revealed type is "tuple[builtins.int, builtins.str]"
Success: no issues found in 1 source file
```

When your static type checker is asked about the type of `do_nothing(tup)`, the type checker looks at the return type of `do_nothing`.

But that's `T`, which is a type variable because the signature looks like `do_nothing[T](...)`.

To infer the actual type that `T` represents, the type checker has to look for other places where `T` is used and it finds one more in the parameter `x: T`.

Since the argument passed in is `tup`, which is of type `tuple[int, str]`, your type checker infers that the return type of `do_nothing(tup)` is _also_ `tuple[int, str]`.

## Type variables are variables

The brilliance of type variables is that they are _variable_.

Depending on how the function `do_nothing` is called, the type variable `T` can mean completely different things:


```py
from typing import reveal_type

def do_nothing[T](x: T) -> T:
    return x

reveal_type(do_nothing("Hello."))
reveal_type(do_nothing(False))
reveal_type(do_nothing([1, 2, 3]))
```

The code above calls the function with a string...

Then with a Boolean value...

Then with a list of strings.

In each of the three different calls the type checker should be able to infer the return type correctly:

```text
$ mypy do_nothing_multiple.py
do_nothing_multiple.py:6: note: Revealed type is "builtins.str"
do_nothing_multiple.py:7: note: Revealed type is "builtins.bool"
do_nothing_multiple.py:8: note: Revealed type is "builtins.list[builtins.int]"
Success: no issues found in 1 source file
```

First, `T` is matched to the type `str`.

Then, it's matched to `bool`.

Finally, it's matched to `list[int]`.

Now you understand what type variables are for.

Next week, you'll learn more about what you can do with them.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
