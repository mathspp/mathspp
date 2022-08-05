---
date: 05-08-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "In this short article I describe how to simplify equality comparisons chained with the operator `or`."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: simplifying-equality-comparisons-with-or
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Simplifying equality comparisons with or"
---

In this short article I describe how to simplify equality comparisons chained with the operator `or`.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


Do you want to check if a Python 🐍 variable matches one of several possible values?
Instead of writing a big chain of `or` and equalities `==`, use the `in` operator!

```py
>>> x = 43
>>> if x == 42 or x == 43 or x == 44:
...     print("Nice!")
...
Nice!
>>> if x in (42, 43, 44):
...     print("Nice!")
...
Nice!
```


Using `in` is a great tip but it isn't always a suitable alternative!
The operator `or` short-circuits, which means it stops comparing as soon as it finds a `True`.
This isn't the case if you use `in`:

```py
>>> def f():
...     return 42
...
>>> def g():
...     return 43
...
>>> def h():
...     return 1 / 0
...
>>> if x == f() or x == g() or x == h():
...     print("Nice!")
...
Nice!
>>> if x in (f(), g(), h()):
...     print("Nice!")
...
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "<stdin>", line 2, in h
ZeroDivisionError: division by zero
```

You can read more about short-circuiting in [this Pydon't article](/blog/pydonts/boolean-short-circuiting).


For a bonus tip, see @guilatrova's tweet on a similar tip:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">I stumbled on an alternative way of comparing many values in Python. 🐍<br><br>• Define tuples for a shorter comparison.<br>• Remove the &quot;and&quot; operator<br><br>Which way do you prefer? Why? <a href="https://t.co/VjZFL8tsQQ">pic.twitter.com/VjZFL8tsQQ</a></p>&mdash; Gui Latrova (@guilatrova) <a href="https://twitter.com/guilatrova/status/1555508935200686080?ref_src=twsrc%5Etfw">August 5, 2022</a></blockquote>


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1555527830603239430) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
