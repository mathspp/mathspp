Today I learned about context variables from the module `contextvars` and how to use them.

===


# Context variables

I was doing some work on [Textual], the project I work on for my job, and I came across the module `contextvars`.
Textual uses `contextvars.ContextVar` in a couple of places and so I decided to write this article to explore how they work.


## What's the point of context variables?

Context variables can store data that is available across different function calls without having to pass them as arguments.
They are particularly useful in asynchronous code.

Passing all the data as arguments to every single function call would bloat function signatures because not all functions need everything, but you'd need to pass everything around because you never know when you might end up calling a function that needs some specific data.

Another alternative would be creating global variables, but I'm guessing that would be just asking for trouble in concurrent code.
I personally don't know, but if global variables worked just fine, I'm sure we wouldn't have a module just for this.


## Creating a context variable

To create a context variable, you use `ContextVar` from the module `contextvars`.
To set and get the value of the variable, you use the methods `.set` and `.get`, respectively:

```py
from contextvars import ContextVar


name = ContextVar("name")
name.set("Rodrigo")
print(name.get())  # Rodrigo
```

As per the documentation, context variables should be declared at the top level of your module, and never inside methods or functions.


### Default value

A context variable can also be created with a default value:

```py
from contextvars import ContextVar


name = ContextVar("name", default="Rodrigo")
print(name.get())  # Rodrigo
```


### Default value for `.get`

Finally, the method `.get` also accepts a default value, similar to the method `dict.get`:

```py
from contextvars import ContextVar


name = ContextVar("name")
print(name.get("no name set"))  # no name set
```

When you call `.get`, if the context variable hasn't been set explicitly, three things can happen, in this order:

 1. you get the default value from the method `.get`; or
 2. you get the default value from the context variable; or
 3. an exception `LookupError` is raised.


## Context variables are available across function calls

As I mentioned previously, a context variable can be accessed from functions and its value will be available:

```py
from contextvars import ContextVar


name = ContextVar("name")
name.set("Rodrigo")


def print_name():
    print(name.get("no name set"))


print_name()  # Rodrigo
```


## Different contexts for context variables

Context variables become more useful when the code is such that you might get different _contexts_ in which the variable is being used.
When that is the case, the variable keeps track of its state for each context.

Better than just telling you about it is showing you what I mean.

Take a look at the code below and notice how:

 - it defines a context variable `number`;
 - it sets the context variable inside `number` and we're able to retrieve that value;
 - we use `changes_number_to` directly from within `main` to change the value to `73` and the change is reflected inside `main` – that's because we didn't go to a different context; and
 - we create a task to run `changes_number_to` to change the value to `42` and the change is _**not**_ reflected inside `main` - a new task represents a new context.

Here is the code:

```py
import asyncio
from contextvars import ContextVar


number = ContextVar("number", default=0)


def print_number(label):
    """Prints the value of `number` in the current context."""
    print(f"{label}: {number.get()}")


async def changes_number_to(new_number):
    print(f"Changing number to {new_number}")
    number.set(new_number)
    print_number("Just changed number to")


async def main():
    number.set(1)
    print_number("Inside main")

    await changes_number_to(73)
    print_number("Inside main")

    task = asyncio.create_task(changes_number_to(42))
    await task
    print_number("Inside main")


asyncio.run(main())
```

The output of running the code above is:

```
Inside main: 1
Changing number to 73
Just changed number to: 73
Inside main: 73
Changing number to 42
Just changed number to: 42
Inside main: 73
```

This shows that _using a coroutine doesn't automatically create a new context_.
However, _creating a task creates a new context_.


## Context variables and mutable values

The final exploration that I made has to do with mutable values.
If a context variable is set to a mutable value and I mutate that value, does the context variable reflect that change?
I guess that it does, but I wanted to be sure, so I ran a couple of experiments.


### Mutable values in a single context

The first experiment that I ran is in a single context.
If I have a mutable value (a list) and I get it, and then mutate it (by appending to it), does the change reflect in the context variable?

The code below shows that it does, as the value of `names` gets updated even without calling `.set`.

```py
from contextvars import ContextVar


names = ContextVar("names")
names.set([])

names.get().append("Rodrigo")

print(names.get())  # ['Rodrigo']
```

This raises a more interesting question:
if I have a mutable value that I access from within a different context and mutate it, does the change get reflected on the other context?


### Mutable values across multiple contexts

The code below is a modification of the previous example with multiple contexts.
This time, instead of a single number we'll have a list (of numbers), and instead of using `.set`, we'll append to the list of numbers.

We want to see whether calling `.append` from the second context changes the list of the original context or not:

```py
import asyncio
from contextvars import ContextVar


number = ContextVar("number")
number.set([])


def print_number(label):
    """Prints the value of `number` in the current context."""
    print(f"{label}: {number.get()}")


async def appends_number(new_number):
    print(f"Changing number to {new_number}")
    number.get().append(new_number)
    print_number("Just changed number to")


async def main():
    number.get().append(1)
    print_number("Inside main")

    await appends_number(73)
    print_number("Inside main")

    task = asyncio.create_task(appends_number(42))
    await task
    print_number("Inside main")


asyncio.run(main())
```

If you run this code, this is the output that you get:

```
Inside main: [1]
Changing number to 73
Just changed number to: [1, 73]
Inside main: [1, 73]
Changing number to 42
Just changed number to: [1, 73, 42]
Inside main: [1, 73, 42]
```

As you can see, appending the `42` in a different context made the change visible to the original context.
You have to be _very_ careful with this!


## Conclusion

Context variables look like a very powerful and flexible tool that obviously comes with some interesting associated caveats.

From the look of it, if you are not careful when using context variables, you will end up with spaghetti code!
This is just a feeling I have, though, and I'm not saying that context variables shouldn't be used!

If you know of a good concrete use case for context variables, please do let me know!
I'd love to take a look at that code.


[Textual]: https://github.com/textualize/textual
