This article goes over the major improvements made to the typing module in Python 3.15.

===

Since static type hinting is becoming more and more popular in the Python world, every Python release brings with it major improvements to the world of typing.
This article goes over the main improvements made to typing in Python 3.15 like the improvements to `TypedDict`, the addition of `typing.TypeForm`, or the addition of the decorator `typing.disjoint_base`.

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## New class parameters for `TypedDict`

Python 3.15 added two class parameters to `TypedDict`: `extra_items` and `closed`.
The gist of it is that `extra_items` allows specifying that your dictionary may have other, unknown keys whose values have the specified type:

```py
from typing import TypedDict

class Movie(TypedDict, extra_items=str):
    title: str
    year: int
```

The definition above means that a variable of type `Movie` might have other keys, as long as their values are assignable to the type `str`:

```py
movie: Movie = {
    "title": "Forrest Gump",
    "year": 1994,
    "genre": "Comedy / Romance",
}
```

The extra item `"genre": "Comedy / Romance"` is allowed because the value is a string and `extra_items=str`.

To completely disallow extra items, regardless of their type, you can set `closed=True` as a class parameter.

There's an article dedicated to [the improvements made to `TypedDict` in Python 3.15](/blog/typeddict-with-extra-items) that you can read to learn more.

## `TypeVarTuple` now matches the interface of `TypeVar`

Type variables, when created manually, accept keyword arguments `bound`, `covariant`, `contravariant`, and `infer_variance`:

```py
from typing import TypeVar

T1 = TypeVar("T1")
T2 = TypeVar("T2", bound=str)
T3 = TypeVar("T3", covariant=True)
T4 = TypeVar("T4", contravariant=True)
T5 = TypeVar("T5", infer_variance=True)
```

In Python 3.15, `TypeVarTuple` can now accept the same keyword arguments when created manually.
The arguments `covariant`, `contravariant`, and `infer_variance` are `False` by default and a type variable tuple is invariant by default.
You can set any of the three to `True` to change the variance of the type variable tuple.

`TypeVarTuple` can also accept the keyword argument `bound`, which is new in Python 3.15.
This was added so that the interface for `TypeVar` and `TypeVarTuple` match.
However, the meaning of `TypeVarTuple("Ts", bound=...)` hasn't been specified yet.

## Instance and subclass checks with protocols

When you define a protocol class, you can use the decorator `@typing.runtime_checkable` to say that that protocol can be used in `isinstance` and `issubclass` checks (the two built-ins will perform basic structural checks in those cases).
The ability to be runtime checkable in _not_ inherited, so a protocol `A` that inherits from a runtime checkable protocol `B` will only be runtime checkable if the definition of `B` also includes `runtime_checkable`:

```py
from typing import Protocol, runtime_checkable

@runtime_checkable
class P1(Protocol): ...      # Is runtime checkable

@runtime_checkable
class P2(P1, Protocol): ...  # Is runtime checkable

class P3(P2, Protocol): ...  # Is NOT runtime checkable
```

The protocols `P1` and `P2` are runtime checkable because they're decorated with `runtime_checkable`.
The protocol `P3` is _not_ runtime checkable, even though it inherits from a protocol that is, because it isn't decorated explicitly with `runtime_checkable`.

Starting with Python 3.15, the ability to use `P3` in `isinstance` and `issubclass` checks has been deprecated.
Starting with Python 3.20, performing such a check will raise `TypeError`.

## Marking disjoint bases

Python 3.15 introduces the decorator `@typing.disjoint_base`, a decorator that can be used to help reflect relationships between classes that cannot be simultaneously inherited from.
As an example, consider the invalid class definition of `MyClass` below:

```py
class MyClass(int, str):
    pass
```

If you try to run this class definition, you get a `TypeError`:

```text
Traceback (most recent call last):
  File "/Users/rodrigogs/Documents/mathspp/mathspp/t.py", line 1, in <module>
    class MyClass(int, str): pass
TypeError: multiple bases have instance lay-out conflict
```

Type checkers can also catch this issue, but before Python 3.14 they did so using heuristics and other tricks.
In other words, they don't deduce the issue strictly based on information that is present in the type system.
The addition of the decorator `disjoint_base` solves this issue, as classes decorated with `disjoint_base` cannot be inherited from simultaneously.

As of Python 3.15, the [stubs for the classes `str`](https://github.com/python/typeshed/blob/d9774b31d7fb4653cf8795e0f7c10a0855caeff9/stdlib/builtins.pyi#L481) [and `int`](https://github.com/python/typeshed/blob/d9774b31d7fb4653cf8795e0f7c10a0855caeff9/stdlib/builtins.pyi#L245-L246) use `disjoint_base`:

```py
# Stub for `str` in typeshed:
@disjoint_base
class str(Sequence[str]): ...

# Stub for `int` in typeshed:
@disjoint_base
class int: ...
```

This adds to the type system information that you could only get if you looked at the implementation of `int` and `str` in the C code.

The decorator `disjoint_base` can also be used in custom classes:

```py
from typing import disjoint_base

@disjoint_base
class A: ...

@disjoint_base
class B: ...

class C(A, B): ...
```

Using a type checker on this code should point out that the class `C` can't inherit from `A` and `B` at the same time.
However, _at runtime_, the decorator `disjoint_base` does _not_ prevent inheriting from 2+ disjoint bases.

## New special form `TypeForm`

In Python, `int`, `str | int`, `Literal[3]`, and `"list[bool]"` are all valid type forms.
The new special form `TypeForm` can be used to refer to these types of values:

```py
tf1: TypeForm[int] = int
tf2: TypeForm[str | int] = str | int
tf3: TypeForm[Literal[3]] = Literal[3]
tf4: TypeForm[list[bool]] = list[bool]
```

The addition of `TypeForm` is more of a meta-typing feature that is more likely to be used by code that manipulates types directly, which is a niche requirement.

As an example, [PEP 747](https://peps.python.org/pep-0747/) shows the signature of converter function using `TypeForm`:

```py
def converter[T](typx: TypeForm[T], value: object) -> T: ...
```

This function would accept a type form object and an arbitrary value and it would then try to convert that value to the given type or error if impossible:

```py
converter("int", "42")  # 42
converter(bytes, "42")  # b'42'
converter(dict[str, int], "42")  # ValueError
```

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}
