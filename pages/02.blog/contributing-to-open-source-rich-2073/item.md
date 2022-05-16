This is the story of how I started contributing to Open Source and, in particular, how I tackled issue #2073 of the well-known Python library `rich`, by Will McGugan.

===


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

Finally, we create a test for nested containers:

```py
def test_multiline_repr_nested():
    """Test multi-line representations in nested containers."""

    test_container = (
        L2(),
        {
            73: Stair(),
            "oi": False,
            None: [
                L2(),
                L2(),
            ],
        },
    )
    result = pretty_repr(test_container)
    expected = """
(
    X v
      ^,
    {
        73: A
             B
              C
               D,
        'oi': False,
        None: [
            X v
              ^,
            X v
              ^
        ]
    }
)
""".strip()
    assert result == expected
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


# Tracing through the code

Having written some tests that `pretty_repr` is supposed to pass,
now I need to go and find how the function `pretty_repr` works.

First, I open the file `pretty.py` and do a search for “`pretty_repr`”.
I get a couple of hits, and easily find the definition,
which is really short:

```py
def pretty_repr(...) -> str:
    """Prettify repr string by expanding on to new lines to fit within a given width.
    ...
    """

    if _safe_isinstance(_object, Node):
        node = _object
    else:
        node = traverse(
            _object, max_length=max_length, max_string=max_string, max_depth=max_depth
        )
    repr_str: str = node.render(
        max_width=max_width, indent_size=indent_size, expand_all=expand_all
    )
    return repr_str
```

Even not being aware of what `_safe_isinstance` or `traverse` are supposed to do,
we can see that the second to last line defines a variable `repr_str` that is then returned from the function.
Therefore, the line `repr_str: str = node.render(...)` seems to be the line responsible for creating the string `repr`.
Thus, we need to dive into `node.render`.

From what can be seen a couple of lines above,
it seems like the variable `node` is of type `Node`,
so then I look for the definition of `Node` and check its method `render`:

```py
@dataclass
class Node:
    # ...
    def render(self, ...) -> str:
        """Render the node to a pretty repr.
        ...
        """
        lines = [_Line(node=self, is_root=True)]
        line_no = 0
        while line_no < len(lines):
            line = lines[line_no]
            if line.expandable and not line.expanded:
                if expand_all or not line.check_length(max_width):
                    lines[line_no : line_no + 1] = line.expand(indent_size)
            line_no += 1

        repr_str = "\n".join(str(line) for line in lines)
        return repr_str
```

Again, it's not a very long definition and it is surprisingly easy to read,
even for someone who doesn't really know what's happening.

By reading the code a couple of times in a row, you get to understand a bit more each time you read:

 1. First, there is a list called `lines` that is initialised with a single instance of `_Line`, whatever that is.
 Then, there is a `while` loop that seems to fiddle with that list,
 and in the end we build `repr_str` by doing a `"\n".join(...)` on what's inside the list `lines`.

So, it seems like the list `lines` will be holding the lines that we want to print out in the pretty representation.

 2. Next, we see that the `while` loop is controlled by a variable `line_no` that is checked against the length of `lines`.
 Also, the first statement of the `while` loop is the assignment `line = lines[line_no]`.
 This really makes it clear that the list `lines` is going to grow in size during the loop,
 otherwise the final `"\n".join()` wouldn't be very interesting.

 3. Afterwards, you see two consecutive conditional statements.
 They all pair nicely with what is being done to the variable `line`:

```py
# ...
if line.expandable and not line.expanded:
    if expand_all or not line.check_length(max_width):
        ... = line.expand(indent_size)
