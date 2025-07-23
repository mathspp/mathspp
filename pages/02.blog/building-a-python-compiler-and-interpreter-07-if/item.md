In the 7th part of [this series][series-link] of building a Python compiler and interpreter we will add support for `if` statements.

===


This is the 7th article of the [“Building a Python compiler and interpreter” series][series-link], so make sure you've gone through the first six articles before tackling this one!

The code that serves as a starting point for this article is [the tag v0.6.0 of the code in this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.6.0).


## Objectives

Broadly speaking, for this article we want to add support to `if` statements.
In practice, these are the smaller tasks we'll need to handle:

 - start tokenizing keywords and colons `:`;
 - start tokenizing indentation;
 - change the grammar to support arbitrarily nested `if` statements;
 - compile these `if` statements; and
 - change the interpreter so that `if` statements can change the bytecode that the interpreter will run next.


## Booleans

In case you're wondering how we'll use conditional statements if we don't even have Booleans in our language yet, it's simple: we'll cheat a bit.
In Python, [any object has a Truthy or Falsy value](/blog/pydonts/truthy-falsy-and-bool), which means integers do too.
So, in the beginning we'll just use integers as Booleans.

The truth of the matter is that adding the literal Booleans `True` and `False`, alongside comparison operators and Boolean operators, is pretty similar to what we've been doing so far.
So, I decided to lead with the challenge of implementing the `if` statement itself, and then we'll tackle the other adjacent things (which are also included as [exercises](#exercises)).


## Tokenization of keywords

One thing that `if` statements will introduce is keywords.
So far, our program did not have any keywords whatsoever.
Now, this will change with the addition of the keyword `if`.

I thought about creating a token type called `KEYWORD` and then having the keyword be the value of the token.
However, I noticed I have different token types for the different operators `+`, `-`, and others, so I thought it made sense to introduce a token type for the keyword `if`, specifically:

```py
class TokenType(StrEnum):
    # ...
    IF = auto()  # if
```

Next, tokenization of keywords can be simple if we leverage some of the work we've already done.
Up until now, `if` would be a perfectly valid variable name in our language.
So, what we can do is let the tokenizer handle names as usual, and right before it creates a token `NAME` we intercept it, check if the name is a keyword, and create a keyword token if necessary.

To do that, we can create a dictionary that maps keywords onto tokens:

```py
KEYWORDS_AS_TOKENS: dict[str, TokenType] = {
    "if": Token(TokenType.IF),
}
```

Here is how we'd change the implementation of `Tokenizer.next_token` using the ideas outlined above:

```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        # ...

        elif char in LEGAL_NAME_START_CHARACTERS:
            name = self.consume_name()
            keyword_token_type = KEYWORDS_AS_TOKENS.get(name, None)
            if keyword_token_type:
                return Token(keyword_token_type)
            else:
                return Token(TokenType.NAME, name)

        # ...
```

We can check that `if` is a recognised keyword:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        ("if", Token(TokenType.IF)),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    assert Tokenizer(code).next_token() == token
```

We need to make another tiny change before we move on, which is to add support for the tokenization of colons `:`:

```py
class TokenType(StrEnum):
    # ...
    COLON = auto()  # :

# ...

CHARS_AS_TOKENS = {
    # ...
    ":": TokenType.COLON,
}
```

Which we should then test:

```py
@pytest.mark.parametrize(
    ["code", "token"],
    [
        # ...
        (":", Token(TokenType.COLON)),
    ],
)
def test_tokenizer_recognises_each_token(code: str, token: Token):
    assert Tokenizer(code).next_token() == token
```


## Handling indentation

The next challenge we need to face is that of handling indentation.
In Python, things like conditionals and loops use indentation to determine what code is under that conditional or under that loop.
This means that we need to be able to tokenize indentation, as that's something meaningful in Python programs.

Just to be clear, "handling indentation" means that we need to be able to recognise when code is indented, but we also need to recognise when code is dedented.
After all:

 - indentation starts a block of code under a statement; and
 - dedentation closes a block of code under a statement.


### Track changes in indentation

A neat thing that we will do, which will make our lives so much easier, is that we won't be tokenizing all of the indentation.
Instead, we will tokenize _changes_ in indentation.

For example, consider this piece of code:

```py
if cond:
    a = 1
    b = 2
    c = 3
