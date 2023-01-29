How do you implement a case-insensitive (or caseless) dictionary? In this article we explore solutions where we inherit from the built-in `dict`, the abstract base class `MutableMapping` from the standard module `collections.abc`, and the `UserDict` in the standard module `collections`.

===

# Introduction

In a recent article, I showed why you should use the string method `casefold` when working with case-insensitive strings.
In that article, I also presented [a basic implementation of a case-insensitive dictionary][casefold-article-dict].
However, that implementation (reproduced below) had a couple of issues.

```py
class CaseInsensitiveDict(dict):
    """Case-insensitive dictionary implementation."""

    def __getitem__(self, key):
        return dict.__getitem__(self, key.casefold())

    def __setitem__(self, key, value):
        return dict.__setitem__(self, key.casefold(), value)

    def __delitem__(self, key):
        return dict.__delitem__(self, key.casefold())
```

Our class `CaseInsensitiveDict` implements the three [dunder methods][dunder-methods] `__getitem__`, `__setitem__`, and `__delitem__`, and that lets us mimic the main behaviour of `dict`.
However, this implementation is incomplete.

We can create an instance of a case-insensitive dictionary and expose the issues with the implementation above:

```py
>>> d = CaseInsensitiveDict()
>>> d["mathspp"] = "https://mathspp.com/"
>>> d.get("MATHSPP", "not found")
'not found'
```

The issues that we will find all revolve around what the example above shows.
Although we implemented the three main dunder methods that let you add / access / delete keys from a dictionary, the other dictionary methods will not use the dunder methods to perform their operations.


# Case-insensitive dictionary inheriting from `dict`

If we wanted to implement a case-insensitive dictionary that inherits from `dict` directly, we would have to provide implementations of most of the `dict` methods in terms of the three dunder methods that we already have.

If you are up for the challenge, open your Python REPL and inspect the result of `dir(dict)`.
That list will contain all the (dunder) methods that `dict` knows about.
Go over that list and implement all the methods that need to be written in terms of our case-insensitive operations.

Such an implementation is included below for reference:

```py
_no_default = object()


class CaseInsensitiveDict(dict):
    """Case-insensitive dictionary implementation."""

    def __getitem__(self, key):
        return dict.__getitem__(self, key.casefold())

    def __setitem__(self, key, value):
        return dict.__setitem__(self, key.casefold(), value)

    def __delitem__(self, key):
        return dict.__delitem__(self, key.casefold())

    # ---

    def __contains__(self, key):
        return dict.__contains__(self, key.casefold())

    def __init__(self, seed=None, **kwargs):
        super().__init__()
        # Defer work to the method .update.
        self.update(seed)
        self.update(kwargs)

    def __or__(self, other):
        """Dictionary updating with the pipe operator |."""
        base = self.copy()
        base.update(other)
        return base

    def __ror__(self, other):
        """Dictionary updating with | and a regular dict on the left."""
        base = CaseInsensitiveDict(other)
        base.update(other)
        return base

    def copy(self):
        return CaseInsensitiveDict(dict.copy(self))

    def get(self, key, default=None):
        return dict.get(self, key.casefold(), default)

    def pop(self, key, default=_no_default):
        if default is _no_default:
            return dict.pop(self, key.casefold())
        else:
            return dict.pop(self, key.casefold(), default)

    def setdefault(self, key, default=None):
        return dict.setdefault(self, key.casefold(), default)

    def update(self, seed=None, **kwargs):
        if seed is None:
            seed = {}

        # Is the seed a mapping...
        if hasattr(seed, "items"):
            for key, value in seed.items():
                self[key] = value
        # or an iterable?
        else:
            for key, value in seed:
                self[key] = value

        for key, value in kwargs.items():
            self[key] = value
```

---

Stay tuned for the remainder of the article!



[dunder-methods]: /blog/pydonts/dunder-methods
[casefold-article-dict]: /blog/how-to-work-with-case-insensitive-strings#how-to-implement-a-case-insensitive-dictionary-in-python
