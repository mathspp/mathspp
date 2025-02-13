In the 8th part of [this series][series-link] of building a Python compiler and interpreter we will add support for Boolean literals and Boolean operators.

===


# Building a Python compiler and interpreter – 08 Booleans

This is the 8th article of the [“Building a Python compiler and interpreter” series][series-link], so make sure you've gone through the first seven articles before tackling this one!

The code that serves as a starting point for this article is [the tag v0.7.0 of the code in this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.7.0).


## Objectives

The objectives for this article are:

 - the introduction of the Boolean literal values `True` and `False`; and
 - the unary Boolean operator `not`.


## Adding Boolean literals

### Boolean literals are keywords

The addition of the Boolean literals doesn't amount to too much work, altough we did tweak the parser in a way that may not be obvious.

So, we start off by creating the appropriate token types and then registering `True` and `False` as keywords.
In case you're wondering why we classify `True` and `False` as keywords, the answer is simple.
We do it because Python does it:

```py
>>> import keyword
>>> keyword.iskeyword("True")
True
>>> keyword.iskeyword("False")
True
```

See?
I answered your question without answering your question!

Let us add `True` and `False` as keywords:

```py
class TokenType(StrEnum):
    # ...
    TRUE = auto()  # True
    FALSE = auto()  # False

# ...

KEYWORDS_AS_TOKENS: dict[str, TokenType] = {
    "if": TokenType.IF,
    "True": TokenType.TRUE,
    "False": TokenType.FALSE,
}
```

We add three tests, and now Boolean literals can be tokenized:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        ("True", Token(TokenType.TRUE)),
        ("False", Token(TokenType.FALSE)),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    assert Tokenizer(code).next_token() == token

# ...

def test_tokenizer_boolean_values():
    code = "a = True\nb=False"
    tokens = list(Tokenizer(code))
    assert tokens == [
        Token(TokenType.NAME, "a"),
        Token(TokenType.ASSIGN),
        Token(TokenType.TRUE),
        Token(TokenType.NEWLINE),
        Token(TokenType.NAME, "b"),
        Token(TokenType.ASSIGN),
        Token(TokenType.FALSE),
        Token(TokenType.NEWLINE),
        Token(TokenType.EOF),
    ]
```


### Creating a general tree node for constants

At this point, we have two subclasses of `TreeNode` that are used for constant values:

 1. `Int`; and
 2. `Float`.

With the addition of Booleans, we'd need a third node, and possibly a fourth and a fifth for `None` and strings.
And possibly even more for dictionaries, lists, sets, and other things.

Instead of doing this, we'll simplify things a bit and we'll create a tree node called `Constant` that will replace all these use cases.

We start by creating this class `Constant` and by deleting `Int` and `Float`:

```py
# Gone:
# @dataclass
# class Int(Expr):
#     value: int

# @dataclass
# class Float(Expr):
#     value: float

@dataclass
class Constant(Expr):
    value: bool | float | int
