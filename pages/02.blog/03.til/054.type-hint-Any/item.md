Today I learned about the type hint `Any` and its bidirectional compatibility with all other types.

===

# Type hint `Any`

The special type `Any` provided in the module `typing` is a type that matches _all_ Python objects.
The documentation says that `Any` is compatible with all other types and all other types are compatible with `Any`.

I have a limited understanding of type hints in Python, but I don't think this sentence is redundant:
 > “A static type checker will treat every type as being compatible with `Any` and `Any` as being compatible with every type.”

This _compatibility_ seems to be related to subclasses and the methods and attributes that objects provide.

For example, suppose we have this class hierarchy:

```py
class Animal:
    pass

class Human(Animal):
    pass
```

All instances of `Human` are also instances of `Animal` (because `Human` is a subclass of `Animal`) but there may be instances of `Animal` that are not instances of `Human`.
For example, dolphins are definitely animals and also definitely not humans.

So, the type `Human` is compatible with the type `Animal`.
Why?
Because all instances of `Human` can also be seen as instances of `Animal`.
In fact, the function below would pass static type checking:

```py
def foo(human: Human) -> Animal:
    return human
```

However, the reverse does **not** pass static type checking:

```py
def bar(animal: Animal) -> Human:
    return animal
```

The static type checker complains with “`Animal` is incompatible with `Human`”.
So, if `Any` is compatible with all types and if all types are compatible with `Any`, this means the functions `f` and `g` below should pass static type checking:

```py
from typing import Any

def f(x: int) -> Any:
    return x

def g(x: Any) -> int:
    return x
```

And they do.
If you run a static type checker against the functions `f` and `g`, they both pass static type checking.

What bothers me is that the function `g` seems to be typed improperly.
After all, I can write this code:

```py
from typing import Any

def g(x: Any) -> int:
    return x

print(g("Hello, world!"))
```

Running this code will print a string (and the function `g` has a type hint saying it returns an integer) but the code passes static type checking!
After all, we have that:

 - the function `g` is correctly typed because the return value `x` is of type `Any`, and `Any` is compatible with `int`; and
 - the function call `g("Hello, world!")` is also ok because the argument is of type `str` and the function `g` expects an argument of type `Any`. The type `str` is compatible with `Any`, so this function call is ok.


## What is `Any` used for?

So, why is `Any` useful if it gives you total freedom and doesn't seem to do anything for you?
As pointed out in the comment section below, “`Any` is useful for gradually adding types to an untyped codebase.”.

So, if you have some code that you'd like to typecheck, you can start by inserting `Any` everywhere, and the code will typecheck.
Then, you can gradually start replacing `Any` with more specific types.

If you are a practical type of person, instead of a purist, there is another situation where `Any` might come in handy.
If you have a piece of code that is very dynamic you might say that a given variable is typed as `Any`, either because the actual type is completely arbitrary during runtime or because the actual type is unwieldy to write.


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
