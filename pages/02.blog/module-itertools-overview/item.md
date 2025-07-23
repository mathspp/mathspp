This article briefly describes the iterators available in the Python module itertools and how to use them.

===


The Python module `itertools` contains 20 tools that every Python developer should be aware of.
We divide the iterators from the module `itertools` in 5 categories to make it easier to learn them and we also present a short list of the generally most useful ones.


## All the iterators from `itertools`

| Category | Iterators |
| :- | :- |
| [Reshaping iterators](#reshaping-iterators) | `batched`, `chain`\*, `groupby`, `islice`, `pairwise`\* |
| [Filtering iterators](#filtering-iterators) | `compress`, `dropwhile`, `filterfalse`, `takewhile` |
| [Combinatorial iterators](#combinatorial-iterators) | `combinations`, `combinations_with_replacement`, `permutations`, `product`* |
| [Infinite iterators](#infinite-iterators) | `count`, `cycle`, `repeat` |
| [Iterators that complement other tools](#iterators-that-complement-other-tools) | `accumulate`, `starmap`, `zip_longest` |

On top of the 19 iterators listed in the table above, the module `itertools` also provides the function `tee`, which is very powerful but often not necessary.
In [“The little book of `itertools`”](/books/the-little-book-of-itertools) I devote a small chapter to it because understanding and reimplementing `tee` is an excellent learning and coding exercise.


## The 3 most useful iterators from `itertools`

In my experience, the 3 more commonly useful tools in the module `itertools` are `product`, `chain`, and `pairwise`.

### `product` flattens nested loops

The iterator `product` is a [combinatorial iterator](#combinatorial-iterators) that is very useful when you want to flatten a series of nested `for` loops.
As the prototypical example, a nested loop that traverses a two-dimensional grid can be rewritten as a single loop with `product`.

So, whenever we have two or more independent, nested `for` loops, like below:

```py
for x in range(width):
    for y in range(height):
        # Do stuff...
```

We can reshape them into a single loop if we use `product`:

```py
from itertools import product

for x, y in product(range(width), range(height)):
    # Do stuff...
```

The flat structure gives you more horizontal space to write your code and makes it easier to manage breaking out of your loop.

This is a very common use case for `product`.
If you go back to old code of yours, I am sure you will be able to find places where you could rewrite a loop like this.


### `chain` creates a single iterable out of many

The iterator `chain` lets you chain two or more iterables together, so that you can traverse them in sequence without having to add them explicitly.

When you are dealing with iterables like lists or strings, you might argue that you would rather spend the time doing the addition instead of having to import `chain`, but this doesn't always work.

Consider this snippet of code that concatenates two lists so that we can traverse them:

```py
# Typical pattern:
first_list = [...]
second_list = [...]
full_list = first_list + second_list  # + third_list + ...
for element in full_list:
    # Do stuff
```

Using `chain`, we wouldn't need the addition:

```py
from itertools import chain

first_list = [...]
second_list = [...]
for element in chain(first_list, second_list):  # Also works with 3+ iterables.
    # Do stuff
```

This also works in situations where you can't concatenate the iterables:

```py
first_gen = (x ** 2 for x in range(3))
second_gen = (x ** 3 for x in range(3))
# first_gen + second_gen  # TypeError!
for value in chain(first_gen, second_gen):
    print(value, end=" ")  # 0 1 4 0 1 8
```

You might also be thinking about the fact that you could just use the built-in `list` on `gen1` and `gen2` to convert them to lists, and then concatenate the lists.
This is true, but it's typically a waste of resources _and_ it won't work when dealing with infinite iterators.

The iterator `chain` also provides an auxiliary constructor called `chain.from_iterable` which, put simply, flattens an iterable.
A typicaly use case would be to flatten a list of lists:

```py
nested = [[1, 2, 3], [4], [], [5, 6]]
flat = list(chain.from_iterable(nested))
print(flat)  # [1, 2, 3, 4, 5, 6]
```

The beauty of `chain.from_iterable` is that you don't even need to convert the final result into a list if all you want is to traverse over the elements:

```py
nested = [[1, 2, 3], [4], [], [5, 6]]
for value in chain.from_iterable(nested):
    print(value, end=" ")  # 1 2 3 4 5 6
```


### `pairwise` produces overlapping pairs of consecutive elements

The iterator `pairwise` accepts any iterable and produces overlapping pairs of consecutive elements.

It is, essentially, an efficient and general implementation of the pattern `zip(my_list[:-1], my_list[1:])`.
Thus, `pairwise` is useful for two main reasons:

 1. slicing can be expensive if you are dealing with a large iterable; and
 2. not all iterables support slicing.

The common pattern that `pairwise` replaces is the following:

```py
names = ["Harry", "Anne", "George"]

for left, right in zip(names[:-1], names[1:]):
    print(f"{left} says hi to {right}")

"""Output:
Harry says hi to Anne
Anne says hi to George
"""
```

Using `pairwise`, you won't need `zip` nor the slicing:

```py
from itertools import pairwise

names = ["Harry", "Anne", "George"]

for left, right in pairwise(names):
    print(f"{left} says hi to {right}")

"""Output:
Harry says hi to Anne
Anne says hi to George
"""
```


## Reshaping iterators

The reshaping iterators in this section produce output in a different format than that of the input.
You can find a simple example for each one of them below.

| Signature | Docs | Brief explanation |
| :- | :- | :- |
| `batched(iterable, n)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.batched) | Produces tuples of length `n` from the given iterable, until exhausted. The last tuple might have less than `n` elements. |
| `chain(*iterables)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.chain) | Produces a single iterable out of multiple iterables. |
| *`chain.from_iterable(iterable)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.chain.from_iterable) | Flattens an iterable of iterables. |
| `islice(iterable, stop)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.islice) | Slices the first `stop` elements from the given iterable. Similar to `lst[:stop]`. |
| *`islice(iterable, start, stop[, step])` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.islice) | Slices the first `stop` elements from the given iterable, dropping the first `start` and returning only one in every `step` elements. Similar to `lst[start:stop:step]`. |
| `groupby(iterable, key=None)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.groupby) | Creates sub-iterators of consecutive values from `iterable` for which the function `key` returns the same value. |
| `pairwise(iterable)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.pairwise) | Produces overlapping pairs of consecutive elements of `iterable`. Similar to `zip(lst[:-1], lst[1:])`. |

### `batched`

```py
# Read a file 5 lines at a time.

from itertools import batched

with open(some_path, "r") as f:
    for lines in batched(f, 5):
        print(lines)  # Process the lines.
```

### `chain`

```py
# Traverse 2+ generators in order (we can't concatenate them).

from itertools import chain

first_gen = (x ** 2 for x in range(3))
second_gen = (x ** 3 for x in range(3))
for value in chain(first_gen, second_gen):
    print(value, end=" ")  # 0 1 4 0 1 8
```

### `islice`

```py
# Slice generators.

from itertools import islice

squares = (x ** 2 for x in range(999_999_999))
for square in islice(squares, 10):
    print(square, end=" ")  # 0 1 4 9 16 25 36 49 64 81

squares = (x ** 2 for x in range(999_999_999))  # Reset
for square in islice(squares, 5, 15, 3):
    print(square, end=" ")  # 25 64 121 196
```

### `groupby`

```py
# Compute longest winning streak.

from itertools import groupby

game_results = "WWWLLWWWWLWWWWWWL"

longest_streak = 0
for key, streak in groupby(game_results):
    if key == "W":
        longest_streak = max(longest_streak, len(list(streak)))
print(longest_streak)  # 6
```

### `pairwise`

```py
from itertools import pairwise

names = ["Harry", "Anne", "George"]

for left, right in pairwise(names):
    print(f"{left} says hi to {right}")

"""Output:
Harry says hi to Anne
Anne says hi to George
"""
```


## Filtering iterators

The filtering iterators accept an iterable and a predicate and will produce a subset of the elements of the original iterable.
You can find a simple example for each one of them below.

| Signature | Docs | Brief explanation |
| :- | :- | :- |
| `compress(data, selectors)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.compress) | Produces the values from the `data` for which the corresponding selector is Truthy. |
| `dropwhile(predicate, iterable)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.dropwhile) | Drops the first consecutive run of elements in the given iterable that satisfy the given predicate. |
| `filterfalse(predicate, iterable)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.filterfalse) | Complement of the built-in `filter`. Produces the values of the given iterable that do not satisfy the given predicate. |
| `takewhile(predicate, iterable)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.filterfalse) | Complement of `dropwhile`. Produces the first run of consecutive values of the given iterable that satisfy the given predicate. |

