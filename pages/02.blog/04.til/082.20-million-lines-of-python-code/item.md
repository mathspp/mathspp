Today I learned that the largest file ever published to PyPI has 20 MILLION lines of code.

===


## 20 million lines of Python code

Today I'm attending [PyCon Portugal 2023][pycon-pt] and Tom Forbes, the Thursday keynoter, presented the largest Python file ever published to PyPI.
It is a Python file with over 20 million lines of Python code.
MILLION.

What does this file do, you might ask?

I'll give you a hint.
This file comes from a project called [EvenOrOdd](https://pypi.org/project/EvenOrOdd/)...
Can you see where we are going?

The file implements a single function `isEven` that starts like this:

```py
def isEven(num):
    if num == 0:
        return True
    elif num == 1:
        return False
    elif num == 2:
        return True
    elif num == 3:
        return False
    # ...
```

If you scroll down for long enough, you'll eventually reach the end of the function, which looks like this:

```py
def isEven(num):
    # ...
    elif num == 1048571:
        return False
    elif num == 1048572:
        return True
    elif num == 1048573:
        return False
    elif num == 1048574:
        return True
    elif num == 1048575:
        return False
    else:
        raise Exception("Number is not within bounds")
```

That's a pretty silly function!
Why on Earth would it stop at 1,048,575?
(Starting at `0` and going up to `1_048_575` means that the function `isEven` covers $2^{20}$ integers.)

I installed the package:

```bash
python -m pip install evenorodd
```

And then decided to test it, just to make sure none of the branches were wrong:

```pycon
>>> from EvenOrOdd
>>> for i in range(2 ** 20):
...     assert EvenOrOdd.isEven(i) == (not (i % 2))
```

Given that this loop is taking a long time to finish, I wonder if the difference between `isEven(0)` and `isEven(1048575)` is noticeable.

Using the module `timeit`, I tried checking how fast I could compute `isEven(0)` versus `isEven(1048575)` and I got these numbers:

```pycon
>>> from timeit import timeit

>>> setup = "from EvenOrOdd.EvenOrOdd import isEven"
>>> timeit("isEven(0)", setup=setup, number=1000)
2.8417000066838227e-05
>>> timeit("isEven(1048575)", setup=setup, number=1000)
6.486064583999905
>>> _ / 1000
0.0064860645839999054
```

The timings above might be different on your machine, but the relative comparisons should be somewhat similar, and the numbers above show that it is faster to call `isEven(0)` 1000 times than it is to compute `isEven(1048575)` _once_.

We can take this further:

```pycon
>>> timeit("isEven(0)", setup=setup, number=100000)  # Changed `number=...`
0.002839708999999857
```

This new timing shows that we can call `isEven(0)` about 100,000 times and be done _before_ `isEven(1048575)` finishes computing...

What is more, I was able to write this blog post and the testing of the function `isEven` is still running (the `for` loop I shared above), so I'll interrupt that loop:

```pycon
>>> for i in range(2 ** 20):
...     assert EvenOrOdd.isEven(i) == (not (i % 2))
...
^CTraceback (most recent call last):
  File "<stdin>", line 2, in <module>
KeyboardInterrupt
>>> i
475308
```

So, as you can see, I've gone through 46% of the _faster_ test cases.
At least we know that the numbers up to 475,307 are correctly classified as even or odd by the function `isEven`.


[pycon-pt]: http://2023.pycon.pt
