---
date: 03-08-2026 18:23
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn why object oriented programming (OOP) exists and how to think about it."
    og:image: "https://mathspp.com/insider/archive/why-oop-exists/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/why-oop-exists/thumbnail.webp"
title: "Why OOP exists"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Why OOP exists

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## What is OOP?

OOP stands for Object Oriented Programming and it's a programming paradigm.

And we're all taught that OOP in Python means creating classes with `class BlaBla:` and then the weird method `__init__` and what have you.

But that's not what OOP is all about.

The core of OOP, as with any other programming paradigm, is about the _ideas_ of the paradigm.

You just have to understand the ideas to know how to use that paradigm.

_Then_, you check if the language you are using provides syntax or tools that make it easier to work with the ideas from OOP.

Online tutorials and blog posts conflate the two.

They teach you the Python syntax:

```py
class MyFirstClass:
    def __init__(self, arg1, arg2):
        self.arg1 = arg1
        self.arg2 = arg2

    def some_method(self):
        print(f"This class has {self.arg1 = }.")
```

But they don't teach you why OOP exists or when you'd want it.

I'm changing that today.

And even if you're an OOP expert, I invite you to read this issue.

Revisit the core OOP ideas.

And by the end of this issue, let me know if you learned something new or not.

## Managing a book catalog

I've been creating a simple library management script to manage all my books.

I want to catalog all my books and then have the catalog readily accessible.

I want to be able to search my books by title, author, or genre.

And a couple of other things.

And I'd love it if you could help me.

I want to implement the following functions:

 1. `find_by_title(catalog, search_term)`: this function goes through the catalog and returns a list with all the books whose title contains the given search term
 2. `get_genre(catalog, genre)`: returns a list with all the books that belong to the given genre
 3. `get_author(catalog, author)`: returns a list with all the books that belong to the given author

My “catalog” is just a list of books, but I'm still deciding how I'm going to represent a book in my Python code.

But while I work on that, and figure out the best way to represent a book in my code, you're going to implement the functions above.

To help with that, I'll give you some helper functions:

 - `book_title(book)`: returns the title of the given book
 - `book_genre(book)`: returns the genre of the given book
 - `book_author(book)`: returns the genre of the given book

Using these helper functions, you can write the three functions from before without having to worry about the way in which I represent a book.

For example, the function `find_by_title(catalog, search_term)` becomes

```py
def find_by_title(catalog, search_term):
    search_term = search_term.casefold()
    return [
        book
        for book in catalog
        if search_term in book_title(book).casefold()
    ]
```

The function `find_by_title` is COMPLETELY independent of how I decide to represent a book in Python.

And THAT's one of the core ideas of OOP.

## The levels of abstraction

When we talk about our programs, there are levels of abstraction.

We can talk about the exact data structures used to implement whatever piece of code.

But often we think about our code at a higher level.

We want to think about the real-world problems that our code solves.

If you're writing a system that manages a library, you want to talk about books.

You want to talk about authors.

You don't want to talk about Unicode, strings, integers, or bytes.

So far, the word “book” was used to refer to _something_ in the code.

You don't know _exactly_ what it is.

But you can already reason about it.

You can even write some of the library-management code without knowing what it is!

Your program deals with data (the books) and with operations you can perform (searching).

The exact, low-level implementation of a book is irrelevant.

And THAT's at the core of OOP.

You care about the high-level data and their operations.

Not about the low-level details of how things are implemented.

## But what is a book?

I know you're dying to know what a “book” looks like in code.

So I'll show you...

Next week.

In the meantime, write the functions `get_genre` and `book_author`.

After that, write a function `book_initialise(author, title, genre)`.

This function should accept three strings and return a “book”.

It is up to you to determine what a “book” is.

But don't you dare use the keyword `class`!

Use only the built-in types that Python gives you.

When you're done, send me your code.

I'll look at it and let you know if you've got it right or not.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
