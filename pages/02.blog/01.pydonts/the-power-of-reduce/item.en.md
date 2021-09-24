---
metadata:
    description: "In this Pydon't we will take a look at the `reduce` function, which used to be a built-in function and is currently in the `functools` module."
title: "The power of reduce | Pydon't 🐍"
---

In this Pydon't we will take a look at the `reduce` function,
which used to be a built-in function and is currently
in the `functools` module.

===

![A Python code snippet that uses short-circuiting.](thumbnail.svg)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

In this Pydon't I'll talk about `reduce`, a function that used
to be a built-in function and that was moved to the `functools`
module with Python 3.

Throughout all of the Pydon'ts I have been focusing only
on Python features that you can use without having to import
anything, so in that regard this Pydon't will be a little bit different.

In this Pydon't, you will:

 - see how `reduce` works;
 - learn about the relationship between `reduce` and `for` loops;
 - notice that `reduce` hides in a handful of other built-in functions we all know and love;
 - learn about a neat use-case for `reduce`;

<!--v-->
 > You can now get your copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->


# How `reduce` works

`reduce` is a tool that is typically associated with functional programming,
which is a programming paradigm that I feel sometimes is underappreciated.
In a short sentence, `reduce` takes an iterable and a binary function
(a function that takes two arguments),
and then uses that binary function to boil the iterable down to a single value.

This might sound weird or complicated, so the best thing I can do is to show
you a simplified implementation of reduce:

```py
def reduce(function, iterable, initial_value):
    result = initial_value
    for value in iterable:
        result = function(result, value)
    return result
```

If you look at it, there really isn't much going on inside that `for` loop:
we just keep updating the `result` variable with the argument `function`
and the consecutive values in the `iterable` argument.

But I can make this even easier to understand for you.
And, in order to do that, I just have to point out a bunch of `reduce`
use cases that you use all the time!
Perhaps the simplest one, and the one that shows up more often, is the `sum` built-in:

```py
>>> sum(range(10))
45
>>> from functools import reduce; import operator
>>> reduce(operator.add, range(10))
45
```

The `operator.add` there is just a way to programmatically refer to the built-in
addition with `+` in Python.

From the [documentation on `operator`][operator],

 > “The `operator` module exports a set of efficient functions corresponding to the
 intrinsic operators of Python.
 For example, `operator.add(x, y)` is equivalent to the expression `x+y`.”

You probably have seen `sum` before, right?
It just adds up all the elements in an iterable.
That's basically what `reduce` does, if the function we give it is the addition.
To make the connection clearer, let's implement our own `sum` function:

```py
def sum(iterable):
    acc = 0
    for elem in iterable:
        acc += elem
    return acc
```

Are you comfortable with the implementation above?
Now let me rejig it a little bit:

```py
def sum(iterable, start=0):
    acc = start
    for elem in iterable:
        acc = acc + elem
    return acc
```

Now, our `sum` function can start adding up at a different value
and we use the `operator.add` function instead of using `+`
or modified assignment `+=`.
Let us now stack this alternative implementation side by side
with the original `reduce` implementation:

```py
def sum(iterable, start=0):     # def reduce(function, iterable, initial_value):
    acc = start                 #     result = initial_value
    for elem in iterable:       #     for value in iterable:
        acc = acc + elem        #         result = function(result, value)
    return acc                  #     return result
```

Can you see how they are the same thing?


# The rabbit hole of the built-in reductions

## Some built-ins

Now that we have seen that `sum` is a reduction,
what other built-in functions are reductions?
Well, part of what a reduction does is taking an iterable
and _reducing_ it to a single value.
What built-in functions do that?

Going through the [list of built-in functions][built-in-functions-docs]
in the docs, here are some functions that catch my attention:

 - `all` – expects an iterable of truthy/falsy values and returns a Boolean;
 - `any` – expects an iterable of truthy/falsy values and returns a Boolean;
 - `max` – accepts an iterable of numbers and returns a single number;
 - `min` – accepts an iterable of numbers and returns a single number;
 - `sum` – we've seen this one already;

Can you implement all of these with a `for` loop?
Can you write all of these as reductions?

I'll give you a hand with the reductions:

