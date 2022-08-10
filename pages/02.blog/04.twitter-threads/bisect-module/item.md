---
date: 10-08-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This short article shows how to use the module `bisect` from the Python standard library."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
references:
  - "Python 3 docs, The Python Standard Library, `bisect`, [https://docs.python.org/3/library/bisect.html](https://docs.python.org/3/library/bisect.html) [last accessed 10-08-2022];"
slug: bisect-module
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "bisect module"
---

This short article shows how to use the module `bisect` from the Python standard library.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


Are you familiar with the module `bisect` from the Python 🐍 standard library?

It is a small module to help you work with sorted lists.
If you have a sorted list and a new value, you can find the index where the new value would go by using the method `bisect`:

```py
>>> value = 22
>>> ordered_values = [1, 5, 19, 23]
# `value` would go at index 3 -^

>>> import bisect
>>> bisect.bisect(ordered_values, value)
3  # the index where `value` would go

>>> ordered_values.insert(3, value)
>>> ordered_values
[1, 5, 19, 22, 23]
```

The function `bisect` tells you where a value _would_ go...
But if you want to insert it, `bisect` can also do that for you!
Just use the method `insort`:

```py
>>> ordered_values
[1, 5, 19, 22, 23]

# Let's add 21 in there, preserving the order:
>>> bisect.insort(ordered_values, 21)
>>> ordered_values
[1, 5, 19, 21, 22, 23]
```

The module `bisect` has four more methods: `bisect_left`, `bisect_right`, `insort_left`, and `insort_right`...

The `_left` and `_right` where to place ties (elements that are equal).

The methods `bisect` and `insort` match their `_right` variants.


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1557367701252734976) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
