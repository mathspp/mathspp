---
metadata:
    description: "This Pydon't will teach you how to use the `set` and `frozenset` Python built-in types."
title: "set and frozenset | Pydon't"
---

This Pydon't will teach you how to use the `set` and `frozenset` Python built-in types.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![An example usage of the `set` built-in.](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

Python contains a handful of built-in types,
among which you can find integers, lists, strings, etc...

Python also provides two built-in types to handle sets,
the `set` and the `frozenset`.

In this Pydon't, you will:

 - understand the relationship between the `set` built-in and the mathematical concept of “set”;
 - learn what the `set` and `frozenset` built-ins are;
 - see what the differences between `set` and `frozenset` are;
 - learn how to create sets and frozen sets;
 - understand how sets fit in with the other built-in types, namely lists;
 - establish a parallel between lists and tuples, and sets and frozen sets;
 - see good example usages of `set` (and `frozenset`) in Python code;

<!--v-->
 > You can now get your copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->

# (Mathematical) sets

A set is simply a collection of unique items where order doesn't matter.
Whenever I have to think of sets, I think of shopping carts.


## No ordering

If you go shopping, and you take a shopping cart with you,
the order in which you put the items in the shopping cart doesn't matter.
The only thing that _actually_ matters is the items that are in the shopping cart.

If you buy milk, chocolate, and cheese,
it doesn't matter the order in which those items are registered.
What matters is that you bought milk, chocolate, and cheese.

In that sense, you could say that the groceries you bought form a set:
the set containing milk, chocolate, and cheese.
Both in maths and in Python, we use `{}` to denote a set,
so here's how you would define the groceries set in Python:

```py
>>> groceries = {"milk", "cheese", "chocolate"}
>>> groceries
{'cheese', 'milk', 'chocolate'}
>>> type(groceries).__name__
'set'
```

We can check that we created a `set` indeed by checking the `__name__` of
the `type` of `groceries`.