d = 4
```

Instead of producing an indentation token before each of the assignments `a = 1`, `b = 2`, and `c = 3`, we will produce one single indentation _change_ token before the assignment `a = 1`.
That's because the indentation in each of those three lines is the same.
Then, when we reach the line with `d = 4`, we see that we no longer have indentation, so we produce a token to signal that we dedented the code.

In order to do this – to track changes in indentation – we'll need an auxiliary variable that tells us what the current indentation level is.
We also need token types for indents and dedents, naturally:

```py
from collections import deque
# ...

class TokenType(StrEnum):
    # ...
    INDENT = auto()  # indentation
    DEDENT = auto()  # dedentation

# ...

class Tokenizer:
    def __init__(self, code: str) -> None:
        # ...
        self.current_indentation_level = 0
        self.next_tokens: deque[Token] = deque()
```

Notice how we also created a new attribute called `next_tokens`.
Why do we need it?


### Saving tokens for later

When tokenizing indentation, we may end up doing "too much work" for a single token.
Just think about the fact that we need to read all the indentation at the beginning of a line before we can compare it with the previous indentation level.
Then, if the change in indentation level is too big, we may need to produce multiple tokens...
However, the method `next_token` is supposed to return _a single token_ at a time!
Thus, we'll save any extra tokens in the deque `next_tokens` and we'll return tokens from that deque whenever they're available.


### Processing indentation

First things first, we'll create a method that consumes indentation, much like we have methods to consume integers and names:

```py
class Tokenizer:
    # ...

    def consume_indentation(self) -> str:
        """Consumes indentation and returns it unprocessed."""
        start = self.ptr
        while self.ptr < len(self.code) and self.code[self.ptr] == " ":
            self.ptr += 1
        return self.code[start : self.ptr]
```

Notice that we impose a simplifying restriction that indentation must be set with spaces and not with tabs.

We then use `consume_indentation` inside `next_token` to process the indentation:

```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        # If we're at the beginning of a line, handle indentation.
        if self.beginning_of_line:
            indentation = self.consume_indentation()
            # If this line only contained indentation, ignore it.
            if self.peek() == "\n":
                self.ptr += 1
                return self.next_token()

            if len(indentation) % 4:
                raise RuntimeError("Indentation must be a multiple of 4.")

            # ...
```

The first thing we do is make sure that we got indentation from a line that actually contains code because, if it doesn't, we don't care about the indentation at all.
Next – and to simplify our lives a bit – we impose the restriction that indentation must be done with multiples of 4 spaces.
Only after passing these restrictions can we produce the indentation tokens.

To produce the indentation tokens, we'll figure out the indentation level set by the indentation we just consumed, we'll compare it with the current indentation level in the attribute `current_indentation_level`, and we'll produce as many tokens of the type `INDENT` as necessary to increase the attribute `current_indentation_level` to the level set by the indentation consumed.
Of course, this only makes sense if we consume _more_ indentation than the current indentation level.
If we consume _less_ indentation, we'll produce tokens of the type `DEDENT`.

Because we may produce 2 or more tokens, instead of returning them, we store them in the deque `next_tokens`.
After processing the indentation and producing the tokens, we flag that we are no longer at the beginning of the line.

Outside of the `if` statement that checks whether we're processing indentation or not, we check if there are any tokens waiting inside the deque `next_tokens`.
If there are, we pop one of those tokens before trying to produce tokens of other types.

If we take these considerations into account, the method `next_token` ends up looking like this:


```py
class Tokenizer:
    # ...

    def next_token(self) -> Token:
        # If we're at the beginning of a line, handle indentation.
        if self.beginning_of_line:
            indentation = self.consume_indentation()
            # If this line only contained indentation, ignore it.
            if self.peek() == "\n":
                self.ptr += 1
                return self.next_token()

            if len(indentation) % 4:
                raise RuntimeError("Indentation must be a multiple of 4.")

            indent_level = len(indentation) // 4
            while indent_level > self.current_indentation_level:
                self.next_tokens.append(Token(TokenType.INDENT))
                self.current_indentation_level += 1
            while indent_level < self.current_indentation_level:
                self.next_tokens.append(Token(TokenType.DEDENT))
                self.current_indentation_level -= 1
            self.beginning_of_line = False

        if self.next_tokens:
            return self.next_tokens.popleft()

        while self.ptr < len(self.code) and self.code[self.ptr] == " ":
            self.ptr += 1

        if self.ptr == len(self.code):
            return Token(TokenType.EOF)

        # ...
