---
date: 21-10-2025 08:17
event: "PyCon Italia 2025"
event_date: 30-05-2025
event_link: https://2025.pycon.it/en/event/dipping-my-toes-in-metaprogramming
main_reference: https://mathspp.com/blog/dipping-my-toes-in-metaprogramming
resources: https://github.com/mathspp/talks/tree/main/20250530_pycon_italia_dipping-my-toes-in-metaprogramming
slides_pdf: https://github.com/mathspp/talks/blob/main/20250530_pycon_italia_dipping-my-toes-in-metaprogramming/slides.pdf
taxonomy:
    category: talks
    tags:
        - "PyCon Italia"
        - "Italy"
        - "2025"
title: "Dipping my toes in metaprogramming"
watch: https://www.youtube.com/watch?v=BFb3MhAKK5U
---

# Dipping my toes in metaprogramming – PyCon Italia 2025

## Abstract

Everyone knows the dunder method `__init__`.
But have you heard of `__init__`‘s big brother, `__new__`?

This talk shows you what the dunder method `__new__` does and how it works.
Join me and dip your toes in the world of metaprogramming in Python!

## Description

Metaprogramming can be quite an intimidating field and the truth is that most of the time you don’t need to know much about it.
If anything at all.

The dunder method `__new__` lives in the border between esoteric metaprogramming you’d learn “just because” and the Python features that advance your understanding of the language and make you a better developer.

This talk will use a progression of a series of small examples and live-coded demos to take you from the dunder method `__init__`, that you should be familiar with, to the method `__new__`.
By the end of the talk, you will understand how `__new__` fits within the Python data model and what it can be useful for.

To reach this understanding, we will:

 - explore the functionality from `pathlib.Path` that automatically creates different instances based on the OS of the user;
 - understand how `__new__` and `__init__` are used in different moments of class instantiation;
 - see how the return value of `__new__` interacts with the dunder method `__init__`; and
 - use `__new__` to subclass immutable types and, in particular, to create a class `TolerantFloat` as a subclass of `float`.
