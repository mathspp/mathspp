Today I learned that the hash of an integer is the integer itself, except for `-1`. The hash of `-1` is `-2`.

===


## Hash of `-1` is `-2`

The hash of a value is a numeric “signature” assigned to that value used in data structures like dictionaries and sets.
Among other interpretations, a hash is supposed to be a quick way to check if two objects are different.
So, if two objects have different hashes, they _are_ different.
But if two objects have the same hash, they can still be different.
When this happens, we have what is called a “hash collision”.

A “hash function” – a function that is used to compute hashes – is good when hash collisions are not common.
Hash collisions are impossible to avoid, but you want collisions to be as rare as possible, in some way.

The Python built-in `hash` computes hashes for Python objects and it's used under the hood with dictionaries, sets, and more.
When applied to integers, it looks like the built-in `hash` returns the integer itself:

```py
assert 0 == hash(0)
assert 1 == hash(1)
assert 46245234 == hash(46245234)
assert -83572 == hash(-83572)
```

Interestingly enough, the hash of `-1` is `-2`, and so is the hash of `-2`:

```py
print(hash(-1))  # -2
print(hash(-2))  # -2
```

This shows a very interesting hash collision that is “easy” to come by!
For example, the integers `6909455589863252355` and `2297769571435864453` also share the same hash, for example, but this seems less surprising than the fact that `-1` and `-2` have the same hash.

I am also trying to find the first positive integer for which the hash does not match the integer itself, but so far Python is still looking for the answer:

```py
from itertools import count

print(
    next(n for n in count() if n != hash(n))  # Python is still running this on my computer.
)
```