```


### Testing indents, dedents, and ignorable lines

Finally, we'll create a test that checks if we can process indentation appropriately:

 - we'll check whether tokens of the type `INDENT` are created in the correct amount;
 - same thing for `DEDENT`; and
 - we'll make sure that empty lines (even if they contain indentation) will be ignored.

Here is the test that I wrote:

```py
def test_tokenizer_indentation_empty_lines():
    """Test that empty lines with indentation are ignored."""
    code = (
        "1\n"
        + "        1\n"  # 2 indents.
        + "        \n"
        + "        \n"
        + "            1\n"  # 1 indent.
        + "        \n"
        + "            \n"
        + "    \n"
        + "    1\n"  # 2 dedents.
        + "        \n"
        + "            \n"
        + "                    \n"
        + "1\n"  # 1 dedent.
        + "    \n"
        + "            \n"
        + "\n"
    )

    tokens = list(Tokenizer(code))
    assert tokens == [
        Token(TokenType.INT, 1),
        Token(TokenType.NEWLINE),
        Token(TokenType.INDENT),
        Token(TokenType.INDENT),
        Token(TokenType.INT, 1),
        Token(TokenType.NEWLINE),
        Token(TokenType.INDENT),
        Token(TokenType.INT, 1),
        Token(TokenType.NEWLINE),
        Token(TokenType.DEDENT),
        Token(TokenType.DEDENT),
        Token(TokenType.INT, 1),
        Token(TokenType.NEWLINE),
        Token(TokenType.DEDENT),
        Token(TokenType.INT, 1),
        Token(TokenType.NEWLINE),
        Token(TokenType.EOF),
    ]
```


## The tree structure for conditionals

An `if` statement has two essential components:

 1. the condition that determines whether to run the code inside; and
 2. the code inside it.

That's it.
And if those two things are the essential components of an `if` statement, then we'll need a tree node that represents those two things.
The condition itself is a regular computation, so we already have a way of representing it in the tree.
The body, however, is going to be composed of an arbitrary list of statements...
We could actually represent the body as the tree node `Program` (since a `Program` is a list of statements) but I want the `Program` to always be the root of the parsed tree of a full program, so we'll create a node specifically for the body of the `if` statement:

```py
@dataclass
class Conditional(Statement):
    condition: Expr
    body: Body


@dataclass
class Body(TreeNode):
    statements: list[Statement]
```

Notice that `Body` does not inherit from `Statement` because the body of a conditional statement is _not_ a statement but rather a collection of statements.


## Grammar for conditional statements

The next step in adding `if` statements to our language is parsing them.
By now, you already know that if we want to parse something, we need to make sure the grammar allows it.

Currently, the grammar looks like this:

```
program := statement* EOF

statement := expr_statement | assignment

expr_statement := computation NEWLINE
assignment := ( NAME ASSIGN )+ computation NEWLINE

computation := ...
```

We'll extend the rule `statement` to include a rule `conditional`.
In turn, the rule `conditional` will be the one referencing the token `IF`, the condition, and the body.
Here is a possible grammar extension:

```
program := statement* EOF

