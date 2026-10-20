---
date: 20-10-2026 12:22
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn the basics of descriptors using an analogy with a factory of properties."
    og:image: "https://mathspp.com/insider/archive/factory-of-properties/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/factory-of-properties/thumbnail.webp"
title: "Factory of properties"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Factory of properties

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter.
 > [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Class `Colour`

[Last week](https://mathspp.com/insider/archive/dynamic-attribute-validation) you started working on a class `Colour` that uses property setters to do attribute validation at runtime:

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
            raise TypeError(f"must be int, not {type(new_r)}")
        if not 0 <= new_r <= 255:
            raise ValueError(f"must be 0 <= r <= 255")
        self._r = new_r
```

The method `r` decorated with `@property` becomes the **getter** for the attribute `r`.

The method `r` decorated with `@r.setter` becomes the **setter** for the attribute `r`.

Everything has the same name (`r`) otherwise the decorator `property` wouldn't be able to do its magic.

What's next?

Implementing the rest of the class `Colour`, which is missing the components `g` and `b`.

The getters and setters for `g` and `b` are structurally the same as the getter and setter for `r`, so do you need to _triplicate_ your code?

## The dunder method `__get__`

The first thing you need to learn about is the dunder method `__get__`.

If a class has the dunder method `__get__`, that methods runs when that class is accessed as an attribute from _another_ class.

That sounds confusing, right?

But it is not.

Take a look at the class `FortyTwo`:

```py
class FortyTwo:
    def __get__(self, *_):
        return 42
```

If you create an instance of `FortyTwo` and call `__get__`, what do you get?

You get 42:

```py
ft = FortyTwo()
print(ft.__get__())  # 42
```

But the method `__get__` is special:

If a class has the dunder method `__get__`, that methods runs when that class is accessed as an attribute from _another_ class.

So, you can create a class that has an instance of `FortyTwo` as an attribute:

```py
class MyClass:
    ft = FortyTwo()
```

The code above sets an instance of `FortyTwo` to be an attribute of the class `MyClass`.

Now, if you access the attribute `ft` from `MyClass`, you won't see the instance `FortyTwo`..:

```py
print(MyClass.ft)  # 42
```

Instead, you get the value 42!

In other words, when you accessed the attribute that pointed to `FortyTwo()`, Python decided to run the method `__get__` instead.

This behaviour is also visible if you access `ft` through instances of `MyClass`:

```py
mc = MyClass()
print(mc.ft)  # 42
```

## The arguments of `__get__`

In the class `FortyTwo`, above, I defined the method `__get__` as `__get__(self, *_)`, thus hiding the arguments that `__get__` accepts.

You can inspect the actual arguments by printing them from within `__get__`.

For that, modify the implementation of `FortyTwo`:

```py
class FortyTwo:
    def __get__(self, *args):
        print(f"In __get__ with {args = }")
        return 42

class MyClass:
    ft = FortyTwo()
```

It's important to redefine `MyClass` after redefining `FortyTwo`, otherwise `MyClass` will still be using the old implementation of `FortyTwo`.

Now that you've assigned `FortyTwo` to `MyClass.ft`, you can try accessing it in its two different forms.

First, you can access it from the class `MyClass`:

```py
MyClass.ft
# In __get__ with args = (
#   None,
#   <class '__main__.MyClass'>
# )
```

Second, you can access it from an instance of the class `MyClass`:

```py
mc = MyClass()
mc.ft
# In __get__ with args = (
#   <__main__.MyClass object at 0x10ac8a510>,
#   <class '__main__.MyClass'>
# )
```

This shows that `FortyTwo.__get__` takes two arguments:

 1. the instance where the object was accessed from (or `None` if not available)
 2. the class that owns the instance of `FortyTwo`

This means that `__get__` can run arbitrary code that involves the class/instance where it was accessed from.

These introspection and dynamic capabilities are the reason **descriptors** are so useful.

## Descriptors

A class with the method `__get__` is called a descriptor.

It's a class whose objects can be used to dynamically compute attributes of the owner objects.

For example, you could create a descriptor for the red component of the class `Colour`:

```py
class RedComponent:
    def __get__(self, obj, cls):
        print("Inside __get__")
        return obj._r

class Colour:
    r = RedComponent()

    def __init__(self, r, g, b):
        self._r = r

purple = Colour(250, 0, 255)
print(purple.r)  # 250
# Inside __get__
```

## `__set_name__`

You're much closer to figuring out the solution to your `Colour` red/green/blue component problem.

But you're missing a final ingredient that allows even more introspection.

A descriptor already knows the object that owns it...

But, by using the dunder method `__set_name__`, the descriptor class can also know the **_name_** of the attribute it's assigned to.

That's very important given that we want a single descriptor that matches the following template:

```py
def <name>(self):
    return self._<name>
```

The solution then becomes:

```py
class PrivateAttributeGetter:
    def __set_name__(self, cls, name):
        print(f"Inside __set_name__ with {name = !r}")
        self.attr_name = f"_{name}"

    def __get__(self, obj, cls):
        print(f"Going to fetch attribute {self.attr_name}")
        return getattr(obj, self.attr_name)
```

An instance of `PrivateAttributeGetter` knows what attribute it's assigned to, and saves it for later.

But it prepends an underscore to the name.

So, if the argument is `name = 'red'`, then `self.attr_name` is `'_red'`.

In `__get__`, it uses that name to fetch the appropriate attribute.

That's done with `getattr(obj, self.attr_name)`, which will turn into `getattr(obj, '_r')`.

This is the same as doing `obj._r`, but programmatically.

The fact that this is all general means you can reuse the same class for all three colour components:

```py
class Colour:
    r = PrivateAttributeGetter()
    g = PrivateAttributeGetter()
    b = PrivateAttributeGetter()

    def __init__(self, r, g, b):
        self._r = r
        self._g = g
        self._b = b

# Inside __set_name__ with name = 'r'
# Inside __set_name__ with name = 'g'
# Inside __set_name__ with name = 'b'
```

Just by defining the class you already get the three lines of output shown in the comments.

Then, you can define an instance of `Colour` and try accessing the attributes:

```py
purple = Colour(250, 0, 255)
print(purple.r)  # Going to fetch attribute _r
# 250
print(purple.g)  # Going to fetch attribute _g
# 0
print(purple.b)  # Going to fetch attribute _b
# 255
```

## Descriptors

The name of this black magic?

[**Descriptors**](https://mathspp.com/blog/pydonts/describing-descriptors).

A Python descriptor is a class that provides the dunder method `__get__`.

In doing so, it allows customising attribute access (just like `property`).

But we're only half way done...

Colour components must also perform validation when setting the attribute.

You'll learn how to do that next week.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
