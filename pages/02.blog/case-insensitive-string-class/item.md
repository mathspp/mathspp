This article shows how you can create a case-insensitive string class using some basic meta programming with the dunder method `__new__`.

===

In this article we want to implement a case-insensitive string class, that we will call `CIStr`, such that the comparisons between an instance of `CIStr` and a regular string, or between two `CIStr` instances, are done in a case-insensitive way.

Here are two examples of what we're looking for:

```pycon
>>> CIStr("Hello") == "heLLO"
True
>>> "heLLO" == CIStr("Hello")
True
```

## Case-insensitive equality comparison

[To compare two strings in a case-insensitive way, we need to use the string method `casefold`](/blog/how-to-work-with-case-insensitive-strings).
So, when we implement our class `CIStr`, its `__eq__` and `__ne__` methods should use `casefold` on both string values before comparing them:

```py
class CIStr(str):
    def __eq__(self, other):
        return self.casefold() == other.casefold()

    def __ne__(self, other):
        return str.__ne__(self.casefold(), other.casefold())

print(CIStr("hello") == "HELlo")  # True
```

This implementation of `__eq__` works because `self.casefold()` will produce a standard string, which is then compared to `other.casefold()` through the default dunder method `__eq__`, not the one we defined here.
If we end up changing the `CIStr` string methods to also return case-insensitive strings, then we may need to be more explicit about the fact that we want to use `str.__eq__` after casefolding both strings:

```py
class CIStr(str):
    def __eq__(self, other):
        return str.__eq__(self.casefold(), other.casefold())

print(CIStr("hello") == "HELlo")  # True
```

If we didn't do this in the final implementation shown here we would hit an infinite recursion loop.


## All case-insensitive comparisons

To make sure that all comparisons are case-insensitive, we also need to take care of the comparison operators `<`, `<=`, `>`, and `>=`.
We could think of using `functools.total_ordering` but that will not work because `str` already defines the comparison dunder methods and we need to override their default behaviour:

```py
class CIStr(str):
    def __eq__(self, other):
        return str.__eq__(self.casefold(), other.casefold())

    def __ne__(self, other):
        return str.__ne__(self.casefold(), other.casefold())

    def __lt__(self, other):  # <
        return str.__lt__(self.casefold(), other.casefold())

    def __le__(self, other):  # <=
        return str.__le__(self.casefold(), other.casefold())

    def __gt__(self, other):  # <=
        return str.__gt__(self.casefold(), other.casefold())

    def __ge__(self, other):  # <=
        return str.__ge__(self.casefold(), other.casefold())

print(CIStr("hello") == "HELlo")  # True
print(CIStr("Hello") > "abracadabra")  # True (H comes after A)
```


## Patch string methods to accept and return case-insensitive strings

Strings have dozens of methods, like `upper`, `startswith`, and `split`.
What if wanted those methods to work with case-insensitive strings _and_ return case-insensitive strings automatically?

For example, as it stands, if we use a method like `upper` on a case-insensitive string, we no longer get a case-insensitive string back.
But we can fix this.
We can patch every single string method so that:

 1. their arguments are always used casefolded; and
 2. their return values are always case-insensitive strings.

This can be achieved if we reimplement every single string method by hand but there is a shorter way.
To do this, we define two decorators:

 1. a class decorator (`patch_string_methods`) that will wrap `CIStr` which will traverse the string methods, looking for all of the methods that are not dunder methods; and
 2. a method decorator (`case_insensitive_decorator`) that will wrap each method found above, that will apply three transformations to the original method:
   1. arguments of the type `str` will be converted automatically to the type `CIStr`; and
   2. return values of type `str` will be converted to the type `CIStr`; and
   3. return values that are lists or tuples of strings will have their strings converted to the type `CIStr` as well.

Here is the code for this:

```py
from functools import wraps

def case_insensitive_decorator(method):
    @wraps(method)
    def case_insensitive_method(*args, **kwargs):
        args = tuple(CIStr(arg) if isinstance(arg, str) else arg for arg in args)
        kwargs = {
            key: CIStr(value) if isinstance(value, str) else value
            for key, value in kwargs.items()
        }
        return_value = method(*args, **kwargs)
        if isinstance(return_value, str):
            return_value = CIStr(return_value)
        elif isinstance(return_value, list | tuple):
            type_ = type(return_value)
            return_value = type_(
                CIStr(element) if isinstance(element, str) else element
                for element in return_value
            )
        return return_value

    return case_insensitive_method

def patch_string_methods(cls):
    for attribute_name in dir(cls):
        if attribute_name.startswith("__"):
            continue
        attribute = getattr(cls, attribute_name)
        if callable(attribute):
            setattr(cls, attribute_name, case_insensitive_decorator(attribute))

    return cls


@patch_string_methods
class CIStr(str):
    def __eq__(self, other):
        return str.__eq__(self.casefold(), other.casefold())

    def __ne__(self, other):
        return str.__ne__(self.casefold(), other.casefold())

    def __lt__(self, other):  # <
        return str.__lt__(self.casefold(), other.casefold())

    def __le__(self, other):  # <=
        return str.__le__(self.casefold(), other.casefold())

    def __gt__(self, other):  # >
        return str.__gt__(self.casefold(), other.casefold())

    def __ge__(self, other):  # >=
        return str.__ge__(self.casefold(), other.casefold())

assert CIStr("hello") == "HELlo"
assert CIStr("Hello") > "abracadabra"
assert CIStr("Hello").upper() == "hello"
hello, world = CIStr("Hello, world!").split(", ")
assert hello == "HELLO"
```
