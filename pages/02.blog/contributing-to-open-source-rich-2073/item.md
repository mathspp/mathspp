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
and I might make educated guess that might turn out to be wrong.
We'll see!)


# Testing my hypothesis

To check if I am not completely wrong here,
I'll create a very simple class that has a multi-line representation.
Then, I'll try to pretty-print it with rich and see what happens.

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

If my make my terminal less wide, the output is a bit less awkward:

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
but isn't valid Python code.

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


[will]: https://twitter.com/willmcgugan
[gh-2073]: https://github.com/Textualize/rich/issues/2073
[gh-first-findings]: https://github.com/textualize/rich/issues/2073#issuecomment-1105123499
