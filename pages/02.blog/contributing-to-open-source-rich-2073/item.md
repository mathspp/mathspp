This is an account of my contribution to solving issue 2073 of the Python open source library `rich`.

===


!!!! This is an account of a contribution **I am still making** to an open source library.
!!!! In other words, this contribution is still **ongoing**.
!!!! Watch this space for updates.


# Preamble

I am trying to get my feet wet in the world of open source.
While I have a couple of contributions under my belt,
those have been pretty basic.
So, I decided to reach out to [Will McGugan][will] and ask if Will knew of any simple issues I could try to solve.
Will suggested I take a look at [issue #2073][gh-2073] of `rich`, so that's what I am going to be doing.


# Setting up the environment

The first thing I do to start work is clone the repository into my machine:

```bash
git clone https://github.com/textualize/rich
```

This way, I'll have all the files locally and I'll be able to play around with the code, etc.

The _next_ thing I do is use `poetry` to install all the dependencies in a virtual environment.
I'm using `poetry` in this step because that's what `rich` uses.

So, I install the dependencies:

```bash
poetry install
```

While the repo cloned and the dependencies installed, I started preparing this blog post.
After the dependencies got installed, I started a new shell within the virtual environment I had just created:

```bash
poetry shell
```

Now, I have an environment where I can play with the current version of `rich` and where I can make sure I understand the issue before I try to fix it.


# Understanding issue 2073

The next step in making my contribution is making sure I understand the issue I am handling.

Here is the description of the bug, as per the original issue:

 > Some of the fields in my objects consist of Numpy or xarray data whose repr may span accros multiple lines. Here is an example:
 > 
 > ```py
 > import numpy as np
 > from rich.pretty import pprint
 > zeros = np.zeros((4,4))
 > pprint(zeros)
 > ```
 > 
 > I get a nicely indented repr:
 > 
 > ```py
 > array([[0., 0., 0., 0.],
 > │      [0., 0., 0., 0.],
 > │      [0., 0., 0., 0.],
 > │      [0., 0., 0., 0.]])
 > ```
 > 
 > Now, if I use one of these in a dictionary, like this
 > 
 > ```py
 > d = {"foo": "bar", "zeros": zeros}
 > pprint(d)
 > ```
 > 
 > I get that
 > 
 > ```py
 > {
 > │   'foo': 'bar',
 > │   'zeros': array([[0., 0., 0., 0.],
 > │      [0., 0., 0., 0.],
 > │      [0., 0., 0., 0.],
 > │      [0., 0., 0., 0.]])
 > }
 > ```
 > 
 > while I'd expect indentation to be adjusted like this
 > 
 > ```py
 > {
 > │   'foo': 'bar',
 > │   'zeros': array([[0., 0., 0., 0.],
 > │                   [0., 0., 0., 0.],
 > │                   [0., 0., 0., 0.],
 > │                   [0., 0., 0., 0.]])
 > }
 > ```
 > 
 > or at least something like this (saves horizontal space, no need to guess length of first line)
 > 
 > ```py
 > {
 > │   'foo': 'bar',
 > │   'zeros': 
 > |   |   array([[0., 0., 0., 0.],
 > │   |          [0., 0., 0., 0.],
 > │   |          [0., 0., 0., 0.],
 > │   |          [0., 0., 0., 0.]])
 > }
 > ```

(I omitted some details that are irrelevant here.)

After reading the issue, the first thing I did was to make sure I am able to reproduce it.
So, I opened a Python interpreter where I also have `numpy` installed and I gave it a try:

```py
>>> import numpy
>>> from rich.pretty import pprint
>>> pprint(numpy.zeros((4, 4)))
array([[0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.]])
>>> d = {"foo": "bar", "zeroes": numpy.zeros((4, 4))}
>>> pprint(d)
{
│   'foo': 'bar',
│   'zeroes': array([[0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.]])
}
```

So, it seems I am able to reproduce the issue.
However, I managed to note two other interesting things that are likely to influence the way I go about solving this.

First, to get the exact same output as the original issue described, I had to make my terminal very narrow.
With my current font size/terminal width, the actual output I get looks like this:

```py
>>> pprint(d)
{'foo': 'bar', 'zeroes': array([[0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.]])}
```

And this output looks a bit misaligned.
I am not sure if the solution would be to have the output be:

```py
# Possible output:
>>> pprint(d)
{'foo': 'bar', 'zeroes': array([[0., 0., 0., 0.],
                         │      [0., 0., 0., 0.],
                         │      [0., 0., 0., 0.],
                         │      [0., 0., 0., 0.]])}
```

To me, this looks a bit odd.
So, when one item inside a collection has a multi-line representation,
we may want the outer collection to also be printed over multiple lines.

Second, the nice-looking, indentend representation of the `numpy` array we see is not `rich`'s doing.
By default, `numpy` does a pretty decent job of pretty-printing arrays with brackets and values aligned:

```py
>>> numpy.zeros((4, 4))
array([[0., 0., 0., 0.],
       [0., 0., 0., 0.],
       [0., 0., 0., 0.],
       [0., 0., 0., 0.]])
>>> print(numpy.zeros((4, 4))) 
[[0. 0. 0. 0.]
 [0. 0. 0. 0.]
 [0. 0. 0. 0.]
 [0. 0. 0. 0.]]
```

So, it must be that `rich` doesn't expect single objects to have multi-line representations.

(At this stage, I haven't opened the source code for `rich`,
I'm just thinking some of these things through.
I might make educated guesses that turn out to be right,
and I might make educated guess that turn out to be wrong.
We'll see!)


# Testing my hypothesis

To check if I am not completely wrong here,
I'll create a very simple class that has a multi-line representation.
Then, I'll try to pretty-print it with `rich` and see what happens.

So, this is my class definition:

```py
class MR:
    """Objects with multi-line representations."""

    def __init__(self, n):
        self.n = n  # Number of lines, expected to be 2 or more.

    def __repr__(self):
        return (
            f"X   | line {0}\n" +
            "\n".join(f"    | line {i}" for i in range(1, self.n))
        )
```

This object has a multi-line `repr` that is aligned by the pipe character:

```py
>>> MR(3)
X   | line 0
    | line 1
    | line 2
>>> print(MR(3))
X   | line 0
    | line 1
    | line 2
```

When pretty-printed with `rich`,
the overall structure of the output doesn't change...
But we do get the faint vertical guidelines that help us understand that what we are seeing is the `repr` of a single object:

```py
>>> pprint(MR(3))
X   | line 0
│   | line 1
│   | line 2
```

If we nest these `MR` objects in, say, another dictionary,
we see the same misalignments as with the numpy array.

First, with my wide terminal:

```py
>>> d = {"foo": "bar", "mr": MR(3), 42: 73}
# Wide terminal
>>> pprint(d)
{'foo': 'bar', 'mr': X   | line 0
│   | line 1
│   | line 2, 42: 73}
```

This little experiment strengthens my suspicion that `rich` may not expect multi-line `repr`s from single objects.
Notice how, not only is the representation of `MR(3)` misaligned,
but the pair `42: 73` looks really awkward when in the same line as the third line of the `repr` of `MR(3)`.

If I make my terminal shorter, the output is a bit less awkward:

```py
# Thin terminal
>>> pprint(d)
{
│   'foo': 'bar',
│   'mr': X   | line 0
│   | line 1
│   | line 2,
│   42: 73
}
```

Now the output looks less awkward, but the `repr` for `MR(3)` is still misaligned.

So, what would we want the output to look like in these cases?


# Expected behaviour

If you identify a bug, that's because the program you are running didn't run the way you expected...
So, that always begs the question: what **did** you expect?

Sometimes, the answer is pretty obvious.
For example, if I am writing a calculator, I expect `2 + 2` to be `4` and nothing else.

In other situations, you might be able to tell that something is off,
but it may be difficult to actually describe how something should be.
Thankfully, this is not one of those cases.

I went back to the GitHub issue and [described my findings][gh-first-findings],
and then I asked what type of output we were looking for.
I reiterated the two options that had been posted in the original issue:

```py
{
│   'foo': 'bar',
│   'zeros': array([[0., 0., 0., 0.],
│                   [0., 0., 0., 0.],
│                   [0., 0., 0., 0.],
│                   [0., 0., 0., 0.]])
}
{
│   'foo': 'bar',
│   'mr': X   | line 0
│             | line 1
│             | line 2
}
```

and

```py
{
│   'foo': 'bar',
│   'zeros': 
│   │   array([[0., 0., 0., 0.],
│   │          [0., 0., 0., 0.],
│   │          [0., 0., 0., 0.],
│   │          [0., 0., 0., 0.]])
}
{
│   'foo': 'bar',
│   'mr': 
│   │    X   | line 0
│   │        | line 1
│   │        | line 2
}
```

The answer Will gave is, in hindsight, pretty obvious.
Remember that the dunder method `__repr__` is supposed to give you a faithful representation of your object,
ideally one that you could use to rebuild the object itself.
In other words, it is good when your `__repr__` prints code that you could use to rebuild the object.

So, assuming your object with a multi-line representation prints code that you can use to rebuild that same object
(like is the case with NumPy arrays),
we want Rich's representations to preserve that property.
In the case of dictionaries, that means we want to go with the first option:

```py
{
│   'foo': 'bar',
│   'zeros': array([[0., 0., 0., 0.],
│                   [0., 0., 0., 0.],
│                   [0., 0., 0., 0.],
│                   [0., 0., 0., 0.]])
}
```

After all, going with the option where the multi-line representation object is by itself might look easier to implement,
but doesn't produce valid Python code for the dictionary.

There was also a question of whether or not there should be guidelines added to the objects with multi-line representations.
After all, right now, Rich adds guides to those objects:

```py
>>> pprint(np.zeros((4, 4)))
array([[0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.],
│      [0., 0., 0., 0.]])
```

and

```py
>>> pprint(MR(3))
X   | line 0
│   | line 1
│   | line 2
```

Will agreed with me, and said we don't want the guidelines here.

There is also a matter of whether or not collections that contain objects with multi-line representations
should have their elements displayed one element per line.
This question is relevant because, right now, short containers are printed on a single line:

```py
>>> pprint({42: None, True: "Howdy!"})
{42: None, True: 'Howdy!'}
```

What if we have an `MR(2)` object in there?
If it's in the middle of the container, we have seen how awkward it looks to keep the next items in line:

```py
>>> d = {"foo": "bar", "mr": MR(3), 42: 73}
>>> pprint(d)
{'foo': 'bar', 'mr': X   | line 0
│   | line 1
│   | line 2, 42: 73}
```

In this case, we would definitely like the output to be

```py
>>> pprint(d)
{
│   'foo': 'bar',
│   'mr': X   | line 0
│             | line 1
│             | line 2,
│   42: 73
}
```

What if the object was only in the end of the container?
In that case, maybe we could get away with inlining everything:

```py
>>> d = {"foo": "bar", 42: 73, "mr": MR(3)}
# Wide terminal
>>> pprint(d)
{'foo': 'bar', 42: 73, 'mr': X   | line 0
                                 | line 1
                                 | line 2}
```

Although, as soon as I wrote it, I realised it looks terrible.

This is what we have, so far, on how the output of multi-line representations should look like when inside a container:

 - The multi-line representation should preserve its original alignments.
 - Multi-line representations should have no vertical guidelines added to it.
 - Containers that contain objects with multi-line representations should automatically display one element per line.

Now that we have decided what we want to do, it is time to start taking care of it!


# Test the expected behaviour

Rich is thoroughly tested, which means I will have to write a couple of tests that make sure my change(s) work as expected.
So, one thing I could do (following TDD – test driven development) is write some tests that check the behaviours outlined above.

In the beginning, all the tests will fail – that's why the GitHub issue was raised.
But when I fix it, the tests will start to pass!

So let me get on with it.
The first thing I do is go to `tests/` and start looking for the appropriate file to add the tests to.
After scrolling for a bit, I find the file `tests/test_pretty.py` that contains the following import line close to the top of the file:

```py
from rich.pretty import Node, Pretty, _ipy_display_hook, install, pprint, pretty_repr
```

We can see that this is importing `pprint`, the function we used above and that is producing the wrong output.
I also searched the file for the function `pprint` and I could find some tests that were actually testing the output of the function `pprint`.
Here is the first such test:

```py
def test_pprint():
    console = Console(color_system=None)
    console.begin_capture()
    pprint(1, console=console)
    assert console.end_capture() == "1\n"
```


However, I noticed there were a lot of tests above these `pprint` tests,
and they seemed to be targeting a function called `pretty_repr`.
So, I decided to open the REPL and see what `pretty_repr` does:

```py
>>> help(pretty_repr)
Help on function pretty_repr in module rich.pretty:
# ...
    Prettify repr string by expanding on to new lines to fit within a given width.
```

So, by playing with it a bit and by reading the docstring above,
I understood that `pretty_repr` returns a pretty version of the `repr` string of an object.
On the other hand, `pprint` _prints_ a pretty version of the `repr` of an object.
At this point, I feel that is likely that `pprint` makes use of `pretty_repr`.
However, `pretty_repr` doesn't add guidelines:

```py
>>> pretty_repr({42: 73, True: None}, max_width=10)
'{\n    42: 73,\n    True: None\n}'
>>> print(pretty_repr({42: 73, True: None}, max_width=10))
{
    42: 73,
    True: None
}
```


Therefore, it feels like we actually have to write some tests against `pretty_repr`.
Then, we need to find out if there is another auxiliary function that adds the guidelines,
or if it's `pprint` that does that,
because we need to write tests that ensure that guidelines don't get added for custom objects with multi-line representations.
If we find out it is another function's responsibility to add guidelines,
we might as well test against it explicitly.

So, let's write some tests.
First thing to do?
Checkout into a new branch where I'll be fixing the issue:

```bash
git checkout -b 2073-multiline-repr
```


## Test against `pretty_repr`

The first thing I did was create two dummy classes that don't do much,
other than having a `repr` that spans multiple lines.
I wasn't sure about whether I should define those classes at the top level of the test script or inside the test functions themselves,
but I want to use these classes in a couple of different tests,
so I reckon it makes sense to define them in the top level.
Other tests define functions in-line, but those are all different from test to test.

Here are the two classes I defined:

```py
# Define two simples classes used when testing multi-line repr-related things.
class L2:
    def __repr__(self):
        return "X v\n  ^"


class Stair:
    def __repr__(self):
        return "A\n B\n  C\n   D"
```

These two classes look like this when printed:

```py
>>> L2()  # The v and ^ align.
X v
  ^
>>> Stair()  # The letters create a stair.
A
 B
  C
   D
```

Then, I created a test that checks that each of these,
when inside a container,
forces the container to span multiple lines:

```py
def test_multiline_repr():
    """Test multi-line representations force containers to span multiple lines."""

    test_dict = {"key": L2()}
    result = pretty_repr(test_dict)
    assert result == "{\n    'key': X v\n             ^\n}"

    test_list = [Stair()]
    result = pretty_repr(test_list)
    assert result == "[\n    A\n     B\n      C\n       D\n]"
```

If we print the strings I am checking `result` against, we see this:

```py
>>> print("{\n    'key': X v\n             ^\n}")
{
    'key': X v
             ^
}
>>> print("[\n    A\n     B\n      C\n       D\n]")
[
    A
     B
      C
       D
]
```

Therefore, we are checking that `pretty_repr` indents everything correctly.

Then, I added another test to make sure that the fact that the containers have other elements doesn't throw off the function `pretty_repr`:

```py
def test_multiline_repr_busy_container():
    """Test multi-line representations still work in containers with other objects."""

    test_dict = {73: None, True: L2()}
    result = pretty_repr(test_dict)
    assert result == "{\n    73: None,\n    True: X v\n            ^\n}"

    test_list = [Stair(), 5.6]
    result = pretty_repr(test_list)
    assert result == "[\n    A\n     B\n      C\n       D,\n    5.6\n]"

    test_tuple = ("Yes", L2(), Stair(), "No")
    result = pretty_repr(test_tuple)
    assert (
        result
        == "(\n    'Yes',\n    X v\n      ^,\n    A\n     B\n      C\n       D,\n    'No'\n)"
    )
```

In that test, we create a dictionary that has a multi-line `repr` object at the end,
a list with a multi-line `repr` object at the beginning,
and a tuple with a couple of multi-line `repr` objects in the middle.

Printing the strings we are comparing against, we see this:

```py
>>> print("{\n    73: None,\n    True: X v\n            ^\n}")
{
    73: None,
    True: X v
            ^
}
>>> print("[\n    A\n     B\n      C\n       D,\n    5.6\n]")
[
    A
     B
      C
       D,
    5.6
]
>>> print("(\n    'Yes',\n    X v\n      ^,\n    A\n     B\n      C\n       D,\n    'No'\n)")
(
    'Yes',
    X v
      ^,
    A
     B
      C
       D,
    'No'
)
```

We have some tests set up.
Now, we can run them:

```bash
pytest tests/test_pretty.py
```

If we do that, we get three spectacular fails:
the tests we just wrote all fail, as expected!

I went ahead and committed these new tests to the new branch.
These tests don't cover all the things we said we wanted.
We still need to make sure that the multi-line `repr`s don't get vertical guidelines.
However, I don't want to write tests for that yet.
First, I want to discover where those vertical guidelines get added.

Now, we can either do that, or we can start fixing `pretty_repr` to return the appropriate pretty representations.

I'll tackle that soon!


[will]: https://twitter.com/willmcgugan
[gh-2073]: https://github.com/Textualize/rich/issues/2073
[gh-first-findings]: https://github.com/textualize/rich/issues/2073#issuecomment-1105123499
