Learn how to use the new `frozendict` built-in to create and manipulate immutable dictionaries.

===

Python 3.15 introduced a new built-in data type: the **frozendict**.
A **frozendict** is an immutable dictionary that can be created with the built-in of the same name:

```py
version_info = frozendict(
    {"major": 3, "minor": 15, "patch": 0}
)
```

The dictionary literal syntax using the curly braces `{}` creates a regular dictionary, so you must always use the built-in `frozendict` to create a new frozen dictionary.

In this article you'll learn about the characteristics of this immutable dictionary like its hashability, along with how to use this in conjunction with `dict` in typing contexts.

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Creation and usage

You create immutable dictionaries with the built-in `frozendict` and you can access key/value pairs with the regular dictionary syntax:

```py
version_info = frozendict({"major": 3, "minor": 15, "patch": 0})

print(version_info["major"])  # 3
```

You can also iterate through a `frozendict`'s keys, values, or items:

```py
for name, number in version_info.items():
    print(name, number)
"""
major 3
minor 15
patch 0
"""
```

If you don't have to support Python 3.14 or earlier, you should use `frozendict` whenever you're creating or returning a dictionary that isn't supposed to be explicitly and clearly used as mutable.

## Immutability of a `frozendict`

The main premise of `frozendict` is that it's immutable:

```py
version_info = frozendict({"major": 3, "minor": 15, "patch": 0})

try:
    version_info["year"] = 2026
except TypeError:
    print("Can't add a new key to a frozendict.")

try:
    version_info["major"] = 4
except TypeError:
    print("Can't modify an existing key.")

try:
    del version_info["patch"]
except TypeError:
    print("Can't delete an existing key.")
```

The code above shows that adding a new key, modifying an existing key, or deleting an existing key of a `frozendict`, all result in a `TypeError` at runtime.

`frozendict` supports all the methods that a mutable dictionary supports, except the ones that mutate the inner state of the dictionary:

 - `clear`: used to clear all items of the dictionary
 - `__delitem__`: used to delete a dictionary item with the `del mapping[key]` syntax
 - `pop` / `popitem`: should remove a key/value pair of the dictionary
 - `__setitem__`: used to assign to a dictionary key with the `mapping[key] = value` syntax
 - `update` / `__ior__`: should update the dictionary with the items of a second dictionary

The immutability of a `frozendict` applies to the identity of its keys and values.
If one of the values is mutable, then that value may be mutated and it will look like the `frozendict` was also mutated.

For example, a `frozendict` instance may hold a list as a value and the list remains mutable:

```py
icecream_flavours = ["vanilla", "chocolate"]
savoury_snacks = ["cheese", "toast"]
food_offered = frozendict({"icecream": icecream_flavours, "savoury": savoury_snacks})

print(food_offered["icecream"])  # ['vanilla', 'chocolate']

icecream_flavours.append("strawberry")

print(food_offered["icecream"])  # ['vanilla', 'chocolate', 'strawberry']
```

This behaviour is akin to what happens in tuples, which are immutable, but that may themselves hold mutable objects.

## Use case examples

As mentioned earlier, you should use `frozendict` whenever you're creating or returning a dictionary that isn't supposed to be explicitly and clearly used as mutable.

### Look-up tables

For example, whenever you're using a dictionary as a simple look-up table, you should use a `frozendict` instead of a mutable dictionary:

```py
# json/decoder.py from the 3.15 std library
NaN = float('nan')
PosInf = float('inf')
NegInf = float('-inf')

_CONSTANTS = frozendict({
    '-Infinity': NegInf,
    'Infinity': PosInf,
    'NaN': NaN,
})
```

The snippet of code above was extracted from the module `json` from the standard library and shows a look-up table using an immutable dictionary.
Since `_CONSTANTS` isn't explicitly meant to be mutated, making it immutable conveys that meaning clearly and prevents accidental mistakes.

### Loading immutable JSON data

The module `json` provides the function `load` to load JSON data from a file and the optional argument `object_hook` can be used with `frozendict` to load dictionaries as immutable instead of mutable.

Suppose you have this piece of JSON data in a file `version.json`:

```json
{
    "major": 3,
    "minor": 15,
    "patch": 0
}
```

If you set `object_hook` to `frozendict` in `json.load`, you get an immutable view of the loaded data:

```py
import json

with open("version.json", "r") as f:
    version_info = json.load(f, object_hook=frozendict)

print(version_info)  # frozendict({'major': 3, 'minor': 15, 'patch': 0})
```

In cases where the JSON represents an immutable _source_, using `frozendict` stops you from mistakenly mutating your data without realising.

For comparison, the regular JSON loading would create a mutable dictionary:

```py
import json

with open("version.json", "r") as f:
    version_info = json.load(f)

print(version_info)  # {'major': 3, 'minor': 15, 'patch': 0}
```

