In the second part of [this series][series-link] of building a Python compiler and interpreter we will improve the support of numbers.

===


# Building a Python compiler and interpreter – 02 numbers

This is the second article of the [“Building a Python compiler and interpreter” series][series-link], so make sure you've gone through the first article before tackling this one.

The code that serves as a starting point for this article is [the tag v0.1.0 of the code in this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.1.0).


## Objectives

The objectives for this article are the following:

 - make some small improvements that will make our lives easier (pretty printing trees and better representations of tokens and bytecodes);
 - add support for integers with more than one digit; and
 - add support for floats.


## Better printing

The first thing we'll do is make sure that our custom classes (for tokens and bytecodes, specifically) look decent when printed out.

For example, if you create a token and print it, it looks like this:

```pycon
>>> from python.tokenizer import *
>>> tok = Token(TokenType.INT, 3)
>>> tok
Token(type=<TokenType.INT: 'int'>, value=3)
```

If you do a similar thing with a bytecode, it will look slightly better, but still not that great:

```pycon
>>> from python.compiler import *
>>> bc = Bytecode(BytecodeType.BINOP, "+")
>>> bc
Bytecode(type=BytecodeType.BINOP, value='+')
```

We want to simplify these string representations so that it is easier to read output that we may print in the future when debugging a particular feature.


### Better string representations for tokens

Suppose we have the token `tok` from before:

```pycon
>>> tok
Token(type=<TokenType.INT: 'int'>, value=3)
```

Our end goal is to implement this representation:

```pycon
>>> tok
Token(TokenType.INT, 3)
```

