This tutorial shows how to use a simple genetic algorithm to deduce physics formulas.

===


# Deducing physics formulas with genetic algorithms

## What's a genetic algorithm?

A genetic algorithm is an algorithm that borrows ideas from Darwin's theory of evolution to find solutions to optimisation problems.
So, if you have a problem and if you have a way of determining how good or bad a solution is to that problem, then a genetic algorithm can be used to run a simulation that tries to find better solutions to that problem.

In order to be able to run a genetic algorithm, you need to be able to do a couple of different things:

 1. you need to be able to generate random possible solutions to the problem;
 2. you need a way to quantify how good, or how bad, a given solution is;
 3. you need to have a way to take two solutions and combine them to create a third solution; and
 4. you need to be able to make small random changes to existing solutions.

The steps of combining solutions and mutating solutions are supposed to emulate the natural process of evolution as described by Darwin's theory of evolution.

Step 1. is how the genetic algorithm starts.
You start by creating a number of random solutions, to which you call the _population_.
Each solution is an _individual_.

Then, you repeat steps 2 - 4 a number of times.
For each repetition, you figure out how good each individual in the population is.
You call _fitness_ or _fitness level_ to the value that quantifies how good a solution is.

In Darwin's theory of evolution, the fittest individuals of a species tend to survive and reproduce while the least fit tend to die.
The same thing will happen in our genetic algorithm.
We pick the fittest individuals, let them reproduce, give them a change to mutate, and then we repeat this.


## Deducing a physics formula

Genetic algorithms can be implemented to any type of problem you can think of, as long as you're able to implement the different steps outlined above.
In this article we will try to deduce the formula that determines the position of a body in motion as a function of time, given its initial position, initial velocity, and acceleration.
The formula looks like this:

$$
x(t) = \frac12 a t^2 + v_0t + x_0
$$

In the formula above, we have that

 - $x_0$ is the initial position of the body;
 - $v_0$ is the initial velocity of the body;
 - $a$ is the acceleration of the body;
 - $t$ is the time for which we want to compute the position; and
 - $x(t)$ is the position of the body after $t$ seconds.

In our simulation, we'll have to start by creating random formulas that will be nowhere close to the correct formula shown above.
Then, we'll figure out what are the formulas that better approximate the correct formula, we'll mix and match those formulas, and we'll mutate some of them.
We'll repeat this a number of times and by the end of the simulation we'll hopefully have a formula that is a good approximation to the formula above.

Let's start implementing our algorithm.


## Representing a formula

We're going to represent a formula as a tree in our algorithm.
A mathematical operator will be a node in that tree and it will point to the nodes that represent the operands.
For example, the formula `a + b` could be represented as a tree with a node `+` that points to the leaves `a` and `b`:

```
    +
   / \
  a   b
```

For more complex formulas, the tree grows.
For example, the formula `a + b * c` could be represented by the tree seen below:

```
   +
  / \
 a   *
    / \
   b   c
```

When we represent a formula as a tree, we see that the mathematical operators are always internal nodes of the tree and the leaves are values.

Here's the Python code that will be used for this effect:

```py
class Formula:
    """Base class for formulas represented as trees."""


class Operator(Formula):
    """Base class for binary operators in formulas."""

    def __init__(self, op, left, right):
        self.op = op
        self.left = left
        self.right = right


class Operand(Formula):
    """Base class for operands in formulas."""

    def __init__(self, value):
        self.value = value
```


## Creating a random initial population

As outlined in the beginning, a genetic algorithm starts with the creation of many random individuals.
This means we need to be able to create a random formula and we can only do that if we know what operators and what operands we have available.

For the operators, we'll go with the four arithmetic operators:

```py
import operator

operators = [
    operator.add,
    operator.sub,
    operator.mul,
    operator.truediv,
]
```

For the operands, we'll go with the four variables seen in the formula ($x_0$, $v_0$, $a$, and $t$) plus the constant value `1`:

```py
operands = [
    "x0",
    "v0",
    "a",
    "t",
    1,
]
```

Now, we'll introduce three class methods:

 1. `Formula.new_formula`;
 2. `Operator.new_operator`; and
 3. `Operand.new_operand`.

The class method `Formula.new_formula` will create a random formula and it will use the methods `Operator.new_operator` and `Operand.new_operand` to do so:

```py
import random

# ...

class Formula:
    """Base class for formulas represented as trees."""

    @classmethod
    def new_formula(self, operator_prob=0.5):
        """Generates a random formula."""
        if random.random() < operator_prob:
            return Operator.new_operator(operator_prob / 2)
        else:
            return Operand.new_operand()
```

