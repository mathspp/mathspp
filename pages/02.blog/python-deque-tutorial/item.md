This tutorial teaches how to work with the Python data structure `collections.deque` and provides 7 example use cases.

===

# Python `deque` tutorial

If you already know how to manipulate a `deque`, feel free to [skip to the example use cases](#deque-examples)!

## What is a `deque`?

A `deque` in Python is a data structure from the [module `collections`](/blog/module-collections-overview).
A `deque` is often compared to a Python list because they are both ordered containers that let you append and pop elements from the right efficiently.

The code below shows the similarities:

```py
from collections import deque

my_list = []
my_deque = deque()  # Create a `deque`.

my_list.append(1)
my_deque.append(1)  # Append an element to its end.

my_list.extend(range(2, 5))
my_deque.extend(range(2, 5))  # Extend the `deque` with more elements.

popped_from_list = my_list.pop()
popped_from_deque = my_deque.pop()  # Pop an element from the end.

print(my_list, popped_from_list)  # [1, 2, 3] 4
print(my_deque, popped_from_deque)  # deque([1, 2, 3]) 4
```

There are two main differences between `deque`s and lists:

 1. you can append and pop elements efficiently from the left on a `deque` (on a list, it becomes slower as the list grows); and
 2. you can control the maximum size of a `deque` with its parameter `maxlen`.

These two differences are the ones that play a key role when determining whether you should use a `deque`, a list, or any other container.


## How to create a `deque`

As I've shown above, an empty `deque` can be created by simply typing `deque()`:

```py
from collections import deque

my_deque = deque()
print(my_deque)  # deque([])
```

A `deque` can also be seeded with elements from any other iterable:

```py
from collections import deque

deque_with_chars = deque("hello!")
print(deque_with_chars)  # deque(['h', 'e', 'l', 'l', 'o', '!'])

deque_with_ints = deque(range(5))
print(deque_with_ints)  # deque([0, 1, 2, 3, 4])

deque_with_things = deque([True, None, {}, set()])
print(deque_with_things)  # deque([True, None, {}, set()])
```

When you instantiate a `deque`, you can also specify the parameter `maxlen`.
The `deque` parameter `maxlen` will restrict the maximum length that your `deque` can have.
For example, the `deque` below has a maximum length of `2`, which means that adding more than two elements will result in other elements being pushed off of the `deque`:

```py
from collections import deque

deque_size_2 = deque([1, 2], maxlen=2)
print(deque_size_2)  # deque([1, 2])

deque_size_2.append(3)  # This forces an element to pop from the left.
# Now, we'll see that the `1` is gone:
print(deque_size_2)  # deque([2, 3])
```

The parameter `maxlen` is what allows you to use Python's `deque` for a number of [interesting examples](#deque-examples) that I'll show below.


## How to modify a `deque`

The [`deque` documentation][deque-documentation] has a comprehensive list of all the methods that `deque`s support but I'll show you the most common ones here.


### Adding elements to a `deque`

To add elements to a `deque`, you'll typically do it in one of three ways:

 - you'll initialise your `deque` with some initial elements;
 - you'll append elements, one at a time, to either end of the `deque`; or
 - you'll extend the `deque` with another iterable.

I've already shown you [how to initialise your `deque` with some elements](#how-to-create-a-deque).
To append elements, you can use the method `append` to add elements on the right and you can use the method `appendleft` to add elements on the left:

```py
from collections import deque

my_deque = deque()

my_deque.append(1)
my_deque.append(2)
print(my_deque)  # deque([1, 2])

my_deque.appendleft(0)
my_deque.appendleft(-1)
print(my_deque)  # deque([-1, 0, 1, 2])
```

If you have another iterable `it` and you want to add all of the elements inside `it` to a `deque`, you can use the methods `extend` and `extendleft`:

```py
from collections import deque

my_deque = deque()
# Do this:
my_deque.extend(range(5))
print(my_deque)  # deque([0, 1, 2, 3, 4])

another_deque = deque()
another_deque.extendleft(range(5))
print(another_deque)  # deque([4, 3, 2, 1, 0])
```

Note that when you use the method `extendleft`, the elements in the `deque` will be in reversed order when compared to the original iterable.

You can also insert an element in the middle of a `deque` with the method `insert`, but that is an inefficient operation on a `deque` (just like on a list).


### Removing elements from a `deque`

To remove elements from a `deque`, you'll probably want to use the methods

 - `pop` – to pop an element from the right;
 - `popleft` – to pop an element... from the left; and
 - `clear` – to clear all of the elements in the `deque`.

Here is an example showing all three methods:

```py
from collections import deque

my_deque = deque(range(10))
print(my_deque)  # deque([0, 1, 2, 3, 4, 5, 6, 7, 8, 9])

my_deque.pop()  # Pops the 9.
my_deque.pop()  # Pops the 8.
print(my_deque)  # deque([0, 1, 2, 3, 4, 5, 6, 7])

my_deque.popleft()  # Pops the 0.
my_deque.popleft()  # Pops the 1.
print(my_deque)  # deque([2, 3, 4, 5, 6, 7])

my_deque.clear()  # Clear everything.
print(my_deque)  # deque([])
```


### Other `deque` methods

A `deque` provides some other useful methods (again, [check the docs][deque-documentation]!).
The list below gives you a quick overview of these methods:

 - `copy` – creates a shallow copy of the `deque`;
 - `count` – counts how many times an element appears;
 - `index` – finds the first position where a given value occurs;
 - `remove` – removes the first occurrence of a value;
 - `reverse` – reverses the `deque` in-place; and
 - `rotate` – rotates the elements inside the `deque`.

The method `rotate` is the only method that lists don't support, so I'll show you a brief example:

```py
from collections import deque

my_deque = deque(range(5))
print(my_deque)  # deque([0, 1, 2, 3, 4])

my_deque.rotate()  # Same as `.rotate(1)`.
print(my_deque)  # deque([4, 0, 1, 2, 3])

my_deque.rotate(2)
print(my_deque)  # deque([2, 3, 4, 0, 1])

my_deque.rotate(-3)  # Undo the previous two rotations.
print(my_deque)  # deque([0, 1, 2, 3, 4])
```

When the argument is positive, `rotate(n)` means “push the elements of the `deque` a total of `n` positions forward”, which has the consequence of bringing the last `n` items of the `deque` to the beginning.
A negative argument does the opposite.


## `deque` examples

The characteristics of a `deque` let you use it for a variety of interesting things.
These will typically make use of the fact that a `deque` can be easily modified on both ends and/or that a `deque` lets you cap its size with the parameter `maxlen`.

In this section we'll go through some examples of use cases for the `deque` data structure that highlight its capabilities:

 - how to [get the last 5 lines of a file](#get-last-5-lines-of-a-file);
 - how to [get the last item that satisfies a predicate](#get-last-item-that-satisfies-a-predicate);
 - how to [fully consume an iterator](#fully-consume-an-iterator);
 - how to [compute a moving average](#compute-moving-average);
 - how to [implement `itertools.pairwise`](#implement-itertoolspairwise);
 - how to [save a sized action history](#save-undoredo-history); and
 - how to [buffer intermediate results inside an iterator-like object](#buffer-for-iterator-like-intermediate-results).

These are just _some_ examples.
If you know of a good use case of `deque` feel free to comment below and I might add it here.


### Get last 5 lines of a file

Given the fact that `deque`s have the parameter `maxlen`, they are particularly suitable for when you want to access the last elements of iterables that you can't slice.
When you want the last 5 items of a list or the last 5 characters of a string, you'd use [a negative slice](/blog/pydonts/idiomatic-sequence-slicing#s-n-1):

```py
my_list = [10, 20, 30, 40, 50, 60, True, False, None, 1, 2]
print(my_list[-5:])  # [True, False, None, 1, 2]

my_string = "mathspp.com"
print(my_string[-5:])  # p.com
```

However, there are iterables that do not support slicing.
Files, for example, cannot be sliced!
So, if you want the last five lines of a file, your best bet is using a `deque`:

```py
from collections import deque

with open(filepath, "r") as file:
    last_5_lines = deque(file, maxlen=5)

# last_5_lines contains the last 5 lines of the file.
print(last_5_lines)
```

!!! This idiom traverses the whole file.
!!! If efficiency is **critical**, consider taking a look at the module [`mmap`](https://docs.python.org/3/library/mmap.html) from the standard library.

The pattern of using a `deque` to get the last few items of an iterable can take many useful forms.
The next section shows a “completely different use case” for a `deque` with a fixed maximum size that ends up being exactly the same thing, except in a different context.


### Get last item that satisfies a predicate

If you set a `deque` to have a maximum size of `1`, you can use it to fetch the last element of any iterable:

```py
from collections import deque

my_string = "Python"
print(deque(my_string, maxlen=1))  # deque(['n'])

my_numbers = range(100)
print(deque(my_numbers, maxlen=1))  # deque([99])

my_list = [42, False, dict(), ..., True]
print(deque(my_list, maxlen=1))  # deque([True])
```

If you combine this with a generator expression and a predicate function, you have an idiom that is very space efficient and that finds the last element of an iterable that satisfies the given predicate.

As a small example, the snippet below shows how to find the last vowel in a sentence:

```py
from collections import deque

sentence = "The quick brown fox jumps over the last dog!"
# The last vowel is an 'o' --------------------------^

vowels = set("aeiouAEIOU")

last_vowel = deque((char for char in sentence if char in vowels), maxlen=1)
try:
    print(last_vowel.pop())  # o
except IndexError:
    print("No vowels found.")
```


The fact that you are using a generator expression and a `deque` that can only hold one element at a time means that you are being as space efficient as possible and this space efficiency can be quite handy if you're going over the lines of a huge file in disk, for example.

You can even wrap this functionality in a class that looks like a function:

```py
from collections import deque

_SENTINEL = object()

class last:
    def __init__(self, iterable):
        self.maybe_value = deque(iterable, maxlen=1)

    def item(self, *, default=_SENTINEL):
        try:
            return self.maybe_value.pop()
        except IndexError:
            if default is _SENTINEL:
                raise ValueError("No last element.") from None
            else:
                return default
```


This allows you to use `last` as a function that accepts an iterable and finds the last value of that iterable.
Then, you use the method `item` to materialise the item.
If the iterable was empty, then `item` will either raise an `ValueError` or return the default value you provide as a keyword argument.

Here are some example applications:

```py
print(last("Rodrigo").item())  # o
print(last(range(10)).item())  # 9

print(last([]).item(default=42))  # 42
print(last([]).item())  # ValueError
```



### Fully consume an iterator

Another clever usage of the parameter `maxlen` of a `deque` lets you create an idiom to exhaust any iterator.
If `it` is an iterator, then `deque(it, maxlen=0)` will exhaust the iterator fully.
This is example is more exotic than the other two but there are cases where you want to reach the end of an iterator without necessarily wanting to do anything with the items you find along the way.

To show that `deque(..., maxlen=0)` does exhaust an iterator, the snippet below uses a generator expression whose last item is given by the expression `1 / 0`, which will raise a `ZeroDivisionError` when that item is computed.
Creating the generator expression isn't enough to trigger the error:

```py
gen_expr = (1 / num for num in range(10, -1, -1))
```


You can also advance the generator a couple of steps and it won't trigger the error:

```py
print(next(gen_expr))  # 0.1
print(next(gen_expr))  # 0.1111111111111111
print(next(gen_expr))  # 0.125
```


But it's only when you exhaust the generator with the help of `deque` that you get the error:

```py
from collections import deque

gen_expr = (1 / num for num in range(10, -1, -1))
next(gen_expr)
next(gen_expr)
next(gen_expr)

deque(gen_expr, maxlen=0)  # ZeroDivisionError
```


This idiom can be helpful when you want to make sure a given iterable can be exhausted without erroring out but at the same time you're not interested in the contents of the iterable.

You can also wrap this idiom in a short function:

```py
from collections import deque

def exhaust(iterable):
    deque(iterable, maxlen=0)
```


### Compute moving average

A moving average is an average computed over an iterable where you only consider a subset of all of the values you have.
For example, the average of the list `[0, 1, 2, 3, 4]` is 2.
However, if you compute the moving average over that list with a window of size 3, you get a succession of increasing values:

```txt
[0, 1, 2, 3, 4]
 ^^^^^^^ average of 1
    ^^^^^^^ average of 2
       ^^^^^^^ average of 3
```

Moving averages have many applications in the world of finance, for example.
At the time of writing, the qutebrowser project (a browser written in Python) uses a `deque` to compute a moving average to [estimate how much time a download will take to complete](https://github.com/qutebrowser/qutebrowser/blob/a9f6ad9731c59c8fdcff25959b1e7ae67b0513fc/qutebrowser/browser/downloads.py#L343).

To compute a moving average with a `deque`, we make use of the parameter `maxlen` once more, which we set to be the size of the window in the moving average.
This means that, when the `deque` is full, appending a new element to the `deque` creates the next window whose average we can compute.

The snippet below implements a function `moving_averages` that accepts an iterable and computes all of the moving averages of a given size on that iterable:

```py
from collections import deque

def moving_averages(data, window_size):
    values = []
    window = deque(maxlen=window_size)
    for element in data:
        window.append(element)
        values.append(sum(window) / len(window))
    return values
```


Applying this to the previous example list `[0, 1, 2, 3, 4]` shows that our implementation computes some initial values that didn't include a full window:

```py
averages = moving_averages([0, 1, 2, 3, 4], 3)
print(averages)  # [0.0, 0.5, 1.0, 2.0, 3.0]
```


Depending on the context, these “incomplete” averages may or may not be relevant.
If the first values are not relevant, you can use `itertools.islice` to fill the window before starting to compute averages:

```py
from collections import deque
from itertools import islice

def moving_averages(data, window_size):
    data = iter(data)
    values = []
    window = deque(islice(data, window_size-1), maxlen=window_size)
    for element in data:
        window.append(element)
        values.append(sum(window) / len(window))

    return values

averages = moving_averages([0, 1, 2, 3, 4], 3)
print(averages)  # [1.0, 2.0, 3.0]
```



### Implement `itertools.pairwise`

Speaking of `itertools`, a `deque` is a simple way of implementing `itertools.pairwise`, which is only available in Python 3.10 or later.
`pairwise` accepts an iterable and produces the (overlapping) pairs of consecutive elements:

```py
from itertools import pairwise

my_list = [42, 73, 16, 0, 10]
for a, b in pairwise(my_list):
    print(a, b)

"""
Output:
42 73
73 16
16 0
0 10
"""
```


If you don't have access to `pairwise`, or if you need to implement a more general version of `pairwise`, you can adapt the implement of `moving_averages` above.
Instead of summing the values inside the “window” and then dividing by the length of the “window”, you return a tuple with the elements:

```py
from collections import deque
from itertools import islice

def pairwise_(data):
    data = iter(data)
    window = deque(islice(data, 1), maxlen=2)
    for value in data:
        window.append(value)
        yield tuple(window)
```


This produces the same result if we apply our implementation of `pairwise_` to our previous list:

```py
my_list = [42, 73, 16, 0, 10]
for a, b in pairwise_(my_list):
    print(a, b)

"""
Output:
42 73
73 16
16 0
0 10
"""
```


If we need a more general version of `pairwise`, to produce tuples of length `n`, we can adapt the previous implementation:

```py
from collections import deque
from itertools import islice

def n_tuples(data, n):
    """Produces consecutive overlapping tuples of size `n`."""
    data = iter(data)
    window = deque(islice(data, n - 1), maxlen=n)
    for value in data:
        window.append(value)
        yield tuple(window)
```


This works in the same way:

```py
my_list = [42, 73, 16, 0, 10]
for a, b, c in n_tuples(my_list, 3):
    print(a, b, c)

"""
Output:
42 73 16
73 16 0
16 0 10
"""
```


Notice how this is remarkably similar to the function `moving_averages` from the section on [computing moving averages](#compute-moving-average) with `deque`.


### Save undo/redo history

[Textual](https://github.com/Textualize/textual), a framework that lets you create TUIs in Python (and the framework I work on, at the time of writing), uses a `deque` to create the stack of undo/redo actions that are allowed in its text area.

Most (if not all!) text editors let you undo your recent changes, in case you want to go back to a previous version of what you had written.
For this to be possible, the text editor keeps a stack of your changes.
Each time you press “undo”, the editor pops an item off that stack and undoes that change.

Theoretically, this stack of changes could be infinite and you could undo _all_ of the changes you _ever_ made on a given document.
In practice, the stack size is capped.
That's because you don't want your computer to grind to a halt because your text editor is using up all of the RAM to keep a huge stack of changes you might want to undo later.

In Python, a list is an excellent data structure for a stack because of its methods `.pop` and `.append`.
If you want the size of the stack to be capped, then a `deque` is the natural candidate for that.
Appending on and popping from the right preserves the stack semantics and the parameter `maxlen` makes sure your stack doesn't grow too much.

By using a `deque` with a maximum size specified by `maxlen`, the undo/redo stack can be used without having to worry about its size:

 - when the user makes a change, an “undo action” is appended to the stack; and
 - when the user uses the shortcut to undo a change, we pop the “undo action” from the stack and apply it.

Making sure the stack doesn't go over a certain size is managed automatically by the `deque`.


### Buffer for iterator-like intermediate results

I've also found that a `deque` can be quite helpful when a method is producing intermediate results and it needs to buffer them.
For example, in my [“Building a Python Compiler and Interpreter” blog series](/blog/tag:bpci), I implement a tokenizer for Python source code.
This tokenizer has a method `next_token` that computes and emits the next token:

```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        # ...
```

The tokenizer has a token buffer implemented with a `deque`.
If the buffer has any tokens when `Tokenizer.next_token` is called, we pop a token from the left of the buffer instead of computing the next one:

```py
from collections import deque

class Tokenizer:
    def __init__(self, code: str) -> None:
        # ...
        self.next_tokens: deque[Token] = deque()

    def next_token(self) -> Token:
        if self.next_tokens:
            return self.next_tokens.popleft()

        # ...
```

This is helpful because there are certain moments when the tokenizer is trying to produce only _one_ token but ends up producing many tokens at the same time.
When that is the case, we return only one token and we save the other tokens in the buffer.
You can see this, in context, in the [“Building a Python Compiler and Interpreter” article where we add the `if` statement](/blog/building-a-python-compiler-and-interpreter-07-if#track-changes-in-indentation).


[deque-documentation]: https://docs.python.org/3/library/collections.html#collections.deque
