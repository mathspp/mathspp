---
metadata:
    description: "This Pydon't walks you through the usages of the __name__ dunder method and how to use it effectively."
title: "__name__ dunder attribute | Pydon't 🐍"
---

This Pydon't walks you through the usages of the
`__name__` dunder method and how to use it effectively.

===

![A Python code snippet that uses __name__.](thumbnail.svg)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

In this Pydon't we will take a look at the `__name__` attribute.
If you Google it, you will find a ton of results explaining _one_
use case of the `__name__` attribute, so in this Pydon't
I'll try to tell you about another couple of use cases
so that you learn to use `__name__` effectively in your Python programs.

In this Pydon't, you will:

 - learn about the idiomatic usage of `__name__` to create “main” functions in Python;
 - learn about the read-only attribute `__name__` that many built-in objects get;
 - see how `__name__` is used in a convention involving logging; and 
 - see some code examples of the things I will be teaching.

<!--v-->
 > You can now get your copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->


# What is `__name__`?

`__name__` is a special attribute in Python.
It is special because it is a dunder attribute,
which is just the name that we give, in Python,
to attributes whose names start and end with a double underscore.
(I explain in greater detail what a dunder attribute/method
is in [a previous Pydon't][pydont-dunder].)

You can [look `__name__` up in the Python documentation][docs-name-search],
and you will find two main results that we will cover here.
One of the results talks about `__main__` as a module attribute,
while the other result talks about `__main__` as an attribute
to built-in object types.


# The module attribute `__name__`

The most commonly known use case for `__name__` is as a module attribute,
when using `__name__` to create “main” functions in Python.
What this means is that you can use `__name__` to determine programmatically
if your code is being ran directly as a script or if it is being imported
from another module.

How can we do this?
Simple!

Go ahead and write the following line in your `print_name.py` file:

```py
print(__name__)
```

Now open your command line and run the script:

```bash
 > python print_name.py
__main__
```

What this is showing you is that the attribute `__name__`
was automatically set to `"__main__"` when you ran your code
as a script.
This is relevant because that is _not_ what happens when
you import your code from elsewhere.

As an example, go ahead and write the following line into your
`importer.py` file:

```py
import print_name
```

Then go ahead and run this new Python script:

```bash
 > python importer.py
print_name
```

Where is that `"print_name"` being printed from?
Well, the _only_ `print` statement you have is in the `print_name.py`
file, so that was definitely the place from where the printed value
came out.
Notice that some code got executed (and some things were
printed to the console) just by importing code from another module.

Also, notice that the value printed matches the name of the file
it came from.
Here, we see that `__name__` was automatically set to the name
of the file it was in (`print_name`) when the code from `print_name`
was imported from `importer`.

So, we see that `__name__` takes on different values depending
on whether the code is ran directly as a script or imported
from elsewhere.

When you write code, you often write a couple of functions that
help you solve your problem, and then you apply those functions
to the problem you have at hands.

For example,
[when I wrote some Python code to count valid passwords
in an efficient manner][blog-counting-passwords],
I wrote a class to represent an automaton in a file called
`automaton.py`:

```py
# automaton.py

class Automaton:
    # ...
```

That class was problem-agnostic, it just implemented some basic
behaviour related to automatons.
It just so happened that that behaviour was helpful for me to solve
the problem of counting passwords efficiently, so I imported that
`Automaton` class in another file and wrote a little program
to solve my problem.
Thus, we can say that the majority of the times that I will use
the code in my `automaton.py` file will be to import it from elsewhere
and to use it.

However, I also added a little demo of the functionality of the
`Automaton` class in the `automaton.py` file.
Now, the problem is that I don't want this little demo to run
every time the `Automaton` class is imported by another program,
so I have to figure out a way to only run the demo if the
`automaton.py` file is ran directly as a script...
The reason is that my demo code has some `print` statements that
wouldn't make sense to a user that just did `import automaton`
from within another script...
Imagine importing a module into your program and suddenly
having a bunch of prints in your console!

Now, we can use `__name__` to avoid that!
We have seen that `__name__` is set to `"__main__"` when a script
is ran directly, so we just have to check that:

```py
# automaton.py

class Automaton:
    # ...

if __name__ == "__main__":
    print("Demo code.")
```

This is the most well-known use case of `__name__`.
This is why you will commonly see snippets like

```py
if __name__ == "__main__":
    main()
```

It is just the Pythonic way of separating the functions and classes
and other definitions, that might be useful for you to import later on,
from the code that you only want to run if your program is the main
piece of code being executed.

By the way, this global variable `__name__` really is a variable
that just gets initialised without you having to do anything.
But you can assign to it, even though it is unlikely that you
might need to do that.
Hence, this code is perfectly valid:

```py
__name__ = "My name!"
if __name__ == "__main__":
    # This will never run:
    print("Inside the __main__ if.")
```


# `__name__` as an object type attribute

There is another Pythonic use case for the `__name__` attribute,
another common usage pattern that employs `__name__`.
This pattern I will teach you about now doesn't have to do
with module attributes, but with object type attributes.

As you may be aware, all objects have a type that tells you
“what” that object is.

Here are a couple of common types:

```py
>>> type(0.5)
<class 'float'>
>>> type("hello") 
<class 'str'>
>>> type(sum)   
<class 'builtin_function_or_method'>
```

Notice how the built-in `type` tells you what is the class
of which the object is an instance.
For example, `"hello"` is a string and that is why
`type("hello")` returns `<class 'str'>`,
which clearly contains the name of the class in there:
it is the `'str'` in there, between the single quotes.

With this information, how would you implement a `get_type_name`
function that only returns the string with the type name,
without the extra `<class>` fluff?

Give it some thought.

Here is a possibility:

```py
>>> def get_type_name(obj):
...     return str(type(obj)).split("'")[1]
...
>>> get_type_name("hello")   
'str'
>>> get_type_name(sum)     
'builtin_function_or_method'
```

Is this a good solution?
I think we can definitely do better.

The documentation tells us that many built-in object types
and definitions come with its `__name__` attribute,
which is “the name of the class, function, method, descriptor, or generator instance”.

Therefore, a better implementation of the `get_type_name` function
would be

```py
>>> def get_type_name(obj):
...     return type(obj).__name__
... 
>>> get_type_name("hello")
'str'
>>> get_type_name(sum)
'builtin_function_or_method'
```

This is much shorter, much cleaner (doesn't have nested function
calls, for example), and much easier to read, as the code _says_
what it is doing.
The name we picked for our function is good already,
because it is easy to make
an educated guess about what the function does,
but it is much better if the body of the function itself
makes it absolutely clear that we are getting what we want!

This ability of reaching out for the `__name__` of things is useful,
for example, when you want to print an error message because
you expected an argument of some type and, instead, you got something
else.
Using `__name__` you can get prettier error messages.

You can query the `__name__` of things other than built-in types.
You can also query the name of functions, for example:

```py
>>> sum.__name__
'sum'
>>> get_type_name.__name__
'get_type_name'
```

This might be relevant if you get ahold of a function in a programmatic
way and need to figure out what function it is:

```py
>>> import random
>>> fn = random.choice([sum, get_type_name])
>>> fn.__name__
'sum'
```

I don't think you are likely to receive a function from a
`random.choice` call, but this just shows how you can use
`__name__` to figure out what function you are looking at.

Another great thing that already comes with a `__name__` is
your custom classes.
If you define a class, `__name__` will be a very clean way
of accessing the pretty class name without having to jump
through too many hoops or doing hacky string processing:

```py
>>> class A():
...     pass
...
>>> A
<class '__main__.A'>
>>> A.__name__
'A'
>>> a = A()
>>> a
>>> type(a)
<class '__main__.A'>
>>> type(a).__name__
'A'
```

Similarly to the module `__name__`, the `__name__` attribute
of types, functions, etc, can be assigned directly:

```py
>>> type(a).__name__
'A'
>>> A.__name__ = "name..?"
>>> type(a).__name__
'name..?'
```

Sometimes this is useful, for example when you need to copy some
metadata from one object to another.


# Examples in code

I showed you what is the meaning that the `__name__` attribute
has, both as a module attribute and as an attribute of type objects,
and now I will show you how this knowledge can be put to practice.
I will be drawing my examples from the Python Standard Library,
as per usual.


## Defining a command line interface to the module code

If you take a look at the [`calendar`][calendar] module,
you will find functions to deal with calendars in various ways.
If you inspect the source code, you will see that the implementation
of the module ends with the following two lines:

```py
# From Lib/calendar.py in Python 3.9.2
if __name__ == "__main__":
    main(sys.argv)
```

In short, the module defines a series of functions that people might
want to import from elsewhere, but if the program is run directly,
then it will call the function `main`, passing it in whatever
arguments the program received from the terminal.

As an example, try running the following on your command line:

```bash
 > python -m calendar 2021 6
     June 2021
Mo Tu We Th Fr Sa Su
    1  2  3  4  5  6
 7  8  9 10 11 12 13
14 15 16 17 18 19 20
21 22 23 24 25 26 27
28 29 30
```

This shows a great example of how a module might have something
interesting/useful to run if it is started as the main program.
The function `main` in the `calendar` module implements a simple
command line interface with the [`argparse`][argparse] module,
and thus the `main` function is _simply_ an entry point to the
code that the module already defined.

Just out of curiosity, the Python Standard Library for my installation
of Python 3.9.2 has 2280 `.py` files, and if you look for it,
you can find the line `if __name__ == "__main__":` in 469 files,
a little over a fifth of the files...
So this really is a common pattern in Python!


## Pretty error messages

### Deleting from an enum

Like I mentioned before, if you want to get the name of the type
of an object, `type(obj).__name__` is likely to be the way to go
about doing that.

An example of where this shows up is in the [`enum`][enum] module.
The `enum` modules gives support to enumerations in Python,
which are sets of symbolic names (members) bound to unique,
constant values.

Here is an example enumeration:

```py
>>> import enum
>>> class Colour(enum.Enum):
...   RED = "RED"
...   GREEN = "GREEN"
...   BLUE = "BLUE"
...
```

Now we have a way of talking about specific colours:

```py
>>> Colour.RED
<Colour.RED: 'RED'>
>>> Colour.BLUE
<Colour.BLUE: 'BLUE'>
```

Now, what if we want to delete one of the colours?
For example, `GREEN`?

```py
>>> del Colour.GREEN
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "C:\Program Files\Python39\lib\enum.py", line 382, in __delattr__
    raise AttributeError("%s: cannot delete Enum member." % cls.__name__)
AttributeError: Colour: cannot delete Enum member.
```

We can't delete `GREEN` from the enum because the `GREEN` attribute
isn't just like any other class attribute, it is actually an integral
part of the enumeration structure, and so the implementers of
this module guarded the class against this type of deletions.

The question is, how did they get the pretty name of my `Colour` class?
I don't even have to show you the code, you can just look at the error
message above, that says we got an `AttributeError` inside the
`Colour` enum.
What was the line of code that produced this pretty error message?
It was the following line:

```py
raise AttributeError("%s: cannot delete Enum member." % cls.__name__)
```

In this line, `cls` is already a class,
something like this:

```py
>>> Colour          # <--
<enum 'Colour'>     # <-- this is what `cls` is...
                    # By the way, this is *not* a string.
```

So we could get its `__name__` directly and produce a pretty
error message, or at least as pretty as error messages go.


### Doing argument validation

Another similar use case can be found in the [`fractions`][fractions]
module.
This module provides a function,
called `from_decimal`,
to convert a `Decimal` number (from the [`decimal`][decimal] module)
into an exact fraction.
From my description of the function maybe you understood it,
but this `from_decimal` function expects a `decimal.Decimal` instance,
and errors out if the argument given is not such a thing:

```py
>>> import fractions
>>> fractions.Fraction.from_decimal("3") 
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "C:\Program Files\Python39\lib\fractions.py", line 188, in from_decimal
    raise TypeError(
TypeError: Fraction.from_decimal() only takes Decimals, not '3' (str)
```

Now the error message isn't enough because the code spans a couple
of different lines, but here is the type validation that the
`from_decimal` function performs:

```py
# From Lib/fractions.py in Python 3.9.2
class Fraction(numbers.Rational):
    # ...

    @classmethod
    def from_decimal(cls, dec):
        """Converts a finite Decimal instance to a rational number, exactly."""
        from decimal import Decimal
        if isinstance(dec, numbers.Integral):
            dec = Decimal(int(dec))
        elif not isinstance(dec, Decimal):
            raise TypeError(
                "%s.from_decimal() only takes Decimals, not %r (%s)" %
                (cls.__name__, dec, type(dec).__name__))
        return cls(*dec.as_integer_ratio())
```

Notice how the function takes a `dec` and tries to convert it to
a `Decimal` if the argument isn't a `Decimal` but is easy to treat
as one.
That is why giving `3` to the function doesn't give an error:

```py
>>> fractions.Fraction.from_decimal(3)
Fraction(3, 1)
```

However, `"3"` is not a `numbers.Integral` and it is also _not_
a `Decimal`, so `dec` fails the tests and we end up with

```py
raise TypeError(
    "%s.from_decimal() only takes Decimals, not %r (%s)" %
    (cls.__name__, dec, type(dec).__name__))
```

Notice how we even have two `__name__` usages here.
The first one is similar the example above with `Enum`,
and we take our `cls` (that is already a class) and simply
ask for its name.
That is the part of the code that built the beginning of the message:

```py
TypeError: Fraction.from_decimal() ...
           ^^^^^^^^
```

Then we print the value that actually got us into trouble, and that
is what the `dec` is doing there:

```py
TypeError: Fraction.from_decimal() only takes Decimals, not '3' ...
                                                            ^^^
```

Finally, we want to tell the user what it is that the user passed in,
just in case it isn't clear from the beginning of the error message.
To do that, we figure out the type of `dec` and then ask for its
`__name__`, hence the `type(dec).__name__` in the code above.
This is what produces the end of the error message:

```py
TypeError: Fraction.from_decimal() only takes Decimals, not '3' (str)
                                                                 ^^^
```

! The `"%s"` and `"%r"` in the string above have to do with string
! formatting, a topic that is yet to be covered in these Pydon'ts.
! [Stay tuned][subscribe] to be the first to know when those
! Pydon'ts are released.

This `type(obj).__name__` pattern is also very common.
In my 3.9.2 installation of the Python Standard Library,
it appeared 138 times in 74 different `.py` files.
The specific `cls.__name__` pattern also showed up a handful of times.


## Logging convention

For the final code example I will be showing you a common convention
that is practised when using the [`logging`][logging] module
to log your programs.

The `logging` module provides a `getLogger` function to the users,
and that `getLogger` function accepts a `name` string argument.
This is so that `getLogger` can return a logger with the specified
name.

On the one hand, you want to name your loggers so that, inside
huge applications, you can tell what logging messages came from where.
On the other hand, the `getLogger` function always returns the same
logger if you give it the same name, so that inside a single module
or file, you don't need to pass the logger around,
you can just call `getLogger` always with the same name.

Now, you want to get your logger by using always the same name and
you also want the name to identify clearly and unequivocally
the module that the logging happened from.
This shows that hand-picking something like `"logger"`
is a bad idea, as I am likely to pick
the same logger name as other developers picked in their code,
and so our logging will become a huge mess if our code interacts.

The other obvious alternative is to name it something specific
to the module we are in, like the file name.
However, if I set the logger name to the file name by hand,
I _know_ I will forget to update it if I end up changing the file name,
so I am in a bit of a pickle here...

Thankfully, this type of situation is a textbook example of when the
`__name__` attribute might come in handy!

The `__name__` attribute gives you a readable name that clearly
identifies the module it is from,
and using `__name__` even means that your
logging facilities are likely to behave well if your code interacts
with other code that also does some logging.

This is why using `getLogger(__name__)` is the recommended convention
in the documentation and that is why this pattern is used approximately
84% of the times!
(It is used in 103 `.py` files out of the 123 `.py` files that call
the `getLogger` function in the Python Standard Library.)


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*The `__name__` attribute is a dynamic attribute that tells you
 the name of the module you are in, or the name of the type of your
 variables.*”

This Pydon't showed you that:

 - `__name__` is a module attribute that tells you the name of the
 module you are in;
 - `__name__` can be used to tell if a program is being ran directly
 by checking if `__name__` is `__main__`;
 - you can and should use `__name__` to access the pretty name of
 the types of your objects;
 - `__name__` is an attribute that can be assigned to without
 any problem;
 - the `if` statement `if __name__ == "__main__":` is a very Pythonic
 way of making sure some code only runs if the program is ran
 directly;
 - the pattern `type(obj).__name__` is a simple way of accessing
 the type name of an object; and
 - there is a well-established convention that uses `__name__` to
 set the name of loggers when using the `logging` module.


If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-dunder]: /blog/pydonts/usages-of-underscore#leading-and-trailing-double-underscores
[blog-counting-passwords]: /blog/counting-passwords-with-automatons
[docs-name-search]: https://docs.python.org/3/search.html?q=__name__&check_keywords=yes&area=default
[calendar]: https://docs.python.org/3/library/calendar.html
[argparse]: https://docs.python.org/3/howto/argparse.html
[enum]: https://docs.python.org/3/library/enum.html
[fractions]: https://docs.python.org/3/library/fractions.html
[decimal]: https://docs.python.org/3/library/decimal.html#decimal.Decimal
[logging]: https://docs.python.org/3/library/logging.html