```

Now, we need to search and replace all occurrences of `Int(` and `Float(` with `Constant(`.

For example, `Parser.parse_value` must be fixed:

```py
class Parser:
    # ...

    def parse_value(self) -> Variable | Constant:
        """Parses an integer or a float."""
        next_token_type = self.peek()
        if next_token_type == TokenType.NAME:
            return Variable(self.eat(TokenType.NAME).value)
        elif next_token_type in {TokenType.INT, TokenType.FLOAT}:
            return Constant(self.eat(next_token_type).value)
        else:
            raise RuntimeError(f"Can't parse {next_token_type} as a value.")
```

And so do all of the tests and `import` statements.

We also need to remove the methods `Compiler.compile_Int` and `Compiler.compile_Float`, which will be replaced by the method `Compiler.compile_Constant`:

```py
class Compiler:
    # ...

    def compile_Constant(self, constant: Constant) -> BytecodeGenerator:
        yield Bytecode(BytecodeType.PUSH, constant.value)
```

After you do all of the `Int(` and `Float(` replacements, make sure the tests run!


### Parsing Booleans as constants

Now that we have the tree node `Constant`, we can parse Boolean values as constants.
For that, we just need to extend the grammar rule `value` that now looks like this:

```
value := NAME | INT | FLOAT | TRUE | FALSE
```

After doing this, we extend the method `Parser.parse_value`:

```py
class Parser:
    # ...

    def parse_value(self) -> Variable | Constant:
        """Parses an integer or a float."""
        next_token_type = self.peek()
        if next_token_type == TokenType.NAME:
            return Variable(self.eat(TokenType.NAME).value)
        elif next_token_type in {TokenType.INT, TokenType.FLOAT}:
            return Constant(self.eat(next_token_type).value)
        elif next_token_type in {TokenType.TRUE, TokenType.FALSE}:
            self.eat(next_token_type)
            return Constant(next_token_type == TokenType.TRUE)
        else:
            raise RuntimeError(f"Can't parse {next_token_type} as a value.")
```

And voilá!
Booleans can now be parsed:

```bash
❯ python -m python.parser "a = True
b = False"
Program(
    statements=[
        Assignment(
            targets=[
                Variable('a'),
            ],
            value=Constant(True),
        ),
        Assignment(
            targets=[
                Variable('b'),
            ],
            value=Constant(False),
        ),
    ],
)
```

We add a small test:

```py
def test_parsing_booleans():
    code = """if True:
    a = False
    b = True"""
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(
        statements=[
            Conditional(
                condition=Constant(True),
                body=Body(
                    statements=[
                        Assignment(
                            targets=[
                                Variable("a"),
                            ],
                            value=Constant(False),
                        ),
                        Assignment(
                            targets=[
                                Variable("b"),
                            ],
                            value=Constant(True),
                        ),
                    ],
                ),
            ),
        ],
    )
```


### That's all, folks!

In order to add Boolean literal values, that's it!
No further changes are needed in the compiler or the interpreter!

Here's an example run with Booleans:

```bash
❯ python -m python.interpreter "if False:
    a = 73"
Done!
{}
None
```

Although we didn't have to change the compiler and interpreter to accomodate Boolean literal values, it won't harm if we add a couple of tests:

```py
# tests/test_compiler.py

def test_compile_booleans():
    tree = Program(
        statements=[
            Assignment(
                targets=[
                    Variable("a"),
                ],
                value=Constant(True),
            ),
            Assignment(
                targets=[
                    Variable("b"),
                ],
                value=Constant(False),
            ),
        ],
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, True),
        Bytecode(BytecodeType.SAVE, "a"),
        Bytecode(BytecodeType.PUSH, False),
        Bytecode(BytecodeType.SAVE, "b"),
    ]
```

```py
# tests/test_interpreter.py

def test_booleans():
    code = """
if True:
    a = 73

if False:
    b = 73
"""

    assert run_get_scope(code) == {"a": 73}
```


## The precedence of Boolean operators

Now that we'll be working on adding Boolean operators, we need to know what their precedences are so that we can modify the grammar correctly.
Thankfully, we don't need to try and guess because we can ask the module `ast` for help:

```pycon
>>> import ast
>>> ast.dump(ast.parse("not 1 + 2"))
'Module(body=[Expr(value=UnaryOp(op=Not(), operand=BinOp(left=Constant(value=1), op=Add(), right=Constant(value=2))))], type_ignores=[])'
```

Addition is the arithmetic operation that has the lowest precedence of all and the `not` seems to have even lower precedence!

This means that our grammar must go from this:

```
# ...
expr_statement := computation NEWLINE
assignment := ( NAME ASSIGN )+ computation NEWLINE
conditional := IF computation COLON NEWLINE body

body := INDENT statement+ DEDENT

computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
# ...
atom := LPAREN expr RPAREN | value
# ...
```

To this:

```
# ...
expr_statement := expr NEWLINE  # <--
assignment := ( NAME ASSIGN )+ expr NEWLINE  # <--
conditional := IF expr COLON NEWLINE body  # <--

body := INDENT statement+ DEDENT

expr := negation
negation := NOT negation | computation
computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
# ...
atom := LPAREN expr RPAREN | value  # <--
# ...
```

Notice that we left the rule `computation` as-is, and instead created a new rule called `expr` that represents the entrypoint to the many rules that govern how expressions are parsed.

But we're moving the carriage ahead of the horses!
We need to tokenize the operator `not` first.


## Adding the Boolean operator `not`

Much like with `True` and `False`, tokenization of the operator `not` didn't entail much work:

```py
class TokenType(StrEnum):
    # ...
    NOT = auto()  # not

# ...

KEYWORDS_AS_TOKENS: dict[str, TokenType] = {
    "if": TokenType.IF,
    "True": TokenType.TRUE,
    "False": TokenType.FALSE,
    "not": TokenType.NOT,
}
```

We make sure this works with a small test:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        ("not", Token(TokenType.NOT)),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    assert Tokenizer(code).next_token() == token
```


### Fixing the parser for the new rule `expr`

Now that we've tokenized the operator `not`, we can go back to our new grammar:

```
# ...
expr_statement := expr NEWLINE  # <--
assignment := ( NAME ASSIGN )+ expr NEWLINE  # <--
conditional := IF expr COLON NEWLINE body  # <--

body := INDENT statement+ DEDENT

expr := negation
negation := NOT negation | computation
computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
# ...
atom := LPAREN expr RPAREN | value  # <--
# ...
```

We can start by going to the rules `expr_statement`, `assignment`, and `conditional`, to make sure we replace the call to `parse_computation` with a call to `parse_expr`:

```py
class Parser:
    # ...

    def parse_atom(self) -> Expr:
        """Parses a parenthesised expression or a number."""
        if self.peek() == TokenType.LPAREN:
            self.eat(TokenType.LPAREN)
            result = self.parse_expr()  # <--
        # ...

    # ...

    def parse_expr_statement(self) -> ExprStatement:
        """Parses a standalone expression."""
        expr = ExprStatement(self.parse_expr())  # <--
        self.eat(TokenType.NEWLINE)
        return expr

    def parse_assignment(self) -> Assignment:
        """Parses an assignment."""
        # ...
        value = self.parse_expr()  # <--
        self.eat(TokenType.NEWLINE)
        return Assignment(targets, value)

    def parse_conditional(self) -> Conditional:
        """Parses a conditional statement."""
        self.eat(TokenType.IF)
        condition = self.parse_expr()  # <--
        # ...
```


### Parsing negations

After replacing all those old calls to `parse_computation`, we can define the new methods `parse_negation` and `parse_expr`:

```py
class Parser:
    # ...

    def parse_negation(self) -> Expr:
        """Parses a Boolean negation."""
        if self.peek() == TokenType.NOT:
            self.eat(TokenType.NOT)
            return UnaryOp("not", self.parse_negation())
        else:
            return self.parse_computation()

    def parse_expr(self) -> Expr:
        """Parses a full expression."""
        return self.parse_negation()
```

Having implemented the parsing code, we'll add two tests.
A simpler test for a single operator `not` and a second test for a sequence of operators:

```py
def test_single_negation():
    tokens = [
        Token(TokenType.NOT),
        Token(TokenType.TRUE),
    ]
    expr_tree = Parser(tokens).parse_expr()
    assert expr_tree == UnaryOp("not", Constant(True))


def test_multiple_negations():
    code = "not not not not not a"
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(
        [
            ExprStatement(
                UnaryOp(
                    "not",
                    UnaryOp(
                        "not",
                        UnaryOp(
                            "not",
                            UnaryOp(
                                "not",
                                UnaryOp(
                                    "not",
                                    Variable("a"),
                                ),
                            ),
                        ),
                    ),
                )
            )
        ]
    )
```


### Interpreting negations

Because a logical negation is a regular unary operator, our compiler already takes care of it.
The final thing we need to do is make sure the interpreter knows how to handle the unary operator `not`:

```py
class Interpreter:
    # ...

    def interpret_unaryop(self, bc: Bytecode) -> None:
        result = self.stack.pop()
        if bc.value == "+":
            pass
        elif bc.value == "-":
            result = -result
        elif bc.value == "not":  # <--
            result = not result
        else:
            raise RuntimeError(f"Unknown operator {bc.value}.")
        self.stack.push(result)
        self.ptr += 1
```

Now, we can test it:

```py
# Replaces `run_computation` with a broader return type.
def run_expr(code: str) -> Any:
    return _run(code).last_value_popped

# ...

@pytest.mark.parametrize(
    ["code", "result"],
    [
        ("not True", False),
        ("not not True", True),
        ("not not not True", False),
        ("not not not not True", True),
        ("not False", True),
        ("not not False", False),
        ("not not not False", True),
        ("not not not not False", False),
    ],
)
def test_not(code: str, result: bool):
    assert run_expr(code) == result
```


## Recap

In this shorter article we added support for some Boolean-related things:

 - the Boolean literal values `True` and `False`; and
 - the Boolean unary operator `not`.

I kept this article shorter because the next one...
Well, the next one will be a tough one!

You can get the code for this article at [tag v0.8.0 of this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.8.0).


## Next steps

The very next step will be to add the missing Boolean operators, `and` and `or`.
This will be a lot of fun because Python supports [Boolean short-circuiting](/blog/pydonts/boolean-short-circuiting) and so will we!

In the next articles we will be looking at adding support for the `else` and `elif` statements.
We'll also look at comparison operators and [their chaining](/blog/pydonts/chaining-comparison-operators).

[The exercises below](#exercises) will challenge you to try and implement a couple of features that we will implement eventually, so go ahead and take a look at those.


## Exercises

 - Try to implement `and` and `or`. (Can you also support [Boolean short-circuiting](/blog/pydonts/boolean-short-circuiting)?)
 - Try to implement the 6 comparison operators `==`, `!=`, `<`, `<=`, `>`, `>=`.
 - Try to implement [chained comparisons](/blog/pydonts/chaining-comparison-operators).
 - Can you add support for the `elif` and `else` statements?
 - Try to add support for the `while` loop. (You can go crazy and also try to add the keywords `break` and `continue`.)


[series-link]: /blog/tags/bpci