```

Inside the two `if` statements, we do a `line.expand`, whatever “expand” means...
But recall that we are building a string representation of a potentially complex object.
This initial line feels like it may be just a very condensed representation of such an object,
and we would like to see if we can use some more space (some more lines) to print more information about the object.
This conjecture is supported by the fact that we check if:

 - the line can be expanded into something more useful (`line.expandable`); and
 - the line hasn't been expanded already (`and not line.expanded`).

Then, we check if:

 - we specified that everything should be expanded (`if expand_all`); or
 - if the current line is too long for what was specified (`not line.check_length(max_width)`).

If the two conditional statements pass,
then we call `.expand` on the current line and assign it to the current list of lines:

```py
lines[line_no : line_no + 1] = line.expand(indent_size)
```

The expression `lines[i : i + 1]` returns a (slice) list that only contains the current line being looked at.
When we assign to it, we get to replace that single line with a larger list of lines.
Here is an example with integers:

```py
>>> l = [1, 0, 5]
>>> l[1:2]
[0]
>>> l[1:2] = [2, 3, 4]
>>> l
[1, 2, 3, 4, 5]
```

Now, we need to understand what the method `expand` does on objects of the type `_Line`.
Reading the docstring for the method `_Line.expand`, we find this:

 > “Expand this line by adding children on their own line.”

What I understand by this is that if I have something like a dictionary in a single line,
the method `.expand` is likely to take my key, value pairs and put them on their own lines.
From what I can gather, the class `_Line` is representing the new line in which the output of a single node (and its children) goes,
so maybe this isn't where I want the modifications to go.

By the end of the method `Node.render`, we have these two final lines:

```py
repr_str = "\n".join(str(line) for line in lines)
return repr_str
```

The way I read this is:

 > “Convert all instances of `_Line` to actual strings, join them by newlines, and return that.”

So, it might be that I need to change the way instances of `_Line` are converted to strings.

Next up? Messing with the code.


# Messing with the code

The first thing I did was to go to the end of the code,
where you can find the conditional statement `if __name__ == "__main__":`,
and replace the code underneath.

I got rid of all of it, and wrote this instead:

```py
if __name__ == "__main__":

    class Stair():
        def __repr__(self):
            return "A\n B\n  C\n   D"

    s = Stair()
    d = {73: 42, "carlota": s}
    f = {True: False, 0: d}
    print(pretty_repr(s))
    print(pretty_repr(d))
    print(pretty_repr(f))
```

! If you are unsure about what `if __name__ == "__main__":` does,
! [I wrote about the dunder attribute `__name__` and the behaviour of `if __name__ == "__main__":`][pydont-main] in a previous article.

By replacing the code under `if __name__ == "__main__":` I can run the command

```bash
python -m rich.pretty
```

to play around with my changes quickly.


## Indentation

Let's see if we can fix the indentation of the successive lines,
and we'll handle whatever issues pop up along the way.

The first thing I tried to do was, inside `_Line.__str__`,
check if the instance contained a node that had a multi-line string representation:

```py
def __str__(self) -> str:
    if "\n" in str(self.node):
        print(f"Found something {self.node = }")
    if self.last:
        return f"{self.whitespace}{self.text}{self.node or ''}"
    else:
        return (
            f"{self.whitespace}{self.text}{self.node or ''}{self.suffix.rstrip()}"
        )
```

Running the module (with `python -m rich.pretty`),
I got some output that showed that I “found something”, once for each example I wrote down.
That means that, at this point, I can know if an instance of `_Line` will span multiple lines
because it contains the character `"\n"`.

Then, I thought that if I can know that a node will span over multiple lines,
I can try to fix the indentation issue at this point.
These thoughts were also motivated by the fact that the method `_Line.__str__` makes use of attributes like `self.whitespace` and `self.suffix`,
which seem to be things that can go before/after the actual node representation.
So, if I want to fix the indentation issue, I will have to split the multi-line node and add enough indentation before each line that is not the first one.
This was my attempt:

```py
def __str__(self) -> str:
    node_str = str(self.node or "")
    if "\n" in node_str:
        print(f"Found something {self.node = }")
        spaces = len(self.whitespace) + len(self.text)
        node_str = ("\n" + " " * spaces).join(node_str.split("\n"))
    if self.last:
        return f"{self.whitespace}{self.text}{node_str}"
    else:
        return (
            f"{self.whitespace}{self.text}{node_str}{self.suffix.rstrip()}"
        )