### `compress`

`compress` is typically useful when you already have the selectors computed, for example because they came from a different data source.
If you have to compute them specifically for `compress`, you're usually better off with the built-in `filter`.

```py
# Find possible voters.

from itertools import compress

people = ["Harry", "Anne", "George"]
can_vote = [True, True, False]

for name in compress(people, can_vote):
    print(name, end=" ")  # Harry Anne
```

### `dropwhile`

```py
from itertools import dropwhile

# Top chess grandmasters and ratings (July 2024)
grandmasters = [
    ("Magnus Carlsen", 2832),
    ("Hikaru Nakamura", 2802),
    ("Fabiano Caruana", 2796),
    ("Arjun Erigaisi", 2778),
    ("Ian Nepomniachtchi", 2770),
]

# Drop grandmasters with rating above 2800:
for gm in dropwhile(lambda gm: gm[1] > 2800, grandmasters):
    print(gm[0], end=", ")  # Fabiano Caruana, Arjun Erigaisi, Ian Nepomniachtchi,
```

### `filterfalse`

```py
# Find people who are too young to vote.

from itertools import filterfalse

people = [
    ("Harry", 17),
    ("Anne", 21),
    ("George", 5),
]

def can_vote(person):
    return person[1] >= 18

for name, _ in filterfalse(can_vote, people):
    print(name, end=", ")  # Harry, George,
```

