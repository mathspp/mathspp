In the 5th part of [this series][series-link] of building a Python compiler and interpreter we will add support for multiple statements in our program.

===


# Building a Python compiler and interpreter – 05 statements

This is the 5th article of the [“Building a Python compiler and interpreter” series][series-link], so make sure you've gone through the first four articles before tackling this one!

The code that serves as a starting point for this article is [the tag v0.4.0 of the code in this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.4.0).


## Objectives

The objective for this article is to make sure that our program can be composed of a series of statements (separated by newlines, as Python does).
As it stands, we can only run a single line of code:

```bash
❯ python -m python.interpreter "1 + 2
3 + 4
5 + 6"
RuntimeError: Can't tokenize '\n'.
```

We'll change this in this article.


## Handling multiple statements

### Tokenizing

In order to handle multiple statements we need to be able to tokenize the statement separators, which are newlines.
Thus, we start by introducing that token type:

```py
class TokenType(StrEnum):
    # ...
    NEWLINE = auto()  # statement separator
```

Now, we might want to add the newline character `"\n"` to the mapping `CHARS_AS_TOKENS`.
This should be enough to allow tokenizing newlines.

However, if we do that, we'll produce as many `NEWLINE` tokens as there are newlines in the code, even if we have multiple empty lines in a row.
This isn't helpful, because we only care about newlines that appear _after_ some code.

We'll modify the tokenizer to cope with this.
We'll create an attribute `beginning_of_line` that determines whether we've produced any tokens on this line or not.
If we hit a newline character and `beginning_of_line` is `True`, that's because we haven't done anything on this line yet and thus we don't want to produce a `NEWLINE` token.

So, the tokenizer is modified to look like this:

```py
class Tokenizer:
    def __init__(self, code: str) -> None:
        self.code = code
        self.ptr: int = 0
        self.beginning_of_line = True

    # ...

    def next_token(self) -> Token:
        while self.ptr < len(self.code) and self.code[self.ptr] == " ":
            self.ptr += 1

        if self.ptr == len(self.code):
            return Token(TokenType.EOF)

        # Handle the newline case.
        char = self.code[self.ptr]
        if char == "\n":
            self.ptr += 1
            if not self.beginning_of_line:
                self.beginning_of_line = True
                return Token(TokenType.NEWLINE)
            else:  # If we're at the BoL, get the next token instead.
                return self.next_token()

        # If we got to this point, we're about to produce another token
        # so we can set BoL to False.
        self.beginning_of_line = False
        if self.peek(length=2) == "**":
            self.ptr += 2
            return Token(TokenType.EXP)
        # Other cases here...
```

! In case you're wondering, it's not trivial to figure out this was the “best” thing to do when you're inexperienced (like I am).
! Many times, I decide to do things in a certain way, and when I make some progress I realise that I should've done it in a different way.
! I'm just trying to short-circuit _some_ of those bad decisions in these articles...
! Although they're bound to happen!

Now, we add tests for the tokenizer.
We need to make sure we can tokenize the newline and we also need to make sure that empty/consecutive newlines are ignored:

```py
@pytest.mark.parametrize(
    "code",
    [
        ("\n\n\n1 + 2\n3 + 4\n"),  # Extras at the beginning.
        ("1 + 2\n\n\n3 + 4\n"),  # Extras in the middle.
        ("1 + 2\n3 + 4\n\n\n"),  # Extras at the end.
        ("\n\n\n1 + 2\n\n\n3 + 4\n\n\n"),  # Extras everywhere.
    ],
)
def test_tokenizer_ignores_extra_newlines(code: str):
    tokens = list(Tokenizer(code))
    assert tokens == [
        Token(TokenType.INT, 1),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 2),
        Token(TokenType.NEWLINE),
        Token(TokenType.INT, 3),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 4),
        Token(TokenType.NEWLINE),
        Token(TokenType.EOF),
    ]
```


### Parsing

Up next, we need to update our grammar and then the parser.
This is what the grammar looks like:

```
program := computation
computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom EXP unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

Now, we want to say that a program is no longer just a computation, but rather an arbitrary number of computations.
We can do this by writing this new grammar:

```
program := statement* EOF

