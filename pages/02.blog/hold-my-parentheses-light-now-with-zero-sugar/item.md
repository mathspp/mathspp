Explore what Python could look like if we got rid of all of its synthatic sugar.

===

# Hold my parentheses light: now with zero sugar


Given a string representing a parenthesised expression like `"(3*(x+42)"`, how can we figure out if the expression is correctly parenthesised?

In the [last article](/blog/hold-my-parentheses) I solved this challenge with a funky Python solution.
In this article, I want to take a look at the “obvious” solution using a loop:

```py
def has_balanced_parens(expression):
    depth = 0
    for char in expression:
        if char == "(":
            depth += 1
        elif char == ")":
            depth -= 1
            if depth < 0:
                return False
    return depth == 0
```

Now, we'll go over this solution and strip it down of its syntactic sugar.
First, we can get rid of all of the arithmetic operators and comparisons because those boil down to calls to some [dunder methods](/blog/pydonts/dunder-methods).

We can start by removing the arithmetic operators:

```py
def has_balanced_parens(expression):
    depth = 0
    for char in expression:
        if char == "(":
            depth = int.__add__(depth, 1)
        elif char == ")":
            depth = int.__sub__(depth, 1)
            if depth < 0:
                return False
    return depth == 0
```

If you know your way around [arithmetic dunder methods](/blog/pydonts/overloading-arithmetic-operators-with-dunder-methods), you'll know that I'm short-circuiting a couple of things, but since `depth` is always an integer and `1` is an integer, I decided to stick with `int.__add__` and `int.__sub__`.

Then, you might think about `__iadd__` and `__isub__`, but `int` does not implement those.

Next, we can do a similar thing for comparison operators:

```py
def has_balanced_parens(expression):
    depth = 0
    for char in expression:
        if str.__eq__(char, "("):
            depth = int.__add__(depth, 1)
        if str.__eq__(char, ")"):
            depth = int.__sub__(depth, 1)
            if int.__lt__(depth, 0):
                return False
    return int.__eq__(depth, 0)
```

Brilliant, right?
But that's not all.
Why do we even need the conditionals, if we can just use the Boolean values directly?

```py
def has_balanced_parens(expression):
    depth = 0
    for char in expression:
        depth = int.__add__(depth, str.__eq__(char, "("))
        depth = int.__sub__(depth, str.__eq__(char, ")"))
        if int.__lt__(depth, 0):
            return False
    return int.__eq__(depth, 0)
```

`if` statements are not really syntactic sugar in Python, but we could definitely get rid of those two.
To conclude, we can get rid of the `for` loop because that's just a `while` loop with extra steps.
And while we're at it, let us replace Booleans with the actual integers they represent:

```py
def has_balanced_parens(expression):
    depth = 0
    expression_iter = iter(expression)
    while 1:
        try:
            char = next(expression_iter)
        except StopIteration:
            break
        depth = int.__add__(depth, str.__eq__(char, "("))
        depth = int.__sub__(depth, str.__eq__(char, ")"))
        if int.__lt__(depth, 0):
            return 0
    return int.__eq__(depth, 0)
```

So, the code above is the solution to our parentheses challenge, written in Python, without any syntactic sugar!
