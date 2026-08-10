---
date: 10-08-2026 19:09
metadata:
    author: Rodrigo Girão Serrão
    description: "The second idea at the core of OOP is putting data and methods together."
    og:image: "https://mathspp.com/insider/archive/objects-in-oop/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/objects-in-oop/thumbnail.webp"
title: "Objects in OOP"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Objects in OOP

> This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

---

## Diving into the core of OOP

[Last week](https://mathspp.com/insider/archive/why-oop-exists) you started diving into the core of OOP.

Not the Python syntax, but the **real** core ideas of the paradigm.

You learned that OOP is about creating an abstraction over related data and functions.

For example, you started working on a library management program.

And you didn’t even know how a book was represented.

You just knew that you had four functions you could use to work with books:

- `book_initialise(author, title, genre)` — this function takes three strings and creates a book for you
- `book_title(book)` — this function takes a book and returns the book title
- `book_author(book)` — this function takes a book and returns the book author
- `book_genre(book)` — this function takes a book and returns the book genre

None of these functions guarantee anything about the *low-level* details of how a book is actually implemented.

And you’re thinking to yourself “What do you mean Rodrigo, it’s a `Book` class”.

But you’re wrong.

Let me show you how I implemented `book_initialise`.

## Concrete implementation of a book

To be able to work with books, I created a function `book_initialise(author, title, genre)`.

The function accepts an author, a title, and a genre (all strings).

It then returns a “book”:

```py
def book_initialise(author, title, genre):
    la = list(map(ord, author))
    lt = list(map(ord, title))
    lg = list(map(ord, genre))
    return ((len(la), len(la) + len(lt)), la + lt + lg)
```

Here's what the book “Oliver Twist”, a novel by Charles Dickens, looks like in Python:

```py
book = book_initialise("Charles Dickens", "Oliver Twist", "novel")
print(book)
# ((15, 27), [67, 104, 97, 114, 108, 101, 115, 32, 68, 105, 99, 107, 101, 110, 115, 79, 108, 105, 118, 101, 114, 32, 84, 119, 105, 115, 116, 110, 111, 118, 101, 108])
```

Lovely, isn't it?

Now, here's the three auxiliary functions I promised:

```py
def book_author(book):
    return "".join(map(chr, book[1][:book[0][0]]))

def book_title(book):
    return "".join(map(chr, book[1][book[0][0]:book[0][1]]))

def book_genre(book):
    return "".join(map(chr, book[1][book[0][1]:]))
```

And they work:

```py
print(book_author(book))  # Charles Dickens
print(book_title(book))  # Oliver Twist
print(book_genre(book))  # novel
```

This implementation of a “book” is likely **not** what you expected.

But the function `find_by_title`, from last week, works nonetheless.

First, let’s define a collection of books:

```python
books = [
    book_initialise("Charles Dickens", "A Christmas Carol", "novella"),
    book_initialise("Charles Dickens", "Oliver Twist", "novel"),
    book_initialise("Charles Dickens", "Great Expectations", "novel"),
]
```

Next, here’s the function `find_by_title` from last week:

```python
def find_by_title(catalog, search_term):
    search_term = search_term.casefold()
    return [
        book
        for book in catalog
        if search_term in book_title(book).casefold()
    ]
```

Note how the function only uses the auxiliary functions listed at the beginning.

The function `find_by_title` doesn’t depend on the exact low-level implementation of a “book”...

Which means it *just works*:

```pycon
>>> for book in find_by_title(books, "ol"):
...     print(book_title(book))
...
A Christmas Carol
Oliver Twist
```

If I want to change the low-level implementation, I can.

As long as I keep the functions `book_xxx` functional, I can do that without breaking your code.

As a random exercise, can you change the function `book_initialise` to produce a *different* representation of a “book”?

After that, fix the functions `book_title`, `book_author`, and `book_genre`.

If you do, the function `find_by_title` should *still* work and you don’t have to touch it.

## Representing authors

Next, you’ll want to add features to your code to work with authors.

As far as authors are concerned, you’ll want to specify their first and last names, along with their nationality and year of birth.

That means you’ll have to create some auxiliary functions:

- `author_initialise(first, last, nationality, birth_year)` — to create an author out of the relevant data
- `author_name(author)` — to return the name of the author
- `author_nationality(author)` — to return the nationality of the author
- `author_birth_year(author)` — to return the birth year of the author
- `author_age(author)` — to return the age of the author

Notice that you’re prefixing all of these auxiliary functions with `author_`.

The name prefix helps you organise your code and it tells you that all of these functions are about the “author” abstraction.

It’s also to differentiate from all the other `book_xxx` functions.

Those are about the “book” abstraction.

It's common to want to have different functions associated with different abstractions.

And it's cumbersome to have to use all these prefixes in the function names...

So that's when the *syntax* comes into play.

## OOP syntax is born now

The syntax that your programming language has for you to work with OOP is born *after* the realisation that it's useful to have abstractions over different entities.

And it's useful to have **groups** of functions associated with each entity.

So far, you have “books” and “authors”.

In other programs, you might have “files” or “databases”.

In other programs you might have “screens”, “images”, or “videos”.

And for each of these entities, you need to be able to initialise them.

You need to provide whatever data you have that's related to that entity.

And then you want to have functions to manipulate those entities and to work with them.

That's when the programming languages decide to introduce syntax to *facilitate* this.

And these “entities” are the **objects** in **object oriented programming**.

Your programs revolve around these objects.

Your *programming* is *oriented* towards these *objects*.

And next week we'll discuss how the syntax is born to accommodate this type of programming.

---

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/\_intro'} ) } %}