The parameter `operator_prob` will determine how likely we are to pick an operator versus an operand.
As the formula grows, we'll decrease the value of `operator_prob` so that it becomes more and more likely that we pick an operand (a leaf in our tree) as opposed to another internal node.

Now, we implement the class methods `Operator.new_operator` and `Operand.new_operand`.
To create a random operand we just need to pick a random element from the list `operands`:

```py
class Operand(Formula):
    # ...

    @classmethod
    def new_operand(cls):
        """Generates a random operand."""
        return cls(random.choice(operands))
```

To generate a random `Operator` we need to do a bit more work.
We pick a random mathematical operator from the list `operators` but we also need to generate random formulas for the left and right operands of that operator.
We do this by recursively calling `Formula.new_formula` with [open recursion](/blog/til/open-recursion):

```py
class Operator(Formula):
    # ...

    @classmethod
    def new_operator(cls, operator_prob):
        """Generates a random operator."""
        op = random.choice(operators)
        left = Formula.new_formula(operator_prob)
        right = Formula.new_formula(operator_prob)
        return cls(op, left, right)
```

This is enough to generate random formulas, as you can verify by calling `Formula.new_formula` and printing the result:

```py
# ...

if __name__ == "__main__":
    print(Formula.new_formula())
```

Running my script a couple of times, I got these results:

```
<__main__.Operand object at 0x104fa1f40>
<__main__.Operator object at 0x102382210>
<__main__.Operand object at 0x104229f40>
<__main__.Operator object at 0x100752210>
```

Admittedly, these don't look very interesting.
We can improve the way our formulas look by implementing the [dunder method `__repr__`](/blog/pydonts/str-and-repr):


```py
# ...

op_as_str = {
    operator.add: "+",
    operator.sub: "-",
    operator.mul: "*",
    operator.truediv: "/",
}

# ...

class Operator(Formula):
    # ...

    def __repr__(self):
        return f"({self.left} {op_as_str[self.op]} {self.right})"


class Operand(Formula):
    # ...

    def __repr__(self):
        return str(self.value)
```

If you generate a couple of random formulas and print them you will get the following output:

```
(a - t)
(((1 * v0) - x0) * a)
(t + x0)
v0
```

To achieve our goal of generating a random initial population we can define an auxiliary function that uses a [list comprehension](/blog/pydonts/list-comprehensions-101) to generate as many formulas as needed:

```py
def initialise_population(n, operator_prob=0.5):
    """Create an initial population of `n` random formulas."""
    return [Formula.new_formula(operator_prob) for _ in range(n)]
```


## Determining the fitness of an individual

### How good is a formula?

Now that we have individuals (formulas), we need a way to determine how good or bad a given individual is.

To determine if a given formula is a good approximation of the formula we're interested in, we'll apply the formulas to a series of data points and then we'll see how far off the formula is from the correct result.

In other words, we'll have a series of (fake) measurements of the phenomenon we're trying to model.
In our case, we're talking about the displacement of a moving object.
We'll apply the formula to the fake measurements and we'll see if the result of the formula is close to the actual result or not.

For example, if the variables are $t = 1$, $a = 4$, $v_0 = 6$, and $x_0 = 3$, then we know that the correct result is $9$:

$$
x(t) = \frac12 a t^2 + v_0t + x_0 = 11 ~ .
$$

Suppose we have three formulas:

 1. $v_0t + x_0$
 2. $v_0 + v_0$
 3. $t$

Applying each formula to the measurements above, we get

 1. $9$
 2. $12$
 3. $1$

For this single measurement, the formula that comes the closest to the correct result is the formula $v_0 + v_0$ but the first formula, $v_0t + x_0$ resembles the structure of the correct formula much more closely.

Because we're deducing the formula for $x(t)$ – in other words, because we're pretending we don't know the correct formula – we can't evaluate a formula by its structure.
Instead, what we can do is have a series of measurements instead of a single one.
By having multiple measurements we decrease the chance that a formula that is wrong approximates the correct result well.

This shows that we'll need a function to generate random measurements according to the formula:

```py
def generate_fake_data():
    """Create a dictionary with fake data according to the formula we're deducing."""
    data = {}
    data["x0"] = random.uniform(-10, 10)
    data["v0"] = random.uniform(-10, 10)
    data["a"] = random.uniform(-10, 10)
    data["t"] = random.uniform(-10, 10)
    data["result"] = (
        0.5 * data["a"] * data["t"] ** 2
        + data["v0"] * data["t"]
        + data["x0"]
    )
    # We add a bit of noise to simulate experimental errors:
    data["result"] += random.normalvariate(0, 0.1)
    return data
```


