---
date: 25-08-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This short article teaches you 3 ways of reversing a Python list."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: 3-ways-to-reverse-a-python-list
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "3 ways to reverse a Python list"
---

This short article teaches you 3 ways of reversing a Python list.

===

Here are 3 simple ways in which you can reverse a Python 🐍 list:

```py
>>> lst = [42, 73, 0]
>>> rev1 = reversed(lst)
>>> rev2 = lst[::-1]
>>> lst.reverse()
```

Let's see how they are different.


## Built-in `reversed`

The built-in `reversed` accepts a sequence and returns an object that knows how to iterate over that sequence in reverse order.
Hence, `reversed`.
Notice it doesn't return a list:

```py
>>> reversed(lst)
<list_reverseiterator object at 0x00000241D77BD520>
```


The `list_reverseiterator` object that is returned is “linked” to the original list...
So, if you change the original list, the reverse iterator will notice:

```py
>>> lst = [42, 73, 0]
>>> rev = reversed(lst)
>>> lst[1] = 999999  # Change something in the original list.

>>> for n in rev:  # Go over the reverse iterator.
...     print(n)
...
0
999999  # This was changed as well.
42
```


## Slicing with `[::-1]`

The slicing syntax with brackets `[]` and colons `:` accepts a “step” that can be negative.
If the “start” and “stop” are omitted and the “step” is -1, we get a copy in the reverse order:

```py
>>> lst = [42, 73, 0]
>>> lst[::-1]
[0, 73, 42]
```

Slices in Python 🐍 are regular objects, so you can also name them.
Thus, you could go as far as creating a named slice to reverse lists, and then use it:

```py
>>> lst
[42, 73, 0]

>>> reverse = slice(None, None, -1)
>>> lst[reverse]
[0, 73, 42]
```

Notice that slices are not “linked” to the original list.
That's because slicing creates a copy of the list.
So, if you change the elements in a given index, the reversed list will not notice:

```py
>>> lst = [42, 73, 0]  # Original list.
>>> rev = lst[::-1]    # Reverse copy.
>>> lst[1] = 999999    # Change something in the original.
>>> rev                # The copy didn't notice.
[0, 73, 42]
```

Slicing is very powerful and useful, and that is why I wrote a whole chapter of my [free book “Pydon'ts”][pydonts] on the subject.


## List method `.reverse`

Lists have a method `.reverse` that reverses the list **in place**.
What this means is that you do not get a return value with the reversed list...
Instead, the list itself gets flipped around 🙃

```py
>>> lst = [42, 73, 0]
>>> lst.reverse()
>>> lst
[0, 73, 42]
```


## Summary

Here is a quick summary:

Reverse a Python list with:

 1. the built-in `reversed` that will notice changes to the original list;
 2. slicing `[::-1]` that creates a copy of the original list; and
 3. the method `.reverse` that reverses a list in place.


[pydonts]: https://mathspp.gumroad.com/l/pydonts


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1562714998757277696) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