statement := expr_statement | assignment | conditional

expr_statement := computation NEWLINE
assignment := ( NAME ASSIGN )+ computation NEWLINE
conditional := IF computation COLON NEWLINE body

body := INDENT statement+ DEDENT

computation := ...
```

Notice how the rule `conditional` mentions yet another rule, `body`.
Of course, we could've written the rule `conditional` like so:

```
conditional := IF computation COLON NEWLINE INDENT statement+ DEDENT
```

However, since the `if` statement is not the only one to have an indented block of code within it, I'm guessing it'll be helpful to have the rule `body` as a separate rule.


## Parsers for the rules `conditional` and `body`

The change to the method `parse_statement` is pretty trivial:

```py
class Parser:
    # ...

    def parse_statement(self) -> Statement:
        """Parses a statement."""
        if self.peek(skip=1) == TokenType.ASSIGN:
            return self.parse_assignment()
        elif self.peek() == TokenType.IF:  # <-- New branch.
            return self.parse_conditional()
        else:
            return self.parse_expr_statement()
```

The implementation of the method `parse_conditional` is also pretty straightforward, we only need to read the grammar rule:

```py
class Parser:
    # ...

    def parse_conditional(self) -> Conditional:
        """Parses a conditional statement."""
        # conditional := IF computation COLON NEWLINE body
        self.eat(TokenType.IF)  # IF
        condition = self.parse_computation()  # computation
        self.eat(TokenType.COLON)  # COLON
        self.eat(TokenType.NEWLINE)  # NEWLINE
        body = self.parse_body()  # body
        return Conditional(condition, body)
```

The only thing that requires a little bit more imagination is the implementation of `parse_body`.
Notice how `body` ends with a token of the type `DEDENT`:

```
body := INDENT statement+ DEDENT
```

Before the `DEDENT`, we do not know how many statements we'll be parsing.
So, inside `parse_body`, we can use a `while` loop to parse statements while there is no token `DEDENT` in sight.

```py
class Parser:
    # ...

    def parse_body(self) -> Body:
        """Parses the body of a compound statement."""
        self.eat(TokenType.INDENT)
        body = Body([])
        while self.peek() != TokenType.DEDENT:
            body.statements.append(self.parse_statement())
        self.eat(TokenType.DEDENT)
        return body
```


## Printing the new AST nodes

The nodes `Body` and `Program` are essentially the same, so we can tack the case of the node `Body` next to the `Program` and it will work without a problem:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    node_name = tree.__class__.__name__
    match tree:
        case Program(statements) | Body(statements):  # <--
            # ...
```

To print a node `Conditional` we follow more or less the same pattern we've been following up until now:

```py
def print_ast(tree: TreeNode, depth: int = 0) -> None:
    indent = "    " * depth
    node_name = tree.__class__.__name__
    match tree:
        # ...
        case Conditional(condition, body):
            print(f"{indent}{node_name}(")
            print_ast(condition, depth + 1)
            print(",")
            print_ast(body, depth + 1)
            print(",")
            print(f"{indent})", end="")
        # ...
```

Now, we can see if parsing conditionals seems to be working well:

```bash
❯ python -m python.parser "if 3 ** 4 - 80:
    a = 3
    b = 5"
Program([
    Conditional(
        BinOp(
            '-',
            BinOp(
                '**',
                Int(3),
                Int(4),
            ),
            Int(80),
        ),
        Body([
            Assignment(
                [
                    Variable('a'),
                ],
                Int(3),
            ),
            Assignment(
                [
                    Variable('b'),
                ],
                Int(5),
            ),
        ]),
    ),
])
```

It even works with nested `if` statements!
Here's an example:

```bash
❯ python -m python.parser "if 1:
    a = 3
    b = a
    if 2:
        c = 3"
Program([
    Conditional(
        Int(1),
        Body([
            Assignment(
                [
                    Variable('a'),
                ],
                Int(3),
            ),
            Assignment(
                [
                    Variable('b'),
                ],
                Variable('a'),
            ),
            Conditional(
                Int(2),
                Body([
                    Assignment(
                        [
                            Variable('c'),
                        ],
                        Int(3),
                    ),
                ]),
            ),
        ]),
    ),
])
```

