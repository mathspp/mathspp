Learn how to remove extra spaces from a string using regex, string splitting, a fixed point, and `itertools.groupby`.

===

In this article you'll learn about three different ways in which you can remove extra spaces from the middle of a string.
That is, you'll learn how to go from a string like

```py
string = "This is  a   perfectly    normal     sentence."
```

to a string like

```py
string = "This is a perfectly normal sentence."
```

## The best solution to remove extra spaces from a string

The best solution for this task, which is both readable and performant, uses the regex module `re`:

```py
import re

def remove_extra_spaces(string):
    return re.sub(" {2,}", " ", string)
```

The function `sub` can be used to **sub**stitute a pattern for a replacement you specify.
The pattern `" {2,}"` finds runs of 2 or more consecutive spaces and replaces them with a single space.

## String splitting

Using the string method `split` can also be a good approach:

```py
def remove_extra_spaces(string):
    return " ".join(string.split(" "))
```

If you're using string splitting, you'll want to provide the space `" "` as an argument.
If you call `split` with no arguments, you'll be splitting on _all_ whitespace, which is not what you want if you have newlines and other whitespace characters you should preserve.

This solution is great, except it doesn't work:

```py
print(remove_extra_spaces(string))
# 'This is  a   perfectly    normal     sentence.'
```

The problem is that splitting on the space will produce a list with empty strings:

```py
print(string.split(" "))
# ['This', 'is', '', 'a', '', '', 'perfectly', '', '', '', 'normal', '', '', '', '', 'sentence.']
```

These empty strings will be joined back together and you'll end up with the same string you started with.
For this to work, you'll have to filter the empty strings first:

```py
def remove_extra_spaces(string):
    return " ".join(filter(None, string.split(" ")))
```

Using `filter(None, ...)` filters out the [Falsy](/blog/pydonts/truthy-falsy-and-bool) strings, so that the final joining operation only joins the strings that matter.

This solution has a problem, though, in that it will completely remove any leading or trailing whitespace, which may or may not be a problem.

The two solutions presented so far — using regular expressions and string splitting — are pretty reasonable.
But they're also boring.
You'll now learn about two other solutions.

## Replacing spaces until you hit a fixed point

You can think about the task of removing extra spaces as the task of replacing extra spaces by the empty string.
And if you think about doing string replacements, you should think about the string method `replace`.

You can't do something like `string.replace(" ", "")`, otherwise you'd remove _all_ spaces, so you have to be a bit more careful:

```py
def remove_extra_spaces(string):
    while True:
        new_string = string.replace("  ", " ")
        if new_string == string:
            break
        string = new_string
    return string
```

You can replace two consecutive spaces by a single space, and you repeat this operation until nothing changes in your string.

The idea of running a function until its output doesn't change is common enough in maths that they call it “finding a fixed point”.
In Python, given a function `f` and an initial argument `x`, you can find a fixed point of `f` with the function `fixedpoint` shown below:

```py
def fixedpoint(f, x):
    while x != f(x):
        x = f(x)
    return x
```

For the given implementation of the function `fixedpoint`, `f(x)` is being computed twice for each value of `x`.
If `f` is expensive to compute or if the repeated calculations are a problem, you can avoid them by using [an assignment expression](/blog/pydonts/assignment-expressions-and-the-walrus-operator):

```py
def fixedpoint(f, x):
    while x != (x := f(x)):
        pass
    return x
```

Having factored out the fixed point logic, you can create a function that replaces two spaces by a single space.
Since that just means freezing the two arguments in the string method `replace`, it might sound like a job for an anonymous function.
However, in situations like this I prefer to [use `functools.partial` (and `functools.Placeholder`)](/blog/functools-partial):

```py
from functools import partial, Placeholder as _P

space_replacer = partial(str.replace, _P, "  ", " ")
```

! [`functools.Placeholder` is new in Python 3.14](/blog/functools-placeholder). In earlier versions of Python you _have_ to use an anonymous function.

The usage of `partial` highlights the fact that you're freezing two arguments and instead changing the strings to which the method call applies.
Making use of the functions `fixedpoint` and `space_replacer`, you can implement `remove_extra_spaces`:

```py
def remove_extra_spaces(string):
    return fixedpoint(replacer, string)
```

A careful look at this implementation of `remove_extra_spaces` reveals that it is also a suitable use case for `functools.partial`:

```py
from functools import partial

remove_extra_spaces = partial(fixedpoint, space_replacer)
```

## Grouping and flattening runs of spaces

Another interesting solution to this problem involves using the function `groupby` from the [module `itertools`](/blog/module-itertools-overview).

The basic idea revolves around the fact that you can use `groupby` to create groups of consecutive non-space characters and groups of consecutive space characters.
The non-space characters are left unchanged and the space characters are replaced by a _single_ space.

First, you need to use `groupby` to create the sequence of groups of non-space and space characters:

```py
for pair in groupby(string, " ".__eq__):
    print(pair)
# (False, <itertools._grouper object at 0x101473700>)
# (True, <itertools._grouper object at 0x1014732e0>)
# (False, <itertools._grouper object at 0x101473700>)
# (True, <itertools._grouper object at 0x1014732e0>)
# ...
```

The pairs that start with `False` represent a group of non-space characters and the pairs that start with `True` represent a group of space characters.
Since `groupby` is lazy, the second element of each pair is a lazy iterable of _characters_.
For the groups of non-space characters, those groups must be joined back into a string:

```py
words = []
for key, group in groupby(string, " ".__eq__):
    if not key:
        words.append("".join(group))
print(words)
# ['This', 'is', 'a', 'perfectly', 'normal', 'sentence.']
```

At this point, it looks like you don't need to do anything else since you can just use `" ".join(words)` to produce a full sentence:

```py
from itertools import groupby

def remove_extra_spaces(string):
    words = [
        "".join(chars)
        for is_space, chars in groupby(string, " ".__eq__)
        if not is_space
    ]
    return " ".join(words)
```

However, if you did that, you'd have the same problem as the splitting implementation: leading and trailing spaces are completely removed.
Thus, to be able to preserve them, you need to explicitly account for all groups:

```py
from itertools import groupby

def remove_extra_spaces(string):
    segments = [
        " " if is_space else "".join(chars)
        for is_space, chars in groupby(string, " ".__eq__)
    ]
    return "".join(segments)
```

## Summary

You just learned about four different ways in which you can remove extra spaces from a string.
By default, use the first solution with the module `re`, doing `re.sub(" {2,}", " ", string)`.
If removing leading and trailing spaces is fine, the solution using the string method `split` is also appropriate.

If you want to exercise your Python skills, there are also solutions using fixed points and `itertools.groupby` that look cool but that end up being too verbose for this task.

For your reference, the table below summarises the behaviour of each solution you learned about:

| Method | Usable | Preserves leading/trailing whitespace | Recommended | Interesting |
| :- | :-: | :-: | :-: | :-: |
| `re.sub` | ✓ | ✓ | ✓ | X |
| string splitting | ✓ | X | ✓ | X |
| fixed point | ✓ | ✓ | X | ✓ |
| `itertools.groupby` | ✓ | ✓ | X | ✓ |
