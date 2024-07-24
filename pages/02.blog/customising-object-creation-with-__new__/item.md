The dunder method `__new__` is used to customise object creation and is a core stepping stone in understanding metaprogramming in Python.

===

# Customising object creation with `__new__`

The [dunder method](/blog/pydonts/dunder-methods) `__new__` is a static method that creates new instances of your class and, therefore, can be used to customise that process.

The dunder methods `__init__` and `__new__` can look quite similar at first sight.
[The dunder method `__init__`](/blog/object-initialisation-with-__init__) will _initialise_ your instance of your class, but that instance must be created before it can be initialised, and that's the job of the dunder method `__new__`.

## Arguments of the dunder method `__new__`

The dunder method `__new__` accepts, as arguments, the class we are trying to create an instance of and all of the arguments passed to the class constructor, as shown in the snippet below:

```py
class C:
    def __new__(cls, *args, **kwargs):
        print(cls, args, kwargs)
        return super().__new__(cls)

C()  # <class '__main__.C'> () {}
C(73, True, a=15)  # <class '__main__.C'> (73, True) {'a': 15}
```

The return statement includes `super().__new__(cls)`, which is the typical way in which the object we want to create is brought to life.


## The return value of the dunder method `__new__`

The dunder method `__new__` can return any object whatsoever and the return value of the dunder method `__new__` is the result we get when we instantiate the class.

For example, if we create a class `C` whose dunder method `__new__` returns 73, then whenever we try to instantiate the class `C` we get the number 73:

```py
class C:
    def __new__(cls):
        return 73

c = C()
print(c)  # 73
print(type(c))  # int
```

Usually, the dunder method `__new__` will return an instance of the class it's in.
When that is the case, Python will automatically call the dunder method `__init__` on the object that was returned and it will pass along the arguments that were specified in the class constructor.
So, if `__init__` gets called, it will receive the same arguments that `__new__` received.

This snippet of code shows that `__init__` only gets called when the return value is an instance of the class where the method `__new__` is defined:

```py
class C:
    def __new__(cls, *, return_73):
        if return_73:
            return 73
        else:
            return super().__new__(cls)

    def __init__(self, *args, **kwargs):
        print("__init__!")

x = C(return_73=True)
print(x)  # 73
y = C(return_73=False)  # __init__!
print(y)  # <__main__.C object at 0x4ac5a507510>
```

It is relevant to note that Python will call `__init__` on the object returned even if it's an instance of a _subclass_ of the class where the method `__new__` is running!

In the snippet below, we create the class `C` and its subclass `D`.
`C` defines the dunder method `__new__` and both classes define their respective dunder methods `__init__`.
When we create an instance of `C`, we actually get an instance of `D` and the dunder method `D.__init__` gets called automatically:


```py
class C:
    def __new__(cls):
        return super().__new__(D)

    def __init__(self):
        print("C.__init__")

class D(C):
    def __init__(self):
        print("D.__init__")

d = C()  # D.__init__
print(type(d))  # <class '__main__.D'>
```

This may look a bit weird but it's actually a pattern that is used in the standard library, in the module `pathlib`.


## How the module `pathlib` uses `__new__` to customise path creation

Have you ever noticed how, if you import `Path` from `pathlib`, and then instantiate it, you get an object that depends on your operating system?

For example, in my machine, I get this:

```py
from pathlib import Path

print(Path())  # PosixPath('.')
```

If I were on a Windows machine, I would get a `WindowsPath` instead of a `PosixPath`.
How does the module do this, if all I do is instantiate always the _same_ class `Path`?

The class `Path` implements a dunder method `__new__` and the dunder method `__new__` looks at the operating system of the machine its running on.
If it's a Windows machine, it will create a `WindowsPath`.
If it's not a Windows machine, it will create a `PosixPath`.
Because both `WindowsPath` and `PosixPath` inherit from `Path`, this won't interfere with the remainder of the object creation and initialisation.

The snippet of code below mimics this structure (with a much sillier but simpler example):

```py
class Number:
    def __new__(cls, value):
        print("Number.__new__", end=", ")
        if cls is Number:
            cls = OddNumber if value % 2 else EvenNumber
        return super().__new__(cls)

    def __init__(self, value):
        print("Number.__init__")
        self.value = value

class EvenNumber(Number):
    def __init__(self, value):
        print("EvenNumber.__init__", end=", ")
        super().__init__(value)
        self.is_even = True

class OddNumber(Number):
    def __init__(self, value):
        print("OddNumber.__init__", end=", ")
        super().__init__(value)
        self.is_even = False

x = Number(73)  # Number.__new__, OddNumber.__init__, Number.__init__
print(type(x))  # <class '__main__.OddNumber'>
print(x.value)  # 73
```

