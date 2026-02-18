---
date: 12-11-2023
event: "PyCon Ireland 2023"
event_link: http://pycon.ie/pycon-2023/schedule/
main_reference: https://mathspp.com/blog/pydonts/describing-descriptors
resources: https://github.com/mathspp/talks/tree/main/20231112_pycon_ireland_describing_descriptors
slides_pdf: https://github.com/mathspp/talks/tree/main/20231112_pycon_ireland_describing_descriptors/slides.pdf
taxonomy:
    category: talks
    tags:
        - "PyCon Ireland"
        - "Ireland"
        - "2023"
title: "Describing Descriptors"
watch: https://www.youtube.com/watch?v=eXkBfRqJ2f8
youtube_embed: https://www.youtube.com/embed/eXkBfRqJ2f8?si=aLglbEGuqGV3KlH0
---

## Abstract

Descriptors are one of those things that many don't even bother to learn because they look confusing.

Thankfully for you, I figured out how descriptors work 20 minutes ago, so there is no one better prepared to teach you descriptors than someone who _just_ learned how they work.

In this talk I will provide an example that shows how descriptors work and that will also motivate why they are useful and needed.

By the time we are done, you will be able to implement descriptors in your code, use them for practical purposes and, most importantly, you will be able to deliver a talk just like mine!

The talk will be easier to follow if you are familiar with how properties work (via the `@property` decorator).

## Outline

The outline of the talk is as follows:

- using properties to implement getters for private attributes or attributes that are computed on the fly;
- using properties to implement setters for those same attributes;
- defining a bunch of similar properties;
- using descriptors to abstract away the pattern that the properties follow;
- descriptor `__get__`;
- descriptor `__set__`; and
- descriptor `__set_name__`.

The talk will start by going over the basics of properties, but it is does not provide a comprehensive introduction to Python properties.
Rather, it is meant as a refresher for someone who knows how to implement and use a property.

