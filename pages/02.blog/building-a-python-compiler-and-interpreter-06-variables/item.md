In the sixth part of [this series][series-link] of building a Python compiler and interpreter we will add support for variables.

===


# Building a Python compiler and interpreter – 06 variables

This is the 6th article of the [“Building a Python compiler and interpreter” series][series-link], so make sure you've gone through the first five articles before tackling this one!

The code that serves as a starting point for this article is [the tag v0.5.0 of the code in this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.5.0).


## Objectives

The objectives for this article all revolve around adding support for variables:

 - tokenising variable names;
 - tokenising assignments;
 - parsing variable assignment and variable references;
 - add compilation support for the assignment statement;
 - modifying the interpreter to introduce a scope; and
 - add support for consecutive assignments of the form `a = b = c = 3`.


## Adding variable assignment

### Tokenizing names

Naturally, the first thing we need to do is make sure we are able to tokenize names from the source code.
This is straightforward to do if we mimic the work we've done for arbitrarily long integers.

First, we create an appropriate token type and identify the characters that can be a part of variable names:

```py
from string import digits, ascii_letters
# ...

class TokenType(StrEnum):
    # ...
    NAME = auto()  # any possible variable name

# ...

LEGAL_NAME_CHARACTERS = ascii_letters + digits + "_"
LEGAL_NAME_START_CHARACTERS = ascii_letters + "_"
```

Python variable names don't need to be restricted to ASCII letters only.
For example, `áñø` is a perfectly valid variable name:

```pycon
>>> áñø = 3
>>> áñø
3
```

But we'll keep it simpler for our own sake.

Then, we create a method `Tokenizer.consume_name` and we use it to tokenize names:

```py
class Tokenizer:
    # ...

    def consume_name(self) -> str:
        """Consumes a sequence of characters that could be a variable name."""
        start = self.ptr
        self.ptr += 1
        while (
            self.ptr < len(self.code) and self.code[self.ptr] in LEGAL_NAME_CHARACTERS
        ):
            self.ptr += 1
        return self.code[start : self.ptr]

    # ...

    def next_token(self) -> Token:
        # ...

        self.beginning_of_line = False
        if self.peek(length=2) == "**":
            self.ptr += 2
            return Token(TokenType.EXP)
        elif char in CHARS_AS_TOKENS:
            self.ptr += 1
            return Token(CHARS_AS_TOKENS[char])
        elif char in LEGAL_NAME_START_CHARACTERS:  # <-- New!
            name = self.consume_name()
            return Token(TokenType.NAME, name)
        # ...
```

Now, I decided to also modify the code at the bottom of the file `tokenizer.py`:

```py
if __name__ == "__main__":
    import sys

    code = sys.argv[1]
    for token in Tokenizer(code):
        print(token)
```

This way, I can just run something like `python -m python.tokenizer a b c _123` and get the output that follows:

```py
Token(TokenType.NAME, 'a')
Token(TokenType.NAME, 'b')
Token(TokenType.NAME, 'c')
Token(TokenType.NAME, '_234')
Token(TokenType.NEWLINE, None)
Token(TokenType.EOF, None)
```

I'll also tweak the method `__repr__` on tokens so that it doesn't show the second argument when it's `None`:

```py
@dataclass
class Token:
    type: TokenType
    value: Any = None

    def __repr__(self) -> str:  # <-- Changed.
        if self.value is not None:
            return f"{self.__class__.__name__}({self.type!r}, {self.value!r})"
        else:
            return f"{self.__class__.__name__}({self.type!r})"
```

The same command from above now produces slightly more condensed output:

```py
Token(TokenType.NAME, 'a')
Token(TokenType.NAME, 'b')
Token(TokenType.NAME, 'c')
Token(TokenType.NAME, '_234')
Token(TokenType.NEWLINE)
Token(TokenType.EOF)
```

Finally, we'll add a couple of tokenizer tests for name tokenizing:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        ("a", Token(TokenType.NAME, "a")),
        ("abc123_", Token(TokenType.NAME, "abc123_")),
        ("_123", Token(TokenType.NAME, "_123")),
        ("_", Token(TokenType.NAME, "_")),
        ("a_2_c_3___", Token(TokenType.NAME, "a_2_c_3___")),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    assert Tokenizer(code).next_token() == token

# ...

