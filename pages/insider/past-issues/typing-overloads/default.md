# 🐍🚀 typing overloads

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider#subscribe) to get weekly Python deep dives like this one on your inbox!

## The problem

The usage of typing overloads allows you to declare relationships between the types of arguments and/or the return type of a function.

For example, the computation `2 * arg` works for strings and ints, and you know the result has the same type as the value used.

Suppose it's a function:

```py
def double(arg):
    return 2 * arg
```

How do you add type hints to this, if you expect to use it with integers and strings?

The obvious solution is to add `str | int` as the type hint for `arg` and for the return value:

```py
def double(arg: str | int) -> str | int:
    return 2 * arg
```

However, this doesn't tell the type checker that there is a relationship between the argument type and the return value:

```py
def double(arg: str | int) -> str | int:
    return 2 * arg

reveal_type(double("hey"))  # str | int
```

(`reveal_type` is kind of like the “print” for type checkers and it shows you the type of whatever you put inside the function.)

Your type checker is telling you that the result of `double("hey")` is going to be a string or an integer...

But you know it's going to be a string...

So how do you teach your type checker that?

## Typing overloads

Typing overloads are the solution.

With typing overloads, you specify signatures of the same function, with one per relationship you want to define.

You want to say that if the argument is a string, you get a string, and if the argument is an integer, you get an integer, so you need two overloads.

To create an overload, you use the decorator `@typing.overload` and define the header of the function with the restricted types:

```py
from typing import overload

## If you give it strings, you get strings:
@overload
def double(arg: str) -> str: ...

## If you give it integers, you get integers:
@overload
def double(arg: int) -> int: ...
```

Then, to conclude, you define the full function with the full signature and its body:

```py
## The general function with the function body:
def double(arg: str | int) -> str | int:
    return 2 * arg
```

When you use the function, now the type checker will understand the relationship between the types:

```py
reveal_type(double("hey"))  # str
```

## Ok, I lied, there's another solution...

Well, if you're already familiar with type variables, you may have realised that you can also solve the problem with the function `double` with a type variable.

Using Python 3.12's generic function syntax, you can say that the type `T` is either `str` or `int`, and then use it instead:

```py
def double[T: (str, int)](arg: T) -> T:
    return 2 * arg
```

This also works in this case, and is nicer, but this won't save you every time...

## Typing overloads can be as complex as needed

The advantage of typing overloads is that they can be as complex as needed and they might reveal relationships that you couldn't create with type variables.

For example, overloads work well with literals, when your function accepts or produces one of a fixed set of values, and those values impose constraints on the other arguments/return value.

For example, suppose you have a function `act` that you can either use to jump by a specified height or to scream any number of given strings:

```py
act("jump", 5)

act("scream", "Python is cool!", "What's up?")
```

You can use overloads to specify that the action “jump” expects a single integer:

```py
from typing import Literal

@overload
def act(action: Literal["jump"], arg: int) -> None:
    ...
```

and that the action “scream” expects one or more strings:

```py
@overload
def act(action: Literal["scream"], arg: str, *args: str) -> None:
    ...
```

You top it all off with the full signature:

```py
type Action = Literal["jump", "scream"]

def act(action: Action, arg: int | str, *args: str) -> None:
    ... # Function body goes here
```

Now, the cool thing is that the overloads even do some sort of validation!

If you use the action “jump” but provide more than a single integer, or if you use strings, you get errors.

Similarly, if you try to use integers when using the action “scream”, you get errors!

## Typing is fun

Typing isn't for everyone and for every project, but it's quite cool!

Are there any typing features or concepts you're struggling with?

Let me know and I'll write about it ;)

## Enjoyed reading?

This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter.
Subscribe to the mathspp insider 🐍🚀 to get weekly Python deep dives like this one on your inbox:

[Join mathspp insider 🐍🚀](?classes=btn,btn-lg,btn-center#subscribe)
