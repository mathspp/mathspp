This article proposes an implementation of an ergonomic binary search algorithm implemented as a bidirectional generator.

===


# Binary search as a bidirectional generator

Python generators can be bidirectional and in this article I will use this feature to try to implement the [binary search](https://en.wikipedia.org/wiki/Binary_search) algorithm in an ergonomic way.
To this end, I'll write a bidirectional generator that implements the binary search algorithm independent of any use cases.
This means the resulting generator can be used ergonomically whenever a binary search needs to be employed.


## Bidirectional generators

Python generators support the method `send` which allows sending data into a generator.
To receive data inside a generator, you can assign a `yield` statement to a variable and when the user calls the method `send`, the argument passed in will be stored in the variable used.
It's also important to note that the method `send` will send data into the generator and it will also execute the generator up to its next `yield` statement.

The snippet below shows how the method `send` interacts with a generator:

```py
def my_gen():
    yield 1
    yield 2
    value = yield 3
    print(value)
    yield 5
    yield 6

values = my_gen()
print(next(values))  # 1
print(next(values))  # 2
print(next(values))  # 3
values.send(4)  # 4
print(next(values))  # 6
```

Note that the `5` was skipped because it was yielded when you sent the `4` into the generator and we didn't catch it outside the generator.

Because of the way the method `send` interacts with generators, a generator that has been instantiated but hasn't been started yet can't be sent any data but it can be started with a call to `send(None)`, which will run the generator up to its first `yield`.


## Binary search

If you abstract the algorithm away from its concrete applications, the binary search algorithm produces a sequence of integer guesses that are candidates to satisfy some criteria.
Since binary search produces these values consecutively, and it doesn't need to generate every possible value ahead of time, it makes sense to consider a generator as the tool to implement binary search.

Additionally, depending on the result of an evaluation that is performed on a guess produced by the algorithm, the next guess might be above or below the previous guess.
This is why the generator needs to be bidirectional, since the user will inform the generator on whether guesses are currently too high or too low.

To inform the binary search generator of whether the guesses need to be increased or decreased, we will send the following integers to the binary search:

 - `-1`: the guess is too high;
 - `0`: the guess is correct; and
 - `1`: the guess is too low.

With this in mind, here is an example usage of the generator I am proposing:

```py
import random

sn = random.randint(0, 100)  # Secret number to be guessed.

searcher = binary_search(0, 100)
for guess in searcher:
    feedback = 1 if guess < sn else -1 if guess > sn else 0
    searcher.send(feedback)

print(f"Secret number was {guess}.")
# Secret number was 42.
```

The highlight is that using the binary search amounts to looping over the searcher with `for guess in searcher`.
Then, using the method `send` you can inform the searcher of whether it should go higher or lower.
When you pass in the `0`, the searcher ends automatically and you exit the loop without having to break out of it explicitly.


## Implementation

The generator includes a `yield` statement after checking that the generator received the feedback in the correct format to allow the generator to be used in a `for` loop and to decouple the act of sending feedback from the yielding of the next guess.
Thus, the code is as follows:

```py
from collections.abc import Generator
from typing import Literal


type FeedbackType = Literal[0] | Literal[1] | Literal[-1]  # Python 3.12+


def binary_search(left: int, right: int) -> Generator[int, FeedbackType, None]:
    """Generates guesses for a binary search in [left, right].

    Send 1 if the guess is too low, -1 if the guess is too high,
    and send 0 if the guess is correct.

    Example:
    >>> sn = random.randint(0, 100)
    >>> searcher = binary_search(0, 100)
    >>> for guess in searcher:
    ...     feedback = 1 if guess < sn else -1 if guess > sn else 0
    ...     searcher.send(feedback)
    ...
    >>> print(f"Secret number was {guess}.")
    Secret number was 42.
    """
    while left <= right:
        middle = (left + right) // 2
        feedback = yield middle
        if feedback not in {-1, 0, 1}:
            raise ValueError(f"Expected feedback in {{-1, 0, 1}}, got {feedback!r}")
        yield

        if feedback == 1:
            left = middle + 1
        elif feedback == -1:
            right = middle - 1
        else:
            break
    else:
        raise RuntimeError("Binary search came out empty.")
```


## Feedback values

The rationale behind the values `1` and `-1` is that they indicate the direction in which the guess needs to be tweaked.
The value `1` means that the guess needs to move in the positive direction, which means the guess is too low.
This is as valid as imagining that the `1` and `-1` should indicate the result of the comparison between the guess and the value we are looking for, in which case `1` should be used to indicate that the guess is too high.

To avoid this issue, the feedback values can be turned into values of an enumeration, forcing the user to be more verbose and infinitely clearer:

```py
from collections.abc import Generator
from enum import auto, Enum


class FeedbackType(Enum):
    HIT = auto()
    TOO_LOW = auto()
    TOO_HIGH = auto()


def binary_search(left: int, right: int) -> Generator[int, FeedbackType, None]:
    """Generates guesses for a binary search in [left, right].

    Send values from the enum FeedbackType to direct the search.

    Example:
    >>> sn = random.randint(0, 100)
    >>> searcher = binary_search(0, 100)
    >>> for guess in searcher:
    ...     feedback = 1 if guess < sn else -1 if guess > sn else 0
    ...     searcher.send(feedback)
    ...
    >>> print(f"Secret number was {guess}.")
    Secret number was 42.
    """
    while left <= right:
        middle = (left + right) // 2
        feedback = yield middle
        if not isinstance(feedback, FeedbackType):
            raise ValueError(f"Feedback can't be {feedback!r}.")
        yield

        if feedback == FeedbackType.TOO_LOW:
            left = middle + 1
        elif feedback == FeedbackType.TOO_HIGH:
            right = middle - 1
        else:
            break
    else:
        raise RuntimeError("Binary search came out empty.")
```


## Generalisation to unbounded searches

This code can be easily adapted so that it also works on unbounded searches.
For this, I created another generator `unbounded_binary_search`.
Here is an example usage:

```py
import random

# Picks a large, unbounded random positive integer.
sn = round(random.expovariate(1e-16))

searcher = unbounded_binary_search(0)
for guess in searcher:
    feedback = TOO_LOW if guess < sn else TOO_HIGH if guess > sn else HIT
    searcher.send(feedback)

print(f"Finished with {guess}.")  # Finished with 10181470251311158.
print(f"Secret number was {sn}.")  # Secret number was 10181470251311158.
```

This example shows how the usage in a `for` loop that traverses the guesses is exactly the same as with the bounded binary search.

The generator `unbounded_binary_search` starts by looking for an upper bound for the binary search and, once it finds it, it defers to the original generator:

```py
def unbounded_binary_search(left: int) -> Generator[int, FeedbackType, None]:
    """Generates guesses for an unbounded binary search in [left, inf).

    Send values from the enum FeedbackType to direct the search.
    """
    # Search for an upper bound.
    delta = 1
    while True:
        feedback = yield left + delta
        if not isinstance(feedback, FeedbackType):
            raise ValueError(f"Feedback can't be {feedback!r}.")
        yield

        if feedback == FeedbackType.HIT:
            return

        if feedback != FeedbackType.TOO_LOW:
            break
        delta *= 2
    # Bound found, find the value.
    yield from binary_search(left + delta // 2, left + delta)
```

Using `yield from` establishes bidirectional communication between the user of the generator `unbounded_binary_search` and the inner use of `binary_search`.


## Am I going crazy?

I just had the idea to create this because the thing that's closest to a binary search in the standard library is [the module `bisect`](https://docs.python.org/3/library/bisect.html) and it doesn't feel very ergonomic nor flexible.
In Python 3.10 the module was improved and its functions _are_ more flexible now, but it doesn't feel like an obvious API.
Did I just have a terrible idea and I'm going completely insane?
Or did I just struck gold?
Let me know in the comments below or [drop me a line](mailto:rodrigo@mathspp.com).
