Today I learned how to use Hypothesis to do confident code refactoring.

===


# Hypothesis for code refactoring

## What is Hypothesis?

[Hypothesis] is a Python library that you can use when testing your code.
In a nutshell, Hypothesis will generate test cases automatically for you.

In case you already know what that means, Hypothesis enables property-based testing, which is a “testing paradigm”.
In case you are interested, I've written about [getting started with Hypothesis](https://semaphoreci.com/blog/property-based-testing-python-hypothesis-pytest) before.

One of the cool things about Hypothesis is that you can use it when you are refactoring code and you want to be as certain as possible that you are not breaking anything.
Let me give you a concrete example.


## Code refactoring

Recently I played a bit with the [Damerau-Levenshtein distance](/blog/til/damerau-levenshtein-distance) and I implemented it in Python.
The code (taken from [this TIL article](/blog/til/damerau-levenshtein-distance)) looked like this:

```py
from functools import lru_cache

@lru_cache
def dl(a, b):
    edit_distances = []

    if len(a) == len(b) == 0:
        edit_distances.append(0)

    if len(a) > 0:
        edit_distances.append(dl(a[:-1], b) + 1)

    if len(b) > 0:
        edit_distances.append(dl(a, b[:-1]) + 1)

    if len(a) > 0 and len(b) > 0:
        edit_distances.append(dl(a[:-1], b[:-1]) + (a[-1] != b[-1]))

    if len(a) > 1 and len(b) > 1 and a[-1] == b[-2] and a[-2] == b[-1]:
        edit_distances.append(dl(a[:-2], b[:-2]) + (a[-1] != b[-1]))


    return min(edit_distances)
```

However, this was just a basic implementation that translated the mathematical formula that the Wikipedia page showed.
I wanted to have a go at rewriting this in a better way.
I wanted to _refactor_ the code.

So, I did.
I came up with this alternative implementation:

```py
from functools import lru_cache


@lru_cache
def dl(a, b):
    if not a or not b:
        return len(a) + len(b)

    levenshstein = min(
        dl(a[:-1], b) + 1,
        dl(a, b[:-1]) + 1,
        dl(a[:-1], b[:-1]) + (a[-1] != b[-1]),
    )

    if a[:-1] and b[:-1] and a[-1] == b[-2] and b[-1] == a[-2]:
        return min(
            levenshstein,
            dl(a[:-2], b[:-2]) + (a[-1] != b[-1]),
        )

    return levenshstein
```

Now, the question is:
are these two functions the same?
Do the two functions compute the same thing?

Enter: Hypothesis!


## Verifying a code refactor with Hypothesis

Because Hypothesis generates random test cases for you, what you can do is create a test where Hypothesis generates two random strings, we feed the two strings to the two alternative implementations, and then we check if they return the same result!

In essence, if `dl` and `dl2` are the two functions, you just need to write this:

```py
# dl.py
from functools import lru_cache

from hypothesis import given
from hypothesis.strategies import text

@lru_cache
def dl(a, b):
    ...


@lru_cache
def dl2(a, b):
    ...


@given(text(max_size=15), text(max_size=15))
def test_dl_match(a, b):
    assert dl(a, b) == dl2(a, b)
```

Then, you can run your tests – for example, with `pytest dl.py` – and wait for Hypothesis's verdict.
If the test passes, that's because the two functions _probably_ compute the same thing.

In this case, the test passes, so the two functions likely are the same and my refactor is OK!

Notice that this doesn't guarantee that the two functions are correct!
I just tested whether they compute the same results.
So, if `dl` is correct, it's likely that `dl2` is also correct; if `dl` is incorrect, it's likely that `dl2` is _also_ incorrect.

So, now you know.
If you want to do a large refactor of a function, have the two versions side by side, use Hypothesis to generate the arguments for them, and compare the results of the two.


[Hypothesis]: https://hypothesis.readthedocs.io/en/latest/index.html


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