def test_tokenizer_names():
    code = "a + 3 - b c12 __d"
    tokens = list(Tokenizer(code))
    assert tokens == [
        Token(TokenType.NAME, "a"),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 3),
        Token(TokenType.MINUS),
        Token(TokenType.NAME, "b"),
        Token(TokenType.NAME, "c12"),
        Token(TokenType.NAME, "__d"),
        Token(TokenType.NEWLINE),
        Token(TokenType.EOF),
    ]
```


### Tokenizing the assignment operator

We must also tokenize the equal sign `=` so that we can write regular assignments!
(I almost forgot!)
Because the equal sign `=` is a single-character operator, this change is quite simple:

```py
class TokenType(StrEnum):
    # ...
    ASSIGN = auto()  # =

# ...

CHARS_AS_TOKENS = {
    # ...
    "=": TokenType.ASSIGN,
}
```

We add a couple of tests and we're done:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        ("=", Token(TokenType.ASSIGN)),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    assert Tokenizer(code).next_token() == token

# ...

def test_tokenizer_assignment_operator():
    code = "a = 3 = = 5"
    tokens = list(Tokenizer(code))
    assert tokens == [
        Token(TokenType.NAME, "a"),
        Token(TokenType.ASSIGN),
        Token(TokenType.INT, 3),
        Token(TokenType.ASSIGN),
        Token(TokenType.ASSIGN),
        Token(TokenType.INT, 5),
        Token(TokenType.NEWLINE),
        Token(TokenType.EOF),
    ]
```


### Grammar rule for the assignment statement

Now, we want to add a grammar rule for the assignment statement.
Thankfully, the grammar is well-prepared for this endeavour.
Here is the current grammar version:

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

What we can do now is extend the rule `statement` to reference a new `assignment` rule:

```
program := statement* EOF

statement := expr_statement | assignment
assignment := NAME ASSIGN computation NEWLINE
expr_statement := computation NEWLINE

computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom EXP unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

This is the new rule we'll be using.
Now, we need to create tree nodes that will make this possible.


### Tree nodes for variables and assignments

What we'll do now is add two new tree nodes.
We'll add a tree node to represent variable names and another node to represent assignments.
Something like this:

```py
@dataclass
class Assignment(Statement):
    target: Variable
    value: Expr


@dataclass
class Variable(Expr):
    name: str
```

We made `Variable` a subclass of expression because we're guessing it will be useful when implementing variable references (which we'll be doing right after we're done with this).

Now, we'll make sure that the function `print_ast` can handle these new node types:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    node_name = tree.__class__.__name__
    match tree:
        # ...
        case Assignment(target, value):
            print(f"{indent}{node_name}(\n", end="")
            print_ast(target, depth + 1)
            print(",")
            print_ast(value, depth + 1)
            print(f",\n{indent})", end="")
        # ...
        case Int(value) | Float(value) | Variable(value):  # <-- Changed.
            # (The `value` of the variable is actually its name...)
            print(f"{indent}{node_name}({value!r})", end="")
        case _:
            raise RuntimeError(f"Can't print a node of type {node_name}")
    if depth == 0:
        print()
```

Now, on to parsing this!


### Parsing assignment statements

We need to modify the method `parse_statement` and we need to introduce a new method `parse_assignment`.
The best way to modify `parse_statement` to determine whether the next statement is an assignment or an expression is to peek one token ahead and see if we can find a token of the type `ASSIGN`.
In that case, we're dealing with an assignment.

In turn, the method `parse_assignment` only needs to eat the expected tokens and then parse an expression as the value of the assignment.
The new changes look like this:

```py
class Parser:
    # ...

    def parse_assignment(self) -> Assignment:
        """Parses an assignment."""
        name_token = self.eat(TokenType.NAME)
        var = Variable(name_token.value)
        self.eat(TokenType.ASSIGN)
        value = self.parse_computation()
        self.eat(TokenType.NEWLINE)
        return Assignment(var, value)

    def parse_statement(self) -> Statement:
        """Parses a statement."""
        if self.peek(skip=1) == TokenType.ASSIGN:
            return self.parse_assignment()
        else:
            return self.parse_expr_statement()
```

Now, if we modify the bottom of the file to include a basic CLI, we can play with this already:

```py
if __name__ == "__main__":
    import sys
    from .tokenizer import Tokenizer

    code = sys.argv[1]
    parser = Parser(list(Tokenizer(code)))
    print_ast(parser.parse())
```

