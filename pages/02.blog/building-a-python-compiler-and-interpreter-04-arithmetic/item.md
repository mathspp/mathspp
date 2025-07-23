In the 4th part of [this series][series-link] of building a Python compiler and interpreter we will add support for more arithmetic operations and parenthesised expressions.

===


This is the 4th article of the [“Building a Python compiler and interpreter” series][series-link], so make sure you've gone through the first three articles before tackling this one!

The code that serves as a starting point for this article is [the tag v0.3.0 of the code in this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.3.0).


## Objectives

The objectives for this article are the following:

 - add support for the unary operators `-` and `+`;
 - add support for parenthesised expressions;
 - add support for more binary operators: `*`, `/`, `%`, and `**`; and
 - understand the relationship between the precedence of operations and the order in which the grammar rules are written.


## Unary operators `-` and `+`

We want to add support to the unary operators `-` and `+`, and sometimes adding support for new syntax starts at the tokenizer level...
But not this time, as the tokenizer already knows what the operators `-` and `+` are.

Thus, we can start at the grammar level.


### Grammar rule for unary operators

In the previous article I hinted at the fact that the nesting of the grammar rules influences the precedence of operations and unary operators have higher precedence over binary operators, so the grammar rule for unary operators must be deeper than the one for binary operators.

The current grammar looks like this:

```
program := computation
computation := number ( (PLUS | MINUS) number )*
number := INT | FLOAT
```

We'll add a rule `unary` that is referenced by `computation`, so that parsing a computation now means we look for unary operators on both sides of the operator, instead of looking for numbers:

```
program := computation
computation := unary ( (PLUS | MINUS) unary )*  # <- reference unary here
unary := PLUS unary | MINUS unary | number    # <- new rule
number := INT | FLOAT
```

Notice how the rule `unary` references itself in the first two options.
This makes it so that we can handle `-3` and `-----3` with the same ease.


### Add unary operators to the AST

Before we can change the parser to accomodate the new unary operators, we need to make sure that the AST can represent unary operators.
Much like we have a node `BinOp`, we can create a node `UnaryOp`:

```py
@dataclass
class UnaryOp(Expr):
    op: str
    value: Expr
```


### Add the new rule to the parser

Now that we changed the grammar rules, we need to modify our parser.
By looking at the rules that changed, we can know _exactly_ which parser methods we need to modify:

 - `parse_computation` – the rule `computation` was changed so we need to change this method; and
 - `parse_unary` – the rule `unary` is new, so we need to implement this method.

Here are the changes to the method `parse_computation`:

```py
class Parser:
    # ...

    def parse_computation(self) -> Expr:
        """Parses a computation."""
        result: Expr
        result = self.parse_unary()

        while (next_token_type := self.peek()) in {TokenType.PLUS, TokenType.MINUS}:
            op = "+" if next_token_type == TokenType.PLUS else "-"
            self.eat(next_token_type)
            right = self.parse_unary()
            result = BinOp(op, result, right)

        return result
```

The bulk of the work, then, happens in `parse_unary`:

```py
class Parser:
    # ...

    def parse_unary(self) -> Expr:
        """Parses an unary operator."""

        if (next_token_type := self.peek()) in {TokenType.PLUS, TokenType.MINUS}:
            op = "+" if next_token_type == TokenType.PLUS else "-"
            self.eat(next_token_type)
            value = self.parse_unary()
            return UnaryOp(op, value)
        else:  # No unary operators in sight.
            return self.parse_number()
```

After the change, we run the tests we already have with `pytest .`.

Now, we want to take the new parser for a spin, so we'll update the function `print_ast` and then we'll try to parse some code.


### Printing unary operators in the AST

Printing `UnaryOp` nodes is straightforward if we copy the implementation for the node `BinaryOp`, but with one child instead of two:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    node_name = tree.__class__.__name__
    match tree:
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

Now, we can see if the parser is working:

```py
if __name__ == "__main__":
    from .tokenizer import Tokenizer

    code = "--++3.5 - 2"
    parser = Parser(list(Tokenizer(code)))
    print_ast(parser.parse())
```

