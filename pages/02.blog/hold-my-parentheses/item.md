Explore unusual Python features to solve the problem of determining whether an expression is properly parenthesised.

===

# Hold my parentheses

Given a string representing a parenthesised expression like `"(3*(x+42)"`, how can we figure out if the expression is correctly parenthesised?

In this article I want to explore a solution that doesn't look like your usual piece of Python code.
We'll build it up and we'll start with a list of Booleans that flag the positions of opening parentheses:

```pycon
>>> expression = "(3*(x+42)"
>>> [c == "(" for c in expression]
[True, False, False, True, False, False, False, False, False]
>>> list(zip(expression, _))
[
    ('(', True),
    ('3', False),
    ('*', False),
    ('(', True),
    ('x', False),
    ('+', False),
    ('4', False),
    ('2', False),
    (')', False)
]
```

We can do a similar thing for closing parentheses:

```pycon
>>> [c == ")" for c in expression]
[False, False, False, False, False, False, False, False, True]
>>> list(zip(expression, _))
[
    ('(', False),
    ('3', False),
    ('*', False),
    ('(', False),
    ('x', False),
    ('+', False),
    ('4', False),
    ('2', False),
    (')', True)
]
```

If we subtract the two, we can create a list with the values `1`, `0`, and `-1`, that tell you where we have opening parentheses or closing parentheses:

```pycon
>>> closes = [c == ")" for c in expression]
>>> opens = [c == "(" for c in expression]
>>> [o - c for o, c in zip(opens, closes)]
[1, 0, 0, 1, 0, 0, 0, 0, -1]
>>> list(zip(expression, _))
[
    ('(', 1),
    ('3', 0),
    ('*', 0),
    ('(', 1),
    ('x', 0),
    ('+', 0),
    ('4', 0),
    ('2', 0),
    (')', -1)
]
```

Notice how we have the `1` next to the characters `"("`, `-1` next to the characters `")"`, and `0` next to every other character.

It's also worth noting that the expression above only works because Booleans are integers and so we can write things like the following:

```pycon
>>> False - False
0
>>> True - False
1
>>> False - True
-1
```

To make everything even more interesting, we can fuse the previous expressions into a single comprehension:

```pycon
>>> [(c == "(") - (c == ")") for c in expression]
[1, 0, 0, 1, 0, 0, 0, 0, -1]
>>> list(zip(expression, _))
[
    ('(', 1),
    ('3', 0),
    ('*', 0),
    ('(', 1),
    ('x', 0),
    ('+', 0),
    ('4', 0),
    ('2', 0),
    (')', -1)
]
```

With this, we can compute the final solution with a single `sum`:

```pycon
>>> sum((c == "(") - (c == ")") for c in expression)
1
```

If the final result is positive, it means we have opened parentheses that haven't been closed.
If the final result is negative, it means we closed too many parentheses.
If the final result is exactly `0`, it means the parentheses are balanced.

Here are more examples:

```pycon
>>> sum((c == "(") - (c == ")") for c in "(3 + 6)")
0
>>> sum((c == "(") - (c == ")") for c in "(3 + 6)))")
-2
>>> sum((c == "(") - (c == ")") for c in "(((3 + 6)")
2
```

What if we want to check if the parentheses are balanced _**and**_ opened in the correct order?
For example, the expression `")))((("` has as many opening parentheses as it has closing parentheses but they are in the wrong order.
How do we check this?

The key to solving this is understanding that instead of getting the final sum with the built-in `sum`, we can use a tool like `accumulate` from the module `itertools` to compute partial sums:

```pycon
>>> from itertools import accumulate
>>> list(accumulate(
...     (c == "(") - (c == ")") for c in ")))((("
... ))
[-1, -2, -3, -2, -1, 0]
```

This technique tells you the depth of nesting at any point, which should only be `0` or positive.
Thus, looking for a `-1` tells you if the nesting is invalid:

```pycon
>>> -1 in accumulate((c=="(") - (c == ")") for c in ")))(((")
True
```

For the sake of completeness, below is an example where we check the nesting depth of a valid expression.
Notice how the number increases whenever we find an opening parenthesis and how it decreases whenever we find a closing parenthesis:

```pycon
>>> expression = "(3*(x+y*(z+w)))"
>>> list(zip(expression, accumulate((c=="(") - (c == ")") for c in expression)))
[
    ('(', 1),
    ('3', 1),
    ('*', 1),
    ('(', 2),
    ('x', 2),
    ('+', 2),
    ('y', 2),
    ('*', 2),
    ('(', 3),
    ('z', 3),
    ('+', 3),
    ('w', 3),
    (')', 2),
    (')', 1),
    (')', 0)
]
```

Putting both checks together, you have to be careful not to try and consume the same generator twice, so we could do something like:

```py
from itertools import accumulate

def has_valid_nesting(expression):
    parens = [(c=="(") - (c == ")") for c in expression]
    return -1 not in accumulate(parens) and sum(parens) == 0
```

This quirky piece of Python code, which isn't horrible but is certain to annoy some people because of the arithmetic operations on Boolean values, is inspired by this famous APL line:

```apl
      +⌿-⌿ '()' ∘.= '(3 + 6)))'
¯2
```

If you get the chance, take a look at [APL](https://aplwiki.com/wiki/).
It's a lot of fun!
