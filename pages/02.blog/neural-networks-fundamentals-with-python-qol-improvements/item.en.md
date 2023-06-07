---
title: "Neural networks fundamentals with Python – QoL improvements"
---

In this article of the [NNFwP series][series] we will work
on some quality-of-life improvements to the neural nets framework.

===

![A nice image with blue and purple lights.](_thumbnail.png "Original photo by JJ Ying on Unsplash.")


# Purpose of this article

The purpose of this article is to go over the core of our code
and implement a couple of helpful enhancements that will
make the code easier to use.

!!! The code for this article, and for the all articles of the series,
!!! can be found in [this GitHub repository][gh-nnfwp].
!!! This article will build upon [v1.3][gh-nnfwp-v1_3] of that code.


# Factoring out utility functions

The very first thing we need to do is to clean up the utility functions
that we have duplicated in our `examples/` scripts.
That shouldn't be too much trouble and will make sure that the code is easy to reuse.

Create a blank file, `utils.py`, and let's get cleaning.


## `load_data`

First up is the `load_data` function that goes in `utils.py`:

```py
# In utils.py
import csv
import numpy as np


def load_data(filepath, delimiter=",", dtype=float):
    """Load a numerical numpy array from a CSV file."""

    with open(filepath, "r") as f:
        data_list = list(csv.reader(f, delimiter=delimiter))
    return np.asarray(data_list, dtype=dtype)
```

Then, we can delete that function from all the `examples/` scripts.
In exchange, we just need to add a little import to the top:

```py
# In examples/mnist.py and examples/mnist_small.py
from utils import load_data
```

## `train` and `test`

The next step is having the `train` and `test` functions be helper functions as well.
For that, we need to figure out what is the signature that we want these two functions
to have, and then we just make the change.

Let us agree that both functions take the network,
then the input data, and then the third argument is either the target outputs,
for the `train` function, or the true labels, for the `test` function.
On top of that, we need to decide what is the format of the data that these
two functions expect, but we can opt for something that isn't too strict:
let's just say that the input data and the output/targets need to come in an iterable,
so that we can write a simple `for` loop to traverse that data.

Having decided for this structure, let us refactor the `train` function first.

At this point in time, here is the `train` function from `examples/mnist.py`:

```py
# From `examples/mnist.py`
def train(net, train_data):
    # Precompute all target vectors.
    ts = {}
    for t in range(10):
        tv = np.zeros((10, 1))
        tv[t] = 1
        ts[t] = tv

    for i, train_row in enumerate(train_data):
        if not i % 1000:
            print(i)

        t = ts[train_row[0]]
        x = to_col(train_row[1:])
        net.train(x, t)
```

We agreed that the parameters are `net`, `inputs`, and `target_outs`,
so we can change the signature of the function:

```py
def train(net, inputs, target_outs):
    pass
```

Now, because we are trying to clean things up, we better take this opportunity
to write a good docstring for our function.
We should've done right from the start, but we were in a hurry.
We can't allow it to be any later than now, so here we go:

```py
def train(net, inputs, target_outs):
    """Train a network with the given inputs and target outputs.

    The network must be an instance of `NeuralNetwork`,
    the `inputs` must be an iterable with successive inputs to the network
    and the `target_outs` should be the respective network target outputs.
    """
    pass
```

The next step is rewriting the actual body function.
For that, we have to do some cleaning up.
In a sense, we just need to strip the above implementation of
all that is superfluous, and keep the minimum to implement
the functionality we just described:

```py
def train(net, inputs, target_outs):
    """Train a network with the given inputs and target outputs.

    The network must be an instance of `NeuralNetwork`,
    the `inputs` must be an iterable with successive inputs to the network
    and the `target_outs` should be the respective network target outputs.
    """

    for x, t in zip(inputs, target_outs):
        net.train(to_col(x), to_col(t))
```

Now, why do we wrap both `x` and `t` in a call to `to_col`?
Well, just to simplify our lives.
NumPy matrices are iterables, but only row by row,
and we need our inputs and targets to be columns,
so we do that transformation here.
Otherwise we would have to take those matrices and convert them
into a more suitable format every time.

This special treatment isn't obvious from the function name,
so it should be documented somewhere.
Because we don't have a separate documentation centre,
we will add a note in the docstring of the function:

```py
def train(net, inputs, target_outs):
    """Train a network with the given inputs and target outputs.

    The network must be an instance of `NeuralNetwork`,
    the `inputs` must be an iterable with successive inputs to the network
    and the `target_outs` should be the respective network target outputs.
    Prior to being fed to the network, both the input and the target
    are converted to column vectors with a call to `to_col`.
    """

    for x, t in zip(inputs, target_outs):
        net.train(to_col(x), to_col(t))
```

