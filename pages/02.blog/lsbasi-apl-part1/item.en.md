---
title: Let's build a simple interpreter for APL - part 1
---

Let's build a simple [APL][apl-wiki] interpreter! APL is an array-oriented programming language I picked up recently. The ease with which I can write code related to mathematics, its strange built-ins (which look like `⍴`, `⍨`, `⍒` or `⍣`) and the fact that it is executed from right to left make it a fresh learning experience!

===

![A black and white doodle of a person in front of a computer.](./lsbasi-apl-part1-bg.png)


# Foreword

First and foremost, let me give credit to Ruslan Spivak's [Let's build a simple interpreter][lsbasi] blog post series on building a Pascal interpreter. I first read the beginning of the series a couple of years ago and ended up [creating the Roj programming language][roj-blog]; this time I am going over the series again but with the purpose of building an interpreter for APL which is fairly distinct from Pascal.

I am writing an APL interpreter and writing about it because
  - it will help me learn APL;
  - I get to flex my Python skills and improve them;
  - I get to document what I did in order to get my code working;
  - I get to help you write your own APL interpreter if you decide to do so!

For those of you who know the LSBASI series, the numbering in my LSBASI series is _not_ going to match Spivak's. This is because in this interpreter I need to worry about things Spivak did not have to and vice-versa, because APL and Pascal have so distinct characteristics in some aspects. On the other hand, the beginning is fairly similar and this post will present work that matches roughly what Spivak has by halfway of his [8th blog post][lsbasi-part8].


## The code

[![](https://img.shields.io/github/stars/RojerGS/RGSPL?style=social)](https://github.com/RojerGS/RGSPL)&nbsp;&nbsp;&nbsp;[![run it on Repl.it!](https://repl.it/badge/github/RojerGS/RGSPL)](https://RGSPLpart1.rojergs.repl.run)

The code for this project is available at [this GitHub repo][rgspl-repo] so go ahead and star it ;) The source code for this part is just the [rgspl1.py][rgspl1] file: you can download it in order to try it out or you can just try it online from your browser. All you have to do is hit the "run on repl.it" button above.


# What we are aiming for

This blog post series will follow along my journey of building an APL interpreter and that is the end goal! To have a fully functional APL interpreter written in Python! That is going to be a lot of work ;)


# Today's goal

In this blog post we will go through the basics to kickstart this project; in particular, we want to be able to parse simple APL statements with:

 1. floats and integers (positive and negative - in APL [we use `¯` to negate a number][apl-wiki-high-minus], e.g. `¯3` is $-3$) and vectors of those;
 2. monadic and dyadic versions of the functions `+-×÷`;
 3. the _commute/switch_ operator `⍨`;
 4. parenthesized expressions;


# Tokenizing

The first thing we need to do is take some APL source code and split it into tokens, getting rid of things we don't need - like whitespace - and finding what each character represents. For example, we look for numbers and decide if those are integers or floats or look at APL glyphs and attach them to their names.

This is the code for the `Token` class that defines the several types of tokens we are going to use today:

```py
class Token:
    """Represents a token parsed from the source code."""

    INTEGER = "INTEGER"
    FLOAT = "FLOAT"
    PLUS = "PLUS"
    MINUS = "MINUS"
    TIMES = "TIMES"
    DIVIDE = "DIVIDE"
    NEGATE = "NEGATE"
    COMMUTE = "COMMUTE"
    LPARENS = "LPARENS"
    RPARENS = "RPARENS"
    EOF = "EOF"

    # Helpful lists of token types.
    FUNCTIONS = [PLUS, MINUS, TIMES, DIVIDE]
    MONADIC_OPS = [COMMUTE]

    # What You See Is What You Get characters that correspond to tokens.
    WYSIWYG = "+-×÷()⍨"
    # The mapping from characteres to token types.
    mapping = {
        "+": PLUS,
        "-": MINUS,
        "×": TIMES,
        "÷": DIVIDE,
        "(": LPARENS,
        ")": RPARENS,
        "⍨": COMMUTE,
    }

    def __init__(self, type_, value):
        self.type = type_
        self.value = value

    def __str__(self):
        return f"Token({self.type}, {self.value})"

    def __repr__(self):
        return self.__str__()
```