Running this will produce the following output:

```py
BinOp(
    '-',
    UnaryOp(
        '-',
        UnaryOp(
            '-',
            UnaryOp(
                '+',
                UnaryOp(
                    '+',
                    Float(3.5),
                ),
            ),
        ),
    ),
    Int(2),
)
```

This seems about right!

Now, we'll add some tests.


### Test parsing unary operators

We'll create two basic tests for the unary operators `-` and `+` and we'll also take the expression from above to turn it into a test:

```py
# test_parser.py

# ...

def test_parsing_unary_minus():
    tokens = [
        Token(TokenType.MINUS),
        Token(TokenType.INT, 3),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == UnaryOp("-", Int(3))


def test_parsing_unary_plus():
    tokens = [
        Token(TokenType.PLUS),
        Token(TokenType.FLOAT, 3.0),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == UnaryOp("+", Float(3))


def test_parsing_unary_operators():
    # --++3.5 - 2
    tokens = [
        Token(TokenType.MINUS),
        Token(TokenType.MINUS),
        Token(TokenType.PLUS),
        Token(TokenType.PLUS),
        Token(TokenType.FLOAT, 3.5),
        Token(TokenType.MINUS),
        Token(TokenType.INT, 2),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == BinOp(
        "-",
        UnaryOp(
            "-",
            UnaryOp(
                "-",
                UnaryOp(
                    "+",
                    UnaryOp(
                        "+",
                        Float(3.5),
                    ),
                ),
            ),
        ),
        Int(2),
    )
```


### Compiling unary operators

After parsing comes compiling, so we need to implement a visitor method for the new AST node `UnaryOp`.
For that to be possible, we need to create a bytecode type for unary operators:

```py
class BytecodeType(StrEnum):
    BINOP = auto()
    UNARYOP = auto()
    PUSH = auto()

    def __repr__(self) -> str:
        return f"{self.__class__.__name__}.{self.name}"
```

Now, we can implement the method `compile_UnaryOp`:

```py
class Compiler:
    # ...

    def compile_UnaryOp(self, tree: UnaryOp) -> BytecodeGenerator:
        yield from self._compile(tree.value)
        yield Bytecode(BytecodeType.UNARYOP, tree.op)
```

The compilation of a unary operator is very similar to that of a binary operator, except there is only one subtree to compile (the `value`) instead of two (`left` and `right`).

Now, we test these changes:

```py
def test_compile_unary_minus():
    tree = UnaryOp("-", Int(3))
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 3),
        Bytecode(BytecodeType.UNARYOP, "-"),
    ]


def test_compile_unary_plus():
    tree = UnaryOp("+", Int(3))
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 3),
        Bytecode(BytecodeType.UNARYOP, "+"),
    ]


def test_compile_unary_operations():
    tree = UnaryOp(
        "-",
        UnaryOp(
            "-",
            UnaryOp(
                "+",
                UnaryOp(
                    "+",
                    Float(3.5),
                ),
            ),
        ),
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 3.5),
        Bytecode(BytecodeType.UNARYOP, "+"),
        Bytecode(BytecodeType.UNARYOP, "+"),
        Bytecode(BytecodeType.UNARYOP, "-"),
        Bytecode(BytecodeType.UNARYOP, "-"),
    ]
```


### Interpreting unary operators

Since we created a new bytecode type, we need a new method called `interpret_unaryop` to interpret unary operator bytecodes.
This is straightforward:

```py
class Interpreter:
    # ...

    def interpret_unaryop(self, bc: Bytecode) -> None:
        result = self.stack.pop()
        if bc.value == "+":
            pass
        elif bc.value == "-":
            result = -result
        else:
            raise RuntimeError(f"Unknown operator {bc.value}.")
        self.stack.push(result)
```

Now, we test these changes:

