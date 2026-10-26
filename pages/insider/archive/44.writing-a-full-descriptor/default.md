---
date: 26-10-2026 16:40
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how descriptors use the method __set__ to dynamically set attributes."
    og:image: "https://mathspp.com/insider/archive/writing-a-full-descriptor/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/writing-a-full-descriptor/thumbnail.webp"
title: "Writing a full descriptor"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Writing a full descriptor

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## A factory of private attributes

[Last week](https://mathspp.com/insider/archive/factory-of-properties) you learned how to create a descriptor that can be reused to implement private attributes:

```py
class PrivateAttributeGetter:
    def __set_name__(self, cls, name):
        print(f"Inside __set_name__ with {name = !r}")
        self.attr_name = f"_{name}"

    def __get__(self, obj, cls):
        print(f"Going to fetch attribute {self.attr_name}")
        return getattr(obj, self.attr_name)
```

The class `PrivateAttributeGetter` implements the logic for a private attribute.

Here's how you can use `PrivateAttributeGetter` to create a class `Colour` with three private attributes:

```py
class Colour:
    r = PrivateAttributeGetter()
    g = PrivateAttributeGetter()
    b = PrivateAttributeGetter()

    def __init__(self, r, g, b):
        self._r = r
        self._g = g
        self._b = b
```

Today, you're going to learn how to use descriptors to allow dynamically setting the values of attributes.

The end goal?

You want to have a class `Colour` that automatically validates the colour components when you set them.

## The dunder method `__set__`

Much like Python runs the method `__get__` when you try to **get** an attribute, the method `__set__` runs when you try to **set** it.

As an example, you can start by seeing how `__set__` is triggered:

```py
class SetExample:
    def __set__(self, *args):
        print(f"Inside __set__ with {args = }")

class MyClass:
    x = SetExample()

mc = MyClass()
mc.x = 73
# Inside __set__ with args = (<__main__.MyClass object at 0x1070fe3c0>, 73)
```

When you try to assign to the attribute that's a descriptor, the method `__set__` is called with two arguments other than `self`:

 1. the object that owns the descriptor
 2. the new value for the attribute

It's up to the method `__set__` to do something useful with that new value.

This might include validating the value, for example.

But something that's also important is figuring out _where_ to save the new value.

That's why `__set__` is typically used in conjunction with the method `__set_name__`, that [you learned about last week](https://mathspp.com/insider/archive/factory-of-properties#set-name).

As a quick example, you can save the descriptor's attribute's name and then save the value inside the attribute with the same name but with a leading underscore.

For example, that's how you could create a descriptor that verifies that numeric attributes fall within a certain range:

```py
class InRange:
    def __init__(self, low, high):
        self.low = low
        self.high = high

    def __set_name__(self, cls, name):
        self.attr_name = f"_{name}"

    def __get__(self, obj, cls):
        return getattr(obj, self.attr_name)

    def __set__(self, obj, new_value):
        if not self.low <= new_value <= self.high:
            raise ValueError(f"value should be between {self.low} and {self.high}")
        setattr(obj, self.attr_name, new_value)
```

Much like you have to use `getattr` inside `__get__` to programmatically fetch the value from the private attribute, you also have to use `setattr` to programmatically assign the new value to the correct attribute.

Remember that you're using these, with `self.attr_name`, because the descriptor can't know what are the names of the attributes it's going to be used with.

So, to keep it general, it must do the attribute operations programmatically.

Here's an example of `InRange` in use:

```py
class Person:
    age = InRange(0, 140)

john = Person()
john.age = 73
john.age = 200
# ValueError: value should be between 0 and 140
```

## Finishing the class `Colour`

Now that you know how to use `__set__`, you can finish implementing the class `Colour`.

A descriptor `ColourComponent` will be very similar to the class `InRange`, with the extra check that the new value must be an integer.

A possible implementation loolks like this:

```py
class ColourComponent:
    def __set_name__(self, cls, name):
        self.attr_name = f"_{name}"

    def __get__(self, obj, cls):
        return getattr(obj, self.attr_name)

    def __set__(self, obj, new_value):
        if not isinstance(new_value, int):
            raise TypeError("component must be an integer")
        if not 0 <= new_value <= 255:
            raise ValueError("value should be between 0 and 255")
        setattr(obj, self.attr_name, new_value)
```

After defining `ColourComponent`, you can define the class `Colour` that uses it:

```py
class Colour:
    r = ColourComponent()
    g = ColourComponent()
    b = ColourComponent()

    def __init__(self, r, g, b):
        self.r = r
        self.g = g
        self.b = b

purple = Colour(255, 0, 200)
purple.b = 300
# ValueError: value should be between 0 and 255
```

## Great, but what are descriptors useful for..?

You're learning about descriptors and it's great.

But what is the point?

When is this useful?

Well, do you know what fundamental Python objects are descriptors..?

Functions are descriptors!

Really!

Here, start by defining a function that does nothing:

```py
def nothing():
    pass
```

If you look at its methods, you'll find a method `__get__` in there:

```py
print(
    "__get__" in dir(nothing)
)  # True
```

Why are functions descriptors..?

Because it's the method `__get__` that turns a function into a method!

You know why that's required..?

Because it is the descriptor mechanism that magically inserts the argument `self` in every method call!

## How methods work under the hood

Do you want to learn a bit more about this?

Let me know by replying to this email.

Next week, we'll either talk about how descriptors, functions, `self`, and methods are all related...

Or about something else.

You decide.

Just reply to this email to let me know.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
