---
author: Redowan Delowar
date: 18-08-2025 13:33
link: https://rednafi.com/python/typing_override/
taxonomy:
    category: link
title: "Explicit method overriding with @typing.override"
# via:
---

This article is where I first learned about `typing.overrides`, a decorator you can use to flag all method overrides when you're subclassing.

A method that is tagged with `typing.overrides` is assumed to be overriding a method from a super class and if the type checker doesn't find the original method you're overriding, it'll let you know there's an issue with your code (maybe a typo? maybe the method changed name?).

As Redowan shows, `overrides` also works with properties, class methods, and more.
Here's an example where mypy will complain because you forgot to specify that `Cat.species` is a property:

```py
from typing import override

class Animal:
    @property
    def species(self) -> str:
        return "Unknown"

class Cat(Animal):
    @override
    def species(self) -> str:
        return "Catus"
```
