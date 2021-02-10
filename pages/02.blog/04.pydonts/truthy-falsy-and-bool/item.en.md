---
metadata:
    description: "Learn what it means for Python objects to have 'Truthy' and 'Falsy' values."
title: "Truthy, Falsy, and bool | Pydon't"
---

All Python objects can be used in expressions that should
return a boolean value, like in an `if` or `while` statement.
Python's built-in objects are usually Falsy (interpreted as `False`)
when they are “empty” or have “no value” and otherwise they
are Truthy (interpreted as `True`).
You can define this behaviour explicitly for your own
objects if you define the `__bool__` dunder method.

===

![A Python code snippet comparing some if statements.](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)

# “Truthy” and “Falsy”

Quoting the Python documentation,

 > “Any object can be tested for truth value, for use in an if or while condition or as operand of the Boolean operations below [`or`, `and` and `not`].”

What does that mean?
It means that we can use any Python object we want whenever a boolean value is expected.
Boolean values (`True` and `False`) are used in conditions,
which pop up in `if` statements and `while` statements, as well as in expressions
that make use of the Boolean operators `or`, `and` and `not`.

As a very basic example, consider this Python session:

```py
>>> if True:
...     print("Hello, World!")
...
Hello, World!
>>> if False:
...     print("Go away!")
...
>>>
```

This piece of code should not surprise you, as it is very standard Python code:
there are a couple of `if` statements that make use of explicit Boolean values.
The next step is using an expression that *evaluates* to a Boolean value:

```py
>>> 5 > 3
True
>>> if 5 > 3:
...     print("Hello, World!")
... 
Hello, World!
```

The *next* step is using an object that is **not** a Boolean value,
which is what this blog post is all about:

```py
>>> l = [1, 2, 3]
>>> if l:
...     print(l)
... 
[1, 2, 3]
```

*This* is the part that could be surprising if you have never encountered it.
The reason this `if` statement is getting executed is because the list
`[1, 2, 3]` is *Truthy*, that is, the list `[1, 2, 3]` can be interpreted
as `True` in a Boolean context.
How can you know if an object is “Truthy” or “Falsy”?
The simplest way is to use the built-in `bool` function that converts any
Python object to a Boolean:

```py
>>> bool(l)
True
```

The way this works is really simple!
There are a couple of rules that specify how this works,
but these simple rules can even be simplified further with a simpler heuristic:

 > “A value of a given type if Falsy when it is “empty” or “without any useful value”.”

Examples of built-in types and their Falsy values include the empty list,
empty set, empty tuple, empty dictionary, the number `0`, `None` and the empty string.
For example:

```py
>>> bool([])
False
>>> bool("")
False
```

Of course, "without any useful value" definitely depends on what you intend
to do with the value you have, so I should really specify the objective rules:

 - By default, a value is Truthy (that is, is interpreted as `True`).
 - An object has a Falsy value (that is, is interpreted as `False`)
if calling `len` on it returns `0`.

Notice that the previous rule tells us that, in general, types that are containers
or sequences (types of objects for which it generally makes sense to use `len` on),
are considered Falsy when they are empty, i.e., when they have length equal to zero.
But there is one more case that gives a Falsy value:

# The `__bool__` Dunder Method

 - An object has a Falsy value (that is, is interpreted as `False`) if
it defines a `__bool__` method that returns `False`.

`__bool__` is a *dunder* method (dunder stands for double underscore)
that you can use to tell your objects if they are Truthy or Falsy
in Boolean contexts, by implementing it in your own classes.
(You have seen [other dunder methods][str-and-repr-pydont] already.)

