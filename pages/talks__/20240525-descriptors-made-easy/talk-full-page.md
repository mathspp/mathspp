---
date: 25-05-2024
event: "PyCon Italia 2024"
event_link: https://2024.pycon.it/en/event/descriptors-made-easy
main_reference: https://mathspp.com/blog/pydonts/describing-descriptors
resources: https://github.com/mathspp/talks/tree/main/20240523_pycon-italy-descriptors-made-easy
slides_pdf: https://github.com/mathspp/talks/tree/main/20240523_pycon-italy-descriptors-made-easy/slide_deck.pdf
taxonomy:
    category: talks
    tags:
        - "PyCon Italia"
        - "Italy"
        - "2024"
title: "Descriptors made easy"
watch: https://www.youtube.com/watch?v=YK6jgXJgFw4
youtube_embed: https://www.youtube.com/embed/YK6jgXJgFw4?si=QJFkgYn43NZYMVj9
---

## Abstract

Descriptors used to confuse the heck out of time…

But then, one day, it clicked! 💡

As soon as they clicked, I started preparing this talk so that descriptors can also click for you!

And that’s what I’ll do: I’ll make sure you understand descriptors for good.

## Description

Descriptors are one of those things that many don’t even bother to learn because they look confusing.

Thankfully for you, I cracked the code and I can assure you they’re not confusing!

In this talk I will provide an example that shows how descriptors work and that will also motivate why they are useful and needed.

By the time we are done, you will be able to implement descriptors in your code and use them for practical purposes.

## Outline

The talk will follow roughly these bullet points:

- introduction
- `@property` and getter methods
- the dunder method `__get__`
- a descriptor example
- identify a similarity between `@property` and `__get__`
- `@property.setter` and setter methods
- the dunder method `__set__`
- another descriptor example
- how to create general descriptors & the dunder method `__set_name__`
- explain why `@property` and descriptors look so similar
- give examples of situations where descriptors are needed
- if time permits, talk about some built-in descriptors
