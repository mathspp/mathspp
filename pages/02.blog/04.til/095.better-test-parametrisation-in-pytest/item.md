Today I learned how to use named tuples to improve readability and flexibility of test parametrisations in pytest.

===

## Better test parametrisation in pytest

Today I attended a [pytest tutorial](https://pretalx.com/pyconde-pydata-2024/talk/DSFWRC/) at [PyCon DE 2024](https://2024.pycon.de) and I learned a couple of really neat tricks to improve parametrised tests.

Let's take a look at them!


### Stacking the `parametrize` mark

One quick tip is that you can stack `parametrize` to run all permutations of different parameters.

For example, if `calc` is a function that does basic arithmetic operations, the test below will test 9 different additions:

```py
import pytest

@pytest.mark.parametrize("a", [-3, 0, 5])
@pytest.mark.parametrize("b", [-8, 0, 42])
def test_calc_add(a, b):
    assert calc(a, b, "+") == a + b
```

Running this test, you get the following output:

```
test_calc.py::test_calc_add[-8--3] PASSED     [ 11%]
test_calc.py::test_calc_add[-8-0] PASSED      [ 22%]
test_calc.py::test_calc_add[-8-5] PASSED      [ 33%]
test_calc.py::test_calc_add[0--3] PASSED      [ 44%]
test_calc.py::test_calc_add[0-0] PASSED       [ 55%]
test_calc.py::test_calc_add[0-5] PASSED       [ 66%]
test_calc.py::test_calc_add[42--3] PASSED     [ 77%]
test_calc.py::test_calc_add[42-0] PASSED      [ 88%]
test_calc.py::test_calc_add[42-5] PASSED      [100%]
```

With this pattern you cannot specify a test output for each single test, so this will only work if you have a different way of computing the result or if the expected result is always the same.

For tests where you must specify the expected result but you also want to test all permutations of certain parameters, you can use the REPL and `itertools.product` to programmatically generate all the tests instead of having to write them down by hand.


### Parametrising with named tuples

You can add a lot of flexibility and readability in your parametrised tests if you use named tuples[^1].
Instead of listing tuples with plenty of different values, we can create a named tuple to wrap around those tuples, which then lets us use named parameters _and_ default arguments in our parametrisation cases!

As an example, consider a test that's parametrised in the conventional way:

```py
import pytest

@pytest.mark.parametrize(
    ["a", "b", "op", "result", "flag1", "flag2"],
    [
        (10, 15, "+", 25, True, False),
        (10, 15, "-", -5, True, False),
        (3, 18, "*", 54, False, False),
        (18, 3, "/", 6, True, False),
        (5, 0, "/", None, True, True),
    ]
)
def test_something(a, b, op, result, flag1, flag2):
    # do stuff with a, b, op, ...
    ...
```

If you create a named tuple, you could use the names of the parameters to help disambiguate the two flags, for example, and you could also add default values:

```py
from typing import NamedTuple
import pytest

class TestCaseParam(NamedTuple):
    a: int
    b: int
    op: str
    result: int
    flag1: bool = True
    flag2: bool = False

@pytest.mark.parametrize(
    ["tc"],
    [
        TestCaseParam(10, 15, "+", 25),
        TestCaseParam(10, 15, "-", -5),
        TestCaseParam(3, 18, "*", 54, flag1=False),
        TestCaseParam(18, 3, "/", 6),
        TestCaseParam(5, 0, "/", None, flag2=True),
    ]
)
def test_something(tc):  # <-- Now we only accept the named tuple.
    # do stuff with tc.a, tc.b, tc.op, ...
    ...
```

This does add more code to your tests, so it's not really reasonable to do it for every single parametrisation you'll create, but it might come in handy for more complex parametrisations.
I really liked this trick!


[^1]: the trainer originally presented this tip with `dataclasses.dataclass` but in a discussion we realised a named tuple was leaner.
