Today I learned about a Python Easter Egg hidden in the hash of two special float values.

===


## Hash of infinity

Today I learned that the values `float("inf")` and `float("-inf")` have two very special hashes:

```pycon
>>> hash(float("inf"))
314159
>>> hash(float("-inf"))
-314159
```

In case you can't tell, those are the first few digits of the mathematical constant $\pi$:

```pycon
>>> import math
>>> math.pi
3.141592653589793
## ^^^^^
```

I learned about this during the [CPython panel](https://ep2025.europython.eu/session/cpython-core-development-panel) held at EuroPython 2025, after the hosts Łukasz & Pablo asked the panel if they know what [the hash of `-1` was](/blog/til/hash-of-1-is-2).
