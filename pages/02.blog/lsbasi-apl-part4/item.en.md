---
title: Let's build a simple interpreter for APL - part 4 - pretty printing
---

In part 4 of this series of blog posts we take a look at pretty printing of APL arrays.

===

!!! I now live stream the development of the RGSPL interpreter [here](/twitch)
!!! and then later upload the recordings to [my YouTube channel](/youtube).

In the previous post I said I would be making less changes in between each blog post
to make the blog posts smaller, but now I went back to check the changes I had
to write about and I realised I did _way_ too much to fit in a single blog post...

So while RGSPL v0.4 is out, I'm going to split it in a couple of blog posts.
You can see all the changes from v0.3 to v0.4 [here][changes].

# Today

The RGSPL v0.4 is essentially v0.3 plus the exercises of the previous blog post,
and that represents plenty of changes and additions to our code base.
In this article we will only cover a few:

 - add testing for the primitives I already have;
 - tokenizer tweaks and fixes;
 - tweaking the CLI to accept a flag to toggle debugging;
 - implement the shape and reshape functions (monadic and dyadic `⍴`); and
 - implement the atop operator `⍤`.

## Next time

Here are the changes we still have to go through in the next blog posts.

 - add class methods to deal with the n-cells of APL arrays and others;
 - homogenise the representation of APL scalars;
 - modify the _index generator_ `⍳` function to make a distinction between 1-item vectors and scalars;
 - modify the _without_ `~` function to work on the major cells of the left argument;

 - implement pretty printing of APL arrays;

 - add auxiliary decorators that do input checking;
 - implement the Boolean functions `∧∨⍲⍱`;
 - implement the encode and decode functions `⊤⊥`;

## The code

