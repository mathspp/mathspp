Learn how objects are automatically iterable if you implement integer indexing.

===

## Introduction

An **iterable** in Python is any object you can traverse through with a `for` loop.
**Iterables** are typically containers and iterating over the iterable object allows you to access the elements of the container.

This article will show you how you can create your own iterable objects through the implementation of integer indexing.

## Indexing with `__getitem__`

To make an object that can be indexed you need to implement the method `__getitem__`.

As an example, you'll implement a class `ArithmeticSequence` that represents an **arithmetic sequence**, like $5, 8, 11, 14, 17, 20$.
An arithmetic sequence is defined by its first number ($5$), the step between numbers ($3$), and the total number of elements ($6$).
The sequence $5, 8, 11, 14, 17, 20$ is `seq = ArithmeticSequence(5, 3, 6)` and `seq[3]` should be $14$.
Using some arithmetic, you can implement indexing in `__getitem__` directly:

```py
class ArithmeticSequence:
    def __init__(self, start: int, step: int, total: int) -> None:
        self.start = start
        self.step = step
        self.total = total

    def __getitem__(self, index: int) -> int:
        if not 0 <= index < self.total:
            raise IndexError(f"Invalid index {index}.")

        return self.start + index * self.step

seq = ArithmeticSequence(5, 3, 6)
print(seq[3])  # 14
```

## Turning an indexable object into an iterable

If your object accepts integer indices, then it is _automatically_ an iterable.
In fact, you can already iterate over the sequence you created above by simply using it in a `for` loop:

```py
for value in seq:
    print(value, end=", ")
# 5, 8, 11, 14, 17, 20,
```

## How Python distinguishes iterables from non-iterables

You might ask yourself “how does Python inspect `__getitem__` to see it uses numeric indices?”
It doesn't!
If your object implements `__getitem__` and you try to use it as an iterable, Python will _try_ to iterate over it.
It either works or it doesn't!

To illustrate this point, you can define a class `DictWrapper` that wraps a dictionary and implements `__getitem__` by just grabbing the corresponding item out of a dictionary:

```py
class DictWrapper:
    def __init__(self, values):
        self.values = values

    def __getitem__(self, index):
        return self.values[index]
```

Since `DictWrapper` implements `__getitem__`, if an instance of `DictWrapper` just _happens_ to have some integer keys (starting at `0`) then you'll be able to iterate partially over the dictionary:

```py
d1 = DictWrapper({0: "hey", 1: "bye", "key": "value"})

for value in d1:
    print(value)
```
```pycon
hey
bye
Traceback (most recent call last):
  File "<python-input-25>", line 3, in <module>
    for value in d1:
                 ^^
  File "<python-input-18>", line 6, in __getitem__
    return self.values[index]
           ~~~~~~~~~~~^^^^^^^
KeyError: 2
```

What's interesting is that you can see explicitly that Python tried to index the object `d` with the key `2` and it didn't work.
In the `ArithmeticSequence` above, you didn't get an error because you raised `IndexError` when you reached the end and that's how Python understood the iteration was done.
In this case, since you get a `KeyError`, Python doesn't understand what's going on and just fails.

If you create an instance of `DictWrapper` that doesn't have a key `0`, Python will still try to iterate over the object and you'll just get the `KeyError` earlier:

```py
d2 = DictWrapper({})

for value in d2:
    print(value)
```
```pycon
Traceback (most recent call last):
  File "<python-input-27>", line 1, in <module>
    for value in d2:
                 ^^
  File "<python-input-18>", line 6, in __getitem__
    return self.values[index]
           ~~~~~~~~~~~^^^^^^^
KeyError: 0
```

## Conclusion

If you have an object that accepts integer indices starting at `0`, Python conveniently infers how to iterate over that object.
Python will access successive indices until it gets an `IndexError`, at which point it stops the iteration.
However, Python can't determine which implementations of the dunder method `__getitem__` satisfy these restrictions, so Python just _tries_ to see what happens.
