Today I learned Python 3.15 will get a new sentinel built-in.

===

Sentinel values are unique placeholder values that are commonly used in programming.
Python 3.15 ships with a new built-in `sentinel` that can be used to create new sentinel values:

```py
# Python 3.15+
>>> MISSING = sentinel("MISSING")
>>> MISSING
MISSING
```

Before this PEP, the most common sentinel idiom used the built-in `object`:

```py
MISSING = object()

def my_function(some_arg=MISSING):
    if some_arg is MISSING:
        ... # Handle the sentinel
```

In the function above, the sentinel value `MISSING` is being used to check whether the user passed _anything_ as the parameter `some_arg` or not.
[PEP 661](https://peps.python.org/pep-0661/), that introduced this built-in, has a great discussion covering the reasons as to why this pattern, and many other sentinel patterns, fall short.
In general, each common sentinel idiom suffers from at least one of the following problems:

 1. **Bad string repr**: the [string representation](/blog/pydonts/str-and-repr) is too long and uninformative
 2. **Type unsafe**: the sentinels don't have a distinct type so it becomes hard or impossible to write code that uses the sentinels and is type safe
 3. **Unexpected copy behaviour**: the sentinels can't be copied or pickled without breaking the sentinel behaviour
