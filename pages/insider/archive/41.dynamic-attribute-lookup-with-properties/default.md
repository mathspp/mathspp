---
date: 05-10-2026 15:55
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how properties allow you to compute attributes dynamically"
    og:image: "https://mathspp.com/insider/archive/dynamic-attribute-lookup-with-properties/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/dynamic-attribute-lookup-with-properties/thumbnail.webp"
title: "Dynamic attribute lookup with properties"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Dynamic attribute lookup with properties

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## The problem with static attributes

Suppose you have a class `Person` defined as such:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last
        self.name = f"{first} {last}"
```

You instantiate the class with the first and last names of that person.

Then, you can access the attribute `name` with the full name:

```py
john = Person("John", "Doe")
print(john.name)  # John Doe
```

The problem with this implementation is that the attributes `first`, `last`, and `name`, are related but that connection isn't explicit in the implementation.

That means you can change the name of an instance only partially:

```py
john.first = "Steve"
print(john.name)  # John Doe
```

This is wrong because now the first name and the full name don't agree.

## Setter methods

One way to fix this is by updating the full name whenever the first or last names change.

This can only be done if you define _setter_ methods for `first` and `last`.

Those would be functions that update the respective attributes and the value of `name`:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last
        self.name = f"{self.first} {self.last}"

    def set_first(self, first):
        self.first = first
        self.name = f"{self.first} {self.last}"

    def set_last(self, last):
        self.last = last
        self.name = f"{self.first} {self.last}"
```

This works:

```py
john = Person("John", "Doe")
print(john.name)  # John Doe

john.set_first("Steve")
print(john.name)  # Steve Doe
```

This isn't very elegant, though, since it's structurally very repetitive.

But there's another fix.

## Getter methods

Another way in which you could fix this is by creating a _getter_ method for `name`.

This means that, instead of defining a regular attribute, you define a method whose sole purpose is to compute the value of the name.

This is more appropriate since a function call will always pick up the most recent version of the name.

First, you define the getter method:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last

    def get_name(self):
        return f"{self.first} {self.last}"
```

Then, you can use that method to fetch the name of a person:

```py
john = Person("John", "Doe")
print(john.get_name())  # John Doe
```

This is a very common practice in various programming languages, but it's not the way in which Python usually does things.

I think this is better than the two setter methods from above.

But Python has a better fix.

## Dynamic attributes with properties

The Python built-in `property` can be used to create attributes that are computed dynamically.

You do that with a bit of dark magic (but not too much dark magic).

First, you write a function that computes the attribute you care about.

In this case, we already have the getter method for `name`:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last

    def get_name(self):
        return f"{self.first} {self.last}"
```

Next, you change the name of the method to match the name of the attribute you'd like to have.

The method `get_name` is so that you can compute the wannabe attribute `name`, so you change the method name to `name`:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last

    def name(self):  # get_name -> name
        return f"{first} {last}"
```

After you have the method with the new signature, you use the built-in `property` as a decorator around the method:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last

    @property  # <-- add the `property` built-in
    def name(self):
        return f"{first} {last}"
```

After these three steps (one of which was done already) you have an attribute that can be computed dynamically.

The beauty of this is that `Person.name` now _looks_ like an attribute:

```py
john = Person("John", "Doe")
print(john.name)  # John Doe
```

In the snippet of code above, it's `john.name` _without_ the parentheses.

So, it doesn't look like you're calling a function.

Under the hood, Python calls the method `name` to compute the name.

That's how you can keep the name in sync:

```py
john = Person("John", "Doe")
print(john.name)  # John Doe

john.first = "Rodrigo"
john.last = "Girão Serrão"
print(john.name)  # Rodrigo Girão Serrão
```

## Property functionality

You're just scratching the surface of what properties can do.

You can learn more about properties by reading [this blog article on the built-in `property`](https://mathspp.com/blog/pydonts/properties).

You can use properties to intercept setting attributes.

To create pseudo-read-only attributes.

And more.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