This is it, this is our new `train` function.
We can go ahead, add it to the `utils.py` file,
and delete it from elsewhere.

This sounds cool, the fact that we are deleting duplicated code...
But we should not get ourselves too carried away!
Don't forget that, for the data we have been using,
what we load from the files are the actual digits
that each row contains, whereas we need a column vector of size 10
when we use the `MSELoss`, right?

So, we still need to create the target vectors from those labels.

Thankfully, we can use some NumPy-foo to do that.
To make our lives easier, let's just assume that we will create
a matrix with the targets along the rows, to match the format of the inputs.

When we load the training data, here is how we can extract the inputs:

```py
train_data = load_data(TRAIN_FILE, delimiter=",", dtype=int)
inputs = train_data[:, 1:]
```

What this means is that `inputs` gets all the rows
(that's what the first `:` indicates) and then all the columns,
starting from the column at index `1`.
This leaves column 0 behind, which is where the targets are:

```py
>>> train_data[:, 0]
[5 0 4 ... 5 6 8]
```

As we can see, the first three targets are a five, a zero, and a four,
and the last three targets are a five, a six, and an eight.
With this column, we can the big targets matrix, that should look like the following:

```py
>>> # ???
array([[0., 0., 0., 0., 0., 1., 0., 0., 0., 0.],
       [1., 0., 0., 0., 0., 0., 0., 0., 0., 0.],
       [0., 0., 0., 0., 1., 0., 0., 0., 0., 0.],
       ...
       [0., 0., 0., 0., 0., 1., 0., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 1., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 0., 0., 1., 0.]])
```

We can use ideas from _array-oriented programming_ to build this matrix all at once.
First, we notice that the matrix is mostly zeros, so we build that foundation:

```py
>>> ts = np.zeros((len(train_data), 10))
>>> ts
array([[0., 0., 0., 0., 0., 0., 0., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 0., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 0., 0., 0., 0.],
       ...
       [0., 0., 0., 0., 0., 0., 0., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 0., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 0., 0., 0., 0.]])
```

Then, we know that, for each row in `ts`,
one single element should be a `1`, as indicated by each
successive value in `train_data[:, 0]`.
_Thankfully_, NumPy allows indexing specific locations
of an array by indicating two lists:

 - a list of rows; and
 - the corresponding list of columns.

Now, the list of rows is each successive index,
and we can build all the possible indices with a `range`.
The list of columns, well, that's what `train_data[:, 0]` contains:

```py
>>> ts[range(len(ts)), train_data[:, 0]] = 1
>>> ts
array([[0., 0., 0., 0., 0., 1., 0., 0., 0., 0.],
       [1., 0., 0., 0., 0., 0., 0., 0., 0., 0.],
       [0., 0., 0., 0., 1., 0., 0., 0., 0., 0.],
       ...
       [0., 0., 0., 0., 0., 1., 0., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 1., 0., 0., 0.],
       [0., 0., 0., 0., 0., 0., 0., 0., 1., 0.]])
```

Putting this together, we can now rewrite the `mnist.py` experiment:

```py
# In `examples/mnist.py`
if __name__ == "__main__":
    layers = [
        Layer(784, 16, LeakyReLU()),
        Layer(16, 16, LeakyReLU()),
        Layer(16, 10, LeakyReLU()),
    ]
    net = NeuralNetwork(layers, MSELoss(), 0.001)

    test_data = load_data(TEST_FILE, delimiter=",", dtype=int)
    accuracy = test(net, test_data)
    print(f"Accuracy is {100*accuracy:.2f}%")     # Expected to be around 10%

    train_data = load_data(TRAIN_FILE, delimiter=",", dtype=int)
    inputs = train_data[:, 1:]
    ts = np.zeros((len(inputs), 10))
    ts[range(len(ts)), train_data[:, 0]] = 1
    train(net, inputs, ts)

    accuracy = test(net, test_data)
    print(f"Accuracy is {100*accuracy:.2f}%")
```

# The series

These are all the articles in this series:

<ol>
{% for article in taxonomy.findTaxonomy({"tag": ["nnfwp"]}).order("date") %}
    <li><a href="{{ article.url }}">{{ article.title }}</a></li>
{% endfor %}
</ol>



[series]: /blog/tag:nnfwp
[gh-nnfwp]: https://github.com/mathspp/NNFwP
[gh-nnfwp-v1_3]: https://github.com/mathspp/NNFwP/tree/v1.3
[numba]: http://numba.pydata.org/
[numba-gh]: https://github.com/numba/numba
[numba-install]: https://numba.readthedocs.io/en/stable/user/installing.html
