Today I learned about the generator method `close`.

===

# The generator method `close`

Python generators have a method called `close` that “closes” the generator.
Closing the generator means that if you call `next` on it again, it will raise `StopIteration`, which is essentially the same as if the generator had been exhausted completely.

Here is a trivial example that shows this method in use:

```py
>>> squares = (num ** 2 for num in range(10))
>>> for square in squares:
...     print(square)
...     if square > 20:
...         squares.close()
...
0
1
4
16
25
```

I learned this from [a poster session I attended at EuroPython 2023](https://ep2023.europython.eu/session/what-are-you-yield-from) by Maxim Danilov.


# An infinite loop `for` that is cancellable

In his poster session, Maxim proposed a really interesting pattern with an infinite loop `for` that could easily get “cancelled” by calling the method `close` on the infinite generator.
Maxim said there were examples in the standard library where this example could be interesting, but sadly I don't remember exactly where he said we could do it.

Essentially, the pattern uses the infinite iterator `iter(int, 1)` to bootstrap a generator and then it uses the method `close`:

```py
import random

infinity = (_ for _ in iter(int, 1))
for _ in infinity:
    print("v")
    if random.random() < 0.05:
        infinity.close()
        print("Loop `for` has been cancelled.")
```

If you run this piece of code, you will get output like this:

```txt
v
v
v
v
v
v
v
Loop `for` has been cancelled.
```

If I understood correctly, Maxim claimed that there was a common pattern with some infinite loops implemented in terms of `while True:`.
In certain situations, those loops can get messy because of the logic used inside the loop to determine when to use the keyword `break`!

This is relevant because calling `close` on the generator that we are iterating over will _prevent the next iteration_ but it will finish running the code of the current iteration.
On the other hand, the keyword `break` will exit the loop altogether as soon as it is encountered, skipping the remainder of the code in the loop.

This was a very specific use case for `close`, but `close` can be used with any generator; it doesn't have to be an infinite one.


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