### Evaluating a formula on given data

Now that we can generate data we need a way to pass that data to a formula to have it compute the result.
We do this by implementing a method `eval` on `Formula` and its subclasses:

```py
class Formula:
    # ...

    def eval(self, data):
        """Evaluates the formula on the given data dictionary."""
        raise NotImplementedError()


class Operator(Formula):
    # ...

    def eval(self, data):
        """Evaluates the formula on the given data dictionary."""
        left = self.left.eval(data)
        right = self.right.eval(data)
        if self.op == operator.truediv and right == 0:
            return float("inf")
        return self.op(left, right)


class Operand(Formula):
    # ...

    def eval(self, data):
        """Evaluates the formula on the given data dictionary."""
        if isinstance(self.value, str):
            return data[self.value]
        return self.value
```

In `Formula.eval` we do nothing except for raising an error because `Formula` is acting as an abstract base class.
It's `Operator.eval` and `Operand.eval` that do the actual work of evaluating a formula.

In `Operator.eval` we start by evaluating the left and right operands.
Then, we check if we're about to perform a division by zero and return `float("inf")` in that case.
(You'd be surprised by how often that would happen if we don't guard against it.)
By returning `float("inf")`, we're essentially making any formula with “divide by zero” unusable.

In `Operand.eval` we check if we're dealing with a variable or not.
If it's a variable, we get its value from the dictionary `data`.
Otherwise, we return the numerical value of the operand.


### Computing the fitness of a formula

Finally, the only thing left to discuss is determining the exact process for figuring out if a formula is good or not.
Given a list of dictionaries with data we can use the mean squared error to determine how good a formula is.
This can be implemented as such:

```py
class Formula:
    # ...

    def fitness(self, data_list):
        squared_errors = [(data["result"] - self.eval(data)) ** 2 for data in data_list]
        return sum(squared_errors) / len(squared_errors)
```

Now, we can test this:

```py
# ...

if __name__ == "__main__":
    data_list = [generate_fake_data() for _ in range(20)]
    formula = Formula.new_formula()
    print(formula, formula.fitness(data_list))
```

Running this a couple of times produces the following output:

```
(v0 * t) 328.5780678472429
t 137.10275605130022
t 225.44361545424462
((t - v0) / a) 495.9531942650734
```

Looks plausible!


## Reproduction of individuals

One of the key aspects of genetic algorithms is the step in which individuals _reproduce_.
The essence of this step is the ability to take two different solutions and combine them to produce another solution.

For our algorithm we will implement a function that combines two different formulas according to a couple of different rules.
These rules are essentially _made up_ and you can come up with your own rules.
After the simulation is up and running, feel free to try to modify the rules and see if you can improve the algorithm.

The way in which we combine formulas will depend on what exactly the formulas are:

 - if we have two operands, we'll pick a random operator and combine the operands under one operator;
 - if we have an operator and an operand, we'll replace one of the branches of the operator with the given operand; and
 - if we have two operators, we'll randomly pick one of its branches and we'll replace one of the two branches of the other operator.

To implement this process we need to be able to create a copy of a given formula:

```py
class Formula:
    # ...

    def copy(self):
        """Creates a copy of the given formula."""
        raise NotImplementedError()


class Operator(Formula):
    # ...

    def copy(self):
        """Creates a copy of the given operator."""
        return Operator(self.op, self.left.copy(), self.right.copy())


class Operand(Formula):
    # ...

    def copy(self):
        """Creates a copy of the given operand."""
        return Operand(self.value)
```

With the aid of the method `copy` we can implement reproduction of formulas following the rules outlined above.
We'll also add a fourth rule that combines two given formulas under a new random operator with a small probability so that we have the ability of creating more complex formulas out of simpler formulas.

With all this in mind, we end up with this code:

```py
def reproduce(f1, f2):
    """Combines two formulas to produce a new one."""
    # If we have two operands, put them under an operator.
    # Do this rarely (2% of the time) for other types of formulas as well.
    if (isinstance(f1, Operand) and isinstance(f2, Operand)) or random.random() < 0.02:
        op = random.choice(operators)
        return Operator(op, f1.copy(), f2.copy())

    # If we have one operator and one operand, put the operand in one of the branches
    # of the operator.
    # If we have two operators, put one branch of f1 into one of the branches of f2.
    # (We assume f1 and f2 were shuffled.)
    if isinstance(f1, Operand):
        branch = f1.copy()
        child = f2.copy()
    elif isinstance(f2, Operand):
        branch = f2.copy()
        child = f1.copy()
    else:
        branch_side = random.choice(["left", "right"])
        branch = getattr(f1, branch_side).copy()
        child = f2.copy()
    child_side = random.choice(["left", "right"])
    setattr(child, child_side, branch)
    return child
```

