Today I learned about the parameter `match` used in `pytest.raises`.

===


## `pytest.raises`: parameter `match`

Today I attended a [pytest tutorial](https://pretalx.com/pyconde-pydata-2024/talk/DSFWRC/) at [PyCon DE 2024](https://2024.pycon.de) and very early in the tutorial I learned about the parameter `match` that you can use with `pytest.raises`.

The parameter `match` accepts a regex pattern that is tested against the error message and your test only passes if the error message matches the regex.

The initial example given was this:

```py
def parse_pos_number(s: str) -> int:
    n = int(s)
    if n < 0:
        raise ValueError("No negativity allowed!")
    return n
```

This function raises a `ValueError` if you pass it a negative value but it will also raise a (different) `ValueError` if you pass it a string that doesn't look like an integer:

```py
parse_pos_number("-1")  # ValueError: No negativity allowed!
parse_pos_number("a")   # ValueError: invalid literal for int() with base 10: 'a'
```

So, when writing a test to make sure that `parse_pos_number` fails when giving it a negative number, we should also ensure that the `ValueError` is “the correct one”.
The test would look like this:

```py
import pytest

def test_with_negative_number():
    with pytest.raises(ValueError, match="No negativity allowed!"):
        parse_pos_number("-1")
```

The trainer also put forward that this parameter `match` can be quite useful if you have a (custom) error that is raised in multiple situations.
Adding `match` to your tests will also test your error messages and it acts as a “documentation” test.
