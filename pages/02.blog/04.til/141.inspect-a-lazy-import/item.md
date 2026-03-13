Today I learned how to inspect a lazy import object in Python 3.15.

===

Python 3.15 comes with lazy imports and today I played with them for a minute.
I defined the following module `mod.py`:

```py
print("Hey!")

def f():
    return "Bye!"
```

Then, in the REPL, I could check that lazy imports indeed work:

```pycon
>>> # Python 3.15
>>> lazy import mod
>>>
```

The fact that I didn't see a "Hey!" means that the import is, indeed, lazy.
Then, I wanted to take a look at the module so I printed it, but that triggered reification (going from a lazy import to a regular module):

```pycon
>>> print(mod)
Hey!
<module 'mod' from '/Users/rodrigogs/Documents/tmp/mod.py'>
```

So, I checked [the PEP that introduced explicit lazy modules](https://peps.python.org/pep-0810/#reification) and turns out as soon as you _reference_ the lazy object directly, it gets reified.
But you can work around it by using `globals`:

```pycon
>>> # Fresh 3.15 REPL
>>> lazy import mod
>>> globals()["mod"]
<lazy_import 'mod'>
```

This shows the new class `lazy_import` that was added to support lazy imports!

Pretty cool, right?
