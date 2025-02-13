The 10th article of [this series][series-link] adds support for `elif` and `else` statements.

===


# Building a Python compiler and interpreter – 10 `elif` and `else`

This is the 10th article of the [“Building a Python compiler and interpreter” series][series-link], so make sure you've gone through the first nine articles before tackling this one!

The code that serves as a starting point for this article is [the tag v0.9.0 of the code in this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.9.0).


## Objectives

The objective for this article is:

 - to add support for the conditional statements `elif` and `else`.

Let's get into it.


## Adding support for `else`

In a conditional statement, the code that follows the `else` is a list of statements that we want to run if the Boolean expression of the conditional statement evaluated to `False`.
We can add this by expanding the node `Conditional` to accomodate for a (possibly non-existent) `else` body:

```py
# parser.py

@dataclass
class Conditional(Statement):
    condition: Expr
    body: Body
    orelse: Body | None = None  # <-- New attribute.
```

And in order to get here, we need to be able to tokenize this new keyword:

```py
# tokenizer.py

class TokenType(StrEnum):
    # ...
    ELSE = auto()  # else

# ...

KEYWORDS_AS_TOKENS: dict[str, TokenType] = {
    # ...
    "else": TokenType.ELSE,
}
```

To be able to parse the `else` and its body, we split the rule `conditional` into two new rules: `if_statement` and `else_statement`.
Then, the original `conditional` puts both together:

```
# ...
conditional := if_statement else_statement?

if_statement := IF expr COLON NEWLINE body
else_statement := ELSE COLON NEWLINE body
# ...
```

This means that the old parser method `parse_conditional` now becomes `parse_if_statement`, and we need to create two new parser methods, `parse_else_statement` and `parse_conditional`:

```py
# parser.py

# ...

class Parser:
    # ...

    def parse_else_statement(self) -> Body:  # <-- New method.
        """Parses an `else` statement and returns its body."""
        self.eat(TokenType.ELSE)
        self.eat(TokenType.COLON)
        self.eat(TokenType.NEWLINE)
        body = self.parse_body()
        return body

    def parse_if_statement(self) -> Conditional:  # <-- Was `parse_conditional`.
        """Parses an `if` conditional and returns it."""
        self.eat(TokenType.IF)
        condition = self.parse_expr()
        self.eat(TokenType.COLON)
        self.eat(TokenType.NEWLINE)
        body = self.parse_body()
        return Conditional(condition, body)

    def parse_conditional(self) -> Conditional:  # <-- New method.
        """Parses a conditional block."""
        if_statement = self.parse_if_statement()

        if self.peek() == TokenType.ELSE:  # The keyword `else` might not be there.
            else_statement = self.parse_else_statement()
            if_statement.orelse = else_statement

        return if_statement
```


## Compiling and interpreting the statement `else`

Now, we need to compile this new attribute `orelse` that the nodes `Conditional` might have.
This involves some jumping around, similar to how we handled Boolean short-circuiting.
If the condition is false, we need to jump past the body of the `if`.
If we don't jump past the `if`, then we reach the end we need to jump past the `else`.

This is the code that implements these two jumps:

```py
# compiler.py

# ...


class Compiler:
    # ...

    def compile_Conditional(self, conditional: Conditional) -> BytecodeGenerator:
        condition_bytecode = self._compile(conditional.condition)
        body_bytecode = list(self._compile(conditional.body))
        orelse = conditional.orelse
        orelse_bytecode = [] if orelse is None else list(self._compile(orelse))

        # Add a “jump past the else” at the end of the `if` when needed:
        if orelse_bytecode:
            body_bytecode.append(
                Bytecode(BytecodeType.JUMP_FORWARD, len(orelse_bytecode) + 1)
            )

        yield from condition_bytecode
        yield Bytecode(  # If the condition is false, jump past the body of the `if`.
            BytecodeType.POP_JUMP_IF_FALSE, len(body_bytecode) + 1
        )
        yield from body_bytecode
        yield from orelse_bytecode
```

For this to work, we need to introduce the new bytecode type that jumps forward no matter what:

```py
# compiler.py

class BytecodeType(StrEnum):
    # ...
    JUMP_FORWARD = auto()
```

To interpret this new bytecode type, we just increment the program pointer:

```py
# interpreter.py

class Interpreter:
    # ...

    def interpret_jump_forward(self, bc: Bytecode) -> None:
        self.ptr += bc.value
```


## Adding support for `elif`

Now, with all of this work, we still need to implement the statement `elif`.
What's quite neat is that you can get the `elif` almost for free.
To understand how, look at the following snippet of code:

```py
if cond1:
    body1
elif cond2:
    body2
else:
    body3
```

The statement `elif` is just syntactic sugar for nested `if` statements:

```py
if cond1:
    body1
else:
    if cond2:
        body2
    else:
        body3
```

And since we already know how to compile `if` and `else`, it is enough for us to tokenize the keyword `elif` and parse it into this structure.
Since the nested `if` will be parsed into a `Conditional`, which is a statement, we can plug that nested `Conditional` straight into the attribute `orelse` of the outer `Conditional`!

But let's take this one step at a time.
First, we tokenize the new keyword:

```py
# tokenizer.py

class TokenType(StrEnum):
    # ...
    ELIF = auto()  # elif

# ...

KEYWORDS_AS_TOKENS: dict[str, TokenType] = {
    # ...
    "elif": TokenType.ELIF,
}
```

Next, we modify the grammar to include a rule `elif_statement` that is almost equal to the rule `if_statement`:

