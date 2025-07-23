Today I learned how to disassemble Python code with the module `dis`.

===

![A photo of some gears, a metaphor to how the Python standard module `dis` works and the fact that it allows us to disassemble Python code, letting us understand how Python runs our code under the hood.](thumbnail.png "Photo by Laura Ockel on Unsplash.")


## What is the module `dis` in Python?

According to [the documentation][dis-docs], the module `dis`

 > “supports the analysis of CPython bytecode by disassembling it.
 > The CPython bytecode which this module takes as an input is defined in the file Include/opcode.h and used by the compiler and the interpreter.”

What does this mean?
Wasn't Python an interpreted language?

I can't speak for all other implementations, but in CPython
(the dominant Python implementation, which is written in C),
Python code is _compiled_ into an intermediate representation, the bytecode,
and that bytecode is what is interpreted.

I won't go into the rabbit hole of really explaining what that means,
I'll just assume you are familiar enough with these ideas for me to explain what `dis` does.
In particular, I'll focus on the function `dis.dis`.


## The function `dis.dis`

The function `dis.dis` accepts a variety of different objects, and then disassembles them for us,
showing us what their bytecode looks like.
For example, you can disassemble a function to see what its bytecode looks like:

```py
>>> def foo():
...     return 42
... 
>>> import dis
>>> dis.dis(foo)
  2           0 LOAD_CONST               1 (42)
              2 RETURN_VALUE
```

This lifts the curtain a little bit, and lets us see a bit of how Python operates under the hood.

By using the function `dis.dis`, we can, for example, realise that these three functions are essentially the same in the eyes of Python:

```py
>>> def foo():
...     1
...     2
...     return 3
... 
>>> dis.dis(foo)
  4           0 LOAD_CONST               1 (3)
              2 RETURN_VALUE

## ---

>>> def foo():
...     return 3
... 
>>> dis.dis(foo)
  2           0 LOAD_CONST               1 (3)
              2 RETURN_VALUE

## ---

>>> def foo(): return 3
... 
>>> dis.dis(foo)
  1           0 LOAD_CONST               1 (3)
              2 RETURN_VALUE
```

The only noticeable difference in the three outputs is in the top-left number:
it started out as `4`, then went down to `2`, and finally down to `1`...
Well, that's just the line number of the operation!

Line 1 is the line with the definition of the function header (`def foo():`) and we just count from there!

So, it seems like the very first `foo`, the one with two extra numbers,

```py
def foo():
    1
    2
    return 3
```

actually ignores the `1` and the `2` because it realises that they do nothing.
However, it keeps the line numbering so that errors get reported properly.

In fact, we can compare the output from `dis.dis` with what we get if we disassemble a function that contains comments, for example, instead of the two integers:

```py
>>> def foo():
...     # Comment 1.
...     # Comment 2.
...     return 3
...
>>> dis.dis(foo)
  4           0 LOAD_CONST               1 (3)
              2 RETURN_VALUE

## ---

>>> def goo():
...     1
...     2
...     return 3
...
>>> dis.dis(goo)
  4           0 LOAD_CONST               1 (3)
              2 RETURN_VALUE
```

Notice how the printed bytecode looks exactly the same!
So yeah, it really looks like Python is completely ignoring the `1` and the `2` in there.

## Constant folding

With the function `dis.dis` we can also realise that CPython does some constant folding when compiling the Python code into bytecode!
What does this mean?
It means that some expressions that have a constant value are simplified _when_ the code is compiled,
in order to save time.

For example, take a look at this simple function:

```py
def foo():
    return 2 + 2
```

We know that the addition `2 + 2` will _always_ return `4`, so CPython does us a favour and computes that _once_,
so that calling `foo` doesn't actually require you to compute that trivial addition.
By checking the bytecode of `foo`, we can see I'm not lying:

```py
>>> dis.dis(foo)
  2           0 LOAD_CONST               1 (4)
              2 RETURN_VALUE
```

Notice how the first line of the output has `LOAD_CONST` and, at the end of that line, it has a `(4)`.
That means Python is loading the constant value `4`, to then return it with `RETURN_VALUE`.

Quite interesting, right?


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe

[dis-docs]: https://docs.python.org/3/library/dis
[constant-folding]: https://en.wikipedia.org/wiki/Constant_folding
