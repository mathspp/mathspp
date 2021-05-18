---
metadata:
    description: "This Pydon't covers the inner workings of sequence slicing, from the built-in `slice` type to the dunder method `__getitem__` and its siblings."
title: Inner workings of sequence slicing | Pydon't
---

In this Pydon't we conclude the slicing trilogy and
take a look at the inner workings of Python slicing,
from the built-in `slice` type to the dunder method
`__getitem__` and its siblings.

===

![A Python code snippet unveiling a bit of the inner workings of slicing.](thumbnail.svg)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

We have written two Pydon'ts already on sequence slicing:

 1. [“Idiomatic sequence slicing”][idiomatic-sequence-slicing]; and
 2. [“Mastering sequence slicing”][mastering-sequence-slicing].

Those two Pydon'ts taught you almost everything there is to know
about sequence slicing,
but there is something that we will only take a look at today:

 - uncovering the two layers of syntactic sugar surrounding sequence slicing; and
 - seeing how to implement slicing for your custom objects.

If you don't really know how sequence slicing works,
you might want to take a look at the Pydon'ts I linked above.
In particular, the Pydon't on [mastering sequence slicing][mastering-sequence-slicing]
can really help you take your Python slicing skills to the next level.

Without further ado, let us begin!

# The `slice` class

I don't know if you know this, but Python has, in its amazing documentation,
a [section devoted to its built-in functions][docs-builtin-functions].
In there, you can find things like `bool`, `enumerate`, or `len`.
If you take a look at the built-in functions that start with “s”,
you will find [`slice`][docs-slice] in there!

Taking a look at the docs about `slice`, we find it shows up in a way
that is similar to `int` or `str`, which means that a `slice` defines
a type of object we can have in our programs:
much like `int(3)` creates an integer `3` or `str(3)` creates a string `"3"`,
`slice(3)` creates a slice:

```py
>>> print(slice(3))
slice(None, 3, None)
```

This is the first level of syntactic sugar we are uncovering in this Pydon't:
Python uses these `slice` objects when we write things like `s[2::3]`!
But first, let us explore the `slice` objects a bit more.


## Slicing parameters

If we read the docs, or if we play around with the `slice` built-in enough,
we find out that this object stores the slicing parameters that
we repeatedly talked about in the previous Pydon'ts.
These parameters are the start, stop, and step, parameters of the slice,
and the docs tell us that we can access them:

```py
>>> sl = slice(1, 12, 3)
>>> sl.start
1
>>> sl.stop
12
>>> sl.step
3
```

However, we _cannot_ modify them:

```py
>>> sl = slice(None, 3, None)
>>> print(sl.start)
None
>>> sl.start = 0
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
AttributeError: readonly attribute
```


## Relationship with `range`

Another really important thing here lies in noting that this relationship that I tried
to make apparent, between slicing and sets of indices specified by `range`,
isn't just coincidental.
No, the documentation specifically says that `slice(start, stop, step)` represents the indices
specified by `range(start, stop, step)`.
This is why it is so helpful to understand the relationship between doing `s[start:stop:step]`
and something (much!) more verbose that makes use of a `for` loop and the corresponding `range`:

```py
>>> s = "Slicing is easy!"
>>> print(s[1:15:3])
li  s
>>> start, stop, step = 1, 15, 3
>>> result = ""
>>> for idx in range(start, stop, step):
...     result += s[idx]
...
>>> print(result)
li  s
```


## Explicit slices instead of colons

We have seen that we can create explicit `slice` objects, but can we use them..?
Of course we can!
I have been talking about syntactic sugar, and this is where it shows up:
writing `s[start:stop:step]` or `s[sl]`, where `sl` is the appropriate slice,
is the same thing!

Here are two examples of this:

```py
>>> s = "Slicing is easy!"
>>> s[1:15:2]
'lcn ses'
>>> sl = slice(1, 15, 2)
>>> s[sl]
'lcn ses'
>>> s[2::3]
'iniey'
>>> sl = slice(2, None, 3)
>>> s[sl]
'iniey'
```

Notice how, in the example above, we use `None`, when creating a `slice` object,
in order to specify an implicit slicing parameter,
such as the omitted stop parameter in the slice `s[2::3]`,
that would go between the two colons.

! By the way, careful with naming your `slice` objects!
! The most obvious name is `slice`, but if you create a slice
! with that name then you will have a hard time creating other
! `slice` objects because you will overwrite the name of the built-in type.
! This is also why you shouldn't name your strings `str` or your integers `int`.


