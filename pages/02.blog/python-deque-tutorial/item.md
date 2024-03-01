This tutorial shows how to work with the Python data structure `collections.deque`, with interactive examples.

===

<link rel="stylesheet" href="https://unpkg.com/@antonz/codapi@0.16.0/dist/snippet.css" />
<script src="https://unpkg.com/@antonz/codapi@0.16.0/dist/snippet.js"></script>

<!--
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>
-->


# Python `deque` tutorial

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

<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>


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
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>

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
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>

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
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>

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
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>

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
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>

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
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>


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
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>

When the argument is positive, `rotate(n)` means “push the elements of the `deque` a total of `n` positions forward”, which has the consequence of bringing the last `n` items of the `deque` to the beginning.
A negative argument does the opposite.


## `deque` examples

The characteristics of a `deque` let you use it for a variety of interesting things.
These will typically make use of the fact that a `deque` can be easily modified on both ends and/or that a `deque` lets you cap its size with the parameter `maxlen`.

In this section we'll go through some examples of use cases for the `deque` data structure.
We'll present some concrete examples that highlight the capabilities of the `deque`.
You are more than welcome to use a `deque` for other things and, if you have a nice use case, feel free to comment below so I can add it here:

 - how to [get the last 5 lines of a file](#get-last-5-lines-of-a-file);
 - how to [get the last item that satisfies a predicate](#get-last-item-that-satisfies-a-predicate);
 - how to [fully consume an iterator](#fully-consume-an-iterator);
 - how to [compute a windowed average](#compute-windowed-average);
 - how to [implement `itertools.pairwise`](#implement-itertoolspairwise); and
 - how to [save a sized action history](#save-action-history).


### Get last 5 lines of a file

Given the fact that `deque`s have the parameter `maxlen`, they are particularly suitable for when you want to access the last elements of iterables that you can't slice.
When you want the last 5 items of a list or the last 5 characters of a string, you'd use [a negative slice](/blog/pydonts/idiomatic-sequence-slicing#s-n-1):

```py
my_list = [10, 20, 30, 40, 50, 60, True, False, None, 1, 2]
print(my_list[-5:])  # [True, False, None, 1, 2]

my_string = "mathspp.com"
print(my_string[-5:])  # p.com
```
<codapi-snippet sandbox="python" editor="basic"></codapi-snippet>

However, there are iterables that do not support slicing because you don't know their size.


### Get last item that satisfies a predicate


### Fully consume an iterator


### Compute windowed average


### Implement `itertools.pairwise`


### Save action history


[deque-documentation]: https://docs.python.org/3/library/collections.html#collections.deque
