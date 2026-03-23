Dive into the inner workings of `contextlib.contextmanager`, a tool to create context managers that can also be used as decorators.

===

## Context managers with `contextlib.contextmanager`

Context managers are a syntactic feature of Python that let you handle resource management in an ergonomic way.
You've probably used a context manager before to interact with files in Python:

```py
with open("path/to/file.txt", "r") as f:
    contents = f.read()
```

Discussing why context managers are helpful and a good idea is beside the point for this article.
I'll assume you're already convinced context managers are a good idea.
Now, I want to focus on how you can use the decorator `contextmanager` from the module `contextlib` to create context managers in an ergonomic way and then I'll walk you through a basic implementation of `contextmanager` itself.


### How to create a context manager by hand

In Python, an object needs to implement the [dunder methods](/blog/dunder-methods) `__enter__` and `__exit__` to be usable as a context manager.
To help you understand how `__enter__` and `__exit__` work, you will reimplement the context manager `suppress` from the module `contextlib` as an example.

[The context manager `contextlib.suppress` can be used to ignore specific exceptions](/blog/ignoring-exceptions-with-contextlib-suppress).
In this first snippet, the division by zero is _suppressed_ by the context manager `suppress` and Python reaches the final call to the function `print`:

```py
from contextlib import suppress

with suppress(ZeroDivisionError):
    1 / 0

print("1 / 0 was ignored.")
## We get to this print ^
```

In this second snippet, the division by zero raises an error that is not handled, thus terminating the program with an error before we reach the call to the function `print`:

```py
from contextlib import suppress

with suppress(ValueError):
    1 / 0

print("Didn't reach this because 1/0 raised an error.")
## We never get to this print ^
```

(If you're wondering, [here's why and when using `suppress` is a good idea](/blog/ignoring-exceptions-with-contextlib-suppress).)

I will now show you how you can reimplement `suppress` by hand.
First, start by making sure that your version of `suppress` can accept some exception types as arguments:

```py
class my_suppress:
    def __init__(self, *exceptions):
        self.exceptions_to_ignore = exceptions
```

Next, the method `__enter__` is called when you enter the context manager using the keyword `with` HOW TO IMPLEMENT IT 