We're using `getattr` and `setattr` to make it easier to randomly work with the branches of the operator formulas.

We can create a couple of random formulas and we can try to combine them:

```py
# ...

if __name__ == "__main__":
    f1, f2 = Formula.new_formula(), Formula.new_formula()
    print(f1)
    print(f2)
    print(reproduce(f1, f2))
    print("---")
```

This produces output like the following:

```
(a / (1 * 1))
1
(a / 1)
---
(1 * (x0 * 1))
(a + x0)
(1 + x0)
---
(v0 * 1)
(x0 - v0)
(x0 - 1)
---
(v0 - x0)
1
(1 - x0)
```


## Mutation of individuals

The final step of the simulation is the mutation of individuals.
For us, this means that we'll implement a method that tweaks the attributes of the `Operator` and `Operand` instances with a small probability.

For operators, this will mean trying to change the operator to a new operator chosen randomly and then recursively mutating the left and right branches of the tree.

For operands, this means one of two things:
 - if the operand is a variable, we'll pick a new operand randomly; and
 - if the operand is a number, we'll randomly pick a different number.

Here's the implementation of the mutation:

```py
class Formula:
    # ...

    def mutate(self, mutation_rate=0.05):
        """Mutates the given formula."""
        raise NotImplementedError()


class Operator(Formula):
    # ...

    def mutate(self, mutation_rate=0.05):
        """Mutates the given formula."""
        if random.random() < mutation_rate:
            self.op = random.choice(operators)
        self.left.mutate(mutation_rate)
        self.right.mutate(mutation_rate)


class Operand(Formula):
    # ...

    def mutate(self, mutation_rate=0.05):
        """Mutates the given formula."""
        if random.random() < mutation_rate:
            if isinstance(self.value, str):
                self.value = random.choice(operands)
            else:
                self.value = random.expovariate(1 / self.value)
```

To test this, I generated a random formula and then tried mutating it in a loop:

```py
if __name__ == "__main__":
    f1 = Formula.new_formula()
    for _ in range(100):
        print(f1)
        f1.mutate()
```

This printed 100 lines but plenty of them were _repeated_ because mutations only happen _some times_.
Here are all of the mutations that we went over:

```
(v0 + (v0 + a))
(v0 * (v0 * v0))
(1 * (v0 * v0))
(1 * (1 * v0))
(1 * (0.5235883929983018 * v0))
(1 * (0.02952786004975312 * v0))
(1 * (0.02952786004975312 * x0))
(1 / (0.02952786004975312 * 1))
(1 - (0.02952786004975312 * 1))
(0.3769839738547256 - (0.22991320078181732 * 1))
(0.3769839738547256 - (0.22991320078181732 / 1))
(0.08725045996788172 - (0.22991320078181732 / 1))
(0.08725045996788172 - (0.3454809905475698 / 0.6439219075274238))
```

Printing these formulas is a bit annoying because of all of the decimal places, so I went ahead and tweaked the [dunder method](/blog/pydonts/dunder-methods) `Operand.__repr__`:

```py
class Operand(Formula):
    # ...

    def __repr__(self):
        if isinstance(self.value, str):
            return self.value
        else:
            return f"{self.value:.2f}"

    # ...
```

This new version of `Operand.__repr__` will only show two decimal places when the operand is a float.
With the new formatting, the formulas from above would be shown as

```
(v0 + (v0 + a))
(v0 * (v0 * v0))
(1 * (v0 * v0))
(1 * (1 * v0))
(1 * (0.52 * v0))
(1 * (0.030 * v0))
(1 * (0.030 * x0))
(1 / (0.030 * 1))
(1 - (0.030 * 1))
(0.38 - (0.23 * 1))
(0.38 - (0.23 / 1))
(0.09 - (0.23 / 1))
(0.09 - (0.35 / 0.64))
```


## The genetic algorithm simulation

Now that we have all of the ingredients to implement the simulation, the only thing that is missing _is_ the simulation.
There are many things that can be customised and tweaked when implementing a genetic algorithm.
This means that the choices I'm making here are not necessarily the best choices ever!
They're the choices I made.
You _should_ try and modify things to see what happens.

