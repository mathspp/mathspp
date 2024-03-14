The Python module collections contains many useful tools.
This is an overview of those tools.

===


# Introduction

All Python developers should know about the module `collections`.
The module `collections` contains many useful tools that can make you a much more productive programmer.
These tools include:

 - `namedtuple`;
 - `deque`;
 - `ChainMap`;
 - `Counter`; and
 - `defaultdict`.

The module `collections` also provides the tools `OrderedDict`, `UserDict`, `UserList`, and `UserString`,
but we will not cover them in this short article.


# `namedtuple`

The `namedtuple` is like a factory of tuples.
You can use it to define new "types of tuples" for which the values have names.

The documentation example is quite good:

```pycon
>>> from collections import namedtuple
# Basic example
>>> Point = namedtuple('Point', ['x', 'y'])

# instantiate with positional or keyword arguments
>>> p = Point(11, y=22)

# indexable like the plain tuple (11, 22)
>>> p[0] + p[1]
33
# unpack like a regular tuple
>>> x, y = p
>>> x, y
(11, 22)

# fields also accessible by name
>>> p.x + p.y
33
# readable __repr__ with a name=value style
>>> p
Point(x=11, y=22)
```

# `deque`

The collection `deque` is like a doubly-linked list.
When using a `deque`, you can append and pop elements from both ends, fast.

Lists (and deques) have the methods `append`, `pop`, and `extend`, that work on the right...
Deques add the methods `appendleft`, `popleft`, and `extendleft`, that work on the left!
(Deques have even more useful methods! These were just a small example.)

Here is an example that manipulates a `deque` from both ends:

```pycon
>>> from collections import deque
>>> d = deque()  # Empty.
>>> d
deque([])
>>> d.append(0); d.append(1); d.append(2).       
>>> d
deque([0, 1, 2])

>>> d.extendleft(range(3, 6))
>>> d
deque([5, 4, 3, 0, 1, 2])

>>> d.pop()
2
>>> d
deque([5, 4, 3, 0, 1])

>>> d.popleft()
5
>>> d
deque([4, 3, 0, 1])
```

You can learn how to use the data structure `deque` by reading [this tutorial](/blog/python-deque-tutorial), which includes 7 usage examples.


# `ChainMap`

The `ChainMap` does exactly what it says on its name:
it chains multiple mappings (think of dictionaries) without having to explicitly merge them.

My favourite use case for a `ChainMap` is when I have multiple dictionaries that represent configurations for a program:

 - the default configuration; and
 - the user configuration.

With a `ChainMap`, I can combine them easily:

```pycon
>>> from collections import ChainMap

# Default and user configurations:
>>> default = {"user": None, "has_twitter": False}
>>> rodrigo = {"user": "@mathsppblog"}

# Merge to create global config:
>>> config = ChainMap(rodrigo, default)
>>> config["user"]  # First dict (rodrigo) has precedence.
'@mathsppblog'

# Not available in `rodrigo`, fetch from `default`
>>> config["has_twitter"]
False

>>> config["has_twitter"] = True
>>> config["has_twitter"]
True
>>> config
ChainMap(
    {'user': '@mathsppblog', 'has_twitter': True},
    {'user': None, 'has_twitter': False},
)
>>> rodrigo
{'user': '@mathsppblog', 'has_twitter': True}
```

Notice that when you update a value of the `ChainMap`, the update is made on the first dictionary.
This may not be what you want all the time.
Thankfully, you can still update the dictionaries separately and the changes will be reflected on the `ChainMap` object!


# `Counter`

The `Counter` is one of my favourites from the standard library!
The `Counter` is like a dictionary that counts elements from what you feed it.

For example, the code below counts the letters in the string `"MISSISSIPPI"` and shows how to retrieve count information:

```pycon
>>> from collections import Counter

>>> cnt = Counter("MISSISSIPPI")
>>> cnt["I"]
4

>>> cnt.items()
dict_items([('M', 1), ('I', 4), ('S', 4), ('P', 2)])

>>> cnt.most_common(2)
[('I', 4), ('S', 4)]
```

`Counter` instances are also versatile and very easy to update.
For example, you can use the method `.update` or you can add another `Counter` instance.
Counters also have a default value of `0` for values that have never been found.

```pycon
>>> from collections import Counter
>>> cnt = Counter("MISSISSIPPI")
>>> cnt["M"]
1

>>> cnt.update("MAGICAL")
>>> cnt["M"]
2

>>> cnt += Counter("THE QUICK LLAMA IS UGLY")
>>> cnt["M"]
3

>>> cnt["?"]
0
```


# `defaultdict`

The `defaultdict` also has a very revealing name:
it is a dictionary with a default value.
Kind of.

The `defaultdict` allows you to specify a _factory_ `f` for the default value.
So, if you try to access a key that your `defaultdict` does not have,
it will call `f()` to provide the default value for that key.
Here is an example where the factory is `list`, which means that when we call `list()` we get an empty list as the default value:

```pycon
>>> from collections import defaultdict

>>> people = {  # Questionable line-up.
...     "Rodrigo": "Portugal",
...     "Cristiano Ronaldo": "Portugal",
...     "Superman": "USA",
...     "Harry Potter": "UK",
...     "Tiger Woords": "USA",
... }

>>> people_by_country = defaultdict(list)

>>> for person, country in people.items():
...     people_by_country[country].append(person)  
...

>>> people_by_country
defaultdict(<class 'list'>, {
    'Portugal': ['Rodrigo', 'Cristiano Ronaldo'],
    'USA': ['Superman', 'Tiger Woords'],
    'UK': ['Harry Potter']
})
```

Notice how the `for` loop contains a single expression `people_by_country[country].append(person)` and we do not have to worry about creating an empty list if it is the first time we are looking at a given country.
That is because `defaultdict` will call `list()` in that case to create an empty list for us!


# Conclusion

Hopefully, this article gave you an overview of some of the useful tools that the module `collections` contains:

 - `namedtuple`;
 - `deque`;
 - `ChainMap`;
 - `Counter`;
 - `defaultdict`;

Familiarise yourself with these tools and be sure to use them if the opportunity arises in your code!
