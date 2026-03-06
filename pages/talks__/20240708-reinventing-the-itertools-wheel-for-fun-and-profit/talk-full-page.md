---
date: 08-07-2024
event: "EuroPython 2024"
event_link: https://ep2024.europython.eu/session/reinventing-the-itertools-wheel-for-fun-and-profit
main_reference: https://mathspp.com/books/the-little-book-of-itertools
resources: https://github.com/mathspp/talks/tree/main/20240708_europython-reinventing-the-itertools-wheel-for-fun-and-profit
taxonomy:
    category: tutorials
    tags:
        - "EuroPython"
        - "Czech Republic"
        - "2024"
title: "Reinventing the `itertools` wheel for fun and profit"
---

## Abstract

In this hands-on tutorial we will reinvent the wheel!

We’ll reinvent the wheel and implement the module `itertools` in plain Python.

We’ll do that because it’s fun, but also because that will teach us a lot about Python:

- the difference between iterables and iterators;
- what the built-ins `iter` and `next` do;
- how Python handles iterations and `for` loops under the hood;
- what the iterator protocol is;
- how the dunder methods `__iter__` and `__next__` play a part in all this; and
- we’ll also learn about the functions inside `itertools`, a module with plenty of useful tools for your day-to-day iterations.

To prepare for the tutorial, please

- have Python 3.12+ installed (ideally);
- clone this GitHub repository https://github.com/mathspp/the-little-book-of-itertools; and
- install the requirements (it’s just `pytest`).
