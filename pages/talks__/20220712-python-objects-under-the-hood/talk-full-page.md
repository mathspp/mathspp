---
date: 12-07-2022
event: "EuroPython 2022"
event_link: https://ep2022.europython.eu/session/python-objects-under-the-hood
main_reference: https://mathspp.com/blog/pydonts/dunder-methods
resources: https://github.com/mathspp/talks/tree/main/20220712_python_objects_under_the_hood
slides_pdf: https://github.com/mathspp/talks/blob/main/20220712_python_objects_under_the_hood/slides.pdf
taxonomy:
    category: tutorials
    tags:
        - "EuroPython"
        - "Ireland"
        - "2022"
title: "Python objects under the hood"
---

## Abstract

Have you ever heard of Python's **magic** methods? I am sorry, but they are not that “magic”! I agree they are really cool, but dunder methods (the name they usually go by) are just regular Python methods that you implement! And it is my job to help **you** learn about them.

Dunder methods are the methods that you need to implement when you want your objects to interact with the syntax of Python. Do you want `len` to be callable on your objects? Implement `__len__`. Do you want your objects to be iterables? Implement `__iter__`. Do you want arithmetics to work on your objects? Implement `__add__` (and a bunch of others!). Just to name a few things your objects could be doing.

In this training, we will go over a series of small use cases for many of the existing dunder methods: we will learn about the way in which each dunder method is supposed to work and then we implement it. This will make you a more well-rounded Python developer because you will have a greater appreciation for how things work in Python. I will also show you the approaches I follow when I am learning about a new dunder method and trying to understand how it works, which will help you explore the remainder dunder methods by yourself.

For this training, you need Python 3.8+ and your code editor of choice.
