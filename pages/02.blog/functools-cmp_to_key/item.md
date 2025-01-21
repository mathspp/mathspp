In this article I explore `functools.cmp_to_key` and propose a possible implementation.

===


# `functools.cmp_to_key`

The [module `functools` provides a function `cmp_to_key`](https://docs.python.org/3/library/functools.html#functools.cmp_to_key) whose documentation says

 > “Transform an old-style comparison function to a key function. Used with tools that accept key functions (such as `sorted()`, `min()`, `max()`, [...]) [...]
 >
 > A comparison function is any callable that accepts two arguments, compares them, and returns a negative number for less-than, zero for equality, or a positive number for greater-than. A key function is a callable that accepts one argument and returns another value to be used as the sort key.”

I never used this function but it got me thinking...
A key function is a function that accepts a value and produces another single value used for comparisons, whereas an “old-style comparison function” accepts two values and produces a single value as the result of a comparison!
How come `cmp_to_key` converts the later into the former?

## An example comparison function

An example comparison key can be given by the function `cmp` below:

```py
def cmp(a, b):
    return a - b
```

You can see that `cmp` returns a negative number if `a < b`, it returns zero if `a == b`, and it returns a positive number if `a > b`:

```py
print(cmp(1, 3))  # < 0
print(cmp(42, 42))  # == 0
print(cmp(73, -5))  # > 0
```


## The example scenario

To study `cmp_to_key` I will consider the task of sorting strings in a list by length.
With the built-in `sorted` and the built-in `len` this is a single function call:

```py
words = ["platypus", "dog", "horse"]
print(sorted(words, key=len))  # ['dog', 'horse', 'platypus']
```

As an “old-style” comparison function, I could implement the following function `cmp_len`:

```py
def cmp_len(str1, str2):
    return len(str1) - len(str2)
```

Let me verify that this works by using `functools.cmp_to_key`:

```py
from functools import cmp_to_key

words = ["platypus", "dog", "horse"]
print(sorted(words, key=cmp_to_key(cmp_len)))  # ['dog', 'horse', 'platypus']
```

Alright, so `cmp_len` was properly defined.
Now, how do I implement `cmp_to_key` myself?


## Using a binary tree to encode comparisons

The reason I was puzzled by `cmp_to_key` is that I typically imagine the key function as a function that accepts whatever values I am working with – in this case, strings – and produces an integer result that is used for comparisons – in this case, string lengths.

However, the key function does not have to return integers.
It can return anything that Python knows how to compare.
For example, the key function can return tuples!
And that's what I went with.

To implement `cmp_to_key` I will write a class that keeps track of a binary tree.
Every time I try to compute the key value of a given string, I will use the comparison function to figure out where in the tree the value would fall into, and then I will return a tuple that encodes this result.

For example, in the beginning the tree is empty.
When Python tries to compute the key value for `"platypus"`, it goes down the tree and it puts it in the root:

```
      +----------+
      | platypus |
      +----------+
```

Then, it returns the tuple `(0,)`.

Next, Python tries to compute the key value for `"dog"`.
First, it compares `"dog"` with `"platypus"` and it gets a `-1` back, so `"dog"` must be to the left of `"platypus"`:

```
      +----------+
      | platypus |
      +----------+
    /
+-----+
| dog |
+-----+
```

Since there was no more tree to compare with, the result is simply `(-1, 0)`.

Finally, Python tries to compute the key value for `"horse"`.
First, it compares `"horse"` with `"platypus"` and it gets a `-1` back, so `"horse"` must be to the left of `"platypus"`.
Then, it compares `"horse"` with `"dog"` and it gets a `1` back, so `"horse"` must be to the right of `"dog"`:

```
      +----------+
      | platypus |
      +----------+
    /
+-----+
| dog |
+-----+
    \
     +-------+
     | horse |
     +-------+
```

The result is then `(-1, 1, 0)`.

When Python uses the tuples `(0)`, `(-1, 0)`, and `(-1, 1, 0)`, as the key values, it will be able to sort the strings correctly.

Alright, let me implement this!

## Concrete implementation of `cmp_to_key`


```py
from __future__ import annotations

from dataclasses import dataclass


@dataclass
class Node:
    value: object
    left: Node | None = None
    right: Node | None = None


class cmp_to_key:
    def __init__(self, cmp):
        self.cmp = cmp
        self.root = None

    def __call__(self, value):
        if self.root is None:
            self.root = Node(value)

        node_at = self.root
        results = []
        while not results or results[-1] != 0:
            c = self.cmp(value, node_at.value)
            results.append(c)
            if c < 0:
                if node_at.left is None:
                    node_at.left = Node(value)
                node_at = node_at.left
            elif c > 0:
                if node_at.right is None:
                    node_at.right = Node(value)
                node_at = node_at.right

        return tuple(results)


def cmp_len(str1, str2):
    return len(str1) - len(str2)

words = ["platypus", "dog", "horse"]
print(sorted(words, key=cmp_to_key(cmp_len)))  # ['dog', 'horse', 'platypus']

more_words = ["platypus", "dog", "horse", "cat", "giraffe", "elephant", "chimpanze", "centipede", "tarantula"]
assert sorted(more_words, key=len) == sorted(more_words, key=cmp_to_key(cmp_len))
```

I was quite happy when this worked, but then I thought about it a bit more...
And if I'm building this binary tree, I am actually sorting the values myself, so this felt a bit redundant!
Is this how `functools.cmp_to_key` is implemented in the standard library?


## The implementation of `cmp_to_key` in the standard library

It is not!
If you [look at the code for `functools.py` in Python 3.13](https://github.com/python/cpython/blob/383af395af828f40d9543ee0a8fdc5cc011d43db/Lib/functools.py#L207-L224), this is what `cmp_to_key` looks like:

```py
def cmp_to_key(mycmp):
    """Convert a cmp= function into a key= function"""
    class K(object):
        __slots__ = ['obj']
        def __init__(self, obj):
            self.obj = obj
        def __lt__(self, other):
            return mycmp(self.obj, other.obj) < 0
        def __gt__(self, other):
            return mycmp(self.obj, other.obj) > 0
        def __eq__(self, other):
            return mycmp(self.obj, other.obj) == 0
        def __le__(self, other):
            return mycmp(self.obj, other.obj) <= 0
        def __ge__(self, other):
            return mycmp(self.obj, other.obj) >= 0
        __hash__ = None
    return K
```

What?!
If you are thinking “How does this even work?!”, then your thoughts match mine exactly.

But think about it.
Just a couple of paragraphs above, I wrote the following:

 > “However, the key function does not have to return integers.
 > It can return anything that Python knows how to compare.
 > For example, the key function can return tuples!
 > And that's what I went with.”
 >
 > — Rodrigo, a couple of paragraphs above

That's what the class `K` does!
The class `K` is “something that Python knows how to compare”.
How does it know how to compare things?
Because it implements the dunder methods for comparison based on the comparison function!
Isn't this absolutely brilliant?

In a way, it's as if the key function lifted comparisons from the list you are working with to the domain of the key values, while the implementation of `K` above drops those comparisons right back to the domain of the values in the list.


## Conclusion

In conclusion, we've seen how the function `cmp_to_key` works and how comparison functions and key functions are equivalent.
For me, this was yet another instance of how looking at the source code for the Python Standard Library taught me a lot.
If you've never done it before, I can highly recommend you do that.
