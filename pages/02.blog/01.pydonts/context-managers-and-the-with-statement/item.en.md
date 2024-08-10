This article explores the syntax and use cases of context managers and the `with` statement.

===

# Context managers and the `with` statement

## What's a context manager

A context manager is a Python feature that lets you use a `with` statement to change the context in which some code runs.

Many people have encountered the `with open(filename) as file:` statement before and that's an example of a context manager.
The code within the `with` statement knows that a file is open while we're inside the `with` statement; that's the change in context introduced by this context manager:

```py
# No file is open here...

with open(filename) as file:
    # The file is open in here!

# No file is open here...
```

Let us consider another example.
The standard module `contextlib` contains a context manager called `suppress`.
With it, you can ignore (suppress) certain exceptions:

```py
from contextlib import suppress

with suppress(ZeroDivisionError):
    1 / 0

print("I just tried dividing by 0 and the program didn't crash.")
```

The program above will not crash when run.
That's because the expression `1 / 0` ran under a different context; a context in which `ZeroDivisionError` exceptions were suppressed:

```py
from contextlib import suppress

# No exceptions will be suppressed...

with suppress(ZeroDivisionError):
    # ZeroDivisionError exceptions will be ignored here!

# No exceptions will be suppressed...
```