```py
@pytest.mark.parametrize(
    ["code", "result"],
    [
        ("-3", -3),
        ("+3", 3),
        ("--3", 3),
        ("---3", -3),
        ("----3", 3),
        ("--++-++-+3", 3),
        ("--3 + --3", 6),
    ],
)
def test_unary_operators(code: str, result: int):
    assert run_computation(code) == result
```

We can also add an interesting dynamic test that makes sure we never forget to implement `interpret_XXX` methods to match bytecodes.
We can do this by traversing all the possible values of `BytecodeType` and checking whether or not the class `Interpreter` has a method with the correct name:

```py
from python.compiler import BytecodeType

def test_all_bytecode_types_can_be_interpreted():
    for bct in BytecodeType:
        name = bct.value
        assert hasattr(Interpreter, f"interpret_{name}")
```

A _similar_ thing can be done for AST nodes and for the compiler, but we must be careful because we don't really need/want to be able to compile _all_ subclasses of `TreeNode`.
For example, as it stands, the class `Expr` isn't supposed to be instantiated, so we won't ever have the need to compile it.
This will be left [as an exercise](#exercises), in case you're interested.

And that's it!
All of a sudden, your language now supports unary operators!
Congratulations.

On to the next challenge!


## Parenthesised expressions

Parenthesised expressions are expressions that are surrounded by parentheses `(...)`.
This is what will allow us to write something like `1 - (2 - 3)`, which should be different from `1 - 2 - 3`.

Right now, our tokenizer can't recognise parentheses, so that's where we'll start!


### Tokenizing parentheses

The first thing we need to do is make sure that our tokenizer can recognise parentheses.
We'll start by creating the appropriate token types:

```py
class TokenType(StrEnum):
    # ...
    LPAREN = auto()
    RPAREN = auto()
```

Then, because this will become a recurring theme, we'll create a mapping of single characters into their token types:

```py
CHARS_AS_TOKENS = {
    "+": TokenType.PLUS,
    "-": TokenType.MINUS,
    "(": TokenType.LPAREN,
    ")": TokenType.RPAREN,
}
```

This will simplify tokenizing a little bit:

```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        while self.ptr < len(self.code) and self.code[self.ptr] == " ":
            self.ptr += 1

        if self.ptr == len(self.code):
            return Token(TokenType.EOF)

        char = self.code[self.ptr]
        if char in CHARS_AS_TOKENS:  # <--
            self.ptr += 1
            return Token(CHARS_AS_TOKENS[char])
        elif char in digits:
            integer = self.consume_int()
            # Is the integer followed by a decimal part?
            if self.ptr < len(self.code) and self.code[self.ptr] == ".":
                decimal = self.consume_decimal()
                return Token(TokenType.FLOAT, integer + decimal)
            return Token(TokenType.INT, integer)
        elif (  # Make sure we don't read a lone full stop `.`.
            char == "."
            and self.ptr + 1 < len(self.code)
            and self.code[self.ptr + 1] in digits
        ):
            decimal = self.consume_decimal()
            return Token(TokenType.FLOAT, decimal)
        else:
            raise RuntimeError(f"Can't tokenize {char!r}.")
```

With the introduction of the mapping `CHARS_AS_TOKENS`, we were able to replace the branches that were tokenizing the plus and the minus signs, and it also tokenizes left and right parentheses.

Everything still passes the tests, so now we'll add a couple of tests for parentheses:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        ("(", Token(TokenType.LPAREN)),
        (")", Token(TokenType.RPAREN)),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    tokens = list(Tokenizer(code))
    assert tokens == [token, Token(TokenType.EOF)]


def test_tokenizer_parentheses_in_code():
    tokens = list(Tokenizer("( 1 ( 2 ) 3 ( ) 4"))
    assert tokens == [
        Token(TokenType.LPAREN),
        Token(TokenType.INT, 1),
        Token(TokenType.LPAREN),
        Token(TokenType.INT, 2),
        Token(TokenType.RPAREN),
        Token(TokenType.INT, 3),
        Token(TokenType.LPAREN),
        Token(TokenType.RPAREN),
        Token(TokenType.INT, 4),
        Token(TokenType.EOF),
    ]
```


### Parsing parenthesised expressions

The tokenizer concerned itself _solely_ with the ability to parse parentheses.
The tokenizer doesn't even care if the first thing you see is a right parenthesis that's clearly unmatched!
Making sure the parentheses are balanced will be the job of the parser.

Parentheses are used to change the order of operations.
A parenthesised expression has the highest precedence of all, so it follows that the rule for parenthesised expressions is at the bottom of our grammar.

The grammar looked like this:

```
program := computation
computation := unary ( (PLUS | MINUS) unary )*
unary := PLUS unary | MINUS unary | number
number := INT | FLOAT
```

It will now look like this:

```
program := computation
computation := unary ( (PLUS | MINUS) unary )*
unary := PLUS unary | MINUS unary | atom     # <- changed
atom := LPAREN computation RPAREN | number   # <- new
number := INT | FLOAT
```

The most interesting thing to note in the new rule `atom` is that inside the parentheses we refer back to the top of the grammar, so that we can have arbitrary expressions inside them!

We have to update the parser to reflect these changes and the neat thing is that we don't need to add new tree node types for the parentheses.
The only thing they do is change the shape of our trees, as the diagram below illustrates:

![The tree representations of the expressions "1 + 2 + 3", which implicitly matches "(1 + 2) + 3", and "1 + (2 + 3)". The addition that is parenthesised is the deeper subtree.](_parentheses_comparision.webp "Comparison between `1 + 2 + 3` and `1 + (2 + 3)`.)

If you remember that `1 + 2 + 3` is the same as `(1 + 2) + 3`, we can see that the parentheses find themselves around the operations that are deeper within the tree.

So, with all this being said, what we need to do is modify the method `parse_unary`:

```py
class Parser:
    # ...

    def parse_unary(self) -> Expr:
        """Parses an unary operator."""
        if (next_token_type := self.peek()) in {TokenType.PLUS, TokenType.MINUS}:
            op = "+" if next_token_type == TokenType.PLUS else "-"
            self.eat(next_token_type)
            value = self.parse_unary()
            return UnaryOp(op, value)
        else:  # No unary operators in sight.
            return self.parse_atom()  # <-- This was changed.
```

On top of that, we need to introduce the method `parse_atom`:

```py
class Parser:
    # ...

    def parse_atom(self) -> Expr:
        """Parses a parenthesised expression or a number."""
        if self.peek() == TokenType.LPAREN:
            self.eat(TokenType.LPAREN)
            result = self.parse_computation()
            self.eat(TokenType.RPAREN)
        else:
            result = self.parse_number()
        return result
```

This is it!
The method `print_ast` requires no changes and this allows us to see the parentheses in action:

```py
if __name__ == "__main__":
    from .tokenizer import Tokenizer

    code = "1 + (2 + 3)"
    parser = Parser(list(Tokenizer(code)))
    print_ast(parser.parse())
```

The output of the code above is the tree that follows:

```py
BinOp(
    '+',
    Int(1),
    BinOp(
        '+',
        Int(2),
        Int(3),
    ),
)
```

We can, and will, add some tests now.
We'll make sure we manage to parse some expressions with parentheses correctly placed and we'll make sure that we get errors when we find unbalanced parentheses.

First, some correct expressions:

```py
def test_parsing_parentheses():
    # 1 + ( 2 + 3 )
    tokens = [
        Token(TokenType.INT, 1),
        Token(TokenType.PLUS),
        Token(TokenType.LPAREN),
        Token(TokenType.INT, 2),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 3),
        Token(TokenType.RPAREN),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == BinOp(
        "+",
        Int(1),
        BinOp(
            "+",
            Int(2),
            Int(3),
        ),
    )


def test_parsing_parentheses_around_single_number():
    # ( ( ( 1 ) ) ) + ( 2 + ( 3 ) )
    tokens = [
        Token(TokenType.LPAREN),
        Token(TokenType.LPAREN),
        Token(TokenType.LPAREN),
        Token(TokenType.INT, 1),
        Token(TokenType.RPAREN),
        Token(TokenType.RPAREN),
        Token(TokenType.RPAREN),
        Token(TokenType.PLUS),
        Token(TokenType.LPAREN),
        Token(TokenType.INT, 2),
        Token(TokenType.PLUS),
        Token(TokenType.LPAREN),
        Token(TokenType.INT, 3),
        Token(TokenType.RPAREN),
        Token(TokenType.RPAREN),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == BinOp(
        "+",
        Int(1),
        BinOp(
            "+",
            Int(2),
            Int(3),
        ),
    )
```

Now, a couple of expressions that should raise errors:

```py
@pytest.mark.parametrize(
    "code",
    [
        "(1",
        "()",
        ") 1 + 2",
        "1 + 2)",
        "1 (+) 2",
        "1 + )2(",
    ],
)
def test_unbalanced_parentheses(code: str):
    tokens = list(Tokenizer(code))
    with pytest.raises(RuntimeError):
        Parser(tokens).parse()
```

I'm tired of writing sequences of tokens by hand, so now I'm using the tokenizer and feeding that result into the parser directly.


### Compiling and interpreting parenthesised expressions

Parenthesised expressions are just expressions that produce trees that are organised in a different way, but the node types are exactly the same!
This means that we don't have to do anything else in order to be able to compile and interpret parenthesised expressions.

Even so, it may be a good idea to add a couple of tests to `test_interpreter.py`:

```py
@pytest.mark.parametrize(
    ["code", "result"],
    [
        ("-(3 + 2)", -5),
        ("1 - (2 - 3)", 2),
        ("(((1))) + (2 + (3))", 6),
        ("(2 - 3) - (5 - 6)", 0),
    ],
)
def test_parenthesised_expressions(code: str, result: int):
    assert run_computation(code) == result
```


## More arithmetic operators

The final objective for this article is to add support for more arithmetic operators:

 - `*` multiplication;
 - `/` division;
 - `%` modulo; and
 - `**` exponentiation.

This time, we have to start at the tokenizer level because we're introducing symbols that our tokenizer doesn't know yet!


### Tokenizing

We start by creating the appropriate token types:

```py
class TokenType(StrEnum):
    # ...
    MUL = auto()
    DIV = auto()
    MOD = auto()
    EXP = auto()
```

Then, we add the one-character operators to the mapping `CHARS_AS_TOKENS`:

```py
CHARS_AS_TOKENS = {
    # ...
    "*": TokenType.MUL,
    "/": TokenType.DIV,
    "%": TokenType.MOD,
}
```

This automatically takes care of tokenizing the operators for multiplication, division, and modulo.
We are left with tokenizing the operator for exponentiation, `**`.
The thing is, `**` uses the same symbol as multiplication, so we have to be careful and we have to try and tokenize `**` before we let the tokenizer recognise and create a multiplication token, otherwise `**` would be tokenized as two consecutive multiplication operators.

We could solve this in a couple of different ways but this is what I did:

```py
class Tokenizer:
    # ...

    def peek(self, length: int = 1) -> str | None:
        """Returns the substring that will be tokenized next."""
        return self.code[self.ptr : self.ptr + length]

    def next_token(self) -> Token:
        while self.ptr < len(self.code) and self.code[self.ptr] == " ":
            self.ptr += 1

        if self.ptr == len(self.code):
            return Token(TokenType.EOF)

        char = self.code[self.ptr]
        if self.peek(length=2) == "**":
            self.ptr += 2
            return Token(TokenType.EXP)
        elif char in CHARS_AS_TOKENS:
            self.ptr += 1
            return Token(CHARS_AS_TOKENS[char])
        # ...
```

After tokenizing these new operators, we need to test the tokenizer:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        ("*", Token(TokenType.MUL)),
        ("**", Token(TokenType.EXP)),
        ("/", Token(TokenType.DIV)),
        ("%", Token(TokenType.MOD)),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    tokens = list(Tokenizer(code))
    assert tokens == [token, Token(TokenType.EOF)]

# ...

def test_tokenizer_distinguishes_mul_and_exp():
    tokens = list(Tokenizer("1 * 2 ** 3 * 4 ** 5"))
    assert tokens == [
        Token(TokenType.INT, 1),
        Token(TokenType.MUL),
        Token(TokenType.INT, 2),
        Token(TokenType.EXP),
        Token(TokenType.INT, 3),
        Token(TokenType.MUL),
        Token(TokenType.INT, 4),
        Token(TokenType.EXP),
        Token(TokenType.INT, 5),
        Token(TokenType.EOF),
    ]
```


### Parsing

The new operations that we are implementing are all binary operators, so we don't need to create new subclasses of `TreeNode`.
However, we must obviously update the grammar of our language.

We've talked before about how the precedence of operations is implicitly defined by the nesting of the grammar rules, so we have to figure out what are the priorities for the operations that we are implementing.

Multiplication and division have the same precedence and they both have higher precedence than addition and subtraction, but they have less precedence than unary operators.
To exemplify, `-3 * -5` is seen as `(-3) * (-5)` (the unary operators have precedence over multiplication) but `1 + 2 * 3 + 4` is seen as `1 + (2 * 3) + 4` (multiplication has precedence over addition).

Recall that our grammar looks like this:

```
program := computation
computation := unary ( (PLUS | MINUS) unary )*
unary := PLUS unary | MINUS unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

So, if multiplication and division are to have higher precedence than addition and subtraction but lower precedence than unary operators, we need to create a rule between the rules `computation` and `unary`, like so:


```
program := computation
computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV) unary )*
unary := PLUS unary | MINUS unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

