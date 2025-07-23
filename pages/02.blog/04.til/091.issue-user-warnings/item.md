Today I learned how to issue user warnings like DeprecationWarnings or SyntaxWarnings.

===


## Issue user warnings

Every once in a while I see a `DeprecationWarning` when I'm doing some coding.
Last time I saw it, I think it was in some `pandas` code in my [pandas and matplotlib tutorial with Pokémon](/blog/learn-pandas-and-matplotlib-with-pokemon).
Today I needed to issue a similar warning and I set out to find out how that works.

Turns out there is a module in the standard library (obviously) that does this: `warnings`.
Issuing a warning with the module `warnings` can be as simple as passing a string to the function `warnings.warn`:

```pycon
>>> import warnings
>>> warnings.warn("Something wrong is not right!")
<stdin>:1: UserWarning: Something wrong is not right!
```

The module gives you a _lot_ of flexibility, though, in terms of:

 - the types of warnings you issue;
 - the way the warnings are formatted;
 - how you can filter the warnings to make sure they only appear when you want them to;
 - where to write the warnings to;
 - and more!

The module `warnings` refers to the “types of warnings” as the categories of warnings.
There are plenty of built-in warning categories, such as `UserWarning` (the default, as the example above shows), `DeprecationWarning`, `SyntaxWarning`, and others.

To change the category of warning you use, you can specify the category as the second parameter `category`:

```pycon
>>> warnings.warn("Dang it!", category=RuntimeWarning)
<stdin>:1: RuntimeWarning: Dang it!
```

Now, a very interesting thing will happen if you run that exact same code again:

```pycon
>>> warnings.warn("Dang it!", category=RuntimeWarning)
>>> # No output?!
```

That's because, by default, equal warnings are suppressed.
(This is also customisable!)
What you can do is reset the warnings:

```pycon
## Only 1:
>>> for _ in range(5):
...     warnings.warn("Warning in a loop")
...
<stdin>:2: UserWarning: Warning in a loop

## All 5 warnings:
>>> for _ in range(5):
...     warnings.resetwarnings()
...     warnings.warn("Warning in a loop")
...
<stdin>:3: UserWarning: Warning in a loop
<stdin>:3: UserWarning: Warning in a loop
<stdin>:3: UserWarning: Warning in a loop
<stdin>:3: UserWarning: Warning in a loop
<stdin>:3: UserWarning: Warning in a loop
```

You can also specify the category by instantiating the appropriate class:

```pycon
>>> warnings.warn(UserWarning("user warning test"))
<stdin>:1: UserWarning: user warning test
>>> warnings.warn(SyntaxWarning("syntax warning test"))
<stdin>:1: SyntaxWarning: syntax warning test
>>> warnings.warn(RuntimeWarning("runtime warning test"))
<stdin>:1: RuntimeWarning: runtime warning test
```

This is what I ended up using, together with the parameter `stacklevel`.

When a warning is posted, it shows the file and line number it came from.
When you have wrapper functions around your warnings, you'll want to use the parameter `stacklevel` to make it so that the warnings refer to the place where you called your wrapper functions instead of having the warning refer to the wrapper function itself.
