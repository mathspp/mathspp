---
date: 20-07-2023
event: "EuroPython 2023"
event_link: https://ep2023.europython.eu/session/how-dunder-methods-rule-python-under-the-hood
main_reference: https://mathspp.com/blog/pydonts/dunder-methods
resources: https://github.com/mathspp/talks/tree/main/20230720-how_dunder_methods_rule_python_under_the_hood
slides_pdf: https://github.com/mathspp/talks/tree/main/20230720-how_dunder_methods_rule_python_under_the_hood/slides.pdf
taxonomy:
    category: talks
    tags:
        - "EuroPython"
        - "Czech Republic"
        - "2023"
title: "How dunder methods rule Python under the hood"
watch: https://www.youtube.com/watch?v=8z1dJgnX2qQ
youtube_embed: https://www.youtube.com/embed/8z1dJgnX2qQ?si=UvTGM28ddTSxNaFq
---

## Abstract

[Python dunder methods](https://mathspp.com/blog/pydonts/dunder-methods) – like `__init__` – are sometimes referred to as “magic methods” but they are not!

They are just regular methods!

Functions that are associated with objects and that you can call with arguments.

The only thing is... Python also calls those functions behind the scenes in certain situations!

So, let us learn what that is all about.

## Description

In this talk aimed at beginners, we will see what “dunder methods” are and how they govern Python behind the scenes.

We will understand, for example, why the funny-looking method `__init__` looks so funny and what its real purpose is.

Then, we will understand that all of the Python syntax really is governed by dunder methods, and thus, if you want your own classes and objects to interact with the Python syntax, all you need to do is implement the appropriate dunder methods.

For example, we will see how to use dunder methods to enable you to create a good string representation of your class, or how to define `len` on your own objects.

Although the talk is aimed at beginners, the audience is expected to know how to use the keyword `class` to create their own classes.

