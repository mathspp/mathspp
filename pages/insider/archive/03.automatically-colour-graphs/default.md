---
date: 05-01-2026
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to dynamically turn a graph into the corresponding SAT formula for the 3-colouring problem."
    og:image: "https://mathspp.com/insider/archive/automatically-colour-graphs/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/automatically-colour-graphs/thumbnail.webp"
title: Automatically colour graphs

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Automatically colour graphs

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## P vs NP

[A couple of emails ago](https://mathspp.com/insider/archive/dark-computer-science-translation) we talked about the P vs NP problem.

Remember that a problem is in P if there exists an efficient algorithm to solve it.
For example, computing the maximum of a list of numbers is a problem in P.

On the other hand, a problem is in NP if there is an efficient algorithm to _check_ a solution, but not necessarily to solve it in the first place.
For example, the SAT problem is in NP...

## The SAT problem

The SAT problem asks if there is a way to make a Boolean formula true.

For example, the formula `A or B` is satisfiable: if you set `A = True`, it doesn't matter what you do to `B` that the formula is already `True`.

On the other hand, the formula `A and not A` is **not** satisfiable: regardless of what you set `A` to, the formula `A and not A` remains `False`.

Given a formula like this, with however many variables, you _have_ to use brute force to try to find a set of assignments to satisfy the formula.

**But** if you are given an assignment already, it is very easy to check if it works.

That's why the SAT problem is in NP.

But there's more...

## NP-completeness

As you also saw, [the SAT problem is NP-complete](https://mathspp.com/insider/archive/dark-computer-science-translation).

This means _any_ other problem in NP can be translated into a SAT problem, which is why [SAT solvers](https://mathspp.com/insider/archive/sat-solver) are such useful pieces of software.
You [also wrote your own SAT solver in a previous email](https://mathspp.com/insider/archive/sat-solver), so today I want to wrap this up by writing a function that automatically translates a graph problem into its equivalent SAT problem.

## Graph 3-colouring

Given a graph like the one below, can you colour its nodes with 3 colours (red, green, and blue) such that no two adjacent vertices share the same colour?

![Graph with four nodes A B C D. Edges between AB, AC, AD, and BC.](_graph.webp)

For this small graph it is easy to see that it is.
For example, paint `A` in red, `B` and `D` in green, and `C` in blue.

But in general, this is a hard problem.
(And it is an NP problem.)

So we can turn it into a SAT problem!

Either by hand, or automatically...

You [did it by hand a couple of weeks ago](https://mathspp.com/insider/archive/dark-computer-science-translation), so now it is time to do it automatically.

## Representing the graph

There are many different ways to represent a graph.
One possible way is as a dictionary that maps vertices to its neighbours.

For the graph shown above, that would be

```python
graph = {
    "A": {"B", "C", "D"},
    "B": {"A", "C"},
    "C": {"A", "B"},
    "D": {"A"},
}
```

Working from this representation, you can start working on building the formula you need.

## The formula components

Just remember that you're building a formula out of variables and Boolean operators:

```python
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

## The translation function

You'll write a function `graph_colouring_to_sat(graph)` that accepts a graph and produces a formula that encodes the 3-colouring problem.

The function will work by building clauses and, in the end, you put them all together with a big `And(clauses)`.

Here's the structure:

```python
def graph_colouring_to_sat(graph):
    clauses = []

    ...

    return And(clauses)
```

## Each node must have at least one colour

Each node must have at least one colour, so you have to go through all nodes and create a clause that represents that.

For the node `A`, we're talking about this formula:

```py
# A has to be red or green or blue:
Or(Var("A_red"), Var("A_green"), Var("A_blue"))
```

So, the function needs to do that:

```python
def graph_colouring_to_sat(graph):
    clauses = []
    colours = ("red", "green", "blue")

    # Each node has at least 1 colour:
    for node in graph:
        variables = [Var(f"{node}_{clr}") for clr in colours]
        clauses.append(Or(variables))

    ...
```

## No node can have two colours

For all pairs of two colours, you need to specify that they can't happen at the same time.
For example, the formula below prevents the node `A` from being both red and blue:

```python
Not(
    And(Var("A_red"), Var("A_blue"))
)
```

You need to do this for each node and for each pair of two colours:

```python
from itertools import combinations

def graph_colouring_to_sat(graph):
    clauses = []
    colours = ("red", "green", "blue")
    
    # Each node has at least 1 colour...
    # Each node has at most 1 colour:
    for node in graph:
        variables = [Var(f"{node}_{clr}") for clr in colours]
        subclauses = []
        for combo in combinations(variables, 2):
            subclauses.append(Not(And(list(combo))))
        clauses.append(And(subclauses))

    ...
```

## Adjacent nodes can't have the same colour

`A` and `B` are connected in the graph above, which means they can't have the same colour.

That translates into a formula like this:

```python
And(
    # A and B can't both be red:
    Not(And(Var("A_red"), Var("B_red"))),
    # A and B can't both be blue:
    Not(And(Var("A_blue"), Var("B_blue"))),
    # A and B can't both be green:
    Not(And(Var("A_green"), Var("B_green"))),
)
```

First, you can get all the unique edges with a nice set comprehension:

```python
edges = {
    tuple(sorted((f_, t_)))
    for f_, tos in graph.items()
    for t_ in tos
}
```

Now, you can create the formula for each unique edge/connection:

```python
from itertools import combinations

def graph_colouring_to_sat(graph):
    clauses = []
    colours = ("red", "green", "blue")
    
    # Each node has at least 1 colour...
    # Each node has at most 1 colour...
    # Connected nodes have different colours:
    edges = {tuple(sorted((f_, t_))) for f_, tos in graph.items() for t_ in tos}
    for a, b in edges:
        clauses.append(
            And(
                [
                    Not(And([Var(f"{a}_{clr}"), Var(f"{b}_{clr}")]))
                    for clr in colours
                ]
            )
        )

    return And(clauses)
```

And that's it!

If you put the three sections together, you got yourself a translator:

```python
def graph_colouring_to_sat(graph):
    clauses = []
    colours = ("red", "green", "blue")

    # Each node has at least 1 colour:
    for node in graph:
        variables = [Var(f"{node}_{clr}") for clr in colours]
        clauses.append(Or(variables))

    # Each node has at most 1 colour:
    for node in graph:
        variables = [Var(f"{node}_{clr}") for clr in colours]
        subclauses = []
        for combo in combinations(variables, 2):
            subclauses.append(Not(And(list(combo))))
        clauses.append(And(subclauses))

    # Connected nodes have different colours:
    edges = {tuple(sorted((f_, t_))) for f_, tos in graph.items() for t_ in tos}
    for a, b in edges:
        clauses.append(
            And(
                [
                    Not(And([Var(f"{a}_{clr}"), Var(f"{b}_{clr}")]))
                    for clr in colours
                ]
            )
        )

    return And(clauses)
```

If you have any questions about the way this code works, hit reply!

I'll read your questions and get back to you ASAP!

## What a ride this has been..!

The past few weeks were a bit atypical.

I decided to write about NP-completeness, which is a very “math-y” subject, while also trying to show you what everything really means through concrete code you could run yourself.

What did you think about these three emails?


## Enjoyed reading? 🐍🚀

Get a _free_ Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
