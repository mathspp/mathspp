---
title: "Neural networks fundamentals with Python – network & loss"
---

In the second article of this short series we will create a class
for a generic neural network and we will also see how to assess
the quality of the output of a network, essentially preparing
ourselves to implement the backpropagation algorithm.

===

![A nice image with blue and purple lights.](_thumbnail.png "Original photo by JJ Ying on Unsplash.")


## Purpose of this article

In this article we want to create a class that represents a generic neural network,
which will build up on the `Layer` class we created [in the first article][part1]
of the series: this class should provide some methods that allow to deal with a whole
network, like feeding it some input and getting the final network output
(just like the little demo we included in our script from the previous article).

After creating such a representation, we will be dealing with the concept of loss:
the way in which we assess how a neural network is performing, and an essential
concept we need if we want our neural network to *learn*.

!!! The code for this article, and for the all articles of the series,
!!! can be found in [this GitHub repository][gh-nnfwp].
!!! We will build upon [v0.1][gh-nnfwp-v0_1] of that code.
!!!
!!! If you need a refresher on what we built last time, have a quick read
!!! [at the previous article][part1].


## Neural network as a chain of layers

In the [previous article][part1] we implemented a `Layer` class and then proceeded
to show how several layer instances could be chained as long as their input
and output dimensions matched.
This is the main characterisation of a neural network:
a sequence of layers that receives some information as input, processes it
over its several layers, and then produces some output.

Aggregating these layers as a single object will make it easier for us to reason
about the neural network as a single entity, instead of having to constantly
deal with several layers.

For that matter, to define a `NeuralNetwork` we need as little as the
sequence of layers that composes it:

```py
class NeuralNetwork:
    """A series of connected, compatible layers."""
    def __init__(self, layers):
        self._layers = layers
```

Of course, it might be a good idea to do a single check at this point,
to see if the layers are compatible with each other:

```py
class NeuralNetwork:
    """A series of connected, compatible layers."""
    def __init__(self, layers):
        self._layers = layers

        # Check layer compatibility
        for (from_, to_) in zip(self._layers[:-1], self._layers[1:]):
            if from_.outs != to_.ins:
                raise ValueError("Layers should have compatible shapes.")
```

After defining the object that holds all our layers, and ensuring
the layers are compatible, we can implement the forward pass method
of the network: the method that takes network inputs and then propagates
that information forward, until the network produces some output.

Because we already have a `forward_pass` method on the `Layer` object,
all we need to do is feed the output of a layer as the input to the
next:

```py
class NeuralNetwork:
    # ...

    def forward_pass(self, x):
        out = x
        for layer in self._layers:
            out = layer.forward_pass(out)
        return out
```

We can now try to use this generic object to reproduce the demo
from the last article:

```py
if __name__ == "__main__":
    """Demo of a network as a series of layers."""
    net = NeuralNetwork([
        Layer(2, 4, leaky_relu),
        Layer(4, 4, leaky_relu),
        Layer(4, 1, leaky_relu),
    ])

    x = np.random.uniform(size=(2, 1))
    output = net.forward_pass(x)
    print(output)
```

We can run the script and see it works:

```py
 > python nn.py
[[-0.06479146]]
```

We can also try to create a network where consecutive layers aren't
compatible, to see our sanity check in action:

```py
>>> NeuralNetwork([Layer(2, 4, leaky_relu), Layer(5, 1, leaky_relu)])
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "<stdin>", line 8, in __init__
ValueError: Layers should have compatible shapes.
```

The example network above takes 2 inputs and transforms them into
4 intermediate values, but the next layer is expecting 5 inputs, so
the two layers are incompatible with each other.


## Assessing performance

When we are trying to get a neural network to learn how to do something,
for example when we want a network to learn how to recognise handwritten
digits, we need a way to look at whatever output a network is generating
and measuring the success the network is having.
The concept of *loss* is exactly that: we give a score to the network's
output, which measures how wrong the network is.
In this way, a neural network that was “perfect” would have 0 loss
and a network with a larger loss is a network that is making mistakes.

