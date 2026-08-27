---
date: 17-08-2026 12:08
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to group functions and data in objects with a namespace created by a class."
    og:image: "https://mathspp.com/insider/archive/classes-create-namespaces/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/classes-create-namespaces/thumbnail.webp"
title: "Classes create namespaces"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Classes create namespaces

!!! This issue explores one piece of a bigger puzzle. Read [Why OOP exists](/blog/why-oop-exists) for the complete, step-by-step journey from the core ideas of OOP to Python classes, objects, methods, and `self`.

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Data and functions grouped together

[Last week](https://mathspp.com/insider/archive/objects-in-oop) you learned that an “object” is an entity that groups data and functions together.

For example, you looked at the notion of a “book” with an author, title, and genre for data, and the functions `book_initialise`, `book_author`, `book_title`, and `book_genre`.

As another example, you can also think of an “author” with a name, a nationality and a birth year.

And with that data, you can have the functions `author_initialise`, `author_name`, `author_nationality`, `author_birth_year`, and `author_age`.

As a quick recap, here's how you could implement the abstraction of an author.

First, you need the function `author_initialise` to create authors:

```py
def author_initialise(
    first,
    last,
    nationality,
    birth_year,
):
    return (
        first, last, nationality, birth_year
    )
```

Contrary to the [implementation of the abstraction for the book](https://mathspp.com/insider/archive/objects-in-oop#concrete-implementation-of-a-book), an author is just a tuple.

Now, you can implement the functions that operate on that data:

```py
def author_name(author):
    return f"{author[0]} {author[1]}"

def author_nationality(author):
    return author[2]

def author_birth_year(author):
    return author[3]
```

The first three functions _need_ to make use of the fact that an author is being represented as a tuple.

But the function `author_age` can be implemented in terms of the other functions, so that's what you do:

```py
def author_age(author):
    # Gross simplification, but sure:
    return 2026 - author_birth_year(author)
```

You want to use the abstraction as much as possible and you don't want to rely on the “low-level” implementation since it might change...

## Grouping the functions in a better way

This idea of representing a real-world entity in code, giving it some data, and then defining functions, is very common.

If you imagine, for a second, that OOP hadn't been invented yet, you could see how this way of working is useful and generally applicable to other situations.

And if you start working with other entities, you'll start having lots of functions with random prefixes.

Just like you already have the `book_xxx` and `author_xxx` functions.

So, Python gives you a better way of grouping the functions.

If you use the keyword `class`, you can create a **namespace** where your functions can live.

What's a **namespace**?

It's a **space** for your **names**.

That's it.

Here's how it works.

First, use the keyword `class`, use the name of your entity to identify your namespace, and then write the functions inside the namespace.

Since Python works with whitespace, the functions need to be _indented_, so that Python understands they're part of the namespace:

```py
class Author:
    def author_initialise(first, last, nationality, birth_year):
        return (
            first, last, nationality, birth_year
        )

    def author_name(author):
        return f"{author[0]} {author[1]}"

    ...
```

Now, since the functions live _inside_ the namespace with the name `Author`, you can drop the prefix from the function name:

```py
class Author:
    # vvv
    def initialise(first, last, nationality, birth_year):
        return (
            first, last, nationality, birth_year
        )

    # vvv
    def name(author):
        return f"{author[0]} {author[1]}"

    ...
```

Now, whenever you want to use a function from the namespace `Author`, you just refer to the namespace and then use a dot `.` to access a function _inside_ the namespace.

Like so:

```py
charles = Author.initialise("Charles", "Dickens", "English", 1812)
print(Author.name(charles))  # Charles Dickens
```

Python creates this syntax to make it easier to group related functions together:

You use the keyword `class` to group a bunch of functions.

In exchange, Python lets you access those functions from a common name prefix and then using the dot notation.

This means that all your `author_xxx` functions, which were only loosely grouped by the name prefix, now became `Author.xxx` functions, and they're grouped under the name `Author`.

By the way, the namespace is called `Author`, with a capital `A`, instead of `author`, just because it's a convention.

In Python, it's common to have your class namespaces start with a capital letter to distinguish them from other variables you created.

## The advantages of the namespace

There's an immediate advantage of creating a grouping namespace with the keyword `class`.

You may think that the functions with the prefix `author_xxx` or the functions under the namespace `Author.xxx` look essentially the same.

And that it's up to personal preference whether you'd rather use the first or the second.

But there's an objective advantage to using the namespace.

If you use the namespace, created with the keyword `class`, there's an obvious home to all your author-related functions.

Before, it'd be very easy to spread your `author_xxx` functions throughout your code file.

Maybe even across code files.

Defining new `author_xxx` functions wherever you needed them.

By the time your project grows a bit, you no longer remember what data is associated with authors and what functions can operate on authors.

By using the keyword `class`, you're creating the obvious _home_ for all the author-related functions.

So, using the namespace promotes better code organisation, which in turn means you'll be writing code that's more readable and maintainable.

But there are more advantages to creating a namespace with the keyword `class`.

And those have to do with a couple of other things that Python decides to give you for free if you use OOP in Python.

Let's explore those next week!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