[![](https://img.shields.io/github/stars/RojerGS/RGSPL?style=social)][rgspl-repo]

The whole code for this project is hosted in [this][rgspl-repo] GitHub repo
and the code for this specific blog post is [the release v0.4][rgspl4].

[This link][changes] shows the diff with all the changes that happened since v0.3.

---

# Testing

Unit testing is a really important thing for several reasons, for example
it provides an automated way to test your code
(and therefore gives you confidence that your code works as expected) and
it helps you figure out when code refactoring broke something.
If you also start by writing the unit testing for a certain feature and only
then implement the feature, you are forced to write code that passes your tests,
instead of writing tests that your code passes.

I don't have much experience with unit testing in Python so I went
to look for a simple alternative in the Python Standard Library and found `unittest`,
so that is what I am using.

I figured I would be writing many more tests than the ones we wrote in the
previous blog post, so I decided to create a directory for the tests and created
a `tests/utils.py` file with some utility functions:

```py
"""
Utility functions used by the tests.
"""

import functools
import unittest

from rgspl import Interpreter, Parser, Tokenizer
from arraymodel import APLArray

def run(code):
    """Run a string containing APL code."""
    return Interpreter(Parser(Tokenizer(code))).interpret()

def S(scalar):
    """Create an APL scalar."""
    return APLArray([], [scalar])

def run_apl_code_decorator(assert_method):
    """Create a new assert method interpreting positional strings as APL code."""

    @functools.wraps(assert_method)
    def new_assert_method(*args, **kwargs):
        i = 0
        args = list(args) # to allow in-place modification.
        # Run, as APL code, the first consecutive strings in the positional arguments.
        while i < len(args) and isinstance(args[i], str):
            args[i] = run(args[i])
            i += 1
        return assert_method(*args, **kwargs)
    return new_assert_method

class APLTestCase(unittest.TestCase):
    """The assert methods preprocess the arguments by running the APL code.
    
    A test case class that overrides some assert methods that start by running
    the APL code in the arguments and only then applying the assertions over them.
    """

    def __init__(self, *args, **kwargs):
        unittest.TestCase.__init__(self, *args, **kwargs)

        # Traverse all the methods of the unittest.TestCase, looking for assertX
        # methods and decorating them accordingly.
        for method_name in dir(self):
            if method_name.startswith("assert") and not method_name.endswith("_"):
                decorated = run_apl_code_decorator(getattr(self, method_name))
                setattr(self, method_name, decorated)
```

The first two functions are really simple, as they represent simple
functions to run a Python `str` as APL code and another one to
create an `APLArray` holding a simple scalar like an `int` or a `float`.

The decorator `run_apl_code_decorator` and the class `APLTestCase` are
the more interesting things in this file.
The `APLTestCase` is a class that inherits from `unittest.TestCase`
(which is the class you should inherit from when writing tests with the
`unittest` framework) and then alters some of the `unittest.TestCase` assert
methods, so that they take strings with APL code that get ran before
testing if things match or not.

If you don't know how `unittest` works then this won't make any sense
to you, so go ahead and skim through [`unittest`'s documentation][unittest].

In order to allow the `APLTestCase` to find those assert methods, we use
some introspection through `dir` and then run the appropriate functions
through the `run_apl_code_decorator` decorator.
This decorator modifies functions in the following way: it traverses the
list of positional arguments and runs the first consecutive run of
strings as if they were APL code.

With those changes, we can do something like

```py
assertEqual("2×4+5", "18")
```

in a test case, without having to explicitly wrap each argument with the `run` function:

```py
assertEqual(run("2×4+5"), run("18"))    # annoying to do in EVERY SINGLE test case
```

After those utility functions and classes are implemented it is just
a matter of writing a series of tests for the things that we already have.

RGSPL v0.4 still has no testing for operators but the functions that
are already implemented have some coverage and so do the tokenizer
and the array parsing mechanism.

You can find all the tests I wrote for v0.4 [here][tests].
In writing tests for the tokenizer I immediately started finding
bugs and errors, so let's cover those changes next.


# `Token` and `Tokenizer` tweaks

## Parsing numbers with empty integer part

The bug that I found was that some decimal numbers were not being tokenized appropriately.
For example, `.3` is a valid decimal in APL but my tokenizer didn't support it.

Thankfully, this fix was fairly simple.
I just had to tweak the `Tokenizer.get_integer` and `Tokenizer.get_next_token` functions:

```py
class Tokenizer:
    # ...
    def get_integer(self):
        """Parses an integer from the source code."""
        start_idx = self.pos
        while self.current_char and self.current_char.isdigit():
            self.advance()
        return self.code[start_idx:self.pos] or "0"     # ← in case it's an empty integer part

    # ...
    def get_next_token(self):
        """Finds the next token in the source code."""

        self.skip_whitespace()
        self.skip_comment()
        if not self.current_char:
            return Token(Token.EOF, None)

        if self.current_char in "¯.0123456789":         # ← numbers can start with .
            return self.get_number_token()

        if self.current_char in Token.ID_CHARS:
            return self.get_id_token()
        if self.current_char in Token.WYSIWYG_MAPPING:
            return self.get_wysiwyg_token()

        self.error("Could not parse the next token...")
```

## Comparing tokens and grouping them

Because of the unit tests we need a way to check if two `Token` objects
are the same, so I had to implement the `__eq__` dunder method
for a `Token` object:

```py
class Token:
    # ...
    def __eq__(self, other):
        return (
            isinstance(other, Token)
            and (self.type, self.value) == (other.type, other.value)
        )
```

I also decided to create another constant list of `Token` types,
`ARRAY_TOKENS`, because I noticed I was already using that a lot,
I just didn't have a constant list to refer to:

```py
ARRAY_TOKENS = [INTEGER, FLOAT, COMPLEX, ID]

# ...
class Parser:
    # ...
    def parse_statement(self):
        """Parses a statement."""

        self.debug(f"Parsing statement from {self.tokens[:self.pos+1]}")

        relevant_types = [Token.ASSIGNMENT, Token.RPARENS] + Token.FUNCTIONS + Token.MONADIC_OPS
        statement = self.parse_vector()
        while self.token_at.type in relevant_types:
            if self.token_at.type == Token.ASSIGNMENT:
                self.eat(Token.ASSIGNMENT)
                statement = Assignment(Var(self.token_at), statement)
                self.eat(Token.ID)
            else:
                function = self.parse_function()
                if self.token_at.type in [Token.RPARENS] + Token.ARRAY_TOKENS:      # ← use the constant list here
                    # ...

    # ...
    def parse_vector(self):
        """Parses a vector composed of possibly several simple scalars."""
        self.debug(f"Parsing vector from {self.tokens[:self.pos+1]}")

        nodes = []
        while self.token_at.type in Token.ARRAY_TOKENS + [Token.RPARENS]:
            if self.token_at.type == Token.RPARENS:
                if self.peek_beyond_parens() in Token.ARRAY_TOKENS:                 # ← same here
                    # ...
```


# Toggling debugging information

I quickly grew tired of having the interpreter always print to my face
the debugging information that I get when I have the keyword
argument `debug=True` on the `Parser`, so I factored it out
as an optional argument to my CLI:

```py
if __name__ == "__main__":

    arg_parser = argparse.ArgumentParser(description="Parse and interpret an APL program.")
    arg_parser.add_argument("-d", "--debug", action="store_true")
    # ...

    if args.repl:
        print("Please notice that, from one input line to the next, variables aren't stored (yet).")
        while inp := input(" >> "):
            try:
                print(Interpreter(Parser(Tokenizer(inp), debug=args.debug)).interpret())        # ← use new flag
            # ...
    elif args.code:
        for expr in args.code:
            print(f"{expr} :")
            print(Interpreter(Parser(Tokenizer(expr), debug=args.debug)).interpret())           # ← ditto
```

# Shape and reshape functions

The _shape_ and _reshape_ functions are, respectively, the monadic and dyadic cases
of the primitive `⍴`.
The _shape_ function takes an array and returns a **vector** with the shape of the argument
array (which is basically what is stored in our `APLArray.shape` attribute).
The _reshape_ function takes a vector on the left and an array on the right, and changes
the right argument vector to have the shape specified by the left argument.
Because the shape of an `APLArray` is specified by its `shape` attribute,
we just need to update it and ensure the `APLArray` instance
has enough data.

Overall, this turns out to be a really simple primitive to implement:

```py
def rho(*, alpha=None, omega):
    """Define monadic shape and dyadic reshape.
    Monadic case:
        ⍴ ⍳2 3
    2 3
    Dyadic case:
        3⍴⊂1 2
    (1 2)(1 2)(1 2)
    """

    if alpha is None:
        shape = [len(omega.shape)]
        data = [S(i) for i in omega.shape]
        return APLArray(shape, data)
    else:
        rank = len(alpha.shape)
        if rank > 1:
            raise ValueError(f"Left argument of reshape cannot have rank {rank}.")

        if alpha.is_scalar():
            shape = [alpha.data[0]]
        else:
            shape = [d.data[0] for d in alpha.data]

        if not all(isinstance(i, int) for i in shape):
            raise TypeError("Left argument of reshape expects integers.")

        data_from = omega.data if len(omega.shape) > 0 else [omega]
        # Extend the data roughly if needed, then truncate if needed.
        data = data_from*(math.ceil(math.prod(shape)/len(data_from)))
        data = data[:math.prod(shape)]
        return APLArray(shape, data)
```

# Atop operator

Even simpler than the _shape_ and _reshape_ functions is the _atop_ operator `⍤`.
`⍤` is very similar to `∘` and `⍥` which are already implemented, so it really is a breeze
to add this operator:

```py
def atop(*, aalpha, oomega):
    """Define the dyadic atop ⍤ operator.
    Monadic case:
        f⍤g ⍵
    f g ⍵
    Dyadic case:
        ⍺ f⍤g ⍵
    f ⍺ g ⍵
    """

    def derived(*, alpha=None, omega):
        return aalpha(alpha=None, omega=oomega(alpha=alpha, omega=omega))
    return derived
```

# The series

<ul>
{% for post in taxonomy.findTaxonomy({"tag": ["lsbasi-apl"]}) %}
    <li><a href="{{ post.url }}">{{ post.title }}</a></li>
{% endfor %}
</ul>


[changes]: https://github.com/RojerGS/RGSPL/compare/v0.3...v0.4
[rgspl4]: https://github.com/RojerGS/RGSPL/releases/tag/v0.4
[previous]: ../lsbasi-apl-part3
[rgspl-repo]: https://github.com/RojerGS/RGSPL
[unittest]: https://docs.python.org/3/library/unittest.html
[tests]: https://github.com/RojerGS/RGSPL/tree/v0.4/tests
