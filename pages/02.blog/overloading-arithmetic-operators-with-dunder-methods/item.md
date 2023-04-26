This article shows you how to overload the arithmetic operators in Python with dunder methods.

===

![](thumbnail.png)

# Introduction

Python lets you override the arithmetic operators like `+` for addition or `*` for multiplication through [dunder methods][dunder-methods].
[Dunder methods][dunder-methods] are special methods whose name starts and ends with a double underscore (hence, “dunder”), and some [dunder methods][dunder-methods] are specific to arithmetic operations.

In this article, you will learn:

 - how to implement unary arithmetic operators:
   - negation (`-p`);
   - (`+p`);
   - absolute coord (`abs(p)`); and
   - inverse (`~`).
 - how to implement binary arithmetic operators:
   - addition (`+`);
   - subtraction (`-`);
   - multiplication (`*`);
   - division (`/`);
   - floor division (`//`);
   - modulo (`%`);
   - (`divmod`); and
   - exponentiation (`pow`).
 - how to implement binary arithmetic operators for bitwise operations:
   - left shift (`<<`);
   - right shift (`>>`);
   - bitwise and (`&`);
   - bitwise or (`|`);
   - bitwise xor (`^`);
 - what are the reflected arithmetic dunder methods;
 - what are the augmented arithmetic dunder methods;
 - what `NotImplemented` is and how it differs from `NotImplementedError`; and
 - how Python determines which arithmetic dunder method to call.

On top of this, we will also provide a working example of a class that implements these methods.


# The example we will be using

The example we will be using throughout this article will be that of a `Vector`.
A `Vector` will be a class for geometrical vectors, like vectors in 2D, or 3D, and it will provide the arithmetic operations to deal with vectors.
For example, by the end of this article, you will have an implementation of `Vector` that lets you do things like these:

```pycon
>>> from vector import Vector
>>> v = Vector(3, 2)
>>> v + Vector(4, 10)
Vector(7, 12)
>>> 3 * v
(9, 6)
>>> -v
(-3, -2)
```

Let us go ahead and start!

This is the starting vector for our class `Vector`:

```py
# vector.py
class Vector:
    def __init__(self, *coordinates):
        self.coordinates = coordinates

    def __repr__(self):
        return f"Vector{self.coordinates}"


if __name__ == "__main__":
    print(Vector(3, 2))
```

Running this code will show this output:

```
Vector(3, 2)
```

This starting vector also shows two dunder methods that we are using right off the bat:

 1. we use [the dunder method `__init__`][dunder-init] to initialise our `Vector` instance; and
 2. we use [the dunder method `__repr__`][dunder-repr] to provide a string representation of our `Vector` objects.

This shows that dunder methods are **not** magical.
They look funny because of the leading and trailing underscores in their names, but they are regular Python methods that Python calls automatically.

We will start by covering the unary arithmetic operations because those are simpler.
Then, we will move along to the binary arithmetic operations.
Good luck! 🐍🚀


# Your first arithmetic dunder method

Take a look at this piece of code:

```pycon
>>> x = 73
>>> -x
-73
```

Does the result surprise you?
Probably not!

Now, look at this:

```pycon
>>> x = 73
>>> x.__neg__()
-73
```

Was this surprising?
The method `__neg__` is the dunder method that is responsible for implementing the unary operation of negation.

Currently, our class `Vector` does not have support for the operation of negation:

```pycon
>>> p = Vector(1, 2)
>>> -p
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: bad operand type for unary -: 'Vector'
```

If we implement a method `__neg__` in our class `Vector`, we can add support for this operation.
The method `__neg__` only accepts the argument `self` and should return the result of negating `self`.
For illustrative purposes, we can start by implementing a method `__neg__` that always returns `"Hello, world!"`:

```py
class Vector:
    ...

    def __neg__(self):
        return "Hello, world!"
```

Now, we can use the unary operation minus:

```pycon
>>> -Vector(1, 2)
'Hello, world!'
```