Now, we can see that assignment statements are being parsed correctly.
For example, running the command seen below:

```bash
❯ python -m python.parser "a = 3 
∙ b = 7
∙ d = 2 ** 2 % 4"
```

We get this tree as output:

```py
Program([
    Assignment(
        Variable('a'),
        Int(3),
    ),
    Assignment(
        Variable('b'),
        Int(7),
    ),
    Assignment(
        Variable('d'),
        BinOp(
            '%',
            BinOp(
                '**',
                Int(2),
                Int(2),
            ),
            Int(4),
        ),
    ),
])
```

As we add some tests to the parser, we can use this tree in them:

```py
def test_parsing_simple_assignment():
    tokens = [
        Token(TokenType.NAME, "a"),
        Token(TokenType.ASSIGN),
        Token(TokenType.INT, 5),
        Token(TokenType.NEWLINE),
    ]
    tree = Parser(tokens).parse_assignment()
    assert tree == Assignment(
        Variable("a"),
        Int(5),
    )


def test_program_with_assignments():
    code = "a = 3\nb = 7\nd = 2 ** 2 % 4"
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(...)  # Tree from the example execution above.
```


### Bytecode operators for variable assignment

The only thing we need to compile assignment statements is a bytecode operator that says “hey, save the value at the top of the stack in this variable”.
That's all.
And thus, we can do that by creating a bytecode operator that is called `SAVE`:

```py
class BytecodeType(StrEnum):
    # ...
    SAVE = auto()
```

Then, when we are compiling assignment statements, we start by compiling the expression that corresponds to the value of the assignment, and then we top it off with a bytecode operator that saves that value in the variable given!
Something like this:

```py
class Compiler:
    # ...

    def compile_Assignment(self, assignment: Assignment) -> BytecodeGenerator:
        yield from self._compile(assignment.value)
        yield Bytecode(BytecodeType.SAVE, assignment.target.name)
```

We can take this for a spin by adding the following `if` statement at the bottom of `compiler.py`:

```py
if __name__ == "__main__":
    import sys
    from .tokenizer import Tokenizer
    from .parser import Parser

    code = sys.argv[1]
    compiler = Compiler(Parser(list(Tokenizer(code))).parse())
    for bc in compiler.compile():
        print(bc)
```

Now, running the command `python -m python.compiler "a = 3"` should produce this output:

```py
Bytecode(BytecodeType.PUSH, 3)
Bytecode(BytecodeType.SAVE, 'a')
```

Now, we'll add some tests:

```py
def test_compile_assignment():
    tree = Assignment(
        Variable("_123"),
        Int(3),
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 3),
        Bytecode(BytecodeType.SAVE, "_123"),
    ]


def test_compile_program_with_assignments():
    tree = Program(
        [
            Assignment(Variable("a"), Int(3)),
            ExprStatement(BinOp("**", Int(4), Int(5))),
            Assignment(Variable("b"), Int(7)),
        ]
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 3),
        Bytecode(BytecodeType.SAVE, "a"),
        Bytecode(BytecodeType.PUSH, 4),
        Bytecode(BytecodeType.PUSH, 5),
        Bytecode(BytecodeType.BINOP, "**"),
        Bytecode(BytecodeType.POP, None),
        Bytecode(BytecodeType.PUSH, 7),
        Bytecode(BytecodeType.SAVE, "b"),
    ]
```

We can now wrap this up and move on to interpret variable assignments!


### Introducing a global scope

If we are going to have variables, we need to save their values somewhere.
We'll do this with a plain dictionary that will represent the scope of the program execution.
Because we don't have functions yet, there is only one scope which is the global one!

So, what we'll do now is a dictionary in the attribute `Interpreter.scope` where the interpreter can store variable names:

```py
class Interpreter:
    def __init__(self, bytecode: list[Bytecode]) -> None:
        self.stack = Stack()
        self.scope: dict[str, Any] = {}
        # ...
```


### Saving variable assignments

When interpreting the bytecode operator `SAVE`, we just save the value at the top of the stack inside the dictionary `scope`:

```py
class Interpreter:
    # ...

    def interpret_save(self, bc: Bytecode) -> None:
        self.scope[bc.value] = self.stack.pop()
```

Now, to help us debug the interpreter, we can print the scope dictionary when we're done interpreting a program:

```py
class Interpreter:
    def interpret(self) -> None:
        # ...

        print("Done!")
        print(self.scope)
        print(self.last_value_popped)
```

