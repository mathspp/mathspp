---
metadata:
    description: "In this Pydon't you'll learn to boost your Python productivity by making the best use possible of the Python REPL."
title: "Boost your productivity with the REPL | Pydon't 🐍"
---

In this Pydon't you'll learn how to make the best use possible of the Python REPL.

===

![A representation of a Python REPL that was just fired up](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

The REPL is an amazing tool that every Python programmer should really know and appreciate!
Not only that, but you stand to gain a lot if you get used to using it and if you learn
to make the most out of it 😉

In this Pydon't, you will:

 - learn what “REPL” stands for;
 - understand how important the REPL is for your learning;
 - understand the mechanism that “prints” results in the REPL;
 - see how to recover the previous result in the REPL, in case you forgot to assign it;
 - learn about the built-in help system;
 - learn some tips for when you're quickly hacking something together in the REPL;
 - be told about two amazing tools to complement your usage of the REPL.


<!--v-->
 > You can now get your free copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->


# REPL

Read. Evaluate. Print. Loop.

That's what “REPL” stands for, and it is often referred to as “read-eval-print-loop”.
The REPL is the program that takes your input code (i.e., reads your code),
evaluates it, prints the result, and then repeats (i.e., loops).

The REPL, sometimes also referred to as the “interactive session”,
or the “interpreter session”,
is what you get when you open your computer's command line and type `python` or `python3`.

That should result in something like the following being printed:

```bash
Python 3.9.2 (tags/v3.9.2:1a79785, Feb 19 2021, 13:44:55) [MSC v.1928 64 bit (AMD64)] on win32
Type "help", "copyright", "credits" or "license" for more information.
>>>
```

Of course, the exact things that are printed (especially the first line)
are likely to differ from what I show here, but it's still the REPL.

(By the way, if you ever need to leave the REPL, just call the `exit()` function.)


# Just fire up the REPL

The REPL is, hands-down, one of your best friends when you are writing Python code.
Having a REPL to play around with just makes it much easier to learn the language.

Can't remember the argument order to a built-in function?
Just fire up the REPL.

Need to do a quick computation that is just a bit too much for the conventional desktop calculator?
Just fire up the REPL.

Can't remember how to spell that module you want to import?
Just fire up the REPL.

You get the idea.

I _cannot_ stress this enough.
Get used to the REPL.
Play with it.
Write code in it.
As soon as you become familiar with it, you'll love it and thank me for that.


# REPL mechanics

## Basic input and output

The REPL generally contains a `>>>` in the beginning of the line, to the left of your cursor.
You can type code in front of that prompt and press Enter.
When you press Enter, the code is evaluated and you are presented with the result:

```py
>>> 3 + 3
6
```

## Multiline input

The REPL also accepts code that spans multiple lines, like `if` statements,
`for` loops, function definitions with `def`, etc.

In order to do those, just start typing your Python code regularly:

```py
>>> if True:
```

When you press Enter after the colon,
Python realises the body of the `if` statement is missing,
and thus starts a new line containing a `...` on the left.
The `...` tells you that this is the _continuation_ of what you started above.

In order to tell Python you are done with the multiline code blocks
is by pressing Enter on an empty line with the continuation prompt `...`:

```py
>>> if True:
...     print("Hello, world!")
...
Hello, world!
>>>
```


## Pasting into the REPL

Pasting into the REPL should work without any problem.

For example, the function below returns the double of the input.
Try copying it into your REPL and then using it.

```py
def double(x):
    return 2 * x
```

However, if you try to copy and paste a multiline block that contains
empty lines in the middle, then the REPL will break your definition.

For example, if you try pasting the following, you get an error:

```py
def double(x):

    return 2 * x
```

Copying the code above and pasting it into the session,
you will end up with a session log like this:

```py
>>> def double(x):
... 
  File "<stdin>", line 2

    ^
IndentationError: expected an indented block
>>>     return 2 * x
  File "<stdin>", line 1
    return 2 * x
IndentationError: unexpected indent
```

This happens because the REPL finds a blank line
and thinks we tried to conclude the definition of the function.


## Implicit printing of results

One last thing you should know about the REPL is that it implicitly “prints”
the results of the expressions you type.

I wrote “prints” in quotes because the REPL doesn't really print the result,
it just shows its _representation_.
The _representation_ of an object is what you get when you call `repr` on the object.
If you explicitly `print` something, then what you get is the result of calling `str` on it.

I wrote a [very detailed Pydon't][pydont-str-and-repr] explaining the differences between the two,
so let me just _show_ you how things are different:

```py
# Define a string.
>>> s = "Hello\nworld!"
# Print its `str` and `repr` values:
>>> print(str(s))
Hello
world!
>>> print(repr(s))
'Hello\nworld!'
# Print the string explicitly and evaluate it in the REPL.
>>> print(s)
Hello
world!
>>> s
'Hello\nworld!'
```

As you can see, printing `s` or just typing it in the REPL
gives two different results.
Just be mindful of that.


## No printing, or `None`

In particular, if the expression you wrote evaluates to `None`,
then nothing gets printed.

The easiest way to see this is if you just type `None` in the REPL.
Nothing gets displayed; contrast that with what happens if you just type `3`:

```py
>>> None
>>> 3
3
```

If you call a function that doesn't have an explicit return value,
or that returns `None` explicitly,
then those functions will not show anything in the REPL:

```py
>>> def explicit_None_return():
...     # Return None explicitly.
...     return None
...
>>> explicit_None_return()      # <- nothing gets displayed.

>>> def implicit_None_return():
...     # Ending without a `return` returns `None` implicitly.
...     pass
...
>>> implicit_None_return()      # <- nothing gets displayed.
```


## Repeated imports

Sometimes it is useful to use the REPL to quickly import a function you just defined.
Then you test the function out and then proceed to changing it in the source file.
Then you'll want to import the function again and test it again, except that won't work.

You need to understand how the REPL handles imports,
because you can't import repeatedly to “update” what's in the session.

To show you this, go ahead and create a file `hello.py`:

```py
# In `hello.py`:
print("Being imported.")
```

Just that.

Now open the REPL:

```py
>>> import hello
Being imported!
```

Now try modifying the string inside the `print`, and re-import the module:

```py
>>> import hello
Being imported!
# Modify the file, then import again:
>>> import hello
>>>
```

Nothing happens!
That's because Python already went through your file and knows what's in there,
so it doesn't need to parse and run the file again.
It can just give you the functions/variables you need.

In short, if you modify variables, functions, code; and you need those changes to be reflected in the REPL,
then you need to leave the REPL with `exit()`, start it again, and import things again.

That's why some of the tips for quick hacks I'll share below are so helpful.

!!! Edit: Another alternative – brought to my attention by a kind reader –
!!! is to use `importlib.reload(module)` in Python 3.4+.
!!! In our example, you could use `importlib.reload(hello)`:

```py
>>> import hello
Being imported
>>> import importlib            # Use `imp` from Python 3.0 to Python 3.3
>>> importlib.reload(hello)
Being imported
<module 'hello' from 'C:\\tmp\\hello.py'>
```

We get that final line because `importlib.reload` returns the module
it reloaded.

You can take a look at [this StackOverflow question and answers][stackoverflow-importlib-reload]
to learn a bit more about this approach.

Be mindful that it may not work as you expect when you have multiple imports.
Exiting the REPL and opening it again may be the cleanest way to reload your imports
in those situations.


## REPL history

I'll be honest with you, I'm not entirely sure if what I'm about to describe
is a feature of the Python REPL or of all the command lines I have worked with
in my entire life, but here it goes:

You can use the up and down arrow keys to go over the history of expressions you already entered.
That's pretty standard.

What's super cool is that the REPL remembers this history of expressions,
even if you exit the REPL, _as long as you don't close the terminal_.


# The last result

If you read my [Pydon't about the usages of underscore][pydont-underscores-last-repl-result]
you might know this already,
but you can use the underscore `_` to retrieve the result of the last expression
if you want to use it and forgot to assign.

Here is a silly example:

```py
>>> 3 + 6
9
>>> _ + 10
19
```

This might come in handy when you call a function or run some code that takes a long time.
For example, downloading something from the Internet.

It can also be helpful if you just ran an expression with side-effects
and you don't want to run that again because you don't want to trigger the side-effects twice.
For example, if you just made a call to an API.

Of course `_` is a valid variable name in and out of itself,
so you can still use it as a variable name.
If you do, however, then `_` will stop reflecting the result of the last expression:

```py
>>> _ = 0
>>> _
0
>>> 3 + 9
12
>>> _
0           # <- it still evaluates to 0!
```

If you want to get back the magical behaviour of `_` holding the result of the last expression,
just delete `_` with `del _`.


# Getting help from within the REPL

Another great feature that is often underappreciated is the built-in help system.
If you need to take a look at a quick reference for a built-in function,
for example, because you forgot what the arguments are, just use `help`!

```py
>>> help(sum)
Help on built-in function sum in module builtins:

sum(iterable, /, start=0)
    Return the sum of a 'start' value (default: 0) plus an iterable of numbers

    When the iterable is empty, return the start value.
    This function is intended specifically for use with numeric values and may
    reject non-numeric types.

>>>
```

What is great about this `help` built-in is that it can even provide help
about _your_ own code, provided you document it well enough.

Here is the result of calling `help` on a function defined by you:

```py
>>> def my_function(a, b=3, c=4):
...     return a + b + c
... 
>>> help(my_function)
Help on function my_function in module __main__:

my_function(a, b=3, c=4)

>>>
```

You can see that `help` tells you the module where your function was defined
and it also provides you with the signature of the function,
default values and all!

To get more information from `help` you need to document your function with a docstring:

```py
>>> def my_function(a, b=3, c=4):
...     """Return the sum of the three arguments."""
...     return a + b + c
... 
>>> help(my_function)
Help on function my_function in module __main__:

my_function(a, b=3, c=4)
    Return the sum of the three arguments.

>>>
```

Now you can see that the `help` function also gives you the information
stored in the docstring.

! I'll be writing a Pydon't about docstrings soon.
! Be sure to [subscribe to my newsletter][subscribe]
! so you don't miss it!


# Tips for quick hacks

The Python REPL is amazing when you need to flesh an idea out,
as it allows you to quickly test some code, tweak it,
and iterate over that repeatedly with instant feedback.

It goes without saying, but the REPL is not a replacement for your IDE!
However, sometimes it helps to know about a couple of little tricks
that you can employ to help you make the most out of your REPL.


## Semicolons

Yes, **really**.

Python supports semicolons to separate statements:

```py
>>> a = 3; b = a + 56; print(a * b)
177
```

However, this feature is something that often does **not** belong in your code,
so refrain from using it.

Despite being generally inadequate for production code,
the semicolons are your best friends when in the REPL.
I'll explain it to you, and you'll agree.

In the command line you can usually use the up and down arrows
to cycle through the most recently typed commands.
You can do that in the REPL as well.
Just try evaluating a random expression,
then press the up arrow and Enter again.
That should run the exact same expression again.

Sometimes you will be working in the REPL testing out a solution
or algorithm incrementally.
However, if you make a mistake, you must reset everything.

At this point, you just press the arrows up and down,
furiously trying to figure out all the code you have ran already,
trying to remember which were the correct expressions and which ones
were wrong...

Semicolons can prevent that!
You can use semicolons to keep track of your whole “progress” as you go:
whenever you figure out the next step,
you can use the arrows to go up to the point where you last “saved your progress”
and then you can add the correct step at the end of your sequence of statements.

Here is an example of an interactive REPL session of me trying to order
a list of names according to a list of ages.

Instead of two separate assignments, I put them on the same line with `;`:

```py
>>> names = ["John", "Anna", "Bill"]; ages = [20, 40, 30]
```

I could have written

```py
>>> names, ages = ["John", "Anna", "Bill"], [20, 40, 30]
```

but using the semicolon expresses the intent of having the two assignments
in separate lines when it comes time to write the real code down.

Then, I will try to see how to put the ages and names together in pairs:

```py
>>> [(age, name) for name, age in zip(names, ages)]
[(20, 'John'), (40, 'Anna'), (30, 'Bill')]
```

However, at this point I realise I'm being redundant and I can just use `zip`
if I reverse the order of the arguments:

```py
>>> list(zip(ages, names))
[(20, 'John'), (40, 'Anna'), (30, 'Bill')]
```

Now that I'm happy with how I've paired names and ages together,
I use the arrow keys to go back to the line with the assignment.
Then, I use a semicolon to add the new piece of code I worked out:

```py
>>> names = ["John", "Anna", "Bill"]; ages = [20, 40, 30]; info_pairs = zip(ages, names)
```

! `zip` is an amazing tool in Python and is one of my favourite
! built-in functions.
! You can learn how to wield its power with [this Pydon't][pydont-zip-up].

Now I can move on to the next step, knowing that a mistake now won't be costly:
I can reset everything by going up to the line with all the intermediate steps and run that single line.


## Not changing lines

When you want to define a simple multiline block, you can often get away
with inlining what comes after the colon.

For example, instead of

```py
>>> for i in range(3):
...     print(i)
...
0
1
2
```

you can write

```py
>>> for i in range(3): print(i)
...
0
1
2
```

While this is style that is _not_ recommended for production code,
it makes it more convenient to go up and down the REPL history.

If you really want to push the boundaries,
you can even combine this with semicolons:

```py
>>> i = 1
>>> while i < 30: print(i); i *= 2
...
1
2
4
8
16
```


## Import, test, loop

If you are writing some code and want to take it for a spin –
just to make sure it makes sense –
fire up the REPL, import the code, and play with it!
That's the magic of the REPL.

Be sure to do any setup for the “tests” in a single line separated with semicolons,
together with the import statements.
That way, when you tweak the code you just wrote, you can type `exit()` to leave the REPL,
enter it again, and then with a couple of up-arrow presses you get your setup code intact
and are ready to play with it again.


# Other tools

I try to stick to vanilla Python as much as possible when writing these Pydon'ts, for one simple reason:
the world of vanilla Python is huge and, for most developers, has lots of untapped potential.

However, I believe I would be doing you a disservice if I didn't mention two tools
that can really improve your experience in/with the REPL.


## Rich

 > “Rich is a Python library for rich text and beautiful formatting in the terminal.”

[Rich][rich-gh] is an open source library that I absolutely love.
You can read the documentation and the examples to get up to speed with Rich's capabilities,
but I want to focus on a very specific one, in particular:

```py
>>> from rich import pretty
>>> pretty.install()
```

Running this in your REPL will change your life.
With these two lines, Rich will pretty-print your variables and even include highlighting.


## IPython

[IPython][ipython] is a command shell for interactive computing in multiple programming languages,
originally developed for the Python programming language.
IPython offers introspection, rich media, shell syntax, tab completion, and history,
among other features.

In short, it is a Python REPL with more bells and whistles.

It is beyond the scope of this Pydon't to tell you all about IPython,
but it is something I had to mention (even though I personally don't use it).


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Get comfortable with using the REPL because that will make you a more efficient Python programmer.*”

This Pydon't showed you that:

 - the REPL is a great tool to help prototype small ideas and solutions;
 - the REPL supports multiline input, and breaks it after an empty line;
 - the REPL implicitly shows the result of the expressions you type,
 with the caveat that what is shown is an objects representation (`repr`), not its string value (`str`);
 - you can use the arrows to navigate the history of the code you typed in the REPL;
 - history of typed code is preserved after you exit the REPL, as long as you don't close the terminal window;
 - `None` results don't get displayed implicitly;
 - repeatedly importing the same module(s) does not update their contents;
 - you can access the result of the previous line using `_`;
 - the `help` built-in can give you basic documentation about the
 functions, and other objects, you have “lying around”; it even works
 on user-defined objects;
 - by using docstrings, you improve the utility of the built-in
 `help` when used on custom objects;
 - although not recommended best practices, the usage of semicolons and in-line multiline statements can save you time when navigating the history of the REPL;
 - Rich is a tool that you can use in your REPL to automatically
 pretty-print results with highlighting;
 - IPython is an alternative Python REPL that comes with even more bells and whistles.

<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!
<!-- ^ -->

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-str-and-repr]: /blog/pydonts/str-and-repr
[pydont-underscores-last-repl-result]: https://mathspp.com/blog/pydonts/usages-of-underscore#recovering-last-result-in-the-session
[pydont-zip-up]: /blog/pydonts/zip-up
[rich-gh]: https://github.com/willmcgugan/rich
[ipython]: https://ipython.org/
[stackoverflow-importlib-reload]: https://stackoverflow.com/q/684171/2828287
