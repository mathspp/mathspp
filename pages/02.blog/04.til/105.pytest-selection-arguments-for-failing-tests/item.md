Today I learned about 5 useful pytest options that let me control what tests to run with respect to failing tests.

===


## pytest selection arguments for failing tests

Florian Bruhin shared a pytest tip on X / Twitter the other day regarding command line options that pytest accepts and that control how pytest handles failed tests.
He shared the following _very_ illustrative diagram:

![Diagram that explains visually how the pytest options `--last-failed`, `--failed-first`, `--new-first`, `--stepwise`, and `--maxfail` work. The visual explanation is done by representing tests as coloured circles, with failing tests represented in red and passing tests represented in green. Then, test runs are represented as sequences of tests connected by a line.](_diagram.webp "Diagram by Florian.")

I think the diagram is so good that I could essentially stop the article here and we all would've understood the options.
But I'll be just a little bit more verbose and explain how each option works.

### `--maxfail`

Create the following file `test.py` with 4 failing tests:

```py
def test_1():
    assert False

def test_2():
    assert False

def test_3():
    assert False

def test_4():
    assert False
```

If you run the tests with `pytest test.py` you get 4 failing tests. Duh.
If you use the option `--maxfail=n`, pytest will run until completion or until it finds `n` failures.
Running with `pytest test.py --maxfail=2` stops after `test_2` failed:

```
FAILED test.py::test_1 - assert False
FAILED test.py::test_2 - assert False
!!!!!!!!!! stopping after 2 failures !!!!!!!!!!!
============== 2 failed in 0.04s ===============
```


### `--stepwise`

The option `--stepwise` stop at each failure and every time you rerun, it picks up from where it left off.
Starting with the file from the previous example, if you run `pytest test.py --stepwise`, we stop immediately at the first failure:

```
FAILED test.py::test_1 - assert False
! Interrupted: Test failed, continuing from this test next run. !
============== 1 failed in 0.08s ===============
```

Note how pytest says we'll pick up from here when we rerun the tests.
Let us go ahead and fix our first test:

```py
def test_1():
    assert True
```

We rerun pytest with `pytest test.py --stepwise` and we go up to test 2:

```
test.py .F
FAILED test.py::test_2 - assert False
! Interrupted: Test failed, continuing from this test next run. !
========= 1 failed, 1 passed in 0.08s ==========
```

Note that pytest says that we passed one test (`test_1`, which was fixed) and that we failed one test (`test_2`).

If we attempt to fix it (but still fail), when we rerun pytest with `pytest test.py --stepwise` we start from test 2 and it will fail again:

```
FAILED test.py::test_2 - assert False
! Interrupted: Test failed, continuing from this test next run. !
======= 1 failed, 1 deselected in 0.08s ========
```

Now, we don't even run `test_1` because we're already past it.
We tried rerunning `test_2` but it still failed.

Let's now fix `test_2`:

```py
def test_2():
    assert True
```

Rerunning with `pytest test.py --stepwise` skips `test_1`, retries `test_2` and goes up to `test_3`:

```
FAILED test.py::test_3 - assert False
! Interrupted: Test failed, continuing from this test next run. !
== 1 failed, 1 passed, 1 deselected in 0.08s ===
```

Now, if we clear the pytest cache with `rm -rf .pytest_cache` and rerun with `pytest test.py --stepwise`, we see that we run the first three tests, stopping at `test_3` because that test is still failing.

### `--new-first`

At this point, even though we have a couple of failing tests, let us add a new (passing) test:

```py
def test_5():
    assert True
```

Running pytest with `--new-first` will start by running the new tests (`test_5`) first, and then it will run the other tests in the usual order.
Let us run the tests with `pytest test.py --new-first -vv`:

```
test.py::test_5 PASSED                   [ 20%]
test.py::test_1 PASSED                   [ 40%]
test.py::test_2 PASSED                   [ 60%]
test.py::test_3 FAILED                   [ 80%]
test.py::test_4 FAILED                   [100%]
## ...
```

Note that `test_5` is shown first, at the top of the list.
If we rerun the tests now, regardless of whether we use `--new-first` or not, the fifth test will be at the bottom of the list because it no longer is a new test:

```
❯ pytest test.py -vv --new-first
============= test session starts ==============
## ...

test.py::test_1 PASSED                   [ 20%]
test.py::test_2 PASSED                   [ 40%]
test.py::test_3 FAILED                   [ 60%]
test.py::test_4 FAILED                   [ 80%]
test.py::test_5 PASSED                   [100%]
```

### `--last-failed`

The option `--last-failed` will rerun only the tests that failed in the previous test session.
At this point, those would be `test_3` and `test_4`:

```
❯ pytest test.py --last-failed -vv
============= test session starts ==============
## ...
collected 5 items / 3 deselected / 2 selected
run-last-failure: rerun previous 2 failures

test.py::test_3 FAILED                   [ 50%]
test.py::test_4 FAILED                   [100%]
## ...
======= 2 failed, 3 deselected in 0.04s ========
```

This option is useful if your test suite is long and you just want to quickly iterate on an attempt to fix a specific bug that is being caught by a test.

### `--failed-first`

Lastly, the option `--failed-first` is similar to the option `--last-failed` but instead of running only the test that failed, it starts by running the tests that failed previously and then runs all other tests.
I'd say this is particularly helpful after you've got the failing tests to pass with `--last-failed` and you just want to make sure you didn't break anything else in other tests.

While we still have our two failing tests, running `pytest test.py --failed-first` will run all five tests, starting with `test_3` and `test_4`:

```
❯ pytest test.py --failed-first -vv
============= test session starts ==============
## ...
run-last-failure: rerun previous 2 failures first

test.py::test_3 FAILED                   [ 20%]
test.py::test_4 FAILED                   [ 40%]
test.py::test_1 PASSED                   [ 60%]
test.py::test_2 PASSED                   [ 80%]
test.py::test_5 PASSED                   [100%]

## ...
========= 2 failed, 3 passed in 0.04s ==========
```

### Learn more

If you want to learn more about pytest I definitely suggest you keep a close look on Florian <https://bruhin.software>.
He does corporate pytest trainings, pytest workshops and talks at conferences, and more.
