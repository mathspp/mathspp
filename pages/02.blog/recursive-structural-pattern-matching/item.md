Learn how to use structural pattern matching (the match statement) to work recursively through tree-like structures.

===

In this short article you will learn to use [structural pattern matching](/blog/pydonts/structural-pattern-matching-tutorial) in recursive, tree-like data structures.

The examples from this article are taken from a couple of [recent issues of my weekly newsletter](/insider).


## A recursive data structure

Structural pattern matching excels at... matching the structure of your objects!
For the two examples in this article, we'll be using a number of dataclasses that you can use to build abstract Boolean expressions:

```py
from dataclasses import dataclass

class Expr:
    pass

@dataclass
class And(Expr):
    exprs: list[Expr]

@dataclass
class Or(Expr):
    exprs: list[Expr]

@dataclass
class Not(Expr):
    expr: Expr

@dataclass
class Var(Expr):
    name: str
```

For example, the code `Not(And([Var("A"), Var("B")]))` represents the Boolean expression `not (A and B)`.


## Evaluating a Boolean expression

Suppose you have a Boolean expression built out of the components shared above.
How do you evaluate that formula if you are given the assignments that map the variables to their values?

For example, if you have the assignments `{"A": True, "B": False}` (for example, a dictionary that maps variable names to values), how can you determine that the expression `Not(And([Var("A"), Var("B")]))` is `True`?

This is where structural pattern matching can be applied recursively and it's where it really shines!

To solve this problem, you will write a function called `evaluate(expression: Expr, assignments: dict[str, bool]) -> bool`.
Your function accepts an expression and the assignments in the form of a dictionary and it returns the final Boolean value the expression evaluates to.

Since you're accepting an expression, you're going to use the `match` statement on the full expression and then create a `case` branch for each of the possible expressions you might have:

 1. a variable;
 2. an `And` expression;
 3. an `Or` expression; or
 4. a `Not` expression.

The structure of the code looks like this:

```py
def evaluate(expression: Expr, assignments: dict[str, bool]) -> bool:
    match expression:
        case Var(): pass
        case And(): pass
        case Or(): pass
        case Not(): pass
```

The trick here is realising that you're using `Expr` as the type of the argument but really, you always expect the argument to be an instance of one of the subclasses of `Expr`, and not a direct `Expr` instance.

However, to make sure you don't trip on a weird bug later on, and because this matching is supposed to be exhaustive – you're supposed to have one `case` for each subclass of `Expr` – you can defend yourself by including a catch-all pattern that raises an error.

When I'm being lazy, I just raise a `RuntimeError`:

```py
def evaluate(expression: Expr, assignments: dict[str, bool]) -> bool:
    match expression:
        case Var(): pass
        case And(): pass
        case Or(): pass
        case Not(): pass
        case _:
            raise RuntimeError(
                f"Couldn't evaluate expression of type {type(expression)}."
            )
```

Now, it's just a matter of implementing the evaluation logic.
In the case of a variable, all you have to do is fetch the variable value from the corresponding dictionary.
However, to make it more convenient to access the attribute `name` of an instance of `Var`, you can add the variable `name` inside the `case Var()` statement to capture the name directly:

```py
def evaluate(expression: Expr, assignments: dict[str, bool]) -> bool:
    match expression:
        case Var(name):
            return assignments[name]
        ...
```

The call `evaluate(Var("A"), {"A": True})` will now produce the value `True`.

To implement the evaluation of `And` and `Or` formulas, you can use a variable to capture the attribute `exprs` and then use the built-ins `and` and `any`, respectively, to evaluate the subexpressions.

It is at this point that the recursion comes in, since the subexpressions contained in `exprs` are, themselves, expressions of the type `Expr`.
This means you can reuse the function `evaluate` to evaluate them:

```py
def evaluate(expression: Expr, assignments: dict[str, bool]) -> bool:
    match expression:
        case Var(name):
            return assignments[name]
        case And(exprs):
            return all(evaluate(sub, assignments) for sub in exprs)
        case Or(exprs):
            return any(evaluate(sub, assignments) for sub in exprs)
        ...
```

