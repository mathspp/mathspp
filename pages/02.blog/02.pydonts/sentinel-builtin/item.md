Learn how to use the sentinel built-in from Python 3.15 for placeholder values.

===

Python 3.15 introduced the new **`sentinel` built-in**.
The **`sentinel`** built-in can be used to create placeholder values:

```py
NOTHING = sentinel("NOTHING")
```

A placeholder created by `sentinel` is the more idiomatic way to provide default arguments or return values where `None` wouldn't be usable.

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Creating and using sentinels

The built-in `sentinel` accepts a string argument which is the name of the sentinel:

```py
NOTHING = sentinel("NOTHING")
print(NOTHING)  # NOTHING
```

The name of the sentinel is used as its default string representation.

Alternatively, you can provide a second named argument to be used as the representation of the sentinel:

```py
NOTHING = sentinel("NOTHING", repr="__main__.NOTHING")
print(NOTHING)  # __main__.NOTHING
```

While there's no runtime restriction imposed, the name of the sentinel and the name of the variable that the sentinel is assigned to should match:

```py
# Correct:
NOTHING = sentinel("NOTHING")

# Wrong:
NOTHING = sentinel("nothing")
N = sentinel("NOTHING")
BANANAS = sentinel("APPLES")
```

Linters and type checkers may complain if there's a mismatch.
Since sentinels are typically constants you won't update, it's also common for sentinels to have names in all uppercase.

Sentinels are typically used as placeholders and the most common operation is to check if a value is a specific sentinel.
For example, imagine a function `find` that looks for a value in an iterable that satisfies a given predicate:

```py
NOTHING = sentinel("NOTHING")

def find(iterable, predicate):
    for value in iterable:
        if predicate(value):
            return value
    return NOTHING
```

Note how the default return value can't be `None` because there are situations where `find(iterable, predicate)` could actually return the value `None`.
This means that a dedicated placeholder value is required, hence the use of the built-in `sentinel`.

To check if the function `find` returned the placeholder or not, you should use the comparison operators `is` or `is not`:

```py
if (value := find(iterable, predicate)) is not NOTHING:
    print(f"A value was found: {value}")
```

## Sentinels in typing

A sentinel can be used as its own type in a typing context, just like `None`.
In the function `find`, from above, the return type would be ` -> ... | NOTHING`:

```py
from collections.abc import Callable, Iterable

NOTHING = sentinel("NOTHING")

def find[V](iterable: Iterable[V], predicate: Callable[[V], bool]) -> V | NOTHING:
    for value in iterable:
        if predicate(value):
            return value
    return NOTHING
```

The snippet above defines the function `find` as accepting an iterable of values of a given type `V`.
Then, the function will either return a value of type `V` (if it finds one) or `NOTHING`, which is the sentinel.

## Real-world example from Textual

As another example of where sentinels could be used, consider the following code from the open source project [Textual](https://github.com/Textualize/textual/):

```py
class UnusedParameter:
    """Helper type for a parameter that isn't specified in a method call."""

UNUSED = UnusedParameter()
"""Sentinel for method signatures."""

class ProgressBar(Widget):
    """A progress bar widget."""

    ...

    def update(
        self,
        *,
        total: None | float | UnusedParameter = UNUSED,
        progress: float | UnusedParameter = UNUSED,
        advance: float | UnusedParameter = UNUSED,
    ) -> None:
        """Update the progress bar with the given options.

        Example:
            progress_bar.update(
                total=200,  # Set new total to 200 steps.
                progress=50,  # Set the progress to 50 (out of 200).
            )
        """

        if not isinstance(total, UnusedParameter):
            ...
```

The method `ProgressBar.update` updates the status of a progress bar.
For example, calling `update(total=None)` changes the type of progress bar to be one of those that constantly update, showing that the UI isn't frozen.
Since `None` is a valid argument value for `total`, it can't be used as the default.

Since Textual is fully typed, using `object()` as the default value was also impossible.
To use `object()`, the type of `total` would have to become `total: None | float | object`.
This means that a call like `update(total="bananas")` wouldn't be caught by the type checker, since the string `"bananas"` is of type `object`.

The workaround, before Python 3.15 introduced `sentinel`, was to create a dedicated class `UnusedParameter` and then create an instance `UNUSED` of that class.

With Python 3.15 and the new built-in `sentinel`, the code becomes:

```py
NOTHING = sentinel("NOTHING")

class ProgressBar(Widget):
    """A progress bar widget."""

    ...

    def update(
        self,
        *,
        total: None | float | NOTHING = NOTHING,
        progress: float | NOTHING = NOTHING,
        advance: float | NOTHING = NOTHING,
    ) -> None:
        """Update the progress bar with the given options."""

        if total is not NOTHING:
            ...
```

This new version of the code is much more straightforward, since the intent is clear from the beginning.
The code inside the method `update` itself also becomes cleaner, although that's not visible in the excerpt of code shared.

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}
