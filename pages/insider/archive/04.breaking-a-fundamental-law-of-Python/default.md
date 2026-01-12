---
date: 12-01-2025
metadata:
    description: "Learn how `itertools.tee` seems to break a fundamental law of Python and how its behaviour is actually legal."
title: Breaking a fundamental law of Python

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Breaking a fundamental law of Python

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!


## Breaking the law

Every time you use `itertools.tee` you are breaking the law.

You're breaking a Python law, but you're breaking a law nonetheless.

See, iterators can only be traversed once.
That's the law.

But `itertools.tee` lets you go over an iterator twice.
Or three times.
Or even ten.

But how?


## Iterables and iterators

To understand what's going on, first you must be familiar with the distinction between “iterator” and “iterable” in Python.

The two terms are obvious related but they don't mean quite the same thing.

An **iterable** is anything you can traverse with a `for` loop.

Lists, strings, dictionaries, sets, files, are just some examples of iterables.

An **iterator** is the Python object that is actually _responsible for enabling the iteration_.

Under the hood, whenever you use a `for` loop on an iterable, Python uses the underlying iterator.


## The iterator of a list

To see this for yourself, you can try creating a simple list and then using the built-in `iter` to get the iterator corresponding to the list:

```pycon
>>> my_list = [42, 73]
>>> it = iter(my_list)
>>> print(it)
<list_iterator object at 0x1005788b0>
```

This `list_iterator` is the iterator that Python uses when it needs to _iterate over the list_.

You can use the built-in `next` to ask for successive elements of the list:

```pycon
>>> next(it)
42
>>> next(it)
73
>>> next(it)
Traceback (most recent call last):
  File "<python-input-7>", line 1, in <module>
    next(it)
    ~~~~^^^^
StopIteration
```

Once you get to the end of the iterator, you get an error.

That's it, that's how `for` loops actually work under the hood.

Now, what's the law you wanted to know about?


## Iterators can only be traversed once

Right, iterators can only be traversed once.

What does this mean?

It means that, once you hit the `StopIteration` with `next`, the iterator is _exhausted_.

If you want to go over the list again, you need a **new iterator**.

This is the law.


## Generators can only be traversed once, too

Generators are another _example of an iterator_.

Generators can only be traversed once!

For example, if you create a generator that produces some square numbers and try to loop over it twice, it doesn't work:

```py
squares = (x ** 2 for x in range(1, 3))

for sq in squares:
    print(sq)
# 1
# 4

for sq in squares:
    print(sq)
# Nothing is printed!
```


## Using `tee` to split an iterator

This is where `tee` comes in!

If you use `tee`, from the module `itertools`, you can create multiple copies of the same iterator!

This essentially means you can go over it as many times as you need!

Here's the same `squares` generator, now using `tee` to split it:

```py
from itertools import tee

squares = (x ** 2 for x in range(1, 3))
squares1, squares2 = tee(squares, 2)

for sq in squares1:
    print(sq)
# 1
# 4

for sq in squares2:
    print(sq)
# 1
# 4
```

If you use `tee(squares, 10)`, you get 10 copies of the generator `squares` instead!

That's pretty nifty, right?

But how does `tee` do it..?


## A possible solution...

One possible solution is to have `tee` convert the given iterator into a list and then just return multiple instances of the list:

```py
def my_tee(iterator, n):
    values = list(iterator)
    return [values] * n
```

This short implementation of `tee` is enough to make sure the example with the generator `squares` works:

```py
def my_tee(iterator, n):
    values = list(iterator)
    return [values] * n

squares = (x ** 2 for x in range(1, 3))
squares1, squares2 = my_tee(squares, 2)

for sq in squares1:
    print(sq)
# 1
# 4

for sq in squares2:
    print(sq)
# 1
# 4
```

But the thing is...

This solution not only is _terribly inefficient_ –

Because you're creating a list to hold _all_ the values that the iterator would produce –

But is also **wrong**!


## Handling large or infinite iterators

The generator `squares` only produced 2 numbers.
But what if it produced 2,000,000,000,000 numbers?

Would you want to create a list with 2,000,000,000,000 numbers?

Or even worse, what if the generator `squares` was infinite?

Here is an example:

```py
from itertools import count

squares = (x ** 2 for x in count())
```

If you call `my_tee` on the generator `squares` above, your Python interpreter will become unresponsive...

If you wait for long enough, you might even crash your computer!
(I'm not sure, I didn't dare to go that far.)

And yet, the original `tee` works just fine:

```py
from itertools import count, tee

squares = (x ** 2 for x in count())
squares1, squares2 = tee(squares, 2)

for sq in squares1:
    print(sq, end=", ")
    if sq == 36:
        break
# 0, 1, 4, 9, 16, 25, 36,

for sq in squares2:
    print(sq, end=", ")
    if sq == 16:
        break
# 0, 1, 4, 9, 16
```

So `tee` must be doing something really clever, right?

Well, yes, but actually no!


## Can you implement `tee`?

Try to implement `tee` yourself.

This is actually one of the exercises in my book [“The little book of itertools”](https://mathspp.com/books/the-little-book-of-itertools).

If you want, give it a try and send me your code before next Monday.

Next week I'll show you how you can implement `tee` in an efficient way.


## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