### `takewhile`

```py
from itertools import takewhile

# Top chess grandmasters and ratings (July 2024)
grandmasters = [
    ("Magnus Carlsen", 2832),
    ("Hikaru Nakamura", 2802),
    ("Fabiano Caruana", 2796),
    ("Arjun Erigaisi", 2778),
    ("Ian Nepomniachtchi", 2770),
]

# Take grandmasters with rating above 2800:
for gm in takewhile(lambda gm: gm[1] > 2800, grandmasters):
    print(gm[0], end=", ")  # Magnus Carlsen, Hikaru Nakamura,
```


## Combinatorial iterators

The combinatorial iterators in this section combine the elements of one or more iterables in different ways and these iterators typically have a mathematical connotation.
You can find a simple example for each one of them after the table.

Despite being a combinatorial iterator, `product` is probably the most universally useful iterator of the whole module!
If you haven't yet, take a look at [how you can use the iterator `product` to flatten nested loops.](#product-flattens-nested-loops).

| Signature | Docs | Brief explanation |
| :- | :- | :- |
| `combinations(iterable, r)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.combinations) | Produce tuples of length `r` of the elements of the given iterable where the elements are sorted with respect to their original positions. |
| `combinations_with_replacement(iterable, r)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.combinations_with_replacement) | Same thing as `combinations`, but each value can be repeated arbitrarily many times. |
| `permutations(iterable, r=None)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.combinations) | Produces all permutations of `r` elements of the given iterable. |
| `product(*iterables, repeat=1)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.product) | Produces tuples combining all elements from all the given iterables. The iterable(s) can be repeated any number of times. |

