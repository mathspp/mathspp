This article shows how to implement the set built-in at the expense of a dictionary.

===


A set is a built-in container data type in Python that provides fast lookup and insertion.
In exchange for that speed, sets are unordered, only hold unique elements, and its elements must be hashable.
These characteristics are similar to those of dictionary keys[^1] and this shows that a set can actually be implemented as a dictionary with no values.
In this article we will explore this relationship between sets and dictionaries by implementing our own set class.

If you are more interested in learning about how to best use sets (and frozensets) in your code, you can [learn how and when to use sets in this article](/blog/pydonts/set-and-frozenset).


## The internal representation of the set values

When I talk about a “dictionary with no values” what I really mean is that the values that we assign to the dictionary keys don't matter.
In our implementation of sets we will always use `None` but you can use whatever you want.
With that said, we can start by implementing `__init__` and `__repr__` so that we can start instantiating our sets and printing them:

```py
class set_:
    """Implementing a set as a dictionary with no values."""

    def __init__(self, iterable=None):
        self.values = {} if iterable is None else dict.fromkeys(iterable)

    def __repr__(self):
        if not self.values:
            return "set_()"
        return "{" + ", ".join(map(repr, self.values.keys())) + "}"


if __name__ == "__main__":
    s = set_(range(10))
    print(s)  # {0, 1, 2, 3, 4, 5, 6, 7, 8, 9}
    s = set_()
    print(s)  # set_()
```

Notice how we're using the class method `dict.fromkeys` to create a dictionary where all keys map to the value `None` inside the dunder method `__init__`:

```pycon
>>> dict.fromkeys(range(3))
{0: None, 1: None, 2: None}
```

In [the dunder method `__repr__`](/blog/pydonts/str-and-repr) we create a special case for when there are no values in the dictionary because we don't want to print `{}` to represent an empty set because `{}` is an empty dictionary!
Next up, we'll implement methods to add and remove elements from the set.


## Set methods

### Constructive / destructive methods

