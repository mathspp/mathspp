---
metadata:
    description: "The str and repr built-ins may look really similar, but they have some key differences!"
title: "str and repr | Pydon't 🐍"
---

Python's `str` and `repr` built-in methods are similar, but not the same.
Use `str` to print nice-looking strings for end users and use `repr` for debugging
purposes.
Similarly, in your classes you should implement the `__str__` and `__repr__`
dunder methods with these two use cases in mind.

===

![A Python code snippet with a custom class and stubs for the dunder methods `str` and `repr`.](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)

# `str` and `repr`

Python has two built-in mechanisms that allow you to convert an object to a string,
so that you can look at it and print it.
I am talking about the `str` class and the built-in `repr` function.

There is often confusion as to what the differences between these two built-ins are,
but the difference is simple and clear.
The `str` class is used when you want to convert something to the string type,
and is also used when you need a readable representation of your object.
On the other hand, the `repr` function is used to create an *unambiguous*
representation of its argument.

End users generally use `str` because they want to print readable and good looking
text, whereas developers may use `repr` because they need to debug code and need
to make sure they know what they are looking at.
For example, take a look at the following interactive session:

```py
>>> print(3)
3
>>> print("3")
3
>>> 3
3
>>> "3"
'3'
```

The `print` function calls `str` on its argument and then displays it, so both the
integer `3` and the string `"3"` get printed the same way:
you have no way to tell if the original object is an integer or a string.
After that, you see that simply writing the integer `3` and the string `"3"` in the
REPL returns an unambiguous representation of the object:
you can tell integers and strings apart, because the REPL is using `repr` under the
hood to show objects.
`repr` is also used when your object is inside a container, like a list or a
dictionary, because containers usually defer their `str` behaviour to `repr`,
as you can see by looking at [PEP 3140][pep3140] and at the following session:

```py
>>> [3, "3"]
[3, '3']
>>> print([3, "3"]) 
[3, '3']
>>> str([3, "3"]) == repr([3, "3"])
True
```

# The `__str__` and `__repr__` dunder methods

When you are defining your own classes in Python you will probably want to specify how
your objects should look when printed, given that the default behaviour in Python
is not very helpful:

```py
>>> class A:
...     pass
... 
>>> a = A()
>>> print(a)
<__main__.A object at 0x012DF640>
>>> a
<__main__.A object at 0x012DF640>
```

If you want to display your objects properly, you will want to implement the `__str__`
and `__repr__` dunder methods (*dunder* stands for *d*ouble *under*score), and the
implementations should follow the use case of `str` and `repr` outlined above:
the implementation of `__str__` should provide a nice, readable representation of your
object and `__repr__` should represent unambiguously your object, preferably by
providing an expression that could be used to rebuild the object.

! If you are not acquainted with Python's dunder methods, you may want to [subscribe]
! to the Pydon't newsletter, I will write more about them later.
! Until then, you may want to have a look at the Python 3 Docs and what they say
! about the [data model](https://docs.python.org/3/reference/datamodel.html#data-model).

When implementing custom classes, I suggest you start by implementing `__repr__`,
as `__str__`
will default to calling `__repr__` if no custom implementation is given,
but only implementing `__str__` still leaves you with rather unhelpful representations
of your objects.

If you just implement `__str__`:

```py
>>> class A:
...     def __str__(self):
...             return "A"
... 
>>> a = A()
>>> a
<__main__.A object at 0x01600760>
>>> print(a)
A
```

if you just implement `__repr__`:

```py
>>> class A:
...     def __repr__(self):
...             return "A"
... 
>>> a = A()
>>> a
A
>>> print(a)
A
```

# Examples in code

## `datetime`

Python's `datetime` module supplies classes for manipulating dates and times.
A simple date could be created like so:

```py
>>> import datetime
>>> date = datetime.datetime(2021, 2, 2)
```

Now that we have your `date` object of type `datetime.datetime`, we can see what its
`repr` looks like and compare it to its `str` version:

```py
>>> print(repr(date))
datetime.datetime(2021, 2, 2, 0, 0)
>>> print(str(date))
2021-02-02 00:00:00
```

We can see that `repr(date)` could be used to create the same exact object:

```py
>>> date == datetime.datetime(2021, 2, 2, 0, 0)
True
>>> date == eval(repr(date))
True
```

Whereas `str(date)` creates a nice-looking representation of the date in question.
Notice that from its `str` we can't even tell that we were dealing with a
`datetime.datetime` object.


## 2D point

An example custom usage of the `__str__` and `__repr__` dunder methods could come
into play if you were to implement a simple class that represents 2D points,
for example because you have to deal with images or a game or maps, or whatever
your use case is.

Ignoring all other methods you would certainly implement, your class could look
like this:

```py
class Point2D:
    """A class to represent points in a 2D space."""

    def __init__(self, x, y):
        self.x = x
        self.y = y

    def __str__(self):
        """Provide a good-looking representation of the object."""
        return f"({self.x}, {self.y})"

    def __repr__(self):
        """Provide an unambiguous way of rebuilding this object."""
        return f"Point2D({repr(self.x)}, {repr(self.y)})"

p = Point2D(0, 0) # the origin.
print(f"To build the point {p} in your code, try writing {repr(p)}.")
```

Running this code prints
`To build the point (0, 0) in your code, try writing Point2D(0, 0).` to your console.
Your end user may be accustomed to 2D points, and thus they may need nothing more than
the standard `(x, y)` representation of a 2D point.
During debugging, the `Point2D` prefix is useful because it helps you distinguish
between a tuple and a custom `Point2D` instance.

# Conclusion

When implementing custom classes you will probably want to give a custom implementation
of the `__repr__` dunder method, and also a `__str__` if you need your instances to
look good when printed to the end user.
`__str__` and `str` are used when you need good looking strings,
while the purpose of `__repr__` and `repr` is to create unambiguous representations
of your objects.

---

If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.

Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

# References

 - Python 3 Documentation, The Python Language Reference, Data model, __repr__ and __str__, [https://docs.python.org/3/reference/datamodel.html#object.__repr__](https://docs.python.org/3/reference/datamodel.html#object.__repr__).
 - Python 3 Documentation, The Python Standard Library, Built-in Functions, [https://docs.python.org/3/library/functions.html](https://docs.python.org/3/library/functions.html).
 - Python 3 Documentation, The Python Standard Library, Built-in Types, str, [https://docs.python.org/3/library/stdtypes.html#str](https://docs.python.org/3/library/stdtypes.html#str).
 - PEP 3140 -- str(container) should call str(item), not repr(item), [https://www.python.org/dev/peps/pep-3140/][pep3140].
 - Stack Overflow, "Purpose of Python's __repr__", [https://stackoverflow.com/questions/1984162/purpose-of-pythons-repr](https://stackoverflow.com/questions/1984162/purpose-of-pythons-repr).
 - dbader.org, "Python String Conversion 101: Why Every Class Needs a “repr”", [https://dbader.org/blog/python-repr-vs-str](https://dbader.org/blog/python-repr-vs-str).

Online references last consulted on the 2nd of February of 2021.

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[pep3140]: https://www.python.org/dev/peps/pep-3140/