We can use these examples as two new tests:

```py
def test_conditional():
    code = "if 3 ** 4 - 80:\n    a = 3\n    b = 5"
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(...)  # Tree from the 1st example above.


def test_nested_conditionals():
    code = "if 1:\n\ta = 3\n\tb = a\n\tif 2:\n\t\tc = 3".expandtabs(tabsize=4)
    tree = Parser(list(Tokenizer(code))).parse()
    assert tree == Program(...)  # Tree from the 2nd example above.
```


## Rewriting the utility to print trees

At this point, I've changed the function `print_ast` so much that I've realised that all cases of the `match` statement are essentially the same.
This means I can rewrite the function in a shorter way, and also in such a way that I don't need to update it every time I create a new node type.

This new implementation started off as something like this:

```py
from typing import Any


def print_ast(
    obj: TreeNode | list[Any] | Any, depth: int = 0, prefix: str = ""
) -> None:
    indent = "    " * depth
    obj_name = obj.__class__.__name__
    if isinstance(obj, TreeNode):
        print(f"{indent}{prefix}{obj_name}(")
        for key, value in vars(obj).items():
            print_ast(value, depth + 1, f"{key}=")
            print(",")
        print(f"{indent})", end="")
    elif isinstance(obj, list) and obj and isinstance(obj[0], TreeNode):
        print(f"{indent}{prefix}[")
        for value in obj:
            print_ast(value, depth + 1)
            print(",")
        print(f"{indent}]", end="")
    else:
        print(f"{indent}{prefix}{obj!r}", end="")

    if not depth:
        print()
```

One key change in this new implementation is that this function no longer assumes it will receive tree nodes.
It may also receive lists of nodes or any other thing, really.
It will do its best to print whatever it receives in the best way possible (see the first and second branches) but if it can't, it will just print whatever it received.

The first case is the generic case where we're printing a tree node, which we can do by traversing all of its attributes and printing them recursively.
We use [the built-in `vars`](/blog/til/vars) to access all of the attributes and we use the attribute name as a prefix to make the output easier to read.

The second case covers lists of tree nodes, where we want to print one tree node per line.

The final case, which runs by default, is that we print whatever we received without doing anything fancy.

This is a great start, but produces output a little bit too verbose:

```py
❯ python -m python.parser "1"
Program(
    [
        ExprStatement(
            expr=Int(
                1,
            ),
        ),
    ],
)
```

Notice how the `Int` in the output above takes up three lines.
What we'll do is tweak the first case a bit so that it occupies less space when reasonable:

```py
def print_ast(
    obj: TreeNode | list[Any] | Any, depth: int = 0, prefix: str = ""
) -> None:
    indent = "    " * depth
    obj_name = obj.__class__.__name__
    if isinstance(obj, TreeNode):
        items = list(vars(obj).items())
        if not items:
            print(f"{indent}{prefix}{obj_name}()", end="")
        elif len(items) == 1 and not isinstance(items[0][1], (TreeNode, list)):
            print(f"{indent}{prefix}{obj_name}({items[0][1]!r})", end="")
        else:
            print(f"{indent}{prefix}{obj_name}(")
            for key, value in items:
                print_ast(value, depth + 1, f"{key}=")
                print(",")
            print(f"{indent})", end="")
    # ...
```

This produces better-looking outputs:

```py
❯ python -m python.parser "if 3 ** 4 - 80:
    a = 3
    b = 5"
Program(
    statements=[
        Conditional(
            condition=BinOp(
                op='-',
                left=BinOp(
                    op='**',
                    left=Int(3),
                    right=Int(4),
                ),
                right=Int(80),
            ),
            body=Body(
                statements=[
                    Assignment(
                        targets=[
                            Variable('a'),
                        ],
                        value=Int(3),
                    ),
                    Assignment(
                        targets=[
                            Variable('b'),
                        ],
                        value=Int(5),
                    ),
                ],
            ),
        ),
    ],
)
```


