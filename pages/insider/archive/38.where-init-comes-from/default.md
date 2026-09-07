---
date: 07-09-2026 16:27
metadata:
    author: Rodrigo Girão Serrão
    description: " Learn about the reason __init__ has a weird name and how you could've invented it yourself."
    og:image: "https://mathspp.com/insider/archive/where-init-comes-from/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/where-init-comes-from/thumbnail.webp"
title: "Where __init__ comes from"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Where __init__ comes from

!!! This issue explores one piece of a bigger puzzle. Read [Why OOP exists](/blog/why-oop-exists) for the complete, step-by-step journey from the core ideas of OOP to Python classes, objects, methods, `self`, and `__init__`.

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Your class `Author`

[In the past few weeks](https://mathspp.com/insider/archive) you've been deriving the Python OOP syntax and behaviours.

Today, this journey comes to an end as you learn about the final piece of this puzzle.

As a quick refresher, this is what your class `Author` looks like:

```py
class Author:
    def initialise(self, first, last, birth_year):
        self.first = first
        self.last = last
        self.nationality = nationality
        self.birth_year = birth_year

    def name(self):
        return f"{self.first} {self.last}"

    def age(self):
        return 2026 - self.birth_year  # Gross simplification.
```

You learned about why you use the [keyword `class`](https://mathspp.com/insider/archive/classes-create-namespaces), [how objects are created](https://mathspp.com/insider/archive/representing-custom-objects), or why [the first argument of each method is always `self`](https://mathspp.com/insider/archive/the-magic-of-self).

Your class `Author` is currently used like so:

```py
charles = Author()
charles.initialise("Charles", "Dickens", 1812)
print(charles.name())  # Charles Dickens
print(charles.age())  # 214
```

The only loose end that we still need to tie is the fact that you have to explicitly initialise an object with a call to `initialise`.

## Explicitly initialising

Whenever you create an object `Author` you have to initialise it to set its attributes to the data that you care about.

This means that you'll always have code of the form

```py
author = Author()
author.initialise(...)
```

What goes in the parentheses of `initialise` might change, but the two lines of code always go hand in hand.

Because of that, Python makes things more convenient for you.

What if the method `initialise` was called automatically for you?

This way, when you create an author, you automatically get the initialisation of the attributes done for you.

This is a very convenient idea, but then you must think of something:

What if the developer creates a method `initialise` that is not supposed to be called automatically?

This is something reasonable that a developer may want to do, so it's not a good idea to call automatically a method with a “normal” name.

A developer might inadvertently create a method with that name and run into issues because Python is trying to do things it shouldn't do.

So, if you want Python to automatically call a method that initialises authors, the method must have a less obvious name...

## The method `__init__`

Enter the method `__init__`.

Python has this rule: if you create a method `__init__`, Python will call it automatically to initialise your object with its attributes.

_All_ you have to do is move your initialisation logic from `Author.initialise` to `Author.__init__`.

Like so:

```py
class Author:
    def __init__(self, first, last, birth_year):
        self.first = first
        self.last = last
        self.nationality = nationality
        self.birth_year = birth_year

    ...
```

Now, you get to simplify the code that creates and initialises author objects.

You go from

```py
charles = Author()
charles.initialise("Charles", "Dickens", 1812)
```

to

```py
charles = Author("Charles", "Dickens", 1812)
```

Before, you passed the arguments directly to `initialise`.

Now, since Python will call `__init__` automatically, you pass the arguments to `Author(...)`.

Python will make sure that those get to `initialise`.

## Dunder methods

The method `__init__` is often referred to as a [dunder method](https://mathspp.com/blog/pydonts/dunder-methods).

“Dunder” stands for “double underscore”, and it refers to the two underscores that come before and after the method name.

Python has many dunder methods: methods that start and end with two underscores and that are called automatically by Python in certain scenarios.

These special methods (another name for dunder methods) are one of the cornerstones of Python programming and knowing how to use them is essential when writing Pythonic code.

## The basics of OOP in a nutshell

You just derived the basics of OOP in a nutshell.

If you'd like to review everything you've learned over the past 6 emails, you can read this blog article: https://mathspp.com/blog/why-oop-exists .

It covers the same ideas and principles, but in a single article.

Now that we close this chapter, what would you like me to write about next week?

Reply to this email to let me know!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
