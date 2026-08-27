---
date: 27-08-2026 17:23
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how Python classes provide a way to create standard representations for your custom objects."
    og:image: "https://mathspp.com/insider/archive/representing-custom-objects/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/representing-custom-objects/thumbnail.webp"
title: "Representing custom objects"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Representing custom objects

!!! This issue explores one piece of a bigger puzzle. Read [Why OOP exists](/blog/why-oop-exists) for the complete, step-by-step journey from the core ideas of OOP to Python classes, objects, methods, and `self`.

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

In past weeks you learned [why OOP exists](https://mathspp.com/insider/archive/why-oop-exists), that [OOP code revolves around objects](https://mathspp.com/insider/archive/objects-in-oop) (hence the name), and that [using the keyword `class` creates a namespace](https://mathspp.com/insider/archive/classes-create-namespaces).

Today you'll start learning about all the wonderful features Python has for working with objects and OOP in general.

## Using tuples to represent objects

[In a previous issue](https://mathspp.com/insider/archive/objects-in-oop) you represented a book as the sequence of code points for the concatenation of the strings associated with the book, along with the cumulative lengths of the concatenated fields.

(Try saying that three times, fast.)

That's what meant that the novel “Oliver Twist”, by Charles Dickens, was represented as

```py
((15, 27), [67, 104, 97, 114, 108, 101, 115, 32, 68, 105, 99, 107, 101, 110, 115, 79, 108, 105, 118, 101, 114, 32, 84, 119, 105, 115, 116, 110, 111, 118, 101, 108])
```

I know we agree this is a brilliant representation.

But it's not for the feint of heart.

So, for authors, [you represented them as a tuple with the data in different elements](https://mathspp.com/insider/archive/classes-create-namespaces).

Charles Dickens, the English author born in 1812, was represented as

```py
("Charles", "Dickens", "English", 1812)
```

But using a tuple, or any other collection, brings some problems with it.

From the outside, all tuples look alike.

The fact that the tuple `("Charles", ...)` is “an author” is merely a convention.

It's a convention that you set and that no one is forced to honour.

And given a tuple, there's no trivial way to figure out if that's supposed to be a tuple or an author.

So, Python provides a _standard_ way to create objects that represent a certain entity.

## How to create objects with the class

When you use the keyword `class`, you're creating a class that contains a namespace.

You already saw that the namespace can be used to group your functions.

For example, here's the class `Author` that holds author-related functions:

```py
class Author:
    def initialise(first, last, nationality, birth_year):
        return (
            first, last, nationality, birth_year
        )

    def name(author):
        return f"{author[0]} {author[1]}"

    ...
```

Now that you have `Author` as the natural home for author-related functions, might as well leverage that.

In fact, Python allows you to use the word `Author` to create author objects!

All you need to do is use parentheses in front of `Author`, just as if you were calling a function:

```py
author = Author()
```

Now, you can print this value and see what it looks like:

```py
print(author)
# <__main__.Author object at 0x10898ef90>
```

This definitely doesn't look like a tuple.

Instead, you see a lot of gibberish and then the words “Author object” somewhere in the middle of that gibberish.

This is the _standard_ representation of an author that Python gives you access to.

And when you run the code `author = Author()`, you can think of `author` as being a plain value suitable to represent an author.

But when you create it, the value doesn't know anything about any particular author.

You have to customise your value with the relevant data.

The function `Author.initialise` created tuples representing authors.

And it filled those tuples with the correct data.

Now you must tweak the function.

You _already_ have an object that represents an author, you only need to fill the data in.

So, first and foremost, the function `initialise` also needs to accept the author argument:

```py
class Author:
    def initialise(
        author,  # <-- NEW !!!
        first, last, nationality, birth_year,
    ):
        ...
```

Next, since the plain author object is passed to `Author.initialise` already, you just need to plug the data of the other arguments into the object.

The way Python lets you do that is by using the dot (`.`) notation to attach the data directly to the author:

```py
class Author:
    def initialise(
        author,  # <-- NEW !!!
        first, last, nationality, birth_year,
    ):
        author.first = first
        author.last = last
        author.nationality = nationality
        author.birth_year = birth_year
```

This dot notation is essentially the same as the one you're using to access functions from the namespace of the class `Author`.

In a way, it's as if the object `author = Author()` provided its own namespace for its own data.

The pieces of data you save in this manner are called **attributes** in Python.

An **attribute** is a name you can access directly from an object.

## Simplifying code that depends on objects

Now that you have your standard representation and **attributes**, code that uses your objects becomes simpler.

For example, the function `Author.name` used to be:

```py
class Author:
    ...

    def name(author):
        return f"{author[1]} {author[2]}"
```

This implementation reveals yet another problem of using a tuple to represent an object...

The index access _has no meaning_ and is easy to mess up.

Did you notice how, in the implementation above, I introduced a bug on purpose?

The first and last names of an author were in positions zero and one, not one and two.

But using **attributes**, this is no longer an issue.

The new, improved version of `Author.name` becomes

```py
class Author:
    ...

    def name(author):
        return f"{author.first} {author.last}"
```

This version is easier to read and to reason about, whereas the previous one was opaque.

## Simplifying the use of the functions

Python does more for you.

Next week, you'll learn about how Python uses all this syntax to make it easier for you to use the functions inside a class namespace.

You'll look at a piece of code like

```py
charles = Author()
Author.initialise(charles, "Charles", "Dickens", 1812)
print(Author.name(charles))  # Charles Dickens
print(Author.age(charles))  # 214
```

And you'll see how to simplify it by removing a lot of redundancy.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