The loss can be computed in several different ways, and it is generally
a function that receives two inputs:
the output produced by the network and the output the network should have
produced.
That is, in order to compute the loss we actually need to know what is the
correct output for the input we just gave to our network, and this is why
neural networks need *training data*: several pairs of inputs and the
correct outputs, so that we can feed the inputs to the network and compare
the results to the expected outputs, through means of computing a loss.

A common example of a loss function is the mean squared error (MSE) function:
it takes two vectors of numbers, computes the differences element by
element (the errors), squares those errors and then computes their mean.
Once again, code speaks louder than my words:

```py
def mean_squared_error(values, expected):
    """Mean squared error between two arrays."""
    return np.mean((values - expected)**2)
```

The distinction between the values we got and the expected values is
quite important, actually.
When we do the maths to check how a network learns, it is very important
to know if the network output is on the right or on the left of that
subtraction.
The distinction is not relevant when computing the actual loss, though:

```py
>>> x = np.random.uniform(size=4)     
>>> x
array([0.69160359, 0.47572945, 0.52957846, 0.88948501])
>>> y = np.random.uniform(size=4)
>>> y
array([0.83473472, 0.39677264, 0.67969412, 0.41577822])
>>> mean_squared_error(x, y)
0.06841338311075142
>>> mean_squared_error(y, x)  # Swap x with y
0.06841338311075142
```

The distinction is only relevant later on.

There are a variety of loss functions, and the most suitable one depends
on the task you are doing, so our network should be able to take a loss
function as argument upon initialisation, to allow for customisation:

```py
class NeuralNetwork:
    """A series of connected, compatible layers."""
    def __init__(self, layers, loss):
        self._layers = layers
        self._loss_function = loss
        # ...
```

After that, we just need to set up a way for the network to compute its
loss:

```py
class NeuralNetwork:
    # ...

    def loss(self, values, expected):
        return self._loss_function(values, expected)
```

This may seem like an unnecessary intermediate step, having this
function that just calls another function, but we will see now
that this is just us preparing for what comes next:
teaching a neural network to do something.


## Teaching a neural network..?

How do neural networks learn?
The really short answer is:
you give it training data (inputs paired with the expected outputs), compute the
loss of the network and change the weights and bias in a way that decreases the loss.

The short, math-y answer is:
you give it training data (inputs paired with the expected outputs) and apply gradient
descent to tune the weight matrices and bias vectors, that is, you differentiate the
loss function with respect to the weights and bias, and then update the weights
and bias in the direction contrary to that of the derivative.

