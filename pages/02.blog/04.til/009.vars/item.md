---
title: "TIL #009 – vars"
metadata:
    description: "Today I learned about the built-in function `vars` in Python."
---

Today I learned about the built-in function `vars` in Python.

===

![Example usage of `vars` in the Python REPL.](thumbnail.webp)


## `vars`

Python has a bunch of built-in functions.
Like, a lot!
It's very difficult to keep track of all of them, remember them,
and use them correctly.

A built-in function I just learned about is `vars`.
I heard about it from Reuven Lerner (a Python trainer) in [his newsletter “Better Developers”][better-devs-ref].
(Disclaimer: that's a referral link, but I _am_ a subscriber and avid reader of the newsletter,
so it is a very honest recommendation!)

Looking at the [Python documentation][py-docs-vars], we can see what `vars` does:

 > “Return the `__dict__` attribute for a module, class, instance, or any other object with a `__dict__` attribute.”

The documentation also goes on to say that “without an argument, `vars()` acts like `locals()`.”.
So that's not useful because we can always use `locals()`.

When `vars` really shines is when you give it an argument, like a module or a class instance!

```py
>>> class Person:
...     def __init__(self, name):
...         self.name = name
...
>>> p = Person("me")
>>> vars(p)
{'name': 'me'}
```

So, we can see that `vars` is a very handy way of inspecting an instance of a class you defined.
Quite cool, right?

For things like built-in classes, or modules, `vars` is similar to `dir`.
Recall that `dir` lists the names of all the attributes of an object,
but `vars` will give you a mapping with the names of the attributes and the corresponding values:

```py
>>> import math
>>> dir(math)
['__doc__', '__loader__', '__name__', '__package__', '__spec__', 'acos', 'acosh', 'asin', 'asinh', 'atan', 'atan2', 'atanh', 'ceil', 'comb', 'copysign', 'cos', 'cosh', 'degrees', 'dist', 'e', 'erf', 'erfc', 'exp', 'expm1', 'fabs', 'factorial', 'floor', 'fmod', 'frexp', 'fsum', 'gamma', 'gcd', 'hypot', 'inf', 'isclose', 'isfinite', 'isinf', 'isnan', 'isqrt', 'lcm', 'ldexp', 'lgamma', 'log', 'log10', 'log1p', 'log2', 'modf', 'nan', 'nextafter', 'perm', 'pi', 'pow', 'prod', 'radians', 'remainder', 'sin', 'sinh', 'sqrt', 'tan', 'tanh', 'tau', 'trunc', 'ulp']
>>> vars(math)
{'__name__': 'math', '__doc__': 'This module provides access to the mathematical functions\ndefined by the C standard.', ...}
```

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[better-devs-ref]: https://sparklp.co/4958f81b
[py-docs-vars]: https://docs.python.org/3/library/functions.html#vars