statement := expr_statement
expr_statement := computation NEWLINE

computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom EXP unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

As it stands, the rule `statement` has only one option (`expr`), but that will change as soon as we add things like assignments, conditionals, and loops.

Now, we want to add/change the parse methods associated with the rules that we created/changed:

 - `program`
 - `statement`
 - `expr`

But first, we need to create the appropriate tree nodes for the parser to produce:

 - we'll say that a `Program` contains a list of statements;
 - we'll create a node `Statement` that won't be instantiated directly but that will be inherited from by the different types of statements; and
 - we'll create a node `ExprStatement` (that inherits from `Statement`) for expressions that stand alone as statements.

But why don't I just change `Expr` to inherit from `Statement`?
Because that would mean that things like `BinOp` would also be statements, and that's not accurate.
Expressions _can stand as statements_, but there are many places that accept expressions and don't accept statements, so it is awkward to create a hierarchy where all expressions are statements.

So, I thought about it and realised I preferred this way.
Is it the best way to go about it?
I have no idea! 🤣

Here are the new tree nodes:

```py
from __future__ import annotations
# ...


@dataclass
class Program(TreeNode):
    statements: list[Statement]


@dataclass
class Statement(TreeNode):
    pass


@dataclass
class ExprStatement(Statement):
    expr: Expr
```

Now that we have the appropriate nodes, we can parse the new/modified grammar rules:

```py
class Parser:
    # ...

    def parse_expr_statement(self) -> ExprStatement:
        """Parses a standalone expression."""
        expr = ExprStatement(self.parse_computation())
        self.eat(TokenType.NEWLINE)
        return expr

    def parse_statement(self) -> Statement:
        """Parses a statement."""
        return self.parse_expr_statement()

    def parse(self) -> Program:  # <-- changed
        """Parses the program."""
        program = Program([])
        while self.peek() != TokenType.EOF:
            program.statements.append(self.parse_statement())
        self.eat(TokenType.EOF)
        return program
```

Now we should run our tests to make sure that nothing is broken...
And 50 tests fail!
What the hell just happened?!

We just changed the way a full program is parsed, so our previous tests are likely to break because they assumed programs were parsed in a specific way.
There are two ways to fix these tests:

 1. Fix them so that they still represent full programs.
 2. Instead of calling the top-level method `Parser.parse`, we call the method that is responsible for parsing that part of the tree.

I opted to go with option 2., which meant that I had to remove the `EOF` token from all parser tests and replace the call to `parse` with a call to `parse_computation`.

For example, consider the test `test_parsing_addition`:

```py
def test_parsing_addition():
    tokens = [
        Token(TokenType.INT, 3),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 5),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == BinOp(
        "+",
        Int(3),
        Int(5),
    )
```

The test now looks like this:

```py
def test_parsing_addition():
    tokens = [
        Token(TokenType.INT, 3),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 5),
    ]
    tree = Parser(tokens).parse_computation()
    assert tree == BinOp(
        "+",
        Int(3),
        Int(5),
    )
```

Now, running the tests again will reveal that all (or most) interpreter tests are broken, so we still need to fix that.
The changes we made to our grammar now require that a program end with a newline, which is a new restriction, which is why the interpreter tests are complaining about `NEWLINE` and `EOF` tokens.

The grammar could be rewritten so that the program doesn't need to end with a newline, but right now I can't figure out how to do that in a decent way.
Instead, we can be cheeky and append a newline character `"\n"` to the end of a piece of code when we instantiate a tokenizer:

```py
class Tokenizer:
    def __init__(self, code: str) -> None:
        self.code = code + "\n"  # Ensure the program ends with a newline.
```

Now, all our programs end with a newline, which means whenever we tokenize a program, it's going to end with a newline token and _then_ the end of file token.
This means we need to quickly fix all tokenizer tests.

After we do that, we're left with fixing the interpreter tests.
However, the interpreter tests broke because we are producing a new tree structure (a more complete one) and the compiler can't compile it yet.

