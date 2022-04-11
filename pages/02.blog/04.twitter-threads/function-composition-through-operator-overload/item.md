---
date: 11-04-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "Let me show you how to do function composition through operator overloading, and other cool tricks!"
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: function-composition-through-operator-overload
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Function composition through operator overload."
---

Let me show you how to do function composition through operator overloading, and other cool tricks!

===

Here is some Python 🐍 code using

 - decorators
 - callable classes
 - custom operators
 - “functional” function composition
 - arbitrary args and kwargs

```py
>>> class F:
...     def __init__(self, f):
...         self.f = f
...     def __call__(self, *args, **kwargs):
...         return self.f(*args, **kwargs)
...     def __or__(self, other):
...         return F(lambda *args, **kwargs: other(self(*args, **kwargs)))
...
>>> @F
... def add_two(n):
...     return n + 2
...
>>> @F
... def divide_3_floor(n):
...     return n // 3
...
>>> s = F(str)
>>> f = add_two | divide_3_floor | s
>>> f(7)
'3'
```

Here is a breakdown of everything that is going on 👇


Let us start... from the end!

```py
>>> f = add_two | divide_3_floor | s
>>> f(7)
'3'
```

The assignment to `f` takes 3 functions:

 - `add_two`
 - `divide_3_floor`
 - `s`

and then combines them.

The `|` is in charge of doing this combination, but what combination is this?


The `|` is doing some “magic” I asked it to do, this is not default behaviour in Python.

`f = g | h` means that the function `f` corresponds to calling `h` after calling `g`.

So, `f(x)` actually means `h(g(x))`.


In my code, I wrote `f = add_two | divide_3_floor | s`.

So, when I write `f(7)`, it actually means something else:

```py
>>> f = add_two | divide_3_floor | s
>>> f(7)
'3'
>>> s(divide_3_floor(add_two(7)))
'3'
```


Formally speaking, the operator pipe `|` is being used to do function composition.

In other words, by using `|` I have another way of composing functions in a convenient way.

But I lied 😅 `add_two`, `divide_3_floor`, and `s`, aren't “functions” per se...


Did you notice how I defined `s`?

```py
s = F(str)
```

I created `s` by taking the built-in `str` and putting it through `F`.

But what IS `F`?

It's just a class I created, right?

But it's a very special class 😉


The class `F` only expects an argument `f`.

```py
class F:
    def __init__(self, f):
        self.f = f
    # ...
```

I used a lowercase `f`, not because I wanted to be mysterious, but because `f` is mathematician-speak for a function.

So, to create an instance of `F`, I just feed it a function.

So, `s = F(str)` creates an `F` out of `str`.


Then, the instance of `F` just stores that function in `self.f`.

Why?

For later use 🙃

The idea is that instances of `F` **behave** like the functions passed in originally.

So, if an instance of `F` must behave like a function, it must be callable.

Call-huh?


In other words, after I write `s = F(str)`, I need to be able to write `s(...)`.

I need to be able to call `s` (hence, callable).

That is why I needed to implement the dunder method `__call__`.

```py
class F:
    # ...
    def __call__(self, *args, **kwargs):
        return self.f(*args, **kwargs)
    # ...
```

The dunder method `__call__` is responsible for making instances of `F` callable.


So, what's the behaviour of `s` when we call it with `s(...)`?

To understand that, we need to look at `__call__`.

First, let us understand the signature:

```py
def __call__(self, *args, **kwargs):
    # ...
```

What do the `*args*` and `**kwargs` do?

Think with me:

Instances of `F` must act like the original functions, right?


But the original functions might be **arbitrary** functions, right?

The original function can be a function with 0 or 3 arguments.

It can be a function with an argument called `this_is_just_an_argument`.

We can't _guess_ how many/what arguments we'll have...


So, we have to plan for _any_ and _all_ cases!

How do we do that?

With `*args` and `**kwargs`.

By using `*args`, a function can receive however many **positional** arguments:

```py
>>> def func_with_args(*args):
...     print(args)
...
>>> func_with_args()
()
>>> func_with_args(15, True)
(15, True)
```


Similarly, by using `**kwargs`, a function can receive however many **keyword** arguments:

```py
>>> def func_with_kwargs(**kwargs):
...     print(kwargs)
...
>>> func_with_kwargs(a=15, b=True)
{'a': 15, 'b': True}
>>> func_with_kwargs()
{}
```

Oh, by the way, here is an important remark:


This cool behaviour comes from using `*` and `**` on the left of the parameters.

The words `args` and `kwargs` are just the common choice in Python!

We can pick other names:

```py
>>> def func_with_args_kwargs(*banana, **split):
...     print(banana)
...     print(split)
...
>>> func_with_args_kwargs()
()
{}
>>> func_with_args_kwargs(15, True, stuff=None, info="hey")
(15, True)
{'stuff': None, 'info': 'hey'}
```


Now we can understand the signature of `__call__`:

`def __call__(self, *args, **kwargs)`

But there is more!

What about the body of the definition?

Why do we use `*args` and `**kwargs` again..?

```py
class F:
    # ...
    def __call__(self, *args, **kwargs):
        return self.f(*args, **kwargs)
    # ...
```


