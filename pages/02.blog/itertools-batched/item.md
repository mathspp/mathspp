Learn how `batched` from the module `itertools` works, example use cases, and how to implement it.

===

# `itertools.batched`

The module `itertools` introduced a new tool called `batched` in Python 3.12.
`itertools.batched` lets you iterate over an iterable by going over portions of that iterable – or batches – that all have the same size, except possibly for the last one.
Some [example use cases](#example-use-cases) include batching API requests or batching data processing.

As a dummy example, consider the snippet below:

```py
>>> from itertools import batched
>>> for batch in batched("Hello, world!", 3):
...     print(batch)
...
('H', 'e', 'l')
('l', 'o', ',')
(' ', 'w', 'o')
('r', 'l', 'd')
('!',)
```

Notice how the last batch is a tuple with the single character, `"!"`.
That's because the original input string had length 13 and I asked for batches of size 3, so `batched` served me with four batches of size 3 and the final batch contained the remaining character.


## Example use cases

In this section I will show you a couple of example use cases for `itertools.batched`.
If you know of any other good use cases, feel free to leave a comment below or to [reach out to me](/contact-me) and I'll include them here.


### Go over a file in chunks

If you have a huge file that you need to go over, but you don't need to read the whole file at once, you can use `batched` to go over a set number of lines at a time.
The code would look like this:

```py
from itertools import batched

with open(some_file, "r") as file:
    for chunk in batched(file, 25):
        process_chunk_of_lines(chunk)
```

This could work well if you were looking for some specific line in the file, for example, and you couldn't hold the whole file in memory.
Of course, in that case, you could probably increase the batch size to something bigger than `25`.


### Chunking a response over a socket

In contexts like socket communications, it is common to have to chunk your response to a maximum size, so if your message is bigger than some limit, you have to send it in chunks.
The code would look something like this:

```py
from itertools import batched

for chunk in batched(raw_data, 1024):
    sent = socket.send(b"".join(chunk))
    if sent < len(chunk):
        # Handle the fact that not all data was sent.
```

In this case, you may need to send strings (or bytes) over the socket, and that is why we do `b"".join(...)`, because `batched` returns tuples with the elements.


### Iterating over substrings

If you need to use `batched` to split a string, but the final thing you need is substrings, you can use `batched` together with `"".join` and `map` to create an iterator that produces substrings of a given length:

```py
map("".join, batched(string, length))
```

Here is an example:

```pycon
>>> from itertools import batched
>>> hello_world_substrings = map("".join, batched("Hello, world!", 3))
>>> for substring in hello_world_substrings:
...     print(substring)
...
Hel
lo,
 wo
rld
!
```

This could also work well with the [socket example](#chunking-a-response-over-a-socket) from above.

Another example where this works well was mentioned to me on Twitter.
Someone was going over a DNA string and needed to turn codons into aminoacids.
According to them, this really just means they needed to go over substrings of length 3, identify them, and store the result elsewhere.

This is how you could do it with `batched`:

```py
from itertools import batched

codons_to_aminoacids = {
    "TTT": "phe",
    "TTC": "phe",
    "TTA": "leu",
    ...
    "CAG": "gln",
    ...
}

dna_string = "CAGTTAGCAGTTACG..."
aminoacids = []
for codon in map("".join, batched(dna_string, 3)):
    aminoacids.append(codons_to_aminoacids[codon])
```

Rewriting this [as a list comprehension](/blog/pydonts/list-comprehensions-101), it would become

```py
# ...

aminoacids = [
    codons_to_aminoacids[codon]
    for codon in map("".join, batched(dna_string, 3))
]
```

Quite neat, right?


### Accessing a rate limited API

It is common for external APIs to have rate limits.
For example, a given API may only accept 100 requests every minute.
In that case, if you have an iterable with all the requests you want to make, you can use `batched` along with a sleep to batch the API requests you make.

Here is an example:

```py
from itertools import batched
from time import sleep

REQS_PER_MINUTE = 100

for batched_requests in batched(all_requests, REQS_PER_MINUTE):
    for request in batched_requests:
        request.make_request()
    sleep(60)  # Sleep a minute to refresh API limits.
```

This snippet should take the rate limits into account and allow you to access the API without any major hurdles.
This may or may not need some minor adjustments, depending on the API you're working with.


### Paginating results

If you need to paginate results – for example, when implementing an API – you can also use `batched`.
In the simplest case, you could whip up a `Pager` class like so:

```py
from itertools import batched


class Pager:
    def __init__(self, results, page_size=25):
        self.pages = batched(results, page_size)

    def next_page(self):
        """Gets the next page of results or None."""
        return next(self.pages, None)


pager = Pager(range(10), page_size=4)
print(pager.next_page())  # (0, 1, 2, 3)
print(pager.next_page())  # (4, 5, 6, 7)
print(pager.next_page())  # (8, 9)
print(pager.next_page())  # None
```

If you look closely, though, the class `Pager` is just a thin wrapper around `batched` and doesn't do a great deal.
However, you can make the `Pager` more interesting by adding a `prev_page` method:

```py
from itertools import batched


class Pager:
    def __init__(self, results, page_size=25):
        self.pages = batched(results, page_size)
        # Initialise empty navigation caches.
        self.prev_pages = []
        self.next_pages = []

    def next_page(self):
        """Gets the next page of results or None."""
        # Get the next page, possibly from the navigation cache.
        if self.next_pages:
            next_page = self.next_pages.pop()
        else:
            next_page = next(self.pages, None)

        if next_page is not None:
            self.prev_pages.append(next_page)

        return next_page

    def prev_page(self):
        """Gets the previous page of results or None."""
        # The last page in prev_pages was the last one sent, so we need
        # to move it to `next_pages`.
        if len(self.prev_pages) >= 2:
            self.next_pages.append(self.prev_pages.pop())
            return self.prev_pages[-1]

        return None


pager = Pager(range(10), page_size=4)

print(pager.prev_page())  # None
print(pager.next_page())  # (0, 1, 2, 3)
print(pager.prev_page())  # None
print(pager.next_page())  # (4, 5, 6, 7)
print(pager.prev_page())  # (0, 1, 2, 3)
print(pager.next_page())  # (4, 5, 6, 7)
print(pager.next_page())  # (8, 9)
print(pager.next_page())  # None
print(pager.next_page())  # None
print(pager.prev_page())  # (4, 5, 6, 7)
print(pager.prev_page())  # (0, 1, 2, 3)
print(pager.next_page())  # (4, 5, 6, 7)
```

If you only want to keep a limited history for navigation purposes (instead of potentially holding _all_ the results in the attributes `prev_pages` and `next_pages`), you can use a `collections.deque` for `self.prev_pages`.


!!! If you have any other use cases for `itertools.batched`, feel free to leave a comment below and I may add it here!


## Implementation

### The algorithm

The implementation of `itertools.batched` is "straightforward" if you consider the fact that the Python documentation provides almost everything we need.

The Python documentation says that `itertools.batched` is roughly equivalent to the following:

```py
from itertools import islice

def batched_docs(iterable, n):
    # batched('ABCDEFG', 3) --> ABC DEF G
    if n < 1:
        raise ValueError('n must be at least one')
    it = iter(iterable)
    while batch := tuple(islice(it, n)):
        yield batch
```

The generator function above takes care producing the batches of the correct size on demand.
However, `batched` is a class while the implementation `batched_docs` is a generator:

```pycon
>>> batched("Hello, world!", 3)
<itertools.batched object at 0x102be5c60>
>>> batched_docs("Hello, world!", 3)
<generator object batched_docs at 0x102ff9d50>
```


### The iterator protocol

To implement a copy of `batched` on our own, we can create a class `batched_` that implements the iterator protocol.
The "iterator protocol" is the set of rules that an object must follow so that Python can use it in `for` loops and in other similar contexts.

For example, [the class `Pager` from before](#paginating-results) cannot be used inside a `for` loop:

```py
for page in pager:
    print(page)
# TypeError: 'Pager' object is not iterable
```

However, if `Pager` implemented the iterator protocol, the code above would work.
The same thing can be said of `batched_`: we need to implement the iterator protocol and then we have our Python implementation of `itertools.batched`.

The simplest way to implement the iterator protocol is by defining the [dunder method `__iter__`][dunder-pydont] as a generator function that yields the values we care about, as the code below shows:


```py
from itertools import islice

class batched_:
    def __init__(self, iterable, n):
        if n < 1:
            raise ValueError('n must be at least one')
        self.iterable = iterable
        self.n = n

    def __iter__(self):
        it = iter(self.iterable)
        while batch := tuple(islice(it, self.n)):
            yield batch


for batch in batched_("Hello, world!", 3):
    print(batch)
"""
('H', 'e', 'l')
('l', 'o', ',')
(' ', 'w', 'o')
('r', 'l', 'd')
('!',)
"""
```

This implementation is _very_ close to the correct result, but it isn't 100% accurate...

Here is the difference:

```pycon
# Our version:
>>> b = batched_("Hello, world!", 3)
>>> list(b)
[('H', 'e', 'l'), ('l', 'o', ','), (' ', 'w', 'o'), ('r', 'l', 'd'), ('!',)]
>>> list(b)
[('H', 'e', 'l'), ('l', 'o', ','), (' ', 'w', 'o'), ('r', 'l', 'd'), ('!',)]

# The `itertools` version:
>>> b = batched("Hello, world!", 3)
>>> list(b)
[('H', 'e', 'l'), ('l', 'o', ','), (' ', 'w', 'o'), ('r', 'l', 'd'), ('!',)]
>>> list(b)
[]
```

Notice how our version shows the complete list twice, while `itertools.batched` only produces the results once...
That's because...


### `batched` is an iterator

`itertools.batched` is an iterable.
It is something you can use in `for` loops.
But `batched` is a very specific type of iterable.
It is an _iterator_.

An _iterator_, in Python, is an object that implements [the dunder methods `__iter__` and `__next__`][dunder-pydont].
For an iterator,

 - the dunder method `__iter__` should return the object itself; and
 - the dunder method `__next__` should return the successive elements and then raise `StopIteration` when done.

So, if `itertools.batched` is an iterator, we need to make sure our version is too:

```py
from itertools import islice

class batched_:
    def __init__(self, iterable, n):
        if n < 1:
            raise ValueError('n must be at least one')
        self.iter = iter(iterable)
        self.n = n

    def __iter__(self):
        return self

    def __next__(self):
        batch = tuple(islice(self.iter, self.n))
        if not batch:
            raise StopIteration()
        return batch
```

This is a much more faithful Python implementation of `itertools.batched`!


[dunder-pydont]: /blog/pydonts/dunder-methods