So, we actually just need to carry on.
Before moving to the compiler, we'll just fix our auxiliary function `print_ast`:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    node_name = tree.__class__.__name__
    match tree:
        case Program(statements):
            print(f"{indent}{node_name}([\n", end="")
            for statement in statements:
                print_ast(statement, depth + 1)
                print(",")
            print(f",\n{indent}])", end="")
        case ExprStatement(expr):
            print(f"{indent}{node_name}(\n", end="")
            print_ast(expr, depth + 1)
            print(f",\n{indent})", end="")
        case UnaryOp(op, value):
            print(f"{indent}{node_name}(\n{indent}    {op!r},")
            print_ast(value, depth + 1)
            print(f",\n{indent})", end="")
        case BinOp(op, left, right):
            print(f"{indent}{node_name}(\n{indent}    {op!r},")
            print_ast(left, depth + 1)
            print(",")
            print_ast(right, depth + 1)
            print(f",\n{indent})", end="")
        case Int(value) | Float(value):
            print(f"{indent}{node_name}({value!r})", end="")
        case _:
            raise RuntimeError(f"Can't print a node of type {node_name}")
    if depth == 0:
        print()
```

At the bottom of the file `parser.py` I added this code to test this:

```py
if __name__ == "__main__":
    from .tokenizer import Tokenizer

    code = """1 % -2
5 ** -3 / 5
1 * 2 + 2 ** 3"""
    parser = Parser(list(Tokenizer(code)))
    print_ast(parser.parse())
```

Running the code with `python -m python.parser` will produce this output:

```py
Program([
    ExprStatement(
        BinOp(
            '%',
            Int(1),
            UnaryOp(
                '-',
                Int(2),
            ),
        ),
    ),
    ExprStatement(
        BinOp(
            '/',
            BinOp(
                '**',
                Int(5),
                UnaryOp(
                    '-',
                    Int(3),
                ),
            ),
            Int(5),
        ),
    ),
    ExprStatement(
        BinOp(
            '+',
            BinOp(
                '*',
                Int(1),
                Int(2),
            ),
            BinOp(
                '**',
                Int(2),
                Int(3),
            ),
        ),
    ),
])
```

After inspecting the tree and making sure it looks right, we can use this as a new test for the parser:

```py
def test_parsing_multiple_statements():
    code = "1 % -2\n5 ** -3 / 5\n1 * 2 + 2 ** 3\n"
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(...)  # Tree from above.
```


### Compiling

The next step is making sure the compiler knows how to handle the new tree nodes `Program` and `ExprStatement`, but thankfully they are both trivial!
To compile a node `Program`, we just need to compile each statement in order:

```py
class Compiler:
    # ...

    def compile_Program(self, program: Program) -> BytecodeGenerator:
        for statement in program.statements:
            yield from self._compile(statement)
```

The node `ExprStatement` is maybe even simpler!
We just need to compile its expression...
_And_ add a `POP` instruction.
Why?

Suppose that the `POP` instruction wasn't there.
Suppose that compiling a node `ExprStatement` amounts to simply compiling its expression:

```py
class Compiler:
    # ...

    def compile_ExprStatement(self, expression: ExprStatement) -> BytecodeGenerator:
        yield from self._compile(expression.expr)
```

Now, if you run a piece of code with multiple expressions, what will you see at the end of the program execution..?
Here's one example:

```bash
❯ python -m python.interpreter "1 + 2
∙ 3 + 4
∙ 5 + 6"
Done!
Stack([3, 7, 11])
```

Because there were three lines of code, the final stack has _three_ elements in there!
The temporary results of previous expressions were left lingering in the stack.
This doesn't make much sense, so we should create a bytecode operator `POP` whose only job is to pop the top element from the stack.

So, we create the new bytecode operator and we use it when compiling nodes of the type `ExprStatement`:

```py
class BytecodeType(StrEnum):
    # ...
    POP = auto()

# ...

class Compiler:
    # ...

    def compile_ExprStatement(self, expression: ExprStatement) -> BytecodeGenerator:
        yield from self._compile(expression.expr)
        yield Bytecode(BytecodeType.POP)
