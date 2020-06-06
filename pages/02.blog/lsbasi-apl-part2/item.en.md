---
title: Let's build a simple interpreter for APL - part 2
slug: lsbasi-apl-part2
published: false
taxonomy:
    category: blogpost
    tag: [programming, python, interpreters, lsbasi-apl]
---

Today is the day! Today is the day we take our [APL][apl-wiki] programs and interpret them, so that something like `÷ 1 2 3 -⍨ 1.1 2.2 3.3` can output `10 5 3.33333333`.

===

![A black and white doodle of a person in front of a computer.](./lsbasi-apl-part2-bg.png)

Just to get us started, here are two ASTs, two Awfully Sketched Trees.

# Recap

If you recall, in the [last blog post][previous] of the series (which was also the first!) we created a simple program that took a basic [APL][apl-wiki] expression (APL is a really cool array-oriented programming language), tokenized it and then parsed it into an AST, an Abstract Syntax Tree. You can test that program online [here][repl-part1].

With that program, we can turn an expression like `5 6 -⍨ ÷1 2`

 - into a list of tokens like `[Token(EOF, None), Token(INTEGER, 5), Token(INTEGER, 6), Token(MINUS, -), Token(COMMUTE, ⍨), Token(DIVIDE, ÷), Token(INTEGER, 1), Token(INTEGER, 2)]`
 - and into an AST like `MOp(⍨ Dyad(- A([S(5), S(6)]) Monad(÷ A([S(1), S(2)]))))`

# Today

Today we are going to:

 2. implement the [Visitor pattern][visitor-wiki] to interpret an AST and produce the final result. You can check [Spivak's 8th post][lsbasi-part8] to see how he did it;
 3. implement assignment of scalars/arrays;
 4. allow for several statements split by `⋄`.

(writing this list was easy, I just went to the previous blog post and copied the list of things we would get done for today!)

Additionaly, to make the above changes easier to implement, I am also going to redesign some of the things that were already done. Please don't be mad at me! Quoting my previous post,

 > _"By the way, this might be a great moment to let you know that I make mistakes! Lots of them!"_

So we are actually starting with

 1. refactoring the grammar and the parser

## The code

[![](https://img.shields.io/github/stars/RojerGS/RGSPL?style=social)][rgspl-repo]&nbsp;&nbsp;&nbsp;[![run it on Repl.it!](https://repl.it/badge/github/RojerGS/RGSPL)][repl-part2]

The whole code for this project is hosted in [this][rgspl-repo] GitHub repo and the code for this specific blog post is [this subfolder][rgspl2]. You can also test today's code in your browser by hitting the "run on repl.it" button above.


# 


[repl-part1]: https://rgsplpart1.rojergs.repl.run/
[repl-part2]: https://google.com
[previous]: https://mathspp.com/blog/lsbasi-apl-part1
[apl-wiki]: https://aplwiki.com/
[rgspl-repo]: https://github.com/RojerGS/RGSPL
[rgspl2]: https://github.com/RojerGS/RGSPL/blob/master/part2