The combinatorial iterators use the position of the elements as a key when uniqueness needs to be taken into account.
In other words, the actual values themselves are never compared between themselves.


### `combinations`

```py
# Possible flavours for 2-scoop ice creams (no repetition)

from itertools import combinations

flavours = ["chocolate", "vanilla", "strawberry"]
for scoops in combinations(flavours, 2):
    print(scoops)

"""Output:
('chocolate', 'vanilla')
('chocolate', 'strawberry')
('vanilla', 'strawberry')
"""
```

### `combinations_with_replacement`

```py
# Possible flavours for 2-scoop ice creams (repetition allowed)

from itertools import combinations_with_replacement

flavours = ["chocolate", "vanilla", "strawberry"]
for scoops in combinations_with_replacement(flavours, 2):
    print(scoops)

"""Output:
('chocolate', 'chocolate')
('chocolate', 'vanilla')
('chocolate', 'strawberry')
('vanilla', 'vanilla')
('vanilla', 'strawberry')
('strawberry', 'strawberry')
"""
```

### `permutations`

```py
# Order in which the 2 scoops can be served (no repetition)

from itertools import permutations

flavours = ["chocolate", "vanilla", "strawberry"]
for scoops in permutations(flavours, 2):
    print(scoops)

"""Output:
('chocolate', 'vanilla')
('chocolate', 'strawberry')
('vanilla', 'chocolate')
('vanilla', 'strawberry')
('strawberry', 'chocolate')
('strawberry', 'vanilla')
"""
```

### `product`

```py
# All the different ice-cream orders I could make

from itertools import product

possible_scoops = [2, 3]
possibly_served_on = ["cup", "cone"]
for scoop_n, served_on in product(possible_scoops, possibly_served_on):
    print(f"{scoop_n} scoops served on a {served_on}.")

"""Output:
2 scoops served on a cup.
2 scoops served on a cone.
3 scoops served on a cup.
3 scoops served on a cone.
"""
```


## Infinite iterators

The infinite iterators in this section produce potentially infinite iterators.
These are typically used in conjunction with other iterators, for example with `zip`.
You can find a simple example for each one of them after the table.

| Signature | Docs | Brief explanation |
| :- | :- | :- |
| `count(start=0, step=1)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.count) | Same as the built-in `range`, but without a stopping point. |
| `cycle(iterable)` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.cycle) | Iterates endlessly over the items in the given iterable. |
| `repeat(object[, times])` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.repeat) | Creates an iterator that repeats the object given endlessly, or the specified number of times. |

### `count`

```py
# Unique ID generator.

from itertools import count

ID_GENERATOR = count()

class Sandwich:
    def __init__(self):
        self.sandwich_id = next(ID_GENERATOR)

print(Sandwich().sandwich_id)  # 0
print(Sandwich().sandwich_id)  # 1
```

### `cycle`

```py
# Create a layered sandwich.

from itertools import cycle

ingredients = cycle(["tomato", "cheese", "chicken"])
layers = 5

print("<bread", end=" ")
for _, ingredient in zip(range(layers), ingredients):
    print(ingredient, end=" ")
print("bread>")
# <bread tomato cheese chicken tomato cheese bread>
```

### `repeat`

```py
# Repeatedly produce the same object.

from itertools import repeat

bread_dispenser = repeat("bread")
people = ["Harry", "Anne", "George"]
for person, bread in zip(people, bread_dispenser):
    print(f"{person}, here's some {bread}, make yourself a sandwich.")