! If you don't understand why we typed `type(groceries).__name__` instead
! of just doing `type(groceries)`, then I advise you to skim through
! [the Pydon't about the dunder attribute `__name__`][pydont-dunder-name].
! (P.S. doing `isinstance(groceries, set))` would also work here!)

To make sure that order really doesn't matter in sets,
we can try comparing this set with other sets containing the same elements,
but written in a different order:

```py
>>> groceries = {"milk", "cheese", "chocolate"}
>>> groceries == {"cheese", "milk", "chocolate"}
True
>>> groceries == {"chocolate", "milk", "cheese"}
True
```


## Uniqueness

Another key property of (mathematical) sets is that there are no duplicate elements.
It's more or less as if someone told you to go buy cheese,
and when you get back home, that person screams from another room:

“Did you buy cheese?”

This is a yes/no question: you either bought cheese or you didn't.

For sets, the same thing happens: the element is either in the set or it isn't.
We don't care about element count.
We don't even consider it.

Here's proof that Python does the same:

```py
>>> {"milk", "cheese", "milk", "chocolate", "milk"}
{'cheese', 'milk', 'chocolate'}
```


# (Common) Operations on sets

Sets define many methods, like [the docs][docs-sets] will tell you.


## Creation

There are three main ways to create a set.

### Explicit `{}` notation

Using the `{}` notation, you write out the elements of the set inside braces
in a comma-separated list:

```py
>>> {1, 2, 3}
{1, 2, 3}
>>> {"cheese", "ham"}
{'cheese', 'ham'}
>>> {"a", "b", "c"}
{'c', 'a', 'b'}
```

By the way, you *cannot* use `{}` to create an empty set!
`{}` by itself will create an empty dictionary.
To create empty sets, you need the next method.


### Calling `set` on an iterable

You can call the built-in function `set` on any iterable to create a set
out of the elements of that iterable.
Notable examples include ranges, strings, and lists.

```py
>>> set(range(3))
{0, 1, 2}
>>> set([73, "water", 42])
{73, 'water', 42}
```

Notice that calling `set` on a string produces a set with the characters
of the string, not a set containing the whole string:

```py
>>> {"mississippi"}
{'mississippi'}

# ↑ different ↓
>>> set("mississippi")
{'s', 'i', 'p', 'm'}
```

Calling `set()` by itself will produce an empty set.


### Set comprehensions

Using `{}`, one can also write what's called a set comprehension.
Set comprehensions are _very_ similar to list comprehensions,
so learning about list comprehensions will be helpful here.

I'll just show a couple of brief examples.

First, one using filtering some of the elements we want to include:

```py
>>> veggies = ["broccoli", "carrot", "tomato", "pepper", "lettuce"]
>>> {veggie for veggie in veggies if "c" in veggie}
{'lettuce', 'carrot', 'broccoli'}
```

And secondly, a set comprehension with two nested `for` loops:

```py
>>> veggies = ["broccoli", "carrot", "tomato", "pepper", "lettuce"]
>>> {char for veggie in veggies for char in veggie}
{'c', 'u', 't', 'o', 'p', 'b', 'l', 'i', 'a', 'e', 'm', 'r'}
```

! I'll be writing a thorough Pydon't about all types
! of comprehensions that Python supports,
! so be sure to [subscribe to the newsletter][subscribe]
! in order to not miss that upcoming Pydon't!


## Operations on a single set

Many common operations are done on/with a single set, namely:

 - membership testing:

```py
>>> "milk" in groceries
True
>>> "broccoli" in groceries
False
```

 - computing the size of the set:

```py
>>> len(groceries)
3
```

 - popping a random element from the set:

```py
>>> groceries.pop()
'cheese'
>>> groceries
{'milk', 'chocolate'}
```

 - adding an element to the set:

```py
>>> groceries.add("cheese")
>>> groceries
{'milk', 'cheese', 'chocolate'}
```


## Iteration

I often relate sets with lists (and tuples).
Sets are similar to lists with unique elements,
but lists are ordered: a list can be traversed from the beginning to
the end, and a list can be indexed.

While sets can also be iterated over (in an order you can't rely on),

```py
>>> for item in groceries:
...     print(item)
...
cheese
milk
chocolate
```

sets cannot be indexed directly:

```py
>>> groceries[0]
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: 'set' object is not subscriptable
```


## Computations with multiple sets

When you have multiple sets (two, or more) you may need
to do other sorts of operations.

Let's define just a couple of sets to use here:

```py
>>> groceries = {"milk", "cheese", "chocolate"}
>>> treats = {"chocolate", "popcorn", "cookie"}
```

Here are some of the more common operations 

 - check for overlap between two sets:

```py
>>> groceries & treats
{'chocolate'}
```

 - join the two sets:

```py
>>> groceries | treats
{'cheese', 'milk', 'popcorn', 'chocolate', 'cookie'}
```

! Notice that the usage of the pipe `|` here is akin
! to the usage of `|` to merge dictionaries in Python 3.9+.

 - find the difference between the two sets
 (what's on the left set but not on the right set):

```py
>>> groceries - treats
{'cheese', 'milk'}
```

 - check for containment using `<`, `<=`, `>=`, and `>`:

```py
>>> {"cheese", "milk"} < groceries
True
>>> groceries < groceries
False
>>> {"cheese", "milk"} <= groceries
True
>>> groceries <= groceries
True
>>> treats > {"chocolate"}
True
>>> treats >= {"chocolate", "cheese"}
False
```

Notice that most of the operator-based operations have corresponding method calls.
The corresponding method calls can accept an arbitrary iterator,
whereas the operator-based versions expect sets.


# Differences between `set` and `frozenset`

## Creation

While you can create a set with the built-in `set`
or through the `{}` notation,
`frozenset`s can only be created through their respective built-in.

`frozenset`s can be created out of other sets or out of any iterable,
much like `set`s.

When printed, `frozenset`s display the indication that they are frozen:

```py
>>> groceries = {'cheese', 'milk', 'chocolate'}
>>> frozenset(groceries)
frozenset({'cheese', 'milk', 'chocolate'})
>>> frozenset(['cheese', 'milk', 'chocolate'])
frozenset({'cheese', 'milk', 'chocolate'})
```

## Mutability

Sets are mutable.
Sets are said to be mutable because they can change, that's what “mutable” means in English.

As I showed you above, the contents of sets can change,
for example through calls to the methods `.add` and `.pop`.

However, if you need to create an object that behaves like a set,
(i.e. where order doesn't matter and where uniqueness is guaranteed)
but that you don't want to be changed, then you want to create a `frozenset`.

An instance of a `frozenset` is pretty much like a `set`,
except that `frozenset` isn't mutable.
In other words, a `frozenset` is immutable, it can't be mutated, it was frozen.

To create a `frozenset`, you just call the appropriate class:

```py
>>> groceries_ = frozenset(groceries)
>>> # Can't add items:
>>> groceries_.add("beans")
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
AttributeError: 'frozenset' object has no attribute 'add'
>>> # Can't pop items:
>>> groceries_.pop()
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
AttributeError: 'frozenset' object has no attribute 'pop'
```

There's a very similar pair of built-in types that have this same dichotomy:
lists and tuples.
Lists are mutable (they have the methods `.append` and `.pop`, for example)
whereas tuples are immutable
(the don't have the methods `.append` or `.pop`,
nor can you assign directly to indices):

```py
# Lists are mutable:
>>> l = [0, 1, 2]
>>> l[0] = 73
>>> l.pop()
2
>>> l.append(42)
>>> l
[73, 1, 42]

# Tuples are immutable:
>>> t = (0, 1, 2)
>>> t[0] = 73
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
>>> t.pop()
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
AttributeError: 'tuple' object has no attribute 'pop'
>>> t.append(42)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
AttributeError: 'tuple' object has no attribute 'append'
```


## To be (hashable) or not to be

An object that is hashable is an object for which a hash can be computed,
hence, hash-_able_.

A hash is an integer that the built-in function `hash` computes
to help with fast operations with dictionaries, e.g. key lookups.

The built-in function knows how to work with some types of objects,
and not with others.
The built-in function `hash` dictates what can and cannot be a dictionary key:
if it is hashable, it can be a dictionary key;
if it isn't hashable, it cannot be a dictionary key.

For example, lists are mutable and unhashable,
and hence they cannot be dictionary keys.
Attempting to use a list as a dictionary key raises an error:

```py
>>> d = {}
>>> d[[1, 2, 3]] = 73
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: unhashable type: 'list'
```

However, the `tuple` – `list`'s sibling – is immutable,
and immutable objects can generally be made hashable.
A tuple can be used as a dictionary key:

```py
>>> d = {}
>>> d[(1, 2, 3)] = 73
>>> d
{(1, 2, 3): 73}
```

Similarly, because `set`s are mutable, they cannot be hashable.
However, `frozenset`s are not mutable, and they are also hashable!
A `set` cannot be a dictionary key, but a `frozenset` can:

```py
>>> d = {}
>>> d[groceries] = 73
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: unhashable type: 'set'
>>> d[frozenset(groceries)] = 73
>>> d
{frozenset({'cheese', 'milk', 'chocolate'}): 73}
```


# What are sets used for?

Quoting [directly from the docs](https://docs.python.org/3/reference/datamodel.html#the-standard-type-hierarchy),

 > “Common uses for sets are fast membership testing, removing duplicates from a sequence, and computing mathematical operations such as intersection, union, difference, and symmetric difference.”

In short, sets are useful when the problems at hand would benefit
from the properties that are inherent to mathematical sets
(element uniqueness and lack of order) and other benefits we inherit
from those properties.

The example of fast membership checking is a good one.

In a recent tweet, I showed how 10-element sets already outperform
10-element lists when doing membership checking:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Did you know that Python 🐍&#39;s sets are very appropriate for fast 🔥 membership checking?<br><br>If you look 👇, you can see that checking if something is in a set is MUCH faster than in the list as the element goes further down the list...<br><br>And the example below only has 10 elements! <a href="https://t.co/iJWwqeNIt0">pic.twitter.com/iJWwqeNIt0</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1433122219207581705?ref_src=twsrc%5Etfw">September 1, 2021</a></blockquote>

These properties will be the main rationale followed by the programmers
that wrote the pieces of code I will be showing you,
showcasing good usages of `set`.


# Examples in code

The examples that follow are my attempts at showing you good usages of
the built-in types `set` and `frozenset`.


## Fast membership checking with `set`

The module [`argparse`][argparse] is a built-in module that allows you
to create command line interfaces.

The main class, the argument parser `ArgumentParser`,
contains the following snippet of code:

```py
# In Lib/argparse.py from Python 3.9.2
class ArgumentParser(_AttributeHolder, _ActionsContainer):
    # ...
    def _parse_known_args(self, arg_strings, namespace):
        # ...
        # map all mutually exclusive arguments to the other arguments
        # they can't occur with
        action_conflicts = {}
        # ...

        seen_actions = set()
        seen_non_default_actions = set()

        def take_action(action, argument_strings, option_string=None):
            seen_actions.add(action)
            argument_values = self._get_values(action, argument_strings)

            # error if this argument is not allowed with other previously
            # seen arguments, assuming that actions that use the default
            # value don't really count as "present"
            if argument_values is not action.default:
                seen_non_default_actions.add(action)
                for conflict_action in action_conflicts.get(action, []):
                    if conflict_action in seen_non_default_actions:
                        msg = _('not allowed with argument %s')
                        action_name = _get_action_name(conflict_action)
                        raise ArgumentError(action, msg % action_name)
```

TL;DR: the sets `seen_actions` and `seen_non_default_actions`
are being used precisely for fast membership checking.

Now follows a lengthier explanation.

When you create a command line application with `argparse`,
you have to specify the options that your command takes.
For example, `-v` for verbose output or `-h` to display the help message.

Sometimes, there may be conflicting options.
For example, if you provide `-v` for verbose output, and also `-q` for quiet output,
then it won't make sense to specify both at the same time.

The `action_conflicts` dictionary will keep track of what things conflict with what.

Later, we initialise two empty sets, `seen_actions` and `seen_non_default_actions`.
Now, every time we see an action,
we add it to the set that contains all actions that have been seen.

Then, if that action was really specified by the user,
we add it to the set of actions that didn't have the default value.

Finally, we access the `action_conflicts` to get a list of all the actions
that are incompatible with the action we are parsing now.
If any conflicting action shows up in the set of actions we already saw previously,
then we throw an error!

Later down the road, we can also find the following:

```py
# In Lib/argparse.py from Python 3.9.2
class ArgumentParser(_AttributeHolder, _ActionsContainer):
    # ...
    def _parse_known_args(self, arg_strings, namespace):
        # ...

        seen_actions = set()
        seen_non_default_actions = set()

        def take_action(action, argument_strings, option_string=None):
            # ...
        
        # ...
        # make sure all required actions were present and also convert
        # action defaults which were not given as arguments
        required_actions = []
        for action in self._actions:
            if action not in seen_actions:
                if action.required:
                    required_actions.append(_get_action_name(action))
            # ...

        if required_actions:
            self.error(_('the following arguments are required: %s') %
                       ', '.join(required_actions))
```

Once more, we are using the set `seen_actions` for fast membership checking:
we traverse all the actions that the command line interface knows about,
and we keep track of all the required actions that the user didn't specify/mention.

After that, if there are any actions in the list `required_actions`,
then we let the user know that they forgot some things.


## Unconditional set addition

There is one other neat detail about the previous example, that I'd like to highlight.

Let me show you the snippet that matters:

```py
# In Lib/argparse.py from Python 3.9.2
class ArgumentParser(_AttributeHolder, _ActionsContainer):
    # ...
    def _parse_known_args(self, arg_strings, namespace):
        # ...
        def take_action(action, argument_strings, option_string=None):
            seen_actions.add(action)
            # ...
```

Focus on the very last line of code: `seen_actions.add(action)`.

This might not seem obvious at first,
but `action` might already be in the set `seen_actions`.

To make this clear, modify `take_action` to include a `print`:

```py
# In Lib/argparse.py from Python 3.9.2
class ArgumentParser(_AttributeHolder, _ActionsContainer):
    # ...
    def _parse_known_args(self, arg_strings, namespace):
        # ...
        def take_action(action, argument_strings, option_string=None):
            print(action)
            seen_actions.add(action)
            # ...
```

Now, go ahead and paste the following code into a file `foo.py`:

```py
import argparse

parser = argparse.ArgumentParser()
parser.add_argument("-t", action="store_true")
args = parser.parse_args()
```

Now open your terminal in the directory where `foo.py` lives:

```
> python foo.py -ttt
_StoreTrueAction(option_strings=['-t'], dest='t', nargs=0, const=True, default=False, type=None, choices=None, help=None, metavar=None)
_StoreTrueAction(option_strings=['-t'], dest='t', nargs=0, const=True, default=False, type=None, choices=None, help=None, metavar=None)
_StoreTrueAction(option_strings=['-t'], dest='t', nargs=0, const=True, default=False, type=None, choices=None, help=None, metavar=None)
```

You get three lines of identical output, one per each time you typed a `t` in the command.

So, we see that we have duplicate actions showing up...
Shouldn't we check if an action has been added before adding it?
Something like

```py
# In Lib/argparse.py from Python 3.9.2
class ArgumentParser(_AttributeHolder, _ActionsContainer):
    # ...
    def _parse_known_args(self, arg_strings, namespace):
        # ...
        def take_action(action, argument_strings, option_string=None):
            if action not in seen_actions:
                seen_actions.add(action)
            # ...
```

**No**!
Don't do that!
This is an anti-pattern and is repeating unnecessary work!
Checking if an element is inside a set or adding it unconditionally
is almost the same work, so checking if it is there and _then_ adding it
is going to double the work you do for all new actions!

The set already handles uniqueness for you,
so you don't have to be worried enforcing it.
In that sense, this is a great example usage of sets.


## Fast membership checking with `frozenset`

In the example above, we saw that the sets we were working with
would grow as the program progressed.
Therefore, we needed mutability and used `set`.

The example I'm about to show is such that the set we care
about is fixed, it always has the same elements.
Henceforth, we will use a `frozenset` instead of a plain `set`.
Why?
Because using `frozenset` makes it crystal clear that the set is fixed.

```py
# In Lib/idlelib/hyperparser.py from Python 3.9.2

import string

# ...

# all ASCII chars that may be in an identifier
_ASCII_ID_CHARS = frozenset(string.ascii_letters + string.digits + "_")
# all ASCII chars that may be the first char of an identifier
_ASCII_ID_FIRST_CHARS = frozenset(string.ascii_letters + "_")

# lookup table for whether 7-bit ASCII chars are valid in a Python identifier
_IS_ASCII_ID_CHAR = [(chr(x) in _ASCII_ID_CHARS) for x in range(128)]
# lookup table for whether 7-bit ASCII chars are valid as the first
# char in a Python identifier
_IS_ASCII_ID_FIRST_CHAR = \
    [(chr(x) in _ASCII_ID_FIRST_CHARS) for x in range(128)]
```

Granted, the snippet above does not tell you what
the variables `_IS_ASCII_ID_CHAR` and `_IS_ASCII_ID_FIRST_CHAR` are for,
but it is quite clear that those two are being built through a list
comprehension that does membership checking on
`_ASCII_ID_CHARS` and `_ASCII_ID_FIRST_CHARS`.
In turn, these two variables are `frozenset`s of characters!

So there you have it!
One more usage of sets for fast membership checking.


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Use (frozen) sets when you are dealing with collections and where what matters is (fast) membership checking.*”

This Pydon't showed you that:

 - sets are (mathematical) objects that contain elements;
   - the elements are unique; and
   - their ordering doesn't matter.
 - the built-in type `set` provides an implementation for the mathematical concept of set;
 - the `frozenset` is an immutable and hashable version of `set`;
 - tuples are to lists like frozen sets are to sets;
 - you can create sets with
   - `{}` enclosing a comma-separated list of items;
   - `set()` and an iterable; and
   - set comprehensions.
 - sets have operations that allow to mutate them (like `.add` and `.append`), among many others;
 - you can combine sets in many different ways, with operators like `&` and `|`;
 - you can check for set containment with `<`, `<=`, `>=`, `>`;
 - you should use `frozenset` if you know the collection of objects won't change;
 - (frozen) sets are often used for fast membership checking; and
 - unconditionally adding to a set is faster than checking for membership first and adding latter.

<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!
<!-- ^ -->

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-dunder-name]: /blog/pydonts/name-dunder-attribute
[docs-sets]: https://docs.python.org/3/library/stdtypes.html#set-types-set-frozenset
[argparse]: https://docs.python.org/3/library/argparse.html
