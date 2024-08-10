Today I learned how to use the function `operator.methodcaller`.

===


# `operator.methodcaller`

The function `methodcaller` from the module `operator` is similar to the functions `itemgetter` and `attrgetter`.
Its only required argument is a string with the name of a method and its return value is a function that calls the specified method on the given object.

You can see a basic example below:

```pycon
>>> from operator import methodcaller

>>> l = [4, 2, 3, 1]
>>> sorter = methodcaller("sort")
>>> sorter(l)  # vs. l.sort()
>>> l
[1, 2, 3, 4]
```

Naturally, this works with arbitrary methods of arbitrary objects:

```pycon
>>> class Person:
...     def __init__(self, first, last):
...         self.first = first
...         self.last = last
...     def get_name(self):
...         return f"{self.first} {self.last}"
...

>>> name_builder = methodcaller("get_name")

>>> hp = Person("Harry", "Potter")
>>> name_builder(hp)  # vs. hp.get_name()
'Harry Potter'
```

The function `methodcaller` also accepts an arbitrary number of positional and keyword arguments that get passed down to the method that is called.
The snippet below exemplifies this:

```pycon
>>> s = "Bananas!"
>>> s.count("a")
3
>>> a_counter = methodcaller("count", "a")
>>> a_counter(s)  # vs. s.count("a")
3
```

The examples below show how `methodcaller` works but they don't show how `methodcaller` is supposed to be used.
In fact, the examples above in isolation would be terrible usage examples for `methodcaller`.

The function `methodcaller` (much like the functions `itemgetter` and `attrgetter`) shine in situations where higher-order functions expect other functions as arguments.
For example, functions like `min`, `max`, and `sorted`, accept an optional argument `key` and `methodcaller` could be useful there:

```pycon
>>> strings = [
...     "Bananas!",
...     "Hello, world!",
...     "The quick brown fox jumps over the lazy dog.",
...     "Can anyone assist that passerby?",
... ]

>>> a_counter = methodcaller("count", "a")

>>> min(strings, key=a_counter)  # String with the least "a"s
'Hello, world!'

>>> max(strings, key=a_counter)  # String with the most "a"s
'Can anyone assist that passerby?'

>>> sorted(strings, key=a_counter)  # Sort strings by number of "a"s
[
    'Hello, world!',                                 # 0
    'The quick brown fox jumps over the lazy dog.',  # 1
    'Bananas!',                                      # 3
    'Can anyone assist that passerby?',              # 5
]
```

`methodcaller` also plays really nicely with `itertools.groupby`.
As another silly example, the snippet below takes a list of strings and groups them by their casing:

```pycon
>>> from itertools import groupby
>>> from operator import methodcaller

>>> strings = [
...     "AHAHA",
...     "lowercase...",
...     "again...",
...     "UPPER",
...     "SCREAM",
... ]

>>> for _, group in groupby(strings, key=methodcaller("isupper")):
...     print(list(group))
...
['AHAHA']                     # all upper case
['lowercase...', 'again...']  # all lower case
['UPPER', 'SCREAM']           # all upper case
```

If you know of, or come up with, other cool use cases for `operator.methodcaller`, please comment below!
