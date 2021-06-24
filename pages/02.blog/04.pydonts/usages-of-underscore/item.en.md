---
metadata:
    description: "In this Pydon't I'll show you how to use the underscore _ to write more Pythonic code."
title: "Usages of underscore | Pydon't"
---

The purpose of this Pydon't is to show you what underscores
are used for in Python, and to show you how to write
more idiomatic code with them.

===

![A Python code snippet that shows the use of the underscore in the session.](thumbnail.svg)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

In this Pydon't we will take a look at all the use cases there are
for `_` in Python.
There are a couple of places where `_` has a very special role
syntactically, and we will talk about those places.
We will also talk about the uses of `_` that are just conventions
people follow, and that allow one to write more idiomatic code.

In this Pydon't, you will:

 - learn about the utility of `_` in the Python REPL;
 - learn what `_` does when used as a prefix and/or suffix of a variable name:
   - a single underscore used as a suffix;
   - a single underscore used as a prefix;
   - double underscore used as a prefix;
   - double underscore used as a prefix and suffix;
 - see the idiomatic usage of `_` as a “sink” in assignments;
 - and understand how that was extended to `_`'s role in the new `match` statement;
 - see the idiomatic usage of `_` in localising strings; and
 - learn how to use `_` to make your numbers more readable.

<!--v-->
 > You can now get your copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->


# Recovering last result in the session

Have you ever called a slow function in the Python session and then lost the return
value because you forgot to assign it to a variable?
I know I have done that countless times!
Because of people like (you and) me, someone made the _best_ decision ever,
and decided that `_` can be used in the Python session to refer to the
last return result:

```py
>>> 1 + 1
2
>>> _
2
>>> sum(range(100_000_000))     # Takes a couple of seconds to finish.
4999999950000000
>>> _
4999999950000000
>>> save_for_later = _
>>> save_for_later
4999999950000000
```

This prevents you from having to re-run the previous line of code,
which is especially helpful if the previous line of code takes some
time to finish, if it had side-effects that you don't want to trigger
again, or even if it can't be re-run (e.g. because you deleted a file
or because you exhausted an iterable).

So, next time you are playing around in the interpreter session and forget
to assign the result of a function call, or some other piece of code,
remember to use `_` to refer back to it.

Notice that if you explicitly assign to `_`,
then the value you assign will stay there until you explicitly delete it.
When you delete it, then `_` will go back to referring to the last
returned result:

```py
>>> _ = "hey"
>>> "_ was explicitly assigned."
'_ was explicitly assigned.'
>>> _
'hey'
>>> del _
>>> "_ is no longer explicitly assigned."
'_ is no longer explicitly assigned.'
>>> _
'_ is no longer explicitly assigned.'
```


# Prefixes and suffixes for variable names

## Single underscore as a suffix

As you know, some words have a special meaning in Python,
and are therefore dubbed as keywords.
This means we cannot use those names for our variables.
Similarly, Python defines a series of built-in functions
that are generally very useful and ideally we would like
to avoid using variable names that match those built-in names.

However, there are occasions in which the perfect variable
name is either one of those keywords or one of those built-in
functions.
In those cases, it is common to use a single `_` as a suffix
to prevent clashes.

For example, in statistics, there is a random distribution
called the “exponential distribution” that depends on a numeric
parameter, and that parameter is typically called “lambda”
in the mathematical literature.
So, when `random` decided to implement that distribution
in `random.expovariate`, they would ideally like to use the word
`lambda` as the parameter to `random.expovariate`,
but `lambda` is a reserved keyword and that would throw an error:

```py
>>> def expovariate(lambda):
  File "<stdin>", line 1
    def expovariate(lambda):
                    ^
SyntaxError: invalid syntax
```

Instead, they could have named the parameter `lambda_`.
(The implementers ended up going with `lambd`, however.)

There are many examples in the Python Standard Library where
the implementers opted for the trailing underscore.
For example, in the code for IDLE (the IDE that comes by default
with Python and that is implemented fully in Python) you can find
this function:

```py
# From Lib/idlelib/help.py in Python 3.9.2
def handle_starttag(self, tag, attrs):
    "Handle starttags in help.html."
    class_ = ''
    for a, v in attrs:
        if a == 'class':
            class_ = v
    # Truncated for brevity...
```