```py
>>> all = lambda iterable: reduce(operator.and_, iterable)
>>> any = lambda iterable: reduce(operator.or_,  iterable)
>>> # Define `max` on iterables at the expense of just the binary max.
>>> max_ = lambda a, b: a if a >= b else b
>>> max = lambda iterable: reduce(max_,          iterable)
>>> # Define `min` on iterables at the expense of just the binary min.
>>> min_ = lambda a, b: a if a <= b else b
>>> min = lambda iterable: reduce(min_,          iterable)
>>> sum = lambda iterable: reduce(operator.add,  iterable)
```

I just find it very interesting that there are so many reductions amongst
the built-in functions!
That makes you think that `reduce` really is a powerful tool, right?
Given that it is worth adding _five_ specialised reductions
to the built-ins...


## Other common reductions

And there is more, of course.
If we use `operator.mul` (for multiplication),
then we get the `math.prod` function that we can use to multiply
all the numbers in an iterable:

```py
>>> from math import prod
>>> prod(range(1, 11))  # 10!
3628800
>>> reduce(operator.mul, range(1, 11))
3628800
```

What if you have a bunch of strings that you want to piece together?
For example, what if you have a list of words that you want to
put back together, separated by spaces?

```py
>>> words = ["Do", "I", "like", "reductions?"]
>>> " ".join(words)
'Do I like reductions?'
```

If we define “string addition” to be the concatenation of the two
strings, but with a space in the middle, then we get the same thing:

```py
>>> reduce(lambda s1, s2: s1 + " " + s2, words)
'Do I like reductions?'
```

Now, please don't get me wrong.
I am _not_ suggesting you start using `reduce` when you need to join strings.
I am _just_ trying to show you how these patterns are so common
and appear in so many places, even if you don't notice them.


# Why bother?

Why should you bother with knowing that `reduce` exists,
and how it works?
Because that is what “learning Python” means:
you need to be exposed to the library, to the built-ins,
you need to learn new algorithms, new ways of doing things,
new tools.

`reduce` is another tool you now have in your toolbelt.
Maybe it is not something you will use every day.
Maybe it is something you will use once a year.
Or even less.
But when the time comes, you _can_ use it,
and your code will be better for that:
because you know how to use the right tool for the job.

People learn a lot by building knowledge on top of the things
that they already learned elsewhere...
And the more you learn elsewhere, the more connections with
different things you can make, and the more things you can discover.
Maybe this article does nothing for you,
but maybe this article was the final push you needed to help
something else click.
Or maybe it feels irrelevant now, but in 1 week, 1 month,
or 1 year, something else will click _because_ you took the time
to learn about `reduce` and to understand how it relates
to all these other built-in functions.


# Far-fetched reductions

The reductions above were reductions that are more “normal”,
but we can do all kinds of interesting things with `reduce`!
Skip this section altogether if you are starting to feel confused
or repulsed by reductions, I don't want to damage your relationship
with `reduce` beyond repair.
This section contains some reductions that are – well, how to put
this nicely..? – that are not necessarily suitable for production.


## First and last

Here's is a little amusing exercise for you.
Can you write a reduction that, given an iterable,
returns its first element?
Similarly, can you write a reduction that, given an iterable,
returns its last element?

Give it some thought, really.

Ok, here are my proposed solutions:

```py
>>> left = lambda l, r: l
>>> reduce(left, range(10))
0
>>> right = lambda l, r: r
>>> reduce(right, range(10))
9
```

[I wrote the text above, a couple of hours went by, and then I came back.]

I have to be honest with you:
I started out thinking these are crazy, but in all honesty,
how do you write a function to retrieve the _last_ element
of an iterable?
Mind you, iterables are not necessarily indexable,
so something like `iterable[-1]` isn't guaranteed to work.
How would you do it?
You could write a `for` loop:

```py
def get_last(iterable):
    for elem in iterable:
        last = elem
    return last
```

But why is that any better than the alternative below?

```py
from functools import reduce
def get_last(iterable):
    return reduce(lambda l, r: r, iterable)
```

Feel free to leave your opinions in the comments below.
_I_ actually like the `reduce` alternative.


## Creating built-in types

Another couple of reductions I wouldn't recommend for production
are the replacements for `dict`, `list`, `set`, and `tuple`.
For example, if you have an iterable, how do you build the corresponding tuple?
How do you write that as a reduction?
Well, for this one you need to remember that `reduce` accepts
a third argument that is the initial value that we are modifying...

Do you get it?
The third argument needs to be an empty tuple:

```py
>>> reduce(lambda t, v: t + (v,), range(10), ())
(0, 1, 2, 3, 4, 5, 6, 7, 8, 9)
```

With similar workarounds, you can redefine `dict`, `list`, and `set`,
as reductions.
Again, not that I recommend that.