## Bytecode that jumps

Parsing conditionals is only half of the story, as you already know.
We must still compile conditionals into bytecode operators which will then be interpreted.
However, compiling a conditional presents a new challenge.

Up until now, we interpreted our program by going over all bytecode operators, in order, one at a time.
Now, an `if` statement means that in the middle of our program we might need to skip a couple of bytecode operators, in case the condition is false.
So, we'll introduce a bytecode operator that signals this exact behaviour: `POP_JUMP_IF_FALSE`.

Here it is, in all its glory:

```py
class BytecodeType(StrEnum):
    # ...
    POP_JUMP_IF_FALSE = auto()
```

Later, the interpreter will use this bytecode operator to move execution _past_ the body of the `if` statement if the condition of the `if` statement evaluates to `False`.
For this to be possible, the bytecode `POP_JUMP_IF_FALSE` must keep track of _where_ it wants to jump to.

Consider the following piece of code as an example:

```py
if cond:
    visited = 1

done = 1
```

This code will compile as

```py
Bytecode(BytecodeType.LOAD, 'cond')
Bytecode(BytecodeType.POP_JUMP_IF_FALSE)  # `if` statement.
Bytecode(BytecodeType.PUSH, 1)
Bytecode(BytecodeType.SAVE, 'visited')
Bytecode(BytecodeType.PUSH, 1)  # <-- Land here if the condition is `False`.
Bytecode(BytecodeType.SAVE, 'done')
```

In the bytecode above, we see that the body of the `if` statement produced 2 bytecode operators, which means that if the condition evaluates to `False`, we'd like to jump past those 2 bytecodes into the bytecode that has a comment next to it.
To make this possible, the value associated with the bytecode `POP_JUMP_IF_FALSE` will be the distance to the bytecode we want to jump to.
Since we wanted to jump to the bytecode with the comment next to it, in this example the value associated with the bytecode `POP_JUMP_IF_FALSE` would be 3.

We can see that Python does a similar thing, and that's where we stole the bytecode operator name from:

```py
>>> import dis
>>> dis.dis("""if cond:
...     a = 1""")
  0           0 RESUME                   0

  1           2 LOAD_NAME                0 (cond)
              4 POP_JUMP_IF_FALSE        3 (to 12)  # <--

  2           6 LOAD_CONST               0 (1)
              8 STORE_NAME               1 (a)
             10 RETURN_CONST             1 (None)

  1     >>   12 RETURN_CONST             1 (None)
```


## Compiling a conditional

Now that we have the correct bytecode to aid us, we can compile tree nodes of the type `Body` and `Conditional`.

The type `Body` is straightforward and matches the implementation for `Program`:

```py
class Compiler:
    # ...

    def compile_Body(self, body: Body) -> BytecodeGenerator:
        for statement in body.statements:
            yield from self._compile(statement)
```

It is the node `Conditional` that requires a moment of thought.
That's because we need to compile the full body and determine its length (in bytecode operators) before we can produce the token `POP_JUMP_IF_FALSE`:

```py
class Compiler:
    # ...

    def compile_Conditional(self, conditional: Conditional) -> BytecodeGenerator:
        yield from self._compile(conditional.condition)
        body_bytecode = list(self._compile(conditional.body))
        yield Bytecode(BytecodeType.POP_JUMP_IF_FALSE, len(body_bytecode) + 1)
        yield from body_bytecode
```

Now, if we compile the code from the previous example, we'll see that the bytecode will have the value `3` associated with it:

```bash
❯ python -m python.compiler "if cond:
    visited = 1

done = 1"
Bytecode(BytecodeType.LOAD, 'cond')
Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 3)
Bytecode(BytecodeType.PUSH, 1)
Bytecode(BytecodeType.SAVE, 'visited')
Bytecode(BytecodeType.PUSH, 1)
Bytecode(BytecodeType.SAVE, 'done')
```