After defining these token types and the `__str__` and `__repr__` methods (that allow us to print the token instances in a more friendly way) we need to be able to convert a string like `5 + 6` to the list of tokens `[Token(EOF, None), Token(INTEGER, 5), Token(PLUS, +), Token(INTEGER, 6)]`.

Notice how the `EOF` token (end-of-file token) is the first one in the list. This is because I decided to tokenize the APL source code from right to left, as that is the execution order of APL. Hopefully this decision doesn't come and bite me later!

By the way, this might be a great moment to let you know that I make mistakes! Lots of them! If at a given point you have an idea to do something in a different way, please _do_ try it out and then let me know in the comments below how it went.

Going back to our program, we already have the `Token` class, now we define our `Tokenizer` that takes a string and then builds the list of tokens. This is the beginning of the class:

```py
class Tokenizer:
    """Class that tokenizes source code into tokens."""

    def __init__(self, code):
        self.code = code
        self.pos = len(self.code) - 1
        self.current_char = self.code[self.pos]

    def error(self, message):
        """Raises a Tokenizer error."""
        raise Exception(f"TokenizerError: {message}")

    def advance(self):
        """Advances the cursor position and sets the current character."""

        self.pos -= 1
        self.current_char = None if self.pos < 0 else self.code[self.pos]

    # ...
```

We instantiate this class with the string with APL code, for example with `Tokenizer("5 + 6")`. The `error` function is used as a helper function, to raise an exception when something goes wrong with the Tokenizer.

Finally, the `advance` function is a little utility function that moves the "cursor" of the tokenizer to the left and redefines the helper variable holding the `current_char`. When we have gone through all of the APL code and we reach the end of the string (which really is the beginning because we are going from right to left) we set the `current_char` to `None` so we know there is nothing more to handle.

With this skeleton built, this is the rest of the class:

```py
class Tokenizer:

    # ...

    def skip_whitespace(self):
        """Skips all the whitespace in the source code."""

        while self.current_char and self.current_char in " \t":
            self.advance()

    def get_integer(self):
        """Parses an integer from the source code."""

        end_idx = self.pos
        while self.current_char and self.current_char.isdigit():
            self.advance()
        return self.code[self.pos+1:end_idx+1]

    def get_number_token(self):
        """Parses a number token from the source code."""

        parts = [self.get_integer()]
        # Check if we have a decimal number here.
        if self.current_char == ".":
            self.advance()
            parts.append(".")
            parts.append(self.get_integer())
        # Check for a negation of the number.
        if self.current_char == "¯":
            self.advance()
            parts.append("-")

        num = "".join(parts[::-1])
        if "." in num:
            return Token(Token.FLOAT, float(num))
        else:
            return Token(Token.INTEGER, int(num))

    def get_wysiwyg_token(self):
        """Retrieves a WYSIWYG token."""

        char = self.current_char
        if char in Token.mapping:
            self.advance()
            return Token(Token.mapping[char], char)

        self.error("Could not parse WYSIWYG token.")

    def get_next_token(self):
        """Finds the next token in the source code."""

        self.skip_whitespace()
        if not self.current_char:
            return Token(Token.EOF, None)

        if self.current_char in "0123456789":
            return self.get_number_token()

        if self.current_char in Token.WYSIWYG:
            return self.get_wysiwyg_token()

        self.error("Could not parse the next token...")

    def tokenize(self):
        """Returns the whole token list."""

        tokens = [self.get_next_token()]
        while tokens[-1].type != Token.EOF:
            tokens.append(self.get_next_token())
        return tokens[::-1]
```

With the code above, the expression `5 -⍨ ¯2.3` would get tokenized into `[Token(EOF, None), Token(INTEGER, 5), Token(MINUS, -), Token(COMMUTE, ⍨), Token(FLOAT, -2.3)]` if we ran `print(Tokenizer("5 -⍨ ¯2.3").tokenize())`. Don't believe me? Just copy the expression

`5 -⍨ ¯2.3`