```

By first converting the node to a string with `str(self.node or "")`,
I handle the general case together with the case when `self.node` is `None`.
Then, I count the length of `self.whitespace` and `self.text` because that is likely to be the length of the extra indentation I need.
I'm assuming this because the method `_Line.__str__` currently puts `self.whitespace` and `self.text` behind the node.
So, if the node spans over multiple lines, the first line is indentend by the length of `self.whitespace` and `self.text`,
whereas the other lines currently are not.
We can fix this.

By splitting the string representation of the node over the character newline `"\n"` and then joining with a newline _and_ as many indentation spaces as needed,
we achieve the net effect of indenting all lines except the first one.
The first line is indented naturally in the f-string that was already there.

Let's run the code and see if we get any indentation!
But we don't...

I scratched my head for a bit and then conjectured that nothing was happening because the dictionaries were not being expanded.
To test this conjecture, I simply added `expand_all=True` to one of the calls to `pretty_repr`:

```py
print(pretty_repr(d, expand_all=True))
```

With the boolean flag `expand_all=True`,
we got an output that was slightly more interesting:

```txt
{
    73: 42,
    'carlota': A
     B
      C
       D
}
```

Hooray 🎉!
Progress!

But we can see that the indentation isn't being correctly calculated yet.
In this case, we also have to account for the length we need to represent the key:

```py
def __str__(self) -> str:
    node_str = str(self.node or "")
    if "\n" in node_str:
        print(f"Found something {self.node = }")
        spaces = len(self.whitespace) + len(self.text) + len(self.node.key_repr)
        # ...
```

But then, MyPy complains that `self.node` might be `None`,
and thus may not have the attribute `self.node.key_repr`.
Fair.
However, if we are inside the branch `if "\n" in node_str`,
we are 100% sure that `self.node` was not `None`,
so we can safely assert that `self.node is not None` and MyPy will stop complaining:

```py
def __str__(self) -> str:
    node_str = str(self.node or "")
    if "\n" in node_str:
        assert self.node is not None
        print(f"Found something {self.node = }")
        spaces = len(self.whitespace) + len(self.text) + len(self.node.key_repr)
```

Running the corrected version, we get the wrong output again:

```py
{
    73: 42,
    'carlota': A
              B
               C
                D
}
```

We missed by less, but it's still wrong.
The issue was a silly oversight:
if there is a key,
there is also the `": "` that we have to account for.

Looking at the definition of the class `Node`,
we see a series of attributes that we may or may not need to account for:

```py
@dataclass
class Node:
    """A node in a repr tree. May be atomic or a container."""

    key_repr: str = ""
    value_repr: str = ""
    open_brace: str = ""
    close_brace: str = ""
    empty: str = ""
    last: bool = False
    is_tuple: bool = False
    is_namedtuple: bool = False
    children: Optional[List["Node"]] = None
    key_separator = ": "
    separator: str = ", "

    # ...
```

After taking a look at these attributes and checking both `Node.__str__` and `Node.iter_tokens`
(on which `Node.__str__` relies heavily),
I concluded that I only need to worry about `self.key_repr` and `self.key_separator`.
(But I might be wrong!)

Thus, I ended up modifying `_Line.__str__` again,
and it ended up looking like this:

```py
def __str__(self) -> str:
    node_str = str(self.node or "")
    if "\n" in node_str:
        assert self.node is not None
        print(f"Found something {self.node = }")
        spaces = len(self.whitespace) + len(self.text)
        if self.node.key_repr:
            spaces += len(self.node.key_repr) + len(self.node.key_separator)
        node_str = ("\n" + " " * spaces).join(node_str.split("\n"))
    if self.last:
        return f"{self.whitespace}{self.text}{node_str}"
    else:
        return (
            f"{self.whitespace}{self.text}{node_str}{self.suffix.rstrip()}"
        )
```

If we run the code again,
we get the correct output:

```py
{
    73: 42,
    'carlota': A
                B
                 C
                  D
}
```


## Automatic expansion

It seems like the indentation is fixed,
but I only got the indentation right because I forced `pretty_repr` to expand everything.
The next step needs to address one of the things I said I wanted:

 > “Containers that contain objects with multi-line representations should automatically display one element per line.”

This probably means I need to change the method `Node.render`,
because it's where we could find references to “expansion” and “expanding things”.
Here is the current method:

```py
def render(
    self, max_width: int = 80, indent_size: int = 4, expand_all: bool = False
) -> str:
    """Render the node to a pretty repr.
    # ...
    """
    lines = [_Line(node=self, is_root=True)]
    line_no = 0
    while line_no < len(lines):
        line = lines[line_no]
        if line.expandable and not line.expanded:
            if expand_all or not line.check_length(max_width):
                lines[line_no : line_no + 1] = line.expand(indent_size)
        line_no += 1

    repr_str = "\n".join(str(line) for line in lines)
    return repr_str