If you run the command `python -m python.interpreter "a = 3"`, you will be greeted with this output:

```
Done!
{'a': 3}
None
```

This shows that it's working!
You can even test variable overriding:

```
❯ python -m python.interpreter "a = 3 
∙ a = 4
∙ a = 5"

Done!
{'a': 5}
None
```

Now, let us add one or two tests to make sure that this keeps working in the future:

```py
# Auxiliary functions:
def _run(code: str) -> Interpreter:
    tokens = list(Tokenizer(code))
    tree = Parser(tokens).parse()
    bytecode = list(Compiler(tree).compile())
    interpreter = Interpreter(bytecode)
    interpreter.interpret()
    return interpreter


def run_computation(code: str) -> int:
    return _run(code).last_value_popped


def run_get_scope(code: str) -> dict[str, Any]:
    return _run(code).scope

# ...

def test_simple_assignment():
    code = "a = 3"
    scope = run_get_scope(code)
    assert len(scope) == 1
    assert scope["a"] == 3


def test_overriding_assignment():
    code = "a = 3\na = 4\na = 5"
    scope = run_get_scope(code)
    assert len(scope) == 1
    assert scope["a"] == 5


def test_multiple_assignment_statements():
    code = "a = 1\nb = 2\na = 3\nc = 4\na = 5"
    scope = run_get_scope(code)
    assert len(scope) == 3
    assert scope["a"] == 5
    assert scope["b"] == 2
    assert scope["c"] == 4
```

That's it!
We just added support for variable assignment!
Now, we'll work on adding support for variable references.


## Variable references in expressions

### Extending the grammar to use names

To add variable references to expressions, we don't need to touch the tokenizer because we already recognise names.
However, we need to change the language grammar so that variables can show up inside expressions.
Recall that this is the current grammar:

```
program := statement* EOF

statement := expr_statement | assignment
assignment := NAME ASSIGN computation NEWLINE
expr_statement := computation NEWLINE

computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom EXP unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

What we'll do is make sure that variable names can appear next to integers and floats inside the rule `number`, which will be renamed to `value`:

```
program := statement* EOF

statement := expr_statement | assignment
assignment := NAME ASSIGN computation NEWLINE
expr_statement := computation NEWLINE

computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom EXP unary | atom
atom := LPAREN computation RPAREN | value
value := NAME | INT | FLOAT
```


### Accepting variable names as values

We need to rename `parse_number` to `parse_value` and we need to update it so that it parses variables when it finds them:

```py
    def parse_value(self) -> Variable | Int | Float:
        """Parses an integer or a float."""
        if self.peek() == TokenType.NAME:
            return Variable(self.eat(TokenType.NAME).value)
        elif self.peek() == TokenType.INT:
            return Int(self.eat(TokenType.INT).value)
        else:
            return Float(self.eat(TokenType.FLOAT).value)

    def parse_atom(self) -> Expr:
        """Parses a parenthesised expression or a number."""
        if self.peek() == TokenType.LPAREN:
            self.eat(TokenType.LPAREN)
            result = self.parse_computation()
            self.eat(TokenType.RPAREN)
        else:
            result = self.parse_value()  # <-- Updated.
        return result
```

We can already parse programs that reference variables in its code:

```bash
❯ python -m python.parser "a = b + 3"
Program([
    Assignment(
        Variable('a'),
        BinOp(
            '+',
            Variable('b'),
            Int(3),
        ),
    ),
])
```

Let us add that tree as a test:

```py
def test_parse_variable_references():
    code = "a = b + 3"
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(...)  # Tree from the example above.
```


### Compiling programs with variable references

Much like we created a bytecode operator `SAVE` to save a value into the global scope, we'll create a bytecode operator `LOAD` to load a variable value from the scope:

```py
class BytecodeType(StrEnum):
    # ...
    LOAD = auto()
```

Now, we need to implement the rule `compile_Variable` that produces this bytecode operator:

```py
class Compiler:
    # ...

    def compile_Variable(self, var: Variable) -> BytecodeGenerator:
        yield Bytecode(BytecodeType.LOAD, var.name)