! If you are not acquainted with Python's dunder methods, you may want to [subscribe]
! to the Pydon't newsletter, I will write more about them later.
! Until then, you may want to have a look at the Python 3 Docs and what they say
! about the [data model](https://docs.python.org/3/reference/datamodel.html#data-model).

Here is a simple example showing an object that is always taken to be Truthy:

```py
>>> class A:  
...     pass
... 
>>> a = A()
>>> if a:
...     print("Hello, World!")
... 
Hello, World!
```

On the opposite end, we can consider a class whose objects will always 
be taken to be Falsy:

```py
>>> class A:
...     def __bool__(self):
...             return False
...
>>> a = A()
>>> if a:
...     print("Go away!")
...
```

In general, your use case may be such that your object sometimes
is Truthy and sometimes is Falsy.

Finally, it is very important to state the order in which the rules
apply!

! When given an arbitrary Python object that needs to be tested for
a truth value, Python first tries to call `bool` on it, in an attempt
to use its `__bool__` dunder method.
! If the object does not implement a `__bool__` method, then Python
tries to call `len` on it.
! Finally, if that also fails, Python defaults to giving a Truthy
value to the object.

# Remarks

Now a couple of remarks about the functioning of Truthy and Falsy values.

## A Note About Containers With Falsy Objects

We said that things like the empty list, zero,
and the empty dictionary are Falsy.
However, things like a list that only contains zeroes or a dictionary composed
of zeroes and empty lists are not Falsy, because the containers themselves are no
longer empty:

```py
>>> bool([])
False
>>> bool({})
False
>>> bool(0)
False
>>> bool([0, 0, 0]) # A list with zeroes is not an empty list.
True
>>> bool({0: []})   # A dict with a 0 key is not an empty dict.
True
```

## A Note About Checking For `None`

As mentioned above, `None` is Falsy:

```py
>>> bool(None)
False
>>> if None:
...     print("Go away!")
...
```

This seems about right, as `None` is the go-to value to be returned
by a function when the function does nothing.

Imagine someone implemented the following function to return the integer
square root of a number, returning `None` for negative
inputs (because negative numbers do not have a square root
in the usual sense):

```py
import math
def int_square_root(n):
    if n < 0:
        return None
    return math.floor(math.sqrt(n))
```

When you use the function above you know it returns `None` if the
computation fails, so now you might be tempted to use your newfound
knowledge about the Falsy value of `None`, and you might write
something like the following, to check if the computation succeeded:

```py
n = int(input("Compute the integer square root of what? >> "))
int_sqrt = int_square_root(n)
if not int_sqrt:
    print("Negative numbers do not have an integer square root.")
```

Now, what happens if `n` is `0` or `0.5`?

```py
>>> n = 0.5
>>> int_sqrt = int_square_root(n)
>>> if not int_sqrt:
...     print("Negative numbers do not have an integer square root.")
... 
Negative numbers do not have an integer square root
```

Which is clearly wrong, because `n = 0.5` is certainly positive.
Let us inspect `int_sqrt`:

```py
>>> int_sqrt
0
```

The problem is that `int_square_root` returned a meaningful value
(that is, it did not return `None`) but that meaningful value
is still Falsy.
When you want to check if a function returned `None` or not,
do not rely on the Truthy/Falsy value of the return value.
Instead, check explicitly if the return value is `None` or not:

```py
# Use                       # Avoid
if returned is None:        # if not returned:
    # ...                   #     # ...
if returned is not None:    # if returned:
    # ...                   #     # ...
```

This recommendation is to avoid problems like the one outlined above.

# Examples in Code

Now I will show you some examples of places where using the Truthy
and Falsy values of Python objects allows you to write more Pythonic
code.

## 2D Point

Let us implement a simple class to represent points in a 2D plane,
which could be an image, a plot or something else.
Retrieving what we already had [in the article about `__str__`
and `__repr__`][str-and-repr-pydont], we can add a `__bool__` method
so that the origin (the point `Point2D(0, 0)`) is Falsy and all other
points are Truthy:

```py
# Retrieved from https://mathspp.com/blog/pydonts/pydont-confuse-str-and-repr
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

    def __bool__(self):
        """The origin is Falsy and all other points are Truthy."""
        return self.x or self.y

print(bool(Point2D(0, 1))) # True
print(bool(Point2D(0, 0))) # False
print(bool(Point2D(1, 0))) # True
print(bool(Point2D(4, 2))) # True
```

Notice how we defined the Truthy/Falsy value of the `Point2D`
in terms of the Truthy/Falsy values of its components!
We want the `Point2D` to be Falsy when `self.x` is `0` and
`self.y` is also `0`, which means a `Point2D` is Truthy
if any of `self.x` or `self.y` are Truthy (that is,
different from `0`)!

## Handling Error Codes or Error Messages

It is quite common for functions to return “error codes”:
integers that encode specific things that did not go quite right,
or for such functions to return error messages as strings
when things don't go right.
These error codes are usually such that returning `0` means
everything went ok, while different other integers
can mean all sorts of problems.

If you are calling such a function, you can use the Truthy
value of strings and/or integers to check if something
went wrong, and to handle it accordingly.

As a generic example, this is the pattern we are looking for:

```py
return_value, error_code = some_nice_function()
if error_code:
    # Something went wrong, act accordingly.

# Alternatively, something like:
return_value, error_msg = some_other_nice_function()
if error_msg:
    print(error_msg)
    # Something went wrong, act accordingly.
```

## Processing Data

It is also very common to use Truthy and Falsy values to measure
if there is still data to be processed.

For example, [when I talked about the walrus operator `:=`][walrus-pydont],
we saw a `while` loop vaguely similar to this one:

```py
input_lines = []
while (s := input()):
    input_lines.append(s)
# No more lines to read.
print(len(input_lines))
```

This `while` loop essentially reads input lines *while* there are lines
to be read.
As soon as the user inputs an empty line `""`,
the loop stops and we print the number of lines we read:

```py
>>> input_lines = []
>>> while (s := input()):
...     input_lines.append(s)
... 
Line 1
Line 2

>>> print(len(input_lines))
2
```

Another common pattern is when you have a list that contains some
data that you have to process,
and such that the list itself gets modified as you process the data.

Consider the following example:

```py
import pathlib

def print_file_sizes(dir):
    """Print file sizes in a directory, recursing into subdirectories."""

    paths_to_process = [dir]
    while paths_to_process:
        path, *paths_to_process = paths_to_process
        path_obj = pathlib.Path(path)
        if path_obj.is_file():
            print(path, path_obj.stat().st_size)
        else:
            paths_to_process += path_obj.glob("*")
```

This is not necessarily the way to go about doing this, *but*
notice the `while` statement, and then the `if: ... else: ...`
block that either prints something, or extends the `paths_to_process` list.


# Conclusion

 - Python's Truthy and Falsy values allow you to rewrite common
conditions in a way that is more readable and, therefore, Pythonic.
 - You can implement your own Truthy and Falsy values in custom
classes by implementing the `__bool__` dunder method.
 - You should also be careful when checking if a given variable
is `None` or not, and avoid using the Falsy value of `None`
in those particular cases.

If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.

Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

# References

 - Python 3 Documentation, The Python Language Reference, Data model, __bool__, [https://docs.python.org/3/reference/datamodel.html#object.__bool__](https://docs.python.org/3/reference/datamodel.html#object.__bool__).
 - Python 3 Documentation, The Python Standard Library, Truth Value Testing, [https://docs.python.org/3/library/stdtypes.html#truth-value-testing](https://docs.python.org/3/library/stdtypes.html#truth-value-testing).
 - Python 3 Documentation, The Python Standard Library, Built-in Functions, `bool`, [https://docs.python.org/3/library/functions.html#bool](https://docs.python.org/3/library/functions.html#bool).
 - PEP 8 -- Style Guide for Python Code, [https://www.python.org/dev/peps/pep-0008/][pep8].
 - Python 3 Documentation, The Python Standard Library, File and Directory Access, `pathlib`, [https://docs.python.org/3/library/pathlib.html](https://docs.python.org/3/library/pathlib.html).
 - Stack Overflow, Listing of all files in directory?, [https://stackoverflow.com/a/40216619/2828287](https://stackoverflow.com/a/40216619/2828287).
 - Stack Overflow, How can I check file size in Python?, [https://stackoverflow.com/a/2104107/2828287](https://stackoverflow.com/a/2104107/2828287).
 - freeCodeCamp, Truthy and Falsy Values in Python: A Detailed Introduction, [https://www.freecodecamp.org/news/truthy-and-falsy-values-in-python/](https://www.freecodecamp.org/news/truthy-and-falsy-values-in-python/).

Online references last consulted on the 9th of February of 2021.

[subscribe]: https://mathspp.com/subscribe
[pep8]: https://www.python.org/dev/peps/pep-0008/
[manifesto]: /blog/pydonts/pydont-manifesto
[str-and-repr-pydont]: /blog/pydonts/pydont-confuse-str-and-repr
[walrus-pydont]: /blog/pydonts/pydont-abuse-the-walrus-operator