To do this, we will modify the [dunder method `__repr__`](/blog/pydonts/str-and-repr#the-str-and-repr-dunder-methods) both in `TokenType` and `Token`.

We'll start with `TokenType`:

```py
class TokenType(StrEnum):
    INT = auto()
    PLUS = auto()
    MINUS = auto()
    EOF = auto()

    def __repr__(self) -> str:
        return f"{self.__class__.__name__}.{self.name}"
```

The first part of the f-string, the `self.__class__.__name__` uses the [dunder attribute `__name__`](/blog/pydonts/dunder-attribute-name) to get the name of the class dynamically, which means we don't need to write something like `f"TokenType(...)"`.
Thus, if we later change the name of the class `TokenType`, we _don't_ need to remember to also change the name in the method `__repr__`.

A token type now looks like this:

```pycon
>>> TokenType.INT
TokenType.INT
# It used to look like this: <TokenType.INT: 'int'>
```

Now that token types are represented in a shorter way, we can also modify `Token`:

```py
@dataclass
class Token:
    type: TokenType
    value: Any = None

    def __repr__(self) -> str:
        return f"{self.__class__.__name__}({self.type!r}, {self.value!r})"
```

We use the same `__name__` "trick" and then we include both the type and the value with the modifier `!r` so that types and values can be copied and pasted as Python code.

For example, as it stands, this is what the previous token looks like:

```pycon
>>> tok
Token(TokenType.INT, 3)
```

This is an excellent representation of the token because it can be copied and pasted and it _works_.
However, **if I remove the `!r`** from above, then the token would look like this:

```pycon
Token(int, 3)
```


### Better string representations for bytecodes

Through a similar process, we can improve the representations of bytecodes.
The method `BytecodeType.__repr__` was already implemented in the previous article (by mistake, I meant to leave it for this article) so all there is left is to implement `Bytecode.__repr__`:

```py
@dataclass
class Bytecode:
    type: BytecodeType
    value: Any = None

    def __repr__(self) -> str:
        return f"{self.__class__.__name__}({self.type!r}, {self.value!r})"
```

We can create and inspect a bytecode to see how much better it looks now:

```pycon
>>> bc
Bytecode(BytecodeType.BINOP, '+')
```

The fact that the value of this bytecode is a string shows why it is important to also include the modifier `!r` in the value, otherwise we'd get a `+` printed directly, which we wouldn't be able to copy and paste directly.


### Better tree printing

The next change we'll do regarding printing is implementing a function `print_ast` that accepts a tree (typically, parser output) and prints it across multiple lines with indentation to help us visualise the hierarchy of the tree.
This may seem a bit redundant now, but this will come in handy whenever we need to debug our parser.

(I'll be using [structural pattern matching](/blog/pydonts/structural-pattern-matching-tutorial), which requires Python 3.10 or later.)

The function `print_ast` that we'll be implementing accepts a tree and an optional parameter `depth`, which indicates how deep into the tree we are.
Then, the function will print the current node and then it will use recursion to keep printing the tree.
Here's this function:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    match tree:
        case BinOp(op, left, right):
            print(indent + op)
            print_ast(left, depth + 1)
            print_ast(right, depth + 1)
        case Int(value):
            print(indent + str(value))
        case _:
            raise RuntimeError(f"Can't print a node of type {tree.__class__.__name__}")
```

If you tokenize and parse the code `3 + 5` and then print it with `print_ast`, you get this:

```
+
    3
    5
```

As you can see from the output above, I went for a pretty simplistic format.
I may change it later, but this will do for now!


## Integers with multiple digits

What we want to do now is add support for integers with more than one digit.
In order to do so, we will add a method `consume_int` to the tokenizer that will move the pointer past an integer with arbitrarily many digits to tokenize it.


### Read an integer

The method will look like this:

```py
class Tokenizer:
    # ...

    def consume_int(self) -> int:
        """Reads an integer from the source code."""
        start = self.ptr
        while self.ptr < len(self.code) and self.code[self.ptr] in digits:
            self.ptr += 1
        return int(self.code[start : self.ptr])
```

This method already moves the pointer (`self.ptr`) _past_ the integer, so we need to adjust the method `Tokenizer.next_token` to make sure we use the method `consume_int` and to only advance the pointer when we are creating tokens for the operators `+` and `-`:

```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        while self.ptr < len(self.code) and self.code[self.ptr] == " ":
            self.ptr += 1

        if self.ptr == len(self.code):
            return Token(TokenType.EOF)

        char = self.code[self.ptr]
        # self.ptr += 1  # We remove this.
        if char == "+":
            self.ptr += 1  # We added this.
            return Token(TokenType.PLUS)
        elif char == "-":
            self.ptr += 1  # We added this.
            return Token(TokenType.MINUS)
        elif char in digits:
            integer = self.consume_int()  # If we found a digit, consume an integer.
            return Token(TokenType.INT, integer)
        else:
            raise RuntimeError(f"Can't tokenize {char!r}.")
```


### Run the tests

After this change, we can run our tests with `pytest .` to see if they find any issues with our change.
If everything is right, we didn't break our previous tests.

Now, of course we need to write a couple of tests to check that longer integers work well:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        (" 61      ", Token(TokenType.INT, 61)),
        ("    72345    ", Token(TokenType.INT, 72345)),
        ("9142351643", Token(TokenType.INT, 9142351643)),
        ("     642357413455672", Token(TokenType.INT, 642357413455672)),
    ],
)
def test_tokenizer_long_integers(code: str, token: Token):
    tokens = list(Tokenizer(code))
    assert tokens == [token, Token(TokenType.EOF)]
```


## Adding support for floats

We will now add support for floats in our code.
This isn't a terribly difficult thing, but it's also slightly more nuanced than one might think at first.

The first we need to do is make sure we can tokenize floats.
We can do this by following a strategy similar to the one we employed when we created a method `consume_int`.


### Token type for floats

But first things first, we need a `FLOAT` token type:

```py
class TokenType(StrEnum):
    INT = auto()
    FLOAT = auto()
    # ...
```


### Reading the decimal part of a number

Then, we can create a method `consume_decimal`.
We can do this in a couple of different ways, but let us determine that `consume_decimal` comes into action to read the decimal part of a number, i.e., when the pointer is pointing to a full stop `"."` after reading an integer:

```py
class Tokenizer:
    # ...

    def consume_decimal(self) -> float:
        """Reads a decimal part that starts with a . and returns it as a float."""
        start = self.ptr
        self.ptr += 1
        while self.ptr < len(self.code) and self.code[self.ptr] in digits:
            self.ptr += 1
        # Did we actually read _any_ digits or did we only manage to read the `.`?
        float_str = self.code[start : self.ptr] if self.ptr - start > 1 else ".0"
        return float(float_str)
```

The final check with the [conditional expression](/blog/pydonts/conditional-expressions) and the string `".0"` is to make sure we support floats written as `3.` or `127.`.
Now, we need to use this method inside the method `next_token`:

```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        while self.ptr < len(self.code) and self.code[self.ptr] == " ":
            self.ptr += 1

        if self.ptr == len(self.code):
            return Token(TokenType.EOF)

        char = self.code[self.ptr]
        if char == "+":
            self.ptr += 1
            return Token(TokenType.PLUS)
        elif char == "-":
            self.ptr += 1
            return Token(TokenType.MINUS)
        elif char in digits:
            integer = self.consume_int()
            # Is the integer followed by a decimal part?
            if self.ptr < len(self.code) and self.code[self.ptr] == ".":
                decimal = self.consume_decimal()
                return Token(TokenType.FLOAT, integer + decimal)
            return Token(TokenType.INT, integer)
        else:
            raise RuntimeError(f"Can't tokenize {char!r}.")
```


### Adding support for floats starting with `.`

Now, Python also supports writing floats like `.3` or `.125`, which we currently don't.
We can add support to this because we already have the method `consume_decimal` to do the work for us.
We can call it directly after trying to tokenize an integer:

```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        # ...
        elif char in digits:
            integer = self.consume_int()
            # Is the integer followed by a decimal part?
            if self.ptr < len(self.code) and self.code[self.ptr] == ".":
                decimal = self.consume_decimal()
                return Token(TokenType.FLOAT, integer + decimal)
            return Token(TokenType.INT, integer)
        elif (  # Make sure we don't read a lone period `.`.
            char == "."
            and self.ptr + 1 < len(self.code)
            and self.code[self.ptr + 1] in digits
        ):
            decimal = self.consume_decimal()
            return Token(TokenType.FLOAT, decimal)
        else:
            raise RuntimeError(f"Can't tokenize {char!r}.")
```

Not only do we check to see if the character we're pointing to is a period but we also need to make sure that it is followed by, at least, one digit.
Otherwise we'd be tokenizing the string `"."` as a float, which is not what we want.


### Testing float tokenisation

Before we add support for floats to the remainder of our program, we need to make sure the tokenizer does a good job of tokenizing floats.
We'll add a couple of tests for that:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        ("1.2", Token(TokenType.FLOAT, 1.2)),
        (".12", Token(TokenType.FLOAT, 0.12)),
        ("73.", Token(TokenType.FLOAT, 73.0)),
        ("0.005", Token(TokenType.FLOAT, 0.005)),
        ("123.456", Token(TokenType.FLOAT, 123.456)),
    ],
)
def test_tokenizer_floats(code: str, token: Token):
    tokens = list(Tokenizer(code))
    assert tokens == [token, Token(TokenType.EOF)]


