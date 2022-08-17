---
date: 17-08-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This short article shows how to chunk iterables into pieces of a fixed length."
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

This short article shows how to chunk iterables into pieces of a fixed length.

===

If you need to chunk a list or another iterable into groups of `n` items,
you can do that with the built-in `zip`:

```py
>>> def chunk(iterable, chunk_size):
...     return list(zip(*[iter(iterable)] * chunk_size))
...
>>> chunk(range(10), 5)
[(0, 1, 2, 3, 4), (5, 6, 7, 8, 9)]
```

You can also add a third argument to process each chunk after it is created:

```py
>>> chunk("Hello, world", 3)
[('H', 'e', 'l'), ('l', 'o', ','), (' ', 'w', 'o'), ('r', 'l', 'd')]

# Redefine `chunk`:
>>> def chunk(iterable, chunk_size, on_chunk=None):
...     on_chunk = on_chunk or (lambda x: x)
...     return [on_chunk(chunk) for chunk in zip(*[iter(iterable)] * chunk_size)]
...
>>> chunk("Hello, world", 3, "".join)
['Hel', 'lo,', ' wo', 'rld']
```


The code above has a limitation, though:
it ignores the last elements if the chunk size doesn't divide evenly into the length of the iterable.
Sometimes this is ok... Sometimes, it is not.

If you use `itertools.zip_longest`, you get the opposite behaviour:

```py
>>> chunk(range(8), 5)
[(0, 1, 2, 3, 4)]  # where are 5, 6, and 7??

>>> from itertools import zip_longest
>>> def chunk_longest(iterable, chunk_size):
...     return list(zip_longest(*[iter(iterable)] * chunk_size))
...

>>> chunk_longest(range(8), 5)
[(0, 1, 2, 3, 4), (5, 6, 7, None, None)]
```

By using `zip_longest`, you get padding with the value `None`.
You could also customise this padding.

Finally, if you want your chunks to always have the same size, you can use `strict=True` in `zip`!
This will make your `chunk` function error if the chunk size doesn't divide evenly!
However, this only works in Python 3.10+ because that is when `zip(..., strict=True)` was added.

```py
>>> def chunk_strict(iterable, chunk_size):
...     return list(zip(*[iter(iterable)] * chunk_size, strict=True))
...

# 2 divides into 6
>>> chunk_strict(range(6), 2)
[(0, 1), (2, 3), (4, 5)]

# 5 does NOT divide into 8
>>> chunk_strict(range(8), 5)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "<stdin>", line 2, in chunk_strict
ValueError: zip() argument 4 is shorter than arguments 1-3
```


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1559837003973287936) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
