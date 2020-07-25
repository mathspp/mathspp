---
title: Py-don't chain different comparison operators
---

Don't chain comparisons in Python if the chained operators are not equal, i.e. avoid things like:

```py
def first_argument_is_larger(a, b, c):
    return b < a > c
```

When the comparisons aren't the same you should split them up and use a logical `and`.

===

The more pythonic code would be

```py
def first_arg_is_larger(a, b, c):
    """Returns True if a is larger than b and c."""
    return a > b and a > c  # or b < a and c < a
```

You might be tempted to write the first version of the code because Python has a really nice functionality: you can chain consecutive comparisons, which allows you to write things like

```py
a < b < c   # instead of a < b and b < c
a == b == c # instead of a == b and b == c
a <= b < c  # instead of a <= b and b < c
```

but you should never forget that [_readability counts_][zen-of-python]! When the comparisons you want to chain aren't the same, your code stops being more readable and elegant and becomes slightly more confusing and less obvious. To prove my point, consider the expression `a != b != c`; for the less attentive eye, this appears to be checking if the three variables `a`, `b` and `c` are all different from each other. However, if we have $a = c$ and $a \neq b$ the expression `a != b != c` evaluates to `True` and not all three variables are different from each other.

You might argue that you are sacrificing performance, because the chaining means less operations to be made... To see if this is true or not, fire up your favourite terminal and run the following commands, comparing the results:

```bash
python -m timeit "0 < 1 < 2"
python -m timeit "0 < 1 and 1 < 2"
```

This uses Python's [`timeit`][timeit] module to find how much time it takes to run each snippet of code. On my machine, the unchained version was about $\approx 10\%$ faster. Under the hood, it might even be the case that the chained comparisons are being converted to the unchained comparisons and then evaluated.

In conclusion, if you can gain a bit of readability while **also** writing more efficient code, why wouldn't you do it?

Did this make any sense? Let me know in the comment section below!

[zen-of-python]: ../zen-of-python
[pydont]: ../.
[timeit]: https://docs.python.org/3/library/timeit.html#module-timeit