def test_tokenizer_lone_period_is_error():
    # Make sure we don't get a float out of a single period `.`.
    with pytest.raises(RuntimeError):
        list(Tokenizer("  .  "))
```

Always write tests!
Before writing the tests, I had a stupid bug in my implementation!


### Parsing floats

The final modifications we need to make are to the parser.
First, we need a tree node for floats:

```py
class Float(TreeNode):
    value: float
```

Next, we need to specify that a `BinOp` node can take `Int`s or `Float`s are its children:

```py
@dataclass
class BinOp(TreeNode):
    op: str
    left: "Int | Float"   # <- We modify this here.
    right: "Int | Float"  # <- We modify this here.
```

Now, we need to look at the method `parse`, that currently looks like this:

```py
class Parser:
    # ...

    def parse(self) -> BinOp:
        """Parses the program."""
        left_op = self.eat(TokenType.INT)  # <- We need to allow for a float here...

        if self.peek() == TokenType.PLUS:
            op = "+"
            self.eat(TokenType.PLUS)
        else:
            op = "-"
            self.eat(TokenType.MINUS)

        right_op = self.eat(TokenType.INT)  # <- ... and here!

        self.eat(TokenType.EOF)

        return BinOp(op, Int(left_op.value), Int(right_op.value))
```

We can accomodate for floats in the same way that we accomodated for two different operations: we can use an `if` statement and the method `peek` to see what's coming.
We could refactor the method `parse` into this:

```py

    def parse(self) -> BinOp:
        """Parses the program."""
        left: Int | Float
        if self.peek() == TokenType.INT:
            left = Int(self.eat(TokenType.INT).value)
        else:
            left = Float(self.eat(TokenType.FLOAT).value)

        if self.peek() == TokenType.PLUS:
            op = "+"
            self.eat(TokenType.PLUS)
        else:
            op = "-"
            self.eat(TokenType.MINUS)

        right: Int | Float
        if self.peek() == TokenType.INT:
            right = Int(self.eat(TokenType.INT).value)
        else:
            right = Float(self.eat(TokenType.FLOAT).value)

        self.eat(TokenType.EOF)

        return BinOp(op, left, right)
```

The method `parse` that you can see above works.
(Which is a good thing!)
However, consider the following.

The treatment for the left and right operands of the binary operation is exactly the same, and it is no longer a trivial call to the method `eat`.
As the features of our language grow, we will start creating auxiliary methods that are responsible for parsing a specific part of the tree.

In this instance, to break up the method `parse` into slightly smaller methods, we can write a method `parse_number` that does exactly that: it parses a number.
Then, we can use that method to parse the left and right children of the `BinOp` node:

```py
class Parser:
    # ...

    def parse_number(self) -> Int | Float:
        """Parses an integer or a float."""
        if self.peek() == TokenType.INT:
            return Int(self.eat(TokenType.INT).value)
        else:
            return Float(self.eat(TokenType.FLOAT).value)
