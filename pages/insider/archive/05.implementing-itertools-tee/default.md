---
date: 19-01-2025
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to implement itertools.tee efficiently using deques as buffers."
    og:image: "https://mathspp.com/insider/archive/implementing-itertools-tee/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/implementing-itertools-tee/thumbnail.webp"
title: Implementing itertools.tee

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Implementing `itertools.tee`

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!


## What `tee` does

[Last week you learned about `itertools.tee`](https://mathspp.com/insider/archive/breaking-a-fundamental-law-of-python) and how it works.

In case you don't recall, `tee` takes an **iterator** and splits it into 2+ _independent_ **iterators**:

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

The first and second loops produced the exact same output, thanks to `tee`.

If you don't use `tee` to split `squares` into `squares1` and `squares2`, the second loop is empty:

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

You can see that there is no output after the second loop because the generator `squares` was _exhausted_ after the first loop.


## Inefficient implementation of `tee`

A possible implementation of `tee` takes the input iterable, converts it to a list, and returns copies of that list:

```py
def my_tee(iterator, n):
    values = list(iterator)
    return [values] * n
```

But this is _very inefficient_...

Imagine the original iterator had millions of elements!

But there is something worse than being inefficient...

This solution _doesn't work_ for infinite iterators.

So, we need to do something smarter...


## Double-ended queues

The correct solution will use **double-ended queues**.

In Python, that's `collections.deque`.

A `deque` can be used to append items to one end and pop items from the other end efficiently:

```py
from collections import deque

d = deque()
d.append(1)
d.append(2)
d.append(3)
print(d.popleft())  # 1
print(d)  # deque([2, 3])
```

The method `popleft` pops an item from the left (efficiently).

And this is what you'll use.

For each independent iterator you want to create, you create a new `deque`.

Each `deque` will act as a buffer for the data that has been produced already.

Think about it!

The original iterator can only be traversed _once_...

And you have to give the illusion of being able to traverse multiple iterators independently...

In reality, what you'll do is take an element from the iterator and put it in all the buffers to be used later.


## Implementation for the auxiliary object

When you call `tee` as `tee(iterable, n)`, you have to return `n` objects.

These are the `tee` iterators that act as the independent iterators.

You can give it whatever name you want, like `_tee_iterator`:

```py
class _tee_iterator:
    ...
```

When you create these iterators, you need to:

 1. give them access to the original iterable that is the source of the values;
 2. create an empty buffer for the values; and
 3. create an attribute that holds references to _all_ auxiliar iterators.

Point 3. is the clever trick that will make sure `_tee_iterator` works.

But the 3 points look like this:

```py
from collections import deque

class _tee_iterator:
    def __init__(self, source):
        self.source = source
        self.buffer = deque()
        self._tee_iterators = None
```

When you first instantiate `_tee_iterator`, you don't have access to the other instances so you start by setting the attribute to `None`.

It's the function `tee` that will set the attribute to the full list:

```py
def tee(iterable, n):
    iterators = [_tee_iterator(iterable) for _ in range(n)]
    for it in iterators:
        it._tee_iterators = iterators
    return tuple(iterators)
```

You are almost done.


## Ensuring you can iterate over the auxiliar objects

At this point, your code has the correct structure but the instances of `_tee_iterator` can't be traversed yet.

For iteration to work, you need to implement the **iterator protocol**, which means defining two dunder methods:

 - `__iter__` – this should just return `self`; and
 - `__next__` – this should return the _next_ element.

Defining `__iter__` according to the **iterator protocol** amounts to two lines of code:

```py
class _tee_iterator:
    ...

    def __iter__(self):
        return self
```

Since `_tee_iterator` is an iterator, its method `__iter__` must return itself.

It is one of the rules of the **iterator protocol**.

The other dunder method is `__next__`, and that's where everything comes together.

For an instance of `_tee_iterator` to produce its next value, ideally it just has to pop something from the data buffer:

```py
class _tee_iterator:
    ...

    def __next__(self):
        return self.buffer.popleft()
```

But this only works if the buffer _has_ an element in it.

If the buffer is empty, you need to:

 1. grab the next value from the source iterator; and
 2. save the value in the buffers of _all_ the associated `_tee_iterator` instances.

Step 2. is the one that makes it look like all iterators are independent copies of the original iterator.

The full implementation of `__next__` looks more or less like this:

```py
class _tee_iterator:
    ...

    def __next__(self):
        if not self.buffer:
            value = next(self.source)
            for it in self._tee_iterators:
                it.buffer.append(value)

        return self.buffer.popleft()
```

Isn't this cool?

Take this implementation of `tee` for a spin and let me know what you think.


## Applying `tee` in the real world

This is all good, but what is `tee` useful for?

For splitting a single-pass iterator into 2+ copies!

As an example, you can use `tee` to implement `itertools.pairwise`.

Can you do it?


## Full source code

<details markdown=1>
<summary>Full source code for the implementation of tee.</summary>


```py
from collections import deque

def tee(iterable, n):
    iterators = [_tee_iterator(iterable) for _ in range(n)]
    for it in iterators:
        it._tee_iterators = iterators
    return tuple(iterators)

class _tee_iterator:
    def __init__(self, source):
        self.source = source
        self.buffer = deque()
        self._tee_iterators = None

    def __iter__(self):
        return self

    def __next__(self):
        if not self.buffer:
            value = next(self.source)
            for it in self._tee_iterators:
                it.buffer.append(value)

        return self.buffer.popleft()
```

</details>



## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