This is what my simulation looks like:

```py
if __name__ == "__main__":
    from functools import partial
    from itertools import pairwise  # Python 3.10+

    POPULATION_SIZE = 10_000
    DATA_SIZE = 200
    TO_REPRODUCE = 5_000
    TOP_SAVED = 20
    GENERATIONS = 70

    population = [Formula.new_formula() for _ in range(POPULATION_SIZE)]
    data = [generate_fake_data() for _ in range(DATA_SIZE)]
    fitness_from_data = partial(Formula.fitness, data_list=data)

    for gen in range(GENERATIONS):
        # Sort population by fitness.
        sorted_pop = sorted(population, key=fitness_from_data)
        best_f = sorted_pop[0]
        print(f"{gen}. {best_f} : {best_f.fitness(data)}")

        # Save a copy of the absolute best formulas.
        top = [f.copy() for f in sorted_pop[:TOP_SAVED]]

        # The fittest are more likely to reproduce.
        parents = random.choices(
            sorted_pop, weights=range(len(sorted_pop), 0, -1), k=TO_REPRODUCE
        )
        children = [reproduce(f1, f2) for f1, f2 in pairwise(parents)]

        # The fittest survive for the next generation with possible mutations.
        fittest = sorted_pop[: POPULATION_SIZE // 2]
        for f in fittest:
            f.mutate(0.1)

        population = top + fittest + children

    sorted_pop = sorted(population, key=fitness_from_data)
    best_f = sorted_pop[0]
    print(f"{best_f} : {best_f.fitness(data)}")
```

I ran this a couple of times.
Some of the times, the final result is terrible.
Some of the times, the final result is decent.
And a couple of times, the final result looks really promising:

```
...
65. (((a * 4.34) + ((t * v0) - v0)) + ((t * (t * a)) / (1.00 * 2.36))) : 354.1270909979102
66. (((a * 4.34) + ((t * v0) - v0)) + ((t * (t * a)) / (1.00 * 2.36))) : 354.1270909979102
67. (((t + (t * v0)) + (a / 5.87)) + ((t * (a * t)) / (0.32 * 6.92))) : 178.39836589667198
68. (((t * (t * a)) / (0.29 * 6.92)) + ((t * (v0 * 1.00)) + (1.00 * 6.92))) : 78.96262333227813
69. (((t * (t * a)) / (0.29 * 6.92)) + ((t * (v0 * 1.00)) + (1.00 * 6.92))) : 78.96262333227813
(((t * (t * a)) / (0.29 * 6.92)) + ((t * (v0 * 1.00)) + (1.00 * 6.92))) : 78.96262333227813
```

Of course, the final result looks promising if you look well enough.
The final formula looks like this:

$$
((t \times (t \times a)) / (0.29 \times 6.92)) + ((t \times (v_0 * 1)) + (1 * 6.92))
$$

You can simplify this into

$$
0.4983 \times at^2 + v_0t + 6.92
$$

This is not quite right but it is remarkably close!
The first two terms of the formula are reproduced almost perfectly.
The $x_0$ is being represented by the constant $6.92$ which, for this run, was probably close to the average value of the $x_0$ of all the data points generated.

One thing I should tell you is that I also tweaked the simulation a few times before settling on what you can see above.
This is what you end up doing in real life.
You try different settings to see what settings produce promising results with some frequency.


## Conclusion

To conclude, in this tutorial you learned about all of the components that you need to implement to write a genetic algorithm:

 - you need to be able to generate random solution candidates (the individuals that make up your population);
 - you need to be able to assess how good each possible solution is (compute the fitness);
 - you need to be able to combine two solutions to create a third one (reproduce individuals); and
 - you need to be able to make small tweaks to random solutions (mutate individuals).

In the particular context of deducing a physics formula, you also learned about how you can represent formulas as trees and how to recursively evaluate formulas.


## What's next

Try to make your simulation better.
Play with the many parameters and see if you can create settings that produce better results more consistently:

 - change the probability of generating an operator when creating a new formula;
 - change the probability of mutating an individual;
 - change the implementation of the function `reproduce`;
 - change the way mutations work in `Operator` nodes;
 - play with the sizes of the population and the number of individuals that reproduce;
 - experiment with the way of selecting what individuals reproduce and the way formulas are paired;
 - and anything else you can think of.

You can also try adding caching to the formulas so that simulations run faster and you can also try to add a progress bar to your simulation to keep track of the progress.

Have fun and let me know in the comments below what things you try to do!
