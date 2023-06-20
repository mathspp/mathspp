Today I learned what open recursion is and how to leverage it.

===

# Open recursion

Open recursion is a technique in which two methods of an object call each other recursively.
The power of this technique resides in the fact that the two methods can be implemented independently.

A silly example of open recursion (in Python) is as follows:

```py
class Fib:
    def _even(self, n):
        if n == 0:
            return 1
        else:
            return self._odd(n - 1) + self._even(n - 2)

    def _odd(self, n):
        if n == 1:
            return 1
        else:
            return self._even(n - 1) + self._odd(n - 2)

    def compute(self, n):
        if n % 2:
            return self._odd(n)
        else:
            return self._even(n)

print(Fib().compute(16))
```

Notice that `_even` calls `_odd` and `_odd` calls `_even`, and that is the open recursion pattern.
Now, this example is pretty silly.
Are there good uses for open recursion?


# Using open recursion in interpretation of programs

Going over [“Essentials of Compilation, An Incremental Approach in Python”](https://github.com/IUCompilerCourse/Essentials-of-Compilation), we create a simple interpreter in Chapter 1 that can handle additions and subtractions of integers, along with a couple of other things.

Such an interpreter could be written recursively, somewhat like this:

```py
from ast import Add, BinOp, Constant

def interpret_1(expr, env):
    match expr:
        case Constant(value):
            return value
        case BinOp(lexpr, Add(), rexpr):
            return interpret_1(lexpr, env) + interpret_1(rexpr, env)
        ...
```

Then, in Chapter 2, the author wants us to extend this interpreter to also handle variables.
A naive approach would be to handle variables explicitly in `interpret_2` and then defer to `interpret_1` for the other cases, like so:

```py
def interpret_2(expr, env):
    match expr:
        case Name(id):
            return env[id]
        case _:
            return interpret_1(expr, env)
```

However, this approach will _not_ work!
What if there is a variable that appears further down the tree?
As soon as the function `interpret_2` calls `interpret_1`, we arrive at a place where variables cannot be handled!

For example, the tree `tree1` below can be handled by `interpret_2` but the tree `tree2` cannot, because it will be dispatched to `interpret_1` and then `interpret_1` will not know how to handle the `Name("foo")`.


```py
from ast import Add, BinOp, Constant, Name

tree1 = Name("foo")

tree2 = BinOp(
    Name("foo"),
    Add(),
    Constant(5),
)
```

Instead of laying out our code with the two independent functions, we can have an interpreter class that is inherited whenever we want to extend our interpreter, and we use **open recursion** to make sure that the “old” interpret function gets to leverage _its own override_!

Here is the code:

```py
from ast import Add, BinOp, Constant, Name

class Interpreter1:
    def interpret(self, expr, env):
        match expr:
            case Constant(value):
                return value
            case BinOp(lexpr, Add(), rexpr):
                return self.interpret(lexpr, env) + self.interpret(rexpr, env)

class Interpreter2(Interpreter1):
    def interpret(self, expr, env):
        match expr:
            case Name(id):
                return env[id]
            case _:
                return super().interpret(expr, env)
```

The key here is that `Interpreter2.interpret` will call `Interpreter1.interpret` via the `super().interpreter` call.
In turn, inside that call, the `self.interpret` will refer to `Interpreter2.interpret`, which will allow us to go back and forth between the two implementations of `interpret`.

Here is an example, after adding `print` statements at the top of each `interpret` method:

```py
tree = BinOp(
    Name("foo"),
    Add(),
    Constant(5),
)

print(Interpreter2().interpret(tree, {"foo": 5}))
"""
Interpreter2.interpret
Interpreter1.interpret
Interpreter2.interpret
Interpreter2.interpret
Interpreter1.interpret
10
"""
```

Quite cool, huh?


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
