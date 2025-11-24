In this article you will learn about itertools.pairwise, how to use it, and how to generalise it.

===

In this tutorial you will learn to use and generalise `itertools.pairwise`.
You will understand what `itertools.pairwise` does, how to use it, and how to implement a generalised version for when `itertools.pairwise` isn't enough.


## `itertools.pairwise`

`itertools.pairwise` is an iterable from the standard module `itertools` that lets you access overlapping pairs of consecutive elements of the input iterable.
That's quite a mouthful, so let me translate:

 > You give `pairwise` an iterable, like `"ABCD"`, and `pairwise` gives you pairs back, like `("A", "B")`, `("B", "C")`, and `("C", "D")`.

In loops, it is common to unpack the pairs directly to perform some operation on both values.
The example below uses `pairwise` to determine how the balance of a bank account changed based on the balance history:

```py
from itertools import pairwise  # Python 3.10+

balance_history = [700, 1000, 800, 750]

for before, after in pairwise(balance_history):
    change = after - before
    print(f"Balance changed by {change:+}.")
```
```txt
Balance changed by +300.
Balance changed by -200.
Balance changed by -50.
```


## How to implement `pairwise`

If you had to implement `pairwise`, you might think of something like the code below:

```py
def my_pairwise(iterable):
    for prev_, next_ in zip(iterable, iterable[1:]):
        yield (prev_, next_)
```

Which directly translates to

```py
def my_pairwise(iterable):
    yield from zip(iterable, iterable[1:])
```

But there is a problem with this implementation, and that is the slicing operation.
`pairwise` is supposed to work with any iterable and not all iterables are sliceable.
For example, files are iterables but are not sliceable.

There are a couple of different ways to fix this but my favourite uses [`collections.deque` with its parameter `maxlen`](https://mathspp.com/blog/python-deque-tutorial#implement-itertools-pairwise):

```py
from collections import deque
from itertools import islice

def my_pairwise(data):
    data = iter(data)
    window = deque(islice(data, 1), maxlen=2)
    for value in data:
        window.append(value)
        yield tuple(window)
```


## Generalising `itertools.pairwise`

`pairwise` will always produce **pairs** of consecutive elements, but sometimes you might want tuples of different sizes.
For example, you might want something like “`triplewise`”, to get triples of consecutive elements, but `pairwise` can't be used for that.
So, how do you implement that generalisation?

In the upcoming subsections I will present different ways of implementing the function `nwise(iterable, n)` that accepts an iterable and a positive integer `n` and produces overlapping tuples of `n` elements taken from the given iterable.

Some example applications:

```py
nwise("ABCD", 2) -> ("A", "B"), ("B", "C"), ("C", "D")
nwise("ABCD", 3) -> ("A", "B", "C"), ("B", "C", "D")
nwise("ABCD", 4) -> ("A", "B", "C", "D")
```


### Using `deque`

The implementation of `pairwise` that I showed above can be adapted for `nwise`:

```py
from collections import deque
from itertools import islice

def nwise(iterable, n):
    iterable = iter(iterable)
    window = deque(islice(iterable, n - 1), maxlen=n)
    for value in iterable:
        window.append(value)
        yield tuple(window)
```

Note that you have to change `maxlen=2` to `maxlen=n`, but also `islice(iterable, 1)` to `islice(iterable, n - 1)`.


### Using `tee`

Another fundamentally different way of implement `nwise` is by using `itertools.tee` to split the input iterable into `n` different iterators, advance each one of them a bit more than the previous one, and then use `zip` to go through them at the same time:

```py
from collections import deque
from itertools import islice, tee

def nwise(iterable, n):
    iterators = tee(iter(iterable), n)
    for idx, iterator in enumerate(iterators):
        for _ in islice(iterator, idx):  # Advance this iterator by `idx` elements.
            pass

    yield from zip(*iterators)
```

The heavy lifting is all done by `itertools.tee`, which takes `iter(iterable)` – a single iterator – and creates `n` independent iterators out of that one.
That's how you can use the `for` loop that follows to go through each iterator and consume part of its output.


### Using recursion and `pairwise`

This might be my favourite way of implementing `nwise`, and that is by leveraging recursion and `pairwise` itself!

I came up with this approach when I realised you could implement `triplewise` by using `pairwise` twice:

```py
from itertools import pairwise

def triplewise(iterable):
    for (a, _), (b, c) in pairwise(pairwise(iterable)):
        yield (a, b, c)

print(list(triplewise("ABCDE")))
```

This means you can use `pairwise` recursively to implement `nwise` for any value of `n` you care about!
That will give you this implementation:

```py
from itertools import pairwise

def nwise(iterable, n):
    if n == 1:
        yield from iter(iterable)
        return

    for (head, *_), tail in pairwise(nwise(iterable, n - 1)):
        yield (head, *tail)
```


## Conclusion

This article gave you a brief introduction to `itertools.pairwise`: you learned how it works and how to implement it.
You also learned how to implement the function `nwise`, a generalisation of `itertools.pairwse`, in three different ways.
Now, whenever you need to iterate over an iterable to access consecutive elements, you know what tools to reach out for!

And hey, if you come up with a fundamentally different way of implementing `nwise`, share it in the comments below and I'll update this article to include it!
