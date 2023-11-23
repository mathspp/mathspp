This article shows 5 ways of flattening a list of lists, ranked from worst to best.

===


# 5 ways to flatten a list of lists

This short article will show 5 ways of flattening a list of lists, ranked from worst to best.

This is the list we'll be using:

```py
list_of_lists = [
    [1, 2, 3],
    [4, 5],
    [6],
    [7, 8, 9],
]
```

Let's start.


## 5th – using `functools.reduce`

```pycon
>>> from functools import reduce
>>> from operator import add
>>> flat_list = reduce(add, list_of_lists, [])
>>> flat_list
[1, 2, 3, 4, 5, 6, 7, 8, 9]
```

This is not a great idea because using `reduce(add, ...)` is almost always worse than using the equivalent built-in `sum`.

In other words, `sum` is the specialised version of `reduce` that adds things, so you'll almost never need `reduce(add, ...)`.

(`reduce` isn't useless, though. You can and should [read about `reduce` and its use-cases](/blog/pydonts/the-power-of-reduce).)

So, this leads clearly to the next version.


## 4th – using `sum`

```pycon
>>> flat_list = sum(list_of_lists, [])
>>> flat_list
[1, 2, 3, 4, 5, 6, 7, 8, 9]
```

This is better than option 5.

But it's still not great.

The main reason this isn't great is because `sum` will do too much work.
(Can you see why?)

Every time we add two lists (the accumulated one and one from `list_of_lists`), we need to create a third list with all of the elements of the two, so we'll waste a lot of time recomputing lists.

So, using `sum` is a neat party trick, and it shows you understand the underlying way in which `sum` works, but it isn't practical.


## 3rd – two nested loops

This is the KISS solution.
It's pretty straightforward and it's a great solution!
Two `for` loops and an `append`:

```py
>>> flat_list = []
>>> for sublist in list_of_lists:
...     for element in sublist:
...         flat_list.append(element)
...
>>> flat_list
[1, 2, 3, 4, 5, 6, 7, 8, 9]
```

This is a brilliant solution!
The reason it's placed in 3rd is not because it's bad, but because you can do even better!


## 2nd – using a list comprehension

```pycon
>>> flat_list = [
...     element
...     for sublist in list_of_lists
...     for element in sublist
... ]
>>> flat_list
[1, 2, 3, 4, 5, 6, 7, 8, 9]
```

This solution is better than the two loops because there are many advantages to using list comprehensions when you have simple `for` loops whose only job is to append items to a new list.

I've written extensively about list comprehensions before!
If you're interested (and you should be!) you can [learn more about list comprehensions and their advantages](/blog/pydonts/list-comprehensions-101).

The list comprehension is always better than the two loops.
So, that's why it comes after the two loops.
But the next solution is only better than the list comprehension in some cases.


## 1st – using `itertools.chain`:

```pycon
>>> from itertools import chain
>>> flat_list = list(chain.from_iterable(list_of_lists))
>>> flat_list
[1, 2, 3, 4, 5, 6, 7, 8, 9]
```

Now, when can this be better than the list comprehension?

Using `chain` is better than the list comprehension when you don't actually need to wrap the call to `chain` in a call to `list`!
In the code above, I used `list(chain...)` because I wanted you to see the resulting list.
But `chain` is a generator, which means it's lazy.

`chain` being lazy means it's very useful when you want to iterate through the flat list, but don't necessarily need a list _per se_.

For example, suppose you're traversing the flat list in a `for` loop and might have a condition that breaks out of the loop if you find something.
In that case, the `chain` might be better.

So, this solution is only better than the list comprehension in some cases, but what's good about me showing it to you is that it teaches you about `chain`, which is quite useful.


## Did I miss something?

If you think I should include another way of flattening a list of lists, even if it's not better than all of the five I already shared, feel free to comment below!