```

We can even create one more level, a method called `parse_computation`, that is dedicated to parsing additions and subtractions:

```py
class Parser:
    # ...

    def parse_computation(self) -> BinOp:
        """Parses a computation."""
        left = self.parse_number()

        if self.peek() == TokenType.PLUS:
            op = "+"
            self.eat(TokenType.PLUS)
        else:
            op = "-"
            self.eat(TokenType.MINUS)

        right = self.parse_number()

        return BinOp(op, left, right)
```

This way, the method `parse` stops being the method that does all of the work, and instead is just an entrypoint to the parser:

```py
class Parser:
    # ...

    def parse(self) -> BinOp:
        """Parses the program."""
        return self.parse_computation()
```

After making these modifications, we run the tests with `pytest .`.
Then, we write tests.


### Testing the parser

We can add some tests at the level of the parser:

```py
# test_parser.py
from python.parser import BinOp, Float, Int

def test_parsing_addition_with_floats():
    tokens = [
        Token(TokenType.FLOAT, 0.5),
        Token(TokenType.PLUS),
        Token(TokenType.INT, 5),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == BinOp(
        "+",
        Float(0.5),
        Int(5),
    )


def test_parsing_subtraction_with_floats():
    tokens = [
        Token(TokenType.FLOAT, 5.0),
        Token(TokenType.MINUS),
        Token(TokenType.FLOAT, 0.2),
        Token(TokenType.EOF),
    ]
    tree = Parser(tokens).parse()
    assert tree == BinOp(
        "-",
        Float(5.0),
        Float(0.2),
    )
```


### Adding floats to `print_ast`

We also shouldn't forget to modify `print_ast` to add support for floats there!
It is just a matter of putting `Int(value)` and `Float(value)` together in a case statement:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    match tree:
        case BinOp(op, left, right):
            print(indent + op)
            print_ast(left, depth + 1)
            print_ast(right, depth + 1)
        case Int(value) | Float(value):  # <- We add the Float here.
            print(indent + str(value))
        case _:
            raise RuntimeError(f"Can't print a node of type {tree.__class__.__name__}")
```


## Adding end-to-end tests

Other than the tokenizer and parser-specific tests, we can also add some end-to-end tests in `test_interpreter.py`.
We'll test arithmetic with floats and/or integers with more than one digit:

```py
@pytest.mark.parametrize(
    ["code", "result"],
    [
        ("103.6 + 5.4", 109),
        ("5.5 - 2", 3.5),
        ("1 + .2", 1.2),
        ("100.0625 - 9.5", 90.5625),
    ],
)
def test_arithmetic_with_floats(code: str, result: int):
    tokens = list(Tokenizer(code))
    tree = Parser(tokens).parse()
    bytecode = list(Compiler(tree).compile())
    interpreter = Interpreter(bytecode)
    interpreter.interpret()
    assert interpreter.stack.pop() == result
```


## Recap

In this article we've made some small adjustments to our code:

 - we changed the way token types and tokens are represented;
 - we changed the way bytecode operations are represented; and
 - we implemented a function `print_ast` that prints trees in a more readable way.

On top of those quality-of-life improvements, we also extended the features we support in our code:

 - we now support integers with arbitrarily many digits (we needed to change the tokenizer for this); and
 - we now support floats, also with arbitrarily many digits (we needed to change the tokenizer and the parser for this).

Adding support for longer integers and for floats may not seem like much, but it lay the essential groundwork that will make extending the parser _much_ easier from now on.
We'll see this as soon as the next article in this series.


## Next steps

Up next we will be working on some features that will let us parse more interesting programs and create more complex trees.
This will entail looking at something called a "language grammar", a concept I'll explain in the next article.

With this language grammar, we will be able to add grouping with parenthesis, unary minus and plus, and more operations like multiplication, division, integer division, modulo, exponentiation, and more.
We will do these things because these operations are conceptually simple while also letting me show you the definite way in which we will be structuring our parser from now on.

[The exercises below](#exercises) will challenge you to try and implement a couple of features that we will implement next, so go ahead and take a look at those.


## Exercises

 - Can you add support for binary, octal, and hexadecimal numbers? (I won't be showing the solution in the next article.)
 - Can you add support for multiple additions and subtractions in a row? For example, can you make your program work with the code `"1 + 2 - 3"`?
 - Can you add support for unary minus?


[series-link]: /blog/tag:bpci
