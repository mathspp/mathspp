Yesterday I spent the whole day tryint to patch a module global.
This is what I ended up with.

===


# Patching module globals with `pytest`

Suppose you have a small project that has an April Fool's joke set up:

```py
# constants.py

APRIL_FOOLS = False
```

```py
# useful_code.py

from constants import APRIL_FOOLS

def add(a, b):
    if APRIL_FOOLS:
        result = a - b
    else:
        result = a + b
    return result
```

Now, suppose you want to test your April Fool's joke with `pytest`.
One thing I found online was that you could use the fixture `monkeypatch` and its method `setattr`, something like this:

```py
# test_useful_code.py

import pytest

import constants
import useful_code

def test_add_on_april_fools(monkeypatch):
    monkeypatch.setattr(constants, "APRIL_FOOLS", True)
    assert useful_code.add(10, 5) == 5
```

(I'm sparing you from hearing about all of the embarrissingly dumb things I tried before getting to this point!)

If you run this test with `pytest test_useful_code.py` you get a failure:

```
FAILED test_useful_code.py::test_add_on_april_fools - assert 15 == 5
```

What's happening, then?
Take a moment to think about it...

What's happening is that when I import `useful_code` into the testing file, that module will then run the line `from constants import APRIL_FOOLS`, which sets the variable `APRIL_FOOLS` inside the namespace of the module `useful_code`, which is _separate_ from the namespace of the module `constants`!

After banging my head against the wall for a while, I realised I could tweak my code to work with this testing approach!
All I had to do was use `constants.APRIL_FOOLS` instead of using `APRIL_FOOLS` directly:

```py
# useful_code.py

import constants

def add(a, b):
    if constants.APRIL_FOOLS:
        result = a - b
    else:
        result = a + b
    return result
```

Now, if you run your test it will pass and the mocking of the variable will have succeeded!

The reason this works is that now we're accessing `APRIL_FOOLS` via the `constants` namespace, so when we patch the value of `APRIL_FOOLS` in `constants`, that patched value will be visible when the constant is used elsewhere.

You could also patch `APRIL_FOOLS` directly in the module `useful_code`, but if you have a constant that is used in many different modules, it is much easier (and better) to patch it _once_ where it is defined versus patching it many times wherever it is imported.

I'm not claiming this is _the best solution_ to this problem but it's certainly _a solution that works_.
If you have better ideas that have more or less the same complexity, let me know!

For reference, this is a simplification of a “real” issue I had when trying to add tests to [this Textual PR of mine](https://github.com/Textualize/textual/pull/4062).