# Getting items from sequences

We have seen that `slice` objects can be used to extract slices
from sequences in the same way as when we use the syntactic sugar
with the colons...
But how, exactly, are these things used to extract elements from sequences?
Tangent to this question, how would I implement slicing capabilities
in my own objects?

The answer lies in the `__getitem__` dunder method.

!!! Recall that “dunder” is short for “double underscore”,
!!! the common name that Python gives to methods that start
!!! and end with two underscores, which generally indicate
!!! that the method has to do with the inner workings of Python.
!!! We have seen other dunder methods in the Pydon'ts about
!!! [`str` and `repr`][str-and-repr] and about
!!! [Truthy, Falsy, and `bool`][bool].

The [`__getitem__` dunder method][getitem-docs] is the method that is called,
behind the scenes, when you try to access indices or slices.
An empirical verification of this is very easy to perform:
we'll just create a new class, called `S`,
that will be wrapping the built-in strings,
and intercept the `__getitem__` call:

```py
>>> class S(str):
...     def __getitem__(self, idx):
...         print("Inside __getitem__")
...         # Just let the built-in string handle indexing:
...         super().__getitem__(idx)
...
>>> s = S("Slicing is easy!")
>>> s[3]
Inside __getitem__
>>> s[1::2]
Inside __getitem__
```

This shows that the `__getitem__` method is the one that is responsible
for indexing sequences.

!!! The line that starts with `super()` is letting the built-in
!!! `str` class handle the indexing for us,
!!! given that our goal was just to verify that the `__getitem__`
!!! method is called.

Now, instead of just printing an irrelevant message,
we could actually print the index (or slice!) that is about
to be used:

```py
>>> class S(str):
...     def __getitem__(self, idx):
...         print(f"The argument was: {idx}")
...         # Just let the built-in string handle indexing:
...         super().__getitem__(idx)
...
>>> s = S("Slicing is easy!")
>>> s[3]
The argument was: 3
>>> s[1::2]
The argument was: slice(1, None, 2)
```

As you can see above, we tried slicing the string with `s[1::2]`
and that was converted to `slice(1, None, 2)` by the time
it got to the `__getitem__` call!

This shows the two bits of syntactic sugar going on:
using the colon syntax for slices, `start:stop:step`,
is just syntactic sugar for creating an explicit `slice` object,
and using brackets `[]` to index/slice is just syntactic sugar
for a call to the `__getitem__` function:

```py
>>> s = "Slicing is easy!"
>>> s[1::3]
'li  s'
>>> s.__getitem__(slice(1, None, 3))
'li  s'
```

This shows that you can use indexing/slicing in your own custom
objects if you implement the `__getitem__` method for your own objects.
I will show you an example of this below.


# Setting items, deleting items, and container emulation

In the Pydon't about [mastering sequence slicing][mastering-sequence-slicing]
we also saw how to do slicing assignment and how to delete
slices of sequences.
To do that in your own objects you have to deal with the
`__setitem__` and `__delitem__` methods,
whose signature is similar to `__getitem__`.
Just take a look at [the docs][container-emulation] if you want to learn
more about these methods or if you are looking at implementing
custom classes that emulate built-in container types.


# Comma-separated indices and slices

I would like to point out another cool thing that you can find
if you dig “deep” enough in the documentation (see [here][slices-grammar]),
or that you probably already encountered if you use other
modules like `numpy` or `pandas`.
This “thing” is the fact that you can write several indices/slices
if you separate them by commas.

Syntactically, that is perfectly valid.
That is, you can write something like that and Python will accept it.
However, Python's built-in types do not support multiple indexing or
slicing, so the built-in types do end up screaming at you:

```py
>>> s = "Slicing is easy!"
>>> s[1, 2, 3, 4:16:2] 
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: string indices must be integers
```

Python complained, but not about the syntax.
It is strings that cannot handle the indices,
and the extra slice,
that you gave to the `__getitem__` setting.
Compare this with an actual `SyntaxError`:

```py
>>> for in range(10):
  File "<stdin>", line 1
    for in range(10):
        ^
SyntaxError: invalid syntax
```

I couldn't even change lines to continue my make-believe
`for` loop, Python outright complained about the syntax being wrong.

However, in your _custom_ objects,
you can add support for multiple indexing/slicing:

```py
>>> class Seq:
...     def __getitem__(self, idx):
...         print(idx)
...
>>> s = Seq()
>>> s[1, 2, 3, 4:16:2]
(1, 2, 3, slice(4, 16, 2))
```

