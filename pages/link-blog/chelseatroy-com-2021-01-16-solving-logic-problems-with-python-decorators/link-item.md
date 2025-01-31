---
author: Chelsea Troy
date: 31-01-2025 11:57
link: https://chelseatroy.com/2021/01/16/solving-logic-problems-with-python-decorators/
taxonomy:
    category: link
title: "Solving Logic Problems with Python Decorators – Chelsea Troy"
via: https://learnbyexample.gumroad.com/l/learnbyexample-weekly
---

In this article, Chelsea states a logic problem:

 > “Baker, Cooper, Fletcher, Miller, and Smith live on different floors of an apartment house that contains only five floors.
 > Baker does not live on the top floor.
 > Cooper does not live on the bottom floor.
 > Fletcher does not live on either the top or the bottom floor.
 > Miller lives on a higher floor than does Cooper.
 > Smith does not live on a floor adjacent to Fletcher’s.
 > Fletcher does not live on a floor adjacent to Cooper’s.
 > Where does everyone live?”

Chelsea then proceeds to writing a couple of suboptimal functions that solve this and then converges on a more efficient version of brute forcing.
Then, because of the way the code is formulated, Chelsea introduces a decorator that solves logic problems like these.
The decorator `solve` must be used around a function that checks the requirements and produces a function that accepts the possible values for each variable.
Something like this:

```py
def all_combinations(**kwargs):
    """Produce all combinations of the variables and their possible values,
    respectively the keys and values of the kwargs dictionary."""
    ...

def solve(reqs_function):
    def solver(**kwargs):
        for comb in all_combinations(**kwargs):
            if reqs_function:
                return comb
        raise RuntimeError("Couldn't solve.")

    return solver

@solve
def reqs_function(**kwargs):
    """Checks the reqs of the problem statement."""
    return kwargs["baker"] != 1 and ...
```
