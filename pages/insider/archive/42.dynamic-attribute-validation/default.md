---
date: 13-10-2026 14:36
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to use properties to validate attribute values during assignment."
    og:image: "https://mathspp.com/insider/archive/dynamic-attribute-validation/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/dynamic-attribute-validation/thumbnail.webp"
title: "Dynamic attribute validation"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Dynamic attribute validation

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Properties

[Last week you learned about properties](https://mathspp.com/insider/archive/dynamic-attribute-lookup-with-properties), which you can use to run an arbitrary function when you access an attribute.

For example, the class `Person` shown below has a property called `name`:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last

    @property
    def name(self):
        return f"{self.first} {self.last}"
```

If you create an instance of the class `Person` and then try to access the attribute `name`, you get the full name:

```py
john = Person("John", "Doe")
print(john.name)  # John Doe
```

This is good because you'll always get the correct name if the first or last names change.

In short, a property turns an attribute access into a function call.

But properties can do much more.

Now, you'll learn how to use a property to validate values when they're assigned to the attribute.

## Property setters

Once you create a property, you get access to a second decorator, `@xxx.setter`, that runs _when the attribute is set_.

Still in the `Person` example, you want to be able to do something like this:

```py
person = Person("John", "Doe")
person.name = "Amelia Smith"
```

And you want the assignment to populate the attributes `first` and `last`, since those are the attributes that the class `Person` uses.
In short, you want the following assertions to pass:

```py
person = Person("John", "Doe")
person.name = "Amelia Smith"

assert person.first == "Amelia"
assert person.last == "Smith"
```

You can do that by writing a function that runs _when_ the attribute `name` is assigned to:

```py
class Person:
    def __init__(self, first, last):
        self.first = first
        self.last = last

    @property
    def name(self):
        return f"{self.first} {self.last}"

    @name.setter  # <--
    def name(self, new_name):
        self.first, self.last = new_name.split()
```

The new method wrapped in the decorator `@name.setter` is what allows the rest of the code to run.

Now, the assertions pass.

Alternatively, you can try printing the attributes so you have more visual output:

```py
person = Person("John", "Doe")
person.name = "Amelia Smith"

print(person.first)  # "Amelia"
print(person.last)  # "Smith"
```

## How property setters work

Let's break this down step by step.

First, you define a method that becomes a property:

```py
class Person:
    ...
    @property
    def name(self):
        ...
```

The name of the function becomes the name of the attribute/property, and the decorator `property` is a built-in.

This also creates a new decorator, which is `@xxx.setter`.

Here, `xxx` should be the name of the property, which is `name`.

So, the decorator `@xxx.setter` is actually `@name.setter`.

Then, that decorator can be used around another method _with the same name_, to turn it into the _setter_ of that attribute.

That's done in

```py
class Person:
    ...
    @name.setter
    def name(self, new_name):
        ...
```

At this point, the name of the decorator and the name of the method must match.

Also, notice how the method takes an argument other than `self`.

This argument will be the _value_ that's being assigned to the attribute.

Try adding some calls to `print` inside the two methods to see everything in action.

## A setter with validation

Suppose you're defining a class to represent an RGB colour:

```py
class Colour:
    def __init__(self, r, g, b):
        self.r, self.g, self.b = r, g, b
```

But you want to validate that the attributes `r`, `g`, and `b`, are always integers in the range `0` through `255`, inclusive.

You can achieve this by using a property.

Let us focus on the attribute `r`, since the other two will be the same.

If you want `r` to be a property with a setter that performs validation, this means you need to _hide_ the real value in an attribute with a different name.

Using a leading underscore is a common choice, so `_r` will be the attribute that hides the real value and the attribute `r` is what the users will use.

The property getter just fetches the value:

```py
class Colour:
    def __init__(self, r, g, b):
        self._r = r
        ...

    @property
    def r(self):
        return self._r

red = Colour(255, 0, 0)
print(red.r)  # 255
```

Now, for the setter, you can perform the validation you need:

 - the value must be an integer
 - the value must be between 0 and 255, inclusive

```py
class Colour:
    ...

    @r.setter
    def r(self, new_r):
        if not isinstance(new_r, int):
            raise TypeError(f"red component must be an integer, not {type(new_r)}")
        if not 0 <= new_r <= 255:
            raise ValueError(f"invalid red component {new_r}, must be 0 <= r <= 255")
        self._r = new_r
```

By doing so, you can now reject invalid values:

```py
red = Colour(255, 0, 0)

red.r = 200
print(red.r)  # 200

red.r = 300
# ValueError: ...
```

At this point, you can also use the property setter validation from inside `__init__` directly.

For that, you assign to `r` directly and let the setter do the validation:

```py
class Colour:
    def __init__(self, r, g, b):
        self.r = r

    @property
    def r(self):
        return self._r

    @r.setter
    def r(self, new_r):
        if not isinstance(new_r, int):
            raise TypeError(f"red component must be an integer, not {type(new_r)}")
        if not 0 <= new_r <= 255:
            raise ValueError(f"invalid red component {new_r}, must be 0 <= r <= 255")
        self._r = new_r
```

Now, if you try to create the colour with invalid values, you get an error immediately:

```py
red = Colour(999, 0, 0)
# ValueError: ...
```

## Full colour source code

That's the code for the component `r`.

Can you complete the class definition to include `g` and `b` as well?

It's just a matter of doing a lot of copy-pasting...

(And fixing the attribute names...)

It's boring, right?

Isn't there a better way..?

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