and then paste it into the read-eval-print-loop [available here](https://RGSPLpart1.rojergs.repl.run).


# Finding structure in the `Token` list

Now that we have all the tokens, we want to represent them in a more structured way. For that purpose we will build what is called an Abstract Syntax Tree (check Spivak's [7th LSBASI post][lsbasi-part7]).

This AST structure will make it much easier for us to interpret an APL program; the price we have to pay is in building the tree first, which we do by traversing the `Token` list (from right to left once more) and then determining what are [scalars][apl-wiki-scalar], what are [arrays][apl-wiki-array], what are [operators][apl-wiki-operator] and what are [dyadic][apl-wiki-dyad]/[monadic][apl-wiki-monad] functions. This is the job our AST will do. After that, interpreting a program becomes really easy.

In order to know _how_ to build the AST I started by coming up with a [_grammar_][bnf-wiki] for the subset of the APL language I wanted to implement. A _grammar_ is just a notational tool that we use to specify what types of statements make sense in a language. In our case, we build a grammar to specify what types of statements make sense in APL.

After banging my head against the wall so much it started to crack,

![A black and white doodle of a head against a fissured wall](./lsbasi-apl-part1-cracked-wall.png)

after a lot of thought, a lot of drafting and after some help from a friendly bunch at the [APL Orchard][apl-orchard], I wrote down this grammar that is supposed to be read from right to left:

```py
PROGRAM := EOF STATEMENT
STATEMENT := ( ARRAY FUNCTION | FUNCTION )* ARRAY
ARRAY := ( "(" STATEMENT ")" | SCALAR )+
SCALAR := INTEGER | FLOAT
FUNCTION := F | FUNCTION "⍨"
F := "+" | "-" | "×" | "÷"
```

Each line represents a rule, which may depend on rules below it, until we reach rules like the `F` or `SCALAR` rules, which can be checked by just looking at the tokens we have at hands. The way this grammar works is (reading the rules from top to bottom and from right to left because that is how APL interprets its programs):

 1. A program is a _statement_ followed by the end of the file;
 2. A statement is an array, followed by 0 or more occurrences of, either a single _function_ (these will be monadic functions) or a _function_ followed by another _array_ (these will be dyadic functions);
 3. An array is 1 or more of a _scalar_ or a parenthesized _statement_;
 4. A scalar is either an integer token or a float token;
 5. A function is a commute operator and a _function_, or just a single _f_;
 6. An _f_ is just a short name for the set of all APL functions we know: `+-×÷`.

Notice how there's rules that reference each other and rules that reference rules higher up in the hierarchy; these self-references and recursions enrich our grammar but make the AST slightly harder to parse.

The way we turn these rules into code to build the AST is simple; first we define types for the different nodes our AST is going to have, which for now are [scalars][apl-wiki-scalar], [arrays][apl-wiki-array], [dyadic functions][apl-wiki-dyad], [monadic functions][apl-wiki-monad] and [operators][apl-wiki-operator]:

```py
class ASTNode:
    """Stub class to be inherited by the different types of AST nodes.

    The AST Nodes are used by the Parser instances to build an
        Abstract Syntax Tree out of the APL programs.
    These ASTs can then be traversed to interpret an APL program.
    """

class Scalar(ASTNode):
    """Node for a simple scalar like 3 or ¯4.2"""
    def __init__(self, token):
        self.token = token
        self.value = self.token.value

    def __str__(self):
        return f"S({self.value})"

    def __repr__(self):
        return self.__str__()

class Array(ASTNode):
    """Node for an array of simple scalars, like 3 ¯4 5.6"""
    def __init__(self, children):
        self.children = children

    def __str__(self):
        return f"A({self.children})"

    def __repr__(self):
        return self.__str__()

class MOp(ASTNode):
    """Node for monadic operators like ⍨"""
    def __init__(self, token, child):
        self.token = token
        self.child = child

    def __str__(self):
        return f"MOp({self.token.value} {self.child})"

    def __repr__(self):
        return self.__str__()

class Monad(ASTNode):
    """Node for monadic functions."""
    def __init__(self, token, child):
        self.token = token
        self.child = child

    def __str__(self):
        return f"Monad({self.token.value} {self.child})"

    def __repr__(self):
        return self.__str__()

class Dyad(ASTNode):
    """Node for dyadic functions."""
    def __init__(self, token, left, right):
        self.token = token
        self.left = left
        self.right = right

    def __str__(self):
        return f"Dyad({self.token.value} {self.left} {self.right})"

    def __repr__(self):
        return self.__str__()
```

After we know what types of nodes we will have, we define a `Parser` class that receives a `Tokenizer` as input and then provides the methods to parse a token list into an AST.

The beginning of the `Parser` class is as follows:

```py
class Parser:
    """Implements a parser for a subset of the APL language.

    The grammar parsed is available at the module-level docstring.
    """

    def __init__(self, tokenizer, debug=False):
        self.tokens = tokenizer.tokenize()
        self.pos = len(self.tokens) - 1
        self.token_at = self.tokens[self.pos]
        self.debug_on = debug

    def debug(self, message):
        """If the debugging option is on, print a message."""
        if self.debug_on:
            print(f"PD @ {message}")

    def error(self, message):
        """Throws a Parser-specific error message."""
        raise Exception(f"Parser: {message}")

    def eat(self, token_type):
        """Checks if the current token matches the expected token type."""

        if self.token_at.type != token_type:
            self.error(f"Expected {token_type} and got {self.token_at.type}.")
        else:
            self.pos -= 1
            self.token_at = None if self.pos < 0 else self.tokens[self.pos]

    def peek(self):
        """Returns the next token type without consuming it."""
        peek_at = self.pos - 1
        return None if peek_at < 0 else self.tokens[peek_at].type
```

Our `Parser` instances receive a keyword argument `debug` (that defaults to `False`) that you can use to print debugging messages, like when we start matching each rule in the grammar above. Like in the `Tokenizer` we also define an `error` method.

New to this class are the helper functions `eat` and `peek`. The `eat` function is used when we _should_ be looking at a given token type and we want to move on. We just "eat" the token type we should be looking at and if we got it right, we just keep moving; if we got it wrong then the class throws an error.

For example, we have the rule `SCALAR := INTEGER | FLOAT`. If we are trying to match that rule and we know we do not have an integer, then we _should_ have a float and we can just "eat" the float.

The `peek` method is a method that allows us to see what token will come next, but without consuming the current token. We need this when looking at the current token is not enough to determine what we are looking at.

For example, when looking at `+` we can only know if it is a monadic function or a dyadic function after we check if an array starts in the next token or not.

After having these helper functions in place we define the `parse_*` methods for the rules of our grammar; each method returns a node with the part of the code that was parsed into an AST by its rule:

```py
class Parser:

    # ...

    def parse_program(self):
        """Parses a full program."""

        self.debug(f"Parsing program from {self.tokens}")
        node = self.parse_statement()
        self.eat(Token.EOF)
        return node

    def parse_statement(self):
        """Parses a statement."""

        self.debug(f"Parsing statement from {self.tokens[:self.pos+1]}")
        node = self.parse_array()
        while self.token_at.type in Token.FUNCTIONS + Token.MONADIC_OPS:
            # pylint: disable=attribute-defined-outside-init
            func, base = self.parse_function()
            if isinstance(base, Dyad):
                base.right = node
                base.left = self.parse_array()
            elif isinstance(base, Monad):
                base.child = node
            else:
                self.error(f"Got {type(base)} instead of a Monad/Dyad.")
            node = func
        return node

    def parse_array(self):
        """Parses an array composed of possibly several simple scalars."""

        self.debug(f"Parsing array from {self.tokens[:self.pos+1]}")
        nodes = []
        while self.token_at.type in [Token.RPARENS, Token.INTEGER, Token.FLOAT]:
            if self.token_at.type == Token.RPARENS:
                self.eat(Token.RPARENS)
                nodes.append(self.parse_statement())
                self.eat(Token.LPARENS)
            else:
                nodes.append(self.parse_scalar())
        nodes = nodes[::-1]
        if not nodes:
            self.error("Failed to parse scalars inside an array.")
        elif len(nodes) == 1:
            node = nodes[0]
        else:
            node = Array(nodes)
        return node

    def parse_scalar(self):
        """Parses a simple scalar."""

        self.debug(f"Parsing scalar from {self.tokens[:self.pos+1]}")
        if self.token_at.type == Token.INTEGER:
            node = Scalar(self.token_at)
            self.eat(Token.INTEGER)
        else:
            node = Scalar(self.token_at)
            self.eat(Token.FLOAT)
        return node

    def parse_function(self):
        """Parses a function possibly monadically operated upon."""

        self.debug(f"Parsing function from {self.tokens[:self.pos+1]}")
        if self.token_at.type in Token.MONADIC_OPS:
            node = MOp(self.token_at, None)
            self.eat(self.token_at.type)
            node.child, base = self.parse_function()
        else:
            base = node = self.parse_f()
        return node, base

    def parse_f(self):
        """Parses a simple one-character function.

        We have to peek forward to decide if the function is monadic or dyadic.
        """

        self.debug(f"Parsing f from {self.tokens[:self.pos+1]}")
        if self.peek() in [Token.RPARENS, Token.INTEGER, Token.FLOAT]:
            node = Dyad(self.token_at, None, None)
        else:
            node = Monad(self.token_at, None)
        self.eat(node.token.type)
        return node

    def parse(self):
        """Parses the whole AST."""
        return self.parse_program()
```

With all these methods we can finally parse a very simple APL program into an AST and in the next blog post we will be able to interpret it! Give it a try right from your browser:

[![run it on Repl.it!](https://repl.it/badge/github/RojerGS/RGSPL)](https://RGSPLpart1.rojergs.repl.run)

With the link above you can see the expression `×⍨ 4.5 - (4 ¯3 5.6)` getting parsed into `MOp(⍨ Monad(× Dyad(- S(4.5) A([S(4), S(-3), S(5.6)]))))`. Note that the `A`s stand for arrays and the `S`s stand for scalars.


# For the next blog post

In the next blog post we will

 - implement the [Visitor pattern][visitor-wiki] to interpret an AST and produce the final result. You can check [Spivak's 8th post][lsbasi-part8] to see how he did it;
 - implement assignment of scalars/arrays;
 - allow for several statements split by `⋄`.

# Exercises

To practice your programming skills and to make sure you really understand what is going on, I suggest you try changing the `Tokenizer` and the `Parser` classes to also handle the functions `⌈` and `⌊`.

If you are feeling brave enough you can also try and implement the changes for the next blog post on your own!

See you next time!

##### All posts in this series:

<ul>
{% for post in taxonomy.findTaxonomy({"tag": ["lsbasi-apl"]}) %}
    <li><a href="{{ post.url }}">{{ post.title }}</a></li>
{% endfor %}
</ul>


[apl-wiki]: https://aplwiki.com/
[apl-wiki-scalar]: https://aplwiki.com/wiki/Scalar
[apl-wiki-array]: https://aplwiki.com/wiki/Array
[apl-wiki-operator]: https://aplwiki.com/wiki/Operator
[apl-wiki-dyad]: https://aplwiki.com/wiki/Dyadic_function
[apl-wiki-monad]: https://aplwiki.com/wiki/Monadic_function
[apl-wiki-high-minus]: https://aplwiki.com/wiki/High_minus
[lsbasi]: https://ruslanspivak.com/lsbasi-part1/
[lsbasi-part8]: https://ruslanspivak.com/lsbasi-part8/
[lsbasi-part7]: https://ruslanspivak.com/lsbasi-part7/
[roj-blog]: https://mathspp.com/blog/creating-programming-language-from-scratch
[apl-orchard]: https://chat.stackexchange.com/rooms/52405/the-apl-orchard
[rgspl-repo]: https://github.com/RojerGS/RGSPL
[rgspl1]: https://github.com/RojerGS/RGSPL/releases/v0.1
[bnf-wiki]: https://en.wikipedia.org/wiki/Backus%E2%80%93Naur_form
[visitor-wiki]: https://en.wikipedia.org/wiki/Visitor_pattern
