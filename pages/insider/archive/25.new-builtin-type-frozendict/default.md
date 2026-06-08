---
date: 08-06-2026 21:27
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn about the new built-in coming to Python 3.15: the frozendict."
    og:image: "https://mathspp.com/insider/archive/new-builtin-type-frozendict/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/new-builtin-type-frozendict/thumbnail.webp"
title: "new built-in type: frozendict"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 new built-in type: frozendict

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## New built-in type

Python 3.15 ships with a _new_ built-in type:

`frozendict`.

A frozen dictionary is just like a regular dictionary, but keys and values are frozen.

That means they're immutable.

The addition of `frozendict` fills a clear hole that the built-in types had:

- Mutable `list`, immutable `tuple`
- Mutable `set`, immutable `frozenset`
- Mutable `dict`, and now, immutable `frozendict`

## How the immutability works

`frozendict` is immutable, which means the keys and values must remain the _same_ throughout the lifetime of the dictionary.

Here's how you can create a frozen dictionary:

```python
me = frozendict({"name": "Rodrigo", "site": "mathspp.com"})
```

If you try to modify the value associated with a key, you get an error:

```python
me["name"] = "John"
# TypeError: 'frozendict' object does not support item assignment
```

If you try to create a new key, you get the same error:

```python
me["newsletter"] = "mathspp insider"
# TypeError: 'frozendict' object does not support item assignment
```

And you also get an error if you try to delete a key:

```python
del me["name"]
# 'frozendict' object does not support item deletion
```

## Mutating inner containers

However, if a frozen dictionary has values that are mutable, you can still mutate those values:

```python
list_dict = frozendict({"list": []})
list_dict["list"].extend(range(5))
print(list_dict["list"])  # [0, 1, 2, 3, 4]
```

This is also what happens with tuples.

The reason is that, in the eyes of the frozen dictionary, there's nothing it can do to prevent the inner list to be mutated.

And you can also argue that it's the _same_ object.

The id of the list doesn't change because it has new values in it.

So, the list _looks_ different, but it's the same.

Kind of like you're also the same, even though you wear different clothes on different days.

## Hashability

Frozen dictionaries are hashable, as long as their respective keys and values are also hashable.

This means you can have frozen dictionaries as dictionary keys, as set elements, or as arguments to functions that cache their arguments.

The two previous dictionaries are hashable and not hashable, respectively:

```python
print(hash(me))  # -1815934699570385105
print(hash(list_dict))
# TypeError: unhashable type: 'list'
```

## Type hierarchy

The new built-in `frozendict` is _not_ a subclass of `dict`.

And `dict` is _not_ a subclass of `frozendict`.

They have completely independent class hierarchies.

You can check this by printing each of their `__mro__` attributes:

```python
print(frozendict.__mro__)
# (<class 'frozendict'>, <class 'object'>)
print(dict.__mro__)
# (<class 'dict'>, <class 'object'>)
```

## In type hints

The new built-in `frozendict` can be used as a type hint to represent frozen dictionary instances.

If you're writing a function that may accept regular dictionaries and frozen dictionaries, you can't just use `dict`, though.

For functions that accept any type of dictionary, you can obviously use `dict | frozendict`.

Alternatively, if your function really works with any _mapping_, and not necessarily _just_ `dict` or `frozendict`, you can use `collections.abc.Mapping`.

In general, if you're using keys to access data from a dictionary in a function but you're not mutating that dictionary, you can use `collections.abc.Mapping`.

If you need mutability, you can use `collections.abc.MutableMapping`.

## Play around with `frozendict`

Python 3.15.0 is scheduled to be officially released in October 2026.

You can already play with a beta release of Python 3.15 if you're [using uv](https://mathspp.com/courses/uv).

For example, `uv run --python 3.15 python` will open a Python 3.15 REPL.

And `uv run --python 3.15 /path/to/script.py` will run `script.py` with Python 3.15.

Take Python 3.15 for a spin and help test it.

If you find a bug with Python 3.15, report it so it's fixed before the official 3.15.0 release!

To learn more about how to improve your Python workflows with uv, sign-up for this [free, four-day uv email course](https://mathspp.com/courses/uv).

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