As you can see from [my set cheatsheet](https://gumroad.com/l/cheatsheet_sets), sets provide the following methods to add/remove elements:

 - `add` – adds one element to the set;
 - `update` – adds all elements from an iterable to the set;
 - `pop` – removes and returns an arbitrary value from the set;
 - `remove` – removes the given element from the set and raises a `KeyError` if the element wasn't there to begin with;
 - `discard` – removes the given element from the set and ignores missing elements; and
 - `clear` – empties the set.

These methods all have straightforward implementations:

```py
class set_:
    # ...

    def add(self, element):
        """Add one element to the set; errors if the element isn't hashable."""
        self.values[element] = None

    def update(self, iterable):
        """Updates the set with all the elements from the given iterable."""
        self.values.update(dict.fromkeys(iterable))

    def pop(self):
        """Remove and return an arbitrary element from the set."""
        item, _ = self.values.popitem()
        return item

    def remove(self, element):
        """Remove the given element from the set; raise `KeyError` if it's not there."""
        del self.values[element]

    def discard(self, element):
        """Remove the given element from the set and ignore missing elements."""
        if element in self.values:
            del self.values[element]

    def clear(self):
        """Clear all elements from the set."""
        self.values = {}


if __name__ == "__main__":
    s = set_()
    print(s)  # set_()
    s.update(range(3))
    print(s)  # {0, 1, 2}
    s.add(999)
    print(s)  # {0, 1, 2, 999}
    element = s.pop()
    print(s, element)  # {0, 1, 2} 999
    try:
        s.remove(element)
    except KeyError:
        print(f"{element} was already popped...")  # 999 was already popped...
    s.discard(element)  # Doesn't raise an error.
    s.clear()
    print(s)  # set_()
```

If you rerun this a couple of times, you might notice that it is the `999` that keeps getting popped, which may look suspicious.
The documentation for the original method `set.pop` says “Remove and return an **arbitrary** set element” [emphasis mine].
So, what this means is that we can't rely on `.pop` to return a value that satisfies any specific condition.
In this implementation, it _just so happens_ that we're returning the element that was added last to the set, and that's fine, and it would be equally fine to start choosing the element randomly or returning the element that has been in the set the longest.


### Miscellaneous set methods

The documentation for the container `set` lists three other methods:

 - `copy` – creates a shallow copy of the set;
 - `len(set)` – to compute the number of elements in the set; and
 - `elem in set` – check if `elem` is in the given set.

The `len(set)` and `elem in set` require using [the appropriate dunder methods](/blog/pydonts/dunder-methods), namely `__len__` and `__contains__`:


```py
class set_:
    # ...

    def copy(self):
        """Create a shallow copy of the set."""
        return set_(self.values.keys())

    def __len__(self):
        """Computes how many elements are in the set."""
        return len(self.values.keys())

    def __contains__(self, element):
        """Checks if the given element is in the set."""
        return element in self.values.keys()


if __name__ == "__main__":
    s1 = set_(range(5))
    s2 = s1.copy()
    s1.add(999)
    # s1 should have one more element...
    print(len(s1), len(s2))  # 6 5
    # ... which is the 999:
    print(999 in s1, 999 in s2)  # True False
```


## Sets are iterable

You can iterate over the contents of a set because sets implement the iterator protocol.
This means that we must do the same.
We can make use of the fact that dictionary keys are already iterable to simplify the implementation of this protocol:

```py
class set_:
    # ...

    def __iter__(self):
        """Make sets iterable."""
        return iter(self.values.keys())


if __name__ == "__main__":
    s1 = set_(range(5))
    for value in s1:
        print(value, end=", ")  # 0, 1, 2, 3, 4, 
```

The iterator protocol says that an object is iterable if calling `iter` on it returns an iterator.
In turn, calling `iter` on an object will call its `__iter__` dunder method.
So, `set_.__iter__` must return an iterator and `iter(self.values.keys())` returns the iterator associated with the keys of the dictionary `self.values`, so that's what we do.


## Set operations

Sets also provide a series of set operations that let us reason about the contents of two or more sets and to produce new sets out of two or more sets.
We will consider two types of set operations:

 1. Boolean comparison operations – operations that compare two sets and return either `True` or `False` according to some predicate.
 2. Operations that build new sets – operations that build new sets from two or more sets.


### Boolean comparison operations

The Boolean comparison operations are `==`, `!=`, `<=`, `<`, `>=`, and `>`.
These operations take into account the elements inside the set:

 - `s1 == s2` – checks if the two sets have exactly the same elements;
 - `s1 != s2` – checks if the two sets have different elements;
 - `s1 <= s2` – checks if `s1` is contained in `s2`, that is, that all the elements of `s1` are in `s2`;
 - `s1 < s2` – checks if `s1` is strictly contained in `s2`, that is, that all elements of `s1` are in `s2` but also that `s2` and `s1` aren't the same;
 - `s1 >= s2` – same as `s2 <= s1`; and
 - `s1 > s2` – same as `s2 < s1`.

These operators can be implemented with the dunder methods `__eq__`, `__neq__`, `__le__`, `__lt__`, `__ge__`, and `__gt__`, respectively.
However, you don't need to implement `__neq__` if you implement `__eq__` (or vice-versa) so I'll just skip `__neq__`.
I'll also implement `>=` and `>` in terms of `<=` and `<`, respectively:

```py
class set_:
    # ...

    def __eq__(self, other):
        """Check if two sets have exactly the same elements."""
        if not isinstance(other, set_):
            return NotImplemented
        return all(elem in other for elem in self) and all(
            elem in self for elem in other
        )

    def __le__(self, other):
        """Check if all elements of self are in the other set."""
        if not isinstance(other, set_):
            return NotImplemented
        return all(elem in other for elem in self)

    def __lt__(self, other):
        """Check that self is strictly contained in the other set."""
        if not isinstance(other, set_):
            return NotImplemented
        return self <= other and self != other

    def __ge__(self, other):
        """Check if all elements of the other set are in self."""
        return other <= self

    def __gt__(self, other):
        """Check that the other set is strictly contained in this set."""
        return other < self


if __name__ == "__main__":
    s1 = set_(range(3))
    s2 = set_(range(5))
    print(s1 != s2)  # True
    print(s1 < s2)  # True
    print(s1 > s2)  # False
```

On top of implementing the comparison operators, sets also provide three Boolean comparison methods:

 - `s1.issubset(s2)` – similar to `s1 <= s2`, but checks if all the elements of `s1` are in the set given;
 - `s1.issuperset(s2)` – similar to `s1 >= s2`, but checks if all the elements of the given set are in the set `s1`; and
 - `s1.isdisjoint(s2)` – checks if the set and the given set have no elements in common.

Strictly speaking, the three methods above accept arbitrary iterables of hashable elements, so my implementation will also reflect this:

```py
class set_:
    # ...

    def issubset(self, other):
        """Checks if we're a subset of the given iterable (seen as a set)."""
        return self <= set_(other)

    def issuperset(self, other):
        """Checks if self contains the given iterable (seen as a set)."""
        return self >= set_(other)

    def isdisjoint(self, other):
        """Checks if self and the other iterable (as a set) have no elements in common."""
        other = set_(other)
        return all(elem not in other for elem in self) and all(
            elem not in self for elem in other
        )


if __name__ == "__main__":
    s1 = set_(range(5))
    print(s1.issubset(range(10)))  # True
    print(s1.issuperset(range(10)))  # False
    print(s1.isdisjoint(range(10, 15)))  # True
```


### Operations that build new sets

Now we're only missing the set operations that build _new_ sets.
There are four such operations that use binary operators and the respective methods that work with general iterables (that are then interpreted as sets):

 - `|` / `union` – creates a new set with all the elements from either set;
 - `&` / `intersection` – creates a new set with the elements that are in both sets simultaneously;
 - `-` / `difference` – creates a new set with the elements that are in the first set and not in the others; and
 - `^` / `symmetric_difference` – creates a new set with the elements that are in either set but not in both.

The binary operators `|`, `&`, `-`, and `^`, are implemented through the dunder methods `__or__`, `__and__`, `__sub__`, and `__xor__`, respectively.
(You can read more about these typically [arithmetic dunder methods in this article](/blog/pydonts/overloading-arithmetic-operators-with-dunder-methods).)
After implementing the binary operators, we can implement the respective methods by relying on augmented assignment:

```py
class set_:
    # ...

    def __or__(self, other):
        """Joins two sets together. (self | other)"""
        if not isinstance(other, set_):
            return NotImplemented
        result = self.copy()
        result.update(other)
        return result

    def union(self, *others):
        """Joins self with many other sets."""
        result = self.copy()
        for other in others:
            result |= set_(other)
        return result

    def __and__(self, other):
        """Computes elements present in both sets. (self & other)"""
        if not isinstance(other, set_):
            return NotImplemented
        result = set_()
        result.update(elem for elem in self if elem in other)
        result.update(elem for elem in other if elem in self)
        return result

    def intersection(self, *others):
        """Intersects self with many other sets."""
        result = self.copy()
        for other in others:
            result &= set_(other)
        return result

    def __sub__(self, other):
        """Creates a set with the elements of self that are not in other. (self - other)"""
        if not isinstance(other, set_):
            return NotImplemented
        return set_(elem for elem in self if elem not in other)

    def difference(self, *others):
        """Subtracts many other sets from self."""
        result = self.copy()
        for other in others:
            result -= set_(other)
        return result

    def __xor__(self, other):
        """Creates a set with the elements of either set that are not in both. (self ^ other)"""
        if not isinstance(other, set_):
            return NotImplemented
        result = set_()
        result.update(elem for elem in self if elem not in other)
        result.update(elem for elem in other if elem not in self)
        return result

    def symmetric_difference(self, *others):
        """Computes the symmetric difference between this and many other sets."""
        result = self.copy()
        for other in others:
            result ^= set_(other)
        return result


if __name__ == "__main__":
    s1 = set_(range(5))
    s2 = set_(range(3, 8))
    print(s1, s2)  # {0, 1, 2, 3, 4} {3, 4, 5, 6, 7}
    print(s1 | s2)  # {0, 1, 2, 3, 4, 5, 6, 7}
    print(s1 & s2)  # {3, 4}
    print(s1 - s2)  # {0, 1, 2}
    print(s1 ^ s2)  # {0, 1, 2, 5, 6, 7}
    print(s1.union(range(5, 8), range(10, 13), range(100, 104)))
    # {0, 1, 2, 3, 4, 5, 6, 7, 10, 11, 12, 100, 101, 102, 103}
    print(s1)  # {0, 1, 2, 3, 4}
```


## Conclusion and full code

To conclude, we can see that implementing a set from a dictionary is quite straightforward, in the sense that we did not have to resort to any advanced techniques or algorithms.
The “hardest part” was making sure we knew what dunder methods were associated with what operators.

In case you don't know, dictionary key objects also implement most (if not all!) methods and operators that sets do, so the code could be even simpler if we made use of that fact.
For example, `s1 < s2` could just be `s1.values.keys() < s2.values.keys()`.
However, I thought that would be against the spirit of this article, so I didn't do it.

Here's the full implementation of the class `set_`:

```py
class set_:
    """Implementing a set as a dictionary with no values."""

    def __init__(self, iterable=None):
        self.values = {} if iterable is None else dict.fromkeys(iterable)

    def __repr__(self):
        if not self.values:
            return "set_()"
        return "{" + ", ".join(map(repr, self.values.keys())) + "}"

    def add(self, element):
        """Add one element to the set; errors if the element isn't hashable."""
        self.values[element] = None

    def update(self, iterable):
        """Updates the set with all the elements from the given iterable."""
        self.values.update(dict.fromkeys(iterable))

    def pop(self):
        """Remove and return an arbitrary element from the set."""
        item, _ = self.values.popitem()
        return item

    def remove(self, element):
        """Remove the given element from the set; raise `KeyError` if it's not there."""
        del self.values[element]

    def discard(self, element):
        """Remove the given element from the set and ignore missing elements."""
        if element in self.values:
            del self.values[element]

    def clear(self):
        """Clear all elements from the set."""
        self.values = {}

    def copy(self):
        """Create a shallow copy of the set."""
        return set_(self.values.keys())

    def __len__(self):
        """Computes how many elements are in the set."""
        return len(self.values.keys())

    def __contains__(self, element):
        """Checks if the given element is in the set."""
        return element in self.values.keys()

    def __iter__(self):
        """Make sets iterable."""
        return iter(self.values.keys())

    def __eq__(self, other):
        """Check if two sets have exactly the same elements."""
        if not isinstance(other, set_):
            return NotImplemented
        return all(elem in other for elem in self) and all(
            elem in self for elem in other
        )

    def __le__(self, other):
        """Check if all elements of self are in the other set."""
        if not isinstance(other, set_):
            return NotImplemented
        return all(elem in other for elem in self)

    def __lt__(self, other):
        """Check that self is strictly contained in the other set."""
        if not isinstance(other, set_):
            return NotImplemented
        return self <= other and self != other

    def __ge__(self, other):
        """Check if all elements of the other set are in self."""
        return other <= self

    def __gt__(self, other):
        """Check that the other set is strictly contained in this set."""
        return other < self

    def issubset(self, other):
        """Checks if we're a subset of the given iterable (seen as a set)."""
        return self <= set_(other)

    def issuperset(self, other):
        """Checks if self contains the given iterable (seen as a set)."""
        return self >= set_(other)

    def isdisjoint(self, other):
        """Checks if self and the other iterable (as a set) have no elements in common."""
        other = set_(other)
        return all(elem not in other for elem in self) and all(
            elem not in self for elem in other
        )

    def __or__(self, other):
        """Joins two sets together. (self | other)"""
        if not isinstance(other, set_):
            return NotImplemented
        result = self.copy()
        result.update(other)
        return result

    def union(self, *others):
        """Joins self with many other sets."""
        result = self.copy()
        for other in others:
            result |= set_(other)
        return result

    def __and__(self, other):
        """Computes elements present in both sets. (self & other)"""
        if not isinstance(other, set_):
            return NotImplemented
        result = set_()
        result.update(elem for elem in self if elem in other)
        result.update(elem for elem in other if elem in self)
        return result

    def intersection(self, *others):
        """Intersects self with many other sets."""
        result = self.copy()
        for other in others:
            result &= set_(other)
        return result

    def __sub__(self, other):
        """Creates a set with the elements of self that are not in other. (self - other)"""
        if not isinstance(other, set_):
            return NotImplemented
        return set_(elem for elem in self if elem not in other)

    def difference(self, *others):
        """Subtracts many other sets from self."""
        result = self.copy()
        for other in others:
            result -= set_(other)
        return result

    def __xor__(self, other):
        """Creates a set with the elements of either set that are not in both. (self ^ other)"""
        if not isinstance(other, set_):
            return NotImplemented
        result = set_()
        result.update(elem for elem in self if elem not in other)
        result.update(elem for elem in other if elem not in self)
        return result

    def symmetric_difference(self, *others):
        """Computes the symmetric difference between this and many other sets."""
        result = self.copy()
        for other in others:
            result ^= set_(other)
        return result
```


[^1]: As of Python 3.7 or 3.8 (I never know...), dictionary keys became ordered, but for a long time they weren't and they really don't need to be.