# The identity element...

## ...or lack thereof

We have seen some reductions already and, if you were brave enough,
you even took a sneak peek at some crazy reductions in the previous section.
However, up until now, I have been (purposefully) not giving much
attention to the third argument to `reduce`.
Let us discuss it briefly.

First, why do we need a third argument to `reduce`?
Well... because we like things to work:

```py
>>> from functools import reduce
>>> import operator
>>> sum([1, 2])
3
>>> reduce(operator.add, [1, 2])
3
>>> sum([1])
1
>>> reduce(operator.add, [1])
1
>>> sum([])
0
>>> reduce(operator.add, [])
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: reduce() of empty sequence with no initial value
```

From a strictly practical point of view,
the third argument to `reduce` exists so that `reduce` can know
what to return in case the given iterable is empty.
This means that, in general, you don't need to worry
about that argument if you know your iterables are never going
to be empty...

The documentation is quite clear with regards to how it uses this
third argument, to which they refer as `initializer`:

 > “If the optional `initializer` is present,
 > it is placed before the items of the iterable in the calculation,
 > and serves as a default when the iterable is empty.
 > If `initializer` is not given and iterable contains only one item,
 > the first item is returned.” [`functools.reduce` Python 3 docs, 8th June 2021].

So, in practical terms, you only _really_ need the `initializer` when
the iterable is empty, and therefore you should use it when it might
happen that you pass an empty iterable into `reduce`.


## What is the identity element

So, if you cannot be 101% sure your iterable is not going to be empty,
how do you decide what value to use in the third argument to `reduce`?
How do you pick the `initializer` argument?
Well, the value that `initializer` should have depends on the function
you are using in your reduction and, in particular,
the `initializer` should be an identity element for that function.
What does that mean?

Again, from a very practical perspective, the identity element is
a special element with a very special behaviour:
the identity element is such that, if the iterable is not empty,
having the identity element or not should be exactly the same thing.
In other words, when in the presence of other values,
the identity element should have no effect at all.

For example, if we are multiplying a list of numbers, what is the identity
element that we should feed `reduce` with?
What is the number that, when multiplied by some other numbers, does exactly nothing?
It is `1`:

```py
>>> from functools import reduce
>>> reduce(operator.mul, range(4, 10))
60480
>>> reduce(operator.mul, range(4, 10), 1)
60480
```

For the built-in reductions, you can generally figure out what the identity
element is by trying to call the reduction with an empty iterable:

```py
>>> sum([])
0
>>> import math
>>> math.prod([])
1
>>> all([])
True
>>> any([])
False
>>> max([])
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: max() arg is an empty sequence
```

`max` and `min` are interesting reductions because,
from the mathematical point of view, they have suitable
identity elements:

 - for `max`, the identity element is `-∞`; and
 - for `min`, the identity element is `∞`.

Why is that?
Again, because these are the values that will not impact
the final result when mixed in with other numbers.

Take a look at the following excerpt from my session:

```py
>>> max(float("-inf"), 10)
10
>>> max(float("-inf"), -132515632534250)
-132515632534250
>>> max(float("-inf"), 67357321)
67357321
```

These six lines of the session show three instances
of how calling `max` with minus infinity as one of the arguments
always returns the other one, because no number is smaller
than minus infinity.

However, `max` and `min` will throw an error if you call
them with empty iterables, _even though_ there is an identity
element that you could use.

```py
>>> max([])
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: max() arg is an empty sequence
```

Maybe they do this so that people don't have to deal with
infinities in their programs?
I honestly don't know!

!!! **Edit**: I went online and [asked people][so-max-min],
!!! and the answer that made the most sense to me is that
!!! `max` and `min` can be used with any comparable objects,
!!! and for other objects, the infinities might make
!!! absolutely no sense.
!!!
!!! For example, `max("abc", "da")` returns `"da"`,
!!! and when comparing strings it really makes no sense
!!! to add `float("-inf")` to the mix.


## The identity element doesn't always exist

There are some operations that look like sensible reductions
but that just _don't_ have an identity element.
Even if you skipped the section on “scary” reductions,
I showed you a reduction that does not have an identity element.
Can you spot it?
(Drop a comment below with your guess).

If you have an operation for which you cannot find an identity element,
then you are either going down the wrong road –
and you really shouldn't use a reduction –
or you need to wrap your reduction with an `if`-statement or a
`try` statement (take a look at [this][eafp-lbyl-pydont] article
to help you understand which one to choose).