We can use this example as a test for the compiler.
We'll also use the code below to produce another test:

```py
if one:
    two = 2

    if three:
        four = 4
        five = 5

    if six:
        seven = 7

    eight = 8

    if nine:
        ten = 10

eleven = 11
```

This produces a huge tree that I won't show here (you can use the parser yourself!), and it compiles into this:

```py
Bytecode(BytecodeType.LOAD, 'one')            # 0
Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 19)  # 1 >> 20 (1 + 19)
Bytecode(BytecodeType.PUSH, 2)                # 2
Bytecode(BytecodeType.SAVE, 'two')            # 3
Bytecode(BytecodeType.LOAD, 'three')          # 4
Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 5)   # 5 >> 10 (5 + 5)
Bytecode(BytecodeType.PUSH, 4)                # 6
Bytecode(BytecodeType.SAVE, 'four')           # 7
Bytecode(BytecodeType.PUSH, 5)                # 8
Bytecode(BytecodeType.SAVE, 'five')           # 9
Bytecode(BytecodeType.LOAD, 'six')            # 10 <<
Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 3)   # 11 >> 14 (11 + 3)
Bytecode(BytecodeType.PUSH, 7)                # 12
Bytecode(BytecodeType.SAVE, 'seven')          # 13
Bytecode(BytecodeType.PUSH, 8)                # 14 <<
Bytecode(BytecodeType.SAVE, 'eight')          # 15
Bytecode(BytecodeType.LOAD, 'nine')           # 16
Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 3)   # 17 >> 20 (17 + 3)
Bytecode(BytecodeType.PUSH, 10)               # 18
Bytecode(BytecodeType.SAVE, 'ten')            # 19
Bytecode(BytecodeType.PUSH, 11)               # 20 << <<
Bytecode(BytecodeType.SAVE, 'eleven')         # 21
```

We can see that the jump locations seem to be correct, so we can add this as a test, too:

```py
def test_single_conditional():
    tree = Program(...)  # Tree from the smaller example.
    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.LOAD, "cond"),
        Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 3),
        Bytecode(BytecodeType.PUSH, 1),
        Bytecode(BytecodeType.SAVE, "visited"),
        Bytecode(BytecodeType.PUSH, 1),
        Bytecode(BytecodeType.SAVE, "done"),
    ]


def test_multiple_conditionals():
    tree = Program(...)  # Huge tree I didn't paste.

    bytecode = list(Compiler(tree).compile())
    assert bytecode == [
        Bytecode(BytecodeType.LOAD, "one"),
        Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 19),
        Bytecode(BytecodeType.PUSH, 2),
        Bytecode(BytecodeType.SAVE, "two"),
        Bytecode(BytecodeType.LOAD, "three"),
        Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 5),
        Bytecode(BytecodeType.PUSH, 4),
        Bytecode(BytecodeType.SAVE, "four"),
        Bytecode(BytecodeType.PUSH, 5),
        Bytecode(BytecodeType.SAVE, "five"),
        Bytecode(BytecodeType.LOAD, "six"),
        Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 3),
        Bytecode(BytecodeType.PUSH, 7),
        Bytecode(BytecodeType.SAVE, "seven"),
        Bytecode(BytecodeType.PUSH, 8),
        Bytecode(BytecodeType.SAVE, "eight"),
        Bytecode(BytecodeType.LOAD, "nine"),
        Bytecode(BytecodeType.POP_JUMP_IF_FALSE, 3),
        Bytecode(BytecodeType.PUSH, 10),
        Bytecode(BytecodeType.SAVE, "ten"),
        Bytecode(BytecodeType.PUSH, 11),
        Bytecode(BytecodeType.SAVE, "eleven"),
    ]
```


## Non-linear interpretation

At last, we'll change the interpreter to handle the new bytecode operator!
Because the bytecode `POP_JUMP_IF_FALSE` moves the execution to a different bytecode operator, it no longer makes sense for the interpreter to use a `for` loop.
Instead, we'll change to a `while` loop and each `interpret_XXX` method will be in charge of handling the movement of the pointer.

