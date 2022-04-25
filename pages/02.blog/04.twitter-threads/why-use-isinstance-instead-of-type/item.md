---
date: 25-04-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This article explains why you should use `isinstance` instead of `type` and its relationship to Python duck typing."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: why-use-isinstance-instead-of-type
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Why use isinstance instead of type?"
---

This article explains why you should use `isinstance` instead of `type` and its relationship to Python duck typing.

===

Python 🐍 has 2 built-ins that are often misused: `type` and `isinstance`.

The built-in `type` tells you the type of an object...

So, many beginners think you should use `type` to check the type of an object.

Sounds reasonable!

But here's why you should use `isinstance`:

```py
>>> type(3)  # Tells me the type of `3` is `int`
<class 'int'>

>>> type(3.14)  # Tells me the `type` of `3.14` is `float`.
<class 'float'>

>>> x = 4.0  # Is 4.0 an integer or a float?
>>> if type(x) == int:  # Seems like a reasonable check, right?
...     print("'Tis an int!")
... else:
...     print("It's a float.")
...
'Tis an int!
```


“Python is a dynamically typed language.”

What does this mean?

It means that the types of things are dynamic – they can change.

For example, a variable `x` can start by holding a string, which can then change into an integer, and then into a list:

```py
>>> x = "3"
>>> type(x)
<class 'str'>

>>> x = int(x)
>>> type(x)
<class 'int'>

>>> x = [None] * x
>>> type(x)
<class 'list'>
```


This also means that, when you write a function, you can't tell what types of arguments you'll get.

E.g., I may write a function to compute square roots of numbers.

I want numbers.

But nothing stops you from calling my function with a string:

```py
>>> def sqrt(x):
...     return pow(x, .5)
... 
>>> sqrt("sqrt goes BOOM")
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "<stdin>", line 2, in sqrt
TypeError: unsupported operand type(s) for ** or pow(): 'str' and 'float'
```


The error above may be surprising.

I called `sqrt` and get an error in `**`/`pow`..?

So, maybe you should check the type of the argument.

Let's check if it's an integer or a float, and if it's not, we complain more accurately.

That's when you might think of using `type`:

```py
def sqrt(x):
    if type(x) not in (int, float):
        raise TypeError("Can only compute square roots of ints and floats.")
    return pow(x, .5)
```


But when you write code like this, you are not taking into account Python's duck typing.

What's duck typing?

"If it walks like a duck and it quacks like a duck, then it must be a duck."


In other words, you don't really *need* the argument to be *really* an `int` or a `float`.

For example, imagine I create a toy class for positive floats only.

Notice how little I wrote to define `Pos` below...

And yet, I can already do plenty with `Pos` objects:

```py
>>> class Pos(float):
...     def __init__(self, value):
...         if value <= 0:
...             raise ValueError("Pos MUST be positive.")
... 
>>> p = Pos(4.51234) 
>>> f"The value of p squared is approximately {pow(p, 2):.2f}."
'The value of p squared is approximately 20.36.'
```


How can I use `pow` and string formatting with instances of `Pos`?

Because, for the purposes of `pow` and formatting, `Pos` objects look *a lot* like floats.

`pow` works on instances of `Pos`...

So, can I compute square roots of my instances of `Pos`?

As of now, not really:

```py
>>> sqrt(p)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "<stdin>", line 3, in sqrt
TypeError: Can only compute square roots of ints and floats.
```


Instances of `Pos` behave pretty much like floats, so maybe we could change the function `sqrt`.

Instead of checking if we really **have** floats or integers...

We can just check if the argument **looks** like a float or an integer!

If it does, compute the square root!


With me so far?

Because this is the essence of duck typing!

If something walks like a duck and quacks like a duck, just pretend it's a duck and get it over with.

How do we check, in Python 🐍, if something looks like a duck?

We use `isinstance`:

```py
>>> p = Pos(4.51234)
>>> def sqrt(x):
...     if not isinstance(x, (int, float)):
...         raise TypeError("Can only compute square roots of ints and floats.")
...     return pow(x, .5)
... 
>>> sqrt(p)
2.124226918198713
```


Most of the times, you don't need a specific type, so no need to use `type`.

You just need things that _look_ like those types, hence you use `isinstance`.

Can you go back to some code you wrote previously and find a check with `type` that you can replace with `isinstance`?


I hope this thread cleared some doubts you might have had about `isinstance` and/or duck typing in Python.

If you have questions, feel free to ask them at the beginning of the thread.

I like writing about Python, so follow me @mathsppblog if you like reading about Python 😉


TL;DR:

When writing code:

 - don't check if you *really* have a duck (`type`);
 - instead, check if you have something that looks enough like a duck (`isinstance`).

I hope this made sense!

Retweet the beginning of the thread if it did 🚀

And I'll see you around 👋


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1518684114634907648) I published on Twitter [@mathsppblog](https://twitter.com/mathsppblog).
