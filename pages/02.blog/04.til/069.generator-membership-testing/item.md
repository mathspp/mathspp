Today I learned that generators support membership testing with the operator `in`.

===

## Generator membership testing

Membership testing in Python boils down to using the operator `in`, as the REPL session excerpt shows:

```pycon
>>> my_list = [42, 73, 16, 0, 10]
>>> 42 in my_list
True
>>> 43 in my_list
False
>>> 10 in my_list
True
```

What I hadn't realised yet is that generators also support membership testing!
It is very easy to verify that they _do_ support membership testing, though:

```py
def generator():
    yield 42
    yield 73
    yield 16
    yield 0
    yield 10

gen = generator()
print(42 in gen)  # True
```


## Gotchas of generator membership testing

What I immediately realised afterwards is that you need to be very careful about membership testing with generators.
For example, consider the generator function from before:

```py
def generator():
    yield 42
    yield 73
    yield 16
    yield 0
    yield 10
```

We can check if the number 999 is in there and we will get the correct answer:

```pycon
>>> gen = generator()
>>> 999 in gen
False
```

But now, think about it.
How can Python tell that 999 is not inside the generator `gen`?
It had to iterate over it, which means it had to go through all the `yield` expressions already!
In turn, this means that the generator was already exhausted.
It is already empty:

```pycon
>>> next(gen)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
StopIteration
```

In practice, if a generator reports that a given value is _not_ in the generator, then it is probably because of this issue; because you had already iterated _past_ the value that you were looking for.

Let me show you another example of this:

```pycon
>>> def generator():
...     yield 1
...     yield 2
...     yield 3
...
>>> gen = generator()
>>> 999 in gen
False
>>> 1 in gen
False
>>> 2 in gen
False
>>> 3 in gen
False
```

This example might look obvious to you because I am using a non-existing value to exhaust the generator.
However, this issue can also arise after successful membership tests:

```pycon
>>> def generator():
...     yield 1
...     yield 2
...     yield 3
...
>>> gen = generator()
>>> 2 in gen
True
>>> 1 in gen
False
```

To make sure you understand what's going on, try to guess the output of the three membership checks below.
Other than that small challenge, that's it for now! [Stay tuned][subscribe] and I'll see you around!

```pycon
>>> def generator():
...     yield 1
...     yield 2
...     yield 3
...
>>> gen = generator()
>>> 3 in gen
## ???
>>> 1 in gen
## ???
>>> 2 in gen
## ???
```

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
