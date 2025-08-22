---
author: Hillel Wayne
date: 22-08-2025 22:16
link: https://www.hillelwayne.com/post/python-abc/
taxonomy:
    category: link
title: "Crimes with Python's Pattern Matching"
# via:
---

In this article, Hillel abuses using `__subclasshook__` in abstract base classes when used in conjunction with [structural pattern matching](/blog/pydonts/structural-pattern-matching-tutorial).
As an example, define

```py
from abc import ABC

class NotIterable(ABC):
    @classmethod
    def __subclasshook__(cls, C):
        return not hasattr(C, "__iter__")
```

Now, you can use `NotIterable()` in a `match ...: case ...:` to match non-iterable values:

```py
def wtp(value):
    match value:
        case NotIterable():
            print("Not an iterable.")
        case _:
            print("Iterable.")

wtp(3)  # Not an iterable.
wtp([])  # Iterable.
```

Another thing I learned from this article is the English noun _chicanery_, “the use of deception or subterfuge to achieve one's purpose”.