As you can see, the multiple indices and slices get packed into a tuple,
which is then passed in to `__getitem__`.

We have taken a look at how slices work under the hood,
and also took a sneak peek at how regular indexing works,
and now we will go through a couple of examples in code
where these things could be helpful.


# Examples in code

Bear in mind that it is likely that you won't be using explicit
`slice` objects in your day-to-day code.
The scarcity of usage examples of `slice` in the
Python Standard Library backs my claim.

Most usages of `slice` I found were for testing other objects'
implementations, and then I found a couple (literally two)
usages in the `xml` module, but to be completely honest
with you, I did not understand why they were being used!
(Do let me know if you can explain to me what is happening there!)


## `itertools.islice`

The first example we will be using is from the `itertools` module's
`islice` function.
The `islice` function can be used to slice into an iterator,
much like regular slicing, with two key differences:

 - `islice` does _not_ work with negative parameters; and
 - `islice` works with generic iterables,
 which is the main reason why `islice` is useful.

! Iterables and generators are fascinating things in Python
! and _there will be_ future Pydon'ts on this subject.
! [Stay tuned][subscribe] for those.

Without going into too much detail about the iterables,
let me show you a clear example of when regular slicing
doesn't work but `islice` works:

```py
>>> f = lambda x: x     # function that returns its input.
>>> f(3)
3
>>> f([1, 2, "Hey"])
[1, 2, 'Hey']
>>> s = "Slicing is easy!"
>>> s[2::3]
'iniey'
>>> m = map(f, s)       # `m` is an iterable with the characters from `s`.
>>> m[2::3]             # regular slicing doesn't work...
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: 'map' object is not subscriptable
>>> import itertools
>>> for char in itertools.islice(m, 2, None, 3):
...     print(char)
...
i
n
i
e
y
```

The example above just shows that `islice` works in some
situations where regular slicing with `[start:stop:step]` doesn't.
The documentation for `islice` provides an approximate Python implementation
of `islice` (the actual function is written in C):

```py
# From https://docs.python.org/3/library/itertools.html#itertools.islice, on the 18th of May 2021
def islice(iterable, *args):
    # (Some comments removed for brevity...)
    s = slice(*args)
    start, stop, step = s.start or 0, s.stop or sys.maxsize, s.step or 1
    it = iter(range(start, stop, step))
    # (Code sliced for brevity, pun much intended.)
    # ...
```

In the example above, the `slice` object is being used
just as an utility to map the arguments given to `islice`
as the parameters that need to go into the `range`
in the third code line of the example.

Another noteworthy thing is the line that assigns to
`start, stop, step` with the `or` operators.
The `or` is being used to assign default values to the parameters,
in case the original argument as `None`:

```py
>>> start = 4       # If `start` has a value,
>>> start or 0      # then we get that value.
4
>>> start = None    # However, if `start` is `None`,
>>> start or 0      # then we get the default value of `0`.
0
# Similarly for the `stop` and `step` parameters;
# here is another example with `stop`:
>>> import sys
>>> stop = 4
>>> stop or sys.maxsize
4
>>> stop = None
>>> stop or sys.maxsize
9223372036854775807
```

! The short-circuiting capabilities of the `or` operator
! (and also of the `and`) will be discussed in detail
! in a later Pydon't, don't worry!

To conclude this example, we see that `slice` can be useful
in the niche use-case of dispatching `range`-like arguments
to their correct positions, because you can read the parameters
off of a `slice` object.


## Custom arithmetic and geometric sequences

In this example I will be showing you a simple example
implementation of a custom object that supports slicing.
For that, we will implement a class for the concept
of geometric progression (see [Wikipedia][geometric-prog]):
a progression that is defined by two parameters:

 - the starting number `s`; and
 - the ratio `r`.

The first number of the progression is `s`,
and each subsequent item is just `r` times the previous one.
Here is how you would create the skeleton for such a concept:

```py
class GeometricProgression:
    def __init__(self, start, ratio):
        self.start = start
        self.ratio = ratio

    def __str__(self):
        return f"GeometricProgression({self.start}, {self.ratio})"

gp = GeometricProgression(1, 3)
print(gp)   # prints GeometricProgression(1, 3)
```

Now, geometric progressions have infinite terms, so we cannot
really just generate “all terms” of the progression and return them
in a list or something like that, so if we want to support
indexing and/or slicing, we need to do something else...
We need to implement `__getitem__`!

