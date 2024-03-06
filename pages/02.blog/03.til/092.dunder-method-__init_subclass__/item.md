Today I learned how to use the dunder method `__init_subclass__` to be notified when a class is subclassed.

===


# `__init_subclass__`

The [dunder method](/blog/pydonts/dunder-methods) `__init_subclass__` is a class method that Python runs when a subclass of that class is instantiated.
The snippet below sums it all:

```py
class Parent:
    def __init_subclass__(cls):
        print(f"Subclass {cls} was created.")

class A(Parent):
    pass

class B(A):
    pass

"""
Output:
Subclass <class '__main__.A'> was created.
Subclass <class '__main__.B'> was created.
"""
```

The code above shows that when subclasses are created (even if they are not direct subclasses, like the case of `B`) the class method `Parent.__init_subclass__` is called.

The class method `__init_subclass__` will also receive the keyword arguments that you specify on class definition.
The snippet below shows this:

```py
class Parent:
    def __init_subclass__(cls, **kwargs):
        print(f"Subclass {cls.__name__} created with {kwargs}")

class A(Parent, kwarg1=73, kwarg2=True):
    pass

# Output: Subclass A created with {'kwarg1': 73, 'kwarg2': True}
```


## Metaprogramming with `__init_subclass__`

The point of the dunder method `__init_subclass__` is that a parent class can modify its child classes when they are being created, thus enabling metaprogramming.
For example, I needed to use `__init_subclass__` in [Textual](http://github.com/textualize/textual) to make sure that all subclasses of a particular class, named `Widget`, had a name that starts with an upper case letter or with an underscore `_`.

You can check the [Textual codebase](http://github.com/textualize/textual) for the full context, but this was essentially what I implemented:

```py
class BadWidgetName(Exception):
    """Raised when widget names do not satisfy the required restrictions."""

class Widget:
    def __init_subclass__(cls):
        name = cls.__name__
        if not name[0].isupper() or not name.startswith("_"):
            raise BadWidgetName(
                f"Widget class {name!r} must start with an upper case letter or underscore '_'."
            )

class A(Widget):   # Ok
    pass

class _b(Widget):  # Ok
    pass

class c(Widget):   # raises BadWidgetName exception.
    pass
```