Now, we'll add exponentiation to the grammar.
Exponentiation has a higher precedence than multiplication:

```pycon
>>> 3 * 4 ** 5 * 6
18432
>>> 3 * (4 ** 5) * 6
18432
```

Surprisingly (or at least, surprisingly to me!) it has higher precedence than unary operators on the left, too:

```pycon
>>> -2 ** 4
-16
>>> -(2 ** 4)
-16
>>> (-2) ** 4
16
```

With this in mind, one might feel tempted to write the following grammar:

```
program := computation
computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom ( EXP atom )*
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

However, there is one big difference between exponentiation and the other arithmetic operations we covered so far, and that is that exponentiation associates from the right instead of from the left.
In other words, notice how `1 - 2 - 3` is the same as `(1 - 2) - 3` and _not_ `1 - (2 - 3)`:

```pycon
>>> 1 - 2 - 3
-4
>>> (1 - 2) - 3
-4
>>> 1 - (2 - 3)
2
```

Subtraction associates from the left (so do addition, multiplication, and division).

However, `2 ** 3 ** 4` is the same as `2 ** (3 ** 4)` and _not_ `(2 ** 3) ** 4`:

```pycon
>>> 2 ** 3 ** 4
2417851639229258349412352
>>> 2 ** (3 ** 4)
2417851639229258349412352
>>> (2 ** 3) ** 4
4096
```

Exponentiation associates from the right.
So, the rule must actually reflect that.

We can fix this by making the rule almost reference itself.
If we wrote `exponentiation := number EXP exponentiation | number` we'd be quite close, but we need to make sure that we can use unary operators on the right side of the exponentiation operator, for example `2 ** -3` must be valid.

Thus, the right side of the exponentiation operator references the rule above:

```
program := computation
computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom EXP unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