Notice the `class_` variable that is defined and updated inside
the loop.
“class” would be the obvious variable name here because we are
dealing with HTML classes, but `class` is a reserved keyword
that we use to define, well, classes...
And that's why we use `class_` here!


## Single underscore as prefix

While the usage of a single underscore as a suffix was more or less
a convention, the usage of a single underscore as a prefix is both
a convention and something that affects some Python programs.

Let me start by explaining the convention:
when you define a name that starts with a single underscore,
you are letting other programmers know that such a name refers
to something that is for internal use only, and that outside
users shouldn't mess around with.

For example, suppose that you are implementing a framework for
online shops, and you are now writing the part of the
code that will fetch the price of an item.
You could write a little function like so:

```py
prices = {
    "jeans": 20,
    "tshirt": 10,
    "dress": 30,
}

def get_price(item):
    return prices.get(item, None)
```

Now, shops nowadays can't do business without having sales from time
to time, so you add a parameter to your function os that you can
apply discounts:

```py
def get_price(item, discount=0):
    p = prices.get(item, None)
    if p is not None:
        return (1 - discount)*p
    else:
        return p
```

Now all is good, except you think it might be a good idea to validate
the discount that the function is trying to apply,
so that discounts are never negative or greater than $100\%$.
You could do that in the main function, or you can devise a helper
function to do that for you, probably because you will need to verify
that discount amounts are correct in a variety of places.

So, you write your helper function:

```py
def valid_discount(discount):
    return 0 <= discount <= 1
```

