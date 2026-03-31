---
date: 30-03-2026 17:58
metadata:
    author: Rodrigo Girão Serrão
    description: "Duck typing is a key concept  in the Python programming language and protocols are an important modern part of that equation."
    og:image: "https://mathspp.com/insider/archive/duck-typing-and-protocols/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/duck-typing-and-protocols/thumbnail.webp"
title: "Duck typing and protocols"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Duck typing and protocols

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## If it walks like a duck...

... and if it quacks like a duck...

Then it must be a duck!

This is a very common saying in the Python community and it's supposed to summarise the concept of **duck typing**.

**Duck typing** tells you one simple thing: in Python, the type of an object is not what's important.

What's important is the set of behaviours that your object exhibits.

Let's see what this means and what protocols have to do with this.

## Most common letter

Consider the function `most_common_letter`, shown below, that computes the most common letter of a string with the help of `collections.Counter`:

```py
from collections import Counter

def most_common_letter(string: str) -> str:
    [(letter, _)] = Counter(string).most_common(1)
    return letter

most_common_letter("banana")  # 'a'
```

The function includes type hints saying that the argument must be a string and the return value is also a string.

So, what will happen if I call the function with a list of strings?

Or with a list of integers?

## Most common elements

Maybe you thought the function will raise an exception because lists of strings or lists of integers aren't strings, and the parameter `string` is typed as `string: str`...

But that's not what happens.

Remember that type hints are irrelevant at _runtime_.

Type hints only matter when you run a static type checker, like mypy or ty.

You can call the function with whatever you want...

And lists of integers or strings also work:

```py
most_common_letter(["yes", "no", "yes", "yes"])  # 'yes'
most_common_letter([1, 2, 3, 1, 1, 1])  # 1
```

Why does this work..?

## Iterable arguments

There are two things at play here, that make the function work with lists of strings, lists of integers, and other objects besides strings.

The first thing that matters is that the argument must be **iterable**.

You must be able to go through the argument to count its values.

That's what `Counter` expects.

You can go through a string, to count the characters.

You can also go through a list, to count its elements.

That's why the argument can be a string or a list.

And it could also be a range, or a tuple, or a dictionary, or _any other **iterable**_.

But that's not all.

## Hashable elements

The argument must be iterable and its elements must be _hashable_.

The class `Counter` only counts elements that can be used as dictionary keys.

It can count strings, integers, and anything else that's hashable.

That's why the function `most_common_letter` would also work with lists of tuples of Booleans:

Because tuples of Booleans are hashable.

So, how do you fix the type hints in the function `most_common_letter` to reflect this flexibility?

## Use protocols to restrict behaviours

You want to fix the type hints of your functions.

You don't want to enumerate all the types that could work.

You just want to say anything works, as long as it's _an iterable_.

That's what a protocol is for.

Similarly, you can use a protocol to say that the elements of the iterable must be _hashable_.

Here's the fixed version:

```py
from collections import Counter
from collections.abc import Iterable, Hashable

def most_common_letter(string: Iterable[Hashable]) -> Hashable:
    [(letter, _)] = Counter(string).most_common(1)
    return letter
```

Now, the type checker accepts strings, lists of integers, lists of strings, and any other iterable of hashable elements.

But now, the question is...

## How do protocols know what's valid?

How can the static type checker know what can be used as an iterable?

How can the static type checker know what's hashable?

The answer lies in the way protocols work.

A protocol looks like the skeleton of a class.

The protocol for `Hashable` could look a bit like this:

```py
from typing import Protocol

class Hashable(Protocol):
    def __hash__(self) -> int: ...
```

The code snippet above defines a protocol, called `Hashable`, that includes the signature of a single method `__hash__`.

The way you interpret this definition is:

“Any object that has a method `__hash__` that takes no arguments and returns an integer satisfies the protocol `Hashable`”.

But this might look a bit magic.

After all, `__hash__` is a dunder method...

So let's see how to create a completely custom protocol.

## The flyer protocol

You're working on code that deals with animals, and you write this protocol:

```py
from typing import Protocol

class Flyer(Protocol):
    def fly_to(self, destination: str) -> None: ...
```

The protocol says that any object that has the method `fly_to`, that accepts a string, is a flyer.

For example, the class `Pigeon` is a flyer:

```py
class Pigeon:
    def fly_to(self, destination: str) -> None:
        print(f"This pigeon is flying to {destination}.")
```

Note that the class `Pigeon` doesn't mention the protocol `Flyer` explicitly.

It doesn't inherit from it.

It doesn't do anything with it.

It just implements the skeleton that the protocol `Flyer` laid out.

Then you write the function `travel`:

```py
def travel(bird: Flyer) -> None:
    bird.fly_to("Hawaii")
    bird.fly_to("Rome")
    bird.fly_to("Tokyo")
```

Since the function `travel` only asks for flyers, you can pass it a pigeon and the static type checker will be happy:

```py
travel(Pigeon())  # Static type checker is happy
```

Later on, you can define another class with the method `fly_to`, and it'll also work.

For example, the class `Airplane` below can also travel:

```py
class Airplane:
    def fly_to(self, destination: str) -> None:
        print("Taking off...")
        print(f"Cruising to {destination}.")
        print(f"Now landing at {destination}.")

travel(Airplane())  # Static type checker is ALSO happy.
```

## Duck protocol

This shows that protocols are the formalisation of duck typing.

A duck protocol would look like this:

```py
from typing import Protocol

class Duck(Protocol):
    def walk(self) -> None: ...
    def quack(self) -> None: ...
```

Any object that implements the methods `walk` and `quack` will be considered a `Duck`!

## Protocols and dunder methods

The protocols from `collections.abc` are just common and useful protocols that are related to Python's dunder methods.

The protocol `Hashable` was one of them.

Any class you create that implements the dunder method `__hash__` will automatically satisfy the protocol `Hashable`.

If you implement the dunder method `__iter__`, you'll automatically satisfy the protocol `Iterable`.

And then you can use those objects in the function `most_common_letter` from above.

## Did this make sense?

I'm trying to find the best way to explain duck typing, protocols, and how all of these things relate to each other.

Did this make sense?

Or not really?

Reply to let me know your questions and your thoughts.

This is important for “The little book of typing”, the book I'm currently writing. :)

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