```

Now, we can write a test for the compilation of nodes of the type `Program` and `ExprStatement`:

```py
def test_compile_program_and_expr_statement():
    tree = Program(
        [
            ExprStatement(Int(1)),
            ExprStatement(Float(2.0)),
            ExprStatement(BinOp("+", Float(3.0), Float(4.0))),
        ]
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 1),
        Bytecode(BytecodeType.POP),
        Bytecode(BytecodeType.PUSH, 2.0),
        Bytecode(BytecodeType.POP),
        Bytecode(BytecodeType.PUSH, 3.0),
        Bytecode(BytecodeType.PUSH, 4.0),
        Bytecode(BytecodeType.BINOP, "+"),
        Bytecode(BytecodeType.POP),
    ]
```

Finally, we can interpret our programs...


### Interpreting

The only change that needs to be made to the interpreter is so that we can handle the new bytecode operator `POP`:

```py
from typing import Any

# ...

class Interpreter:
    def __init__(self, bytecode: list[Bytecode]) -> None:
        # ...
        self.last_value_popped: Any = None

    # ...

    def interpret_pop(self, bc: Bytecode) -> None:
        self.last_value_popped = self.stack.pop()
```

You might have noticed that when interpreting a pop, I assign the popped value to the attribute `last_value_popped`.

Since we don't have support for variables or printing yet, this attribute is used for debugging, so that we can know what was the value that was last popped from the stack.
This will also make sure we manage to fix all the interpreter errors we were having.

In fact, if you replace the return of `interpreter.stack.pop()` with `interpreter.last_value_popped` in the auxiliary function `run_computation` in the file `tests/test_interpreter.py`, the interpreter tests should all pass now:

```py
def run_computation(code: str) -> int:
    tokens = list(Tokenizer(code))
    tree = Parser(tokens).parse()
    bytecode = list(Compiler(tree).compile())
    interpreter = Interpreter(bytecode)
    interpreter.interpret()
    return interpreter.last_value_popped  # <-- Changed!
```

Now we run the tests:

```bash
❯ pytest .
========================== test session starts ==========================
platform darwin -- Python 3.12.0, pytest-7.4.2, pluggy-1.3.0
rootdir: /Users/rodrigogs/Documents/python
plugins: anyio-3.7.1
collected 103 items                                                     

tests/test_compiler.py ...........                                [ 10%]
tests/test_interpreter.py ....................................    [ 45%]
tests/test_parser.py ......................                       [ 66%]
tests/test_tokenizer.py ..................................        [100%]

========================== 103 passed in 0.05s ==========================
```

We might also change the end of the method `interpret` to print the last value popped instead of the stack:

```py
class Interpreter:
    # ...

    def interpret(self) -> None:
        # ...

        print("Done!")
        print(self.last_value_popped)
```

Right now, we won't be adding tests where code has multiple statements because we don't have a meaningful way of making sure that the intermediate statements are producing the correct results (although it is very likely that they are).
Instead, we'll wait until we have variable assignment (which is coming up next!) to check that.


## Recap

In this article we've rewritten our program so that we are able to handle multiple statements.
This entailed:

 - adding a new token type for newline characters;
 - modifying the tokenizer to ignore consecutive empty lines;
 - modifying the tokenizer to force all programs to end with a newline character;
 - rewrite the language grammar to allow for a program to be composed of multiple statements;
 - introducing new tree nodes to represent a program, a statement, and a statement which is solely composed of an expression;
 - compiling the new node types;
 - creating a new bytecode operator; and
 - changing the interpreter to handle the new bytecode operator.

This article was an excellent example of how something that looks rather simple can have a great impact on the program.

You can get the code for this article at [tag v0.5.0 of this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.5.0).


## Next steps

In the next article we will add variable assignment and access.
Then, we'll start looking at `if` statements and `while` loops!

[The exercises below](#exercises) will challenge you to try and implement a couple of features that we will implement next, so go ahead and take a look at those.


## Exercises

 - Change the tokenizer to start recognising sequences of characters as variable names.
 - Can you create a set of names that are not valid variable names because they are keywords?
 - Change the grammar so that a program is made up of computations and variable assignments and update the parser accordingly.
 - Change the grammar so that a “number” is actually a number or a variable reference and update the parser accordingly.


[series-link]: /blog/tags/bpci
