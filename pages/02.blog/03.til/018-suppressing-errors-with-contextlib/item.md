Today I learned that the module `contextlib` provides with a context manager to suppress specified errors.

===

![Bottom half of a face in grayscale doing a “shush” gesture.](thumbnail.png "Photo by Kristina Flour on Unsplash.")


# `contextlib`

The module [`contextlib`][contextlib] contains many utilities to deal with context managers.
In particular, it contains some useful context managers.

One example of a useful context manager that `contextlib` possesses is `suppress`:
the context manager `suppress` does exactly what it says:
it suppresses errors of the specified types.

For example, the code

```py
from contextlib import suppress

with suppress(KeyError):
    ...
```

prepares a context manager in which `KeyError`s are ignored.

This context manager is a great replacement for the following pattern:

```py
try:
    # ...
except SomeError:
    pass
```

When we want to try to do something and want do nothing in case it fails,
the context manager `suppress` is great because it reduces the boilerplate you have to write.

As an example, consider a function that deletes a key from a dictionary.
If you try to delete a key from a dictionary that doesn't contain that key,
you get an error:

```py
>>> del {}["non-existing"]
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
KeyError: 'non-existing'
```

Thus, we could write our function in one of two ways:

```py
def dictionary_key_delete(a_dict, key):
    if key in a_dict:
        del a_dict[key]

# or

def dictionary_key_delete(a_dict, key):
    try:
        del a_dict[key]
    except KeyError:
        pass
```

Because Python prefers [a EAFP coding style][eafp],
the approach with `try` is often preferred in Python.
However, with `contextlib.suppress`, it becomes more concise:

```py
from contextlib import suppress

def dictionary_key_delete(a_dict, key):
    with suppress(KeyError):
        del a_dict[key]
```

At the same time, this makes the code very expressive,
because we know beforehand that we will ignore that specific type of error,
giving a hint that we really just want to _try_ to do something but we won't be bothered if it fails.

Thank you, [@loicteixeira][loicteixeira], for teaching me this, while discussing the [Python Problem-Solving Bootcamp][bootcamp].

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[eafp]: /blog/pydonts/eafp-and-lbyl-coding-styles
[bootcamp]: /pythonbootcamp
[contextlib]: https://docs.python.org/3/library/contextlib.html
[loicteixeira]: https://twitter.com/loicteixeira
