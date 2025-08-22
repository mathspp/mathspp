Learn how to use `functools.Placeholder`, new in Python 3.14, with real-life examples.

===

By reading this article you will understand what `functools.Placeholder` is for and how to use it effectively.


## Partial function application

The new `Placeholder` singleton only makes sense in the context of `functools.partial`, so in order to understand `Placeholder` you will need to [understand how `functools.partial` works and how to use it](/blog/functools-partial).

In a nutshell, `partial` allows you to perform partial function application, by “freezing” arguments to functions.


## How to pass arguments to `functools.partial`

Up until Python 3.13, you could use `partial` to freeze arguments in two types of ways:

 1. you could pass positional arguments to `partial`, which would be passed in the same order to the function being used with `partial`; or
 2. you could pass keyword arguments to `partial`, which would be passed with the same name to the function being used with `partial`.


## Using keyword arguments to skip the first argument

The method 2. is especially useful if you're trying to freeze an argument that is not the first one.
For example, if you use the built-in `help` on the built-in `int`, you can see this signature:

```
int(x, base=10) -> integer
```

If you want to convert a binary string to an integer, you can set `base=2`:

```py
print(int("101", 2))  # 5
```

Now, suppose you want to create a function `from_binary` by “freezing” the argument `2` in the built-in `int`.
Writing

```py
from_binary = partial(int, 2)
```

won't work, since in `partial(int, 2)`, the value `2` is seen as the argument `x` from the signature above.
However, you can pass the base as a keyword argument, skipping the first argument `x` from the signature of the built-in `int`:

```py
from functools import partial

from_binary = partial(int, base=2)

print(from_binary("101"))  # 5
```

But this doesn't always work.


## When keyword arguments don't work

Consider the following function that [uses the string methods `maketrans` and `translate`](/blog/pydonts/string-translate-and-maketrans-methods) to strip punctuation from a string:

```py
import string

_table = str.maketrans("", "", string.punctuation)
def remove_punctuation(string):
    return string.translate(_table)

print(remove_punctuation("Hello, world!"))  # Hello world
```

The function `remove_punctuation` is a thin wrapper around the string method `str.translate`, which is the function doing all the work.
In fact, if you look at `str.translate` as a function, you always pass `_table` as the second argument; what changes is the first argument:

```py
print(str.translate("Hello, world!", _table))  # Hello world
print(str.translate("What?!", _table))  # What
```

This may lead you to wanting to use `partial` to freeze the value `_table` on the function `str.translate`, so you use the built-in `help` to check the signature of `str.translate`:

```
translate(self, table, /) unbound builtins.str method
```

You can see that the first argument is `self`, the string you are trying to translate, and then `table` is the translation table (that `str.maketrans` built magically for you).
But you can also see the forward slash `/`, which means that `self` and `table` are positional-only arguments that cannot be passed in as keyword arguments!

As such, you **cannot** write the following code:

```py
from functools import partial
import string

_table = str.maketrans("", "", string.punctuation)
remove_punctuation = partial(str.translate, table=_table)

print(remove_punctuation("Hello, world!"))
# TypeError: str.translate() takes no keyword arguments
```

But you also can't pass `_table` as a position argument to `partial` because the first argument must be the string to translate...

In a way, you want `partial` to capture the pattern `str.translate(???, _table)`...
And that's what `functools.Placeholder` does.


## Using `functools.Placeholder` to reserve a place for an argument

The singleton `Placeholder` can be used exactly for this: to reserve a place for a positional argument that will only be filled later.
Using Python 3.14 (or later), you can write this code:

```py
from functools import partial, Placeholder
import string

_table = str.maketrans("", "", string.punctuation)
remove_punctuation = partial(str.translate, Placeholder, _table)  # <--

print(remove_punctuation("Hello, world!"))  # Hello world
```

The key line is

```py
remove_punctuation = partial(str.translate, Placeholder, _table)  # <--
```

When you call `remove_punctuation(string)`, `partial` sees that you used a `Placeholder` earlier and it puts the string in that place, turning it into `str.translate(string, _table)`.


## Making `Placeholder` a bit more ergonomic

Since `Placeholder` is quite a mouthful – or a “keyboard-ful”, maybe –, you may want to import `Placeholder` with a shorter name.
For example, importing `Placeholder as _` leads to usages of `Placeholder` that look kind of neat:

```py
from functools import partial, Placeholder as _
import string

_table = str.maketrans("", "", string.punctuation)
remove_punctuation = partial(str.translate, _, _table)  # <--

print(remove_punctuation("Hello, world!"))  # Hello world
```

Using a single underscore `_` as shorthand for `Placeholder` is dangerous, though, because `_` is typically used as a throwaway variable and you'll shadow `Placeholder`.
A more viable alternative is `_P`, which is still pretty short.


## Conclusion

From Python 3.14, you can use `partial` with more functions and in more scenarios, specifically with functions that accept some arguments as positional-only.
In this article you saw an example of using `Placeholder` with the string method `str.translate` to create a function that removes punctuation from strings.