(You can see [the exact line of code that does this for `pathlib` in Python 3.14 on GitHub](https://github.com/python/cpython/blob/e9681211b9ad11d1c1f471c43bc57cac46814779/Lib/pathlib/_local.py#L522-L525).)


## Using `__new__` to subclass immutable types

The Python documentation says that “`__new__()` is intended mainly to allow subclasses of immutable types (like `int`, `str`, or `tuple`) to customize instance creation.”
But what does this mean?

As a motivating example, let us try to implement a class `TolerantFloat` that is a subclass of `float`.
Instances of `TolerantFloat` will work exactly like floats except for equality comparisons, where the comparison will be based on an error tolerance to account for floating point errors.

Here is an example usage of this class we want to implement:

```py
x = TolerantFloat(0.5, rel_tol=0.1)
print(x == 0.51)  # True (close enough)
print(x == 0.42)  # False (not close enough)
```

How do we implement `TolerantFloat`..?
(Go ahead and give it a go yourself!)

The first thing you need to understand is that the method `__init__` does nothing on immutable objects.
For example, calling `__init__` with a new value won't change the number associated with a float:

```py
x = 3.5
print(x)  # 3.5
x.__init__(42.73)
print(x)  # 3.5
```

This is opposed to what happens for mutable types, for which calling `__init__` can have an effect on the value:

```py
my_list = [42]
my_list.__init__(range(3))
print(my_list)  # [0, 1, 2]
```

This won't work on immutable objects because they are... Immutable!
For floats, the only moment where we can influence the object being created is inside `__new__`, because `__init__` isn't used at all.

Thus, if our tentative implementation of `TolerantFloat` tries to resort to the method `__init__`, it will fail:

```py
class TolerantFloat(float):
    def __init__(self, value, rel_tol):
        super().__init__(self, value)
        self.rel_tol = rel_tol

x = TolerantFloat(0.5, rel_tol=0.1)  # TypeError
```

We already know that calling `super().__init__` won't do anything, but where does the `TypeError` come from?
Remember that `__new__` and `__init__` are called with the same arguments, so when we type `TolerantFloat(0.5, rel_tol=0.1)`, we are trying to call `float.__new__` with with two arguments and Python isn't happy about it.

This means that we _must_ implement `TolerantFloat.__new__` to pass only the numerical value to `float.__new__`:

```py
class TolerantFloat(float):
    def __new__(cls, value, rel_tol):
        return super().__new__(cls, value)

    def __init__(self, value, rel_tol):
        self.rel_tol = rel_tol

x = TolerantFloat(0.5, rel_tol=0.1)
print(x)  # 0.5
```

By implementing `TolerantFloat.__new__` we can intercept object creation at the right point to make sure that Python initialises the float properly and only then do we add the relative tolerance as an attribute.
With this, we can finally implement the dunder method `__eq__` that uses `math.isclose` to do the lenient equality comparison:

```py
from math import isclose

class TolerantFloat(float):
    def __new__(cls, value, rel_tol):
        return super().__new__(cls, value)

    def __init__(self, value, rel_tol):
        self.rel_tol = rel_tol

    def __eq__(self, other):
        return isclose(self, other, rel_tol = self.rel_tol)

x = TolerantFloat(0.5, rel_tol=0.1)
print(x == 0.51)  # True
print(x == 0.42)  # False
```


## Using `__new__` in metaclasses

The documentation also mentions that `__new__` is commonly overridden in custom metaclasses in order to customise class creation.
To give you an example of what this could look like, we will implement a metaclass that injects a better default [dunder method `__str__`](/blog/pydonts/str-and-repr) in all classes that use that metaclass.
(Note that this is a simplified example of how `__new__` can be used to customise class creation and this specific goal could be achieved through other means.)

```py
def better_str(self):
    return f"<{self.__class__.__name__} object>"

class BetterStrMeta(type):  # <- Metaclasses inherit from `type`!
    def __new__(cls, *args, **kwargs):
        print("BetterStrMeta.__new__")
        cls_object = super().__new__(cls, *args, **kwargs)
        cls_object.__str__ = better_str
        return cls_object

class A1:
    ...

class A2(metaclass=BetterStrMeta):  # BetterStrMeta.__new__
    ...

print(A1())  # <__main__.A1 object at 0x46616501090>
print(A2())  # <A2 object>  <- cleaner!
```

If you run the code above you will see that the first print is `BetterStrMeta.__new__` because that runs when the class is defined.
In there, we defer to `super().__new__` to do the object creation (which is the class `A2`) and then we inject the method `better_str` in place of `A2.__str__`.
Finally, when we print an instance of `A2` we see a cleaner string representation of that object.


## When to really use `__new__`

The dunder method `__new__` is a method that the vast majority of Python programmers will never _have_ to use.
In some cases, you may want to [implement an instantiation pattern similar to that of `pathlib.Path`, where you instantiate a main class that dispatches automatically to a subclass](#how-the-module-pathlib-uses-__new__-to-customise-path-creation), but that's a fairly rare requirement.
Using `__new__` to subclass built-in types or in metaclasses is likely an even rarer requirement.

So, if you want to learn metaprogramming you need to learn how `__new__` works.
This knowledge will help you understand better how Python works and that is a good thing.
However, in the code you write, you probably won't need to use this knowledge explicitly.
