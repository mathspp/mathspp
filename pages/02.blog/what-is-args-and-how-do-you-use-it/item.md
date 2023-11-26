This article explains what `*args` means in Python and how to use it in your own functions.

===


# What is `*args` and how do you use it?

You probably know the built-in function `max`.
What does it do?
You give it some values, and it returns the largest one.

How many values can you give it?
However many you want!
Here are three examples below:

```pycon
>>> max(1, 2)
2
>>> max(1, 2, 3)
3
>>> max(1, 2, 3, 4, 5, 6, 7)
7
```

The number of arguments that the function `max` accepts is variable.

It's not just 2.
It's not just 2 or 3.
It's not just 2, 3, or 7.
It's _**any**_ number of arguments.
You could even pass it 100 or 1000 arguments.

But now, think about how you would implement a function that works like this.
How do you create a function that takes an _arbitrary_ number of arguments?

For example, how do you take the function `add` below and make it so that it works with as many arguments as you want?

```
def add(a, b):
    return a + b
```

Right now, it only accepts 2 arguments.
What if you want it to accept 1, 2, 7, 100, or 1000 arguments?
That's when `*args` comes in.

If you use `*args` as a parameter, Python knows that it can collect as many arguments as the user passes in, and they will be stored inside the tuple `args`.

Here's a function that prints its `args` so that we can see what `args` really is:

```pycon
>>> def print_args(*args):
...     print(f"args is a {type(args)} with {len(args)} elements.")
...     print(args)
...

>>> print_args(1)
args is a <class 'tuple'> with 1 elements.
(1,)

>>> print_args(1, 2)
args is a <class 'tuple'> with 2 elements.
(1, 2)

>>> print_args([73, 42])
args is a <class 'tuple'> with 1 elements.
([73, 42],)

>>> print_args([73, 42], "oi", "rodrigo", False)
args is a <class 'tuple'> with 4 elements.
([73, 42], 'oi', 'rodrigo', False)

>>> print_args() args is a <class 'tuple'> with 0 elements.
()
```

There's one thing I want you to understand, now.
The 5 characters `*args` aren't special.
What matters is the asterisk `*` immediately to the left of a parameter name.
Typically, in Python we use `*args`, but you could've typed `*bananas` or `*abracadabra`.

This new version of `print_args` below works just as well:

```py
def print_args(*abracadabra):
    print(f"abracadabra is a {type(abracadabra)} with {len(abracadabra)} elements.")
    print(abracadabra)
```

Here's a summary of what we've seen so far:

 - `*args` enables functions to receive an arbitrary number of arguments;
 - it's the asterisk `*` on the left of a parameter name that makes this possible, we just use `*args` as a convention; and
 - `args` is always a tuple, which can be empty.

Now, with all this knowledge, you can implement the function `add` as I challenged you above.
How would you do it?
Something like this:

```pycon
>>> def add(*numbers_to_add):
...     added = 0
...     for number in numbers_to_add:
...         added += number
...     return added
...
>>> add(1, 2, 3, 4, 5)
15
```

Even better would be to just leverage the built-in `sum`, given that `numbers_to_add` is a tuple:

```pycon
# Or even better, given that “numbers_to_add” is a tuple:
>>> def add(*numbers_to_add):
...     return sum(numbers_to_add)
...
>>> add(1, 2, 3, 4, 5)
15
```

So, this is the magic of `*args`.

We're just scratching the surface, though.
The asterisk `*` can be used in Python for many more (related) things.
For example, you can [read about unpacking with starred assignments](/blog/pydonts/unpacking-with-starred-assignments).
