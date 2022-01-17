Today I learned how to use the `namedtuple` from the module `collections`.

===

![The Python import statement that allows us to use the Python module `selectors`.](thumbnail.png)


# `namedtuple`

The module [`collections`][collections] is a gold mine of useful Python tools,
and one of those tools is the `namedtuple`.

A named tuple is like a tuple,
with the added functionality that you can access the contents of the tuple by name,
instead of just by position.

For example, the tuple

```py
>>> me = ("Rodrigo", "mathsppblog")
```

can represent me, in the sense that `me[0]` is my first name and `me[1]` is my Twitter handle.
If `me` were a named tuple, I would not have to remember the order of the items inside the tuple,
I could just do something like

```py
>>> me.name
'Rodrigo'
>>> me.twitter
'mathsppblog'
```

Of course you could also do this with a class:

```py
class Person:
    def __init__(self, name, twitter):
        self.name = name
        self.twitter = twitter
```

This also works, but that's also more work!


# How to create a `namedtuple`

So, how do you create a `namedtuple`?
After the appropriate imports, you just need to call the factory function `namedtuple`:

```py
>>> from collections import namedtuple
>>> Person = namedtuple("Person", ["name", "twitter"])
>>> me = Person("Rodrigo", "mathsppblog")
>>> me.name
'Rodrigo'
>>> me.twitter
'mathsppblog'
>>> me[1]
'mathsppblog'
```

That's all it takes!

Reading [the docs][namedtuple-docs] will show you how powerful named tuples can be,
and even shows two nice use cases for them.
I'll reproduce one here.

Suppose you have the following CSV file `twitter_people.csv`:

```txt
Rodrigo,mathsppblog
Mike,driscollis
Will,willmcgugan
```

You want to read this data in, and use this data to build `Person` named tuples like the named tuple `me` above.

By using the module `csv` (to read the CSV data) and the `_make` function of the named tuple, this is possible:

```py
>>> from collections import namedtuple
>>> Person = namedtuple("Person", ["name", "twitter"])

>>> import csv
>>> with open("twitter_people.csv", "r") as f:
...     reader = csv.reader(f)
...     for person in map(Person._make, reader):
...         print(f"{person.name} is on Twitter @{person.twitter}!")
...
Rodrigo is on Twitter @ mathsppblog!
Mike is on Twitter @ driscollis!
Will is on Twitter @ willmcgugan!
```


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[collections]: https://docs.python.org/3/library/collections
[namedtuple-docs]: https://docs.python.org/3/library/collections.html#collections.namedtuple
