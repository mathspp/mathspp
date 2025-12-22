---
date: 22-12-2025
metadata:
    description: "Learn how to write your own SAT solver in Python in this sample of the mathspp insider weekly newsletter."
title: Dark computer science “translation”

process:
  twig: true
cache_enable: false
---

# 🐍🚀 SAT solver

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider#subscribe) to get weekly Python deep dives like this one on your inbox!

## P vs NP

[Last week we talked about the one-million dollar P vs NP problem](https://mathspp.com/insider/archive/dark-computer-science-translation), which essentially asks:

 > Are there problems that are inherently difficult or is it the case that we haven’t been clever enough to figure out efficient algorithms for the ones that look difficult?

In particular, you learned that the SAT problem (the SATisfiability problem), is NP.

That's because, given a Boolean formula, there is no efficient algorithm to figure out if you can assign values to the variables that makes the formula become `True`.

In other words, you have to try every single possible assignment to check if a formula is satisfiable.

A program that takes a formula and tries to see if it is satisfiable is a SAT solver, and you're about to write one.

## The formula you are checking

You need an example formula to use as an example...
You can start with something small, with three variables:

```py
formula = Or([
    Not(And(Var("A"), Var("B"))),
    Not(And(Var("B"), Var("C"))),
    Not(And(Var("C"), Var("A"))),
])
```

## Representing a formula

The first thing you need to do is define `Or`, `Not`, `And`, and `Var`, so that you can actually build formulas.
Using the module `dataclasses` is a good idea because you get formula initialisation (through `__init__`) and printing (through `__repr__`) for free:

```py
from dataclasses import dataclass

class Formula:
    pass

@dataclass
class And(Formula):
    exprs: list[Formula]

@dataclass
class Or(Formula):
    exprs: list[Formula]

@dataclass
class Not(Formula):
    expr: Formula

@dataclass
class Var(Formula):
    name: str
```

At this point, you can already build your formula and print it:

```py
formula = Or([
    Not(And(Var("A"), Var("B"))),
    Not(And(Var("B"), Var("C"))),
    Not(And(Var("C"), Var("A"))),
])
print(formula)
# Or(exprs=[Not(expr=And(exprs=[Var(name='A'), Var(name='B')])), Not(expr=And(exprs=[Var(name='B'), Var(name='C')])), Not(expr=And(exprs=[Var(name='C'), Var(name='A')]))])
```

The output isn't particularly easy to read, but all the information is there so you can check that the formula was built correctly.

(Exercise: write a function `print_formula` that prints these formulas neatly!)

## How to evaluate a formula

The next step you need is to write a function, called `evaluate`, that accepts a series of assignments and a formula and checks if those assignments satisfy the given formula.

If the assignments are given in the form of a dictionary that maps variable names to values, then your function `evaluate` can use [structural pattern matching](https://mathspp.com/blog/pydonts/structural-pattern-matching-tutorial) to evaluate a formula:

```py
def evaluate(context: dict[str, bool], formula: Formula) -> bool:
    match formula:
        case Var(name):
            return context[name]
        case Not(expr):
            return not evaluate(context, expr)
        case Or(exprs):
            return any(evaluate(context, subformula) for subformula in exprs)
        case And(exprs):
            return all(evaluate(context, subformula) for subformula in exprs)
        case _:
            raise RuntimeError
```

When you have a recursive data structure, like our formulas, structural pattern matching allows you to write very elegant code that operates on your structure.

The equivalent code that uses conditional statements (`if`/`elif`/`else`) would be much less elegant...

You now have a function to _evaluate_ the formula with a given set of assignments, but you still have to write the function that tests all those assignments!

## The core of the SAT solver

For a complex SAT solver, you'll probably want to inspect your formula and try to make deductions about the assignments that are worth trying, to see if you can reduce the amount of work that needs to be done.

That's because, if your formula has `n` variables, you need to check up to `2 ** n` different assignments!

And trust me, when `n` grows large (and it will), this is _too_ much work.

But your SAT solver will be a modest piece of code without too many bells and whistles, so it will just use brute-force:

```py
from itertools import product

def satisfy(formula: Formula) -> dict[str, bool] | None:
    names = varnames(formula)  # <--
    n = len(names)
    for assignment in product([True, False], repeat=n):
        context = dict(zip(names, assignment))
        if evaluate(context, formula):
            return context
    return None
```

The function `satisfy` is using an auxiliary function `varnames` that goes through a formula to find all of the variables that are being used in that formula.

(You still have to define that function.)

Then, you're using your trusted `itertools.product` to create tuples with as many Boolean values as there are variables.

In this example `n` is 3, so `product` will produce `2 ** 3 == 8` tuples in total:

```py
(True, True, True)   # 1
(True, True, False)  # 2
(True, False, True)  # 3
# ...
```

Finally, you zip the variable names and the potential values together to create a dictionary that maps variable names to values.

This is what gets passed to the function `evaluate`, which will check if the given assignment satisfies the formula or not.

## Running the SAT solver

If you run the SAT solver on your formula, you will get one of the many possible assignments that work:

```py
print(satisfy(formula))
# {'A': True, 'B': True, 'C': False}
```

Pretty cool!

You just wrote your own SAT solver!

But this was a very simple formula.

The reason you wrote this was to solve a graph colouring problem...

Remember?

## The 3-colouring graph problem

Given a graph like the one below, you can use your SAT solver to figure out how to colour each node (using red, green, or blue) so that no two connecting nodes share a colour.

![Graph with four nodes A B C D. Edges between AB, AC, AD, and BC.](_graph.webp)

[Last week you saw how to turn this problem into a SAT problem](https://mathspp.com/insider/archive/dark-computer-science-translation), which results in a huge formula with at least 12 “top-level” clauses and many smaller sub expressions.

The formula looks like this:

```py
graph_formula = And([
    # Nodes A, B, C, and D, have at least one colour.
    Or([Var("A_red"), Var("A_green"), Var("A_blue")]),
    Or([Var("B_red"), Var("B_green"), Var("B_blue")]),
    Or([Var("C_red"), Var("C_green"), Var("C_blue")]),
    Or([Var("D_red"), Var("D_green"), Var("D_blue")]),
    # Node A doesn't have two colours.
    And([
        Not(And([Var("A_red"), Var("A_blue")])),
        Not(And([Var("A_blue"), Var("A_green")])),
        Not(And([Var("A_green"), Var("A_red")])),
    ]),
    # Node B doesn't have two colours.
    And([
        Not(And([Var("B_red"), Var("B_blue")])),
        Not(And([Var("B_blue"), Var("B_green")])),
        Not(And([Var("B_green"), Var("B_red")])),
    ]),
    # Node C doesn't have two colours.
    And([
        Not(And([Var("C_red"), Var("C_blue")])),
        Not(And([Var("C_blue"), Var("C_green")])),
        Not(And([Var("C_green"), Var("C_red")])),
    ]),
    # Node D doesn't have two colours.
    And([
        Not(And([Var("D_red"), Var("D_blue")])),
        Not(And([Var("D_blue"), Var("D_green")])),
        Not(And([Var("D_green"), Var("D_red")])),
    ]),
    # Nodes A and B don't share a colour.
    And([
        Not(And([Var("A_red"), Var("B_red")])),
        Not(And([Var("A_blue"), Var("B_blue")])),
        Not(And([Var("A_green"), Var("B_green")])),
    ]),
    # Nodes A and C don't share a colour.
    And([
        Not(And([Var("A_red"), Var("C_red")])),
        Not(And([Var("A_blue"), Var("C_blue")])),
        Not(And([Var("A_green"), Var("C_green")])),
    ]),
    # Nodes B and C don't share a colour.
    And([
        Not(And([Var("B_red"), Var("C_red")])),
        Not(And([Var("B_blue"), Var("C_blue")])),
        Not(And([Var("B_green"), Var("C_green")])),
    ]),
    # Nodes A and D don't share a colour.
    And([
        Not(And([Var("A_red"), Var("D_red")])),
        Not(And([Var("A_blue"), Var("D_blue")])),
        Not(And([Var("A_green"), Var("D_green")])),
    ]),
])
```

If you throw your SAT solver at this formula, you should get something like this as output:

```py
{'D_blue': True, 'D_green': True, 'D_red': False, 'B_blue': True, 'C_green': True, 'A_red': True, 'A_blue': False, 'B_red': False, 'B_green': False, 'C_blue': False, 'A_green': False, 'C_red': False}
```

If you look through the variables, this result suggests the following colouring:

 - Node A: red
 - Node B: blue
 - Node C: green
 - Node D: green

If you try to colour the graph shown above with these colours, you will see that no two neighbouring nodes share the same colour!

## Extra challenge!

I typed that huge formula by hand.

Took me a minute!

What you can do, which is much better, is to write a function that accepts a graph (maybe the node names and a list of edges) and automatically builds the formula that represents the 3-colouring problem.

Give this a go and send me your code when you're done!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_subscribe'} ) } %}
