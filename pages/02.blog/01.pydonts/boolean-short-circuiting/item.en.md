---
metadata:
    description: "In this Pydon't we explore what Boolean short-circuiting for the `and` and `or` operators is, and how to use this functionality to write more expressive code."
title: "Boolean short-circuiting | Pydon't 🐍"
---

In this Pydon't we explore what Boolean short-circuiting
for the `and` and `or` operators is, and how to use this
functionality to write more expressive code.

===

![A Python code snippet that uses short-circuiting.](thumbnail.svg)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

In this Pydon't we will take a closer look at how `and` and `or` really
work and at a couple of really neat things you can do because of the
way they are defined.
In particular, we will look at

 - the fact that `and` and `or` return values from their operands,
 and not necessarily `True` or `False`;
 - what “short-circuiting” is and how to make the best use of it;
 - how short-circuiting in `and` and `or` extends to `all` and `any`; and
 - some expressive use-cases of Boolean short-circuiting.

For this Pydon't, I will assume you are familiar with what “Truthy”
and “Falsy” values are in Python.
If you are not familiar with this concept, or if you would like just a
quick reminder of how this works, go ahead and read the
[“Truthy, Falsy, and `bool`” Pydon't][truthy-pydont].

<!--v-->
 > You can now get your copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->

# Return values of the `and` and `or` operators

If we [take a look at the docs][docs-and-or-not],
here is how `or` is defined:

 > “`x or y` returns `y` if `x` is false, otherwise it returns `x`.”

Equivalently, but written with an `if` expression,

```py
(x or y) == (y if not x else x)
```

This may not seem like it is worth spending a thought on,
but already right at this point we can see something very interesting:
even though we look at the truthy or falsy value of `x`,
what we return are the _values_ associated with `x`/`y`,
and _not_ a Boolean value.

For example, look at the program below and think about what it outputs:

```py
if 3 or 5:
    print("Yeah.")
else:
    print("Nope.")
```

If you thought it should print “Yeah.”, you are right!
Notice how `3 or 5` was the condition of the `if` statement
and it evaluated to `True`, which is why the statement under `if` got executed.

Now, look at the program below and think about what it outputs:

```py
print(3 or 5)
```

What do you think it outputs?
If you think the output should be `True`, you are wrong!
The program above outputs `3`:

```py
>>> 3 or 5
3
```

Let's go back to something I _just_ said:

 > “*Notice how `3 or 5` was the condition of the `if` statement
 > and it evaluated to `True`, which is why the statement under `if` got executed.*”

The wording of this statement is wrong,
but the error in it is fairly subtle.
If you spotted it before I pointed it out,
give yourself a pat in the back, you deserve it.
So, what did I say wrong?

`3 or 5` _does not_ evaluate to `True`!
It evaluates to `3`, which is _truthy_ and therefore
tells the `if` to execute its statements.
Returning `True` or a truthy value is something significantly
different.

A similar thing happens with `and`.
[As per the docs][docs-and-or-not], `and` can be defined as follows:

 > “`x and y` returns `x` if `x` is false, otherwise it returns `y`.”

We can also rewrite this as

```py
(x and y) == (x if not x else y)
```

Take your time to explore this for a bit,
just like we explored `x or y` above.


# Short-circuiting

You might be asking why this distinction is relevant.
It is mostly relevant because of the following property:
`and` and `or` only evaluate the right operand if the left operand is
not enough to determine the result of the operation.
This is what short-circuiting is:
not evaluating the whole expression (stopping short of evaluating it)
if we already have enough information to determine the final outcome.

This short-circuiting feature, together with the fact that
the boolean operators `and` and `or` return the values of the operands
and not necessarily a Boolean, means we can do some really neat things
with them.


## `or`

### `False or y`

`or` evaluates to `True` if any of its operands is truthy.
If the left operand to `or` is `False` (or falsy, for that matter)
then the `or` operator _has_ to look to its right operand in order
to determine the final result.

Therefore, we know that an expression like

```py
val = False or y
```

will have the value of `y` in it,
and in an `if` statement or in a `while` loop,
it will evaluate the body of the construct only if `y` is truthy:

```py
>>> y = 5   # truthy value.
>>> if False or y:
...     print("Got in!")
... else:
...     print("Didn't get in...")
...
Got in!
>>> y = []  # falsy value.
>>> if False or y:
...     print("Got in 2!")
... else:
...     print("Didn't get in 2...")
...
Didn't get in 2...
```

Let this sit with you:
if the left operand to `or` is `False` or falsy, then we _need_
to look at the right operand to determine the value of the `or`.


### `True or y`

On the other hand, if the left operand to `or` is `True`,
we do not need to take a look at `y` because we already know
the final result is going to be `True`.

Let us create a simple function that returns its argument unchanged
but that produces a side-effect of printing something to the screen:

```py
def p(arg):
    print(f"Inside `p` with arg={arg}")
    return arg
```

Now we can use `p` to take a look at the things that Python
evaluates when trying to determine the value of `x or y`:

```py
>>> p(False) or p(3)
Inside `p` with arg=False
Inside `p` with arg=3
3
>>> p(True) or p(3)
Inside `p` with arg=True
True
```

Notice that, in the second example, `p` only did one print
because it never reached the `p(3)`.


### Short-circuiting of `or` expressions

Now we tie everything together.
If the left operand to `or` is `False` or falsy, we know that
`or` has to look at its right operand and will, therefore,
return the value of its right operand after evaluating it.
On the other hand, if the left operand is `True` or truthy,
`or` will return the value of the left operand without
even evaluating the right operand.


## `and`

We now do a similar survey, but for `and`.

### `False and y`

`and` gives `True` if _both_ its operands are `True`.
Therefore, if we have an expression like

```py
val = False and y
```

do we need to know what `y` is in order to figure out what `val` is?
No, we do not, because regardless of whether `y` is `True` or
`False`, `val` is always `False`:

```py
>>> False and True
False
>>> False and False
False
```

If we take the `False and y` expressions from this example and compare
them with the `if` expression we wrote earlier, which was

 > ```py
 > (x and y) == (x if not x else y)
 > ```

we see that, in this case, `x` was substituted by `False`,
and, therefore, we have

```py
(False and y) == (False if not False else y)
```

Now, the condition inside that `if` expression reads

```py
not False
```

which we know evaluates to `True`, meaning that the `if` expression
never returns `y`.

If we consider any left operand that can be `False` or falsy,
we see that `and` will never look at the right operand:

```py
>>> p([]) and True  # [] is falsy
Inside `p` with arg=[]
[]
>>> p(0) and 3242   # 0 is falsy
Inside `p` with arg=0
0
>>> p({}) and 242   # {} is falsy
Inside `p` with arg={}
{}
>>> p(0) and p(0)   # both are falsy, but only the left matters
Inside `p` with arg=0
0
```


### `True and y`

Now, I invite you to take a moment to work through the same reasoning,
but with expressions of the form `True and y`.
In doing so, you should figure out that the result of
such an expression is always the value of `y`,
because the left operand being `True`, or any other truthy value,
doesn't give `and` enough information.


### Short-circuiting of `and` expressions

Now we tie everything together.
If the left operand to `and` is `False` or falsy,
we know the expression returns the value of the left operand
regardless of the right operand, and therefore we do not even evaluate
the right operand.
On the other hand, if the left operand to `and` is `True`,
then `and` will evaluate the right operand and return its value.


# Short-circuiting in plain English

Instead of memorising rules about what sides get evaluated when,
just remember that both `and` and `or` will evaluate as many
operands as needed to determine the overall Boolean result,
and will then return the value of the last side that they evaluated.

As an immediate conclusion, the left operand is _always_ evaluated,
as you might imagine.

If you understand that, then it is just a matter of you knowing
how `and` and `or` work from the Boolean perspective.


# `all` and `any`

The built-in functions `all` and `any` _also_ short-circuit,
as they are simple extensions of the behaviours provided by
`and` and `or`, respectively.

`all` wants to make sure that _all_ the values of its argument
are truthy, so as soon as it finds a falsy value, it knows
it's game over.
That's why [the docs][docs-all] say `all` is equivalent to
the following code:

```py
def all(it):
    for elem in it:
        if not elem:
            return False
    return True
```

Similarly, `any` is going to do its best to look for _some_
value that is truthy.
Therefore, as soon as it finds one, `any` knows it has achieved
its purpose and does not need to evaluate the other values.

Can you write an implementation of `any` that is similar to the
above implementation of `all` and that also short-circuits?


# Short-circuiting in chained comparisons

A [previous Pydon't][chaining-comparisons-pydont] has shown you
that comparison operators can be chained arbitrarily,
and those are almost equivalent to a series of comparisons
separated with `and`, except that the subexpressions are only
evaluated once, to prevent wasting resources.
Therefore, because we are also using an `and` in the background,
chained comparisons can also short-circuit:

```py
# 1 > 2 is False, so there's no need to look at p(2) < p(3)
>>> p(1) > p(2) < p(3)
Inside `p` with arg=1
Inside `p` with arg=2
False
```


# Examples in code

Now that we have taken a look at how all of these things work,
we will see how to put them to good use in actual code.


## Short-circuit to save time

One of the most basic usages of short-circuiting is to save time.
When you have a `while` loop or an `if` statement with multiple
statements, you may want to include the faster expressions before
the slower ones, as that might save you some time if the result
of the first expression ends up short-circuiting.


### Conditionally creating a text file

Consider this example that should help me get my point across:
imagine you are writing a function that
creates a helper `.txt` file but only if it is a `.txt` file
and if it does not exist yet.

With this preamble, your function needs to do two things:
 - check the suffix of the file is `.txt`;
 - check if the file exists in the filesystem.

What do you feel is faster?
Checking if the file ends in `.txt` or looking for it in the whole
filesystem?
I would guess checking for the `.txt` ending is simpler,
so that's the expression I would put first in the code:

```py
import pathlib

def create_txt_file(filename):
    path = pathlib.Path(filename)
    if filename.suffix == ".txt" and not path.exists():
        # Create the file but leave it empty.
        with path.open():
            pass
```

This means that, whenever `filename` does not respect the `.txt`
format, the function can exist right away and doesn't even
need to bother the operating system with asking if the file
exists or not.


### Conditionally checking if a string matches a regular expression

Now let me show you a real example of an `if` statement that uses
short-circuiting in this way, saving some time.
For this, let us take a look at a function from the [`base64`][base64] module,
that we take from the Python Standard Library:

```py
# From Lib/base64.py in Python 3.9.2
def b64decode(s, altchars=None, validate=False):
    """Decode the Base64 encoded bytes-like object or ASCII string s.
    [docstring cut for brevity]
    """
    s = _bytes_from_decode_data(s)
    if altchars is not None:
        altchars = _bytes_from_decode_data(altchars)
        assert len(altchars) == 2, repr(altchars)
        s = s.translate(bytes.maketrans(altchars, b'+/'))
    if validate and not re.fullmatch(b'[A-Za-z0-9+/]*={0,2}', s):   # <--
        raise binascii.Error('Non-base64 digit found')
    return binascii.a2b_base64(s)
```

This `b64decode` function takes a string (or a bytes-like object)
that is assumed to be in base 64 and decodes it.

Here is a quick demo of that:

```py
>>> import base64
>>> s = b"Base 64 encoding and decoding."
>>> enc = base64.b64encode(s)
>>> enc
b'QmFzZSA2NCBlbmNvZGluZyBhbmQgZGVjb2Rpbmcu'
>>> base64.b64decode(enc)
b'Base 64 encoding and decoding.'
```

Now, look at the `if` statement that I marked with a comment:

```py
if validate and not re.fullmatch(b'[A-Za-z0-9+/]*={0,2}', s):
    pass
```

`validate` is an argument to `b64decode` that tells the function if we should
validate the string that we want to decode or not,
and then the `re.fullmatch()` function call does that validation,
ensuring that the string to decode only contains valid base 64 characters.
In case we want to validate the string and the validation fails,
we enter the `if` statement and raise an error.

Notice how we _first_ check if the user wants to validate the string
and only then we run the regular expression match.
We would obtain the exact same result if we changed the order of the operands
to `and`, but we would be spending much more time than needed.

To show that, let us try both cases!
Let's build a string with 1001 characters, where only the last one is invalid.
Let us compare how much time it takes to run the boolean expression
with the regex validation before and after the Boolean `validate`.

```py
import timeit

# Code that sets up the variables we need to evaluate the expression that we
# DO NOT want to be taken into account for the timing.
setup = """
import re
s = b"a"*1000 + b"*"
validate = False 
"""

# with    short-circuiting: 0.01561140s on my machine.
print(timeit.timeit("validate and not re.fullmatch(b'[A-Za-z0-9+/]*={0,2}', s)", setup))
# without short-circuiting: 27.4744187s on my machine.
print(timeit.timeit("not re.fullmatch(b'[A-Za-z0-9+/]*={0,2}', s) and validate", setup))
```

Notice that short-circuiting speeds up these comparisons by a factor of ~1750.

!!! The [`timeit`][timeit] module is great and I recommend you take a peek
!!! at its docs.
!!! Here, we use it to run that Boolean expression repeatedly
!!! (one million times, to be more specific).

Of course we could try longer or shorter strings, we could try strings
that pass the validation and we could also try strings that fail the validation
at an earlier stage, but this is just a small example that shows how
short-circuiting can be helpful.


## Short-circuit to flatten `if` statements

Short-circuiting can, and should, be used to keep `if` statements
as flat as possible.


### Conditional validation

A typical usage pattern is when we want to do some validation
if certain conditions are met.

Keeping the previous `b64decode` example in mind, that previous `if` statement
could've been written like so:

```py
# Modified from Lib/base64.py in Python 3.9.2
def b64decode(s, altchars=None, validate=False):
    """Decode the Base64 encoded bytes-like object or ASCII string s.
    [docstring cut for brevity]
    """
    s = _bytes_from_decode_data(s)
    if altchars is not None:
        altchars = _bytes_from_decode_data(altchars)
        assert len(altchars) == 2, repr(altchars)
        s = s.translate(bytes.maketrans(altchars, b'+/'))
    # Do we want to validate the string?
    if validate:                                            # <--
        # Is the string valid?
        if not re.fullmatch(b'[A-Za-z0-9+/]*={0,2}', s):    # <--
            raise binascii.Error('Non-base64 digit found')
    return binascii.a2b_base64(s)
```

Now we took the actual validation and nested it,
so that we have two separate checks: one tests if we need to do validation
and the other one does the actual validation.
What is the problem with this?
From a fundamentalist's point of view, you are clearly going against
[the Zen of Python][zop-pydont], that says

 > “Flat is better than nested.”

But from a practical point of view, you are also increasing the vertical
space that your function takes up by having a ridiculous `if` statement
hang there.
What if you have multiple conditions that you need to check for?
Will you have a nested `if` statement for each one of those?

This is exactly what short-circuiting is useful for!
Only running the second part of a Boolean expression if it is relevant!


### Checking preconditions before expression

Another typical usage pattern shows up when you have something you need
to check, for example you need to check if a variable `names` is a list
containing strings or you need to check if a given argument `term`
is smaller than zero.
It may happen that, in that context, it is not a good idea to do those checks
immediately:

 - the variable `names` might not be a list or might be empty; or
 - the argument `term` might be of a different type and, therefore,
 might be incomparable to zero.

Here is a concrete example of what I mean:

```py
# From Lib/asynchat in Python 3.9.2
def set_terminator(self, term):
    """Set the input delimiter.

    Can be a fixed string of any length, an integer, or None.
    """
    if isinstance(term, str) and self.use_encoding:
        term = bytes(term, self.encoding)
    elif isinstance(term, int) and term < 0:
        raise ValueError('the number of received bytes must be positive')
    self.terminator = term
```

This is a helper function from within the [`asynchat`][asynchat] module.
We don't need to know what is happening outside of this function to
understand the role that short-circuiting has in the `elif` statement.
If the `term` variable is smaller than `0`, then we want to raise
a `ValueError` to complain, but the previous `if` statement shows
that `term` might also be a string.
If `term` is a string, then comparing it with 0 raises another
`ValueError`, so what we do is start by checking a necessary
precondition to `term < 0`:
`term < 0` only makes sense if `term` is an integer, so we start
by evaluating `isinstance(term, int)` and only then running the comparison.

Let me show you another example from the [`enum`][enum] module:

```py
# From Lib/enum.py in Python 3.9.2
def _create_(cls, class_name, names, *, module=None, qualname=None, type=None, start=1):
    """
    Convenience method to create a new Enum class.
    """
    # [cut for brevity]

    # special processing needed for names?
    if isinstance(names, str):
        names = names.replace(',', ' ').split()
    if isinstance(names, (tuple, list)) and names and isinstance(names[0], str):
        original_names, names = names, []
        last_values = []
        for count, name in enumerate(original_names):
            value = first_enum._generate_next_value_(name, start, count, last_values[:])
            last_values.append(value)
            names.append((name, value))

    # [cut for brevity]
```

The longer `if` statement contains three expressions separated by `and`s,
and the first two expressions are there to make sure that the final one,

```py
isinstance(names[0], str)
```

makes sense.
You can read along the statement and thing about what it means if execution
reaches that point:

```py
if isinstance(names, (tuple, list)) and names and isinstance(names[0], str):
#^ lets start checking this `if` statement.

if isinstance(names, (tuple, list)) and names and isinstance(names[0], str):
#                                   ^
# we only need to take a look at the right-hand side of this `and` if `names`
# is either a tuple or a list.

if isinstance(names, (tuple, list)) and names and isinstance(names[0], str):
#                                             ^
# at this point, I've checked if `names` is a list or a tuple and I have
# checked if it is truthy or falsy (i.e., checked if it is empty or not).
# I only need to look at the right-hand side of this `and` if `names`
# is NOT empty.

if isinstance(names, (tuple, list)) and names and isinstance(names[0], str):
#                                                 ^
# If I'm evaluating this expression, it is because `names` is either a
# list or a tuple AND it is not empty, therefore I can index safely into it
# with `names[0]`.
```

This flat `if` statement is much better than the completely nested version:

```py
if isinstance(names, (tuple, list)):
    if names:
        if isinstance(names[0], str):
            pass
```

Of course, you might _need_ the nested version if, at different points,
you might need to do different things depending on what happens.
For example, suppose you want to raise an error if the list/tuple
is empty.
In that case, you would need the nested version:

```py
if isinstance(names, (tuple, list)):
    if names:
        if isinstance(names[0], str):
            pass
    else:
        raise ValueError("Empty names :(")
```

Can you understand why this if statement I just wrote is different
from the two following alternatives?

```py
# Can I put `and names` together with the first check?
if isinstance(names, (tuple, list)) and names:
    if isinstance(names[0], str):
        pass
else:
    raise ValueError("Empty names..? :(")


# What if I put it together with the second `isinstance` check?
if instance(names, (tuple, list)):
    if names and isinstance(names[0], str):
        pass
    else:
        raise ValueError("Empty names..? :(")
```

If this is a silly exercise for you, sorry about that!
I just want you to be aware of the fact that when you have
many Boolean conditions, you need to be careful when checking
specific configurations of what is `True` and what is `False`.


## Define default values

### How it works

If you've been skimming this article, just pay attention to this section right here.
This, right here, is my favourite use of short-circuiting.
Short-circuiting with the Boolean operator `or` can be used to assign default
values to variables.

How does this work?
This uses `or` and its short-circuiting functionality to assign a default
value to a variable if the current value is falsy.
Here is an example:

```py
greet = input("Type your name >> ") or "there"
print(f"Hello, {greet}!")
```

Try running this example and press <kbd>Enter</kbd> without
typing anything.
If you do that, `input` returns an empty string `""`,
which is falsy.
Therefore, the operator `or` sees the falsy value on its left
and needs to evaluate the right operand to determine the final
value of the expression.
Because it evaluates the right operand, it is the right
value that is returned, and `"there"` is assigned to `greet`.


### Ensuring a list is not empty

Now that we've seen how this mechanism to assign default values
works, let us take a look at a couple of usage examples
from the Python Standard Library.

We start with a simple example from the `collections` module,
specifically from the implementation of the
[`ChainMap`][collections-chainmap] object:

```py
# From Lib/collections/__init__.py in Python 3.9.2
class ChainMap(_collections_abc.MutableMapping):
    ''' A ChainMap groups multiple dicts (or other mappings) together
    [docstring cut for brevity]
    '''

    def __init__(self, *maps):
        '''Initialize a ChainMap by setting *maps* to the given mappings.
        If no mappings are provided, a single empty dictionary is used.

        '''
        self.maps = list(maps) or [{}]          # always at least one map
```

This `ChainMap` object allows you to combine multiple mappings
(for example, dictionaries) into a single mapping that combines
all the keys and values.

```py
>>> import collections
>>> a = {"A": 1} 
>>> b = {"B": 2, "A": 3}
>>> cm = collections.ChainMap(a, b)
>>> cm["A"]
1
>>> cm["B"]
2
```

The assignment that we see in the source code ensures that
`self.maps` is a list of, at least, one empty mapping.
If we give no mapping at all to `ChainMap`, then `list(maps)`
evaluates to `[]`, which is falsy, and forces the `or` to look
at its right operand, returning `[{}]`:
this produces a list with a single dictionary that has nothing inside.


### Default value for a mutable argument

I'll share another example with you, now.
This example might look like the same as the one above,
but there is a nice subtlety here.

First, the code:

```py
# From Lib/cgitb.py in Python 3.9.2
class Hook:
    """A hook to replace sys.excepthook that shows tracebacks in HTML."""

    def __init__(self, display=1, logdir=None, context=5, file=None,
                 format="html"):
        self.display = display          # send tracebacks to browser if true
        self.logdir = logdir            # log tracebacks to files if not None
        self.context = context          # number of source code lines per frame
        self.file = file or sys.stdout  # place to send the output
        self.format = format
```

This code comes from the [`cgitb`][cgitb] module and defines `sys.stdout`
to be the default value for the `self.file` variable.
The definition of the `__init__` function has `file=None` as a keyword
argument _also_ with a default value of `None`,
so why don't we just write `file=sys.stdout` in the first place?

The problem is that `sys.stdout` can be a mutable object,
and therefore, using `file=sys.stdout` as a keyword argument with a default
value is not going to work as you expect.
This is easier to demonstrate with a list as the default argument,
although the principle is the same:

```py
>>> def append(val, l=[]):
...   l.append(val)
...   print(l)
... 
>>> append(3, [1, 2])
[1, 2, 3]
>>> append(5)
[5]
>>> append(5)
[5, 5]
>>> append(5)
[5, 5, 5]
```

Notice the three consecutive calls `append(5)`.
We would expect the three calls to behave the same way,
but because a list is a mutable object, the three consecutive
calls to `append` add the values to the default value itself,
that started out as an empty list but keeps growing.

! I'll write about mutability in more detail in future Pydon'ts,
! so be sure to [subscribe] to not miss that future Pydon't.


## Find witnesses in a sequence of items

As the final usage example of short-circuiting, I'll share something
really neat with you.

If you use [assignment expressions and the walrus operator `:=`][walrus-pydont]
together with generator expressions, we can use the fact that
`all` and `any` also short-circuit in order to look for “witnesses”
in a sequence of elements.

If we have a predicate function `predicate` (a function that returns
a Boolean value) and if we have a sequence of values, `items`,
we could use

```py
any(predicate(item) for item in items)
```

to check if any element(s) in `items` satisfy the `predicate` function.

If we modify that to be

```py
any(predicate(witness := item) for item in items)
```

Then, in case any `item` satisfies the predicate function, `witness`
will hold its value!

For example, if `items` contains many integers, how do we figure out
if there are any odd numbers in there and how do we print the first one?

```py
items = [14, 16, 18, 20, 35, 41, 100]
any_found = False
for item in items:
    any_found = item % 2
    if any_found:
        print(f"Found odd number {item}.")
        break

# Prints 'Found odd number 35.'
```

This is one alternative.
What other alternatives can you come up with?

Now, compare all those with the following:

```py
items = [14, 16, 18, 20, 35, 41, 100]
is_odd = lambda x: x % 2
if any(is_odd(witness := item) for item in items):
    print(f"Found odd number {witness}.")

# Prints 'Found odd number 35.'
```

Isn't this neat?


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Be mindful when you order the left and right operands to
 the `and` and `or` expressions, so that you can make the most out of
 short-circuiting.*”

This Pydon't showed you that:

 - `and` and `or` return the value of one of its operands,
 and not necessarily a Boolean value;
 - both Boolean operators short-circuit:
   - `and` only evaluates the right operand if the left operand
   is truthy;
   - `or` only evaluates the right operand if the left operand
   is falsy;
 - the built-in functions `all` and `any` also short-circuit;
 - short-circuiting also happens in chained comparisons, because
 those contain an implicit `and` operator;
 - using short-circuiting can save you a lot of computational time;
 - nested structures of `if` statements can, sometimes, be flattened
 and simplified if we use short-circuiting with the correct ordering
 of the conditions;
 - it is customary to use short-circuiting to test some preconditions
 before applying a test to a variable;
 - another great use-case for short-circuiting is to assign
 default values to variables and function arguments,
 especially if the default value is a mutable value; and
 - short-circuiting, together with the walrus operator `:=`,
 can be used to find a witness value with respect to a predicate
 function.

If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[truthy-pydont]: /blog/pydonts/truthy-falsy-and-bool
[walrus-pydont]: /blog/pydonts/assignment-expressions-and-the-walrus-operator
[chaining-comparisons-pydont]: /blog/pydonts/chaining-comparison-operators
[zop-pydont]: /blog/pydonts/pydont-disrespect-the-zen-of-python
[docs-and-or-not]: https://docs.python.org/3/library/stdtypes.html#boolean-operations-and-or-not
[docs-all]: https://docs.python.org/3/library/functions.html#all
[base64]: https://docs.python.org/3/library/base64.html
[timeit]: https://docs.python.org/3/library/timeit.html
[asynchat]: https://docs.python.org/3/library/asynchat.html
[enum]: https://docs.python.org/3/library/enum.html
[collections-chainmap]: https://docs.python.org/3/library/collections.html#collections.ChainMap
[cgitb]: https://docs.python.org/3/library/cgitb.html