So, let us start by changing the interpreter to use a `while` loop and make sure everything else still works:

```py
class Interpreter:
    def __init__(self, bytecode: list[Bytecode]) -> None:
        # ...
        self.ptr: int = 0

    def interpret(self) -> None:
        while self.ptr < len(self.bytecode):
            bc = self.bytecode[self.ptr]
            bc_name = bc.type.value
            interpret_method = getattr(self, f"interpret_{bc_name}", None)
            if interpret_method is None:
                raise RuntimeError(f"Can't interpret {bc_name}.")
            interpret_method(bc)

        print("Done!")
        print(self.scope)
        print(self.last_value_popped)

    def interpret_push(self, bc: Bytecode) -> None:
        # ...
        self.ptr += 1  # <-- New.

    def interpret_pop(self, _: Bytecode) -> None:
        # ...
        self.ptr += 1  # <-- New.

    def interpret_binop(self, bc: Bytecode) -> None:
        # ...
        self.ptr += 1  # <-- New.

    def interpret_unaryop(self, bc: Bytecode) -> None:
        # ...
        self.ptr += 1  # <-- New.

    def interpret_save(self, bc: Bytecode) -> None:
        # ...
        self.ptr += 1  # <-- New.

    def interpret_load(self, bc: Bytecode) -> None:
        # ...
        self.ptr += 1  # <-- New.

    def interpret_copy(self, _: Bytecode) -> None:
        # ...
        self.ptr += 1  # <-- New.
```

Now we can add the method `interpret_pop_jump_if_false`:

```py
class Interpreter:
    # ...

    def interpret_pop_jump_if_false(self, bc: Bytecode) -> None:
        value = self.stack.pop()
        if not value:
            self.ptr += bc.value
        else:
            self.ptr += 1  # Default behaviour is to move to the next bytecode.
```

Let us write a couple of tests to make sure that this feature is working well:

```py
def test_flat_conditionals():
    code = """
if 1:
    a = 1
    b = 1
if 0:
    a = 20
    b = 20

if a:
    c = 11 - 10
"""

    assert run_get_scope(code) == {"a": 1, "b": 1, "c": 1}


def test_nested_conditionals():
    code = """
if 1:
    if 1:
        a = 1

        if 0:
            c = 1

    if a:
        b = 1

    if 5 - 5:
        c = 1
"""

    assert run_get_scope(code) == {"a": 1, "b": 1}
```


## Recap

In this article we've added support for `if` statements.
This entailed doing a couple of interesting things:

 - representing indentation as changes to the indentation level instead of the overall indentation level;
 - tokenizing indents and dedents;
 - extending the tokenizer to recognise keywords (`if`);
 - introduced new tree node types to represent an `if` statement and its body of statements;
 - introduced a new bytecode operator that makes the interpreter change positions; and
 - changed the interpreter so that the bytecodes can be interpreted in a non-linear fashion.

You can get the code for this article at [tag v0.7.0 of this GitHub repository](https://github.com/mathspp/building-a-python-compiler-and-interpreter/tree/v0.7.0).


## Next steps

In the next articles we will be looking at adding support for `else` and `elif` statements, as well as the `while` loop and proper Boolean values and Boolean operators.

[The exercises below](#exercises) will challenge you to try and implement a couple of features that we will implement eventually, so go ahead and take a look at those.


## Exercises

 - Try to implement the 6 comparison operators `==`, `!=`, `<`, `<=`, `>`, `>=`.
 - Implement the Boolean literals `True` and `False`.
 - Implement the Boolean operators `not`, `and`, and `or`.
 - Can you add support for the `else` statement?
 - What about `elif` statements?
 - Can you add proper Boolean values and comparison operators?
 - Try to add support for the `while` loop. (You can go crazy and also try to add the keywords `break` and `continue`.)


[series-link]: /blog/tags/bpci