Finally, we need to figure out where the operator modulo `%` goes (which associates from the left, by the way).
I tried a couple of numerical experiments and then I ended up looking it up to be absolutely certain: as it turns out, the modulo operator has the same precedence as the multiplication and division operators, so the final grammar actually looks like this:

```
program := computation
computation := term ( (PLUS | MINUS) term )*
term := unary ( (MUL | DIV | MOD) unary )*
unary := PLUS unary | MINUS unary | exponentiation
exponentiation := atom EXP unary | atom
atom := LPAREN computation RPAREN | number
number := INT | FLOAT
```

We have our grammar, now we need to implement it.
We've made changes to the rules `computation` and `unary` and we've added the rules `term` and `exponentiation`, so those are the methods that must change in the parser.

They end up looking like this:

```py
class Parser:
    # ...

    def parse_exponentiation(self) -> Expr:
        """Parses an exponentiation operator."""
        result = self.parse_atom()
        if self.peek() == TokenType.EXP:
            self.eat(TokenType.EXP)
            result = BinOp("**", result, self.parse_unary())
        return result

    def parse_unary(self) -> Expr:
        """Parses an unary operator."""
        if (next_token_type := self.peek()) in {TokenType.PLUS, TokenType.MINUS}:
            op = "+" if next_token_type == TokenType.PLUS else "-"
            self.eat(next_token_type)
            value = self.parse_unary()
            return UnaryOp(op, value)
        else:  # No unary operators in sight.
            return self.parse_exponentiation()

    def parse_term(self) -> Expr:
        """Parses an expression with multiplications, divisions, and modulo operations."""
        result: Expr
        result = self.parse_unary()

        TYPES_TO_OPS = {
            TokenType.MUL: "*",
            TokenType.DIV: "/",
            TokenType.MOD: "%",
        }
        while (next_token_type := self.peek()) in TYPES_TO_OPS:
            op = TYPES_TO_OPS[next_token_type]
            self.eat(next_token_type)
            right = self.parse_unary()
            result = BinOp(op, result, right)

        return result

    def parse_computation(self) -> Expr:
        """Parses a computation."""
        result: Expr
        result = self.parse_term()

        while (next_token_type := self.peek()) in {TokenType.PLUS, TokenType.MINUS}:
            op = "+" if next_token_type == TokenType.PLUS else "-"
            self.eat(next_token_type)
            right = self.parse_term()
            result = BinOp(op, result, right)

        return result
```

