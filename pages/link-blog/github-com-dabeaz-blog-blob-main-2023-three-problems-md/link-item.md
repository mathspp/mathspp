---
author: David Beazley
date: 29-01-2025 23:14
link: https://github.com/dabeaz/blog/blob/main/2023/three-problems.md
taxonomy:
    category: link
title: "Three problems"
# via:
---

In this article David shows how to use parser combinators and lambda functions to define a small but powerful set of parsers that are highly modular and composable.
The parsers and parser combinators in the article are mostly implemented with anonymous functions in a style that would be frowned upon by most, but I think David did that on purpose.
I'm just not insightful enough to understand why.

I remember reading this article some time ago and not understanding a single thing.
This time, I understood some things.
Hopefully, next time I read it, I will understand even more.

Either way, here's some of the parsers that Dave defines:

```py
# Copied from the linked article.
shift   = lambda inp: bool(inp) and (inp[0], inp[1:])
nothing = lambda inp: (None, inp)
filt = lambda predicate: (
         lambda parser:
           lambda inp: (m:=parser(inp)) and predicate(m[0]) and m)
literal = lambda value: filt(lambda v: v == value)
char = lambda v: literal(v)(shift)
fmap = lambda func: (
         lambda parser:
           lambda inp: (m:=parser(inp)) and (func(m[0]), m[1]))
digit = filt(str.isdigit)(shift)

def one_or_more(parser):
    def parse(inp):
        result = [ ]
        while (m:=parser(inp)):
            value, inp = m
            result.append(value)
        return bool(result) and (result, inp)
    return parse

digits = fmap(''.join)(one_or_more(digit))
value = fmap(int)(digits)

def seq(*parsers):
    def parse(inp):
        result = [ ]
        for p in parsers:
            if not (m:=p(inp)):
                return False
            value, inp = m
            result.append(value)
        return (result, inp)
    return parse

either = lambda p1, p2: (lambda inp: p1(inp) or p2(inp))
```

Now we can parse some basic mathematical expressions with additions, multiplications, exponents, and parens:

```py
# Created by myself.
plus = char("+")
times = char("*")
exp = fmap("".join)(seq(char("*"), char("*")))
lparen = char("(")
rparen = char(")")

term = lambda s: either(
    seq(lparen, addition, rparen),
    value,
)(s)

exp = either(
    seq(term, exp, term),
    term,
)

mul = either(
    seq(exp, times, exp),
    exp,
)

addition = either(
    seq(mul, plus, mul),
    mul,
)

math_parser = addition
print(math_parser("3*2+(2+5)**8"))
# ([[3, '*', 2], '+', [['(', [2, '+', 5], ')'], '**', 8]], '')
```