```

If we run the same tiny program from before we can see that this is working correctly:

```bash
❯ python -m python.compiler "a = b + 3"
Bytecode(BytecodeType.LOAD, 'b')
Bytecode(BytecodeType.PUSH, 3)
Bytecode(BytecodeType.BINOP, '+')
Bytecode(BytecodeType.SAVE, 'a')
```

Now, we can add this as a test:

```py
def test_compile_variable_reference():
    tree = Program(
        [
            Assignment(
                Variable("a"),
                BinOp(
                    "+",
                    Variable("b"),
                    Int(3),
                ),
            ),
        ]
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.LOAD, "b"),
        Bytecode(BytecodeType.PUSH, 3),
        Bytecode(BytecodeType.BINOP, "+"),
        Bytecode(BytecodeType.SAVE, "a"),
    ]
```


### Interpreting variable references

The final step of this endeavour is to add a method that interprets the bytecode operator we just created.
Thankfully, this is a short addition to the class `Interpreter`:

```py
class Interpreter:
    # ...

    def interpret_load(self, bc: Bytecode) -> None:
        self.stack.push(self.scope[bc.value])
```

And this works spectacularly!
Check it out:

```bash
❯ python -m python.interpreter "a = 3
∙ b = 4
∙ c = a ** b
∙ d = 7 - c"
Done!
{'a': 3, 'b': 4, 'c': 81, 'd': -74}
None
```

Isn't this amazing?

And here are two tests to make sure this keeps working:

```py
@pytest.mark.parametrize(
    ["code", "scope"],
    [
        ("a = 1\nb = 1\nc = a + b", {"a": 1, "b": 1, "c": 2}),
        ("a = 1\nb = a\nc = b\na = 3", {"a": 3, "b": 1, "c": 1}),
    ],
)
def test_assignments_and_references(code: str, scope: dict[str, Any]):
    assert scope == run_get_scope(code)
```


## Consecutive assignments

The final push for this article will be to make sure assignments like `a = b = c = 3` work well.
This will entail changing the grammar, modifying the tree node `Assignment`, creating a new bytecode operator, and then compiling assignment statements in a slightly different way.


### Grammar for consecutive assignments

The reason why we only support one assignment at a time is...
Because the grammar is written like that!
Here's the assignment rule:

```
assignment := NAME ASSIGN computation NEWLINE
```

We'll change the rule so that the sequence `NAME ASSIGN` can appear more than once, which we denote with the symbol `+`:

```
assignment := ( NAME ASSIGN )+ computation NEWLINE
```

This means that an assignment expression starts with one _or more_ names and equal signs which are then followed by a computation and a newline.


### Parsing modifications

We need to change the tree node `Assignment`.
Instead of a _single_ target variable, we'll have a list of target variables:

```py
@dataclass
class Assignment(Statement):
    targets: list[Variable]  # <-- New type and new name!
    value: Expr
```

Notice how we renamed `target` to `targets`!

Next, we need to change the way in which we parse assignment statements to make sure we consume as many consecutive assignments as we can find:

```py
class Parser:
    # ...

    def parse_assignment(self) -> Assignment:
        """Parses an assignment."""
        first = True
        targets: list[Variable] = []
        while first or self.peek(skip=1) == TokenType.NAME:
            first = False
            name_token = self.eat(TokenType.NAME)
            self.eat(TokenType.ASSIGN)
            targets.append(Variable(name_token.value))

        value = self.parse_computation()
        self.eat(TokenType.NEWLINE)
        return Assignment(targets, value)
```

Now, if we fix our `prist_ast`, we can check if this is parsing correctly:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    node_name = tree.__class__.__name__
    match tree:
        # ...
        case Assignment(targets, value):
            print(f"{indent}{node_name}(")
            print(f"{indent}    [")
            for target in targets:
                print_ast(target, depth + 2)
                print(",")
            print(f"{indent}    ],")
            print_ast(value, depth + 1)
            print(f",\n{indent})", end="")
    # ...
```

With `print_ast` fixed we can see that parsing is working correctly:

```bash
❯ python -m python.parser "a = b = c = 3"
Program([
    Assignment(
        [
            Variable('a'),
            Variable('b'),
            Variable('c'),
        ],
        value=Int(3),
    ),
])
```

Before we move on, we just need to make sure we fix the parser tests that used the previous version of `Assignment` (that only had a single target variable) and we can also add the tree above as a new test:

```py
# Fixed tests that used the node Assignment...

def test_consecutive_assignments():
    code = "a = b = c = 3"
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(
        [
            Assignment(
                [
                    Variable("a"),
                    Variable("b"),
                    Variable("c"),
                ],
                Int(3),
            ),
        ]
    )
```


