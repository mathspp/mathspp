This article explains the 5 best new features of Python 3.15 with clear examples and explanations.

===

Python 3.15 has been published and [it packs plenty of new features and improvements](https://docs.python.org/3.15/whatsnew/3.15.html) over Python 3.14.
This article explores the five most exciting new features of Python 3.15:

 1. Lazy imports
 2. New built-in `frozendict`
 3. New built-in `sentinel`
 4. Unpacking inside comprehensions
 5. Tachyon, a new sampling profiler

You'll get the elevator pitch of each feature and you'll see a couple of examples of their usage.

By the end of this article you'll have a clear picture of some of the new cool features that Python 3.15 brings to the table and you'll be excited to try them out.

## 15 days of Python 3.15

To celebrate the release of Python 3.15, over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Lazy imports

**Explicit lazy imports**, introduced in [PEP 810](https://peps.python.org/pep-0810/), introduce the new keyword `lazy` so that you can mark an import as lazy.
Lazy imports don't run the module you're importing _until_ the imported name is needed.

This feature is very useful if you have applications that have a slow startup time because they import heavy modules.
For example, you can speed up the startup time of a CLI by lazy importing the dependencies of the CLI or the startup time of a development server that doesn't need to frontload every single dependency while you're debugging.

A lazy import starts with the keyword `lazy`:

```py
lazy import json
lazy from math import sqrt
```

The snippet of code above imports the module `json` lazily and it imports the function `sqrt`, from the module `math`, lazily.

An import being lazy means that the module you're importing from doesn't run _until_ the import is required.
Instead of running the module, a lazy import gives you an object of the type `lazy_import`:

```py
lazy import json

print(globals()["json"])  # <lazy_import 'json'>
```

As soon as you _touch_ the lazy import, it resolves the lazy import and it replaces itself with the real module.
That's why you're printing it through `globals`.
If you run `print(json)`, you'll trigger the lazy import resolution and you'll get the real `json` module:

```py
lazy import json

print(globals()["json"])  # <lazy_import 'json'>

# Trigger resolution:
print(json)  # <module 'json' from '...'>

# The lazy import is gone:
print(globals()["json"])  # <module 'json' from '...'>
```

## New built-in `frozendict`

The new built-in `frozendict`, defined in [PEP 814](https://peps.python.org/pep-0814/), introduces an immutable, hashable, built-in dictionary type.

The literal syntax `{key: value, ...}` still builds regular dictionaries, so you need to use the built-in `frozendict` explicitly to build a frozen dictionary:

```py
version_info = frozendict({"major": 3, "minor": 15, "patch": 0})
print(version_info)  # frozendict({'major': 3, 'minor': 15, 'patch': 0})
```

Trying to add or remove keys, or changing the value associated with a key, results in a `TypeError`:

```py
# New key/value pair:
version_info["next_minor"] = 16
# TypeError: 'frozendict' object does not support item assignment

# Modify existing key/value pair:
version_info["patch"] += 1
# TypeError: 'frozendict' object does not support item assignment

# Delete existing key/value pair:
del version_info["patch"]
# TypeError: 'frozendict' object does not support item deletion
```

When all of its keys and values are hashable, a `frozendict` is also hashable.
This means you can use instances of `frozendict` as dictionary keys or as set elements:

```py
# `frozendict` has a dictionary key:
has_cool_features = {version_info: True}
```

## New built-in `sentinel`

The new built-in `sentinel`, defined in [PEP 661](https://peps.python.org/pep-0661/), can be used to create named placeholder values that have that can't be mistaken for any of the appropriate values you want to accept.

You can create a new sentinel value by calling the built-in `sentinel` and passing it a name:

```py
NOTHING = sentinel("NOTHING")

def find_and_return(haystack, predicate):
    for value in haystack:
        if predicate(value):
            return value
    return NOTHING
```

The snippet above creates a sentinel called `NOT_FOUND` and uses it as the default return value of the function `find_and_return`.
The sentinel can be checked for with `is` when the function is called:

```py
if find_and_return(dataset, filter_function) is NOTHING:
    print("No users satisfy your criteria...")
```

Two of the benefits of these dedicated sentinel values is that their string representation matches their name and they are their own type, meaning that typed functions don't become overly complex or generic when using placeholders:

```py
def find_and_return[V](haystack: Iterable[V], predicate: Callable[[V], bool]) -> V | NOTHING:
    ...
```

## Unpacking inside comprehensions

[PEP 798](https://peps.python.org/pep-0798/) introduces unpacking inside comprehensions.
This feature is specially relevant in the context of nested structures:

```py
nested = [
    (1, 2, 3),
    [4, 5],
    [6],
    (7, 8, 9)
]

flat = [*sub for sub in nested]
print(flat)  # [1, 2, 3, 4, 5, 6, 7, 8, 9]
```

The syntax `*sub` inside the list comprehension wasn't supported before.
This works inside list, dict, and set comprehensions, as well as in generator expressions.

Note that this new syntax does _not_ introduce new behaviour.
Instead, it introduces an alternative to the more cumbersome nested comprehension:

```py
flat = [
    value
    for sub in nested
    for value in sub
]

print(flat)  # [1, 2, 3, 4, 5, 6, 7, 8, 9]
```

## Tachyon, a new sampling profiler

Tachyon is a new high-frequency **sampling profiler** introduced in [PEP 799](https://peps.python.org/pep-0799/).

![The Tachyon logo, featuring a blue Python with wings coming from its head.](_tachyon.webp "The Tachyon logo")

Being a **sampling profiler**, Tachyon only produces an estimate of the time each part of your code takes to run.
However, Tachyon can sample your code up to 1,000,000 times per second, so you can expect to get pretty accurate results.

You can use Tachyon to run and profile a script with the command

```bash
% python -m profiling.sampling run script.py
```

This will run `script.py` and present the profiling results in your terminal.
The command starts with `python -m profiling.sampling` because Tachyon is available in the standard library in the module `profiling.sampling`.

Tachyon also supports 7 output formats, so you could have it generate an HTML flamegraph, for example.

But above all, Tachyon can be _attached to a running process_, so it's the ideal tool to profile a service in production with near-zero overhead.
To attach to process `12345`, you'd run the command

```bash
% python -m profiling.sampling attach 12345
```

## Python 3.15 brings much more to the table

From package startup configuration files, to improved developer experience, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}