# Why some people dislike `reduce`

If `reduce` is such a powerful tool,
why was it moved from the built-ins into `functools`?
More importantly, why do people dislike `reduce`?

There is [some information][guido] online about why
`reduce` was moved into `functools`,
but I can only speak about my experience with `reduce` and
how I have seen people around me react to it.

One of the things I have seen is that people look at `reduce`
as if it were a tool that people only use when they are trying
to be smart, but I think that is just prejudice against `reduce`.
Sometimes, it may be difficult to draw the line between what is
code that is worth having people think about for a bit,
versus code that isn't.

Furthermore, sometimes functions like `reduce` are
used in convoluted academic exercises, or in brain-teasers,
that are meant just to jog your brain.
People then forget those are not indications
of how `reduce` should be used in the wild, and build these
bitter feelings for such wonderful tools.


# Examples in code

I looked for usages of `reduce` in the Python Standard Library and I didn't
find many, but I found one usage pattern (in two different places) and I just
found it to be really elegant, and that's what I am sharing with you here.

Other than that, even if you are not explicitly using `reduce`,
just remember that functions like `sum`, `math.prod`, `max`, `min`, `all`, `any`, etc,
are pervasive in our code and, whether you like it or not,
you are using reductions in your own code.


## Reaching inside nested dictionaries

In case you want to take a look at the original pattern,
you can find it in the `importlib.metadata.EntryPoint.load` function,
but I'll change it a little bit to make it simpler.

Say you have a series of nested dictionaries:

```py
>>> d = {"one": {2: {"c": {4: 42}}}}
```

Now, say that you want to access the nested `42` through a series
of successive key accesses that you have in a list:

```py
>>> keys = ["one", 2, "c", 4]
```

How do you reach the inner `42`?
Well, you can write a loop:

```py
>>> d = {"one": {2: {"c": {4: 42}}}}
>>> val = d
>>> for key in keys:
...     val = val[key]
... 
>>> val
42
```

But we can, once more, compare that `for` loop with the definition
of the reduction:

```py
                        # def reduce(function, iterable, initial_value):
val = d                 #     result = initial_value
for key in keys:        #     for value in iterable:
    val = val[key]      #         result = function(result, value)
val                     #     return result
```

So we can see the structure is very similar!
We just have to figure out what is the correct function to use,
and that is `dict.get`:

```py
>>> reduce(dict.get, keys, d)
42
```

Isn't this neat?


## Reaching inside nested classes

Similarly, we can use this pattern to programmatically access
class attributes that are deeply nested.

Let me define a class with nothing in it:

```py
>>> class C:
...     pass
...
```

Now, let me create a couple of instances and nest them:

```py
>>> c = C()
>>> c.one = C()
>>> c.one._2 = C()
>>> c.one._2.c = C()
>>> c.one._2.c._4 = 42
```

If I have the base instance `c`, and if I have the names
of the successive attributes that lead to `42`, how do I get there?
Well, instead of using `dict.get`, we can use `getattr`:

```py
>>> attrs = ["one", "_2", "c", "_4"]
>>> reduce(getattr, attrs, c)
42
```

! I'll be writing about `getattr` soon, so be sure to [subscribe]
! to stay tuned.


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Reductions are classical techniques that you use frequently,
 even if you do not realise you are doing so!*”

This Pydon't showed you that:

 - `reduce` takes an iterable of objects and applies a function
 successively, to build a single final object;
 - `reduce` was a built-in function in Python 2 and in Python 3
 it lives in the `functools` module;
 - reductions can be converted to `for` loops and back following
 a very well-defined pattern;
 - built-in functions like `sum`, `max`, `min`, `any`, and `all`,
 are reductions;
 - a reduction can work with an optional third argument,
 to initialise the process, and that element is supposed to be
 the identity element of the function you are using;
 - not all functions have identity elements;
 - the `operator` module allows you to access built-in operations,
 like addition and subtraction, and pass them around your code; and
 - `reduce` can be used to reach programmatically inside nested
 dictionaries or class attributes.

If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[eafp-lbyl-pydont]: /blog/pydonts/eafp-and-lbyl-coding-styles
[operator]: https://docs.python.org/3/library/operator.html
[built-in-functions-docs]: https://docs.python.org/3/library/functions.html
[guido]: https://www.artima.com/weblogs/viewpost.jsp?thread=98196
[so-max-min]: https://stackoverflow.com/q/67894680/2828287