Now, we add a test to the parser (with the help of `print_ast`, after making sure the AST printed is correct):

```py
def test_parsing_more_operators():
    # "1 % -2 ** -3 / 5 * 2 + 2 ** 3"
    tokens = [
        Token(TokenType.INT, 1),
        Token(TokenType.MOD),
        Token(TokenType.MINUS),
        Token(TokenType.INT, 2),
        Token(TokenType.EXP),
        Token(TokenType.MINUS),
        Token(TokenType.INT, 3),
        Token(TokenType.DIV),
        Token(TokenType.INT, 5),
        Token(TokenType.MUL),
        Token(TokenType.INT, 2),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 2),
        Token(TokenType.EXP),
        Token(TokenType.INT, 3),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == BinOp(
        "+",
        BinOp(
            "*",
            BinOp(
                "/",
                BinOp(
                    "%",
                    Int(1),
                    UnaryOp(
                        "-",
                        BinOp(
                            "**",
                            Int(2),
                            UnaryOp(
                                "-",
                                Int(3),
                            ),
                        ),
                    ),
                ),
                Int(5),
            ),
            Int(2),
        ),
        BinOp(
            "**",
            Int(2),
            Int(3),
        ),
    )
```


### Compiling

We don't need to do anything for this step!
Although it may be a good idea to test the compilation of a few simple expressions with the new operators.

