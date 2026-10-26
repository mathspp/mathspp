Learn how to define a TypedDict that accepts extra items of a given type.

===

A `TypedDict` is a type constructor that allows creating specialised types for dictionaries that have a _fixed_ set of string keys:

```py
from typing import TypedDict

class Movie(TypedDict):
    title: str
    year: int
```

The snippet above creates a new type `Movie` that represents dictionaries with the key `"title"` and a string value, and the key `"year"` and an integer value:

```py
forrest_gump = {"title": "Forrest Gump", "year": 1994}
```

In Python 3.15, `TypedDict` was improved to allow specifying whether or not the dictionary may have _other_ keys and what must be the type of those other items when they're allowed.

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Allowing extra items of a given type

When creating a `TypedDict`, you may specify the class parameter `extra_items`.
When `extra_items` is set, your `TypedDict` may have additional keys of the type specified:

```py
from typing import TypedDict

class Movie(TypedDict, extra_items=str):
    title: str
    year: int
```

This snippet of code creates a `TypedDict` with two keys that _allows extra string items_, as specified by `extra_items=str`:

```py
# ✅ Type checks
m1: Movie = {"title": "Forrest Gump", "year": 1994, "genre": "Comedy/Romance"}
```

The assignment to `m1` is type safe.
While the key `"genre"` is not specified in the definition of `Movie`, the corresponding item is a string.
On the other hand, the assignment to `m2` below fails type checking:

```py
# ❌ Doesn't type check
m2: Movie = {"title": "Forrest Gump", "year": 1994, "rating": 8.8}
```

The dictionary literal can't be a `Movie` because the key `"rating"` is unknown _and_ the corresponding value is a float.

## Disallowing extra items of a given type

You can use the class parameter `closed=True` to specify that the given `TypedDict` has exactly the keys specified in its body, and none more.
But why is this relevant?

When `extra_items` is not set, you get the `TypedDict` behaviour from 3.14 and earlier.
When a `TypedDict` is subclassed, you may add new keys:

```py
class Movie(TypedDict):
    title: str
    year: int

class RatedMovie(Movie):
    rating: float
```

By defining `RatedMovie` as a subclass of `Movie`, a variable of type `Movie` might have the key `"rating"`:

```py
# ✅ Type checks
rated: RatedMovie = {"title": "Forrest Gump", "year": 1994, "rating": 8.8}
movie: Movie = rated
print(movie.rating)  # 8.8
```

So, by setting `closed=True`, you prevent subclasses from adding more keys[^1].

[^1]: You also prevent a couple of other scenarios where structural assignability would allow a `TypedDict` to have other items that its type doesn't describe.

The class parameters `extra_items` and `closed` can't be used at the same time, and setting `closed=False` is the same as not specifying any of the two.
Naturally, the default behaviour matches the behaviour of `TypedDict` in Python 3.14 or earlier.

## Specifying extra items when unpacking

`typing.Unpack` can be used in conjunction with `TypedDict` to type the keyword arguments that a function may accept.
By using `Unpack` in conjunction with `extra_items`, you have the ability to specify whether or not a function may accept _more keyword arguments_ than the ones already specified by the `TypedDict`.

So, when no extra items are specified, using `Unpack` with a `TypedDict` should allow no extra keyword arguments.
As an example, the two functions `f` below are equivalent:

```py
from typing import TypedDict, Unpack

class Movie(TypedDict):
    title: str
    year: int

def f(**kwargs: Unpack[Movie]) -> None: ...

# ---

def f(*, title: str, year: int) -> None: ...
```

When `extra_items` is specified, you're allowing extra keyword arguments of that type.
As such, the two functions `g` below are equivalent:

```py
from typing import TypedDict, Unpack

class MovieExtra(TypedDict, extra_items=str):
    title: str
    year: int

def g(**kwargs: Unpack[MovieExtra]) -> None: ...

# ---

def g(*, title: str, year: int, **kwargs: str) -> None: ...
```

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}