## Typing `frozendict`

### Generic type

In typing, `frozendict` is a generic class and is annotated like `dict`:

```py
import sys

def version_info() -> frozendict[str, int]:
    return frozendict({
        "major": sys.version_info.major,
        "minor": sys.version_info.minor,
        "patch": sys.version_info.micro,
    })
```

The syntax `frozendict[str, int]` is an immutable dictionary that maps strings to integers.

### The protocol `Mapping`

Note that `frozendict` and `dict` have unrelated class hierarchies.
From 3.15 onward, it'll become more important to use the protocols `collections.abc.Mapping` and `collections.abc.MutableMapping`.

Consider the built-in function `eval`, that evaluates a Python expression and accepts a dictionary `globals` to specify values for global variables:

```py
print(eval("3 + x", globals={"x": 5}))  # 8
```

In Python 3.14, the function `eval` was typed[^1] like this:

```py
from typing import Any

def eval(source: str, globals: dict[str, Any]) -> Any:
    ...
```

[^1]: We're making some simplifications. You can [view the full typing of the built-in `eval` on GitHub](https://github.com/python/typeshed/blob/86d68f86591c4bf97723350505d2cd28123ab3b6/stdlib/builtins.pyi#L1757-L1781), for example.

Since `dict` and `frozendict` are unrelated classes, this means that `eval` can't accept an immutable dictionary.
To fix this in Python 3.15, you need to specify a type union:

```py
from typing import Any

def eval(source: str, globals: dict[str, Any] | frozendict[str, Any]) -> Any:
    ...
```

If your function needs a mapping and it doesn't need to mutate it, in most scenarios you'll want to use the protocol `collections.abc.Mapping` instead of the type union.
The protocol is a bit more general and accepts both mutable and immutable dictionaries:

```py
from collections.abc import Mapping
from typing import Any

def eval(source: str, globals: Mapping[str, Any]) -> Any:
    ...
```

By using `Mapping` as the type of `globals`, both calls are valid in the eyes of a type checker:

```py
variables = {"x": 5}
eval("3 + x", globals=variables)

variables = frozendict(variables)
eval("3 + x", globals=variables)
```

As another example, my blog tools define a function `get_file_stats_and_tags` that accepts a cache that maps folder names to stats computed for the corresponding blog article:

```py
type Stats = ...  # A custom type.

def get_file_stats_and_tags(cache: dict[str, Stats], filepath: Path):
    ...
```

The body of the function `get_file_stats_and_tags` only uses the cache as a source of information and doesn't mutate it.
This means that, in the future, I may want to pass it an immutable dictionary representing the cache.
For my function to support it, the best fix is to replace `dict` with `Mapping`:

```py
from collections.abc import Mapping

type Stats = ...  # A custom type.

#               swapped out `dict` vvvvvvv
def get_file_stats_and_tags(cache: Mapping[str, Stats], filepath: Path):
    ...
```

## Hashability

A `frozendict` is hashable whenever all its values are hashable.
This means that `frozendict` instances can be used as dictionary keys or set elements, and they can be used as arguments to cached functions.

```py
version_info = frozendict({"major": 3, "minor": 15, "patch": 0})

versions = {version_info: "favourite version"}

print(versions)
# {frozendict({'major': 3, 'minor': 15, 'patch': 0}): 'favourite version'}
```

Since integers are hashable, the `frozendict` instance `version_info` can be hashed and can be used as a dictionary key in the dictionary `versions`.

## Comparing `frozendict` instances

`frozendict` instances can be compared among themselves:

```py
print(
    frozendict({"major": 3, "minor": 15, "patch": 0})
    == frozendict({"patch": 0, "major": 3, "minor": 15})
)  # True
```

In equality comparisons, the order of the items does not matter.

`frozendict` instances can also be compared with mutable dictionaries:

```py
print(
    frozendict({"major": 3, "minor": 15, "patch": 0})
    == {"patch": 0, "major": 3, "minor": 15}
)  # True
```

In comparisons between `frozendict` instances and mutable dictionaries, the mutability is disregarded.
If the items match, regardless of order, the two mappings are considered equal.

## Adoption guidelines for library maintainers

If you're using Python 3.15+, prefer `frozendict` over `dict` always, except if you explicitly need to mutate the mapping you're creating.

If you're using Python 3.14 or earlier, you can't use `frozendict` directly.
But if you're using type hints, start using the protocol `collections.abc.Mapping` instead of `dict` in your functions.

Many functions accept arguments of type `dict[K, V]` but they don't need to _mutate_ the argument dictionary.
If your function doesn't need to mutate the dictionary, swap the type hint with `collections.abc.Mapping`.
This means your code will be “forward-compatible” with users of Python 3.15+ and their type checkers won’t annoy them for using your code.

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}