The great answer is:
check out [3b1b's videos][3b1b-nn], where he explains everything in a very visual
way.

Here is my shot at an explanation that overlooks some details:
mathematics has a tool to analyse functions, called differentiation.
When we differentiate a function and give it a value, we get another value that
measures how strongly the original function is increasing.
For example, if $f$ is a function and $f'$ is its derivative,
and if $g$ is another function and $g'$ is its derivative,
and if we apply the derivatives to some point, say $3$, and we get

$$
    f'(3) = 1, ~ g'(3) = 10 ~~~,
$$

then that means the function $g$ is growing more than the function $f$
around the value $3$, because $10 > 1$.
It *does not* mean $g$ is greater than $f$ at that point, it means
$g$ is *growing faster* than $f$ around that point.

These numbers that, in a way, measure how much a function is growing,
can actually be interpreted in a different way:
if the derivative of $f$ is positive at $3$, it means that if you
increase $3$ ever so slightly, then the value of $f$ should increase;
if the derivative of $f$ is negative at $3$, then it means that if
you decrease $3$ ever so slightly, then the value of $f$ should increase.
That is, the sign of the derivative tells you the direction in which
you have to go if you want to increase $f$...

So...

If you walk ever so slightly in *opposite* direction, the function
is likely to go down...
So let us do that!
If we differentiate the loss function (the function measuring our success),
and if we walk ever so slightly in the *opposite* direction of the derivative,
then we hope that the loss function goes down as well (even if just a little bit).

Of course, because neural networks are fairly complex, computing this derivative
can also be complicated.
Thankfully, we will be using a nice algorithm to simplify our code, but it doesn't
mean that the mathematical justification for it isn't cumbersome to go through.
For now, let us just agree on the following:
in order to compute these derivatives, we will need to know how to differentiate
the activation functions and the loss function we use.


### Differentiating the Leaky ReLU

The Leaky ReLU is a function built from two other linear functions, and those are
easy to differentiate from the mathematical point of view.
If we turn a blind eye to the case that corresponds to having an input
exactly equal to zero, we can describe the derivative of the Leaky ReLU as follows:
if the input is positive, the Leaky ReLU behaves as $f(x) = x$, for which the
derivative is just $1$;
if the input is negative, the Leaky ReLU behaves as $f(x) = \alpha x$,
for which the derivative is just $\alpha$.

If $f(x)$ is the Leaky ReLU, then $f'(x)$ is its derivative and we can
(more or less) write

$$
f'(x) = \begin{cases}
    1, ~ \text{if} ~ x > 0 \\
    \alpha, ~ \text{if} ~ x \leq 0
\end{cases}
$$

Therefore, the derivative of the Leaky ReLU can be coded, for example, as

```py
def d_leaky_relu(x, leaky_param=0.1):
    """Derivative of the Leaky ReLU function."""
    return np.maximum(x > 0, leaky_param)
```

and an example usage:

```py
>>> x = np.array([-2, -1, 3, 4])
>>> x
array([-2, -1,  3,  4])
>>> d_leaky_relu(x)
array([0.1, 0.1, 1. , 1. ])
```


### Differentiating the MSE Loss

The MSE Loss is a quadratic function, which is also fairly easy to differentiate.
The subtlety here lies in understanding that our MSE Loss function takes two inputs,
the values we got and the expected, or reference, values, but we only need to
differentiate with respect to the values we got.
In other words, we are only interested in knowing how the values we compute actually
influence the loss.
The expected values are fixed and there is no point in seeing how those would make
the loss bigger or smaller, as we can't really touch them.

If we write our MSE Loss function as $L(x, t)$, where $x$ is for the actual values
and $t$ is for the reference values, and if both these vectors have $n$ components,
then we implemented $L(x, t)$ as

$$
    \frac1n \sum_{i=1}^n (x_i - t_i)^2 ~~~.
$$

This means that the derivative of $L$, with respect to $x$, will be a vector with
the same shape of $x$, and with each element representing how much $x_i$ influences
the loss:

$$
    \frac{\delta L}{\delta x_i} = \frac2n (x_i - t_i) ~~~.
$$

Essentially, this means that computing the derivatives of the loss function boils down to
the following code:

```py
def d_mean_squared_error(values, expected):
    """Derivative of the mean squared error with respect to the computed values."""
    return 2*(values - expected)/values.size
```

and an example usage:

```py
>>> x = np.array([1,2,3,4])
>>> x
array([1, 2, 3, 4])
>>> t = np.zeros(shape=4)
>>> t
array([0., 0., 0., 0.])
>>> d_mean_squared_error(x, t)
array([0.5, 1. , 1.5, 2. ])
```


## Keeping derivatives together with their functions

We have coded the derivatives of the Leaky ReLU and the MSE, because those
are the activation and loss functions we have been using.
When we implement more activation functions and more loss functions, we will
also need to implement their derivatives, and when we use them inside
the neural network, we need to be sure that we are using the derivatives
that match the functions we used.

For that matter, we can introduce two simple generic classes that specify
this behaviour, and that we proceed to inheriting when defining
activation or loss functions.
That way, the derivative is always paired with the original function.

For example, for activation functions we can do the following:

```py
from abc import ABC, abstractmethod

class ActivationFunction:
    """Class to be inherited by activation functions."""
    @abstractmethod
    def f(self, x):
        """The method that implements the function."""
        pass

    @abstractmethod
    def df(self, x):
        """Derivative of the function with respect to its input."""
        pass
```

Then, we just need to inherit this `ActivationFunction` and reuse
the two functions we already have to define the Leaky ReLU:

```py
class LeakyReLU(ActivationFunction):
    """Leaky Rectified Linear Unit."""
    def __init__(self, leaky_param=0.1):
        self.alpha = leaky_param

    def f(self, x):
        return np.maximum(x, x*self.alpha)

    def df(self, x):
        return np.maximum(x > 0, self.alpha)
```

We do a similar thing for the loss function:

```py
class LossFunction:
    """Class to be inherited by loss functions."""
    @abstractmethod
    def loss(self, values, expected):
        """Compute the loss of the computed values with respect to the expected ones."""
        pass

    @abstractmethod
    def dloss(self, values, expected):
        """Derivative of the loss with respect to the computed values."""
        pass

class MSELoss(LossFunction):
    """Mean Squared Error Loss function."""
    def loss(self, values, expected):
        return np.mean((values - expected)**2)

    def dloss(self, values, expected):
        return 2*(values - expected)/values.size
```

The final thing we need to do is update the remainder of the code to reflect these changes.
In short, we only need to make sure that a `Layer` calls the `f` method of an activation function

```py
class Layer:
    # ...
    def forward_pass(self, x):
        """Compute the next set of neuron states with the given set of states."""
        return self.act_function.f(np.dot(self._W, x) + self._b)
```

and that the `loss` method of a `NeuralNetwork` calls the `loss` method of the loss function:

```py
class NeuralNetwork:
    # ...
    def loss(self, values, expected):
        return self._loss_function.loss(values, expected)
```


## New demo

With all these changes, we should create a new short demo for our network:

```py
if __name__ == "__main__":
    """Demo of a network as a series of layers."""
    net = NeuralNetwork([
        Layer(2, 4, LeakyReLU()),
        Layer(4, 4, LeakyReLU()),
        Layer(4, 1, LeakyReLU()),
    ], MSELoss())

    x = np.random.uniform(size=(2, 1))
    print("Input is:", x)
    output = net.forward_pass(x)
    print("Output is:", output)
    # Ensure "expected" output is a column
    print("Loss is:", net.loss(output, np.array(0, ndmin=2)))
```

An example run of this program produces the following output:

```py
 > python nn.py
Input is: [[0.30963176]
 [0.80997111]]
Output is: [[-0.02405259]]
Loss is: 0.0005785269049728341
```

## Current code

As of now, I have a file that spans for 106 lines.
You can find all the code for this series in [this GitHub repository][gh-nnfwp].
The code that corresponds to the end of this article is available [under the tag v0.2][gh-nnfwp-v0_2].

In the next article we will be putting together all the derivatives
in what is usually referred to as the *backpropagation algorithm*, which is the algorithm
that allows networks to “learn”.
That will be a math-y and lengthy article, for just a few lines of code in the end...

## The series

These are all the articles in this series:

<ol>
{% for article in taxonomy.findTaxonomy({"tag": ["nnfwp"]}).order("date") %}
    <li><a href="{{ article.url }}">{{ article.title }}</a></li>
{% endfor %}
</ol>

[part1]: /blog/neural-networks-fundamentals-with-python-intro
[3b1b-nn]: https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi
[3b1b-nn1]: https://www.youtube.com/watch?v=aircAruvnKk
[gh-nnfwp]: https://github.com/mathspp/NNFwP
[gh-nnfwp-v0_1]: https://github.com/mathspp/NNFwP/tree/v0.1
[gh-nnfwp-v0_2]: https://github.com/mathspp/NNFwP/tree/v0.2