"""Output:
Harry, here's some bread, make yourself a sandwich.
Anne, here's some bread, make yourself a sandwich.
George, here's some bread, make yourself a sandwich.
"""
```


## Iterators that complement other tools

The iterators listed here complement other iterators from the language (for example, just how `filter` and `filterfalse` complement each other).
You can find a simple example for each one of them after the table.

| Signature | Complements | Docs | Brief explanation |
| :- | :- | :- | :- |
| `accumulate(iterable[, function, *, initial=None])` | `functools.reduce` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.accumulate) | Just like `functools.reduce`, but accumulates the intermediate values. |
| `starmap(function, iterable)` | `map` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.starmap) | Like `map(lambda args: function(args), iterable)`. |
| `zip_longest(*iterables, fillvalue=None)` | `zip` | [Docs](https://docs.python.org/3/library/itertools.html#itertools.zip_longest) | Like `zip`, but stops on the longest iterable instead of the shortest one, filling empty positions with the value specified. |


### `accumulate`

The iterator `accumulate` works in a similar way to [`functools.reduce`](/blog/pydonts/the-power-of-reduce).
While `reduce` only produces the final value of the reduction, the iterator `accumulate` provides the intermediate values as well.

```py
# Partial products to see investment growth over time.

from functools import reduce
from itertools import accumulate
from operator import mul

interest_rates = [1.005, 1.005, 1.008, 1.01, 1.01, 1.02]
initial_investment = 1000

# Same as `math.prod`:
print(reduce(mul, interest_rates, initial_investment))  # ~1059.34
print(list(
    accumulate(
        interest_rates,
        mul,
        initial=initial_investment,
    )
))  # ~ [1000, 1005, 1010.02, 1018.11, 1028.29, 1038.57, 1059.34]
```

### `starmap`

```py
# Useful when arguments are packed but function expects different arguments.

from itertools import starmap

to_compute = [
    (2, 3),  # 8
    (2, 4),  # 16
    (2, 5),  # 32
    (3, 2),  # 9
    (3, 3),  # 27
]

print(list(
    starmap(pow, to_compute)  # [8, 16, 32, 9, 27]
))

# Compare to:
bases = [2, 2, 2, 3, 3]
exponents = [3, 4, 5, 2, 3]
print(list(
    map(pow, bases, exponents)  # [8, 16, 32, 9, 27]
))
```

### `zip_longest`

```py
# Go over multiple iterables until all are exhausted.

from itertools import repeat, zip_longest

# Available ingredients:
bread = repeat("bread", 4)
mayo = repeat("mayo", 2)
chicken = repeat("chicken", 4)

for ingredients in zip_longest(bread, mayo, chicken, fillvalue=""):
    print(f"Here's a sandwich with {' '.join(ingredients)}.")

"""Output:
Here's a sandwich with bread mayo chicken.
Here's a sandwich with bread mayo chicken.
Here's a sandwich with bread  chicken.
Here's a sandwich with bread  chicken.
"""
```

## The function `tee`

The function `tee` is brilliant because it seems to implement something that goes agains the very own definition of iterators.
An iterator provides a stream of data that can only be consumed once, but `tee(iterable, n=2)` can be used to produce as many _independent_ iterators over a single source of data as you may want.

In [“The little book of `itertools`”](/books/the-little-book-of-itertools) we explore `tee` in depth and we reimplement it.
For this overview of the module `itertools`, it suffices to tell you that it is unlikely that you will need it.

Before `pairwise` was introduced in Python 3.10, `tee` would provide a good way to implement it:

```py
from itertools import tee

def pairwise(iterable):
    first, second = tee(iterable, 2)
    next(second)
    yield from zip(first, second)
```

## `itertools` is an excellent module to study

The module `itertools` is an excellent module to study, not only because it provides many useful tools that you can use in your code, but also because reimplementing `itertools` in Python is an excellent exercise for when you are learning how to work with generators and iterators.

I invite you to take a look at [“The little book of `itertools`”](/books/the-little-book-of-itertools), a very short book where I walk you through reimplementing `itertools` effectively in Python, with explanations of all of the things you need to know, with proposed solutions to compare your work against, and with automated tests to help you ensure you're on the right track.

[>> Take a look at “The little book of `itertools`” here.](/books/the-little-book-of-itertools)
