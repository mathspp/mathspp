---
title: "TIL #012 – At operator for matrix multiplication"
metadata:
    description: "Today I learned that Python 3.5+ supports the operator `@` for matrix multiplication."
---

Today I learned that Python 3.5+ supports the operator `@` for matrix multiplication.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![Snippet of Python code using `@` with `numpy`.](thumbnail.webp)


## At operator `@`

[Since Python 3.5][release-notes-35], Python has the infix operator `@`.
This operator was introduced with [PEP 465][pep465] to be used in matrix multiplication.

You can try to use it with just vanilla Python,
but no vanilla Python types define their behaviour with `@`:

```py
>>> 3 @ 5
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: unsupported operand type(s) for @: 'int' and 'int'
```

However, just looking at the error above, you see that the error is in `@`
not knowing what to do with integers.
The error _is not_ the fact that `@` is an invalid operator!
So cool!


## Matrix multiplication in `numpy` with `@`

If you have `numpy` at hand, you can check `@` works,
because `numpy` arrays added support to be used with `@`:

```py
>>> import numpy as np
>>> np.random.rand(3, 3) @ np.random.rand(3, 3)
array([[0.89431673, 0.57949659, 0.59470797],
       [0.47364302, 0.29837518, 0.33552972],
       [1.12634752, 0.75218169, 0.78876082]])
>>> _ @ np.eye(3)   # The identity (eye) matrix leaves the other matrix unchanged.
array([[0.89431673, 0.57949659, 0.59470797],
       [0.47364302, 0.29837518, 0.33552972],
       [1.12634752, 0.75218169, 0.78876082]])
```

! `_` is just a way to refer to the last result of the REPL.
! Read about it in [this Pydon't][pydont-underscore-repl].


## Using `@` with custom classes/types

If you want your own objects to add support for `@`,
all you have to do is implement the dunder methods `__matmul__` and `__rmatmul__`:

```py
>>> class Dummy:
...     def __matmul__(self, other):
...         print("Works!")
...         return 42
...     def __rmatmul__(self, other):
...         print("Also works!")
...         return 73
...
>>> d = Dummy()
>>> d @ 1
Works!
42
>>> 1 @ d
Also works!
73
```

There's also the `__imatmul__` method for in-place matrix multiplication:

```py
>>> class Dummy:
...     def __imatmul__(self, other):
...         print("In-place!")
...
>>> d = Dummy()
>>> d @= 1
In-place!
```

Of course, this silly example above doesn't show you the proper semantics of the `__matmul__`,
`__rmatmul__`, and `__imatmul__` methods.
It just shows you they exist and they interact with the operator `@`!

By the way, for reference, here is the tweet that showed me this:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">How did I not know @ was a proper operator?!</p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1447905365316734982?ref_src=twsrc%5Etfw">October 12, 2021</a></blockquote>


That's it for now! [Stay tuned][subscribe] and I'll see you around!


[subscribe]: /subscribe
[release-notes-35]: https://docs.python.org/3/whatsnew/3.5.html
[pep465]: https://www.python.org/dev/peps/pep-0465/
[pydont-underscore-repl]: https://mathspp.com/blog/pydonts/usages-of-underscore#recovering-last-result-in-the-session