```py
def test_compile_multiplication():
    tree = BinOp(
        "*",
        Int(3),
        Float(3.14),
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 3),
        Bytecode(BytecodeType.PUSH, 3.14),
        Bytecode(BytecodeType.BINOP, "*"),
    ]


def test_compile_division():
    tree = BinOp(
        "/",
        Int(1),
        Int(2),
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 1),
        Bytecode(BytecodeType.PUSH, 2),
        Bytecode(BytecodeType.BINOP, "/"),
    ]


def test_compile_exponentiation():
    tree = BinOp(
        "**",
        Float(0.1),
        Float(3.14),
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, 0.1),
        Bytecode(BytecodeType.PUSH, 3.14),
        Bytecode(BytecodeType.BINOP, "**"),
    ]


def test_compile_modulo():
    tree = BinOp(
        "%",
        Int(-3),
        Float(-5.6),
    )
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.PUSH, -3),
        Bytecode(BytecodeType.PUSH, -5.6),
        Bytecode(BytecodeType.BINOP, "%"),
    ]
```


### Interpreting

To finalise our work here, we need to modify the method that interprets binary operators in the interpreter so that it handles all of the new operators we added.
Instead of growing the `if` and `elif` branches like crazy, I'll use the module `operator` to simplify our lives a bit:

