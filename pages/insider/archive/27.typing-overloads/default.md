---
date: 22-06-2026 19:41
metadata:
    author: Rodrigo Girão Serrão
    description: "A typing feature you can use to encode type relationships in your functions."
    og:image: "https://mathspp.com/insider/archive/typing-overloads/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/typing-overloads/thumbnail.webp"
title: "Typing overloads"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Typing overloads

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Getting lines from a document

Take a look at a function defined in the codebase for Textual, a project I used to work on:

```py
class Document(...):
    def __getitem__(self, line_index: int | slice) -> str | list[str]:
        ...
```

Imagine that the class `Document` represents a text document.

The dunder method `__getitem__` allows you to index into the document and retrieve the corresponding line.

For example, `document[0]` gives you the first line.

But the signature of `__getitem__` tells you that you can also use slices.

For example, `document[:5]` [gives you the first five elements](https://mathspp.com/blog/pydonts/idiomatic-sequence-slicing).

And in that case, you get a _list_ of lines.

You'll know that if you look at the code.

But that's not what the typing says.

## Unions hide information

The two unions, one in `line_index` and the other in the result of the method, are independent.

As such, they _hide_ the information that you _know_.

If you write `reveal_type(document[0])`, a type checker will say `str | list[str]`.

But _you know_ it's supposed to be `str`...

So, how can you fix this?

## Typing overloads

You can use typing overloads to establish relationships between types in function signatures.

You use `typing.overload` as a decorator around each possible combination you know about and in the end you provide the final implementation _without_ the decorator.

For the `__getitem__` example, the code would look like this:

```py
from typing import overload

class Document:
    @overload
    def __getitem__(self, line_index: int) -> str: ...

    @overload
    def __getitem__(self, line_index: slice) -> list[str]: ...

    def __getitem__(self, line_index: int | slice) -> str | list[str]:
        ...  # Your function implementation goes here.
```

The overloads can't have any code.

It's common to use `...` in them.

It's the final version that puts all the overloads together into the generic signature _and_ provides the implementation.

You can test it now:

```py
from typing import overload

class Document:
    ...

d = Document()
reveal_type(d[0])  # Revealed type is "str"
reveal_type(d[:5])  # Revealed type is "list[str]"
```

## Typing overloads work well with literals

Typing overloads are also commonly used with literals.

And specifically, Boolean values.

Suppose you have a function that accepts a Boolean flag.

The Boolean flag determines whether you also return debugging information along the expected integer result.

Without an overload, your function looks like this:

```py
def my_function(arg: int, return_debug: bool = False) -> int | tuple[int, str]:
    ...
```

Whenever you use your function, the type checker will never know if the result was an integer or a tuple.

For example, a type checker complains about this code:

```py
result, debug = my_function("some argument", True)
```

Why?

Because _if_ the result was just an integer, you can't unpack it like that.

But _you know_ it's supposed to be a tuple that you can unpack...

You can use `typing.Literal` to fix this!

Here's the solution:

```py
from typing import overload, Literal

@overload
def my_function(arg: int, return_debug: Literal[False] = False) -> int: ...

@overload
def my_function(arg: int, return_debug: Literal[True] = True) -> tuple[int, str]: ...

def my_function(arg: int, return_debug: bool = False) -> int | tuple[int, str]:
    ...  # Your function implementation goes here.

reveal_type(my_function(3, True))  # Revealed type is tuple[int, str]
```

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