```

It feels like the appropriate place to ensure that things get expanded is the second `if` statement.
The first one checks if the line can be expanded and if it hasn't been expanded yet.
_Then_, we check if we have to `expand_all` (which I manually set to `True` previously)
or if the current line doesn't fit within the maximum width `max_width` set.

Much like we have a method `_Line.check_length` to make sure that the given length fits the maximum width,
it feels like I could add a method `_Line.check_multiline` that checks if the instance of `_Line` has a representation that spans multiple lines.

Let's add that next to `_Line.check_length`,
because it was `_Line.check_length` that informed my implementation of `_Line.check_multiline`:

```py
    def check_length(self, max_length: int) -> bool:
        """Check this line fits within a given number of cells."""
        start_length = (
            len(self.whitespace) + cell_len(self.text) + cell_len(self.suffix)
        )
        assert self.node is not None
        return self.node.check_length(start_length, max_length)

    def check_multiline(self) -> bool:
        """Check if the line actually contains multiline text."""
        assert self.node is not None
        return "\n" in self.text or self.node.check_multiline()
```

Now, obviously I have to implement the method `Node.check_multiline`,
but there is also a method `Node.check_length` that can inform my implementation:

```py
def check_length(self, start_length: int, max_length: int) -> bool:
    """Check the length fits within a limit.

    Args:
        start_length (int): Starting length of the line (indent, prefix, suffix).
        max_length (int): Maximum length.

    Returns:
        bool: True if the node can be rendered within max length, otherwise False.
    """
    total_length = start_length
    for token in self.iter_tokens():
        total_length += cell_len(token)
        if total_length > max_length:
            return False
    return True

def check_multiline(self) -> bool:
    """Check if the node spans multiple lines.

    Returns:
        bool: True if the node spans two or more lines, otherwise False.
    """
    return any("\n" in token for token in self.iter_tokens())
```

After implementing these checks, we need to add them to the method `Node.render`:

```py
    def render(
        self, max_width: int = 80, indent_size: int = 4, expand_all: bool = False
    ) -> str:
        """Render the node to a pretty repr.
        # ...
        """
        lines = [_Line(node=self, is_root=True)]
        line_no = 0
        while line_no < len(lines):
            line = lines[line_no]
            if line.expandable and not line.expanded:
                if (
                    expand_all
                    or not line.check_length(max_width)
                    or line.check_multiline()
                ):
                    lines[line_no : line_no + 1] = line.expand(indent_size)
            line_no += 1

        repr_str = "\n".join(str(line) for line in lines)
        return repr_str
```

After deleting the statement `print` and running the code,
we get some good looking output:

```py
A
 B
  C
   D
{
    73: 42,
    'carlota': A
                B
                 C
                  D
}
{
    True: False,
    0: {
        73: 42,
        'carlota': A
                    B
                     C
                      D
    }
}
```

This looks pretty promising!
Let's run the tests:

```bash
λ pytest tests/test_pretty.py
========================================= test session starts ==========================================
platform win32 -- Python 3.9.7, pytest-7.0.1, pluggy-1.0.0
rootdir: C:\Users\rodri\Documents\Programming\rich\tests, configfile: pytest.ini
plugins: cov-3.0.0
collected 44 items

tests\test_pretty.py ........................................s...                                 [100%]

==================================== 43 passed, 1 skipped in 0.56s ===================================== 
```

Great success!
All tests passed, including the ones I wrote earlier!
This is a decent indication that I _didn't_ break anything and that I made progress!

At this point, I [drafted a pull request][pr] and asked a couple of questions that will help me.
After all, there are three objectives for these changes, and I haven't achieved one of them:

 > “Multi-line representations should have no vertical guidelines added to it.”

I will wait for the reply to my comment on the [PR][pr] and then I'll keep on writing about my adventure.

See you soon!


[pydont-main]: /blog/pydonts/name-dunder-attribute#the-module-attribute-__name__
[will]: https://twitter.com/willmcgugan
[gh-2073]: https://github.com/Textualize/rich/issues/2073
[gh-first-findings]: https://github.com/textualize/rich/issues/2073#issuecomment-1105123499
[pr]: https://github.com/Textualize/rich/pull/2267#issuecomment-1126747409
