---
date: 26-08-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This short article teaches you 3 ways of creating a Python dictionary."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: 3-different-ways-to-create-a-dictionary
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "3 different ways to create a dictionary"
---

This short article teaches you 3 ways of creating a Python dictionary.

===

Here are 3 ways in which you can create a Python 🐍 dictionary:
(Of the three, the last one is the least commonly used.)

```py
>>> dict([(1, "one"), (2, "two")])
{1: 'one', 2: 'two'}

>>> dict(name="Rodrigo", twitter="mathsppblog")
{'name': 'Rodrigo', 'twitter': 'mathsppblog'}

>>> dict.fromkeys(["likes", "retweets"], 0)
{'likes': 0, 'retweets': 0}
```


## An iterable of key, value pairs

The built-in `dict` can take an iterable with key, value pairs.
Useful, for example, when you have a bunch of keys and a bunch of values that you put together with `zip`:

```py
>>> dict([(1, "one"), (2, "two")])
{1: 'one', 2: 'two'}

>>> keys = range(1, 4)
>>> values = "one two three".split()
>>> dict(zip(keys, values))
{1: 'one', 2: 'two', 3: 'three'}
```

In this particular case, you could also get creating with `enumerate`
and [make use of the keyword argument `start`](/blog/pydonts/enumerate-me#optional-start-argument):

```py
>>> dict(enumerate(values, start=1))
{1: 'one', 2: 'two', 3: 'three'}
```


## Keyword arguments

You can use keyword arguments in `dict` to define key, value pairs in your dictionary!
However, this only works if your keys are valid variable names:

```py
# Values don't have to be strings:
>>> dict(one=1, two=2)
{'one': 1, 'two': 2}

# But the keys have to:
>>> dict(1="one", 2="two")
  File "<stdin>", line 1
    dict(1="one", 2="two")
         ^
SyntaxError: expression cannot contain assignment, perhaps you meant "=="?

# Keys have to be valid variable names:
>>> dict(name="Rodrigo", twitter="mathsppblog")
{'name': 'Rodrigo', 'twitter': 'mathsppblog'}
```


## Class method `dict.fromkeys`

The class method `dict.fromkeys` accepts an iterable and a value, and produces a dictionary where all keys have that value.
By default, that value is `None`:

```py
# Default value is None:
>>> dict.fromkeys("abc")
{'a': None, 'b': None, 'c': None}
>>> dict.fromkeys(range(5))
{0: None, 1: None, 2: None, 3: None, 4: None}

# Different default values:
>>> dict.fromkeys(range(5), ";)")
{0: ';)', 1: ';)', 2: ';)', 3: ';)', 4: ';)'}
>>> dict.fromkeys(["likes", "retweets"], 0)
{'likes': 0, 'retweets': 0}
```


The class method `.fromkeys` has a gotcha associated with it, though.
Be careful when using mutable values, because the value isn't copied to each key.
It's exactly the same object used over and over:

```py
>>> d = dict.fromkeys(range(3), [])
>>> d
{0: [], 1: [], 2: []}

>>> d[0].append("zero")
>>> d[1].append("one")

>>> d[2]  # Shouldn't d[2] be empty?!
['zero', 'one']
>>> d
{0: ['zero', 'one'], 1: ['zero', 'one'], 2: ['zero', 'one']}  # 🤯
```


These are just 3 ways of creating a Python dictionary.
Soon, I'll send out a Mathspp Insider article talking about all the ways in which you can create dictionaries in Python 🐍
Join to keep learning: <https://mathspp.com/insider>


## Summary

Here's a quick recap:

 1. `dict` accepts an iterable that contains key, value pairs;
 2. use keyword arguments in `dict` if you want string keys; and
 3. `.fromkeys` gives the same value (default is `None`) to a bunch of keys.

Comment other ways in which you can create a Python 🐍 dict!


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1563153357359181824) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