```py
import operator


BINOPS_TO_OPERATOR = {
    "**": operator.pow,
    "%": operator.mod,
    "/": operator.truediv,
    "*": operator.mul,
    "+": operator.add,
    "-": operator.sub,
}


class Interpreter:
    # ...

    def interpret_binop(self, bc: Bytecode) -> None:
        right = self.stack.pop()
        left = self.stack.pop()
        op = BINOPS_TO_OPERATOR.get(bc.value, None)
        if op is not None:
            result = op(left, right)
        else:
            raise RuntimeError(f"Unknown operator {bc.value}.")
        self.stack.push(result)
```

That's it!
Now, a couple more tests and we're done.
First, we are going to add some tests where we provide the same expression twice, except one of the versions contains redudant parentheses.
The purpose of these tests is to make sure the precedence of operations is correct:

```py
@pytest.mark.parametrize(
    ["code", "correct_precedence"],
    [
        ("2 + 3 * 4 + 5", "2 + (3 * 4) + 5"),
        ("2 - 3 * 4 - 5", "2 - (3 * 4) - 5"),
        ("2 + 3 / 5 + 7", "2 + (3 / 5) + 7"),
        ("20 % 4 * 10", "(20 % 4) * 10"),
        ("-2 ** -3", "- (2 ** -3)"),
        ("2 ** 3 * 4", "(2 ** 3) * 4"),
        ("2 * 3 ** 4", "2 * (3 ** 4)"),
        ("5 + 4 % 9", "5 + (4 % 9)"),
    ],
)
def test_arithmetic_operator_precedence(code: str, correct_precedence: str) -> None:
    assert run_computation(code) == run_computation(correct_precedence)
```

Then, we can also add some tests to make sure the results being computed are correct:

```py
@pytest.mark.parametrize(
    ["code", "result"],
    [
        ("4 % 5 % 3", 1),
        ("2 * 3 * 4", 24),
        ("-2 ** 10", -1024),
        ("2 / 2 / 1", 1.0),
        ("2 + 3 * 4 ** 5 - 6 % 7 / 8", 3073.25),
    ],
)
def test_all_arithmetic_operators(code: str, result: int | float) -> None:
    assert run_computation(code) == result
```


## Recap

In this article we've accomplished quite a lot!

 - added support for unary operators;
 - added support for parenthesised expressions; and
 - added four more arithmetic operators.

All of these changes involved modifying the language grammar, which helped us understand how the ordering of the rules affects the precedence of operators.
We've also had to upgrade the tokenizer and make changes to all the four parts of our program.

You can get the code for this article at [tag v0.4.0 of this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.4.0).


## Next steps

The next steps, for the next few articles, will revolve around statements (making sure that our program handles more than just _one_ computation) and variables (assignment and lookup).

[The exercises below](#exercises) will challenge you to try and implement a couple of features that we will implement next, so go ahead and take a look at those.


## Exercises

 - Add a dynamic test that finds all (relevant!) subclasses of `TreeNode` and makes sure the class `Compiler` has a method to compile that subclass of `TreeNode`.
 (I won't be solving this exercise in the next articles.)
 - Change the grammar so that a program is not a single computation, but rather an arbitrary number of them separated by newlines.
 - Change the tokenizer to start recognising words as variables.
 - Change the grammar so that a program is made up of computations and variable assignments and update the parser accordingly.
 - Change the grammar so that a “number” is actually a number or a variable reference and update the parser accordingly.


[series-link]: /blog/tags/bpci