```
# ...
if_statement := IF expr COLON NEWLINE body
elif_statement := ELIF expr COLON NEWLINE body
else_statement := ELSE COLON NEWLINE body
# ...
```

The rule `conditional` now needs to take the `elif` statements into account, which may be in any number:

```
# ...
conditional := if_statement ( elif_statement )* else_statement?
# ...
```

The new parser method that parses `elif` statements is also almost equal to the method that parses `if` statements, since the respective grammar rules are so similar.

Since we want to leverage the fact that an `elif` can be seen as a nested `if`, we don't need to create a new AST node for the `elif` statements.
Instead, we can just parse an `elif` into a node of the type `Conditional`:

```py
# parser.py

# ...

class Parser:
    # ...

    def parse_elif_statement(self) -> Conditional:  # <-- New method.
        """Parses an `elif` conditional and returns it."""
        self.eat(TokenType.ELIF)
        condition = self.parse_expr()
        self.eat(TokenType.COLON)
        self.eat(TokenType.NEWLINE)
        body = self.parse_body()
        return Conditional(condition, body)
```

The fun part comes now.
How do we parse an unknown number of `elif` statements?
How do we build the tree for this?

Since an `elif` produces a nested `Conditional`, we take that `Conditional` and put it inside the `orelse` of the node `Conditional` that belongs to the `if`.
This builds a structure that can look more or less like this:

```py
Conditional(
    cond1,               # if cond1:
    body1,               #     body1:
    orelse=Conditional(
        cond2,           # elif cond2:
        body2,           #     body2
    ),
)
```

Then, if there are any more `elif` statements or an `else`, those get plugged into the attribute `orelse` of the _inner_ `Conditional`!
Thus, to parse a full conditional at this point, we

1. start by parsing the `if` statement;
2. we parse as many `elif` statements as there are, and every time we do:
   1. we put the `elif` conditional in the attribute `orelse` of the previous node of type `Conditional`; and
   2. we update the node `Conditional` that has an available attribute `orelse` in case we need to nest even more statements;
3. finally, we parse an `else` if needed and put it in the attribute `orelse` of the innermost node of type `Conditional`.

Here is the code for this algorithm:

```py
# parser.py

# ...

class Parser:
    # ...

    def parse_conditional(self) -> Conditional:
        """Parses a conditional block."""
        top_conditional = self.parse_if_statement()

        innermost_conditional = top_conditional
        while self.peek() == TokenType.ELIF:
            elif_statement = self.parse_elif_statement()
            innermost_conditional.orelse = Body([elif_statement])
            innermost_conditional = elif_statement
        if self.peek() == TokenType.ELSE:
            else_statement = self.parse_else_statement()
            innermost_conditional.orelse = else_statement

        return top_conditional
```

And because we didn't introduce any changes to the structure of the AST, our compiler does not need to be modified and nor does the interpreter!


## Some tests

Don't tell anyone I said this, but I'm getting tired of writing tests for these things!
And looking back on a couple of previous articles, testing the specific shape of the AST or the structure of the bytecode can be dangerous if we need to do non-trivial refactors later...

I need to fix this eventually, but for now I wrote a couple more tests.
They're fairly long and they aren't necessarily super interesting, so I'll just link to the tests on GitHub instead of pasting them here:

 - [Tokenizer tests](https://github.com/mathspp/building-a-python-compiler-and-interpreter/compare/v0.9.0...v0.10.0#diff-21366b599c57ca8ad798657d2f6a1d7e2c479ba849513f67af3f7a844fb271a7).
 - [Parser tests](https://github.com/mathspp/building-a-python-compiler-and-interpreter/compare/v0.9.0...v0.10.0#diff-237d6caeea59e3cf8f303958b4b29e752861048496a047a6af38a12915a02e32).
 - [Compiler tests](https://github.com/mathspp/building-a-python-compiler-and-interpreter/compare/v0.9.0...v0.10.0#diff-e34b71b4a9e5180dd896d166086e60748a08cfb8359021ec17d8ba5f8cb9bbd5).
 - [Interpreter tests](https://github.com/mathspp/building-a-python-compiler-and-interpreter/compare/v0.9.0...v0.10.0#diff-d54bbc4862f37675b5d0a8512cf7e7ad8c6d6fbc4f8dcdc3ac5b0e205e90d0f1).


## Recap

In this article we:

 - added support for the keyword `else`;
 - understood that the statement `elif` is equivalent to a nested `if`; and
 - exploited this relationship to implement `elif` at the expense of `if` and `else` only, without modifying the AST, the compiler, and the interpreter, further.

You can get the code for this article at [tag v0.10.0 of this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.10.0).


## Next steps

The very next article will be slightly different because I want to improve the setup of this project.
We've been spending enough time here that it is worth introducing a project management tool to help us with packaging, dependencies, and more.
Then, I will look at comparison operators, [chained comparison operators](/blog/pydonts/chaining-comparison-operators), and then, functions.

[The exercises below](#exercises) will challenge you to try and implement a couple of features that we will implement eventually, so go ahead and take a look at those.


## Exercises

 - Try to implement the 6 comparison operators `==`, `!=`, `<`, `<=`, `>`, `>=`.
 - Try to implement [chained comparisons](/blog/pydonts/chaining-comparison-operators).
 - Can you implement [conditional expressions](/blog/pydonts/conditional-expressions)?
 - Try to add support for the `while` loop. (You can go crazy and also try to add the keywords `break` and `continue`.)


[series-link]: /blog/tags/bpci