### Copying the top of the stack

Consider the code `a = b = c = 3`.
So far, we used the bytecode operator `SAVE` to pop the top value of the stack and associate it with a given variable name.
However, in the code above we have three variable names and only a single value `3` that will be put at the top of the stack.

What we need to do is to essentially create copies of the value that is at the top of the stack so that there are enough values to associate with all the variables that we have!
So, the first thing we need to do is create a bytecode operator that has the function of creating a copy of the value that is at the top of the stack:

```py
class BytecodeType(StrEnum):
    # ...
    COPY = auto()
```


### Compiling successive statements

The next step is updating the method `compile_Assignment` to make sure that we go through all the possible targets of the assignment node and create enough copies of the value.
The new method looks like this:

```py
class Compiler:
    # ...

    def compile_Assignment(self, assignment: Assignment) -> BytecodeGenerator:
        yield from self._compile(assignment.value)
        # For all but the last, we create a copy before saving.
        for target in assignment.targets[:-1]:
            yield Bytecode(BytecodeType.COPY)
            yield Bytecode(BytecodeType.SAVE, target.name)
        # Last one, we can finally consume the value at the top of the stack.
        yield Bytecode(BytecodeType.SAVE, assignment.targets[-1].name)
```

Then, after changing `Bytecode.__repr__` so that it doesn't show its value if it is `None`, running the command `python -m python.compiler "a = b = c = 3"` produces the following output:

```bash
❯ python -m python.compiler "a = b = c = 3"
Bytecode(BytecodeType.PUSH, 3)
Bytecode(BytecodeType.COPY)
Bytecode(BytecodeType.SAVE, 'a')
Bytecode(BytecodeType.COPY)
Bytecode(BytecodeType.SAVE, 'b')
Bytecode(BytecodeType.SAVE, 'c')
```

Finally, as far as compilation is concerned, we need to fix the tests that instantiated nodes of the type `Assignment` directly and add a new test:

```py
# Fixed tests using `Assignment` directly...

def test_compile_consecutive_assignments():
    tree = Program(
        [
            Assignment(
                [
                    Variable("a"),
                    Variable("b"),
                    Variable("c"),
                ],
                Int(3),
            ),
        ]
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 3),
        Bytecode(BytecodeType.COPY),
        Bytecode(BytecodeType.SAVE, "a"),
        Bytecode(BytecodeType.COPY),
        Bytecode(BytecodeType.SAVE, "b"),
        Bytecode(BytecodeType.SAVE, "c"),
    ]
```


### Interpretation of the `COPY` bytecode operator

And now, the final tweak!
We must implement a method `interpret_copy` so that the new bytecode operator can be interpreted correctly!
Here it is:

```py
class Interpreter:
    # ...

    def interpret_copy(self, _: Bytecode) -> None:
        self.stack.push(self.stack.peek())
```

Now, we add a test to make sure this works:

```py
@pytest.mark.parametrize(
    ["code", "scope"],
    [
        # ...
        ("a = b = c = 3", {"a": 3, "b": 3, "c": 3}),
    ],
)
def test_assignments_and_references(code: str, scope: dict[str, Any]):
    assert scope == run_get_scope(code)
```


## Recap

In this article we've added support variables in a lot of different ways:

 - variable assignment works, after we created new node types and introduced a scope to our interpreter;
 - variable reference works fine and variables can be used within other expressions; and
 - we've also added support for consecutive assignments.

All this work was possible because

 - we extended the tokenizer to recognise names;
 - we created new node types to represent variables and assignments;
 - we created three new bytecode operators to read from and write into the scope and to duplicate the top of the interpreter stack; and
 - we updated the interpreter to cope with all that.

You can get the code for this article at [tag v0.6.0 of this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.6.0).


## Next steps

In the next articles we will be looking at more interesting statements like `if` statements and `while` loops.

[The exercises below](#exercises) will challenge you to try and implement a couple of features that we will implement eventually, so go ahead and take a look at those.


## Exercises

 - Try to implement augmented assignment (`+=`, `-=`, and others). Tip: create new tokens for the augmented assignment operators and a new tree node type.
 - How would you go about adding an `if` statement to the language? The body of the `if` statement starts immediately after the `:` and the newline, but where does it end?


[series-link]: /blog/tag:bpci