!!! By the way, if you want to learn more about the fact that
!!! Python allows the chaining of comparisons,
!!! like what you see above, you can read [this Pydon't][pydont-chaining]
!!! on the subject.

Now you have a way to validate discounts and you can use that:

```py
def get_price(item, discount=0):
    if not valid_discount(discount):
        raise ValueError(f"Trying to apply an illegal discount on {item}.")
    p = prices.get(item, None)
    if p is not None:
        return (1 - discount)*p
    else:
        return p
```

Perfect!
The codebase for your online shop management framework is well
on its way.

Now imagine, for a second, that you are a user of your framework,
and not an implementer.
You will probably install the framework from PyPI, with `pip`,
or maybe directly from GitHub.
But when you do, and when you import the code to start using it,
you will import the `get_price` and the `valid_discount` functions.
Now, you need the `get_price` function but you don't need the
`valid_discount` because the whole framework already protects the
user from illegal discounts and negative prices and whatnot!
In other words, the `valid_discount` function is more relevant to the internals
of the framework than to users of the framework.
Except the user probably doesn't know that, because the user sees the
`valid_discount` function and it is fair to assume that the user
will think they have to use that function to validate discounts
for themselves...
How could they know they don't need to?

One solution would be for you to follow the convention we just
started discussing!
If you name your function just a tad differently:

```py
def _valid_discount(discount):
    return 0 <= discount <= 1
```

The user of the framework immediately understands
“oh, I don't have to worry about this function because its name
starts with a single underscore”.
Not only that, but Python even helps users not worry about
those functions with leading underscores.

Go ahead and write the following in your `onlineshop.py` file:

```py
# onlineshop.py
def _valid_discount(discount):
    return 0 <= discount <= 1

prices = {
    "jeans": 20,
    "tshirt": 10,
    "dress": 30,
}

def get_price(item, discount=0):
    if not _valid_discount(discount):
        raise ValueError(f"Trying to apply an illegal discount on {item}.")
    p = prices.get(item, None)
    if p is not None:
        return (1 - discount)*p
    else:
        return p
```

After you do that, open your Python REPL,
import everything from `onlineshop` and try getting some
prices and discounts:

```py
>>> from onlineshop import *
>>> get_price("jeans")
20
>>> get_price("jeans", discount=0.5)
10.0
>>> get_price("jeans", discount=1.3)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "C:\Users\rodri\Documents\mathspp\onlineshop.py", line 13, in get_price
    raise ValueError(f"Trying to apply an illegal discount on {item}.")
ValueError: Trying to apply an illegal discount on jeans.
```

Notice how both functions appear to be working just fine,
and notice that we got an error on the last call because
1.3 is too big of a discount, so the `_valid_discount` function
said it wasn't valid.

Let us check it for ourselves:

```py
>>> _valid_discount(1.3)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
NameError: name '_valid_discount' is not defined
```

We get a `NameError` because the `_valid_discount` function isn't defined...
Because it was never imported!
The function was not imported into your code, even though the original
code can still use it internally.
If you really need to access `_valid_discount`, then you either
import it explicitly, or you just import the module name and then
access it with its dotted name:

```py
>>> from onlineshop import _valid_discount
>>> _valid_discount(0.5)
True
>>> import onlineshop
>>> onlineshop._valid_discount(1.3)
False
```

This mechanism also works with the variables, as long as their name
starts with a leading underscore.
Go ahead and rename the `prices` variable to `_prices`,
close the REPL, open it again, and run `from onlineshop import *`.
`_prices` will not be defined!

So, on the one hand, notice that a leading underscore really is
an _indication_ of what things you should and shouldn't be concerned
with when using code written by others.
On the other hand, the leading underscore is _just_ an indication,
and it won't prevent others from accessing the names that you
write with a leading underscore.

Finally, there is one other way of controlling what gets imported
when someone uses the `*` to import everything from your module:
you can use the `__all__` variable to specify the names
that should be imported on that occasion.

Go ahead and add the following line to the top of your `onlineshop.py` file:

```py
__all__ = ("get_price", "_valid_discount")
```

After you do that, close your REPL and reopen it:

```py
>>> from onlineshop import *
>>> get_price
<function get_price at 0x0000029410907430>
>>> _valid_discount
<function _valid_discount at 0x0000029410907280>
>>> prices
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
NameError: name 'prices' is not defined
```

Notice that all the names inside `__all__` were imported,
regardless of them starting with a single underscore or not,
and the names that were _not_ listed did not get included.
In my example, my variable was named `prices`
(so it didn't even have a leading underscore!)
and it was not imported.

This `__all__` variable is the perfect segue into the next
subsection:


## Leading and trailing double underscores

In Python, a name that starts and ends with double underscores
is a name that has internal relevance to Python.
For example, many functions like [`__str__`, `__repr__`][pydont-str-repr],
[`__bool__`][pydont-bool], and `__init__`, are sometimes referred to as “magic”
functions because they interact, in some way, with Python's
“internal” functioning.

A better name for these magic functions and variables is “dunder function”,
or “dunder variable”, or “dunder method”, depending on the context.
(The word “dunder” – a common word in the Python world –
is short for "double underscore"!)

However, these dunder names are not really magical: they are _just_ functions.
(Or variables, just like `__all__`.)
What you can know is that when you find a name that starts and ends
with a double underscore, chances are, it is a name that interacts
with Python's syntax in some way.

For example, what calling the `str` built-in function with some argument do
is exactly the same as calling the `__str__` function of that same argument:

```py
>>> n = 3
>>> str(n)
'3'
>>> n.__str__()
'3'
```

Of course writing `str(n)` looks much nicer than `n.__str__()`,
but this just tells you that if you define your own objects,
you need to implement the `__str__` method so that your objects
can be given as arguments to the `str` built-in.
(I wrote about `str`, `__str__`, `repr`, and `__repr__` in more
detail [here][pydont-str-repr], so give that Pydon't a read
if you need.)

So, in conclusion, double leading and trailing underscores
are used for functions and variables with some “special”
meaning that often has to do with the default Python behaviour.

! Don't use (create) dunder names in your own programs,
! so that you don't trip on something unexpected and to avoid
! collisions with future changes/additions to the Python language!


## Double leading underscore

In this subsection we will take a look at what happens when you
use a double underscore in the beginning of a name.
A double underscore, in the beginning of a name, has a special
use case: you use it for variables and methods that you
would wish to “protect” with the leading underscore
(so that users know to leave it alone)
but that have such common names that you are afraid
others might overwrite them.

What does this mean?

First, let us see this in action.
Modify the `onlineshop.py` file so that our code
now belongs to a class called `OnlineShop`:

```py
# onlineshop.py
class OnlineShop:
    __prices = {
        "jeans": 20,
        "tshirt": 10,
        "dress": 30,
    }

    def _valid_discount(self, discount):
        return 0 <= discount <= 1

    def get_price(self, item, discount=0):
        if not self._valid_discount(discount):
            raise ValueError(f"Trying to apply an illegal discount on {item}.")
        p = self.__prices.get(item, None)
        if p is not None:
            return (1 - discount)*p
        else:
            return p
```

Notice that the `prices` variable now is `__prices`.
Let us take this little class for a spin:

```py
>>> from onlineshop import OnlineShop as OS
>>> shop = OS()
>>> shop.get_price("jeans")
20
```

The code appears to be working, so now let us take a look at the `__prices` variable:

```py
>>> shop.__prices
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
AttributeError: 'OnlineShop' object has no attribute '__prices'
```

Uh oh, an error again!
We can't reach the `__prices` variable, even though the `get_price` method
clearly makes (successful!) use of it.
Why can't we reach the `__prices` variable?
Well, we can use the [`dir()`][dir] built-in to list all the
attributes of our `shop` object:

```py
>>> dir(shop)
['_OnlineShop__prices', '__class__', '__delattr__', '__dict__', '__dir__', '__doc__',
'__eq__', '__format__', '__ge__', '__getattribute__', '__gt__', '__hash__', '__init__',
'__init_subclass__', '__le__', '__lt__', '__module__', '__ne__', '__new__', '__reduce__',
'__reduce_ex__', '__repr__', '__setattr__', '__sizeof__', '__str__', '__subclasshook__',
'__weakref__', '_valid_discount', 'get_price']
```

Go ahead and look for the names of the things we defined.
Can you find the `_valid_discount` and `get_price` functions?
What about `__prices`?
You won't be able to find `__prices` in that list,
but the very first item of the list is `_OnlineShop__prices`,
which looks awfully related.

Remember when I said that a double leading underscore is used to
avoid name collisions?
Well, there's a high chance that people might want to create
a variable named `prices` if they extend your online shop framework,
and you might still need your original `prices` variable,
so you have two options:

 - give a huge, very complicated, name to your `prices` variable,
 so that it becomes highly unlikely that others will create a variable
 with the same name; or
 - you use `__prices` to ask Python to _mangle_ the variable name,
 to avoid future collisions.

Going with the second option meant that Python took the original
variable name, which was `__prices`,
and prepended the class name to it, plus an additional leading underscore,
so that users still know they should leave that name alone.
That is the explicit name you can use to reach that variable from outside
the class:

```py
>>> shop._OnlineShop__prices
{'jeans': 20, 'tshirt': 10, 'dress': 30}
```

This name mangling facility works for both variables and functions,
so you could have a `__valid_discount` method that would look
like `_OnlineShop__valid_discount` from outside of the class,
for example.

It is highly likely that you won't have the need to use double leading
underscores in your code, but I couldn't just ignore this use case!


# Underscore as a sink

One of my favourite use cases for the underscore is when we use
the underscore as the target for an assignment.
I am talking about the times we use `_` as a variable name
in an assignment.

It is a widely-spread convention that using `_` as a variable name
means “I don't care about this value”.
Having said this, you should be asking yourself this:
If I don't care about a value, why would I assign it in the first place?
Excellent question!

Doing something like

```py
_ = 3       # I don't care about this 3.
```

_is_ silly.
Using the underscore as a sink
(that is, as the name of a variable that will hold a value that I
do _not_ care about)
is useful in _other_ situations.


## Unpacking

I have written at length about unpacking in other Pydon'ts:

 - [“Unpacking with starred assignments”][pydont-unpacking-star]
 - [“Deep unpacking”][pydont-deep-unpacking]

Unpacking is a feature that lets you, well, unpack multiple values
into multiple names at once.
For example, here is how you would split a list into its first and
last items, as well as into the middle part:

```py
>>> first, *mid, last = range(0, 10)
>>> first
0   
>>> mid
[1, 2, 3, 4, 5, 6, 7, 8]
>>> last
9
```

Isn't this neat?
Well, it is!
But what if you only cared about the first and last items?
There are various options, naturally, but I argue that the most elegant
one uses `_` as a sink for the middle part:

```py
>>> first, *_, last = range(0, 10)
>>> first
0
>>> last
9
```

Why is this better than the alternative below?

```py
>>> sequence = range(0, 10)
>>> first, last = sequence[0], sequence[-1]
```

Obviously, `sequence = range(0, 10)` is just an example of a sequence.
If I knew in advance this were the sequence I'd be using, then I would
assign `first = 0` and `last = 9` directly.
But for generic sequences, the two use cases behave differently.

Can you figure out when?
I talk about that in [this Pydon't][pydont-deep-unpacking].

The behaviour is different when `sequence` has only one element.
Because they behave differently, there might be cases where you
_have_ to use one of the two alternatives,
but when you are given the choice,
the unpacking looks more elegant and conveys the intent to
split the sequence in its parts better.

Of course `_` is a valid variable name and you can ask for its value:

```py
>>> first, *_, last = range(0, 10)
>>> _
[1, 2, 3, 4, 5, 6, 7, 8]
```

But when I see the `*_` in the assignment, I immediately understand
the semantics of that assignment as “ignore the middle part of the range”.

This can also be used when you are unpacking some structure,
and only care about specific portions of the structure.
You could use indexing to access the specific information you want:

```py
>>> colour_info = ("lightyellow", (255, 255, 224))
>>> blue_channel = colour_info[1][2]
>>> blue_channel
224
```

But if the `colour_info` variable is malformed,
you will have a hard time figuring that out.
Instead, using unpacking, you can assert that the structure is correct
and at the same time only access the value(s) that matter:

```py
>>> colour_info = ("lightyellow", (255, 255, 224))
>>> _, (_, _, blue_channel) = colour_info
>>> blue_channel
224
```


## Iterating independently of the iteration number

Another similar use case shows up when you need to iterate with a `for` loop,
but you really do _not_ care about the iteration number you are in.
For example, say that you want to generate 5 random integers between 0 and 20.
How would you write that?
I would write it as such:

```py
>>> import random
>>> nums = [random.randint(0, 20) for _ in range(5)]
[16, 1, 17, 3, 1]
```

Why did I use `_` in front of `for`?
Because the expression I am running repeatedly does not depend on the iteration
count, it is independent of that count.
So, in order to convey that meaning more clearly, I use the `_` as a sink
for the iterator variable.

Again, `_` is a perfectly valid variable name and I could use it
in the expression itself:

```py
>>> [_ + 2 for _ in range(5)]
[2, 3, 4, 5, 6]
```

But the point is that using `_` as a sink is a _convention_
to make the semantics of your programs more clear.


# Matching everything in the new match statement

The new `match` statement is coming in Python 3.10,
and [there is _much_ to look forward to][pydont-match].
Following the spirit of the common use case of using `_`
as a sink in assignments, the underscore will also be
used in the new `match` statement as the wildcard that matches
“anything else”:

```py
# Needs Python 3.10 to run
>>> v = 10
>>> match v:
...     case 0:
...             print("null")
...     case 1:
...             print("uno")
...     case 2:
...             print("two")
...     case _:
...             print("whatever")
... 
whatever
```

And in the case of the `match` statement, it is a true sink:
you cannot use the `_` to refer to the original value,
so in the `match` statement, `_` really means “I don't care”!
Take a look:

```py
>>> v = 10
>>> match v:
...     case _:
...             print(_)
... 
Traceback (most recent call last):
  File "<stdin>", line 3, in <module>
NameError: name '_' is not defined
```

If you want to match anything else _and_ be able to refer
to the original value, then you need to use a valid target name:

```py
>>> v = 10
>>> match v:
...     case wtv:
...             print(wtv)
... 
10
```


# String localisation

Another niche use case for the underscore, but that I find absolutely lovely,
is for when you need to localise your programs.
Localising a program means making it suitable for different regions/countries.
When you do that, one of the things that you have to do is translate the strings
in your program, so that they can be read in many different languages.

How would you implement a mechanism to enable your program to output
in (arbitrarily many) different languages?
Do think about that for a second, it is a nice challenge!
Assume you can't use modules built specifically for localisation.

Whatever you do, for example a function call or accessing a dictionary,
is going to happen in various places and is going to generate too much noise.
If your program has plenty of strings, going from

```py
print("Hello, world!")
```

to

```py
print(translate("Hello, world!"))
```

may look harmful, but in a program with many strings, all the `translate` calls
will add a lot of visual clutter.
So, it is common practice to create an alias to a function like the `translate`
function and call it `_`.
Then, localising a string doesn't add much visual clutter:

```py
print(_("Hello, World!"))
```

This is just a _convention_, but it is so common that it is even mentioned
in the [`gettext` docs][gettext-docs], the documentation for a module
designed specifically to help your programs handle multiple (natural) languages.

When I first found this usage of `_` I was very confused.
I found it when looking at the source code for the `argparse` module.
Because `argparse` deals with command-line interfaces,
it makes sense that its inner-workings are localised, so that its
command-line messages match the language of the command-line itself.
I still remember the very first time I saw it; I was looking at these two lines:

```py
if prefix is None:
    prefix = _('usage: ')
```

I was very confused with the `_('usage: ')` part of the assignment,
but eventually I found the import statement in that file:

```py
from gettext import gettext as _, ngettext
```

And I realised they were setting `_` as an alias for `gettext`.


# Improve number readability

The final use case for underscores that we will discuss has to do with
improving the readability of numbers.

Quick.

How much is `n` below?

```py
>>> n = 99999999
```

If you thought/said “99 million, 999 thousand and 999”,
you got it right.

Now, how much is `n` now?

```py
>>> n = 100_000_000
```

Is there any doubt that we are talking about 100 nillion?
Using `_` as a thousands separator really makes a difference here,
and you shouldn't need any more convincing!
But I'll just show you a little example from the Python Standard Library.
Take a look at the two conditions below, and let me know which one is
easier to read.

Without separator:

```py
if not 1000 <= rounds <= 999999999:
    raise ValueError('rounds out of the range 1000 to 999999999')
```

With separator:

```py
if not 1000 <= rounds <= 999_999_999:
    raise ValueError('rounds out of the range 1000 to 999_999_999')
```

If you tell me you prefer the first one, go away.
I don't want you here any more!

The underscore doesn't have to be the _thousands_ separator,
you can use it between any digits you may want.
But most importantly, it works with any other bases.

For example, use `_` to group bits in binary digits:

```py
>>> thirty_five = 0b0010_0011
>>> forty_seven = 0b0010_1111
```

Or maybe to separate the R, G, and B channels of the hexadecimal
value of a colour:

```py
>>> lightyellow = 0xff_ff_e0
>>> peachpuff   = 0xff_da_b9    # I didn't invent this name!
```


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Coding conventions exist to make our lives easier, so it is worth
 learning them to make our code more expressive and idiomatic.*”

This Pydon't showed you that:

 - you can recover the last value of an expression in the Python REPL
 with `_`;
 - `_` has quite an impact on names when used as a prefix/suffix:
   - `name_` is a common choice for when `name` is a reserved keyword;
   - `_name` is a _convention_ to signal that `name` is an internal name
   and that users probably shouldn't mess with it;
     - `_name` won't be imported if someone uses a `from mymodule import *`
     wildcard import; and
     - this can be overriden if `_name` is added to the `__all__` list
     in `mymodule`.
   - dunder names (that start and end with double underscore)
   refer to Python's internals and allow you to interact with Python's syntax;
   - `__name` is used inside classes to prevent name collisions, when you
   want to use an internal variable with a name that you are afraid users
   might override by mistake;
 - `_` is used in an idiomatic fashion as a sink in assignments, especially
   - when unpacking several values, when only some are of interest;
   - when iterating in a `for` loop where we don't care about the iteration number;
 - the new `match` statement uses `_` as the “match all” case and makes it
 a true sink because `_` can't be used to access the original value;
 - `_` is often used as an alias for localisation functions because of its
 low visual impact;
 - numbers in different bases (decimal, binary, ...) can have their digits
 split by underscores to improve readability. For example, compare
 `99999999` with `999_999_999` with `999999999`.


If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-chaining]: /blog/pydonts/chaining-comparison-operators
[pydont-str-repr]: /blog/pydonts/str-and-repr/
[pydont-bool]: /blog/pydonts/truthy-falsy-and-bool
[dir]: https://docs.python.org/3/library/functions.html#dir
[pydont-unpacking-star]: /blog/pydonts/unpacking-with-starred-assignments
[pydont-deep-unpacking]: /blog/pydonts/deep-unpacking
[pydont-match]: /blog/pydonts/structural-pattern-matching-tutorial
[gettext-docs]: https://docs.python.org/3/library/gettext.html
