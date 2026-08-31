---
date: 31-08-2026 14:06
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how 'self' plays an important role in OOP in Python and where it comes from."
    og:image: "https://mathspp.com/insider/archive/the-magic-of-self/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/the-magic-of-self/thumbnail.webp"
title: "The magic of self"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 The magic of self

!!! This issue explores one piece of a bigger puzzle. Read [Why OOP exists](/blog/why-oop-exists) for the complete, step-by-step journey from the core ideas of OOP to Python classes, objects, methods, and `self`.

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Your class `Author`

Over the past few weeks we've been riding a rollercoaster.

Essentially deriving OOP from scratch.

Last week we stopped at a pretty decent place.

You [already have attributes](https://mathspp.com/insider/archive/representing-custom-objects):

```py
class Author:
    def initialise(author, first, last, nationality, birth_year):
        author.first = first
        author.last = last
        author.nationality = nationality
        author.birth_year = birth_year

    def name(author):
        return f"{author.first} {author.last}"

    def age(author):
        return 2026 - author.birth_year  # Gross simplification.
```

And to use your class `Author`, you can do something like this:

```py
charles = Author()
Author.initialise(charles, "Charles", "Dickens", 1812)
print(Author.name(charles))  # Charles Dickens
print(Author.age(charles))  # 214
```

Today, you'll learn how to use **Python methods** to simplify the code above even further.

## Removing redundancy

The first line of the snippet above reads `charles = Author()`.

This sets that the variable `charles` points at an object that represents an author.

So, it feels quite redundant when, in the next three lines, you need to spell out the namespace `Author` to access the functions `initialise`, `name`, and `age`.

And Python agrees with you.

Python thinks this is pretty redundant.

So, Python gives you a shortcut.

You can use the dot notation to access functions of a class namespace from an object of that class directly.

That is, you can use the object `charles` to access the functions of the namespace `Author` directly!

This means your code would look a bit more like this:

```py
charles = Author()
#vvvvvv
charles.initialise(charles, "Charles", "Dickens", 1812)
#     vvvvvvv
print(charles.name(charles))  # Charles Dickens
print(charles.age(charles))  # 214
```

But now this is arguably even worse...

Now, instead of spelling out the class namespace `Author`, you have to spell out the same variable twice...

Python goes a step further to help you out here, too.

Since you want to use a function from `Author` on the object `charles`, and you already established that by using the dot notation directly on `charles`, you _can omit the argument `charles` from the argument list_.

This means your code becomes a bit slimmer:

```py
charles = Author()
#                  v `charles` is gone
charles.initialise("Charles", "Dickens", 1812)
print(charles.name())  # Charles Dickens
print(charles.age())  # 214
```

You can give it a go yourself.

_Without_ modifying the class `Author`, from before, this new snippet of code should work.

## An argument mismatch

But this raises a funny question.

If you go back to the class definition, you see that the three functions accepted some arguments:

 - `initialise` has four parameters
 - `name` & `age` have one parameter each

However, the snippet above uses fewer arguments:

 - `initialise` is called with three arguments
 - `name` & `age` are called with zero arguments

How can you explain this mismatch?

The truth is that the mismatch is only _apparent_.

To make your life easier, Python fills in the first argument automatically.

In a way, Python takes an expression like `charles.age()` and translates it to `Author.age(charles)`.

Similarly, Python takes an expression like `charles.initialise("Charles", ...)` and translates it to `Author.initialise(charles, "Charles", ...)`.

When you access a class namespace function through an object of that class, you call it a **method**.

For example, when you write `charles.age()`, you can say you're calling the **method** `age`.

When you define functions inside a class namespace, it's because you want to use them with objects of that class.

So, we usually call those functions **methods**.

As an example, you'd say that the class `Author` has three methods: `initialise`, `name`, and `age`.

## The magic of `self`

If you've been doing Python for a while or if you've read any other OOP tutorial, you might be puzzled about something.

In a class, the first parameter of a method is always `self`.

And yet, the parameters of your `Author` methods are all `author`.

It so happens that `self` is **a convention** of the Python world.

This convention is so well established, that it's almost like a rule.

This convention just says that you should use `self` as the name of the first parameter of each method in a class.

Thus, the Pythonic way of defining the class `Author` looks like this:

```py
class Author:
    def initialise(self, first, last, birth_year):
        ...

    def name(self):
        ...

    def age(self):
        ...
```

What's really important here is that the word “self” is merely a convention.

Using `self`, `author`, or `bananas`, is equivalent from a practical point of view.

The code will work the same way.

In fact, the code _has_ been working up to this point and the first parameter was called `author`, not `self`.

## Convenient initialisation

Our OOP saga is coming close to an end.

Next week, you'll learn about a final trick that Python has up its sleeve to make it more convenient to initialise objects.

(Spoiler alert: it has to do with “magic methods” or “[dunder methods](https://mathspp.com/blog/pydonts/dunder-methods)”.)

Learning about this trick will conclude our foray into the fundamentals of OOP in Python.

What would you like me to write about next?

Reply to this email to let me know :)

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