By using `*args` and `**kwargs` again, we are trying to unpack the arguments we got so far.

By unpacking those arguments, we pass them down to `self.f`, which is the original function:

```py
>>> s = F(str)
>>> s(15)
'15'
```

Can you trace the journey that the `15` makes?


One good thing you can do is modify `__call__` to print `args` and `kwargs` before the `return`.

That will help you visualise things!

It's also something I do very often:

I tweak the code I'm studying to see what is really going on.


Now we know what is going on with `__call__`...

But what's up with `__or__`..?

The dunder method `__or__` is what is called when we use `|` next to instances of `F`.

But what gives?


By implementing `__or__`, we hijack the operator `|` and we get to use it for whatever we want!

In this case, we want `|` to be able to combine instances of `F` with each other.

How do we do that?

“Simple”:


Remember that `F`s are supposed to look like functions.

When we combine two `F`s, we want to create a new one that represents the two `F`s in succession.

Let us use the built-ins `int` and `bin` to see what happens when we do `F(int) | F(bin)`.

```py
>>> int("15")  # String to integer.
15
>>> bin(15)    # Integer to binary representation.
'0b1111'
```


When we write `F(int) | F(bin)`, we want to combine the `F(int)` with the `F(bin)`.

Also, when we write `F(int) | F(bin)`, the dunder `__or__` is called.

When that happens, we go inside the `__or__` that looks like this:

```py
class F:
    # ...
    def __or__(self, other):
        return F(lambda *args, **kwargs: other(self(*args, **kwargs)))
```

What's what?


Inside `__or__`, after writing `F(int) | F(bin)`, these are the arguments:

 - `self` is `F(int)`; and
 - `other` is `F(bin)`.

We want the combination to be an `F` that calls `bin` after calling `int`:

```py
>>> int_then_bin = F(int) | F(bin)
>>> int_then_bin("15")
'0b1111'
```

We want the function inside `other` after the function inside `self`.


And we want the whole thing to be an `F`.

Why?

So that we can later combine that _again_ if we want to!

That explains part of what is going on:

```py
class F:
    # ...
    def __or__(self, other):
        return F(...other(self(...)))
```


What are we missing?

Not too much, now!

Remember how instances of `F` are created?

We need to feed `F` with a function.

So, in order to combine `F(int)` with `F(bin)`, which are `self` and `other`, respectively, we need to wrap them in a function.


We could define a regular function with `def` and whatnot, but I opted for something leaner.

By using a `lambda`, I can write a function that wraps `self` and `other`:

```py
>>> self = F(int)
>>> other = F(bin)
>>> func = lambda *args, **kwargs: other(self(*args, **kwargs))
>>> func("15")
'0b1111'
```


In the end, we just add the `F` around that so that the lambda can also be combined with other things.

The lambda, by itself, is a regular lambda.

If I put it inside an `F`, we get one of these cool things that can be combined with each other:

```py
>>> self = F(int)
>>> other = F(bin)
>>> func = lambda *args, **kwargs: other(self(*args, **kwargs))
>>> func
<function <lambda> at 0x000001E60733E940>
>>> F(func)
<__main__.F object at 0x000001E6073169D0>
```


The final piece of the puzzle is the `@F` on top of the definitions of `add_two` and `divide_3_floor`:

```py
>>> @F
... def add_two(n):
...     return n + 2
...
>>> @F
... def divide_3_floor(n):
...     return n // 3
...
```

By using the keyword `def` I can create regular Python functions, right?

But I want these to be cool functions I can combine.

So, they need to go through `F`.

But how?


They could've gone through `F` like `str` did:

```py
>>> def add_two(n):
...     return n + 2
...
>>> add_two = F(add_two)
>>> def divide_3_floor(n):
...     return n // 3
...
>>> divide_3_floor = F(divide_3_floor)
```

Because I only care about the cool version of these functions, I can reuse the same name.

(For `s` and `str` I used two names: `s = F(str)`.)


Now, take a look at the pattern that emerges!

I took the functions `add_two` and `divide_3_floor` and tweaked them.

Instead of letting them be vanilla functions, I added some functionality.

I decorated them with some bells and whistles.

I **decorated** them...


This is the pattern of decorators!

Instead of doing the final assignment by hand, after making the functions go through `F`, I can just write `@F` at the top.

By writing the `@F`, Python does that assignment for me.


This is a lot to digest!

My number 1 advice?

Write the code and play with it.

Add prints.

Test your understanding.

For your convenience, this thread is on my site.

This means you can bookmark the link or go there and copy & paste the code: https://mathspp.com/blog/twitter-threads


Here are some of the things we have seen:

 - callable classes
 - custom operators for classes (the `|`)
 - decorators (and classes as decorators)
 - `*args` and `**kwargs`
 - function composition
 - lambdas
 - and more!

Follow me @mathsppblog for more 😉


But wait, there is even MORE!

My original snippet of code wasn't like this.

There was a subtle (but not so subtle!) difference.

Here is the original code: https://twitter.com/mathsppblog/status/1512495768300572674

Can you spot the differences?

Can you explain them?

Hint: look at the final result.


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1513621382818680840) I published on Twitter [@mathsppblog](https://twitter.com/mathsppblog).