Let us implement `__getitem__` in such a way that it returns a list
with all the elements that the user tried to fetch:

```py
import sys

class GeometricProgression:
    def __init__(self, start, ratio):
        self.start = start
        self.ratio = ratio

    def __str__(self):
        return f"GeometricProgression({self.start}, {self.ratio})"

    def nth(self, n):
        """Compute the n-th term of the progression, 0-indexed."""
        return self.start*pow(self.ratio, n)

    def __getitem__(self, idx):
        if isinstance(idx, int):
            return self.nth(idx)
        elif isinstance(idx, slice):
            start, stop, step = idx.start or 0, idx.stop or sys.maxsize, idx.step or 1
            return [self.nth(n) for n in range(start, stop, step)]
        else:
            raise TypeError("Geo. progression indices should be integers or slices.")

gp = GeometricProgression(1, 3)
print(gp[0])        # prints 1
print(gp[1])        # prints 3
print(gp[2])        # prints 9
print(gp[0:3])      # prints [1, 3, 9]
print(gp[1:10:3])   # prints [3, 81, 2187]
```

As you can see, our implementation already supports slicing and indexing,
but we can take this just a little bit further,
and add support for multiple indices/slices with ease:

```py
import sys

class GeometricProgression:
    def __init__(self, start, ratio):
        self.start = start
        self.ratio = ratio

    def __str__(self):
        return f"GeometricProgression({self.start}, {self.ratio})"

    def nth(self, n):
        """Compute the n-th term of the progression, 0-indexed."""
        return self.start*pow(self.ratio, n)

    def __getitem__(self, idx):
        if isinstance(idx, int):
            return self.nth(idx)
        elif isinstance(idx, slice):
            start, stop, step = idx.start or 0, idx.stop or sys.maxsize, idx.step or 1
            return [self.nth(n) for n in range(start, stop, step)]
        elif isinstance(idx, tuple):
            return [self.__getitem__(sub_idx) for sub_idx in idx]
        else:
            raise TypeError("Geo. progression indices should be integers or slices.")

gp = GeometricProgression(1, 3)
print(gp[0, 1, 4])              # prints [1, 3, 81]
print(gp[0:2, 0:2, 1, 0:2])     # prints [[1, 3], [1, 3], 3, [1, 3]]
```

And that is it, this shows you a (simple) working example of how you could
define indexing and slicing into your own objects.

!!! You can find this simple implementation [on GitHub][geometric-prog-gh],
!!! in case you need it.

# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Sequence slicing hides two layers of syntactic sugar for you,
 but you do need to know about them if you want to write custom
 objects that support indexing and/or slicing.*”

This Pydon't showed you that:

 - there is a built-in `slice` type in Python;
 - the syntax `[start:stop:step]` is just syntactic sugar for `slice(start, stop, step)`;
 - `slice(start, stop, step)` represents the indices of `range(start, stop, step)`;
 - when you use `seq[]` to index/slice into `seq`,
 you actually call the `__getitem__` method of `seq`;
 - `__getitem__`, `__setitem__`, and `__delitem__`, are the three methods that you would
 need in custom objects to emulate indexing, indexing assignment and indexing deletion;
 - Python syntax _allows_ for multiple indices/slices separated by commas;
 - `itertools.islice` can be used with iterables, whereas plain slicing cannot; and
 - it can be fairly straightforward to implement (multiple) indexing/slicing for your
 own objects.

If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[idiomatic-sequence-slicing]: /blog/pydonts/idiomatic-sequence-slicing
[mastering-sequence-slicing]: /blog/pydonts/mastering-sequence-slicing
[islice]: https://docs.python.org/3/library/itertools.html#itertools.islice
[docs-builtin-functions]: https://docs.python.org/3/library/functions.html
[docs-slice]: https://docs.python.org/3/library/functions.html#slice
[slices-grammar]: https://docs.python.org/3/reference/expressions.html#slicings
[str-and-repr]: /blog/pydonts/str-and-repr
[bool]: https://mathspp.com/blog/pydonts/truthy-falsy-and-bool
[getitem-docs]: https://docs.python.org/3/reference/datamodel.html#object.__getitem__
[itertools]: https://docs.python.org/3/library/itertools.html#itertools.islice
[container-emulation]: https://docs.python.org/3/reference/datamodel.html#emulating-container-types
[geometric-prog]: https://en.wikipedia.org/wiki/Geometric_progression
[geometric-prog-gh]: https://github.com/RojerGS/projects/blob/master/misc/geometric_progression.py