Of course, it doesn't make much sense for the negation of a vector to be the string `"Hello, world!"`.

The unary negation we will implement for real will negate each coordinate, one by one:

```py
class Vector:
    ...

    def __neg__(self):
        return Vector(*[-coord for coord in self.coordinates])
```

This is a more sensible operation, which enables this:

```pycon
>>> -Vector(1, -2, 3, -4)
Vector(-1, 2, -3, 4)
```

That's it!
This was your first arithmetic dunder method! 🎉

Now, we'll implement the remaining unary arithmetic operations.


# The dunder methods for the unary arithmetic operations

There are four unary arithmetic operations for a coord `p`:

 1. `-p` is negation and is implemented via `__neg__`;
 2. `+p` is implemented via `__pos__` (I have no idea what it's called!);
 3. `abs(p)` is the absolute coord and is implemented via `__abs__`; and
 4. `~p` is inversion and is implemented via `__invert__`.

These four dunder methods are all unary, which means the only argument they take is `self`, which is going to be the instance of `Vector` that they need to operate on.


## The dunder method `__neg__` for negation

This one was already implemented above!


## The dunder method `__pos__`

When used on integers and floats, `__pos__` acts as a no-op:

```pycon
>>> x = 73
>>> +x
73
>>> x.__pos__()
73

>>> x = -73
>>> +x
-73
>>> x.__pos__()
-73
```

So, we will do the same thing for vectors.
However, because the other unary arithmetic operations return _different_ instances, we will be sure to return a _different_ instance that has the same coordinates:

```py
class Vector:
    ...

    def __pos__(self):
        return Vector(*self.coordinates)

if __name__ == "__main__":
    p = Vector(1, 2, 3)
    print(+p)  # Vector(1, 2, 3)
    print((+p) is p)  # False
```


## The dunder method `__abs__` for the absolute coord

The dunder method `__abs__` is called when we use the built-in `abs`.

```pycon
>>> 1 + 2j
(1+2j)
>>> abs(1 + 2j)
2.23606797749979
>>> (1 + 2j).__abs__()
2.23606797749979
```

For our class `Vector`, we will return the _magnitude_ of the vector, which is the square root of the sum of the squares of all the coordinates.

```py
class Vector:
    ...

    def __abs__(self):
        return pow(sum(coord ** 2 for coord in self.coordinates), 0.5)

if __name__ == "__main__":
    p = Vector(1, 2)
    print(abs(p))
```


## The dunder method `__invert__` for inversion

The dunder method `__invert__` is called when the unary arithmetic operation `~` is used.
For integers, this operation is based on binary.
(Try to figure out what it does!)

For our class `Vector`, we can do whatever we want.
The operation I'm implementing is inspired by geometry.
It looks like this:

```py
class Vector:
    ...

    def __invert__(self):
        """Compute a vector that is orthogonal to this one."""
        if len(self.coordinates) <= 1:
            raise TypeError(
                f"Cannot invert vector of length {len(self.coordinates)}."
            )

        # Look for two non-zero coordinates to swap.
        to_flip = [0, 1]
        for idx, coord in enumerate(self.coordinates):
            if coord:
                to_flip.append(idx)

        # Zero out all coordinates...
        coordinates = [0] * len(self.coordinates)
        # except the two we are swapping out.
        coordinates[to_flip[-1]] = self.coordinates[to_flip[-2]]
        coordinates[to_flip[-2]] = -self.coordinates[to_flip[-1]]
        return Vector(*coordinates)
```

What does this do?
Given a vector, it will look for two coordinates that are _not_ zero and it will swap them out, while also flipping the sign in one of them.
All other coordinates of the result will be 0.

Here are some small examples:

```pycon
>>> ~Vector(1, 2, 3)
Vector(0, -3, 2)
>>> ~Vector(1, 0, 3)
Vector(-3, 0, 1)
>>> ~Vector(1, 0, 0)
Vector(0, -1, 0)
```

Here are some examples with longer vectors:

```pycon
>>> ~Vector(1, 2, 3, 4, 5, 6, 7)
Vector(0, 0, 0, 0, 0, -7, 6)
>>> ~Vector(1, 0, 0, 0, 5, 0, 0)
Vector(-5, 0, 0, 0, 1, 0, 0)
>>> ~Vector(0, 2, 0, 4, 0, 0, 7)
Vector(0, 0, 0, -7, 0, 0, 4)
```

This is not a random operation I came up with, it is something from geometry.
You can read [this Wikipedia article](https://en.wikipedia.org/wiki/Orthogonality) to learn about "orthogonal vectors".
This will also make more sense when we implement the dunder method `__matmul__`, later.


## Unary arithmetic operations summary

If you got to this point, it means you have implemented all unary arithmetic operations.
Good job! 🚀

Here is all the code up until this point:

```py
class Vector:
    def __init__(self, *coordinates):
        self.coordinates = coordinates

    def __repr__(self):
        return f"Vector{self.coordinates}"

    def __matmul__(self, other):
        return sum(c1 * c2 for c1, c2 in zip(self.coordinates, other.coordinates))

    def __neg__(self):
        return Vector(*[-coord for coord in self.coordinates])

    def __pos__(self):
        return Vector(*self.coordinates)

    def __abs__(self):
        return pow(sum(coord**2 for coord in self.coordinates), 0.5)

    def __invert__(self):
        """Compute a vector that is orthogonal to this one."""
        if len(self.coordinates) <= 1:
            raise TypeError(
                f"Cannot invert vector of length {len(self.coordinates)}."
            )

        # Look for two non-zero coordinates to swap.
        to_flip = [0, 1]
        for idx, coord in enumerate(self.coordinates):
            if coord:
                to_flip.append(idx)

        # Zero out all coordinates...
        coordinates = [0] * len(self.coordinates)
        # except the two we are swapping out.
        coordinates[to_flip[-1]] = self.coordinates[to_flip[-2]]
        coordinates[to_flip[-2]] = -self.coordinates[to_flip[-1]]
        return Vector(*coordinates)
```


# The dunder methods for binary arithmetic operations

Up until now, we dealt with _unary_ operators.
This means that the operator expected a _single_ object to work with.
As we delve into _binary_ operators, the dunder methods we will implement will take two arguments: `self` and `other`.

This will be explained right away, as we start implementing addition.


# Addition and the dunder method `__add__`

To implement addition between our `Vector` instances we need to implement the dunder method `__add__`.
When Python finds an expression like `a + b`, Python will try to run `a.__add__(b)`, which is why we can use the dunder method `__add__` to implement addition for our objects.

Because addition is a binary operator (you add _two_ things), the dunder method `__add__` takes two arguments:

 1. `self`; and
 2. the other thing that is being added, that we typically call `other`.

Remember:
`a + b` turns into `a.__add__(b)`, which is a regular method call!
So, `b` will be the “`other`” thing that we want to add to `self` and will be passed in as an argument.

For our `Vector` class, the signature of `__add__` looks like this:

```py
class Vector:
    ...

    def __add__(self, other):
        ...
```

Now, instead of `...` we just need to provide the actual implementation.
Adding `Vector` instances amounts to adding up all their respective coordinates:

```py
class Vector:
    ...

    def __add__(self, other):
        result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
        return Vector(*result_coordinates)


if __name__ == "__main__":
    print(Vector(3, 2) + Vector(10, 4))  # Vector(13, 6)
```

!!! I'm using a [list comprehension][list-comp] and the built-in [zip] to go over the respective coordinates of each `Vector` instance.

This is all it takes to implement a dunder method.


# Adding validation to your dunder methods

Now, the implementation we provided above is pretty barebones.
For example, it is going to raise an interesting error if we try to add a vector to an integer:

```pycon
>>> Vector(1, 2) + 3
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "/Users/rodrigogs/Documents/vector.py", line 9, in __add__
    result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
                                                        ^^^^^^^^^^^^
AttributeError: 'int' object has no attribute 'coordinates'
```

We get an error because we _assumed_ that `other` was going to be an instance of a `Vector`, but we tried to add a vector and an integer, and so our assumption didn't hold.
In general, you will want to use `isinstance` to make sure you can do the operation you really want to do:

```py
class Vector:
    ...

    def __add__(self, other):
        if isinstance(other, Vector):
            result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
            return Vector(*result_coordinates)
```

When we add this check, the error goes away entirely:

```pycon
>>> from vector import Vector
>>> Vector(1, 2) + 3
>>> # It didn't error?!
```

That is also not quite what we wanted.
What we would like to see is one of those `TypeError`s that the language raises when we mix types in the wrong way:

```pycon
>>> 3 + "3"
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: unsupported operand type(s) for +: 'int' and 'str'
```

How do we raise this error?
You might think of actually raising the error yourself, with `raise TypeError(...)`, but there is a built-in mechanism that does this.


# Using `NotImplemented` to flag operations you don't support

## The built-in constant `NotImplemented`

When there is a combination of arguments that you do not have support for, you need to return the built-in constant `NotImplemented`.

The `NotImplemented` constant is like `None` in the sense that it is built-in and that there is only one.
You don't instantiate `None` coordinates, you just use the coord `None`.
Similarly, you don't instantiate `NotImplemented` coordinates, you just use `NotImplemented`.

If you need to return `NotImplemented` if you do not know how to add the vector with the other argument, you need to modify the method `__add__` like so:

```py
class Vector:
    ...

    def __add__(self, other):
        if isinstance(other, Vector):
            result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
            return Vector(*result_coordinates)
        return NotImplemented
```

! You should _return_ the coord `NotImplemented`.
! Please, do not mistake this for _returning_ the coord `NotImplementedError` or for _raising_ the exception `NotImplementedError`.

When you return `NotImplemented`, you are telling Python that a vector cannot be added with whatever type `other` was, so Python will take care of raising the appropriate `TypeError` for you:

```pycon
>>> from vector import Vector
>>> Vector(1, 2) + 3
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: unsupported operand type(s) for +: 'Vector' and 'int'
```

You can even see this is what happens behind the curtains with some built-in types!
For example, `3 + "hello"` raises an error, but `(3).__add__("hello")` returns `NotImplemented`:

```pycon
>>> (3).__add__("hello")
NotImplemented
```


## The difference between `NotImplemented` and `NotImplementedError`

The two built-ins `NotImplemented` and `NotImplementedError` may look similar, but they have very distinct use cases.

The built-in _constant_ `NotImplemented` is used _only_ in the context of arithmetic dunder methods to tell Python that a specific operation can't be handled by a specific class, whereas the built-in _exception_ `NotImplementedError` is raised when you have defined the body of a function or a method to specify its signature, but you haven't implemented the behaviour yet.

This is useful, for example, when you use a class as an abstract base class and you specify the signatures of the methods that the subclasses will need, but you don't implement them because it is up to the subclasses to provide that behaviour.
Here is a short `Shape` example:

```py
class Shape:
    def __init__(self, name):
        self.name = name

    def area(self):
        raise NotImplementedError("Subclass must implement this method")

    def perimeter(self):
        raise NotImplementedError("Subclass must implement this method")
```

The `Shape` specifies that all subclasses of the class `Shape` must implement the methods `area` and `perimeter`:

```py
class Rectangle(Shape):
    def __init__(self, length, width):
        super().__init__("Rectangle")
        self.length = length
        self.width = width

    def area(self):
        return self.length * self.width

    def perimeter(self):
        return 2 * (self.length + self.width)

rect = Rectangle(5, 3)
print(rect.area()) # 15
print(rect.perimeter()) # 16
```

!!! Python has more complete mechanisms to handle abstract base classes (called interfaces, in other languages), but this small example illustrates the vector.


# Extending your dunder method to more types

Before taking this little tangent about the difference between `NotImplemented` and `NotImplementedError`, we saw that our vectors cannot be added to integers.
However, we wish to extend our implementation of `Vector` to handle integer and float addition.
To add an integer or a float to a `Vector` means that all coordinates of the `Vector` get shifted by the given amount.

To implement that behaviour, we need to add an extra branch to our `if` statement inside `__add__`:

```py
class Vector:
    ...

    def __add__(self, other):
        if isinstance(other, Vector):
            result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
            return Vector(*result_coordinates)
        elif isinstance(other, (int, float)):
            result_coordinates = [coord + other for coord in self.coordinates]
            return Vector(*result_coordinates)
        return NotImplemented
```

Now, `Vector` instances can be added to numbers!
It works with integers:

```py
class Vector:
    ...

if __name__ == "__main__":
    print(Vector(1, 2) + 3)  # Vector(4, 5)
```

It works with floats:

```py
class Vector:
    ...

if __name__ == "__main__":
    print(Vector(1, 2) + 3)  # Vector(4, 5)
    print(Vector(1, 2) + 4.5)  # Vector(5.5, 6.5)
```

And it even works backwards:

```py
class Vector:
    ...

if __name__ == "__main__":
    print(Vector(1, 2) + 3)  # Vector(4, 5)
    print(Vector(1, 2) + 4.5)  # Vector(5.5, 6.5)
    print(3 + Vector(1, 2))  # Raises: TypeError: unsupported operand type(s) for +: 'int' and 'Vector'
```

Huh?
What do you mean?
We _just_ implemented addition between instances of `Vector` and `int`...

Let us add a couple of print statements for debugging:

```py
class Vector:
    ...

    def __add__(self, other):
        print(f"About to add {self} with {other}")
        if isinstance(other, Vector):
            result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
            return Vector(*result_coordinates)
        elif isinstance(other, (int, float)):
            print(f"{other} is an int or a float!")
            result_coordinates = [coord + other for coord in self.coordinates]
            return Vector(*result_coordinates)
        return NotImplemented

if __name__ == "__main__":
    print("Vector plus int")
    print(Vector(1, 2) + 3)  # Vector(4, 5)
    print()
    print("Vector plus float")
    print(Vector(1, 2) + 4.5)  # Vector(5.5, 6.5)
    print()
    print("int plus Vector")
    print(3 + Vector(1, 2))  # Raises: TypeError: unsupported operand type(s) for +: 'int' and 'Vector'
```

Now, if we re-run the file, we see... Nothing!
This is the output:

```
Vector plus int
About to add Vector(1, 2) with 3
3 is an int or a float!
Vector(4, 5)

Vector plus float
About to add Vector(1, 2) with 4.5
4.5 is an int or a float!
Vector(5.5, 6.5)

int plus Vector
Traceback (most recent call last):
  File "/Users/rodrigogs/Documents/vector.py", line 29, in <module>
    3 + Vector(1, 2)
    ~~^~~~~~~~~~~~~
TypeError: unsupported operand type(s) for +: 'int' and 'Vector'
```

Notice that we get the error without seeing the prints from within `__add__`...
And you know why?
Well, obviously because `__add__` never got called.

Let me explain:


# Reflected dunder methods

To be precise, when your arithmetic dunder method returns `NotImplemented`, it tells Python that that specific method call failed.
For example, when `Vector.__add__` returns `NotImplemented`, it tells Python that the class `Vector` does not know how to add vectors with whatever was in the argument `other`.

However, when Python sees the return value `NotImplemented` coming out of an arithmetic dunder method, Python does not raise the `TypeError` exception immediately!
In fact, it will try to run a plan B, first.

When you write `a + b`, Python will start by trying to run `a.__add__(b)`.
If that fails (that is, if it returns `NotImplemented`), Python will then try to run `b.__radd__(a)`!

!!! Notice that I wrote `__radd__` with an extra `r`, and not just `__add__`.

`__radd__` is the “reflected dunder dunder `__add__`”, and it is like the plan B for addition.is from a subclass of the left operand.

So, when we wrote `3 + Vector(1, 2)`, Python started by trying to run `(3).__add__(Vector(1, 2))`, which returns `NotImplemented`:

```py
>>> from vector import Vector
>>> (3).__add__(Vector(1, 2))
NotImplemented
```

Then, Python will try to run `Vector(1, 2).__radd__(3)`.
Because we have not implemented that method, Python raises the exception `TypeError`.

All other arithmetic dunder methods also have a “reflected” version which has the same name but with the letter `r` prefixed.
_Some_ examples of reflected dunder methods include:

 - the method `__rsub__` which is the reflected dunder method for subtraction;
 - the method `__rmul__` which is the reflected dunder method for multiplication; or
 - the more exotic method `__rpow__` which is the reflected dunder method for exponentiation.

So, all things considered, if we want to be able to write expressions like `3 + Vector(1, 2)`, we need to implement the dunder method `Vector.__radd__`.


# Implementing reflected dunder methods

## Commutative operations

For our example, `Vector(1, 2) + 3` is supposed to return the same value as `3 + Vector(1, 2)`, so we can implement `__radd__` in terms of `__add__`:

```py
class Vector:
    ...

    def __radd__(self, other):
        print(f"Inside __radd__ with {self} and {other}")
        return self + other

if __name__ == "__main__":
    print(3 + Vector(1, 2))
```

If you run this code, it outputs the following:

```py
Inside __radd__ with Vector(1, 2) and 3
About to add Vector(1, 2) with 3
3 is an int or a float!
Vector(4, 5)
```

In fact, because addition with instances of `Vector` is commutative (that is, the result does not depend on the order of the left and right operands), you could even say that `__radd__ = __add__`:

```py
class Vector:
    ...

    def __add__(self, other):
        # Implementation omitted for brevity...

    __radd__ = __add__
```

This would still work.
Give it a try.


## Non-commutative operations

Not all arithmetic operations are commutative.
In fact, even addition isn't _always_ commutative!
Addition of strings – which we call concatenation – isn't commutative because `a + b` is usually different from `b + a`:

```pycon
>>> a = "Hello, "
>>> b = "world!"
>>> a + b
'Hello, world!'
>>> b + a
'world!Hello, '
```

When the operation isn't commutative, you have to implement the reflected dunder method like any other dunder method.
You will see examples of this throughout this article.


## `NotImplemented` in reflected dunder methods

Reverse dunder methods should _also_ return `NotImplemented` when the operation isn't defined for certain types of other arguments.

For example, if we were to implement `__radd__` explicitly for `Vector`, we would still return `NotImplemented` at the end of the method.
Suppose that we didn't:

```py
class Vector:
    ...

    def __radd__(self, other):
        print(f"About to radd {self} with {other}")
        if isinstance(other, Vector):
            result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
            return Vector(*result_coordinates)
        elif isinstance(other, (int, float)):
            print(f"{other} is an int or a float!")
            result_coordinates = [coord + other for coord in self.coordinates]
            return Vector(*result_coordinates)
        # return NotImplemented  # <-- Do we need this?
```

Can you guess what's going to happen now?
What should be the result of running the code below?

```py
class Vector:
    ...


if __name__ == "__main__":
    print("Adding a list and a vector:")
    print([1, 2] + Vector(1, 2))
```

Addition between lists and vectors is not defined, so this should result in a `TypeError`.
However, because `Vector.__radd__` does not return `NotImplemented`, Python actually thinks that this results in `None`.
The output of running that code is:

```
Adding a list and a vector:
About to radd Vector(1, 2) with [1, 2]
None
```

What is happening here is that the method `__radd__` has no explicit return at the end, which means the method returns `None` when using `__radd__` to add a `Vector` to something else that isn't a `Vector`, an integer, or a float.

If we want to get the `TypeError`, we need to return `NotImplementedError`:

```py
class Vector:
    ...

    def __radd__(self, other):
        print(f"About to radd {self} with {other}")
        if isinstance(other, Vector):
            result_coordinates = [a + b for a, b in zip(self.coordinates, other.coordinates)]
            return Vector(*result_coordinates)
        elif isinstance(other, (int, float)):
            print(f"{other} is an int or a float!")
            result_coordinates = [coord + other for coord in self.coordinates]
            return Vector(*result_coordinates)
        return NotImplemented  # <-- We need this!
```

Now, when we run this code, Python gives us a great error message:

```
About to radd Vector(1, 2) with [1, 2]
Traceback (most recent call last):
  File "/Users/rodrigogs/Documents/vector.py", line 43, in <module>
    print([1, 2] + Vector(1, 2))
          ~~~~~~~^~~~~~~~~~~~~
TypeError: can only concatenate list (not "Vector") to list
```


# Reflected dunder methods and subclasses

There is another situation in which reflected dunder methods come in handy, and that is when the right operand is from a subclass of the left operand.
Let me explain.

You are writing some code and you implement a class `S` that just holds a string.
Then, you implement addition between instances of the type `S`:

```py
class S:
    def __init__(self, value):
        self.value = value

    def __add__(self, other):
        if isinstance(other, S):
            return self.value + other.value
        return NotImplemented
```

This works just fine:

```pycon
>>> s1 = S("Hello, ")
>>> s2 = S("world!")
>>> s1 + s2
'Hello, world!'
```

Then, you decide to create a subclass of `S`, called `E`, which always holds the empty string.
Something like this:

```py
class E(S):
    def __init__(self):
        super().__init__("")
```

Because `E` is a subclass of `S`, you can add instances of `S` and `E` without a problem:

```pycon
>>> S("Hey") + E()
'Hey'
>>> E() + S("Hey")
'Hey'
>>> E() + E()
''
```

Everything is fine, right?
However, `E` is _always_ the empty string, which means that when you add an instance of `E` to another instance of `S`, the result is _always_ the string saved in the other instance, rigth?

So, you could optimise addition with instances of the type `E` by saying that you only need to return the string from the other instance.
Something like this:

```py
class S:
    def __init__(self, value):
        self.value = value

    def __add__(self, other):
        print("S.__add__")  # <-- Added this here.
        if isinstance(other, S):
            return self.value + other.value
        return NotImplemented


class E(S):
    def __init__(self):
        super().__init__("")

    def __add__(self, other):
        print("E.__add__")  # <-- Helper print.
        if isinstance(other, S):
            return other.value
        return NotImplemented

    def __radd__(self, other):
        print("E.__radd__")  # <-- Helper print.
        if isinstance(other, S):
            return other.value
        return NotImplemented
```

Because this behaviour is more specialised and because it comes from a subclass of `S`, Python will give priority to `E`'s way of adding things together if we try to add an instance of `S` with an instance of `E`:

```pycon
>>> S("Oi") + E()
E.__radd__
'Oi'
```

Notice that we didn't see the print from `S.__add__` because `E.__radd__` has priority over `S.__add__` because `E` is a subclass of `S`.

So, 


# Augmented arithmetic assignment

In Python, we can write things like `counter += 1` and `multiplier *= 2`.
This is called augmented assignment and there are dunder methods used to implement this behaviour.

The dunder methods that are used for augmented assignment start with an “i”, which I am guessing stands for “in-place”.
The rationale for these methods is that they should try to do the operation in-place.
If it makes sense for your object to be modified in place, then you should implement the augmented arithmetic assignment dunder methods.
For example, for addition that is going to be `__iadd__`.



[dunder-methods]: /blog/pydonts/dunder-methods
[dunder-init]: /blog/object-initialisation-with-__init__
[dunder-repr]: /blog/pydonts/str-and-repr
[list-comp]: /blog/pydonts/list-comprehensions-101
[zip]: /blog/pydonts/zip-up
