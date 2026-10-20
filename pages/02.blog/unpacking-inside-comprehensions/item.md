Learn how to use the unpacking operator inside list comprehensions.

===

Starting with Python 3.15, you can use the asterisk `*` to unpack inside [list comprehensions](/blog/pydonts/list-comprehensions-101):

```py
nested = [[0, 1, 2], [3, 4], [], [5, 6, 7]]
flattened = [*sublist for sublist in nested]
print(flattened)  # [0, 1, 2, 3, 4, 5, 6, 7]
```

This new unpacking feature also works with set comprehensions, dictionary comprehensions, and generator expressions.
This article explains the syntax, the alternatives in Python 3.14 or earlier, and shows real world example applications.

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Unpacking inside a comprehension

If you have a nested list of lists, you can flatten them out by unpacking inside a comprehension:

```py
nested = [[0, 1, 2], [3, 4], [], [5, 6, 7]]
flattened = [*sublist for sublist in nested]
print(flattened)  # [0, 1, 2, 3, 4, 5, 6, 7]
```

Before the unpacking syntax in 3.15, you'd have to write a nested list comprehension:

```py
nested = [[0, 1, 2], [3, 4], [], [5, 6, 7]]
flattened = [
    element
    for sublist in nested
    for element in sublist
]
print(flattened)  # [0, 1, 2, 3, 4, 5, 6, 7]
```

The structure of the nested list comprehension mirrors the structure of the equivalent nested `for` loop:

```py
nested = [[0, 1, 2], [3, 4], [], [5, 6, 7]]
for sublist in nested:
    for element in sublist:
        flattened.append(element)
print(flattened)  # [0, 1, 2, 3, 4, 5, 6, 7]
```

If you're familiar with list methods, you'll know that the nested loop version can be written more idiomatically by removing the inner loop and using the method `extend` instead:

```py
nested = [[0, 1, 2], [3, 4], [], [5, 6, 7]]
for sublist in nested:
    flattened.extend(sublist)
print(flattened)  # [0, 1, 2, 3, 4, 5, 6, 7]
```

The call to `extend` allows you to add all the elements of `sublist` to the flattened list without explicitly iterating, just like unpacking inside the comprehension.
In fact, using `extend` inside a `for` loop is the cue for using unpacking inside list comprehensions.

## Unpacking inside set comprehensions

Unpacking inside set comprehensions works exactly in the same way, except the final collection is a set and not a list:

```py
nested = [[0, 1, 2], [3, 4], [], [5, 6, 7]]
flattened = {*sublist for sublist in nested}
print(flattened)  # {0, 1, 2, 3, 4, 5, 6, 7}
```

The code above matches the previous example, but creates a flat set instead of a list.

As another example, consider the code below that uses the set method `update` to create a set with all the unique characters used in a list of strings:

```py
words = ["hello", "world"]

characters = set()
for word in words:
    characters.update(word)

print(characters)  # {'e', 'r', 'h', 'o', 'l', 'w', 'd'}
```

Using `set.update` inside a `for` loop is akin to using `list.extend` inside a `for` loop, and it's a cue that you may want to use unpacking inside a set comprehension:

```py
words = ["hello", "world"]
characters = {*word for word in words}
print(characters)  # {'e', 'r', 'h', 'o', 'l', 'w', 'd'}
```

## Unpacking inside dictionary comprehensions

Dictionaries can also be unpacked inside dictionary comprehensions, but you need to use two asterisks `**` instead of just one `*`:

```py
configs = [
    {"user": "<default>", "font_size": 18},
    {"user": "rodrigo"},
]
config = {**conf for conf in configs}
print(config)  # {'user': 'rodrigo', 'font_size': 18}
```

Unpacking a dictionary inside a dictionary comprehension is preferred over using a `for` loop with repeated calls to `dict.update`:

```py
configs = [
    {"user": "<default>", "font_size": 18},
    {"user": "rodrigo"},
]

config = {}
for conf in configs:
    config.update(conf)

print(config)  # {'user': 'rodrigo', 'font_size': 18}
```

## Code examples

This section will cover a few examples from the standard library where you could use unpacking inside comprehensions.

### Ignoring names in `shutil`

The module `shutil` has a function that creates a list of ignored names out of a series of patterns:

```py
# shutil.py
ignored_names = []
for pattern in patterns:
    ignored_names.extend(fnmatch.filter(names, pattern))
return set(ignored_names)
```

This snippet of code follows the pattern of calling `list.extend` inside a `for` loop.
This means it could be rewritten as

```py
return {
    *fnmatch.filter(names, pattern)
    for pattern in patterns
}
```

### Computing slots in `dataclasses`

The module `dataclasses` has a snippet of code that computes slots that a dataclass will inherit:

```py
inherited_slots = set(
    itertools.chain.from_iterable(map(_get_slots, cls.__mro__[1:-1]))
)
```

The usage of `itertools.chain.from_iterable` allows flattening the iterable of slots produced by `map(_get_slots, cls.__mro__[1:-1])`, and that's then wrapped in `set` to build a set of all the inherited slots.

To help understand the snippet above, the code below shows how `chain.from_iterable` can be used to flatten a nested iterable:

```py
from itertools import chain

nested = [[0, 1, 2], [3, 4], [], [5, 6, 7]]
flattened = list(chain.from_iterable(nested))
print(flattened)  # [0, 1, 2, 3, 4, 5, 6, 7]
```

The advantage of using `chain.from_iterable` is that it's lazy, so if you need to iterate over the elements of nested iterables, it's best to use `chain.from_iterable`.
However, in scenarios like the one in `dataclasses`, the call to `chain.from_iterable` is just an intermediate step, since you take the chain and transform it to a set:

```py
inherited_slots = set(
    itertools.chain.from_iterable(map(_get_slots, cls.__mro__[1:-1]))
)
```

`chain.from_iterable` is being used as a helper to get a set with all the slots from all the classes in the method resolution order (`__mro__`) of `cls`.
Since the laziness of `chain.from_iterable` is not being leveraged here, you can replace it with unpacking inside a set comprehension:

```py
inherited_slots = {*_get_slots(c) for c in cls.__mro__[1:-1]}
```

The resulting code is more elegant since it's more straightforward.

## Unpacking inside generator expressions

Generator expressions also support unpacking inside them.
In general, the generator expression

```py
g = (*sub for sub in iterable)
```

is equivalent to a generator function with a double `for` loop:

```py
def generator(iterable):
    for sub in iterable:
        for value in sub:
            yield value

g = generator()
```

As another example, in Python 3.14 and earlier, `chain.from_iterable` was the most convenient way to flatten an iterable lazily.
Now, `chain.from_iterable` can be replaced with a generator expression with unpacking:

```py
# Before:
from itertools import chain
chain.from_iterable(map(_get_slots, cls.__mro__[1:-1]))  # From `dataclasses.py`

# After:
(*_get_slots(c) for c in cls.__mro__[1:-1])
```

The snippet above shows a concrete example based on the `dataclasses` snippet, but ignoring the final computed set.
In general, the call

```py
chain.from_iterable(iterable)
```

Can become

```py
(*sub for sub in iterable)
```

Using the generator expression is particularly useful if the iterable is a more complex expression, as is the case with `map(...)` in the previous example.

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}
