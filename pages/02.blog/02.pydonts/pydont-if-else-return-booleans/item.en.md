---
title: Py-don't use an if-else to return two booleans
slug: if-else-return-booleans
date: 14-03-2020
published: false
taxonomy:
    tag: [pydont, python, programming]
---

Don't use an `if-else` statement to decide whether to return `True` or `False`, i.e. don't do something like

```py
def my_function():
    # some code here...
    if some_condition:
        return True
    else:
        return False
```

You can, and probably should, return the value of `some_condition` instead!

===

The more Pythonic code would be

```py
def my_function():
    # some code here...
    return some_condition
```

This is easier to explain with an example. Let us say you want to write some code that checks if a string starts with a lowercase letter. You could be tempted to do the following:

```py
def word_starts_lowercase_letter(string):
    letters = "abcdefghijklmnopqrstuvwxyz"
    if string[0] in letters:
        return True
    else:
        return False
```

but notice that it is much shorter, and much cleaner, to write

```py
def word_starts_lowercase_letter(string):
    letters = "abcdefghijklmnopqrstuvwxyz"
    return string[0] in letters
```

Why would we want to use the shorter version instead of the first? This is easy to explain and has to do with one thing I like about Python: good Python code can be read like a sentence!

`string[0]` can be read as _the first character of `string`_, so the first version of the code reads:

!!! If the first character of `string` is in the `letters`, return `True`, otherwise return `False`.

But the **second version** of the code can be read as:

!!! Return whether the first character of `string` is in the `letters`.

Notice how both readings are very, _very_ close to what is written in the code. Having said that, which code version/written explanation is preferable?

I say [_flat is better than nested_][zen-of-python], and there really is no point in creating an `if-else` statement to return the actual boolean value of the condition, so just return the conditional expression!

Below I included a snippet with a couple of examples with this [py-don't][pydont]. Try rewriting the functions to remove the unnecessary `if-else` statements.

```py
def is_multiple(x, n):
    """Returns True if x is a multiple of n."""
    if x % n == 0:
        return True
    else:
        return False

def is_larger(l, n):
    """Returns True if the list l has more than n elements."""
    if len(l) > n:
        return True
    else:
        return False

def is_lowercase(s):
    """Returns True if the string s is in all lowercase letters."""
    lower = s.lower()
    if lower == s:
        return True
    else:
        return False

def is_missing(needle, haystack):
    """Returns True if the needle is not in the haystack."""
    if needle in haystack:
        return False
    else:
        return True
```

Did this make any sense? Let me know in the comment section below!

[zen-of-python]: ../zen-of-python
[pydont]: ../.