You can try calling `evaluate` to check that it's working alright[^1]:

[^1]: The class method `dict.fromkeys` accepts a second argument to which all keys are mapped, so `dict.fromkeys("ABCDEF", False)` iterates over the string `"ABCDEF"` and maps each character to the value `False`.

```py
expr = Or(
    [
        And([Var("A"), Var("B"), Var("C")]),
        And([Var("D"), Var("E")]),
        And([Var("F")]),
    ]
)
assignments = dict.fromkeys("ABCDEF", False)  # Set all keys to False.
assignments["F"] = True  # Set only F to True.

print(evaluate(expr, assignments))  # True
```

Next up, and to conclude the evaluation of expressions, you have to implement the evaluation of `Not` formulas.
To evaluate an expression of the type `Not(expr)`, you just have to negate the evaluation of the subexpression `expr`:

```py
def evaluate(expression: Expr, assignments: dict[str, bool]) -> bool:
    match expression:
        case Var(name):
            return assignments[name]
        case And(exprs):
            return all(evaluate(sub, assignments) for sub in exprs)
        case Or(exprs):
            return any(evaluate(sub, assignments) for sub in exprs)
        case Not(expr):  # <--
            return not evaluate(expr, assignments)
        case _:
            raise RuntimeError(
                f"Couldn't evaluate expression of type {type(expression)}."
            )
```


## Pretty-printing formulas

Suppose you're working with a larger expression now.
Something like this:

```py
large_expr = Or([
    Not(
        And([
            Var("v01"),
            Not(Var("v02")),
        ])
    ),
    Or([
        Var("v03"),
        Not(Var("v04")),
    ])
])
```

Because all classes being used are dataclasses, you can print your expression and the information printed will be informative...
But it will be messy:

```py
print(large_expr)
```
```text
Or(exprs=[Not(expr=And(exprs=[Var(name='v01'), Not(expr=Var(name='v02'))])), Or(exprs=[Var(name='v03'), Not(expr=Var(name='v04'))])])
```

Can you use recursive structural pattern matching to write a function `pretty_print` that produces output that is formatted with indentation to make it easier to read the structure of the expression?

The technique will be very similar to what you did above.
You're going to start by matching the argument and then creating a `case` statement for each subclass or `Expr`:

```py
def pretty_print(expression: Expr) -> None:
    match expression:
        case Var(): pass
        case And(): pass
        case Or(): pass
        case Not(): pass
        case _:
            raise RuntimeError(
                f"Couldn't print expression of type {type(expression)}."
            )
```

The easiest case is printing a variable:

```py
def pretty_print(expression: Expr) -> None:
    match expression:
        case Var(name):
            print(f"Var({name!r})")
        ...
```

For any other case, you need to print the class name, call the function `pretty_print` recursively, and then print the closing parentheses:

```py
def pretty_print(expression: Expr) -> None:
    match expression:
        case Var(name):
            print(f"Var({name!r})")
        case And(exprs) | Or(exprs):
            print(f"{type(expression).__name__}([")
            for sub in exprs:
                pretty_print(sub)
            print("])")
        case Not(expr):
            print(f"Not(")
            pretty_print(expr)
            print(")")
        case _:
            raise RuntimeError(
                f"Couldn't print expression of type {type(expression)}."
            )
```

This function is close to what we want, but it isn't right yet:

```py
pretty_print(large_expr)
```
```text
Or([
Not(
And([
Var('v01')
Not(
Var('v02')
)
])
)
Or([
Var('v03')
Not(
Var('v04')
)
])
])
```

You're missing the indentation.
To fix it, you can add an optional parameter `depth` to the function `pretty_print`.
Whenever you call the function `pretty_print` recursively, the depth increases by one, and you can use the value of `depth` to determine how much indentation is required:

```py
def pretty_print(expression: Expr, depth: int = 0) -> None:
    indent = " " * 4 * depth
    match expression:
        case Var(name):
            print(f"{indent}Var({name!r})")
        case And(exprs) | Or(exprs):
            print(f"{indent}{type(expression).__name__}([")
            for sub in exprs:
                pretty_print(sub, depth + 1)
            print(f"{indent}])")
        case Not(expr):
            print(f"{indent}Not(")
            pretty_print(expr, depth + 1)
            print(f"{indent})")
        case _:
            raise RuntimeError(
                f"Couldn't print expression of type {type(expression)}."
            )
```

If you try pretty printing the expression `large_expr`, you can see you're closer:

```py
pretty_print(large_expr)
```
```text
Or([
    Not(
        And([
            Var('v01')
            Not(
                Var('v02')
            )
        ])
    )
    Or([
        Var('v03')
        Not(
            Var('v04')
        )
    ])
])
```

The only thing that's missing are the trailing commas in subexpressions inside `And` and `Or`.
One way to fix this is by adding another parameter to your recursive function that indicates whether you need a trailing comma or not.

When you match against an `And` or an `Or`, you set the trailing comma `tc` to `","` when calling `pretty_print` recursively.
When you match against `Not`, you leave the trailing comma `tc` empty.

When printing the closing parenthesis of any instance, you add the trailing comma `tc` after the closing parenthesis:

```py
def pretty_print(expression: Expr, depth: int = 0, tc: str = "") -> None:
    indent = " " * 4 * depth
    match expression:
        case Var(name):
            print(f"{indent}Var({name!r}){tc}")  # <--
        case And(exprs) | Or(exprs):
            print(f"{indent}{type(expression).__name__}([")
            for sub in exprs:
                pretty_print(sub, depth + 1, ",")  # <--
            print(f"{indent}]){tc}")  # <--
        case Not(expr):
            print(f"{indent}Not(")
            pretty_print(expr, depth + 1)
            print(f"{indent}){tc}")  # <--
        case _:
            raise RuntimeError(
                f"Couldn't print expression of type {type(expression)}."
            )
```

If you try this function out again, you get the correct output:

```py
pretty_print(large_expr)
```
```text
Or([
    Not(
        And([
            Var('v01'),
            Not(
                Var('v02')
            ),
        ])
    ),
    Or([
        Var('v03'),
        Not(
            Var('v04')
        ),
    ]),
])
```

Strictly speaking, this doesn't match the original code 100%.
That's because the original code kept the negations of variables in a single line, as `Not(Var(xxx))`, whereas the current implementation of `pretty_print` always splits the `Not` across 3+ lines.

If you wanted to special-case this particular pattern, you could create a more specific pattern as a `case` statement:

```py
def pretty_print(expression: Expr, depth: int = 0, tc: str = "") -> None:
    indent = " " * 4 * depth
    match expression:
        ...
        case Not(Var(name)):
            print(f"{indent}Not(Var({name!r})){tc}")
        case Not(expr):
            print(f"{indent}Not(")
            pretty_print(expr, depth + 1)
            print(f"{indent}){tc}")  # <--
        ...
```

There you go, now the negations of variables are inlined:

```py
pretty_print(large_expr)
```
```text
Or([
    Not(
        And([
            Var('v01'),
            Not(Var('v02')),
        ])
    ),
    Or([
        Var('v03'),
        Not(Var('v04')),
    ]),
])
```


## Conclusion

Structural pattern matching shines when applied to tree-like recursive data structures because it allows you to peel the layers of the data structure with ease and manipulate them to perform whatever operations you need.
In this article, you evaluated Boolean expressions and you pretty-printed them.

Some tips for when you're using recursive structural pattern matching:

 1. Destructure your objects directly in the `case` statement to use the attributes you need in the body of the `case` statement.
 2. It's always a good idea to have a catch-all `case _` statement at the bottom to ensure you don't forget to handle any cases.
 3. You can add optional parameters to your recursive function to carry information up and down the call stack.
