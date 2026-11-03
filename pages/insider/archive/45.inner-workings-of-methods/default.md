---
date: 03-11-2026 08:00
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn the inner workings of methods and the mechanism that automatically inserts 'self' in method calls."
    og:image: "https://mathspp.com/insider/archive/inner-workings-of-methods/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/inner-workings-of-methods/thumbnail.webp"
title: "Inner workings of methods"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Inner workings of methods

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Functions are descriptors

For the past few weeks you've been learning about descriptors.

Today you'll learn how descriptors are the mechanism that powers methods and the magic argument `self`.

The most important thing you need is to remember that the dunder method `__get__` is a key part of descriptors:

```py
class MyDescriptor:
    def __get__(self, obj, cls):
        print("Running `__get__`")
        return 42

class MyClass:
    bananas = MyDescriptor()

mc = MyClass()
print(mc.bananas)
# Running `__get__`
# 42
```

When you access the attribute `bananas`, instead of getting the instance of `MyDescriptor`, you run the method `__get__`.

In the end, you get the value that comes out of `__get__`, which is 42.

And the truth is that regular functions are also descriptors.

You can know this because they define the dunder method `__get__`:

```py
print("__get__" in dir(my_function))
```

As you'll see, this is the key that explains how methods automatically have the argument `self`.

But there's another important ingredient here.

## Classes are just namespaces

A class defined with the keyword `class` is a namespace.

But you can write any code you want inside a class:

```py
class Useless:
    for age in range(16, 20):
        if age < 18:
            print(f"At {age} you're underaged in Portugal")
        else:
            print(f"At {age} you can already vote in Portugal!")
```

If you run this code, this is the result you get:

```text
At 16 you're underaged in Portugal
At 17 you're underaged in Portugal
At 18 you can already vote in Portugal!
At 19 you can already vote in Portugal!
```

The only difference between running the loop and the conditional inside the class is that the variable `age` remains defined in the class:

```py
print(Useless.age)  # 19
```

But this shows that a class is just a namespace.

And names defined inside the class will live inside that namespace.

The point is to show that a function that's defined inside the class is just the same as a function defined outside the class.

## Methods aren't special

The first thing you need to understand is that a method isn't special because it's defined _inside_ the class.

Here's a class `Square` with a regular method:

```py
class Square:
    def __init__(self, length):
        self.length = length

    def area(self):
        return self.length ** 2

sq = Square(5)
print(sq.area())  # 25
```

But here's the exact same behaviour, with a function defined outside the class:

```py
class Square:
    def __init__(self, length):
        self.length = length

def area_out(square):
    return square.length ** 2

sq = Square(5)
print(area_out(sq))  # 25
```

And you can recover the method behaviour if you assign `area_out` to be an attribute of the class:

```py
class Square:
    def __init__(self, length):
        self.length = length

def area_out(square):
    return square.length ** 2

Square.area = area_out

sq = Square(5)
print(sq.area())  # 25
```

Note how `sq.area` worked just fine, even though `area_out` wasn't defined as a method initially.

And note how the argument `self` was populated automatically just like normal.

## When is a function a method?

To see how this happens, you can implement functions and methods yourself.

Here's a class that could represent a function that computes the area of a square:

```py
class SquareAreaFunction:
    def __call__(self, square):
        return square.length ** 2

area_function = SquareAreaFunction()
area_function(sq)  # 25
```

But functions are descriptors, so they must implement `__get__`.

The question is, what should `__get__` return?

Well, you can look at the previous implementation of the class `Square` to see what should happen:

```py
print(area_out)  # <function area_out at 0x106502c40>
print(Square.area)  # <function area_out at 0x106502c40>
print(sq.area)  # <bound method area_out of <__main__.Square object at 0x10649a510>>
```

The function by itself and when accessed from the class look the same.

And it looks like a regular function.

But when the function is accessed _through_ a class instance, it looks like a method.

This is what you have to implement yourself.

## Accessing the function from the class

Let's try to match that structure to our function built by hand.

First, implement a `__get__` that does nothing:

```py
class SquareAreaFunction:
    def __call__(self, square):
        return square.length ** 2

    def __get__(self, obj, cls):
        print("Inside SquareAreaFunction.__get__")
        return None
```

Now, plug that function into the class `Square`:

```py
area_function = SquareAreaFunction()
Square.area = area_function
sq = Square(5)

print(area_function)  # <__main__.SquareAreaFunction object at 0x106499400>
print(Square.area)  # Inside SquareAreaFunction.__get__; None
print(sq.area)  # Inside SquareAreaFunction.__get__; None
```

You see that `__get__` is being called twice.

The first time, in `Square.area`, it should return the function itself.

But when you access the function as `Square.area`, the argument `obj` is `None` and the class `cls` is available, so you can detect that case quite well:

```py
class SquareAreaFunction:
    def __call__(self, square):
        return square.length ** 2

    def __get__(self, obj, cls):
        if obj is None:
            print(f"Inside SquareAreaFunction.__get__ with {cls = }")
            return self
        print("Inside SquareAreaFunction.__get__")
        return None
```

The check `if obj is None` checks if the function is being accessed from the class:

```py
area_function = SquareAreaFunction()
Square.area = area_function
sq = Square(5)

print(area_function)  # <__main__.SquareAreaFunction object at 0x106499400>
print(Square.area)  # Inside SquareAreaFunction.__get__ with cls = <class '__main__.Square'>
# <__main__.SquareAreaFunction object at 0x10649a7b0>
print(sq.area)  # Inside SquareAreaFunction.__get__; None
```

The new code for `__get__` already fixes the `print(Square.area)`.

Accessing the function outside the class or from the class should be the same thing.

The only thing that's left is fixing the `sq.area`, which should be a method...

## Implementing a method

So, that's what you need to implement next:

```py
class BoundMethod:
    def __init__(self, function, instance):
        self.function = function
        self.instance = instance

    def __call__(self, *args, **kwargs):
        return self.function(self.instance, *args, **kwargs)
```

This class `BoundMethod` represents methods, and it's what you must return from `__get__` when the function is accessed through another class instance:

```py
class SquareAreaFunction:
    def __call__(self, square):
        return square.length ** 2

    def __get__(self, obj, cls):
        if obj is None:
            print(f"Inside SquareAreaFunction.__get__ with {cls = }")
            return self

        print("Inside SquareAreaFunction.__get__")
        function = self  # Inside SquareAreaFunction,
        # “self” IS a function
        return BoundMethod(function, obj)
```

When the square area function is accessed as a method, you're returning a _**bound method**_.

What's that?

It's really just a function with the first argument (`self`) frozen in place.

That's the mechanism that “automatically inserts `self`”.

This “automatic insertion” is spelled out in the code: `BoundMethod(function, obj)`.

When the bound method is called, the object is the first argument to the function:

```py
class BoundMethod:
    # ...
    def __call__(self, *args, **kwargs):
        return self.function(self.instance, *args, **kwargs)
```

Here's proof that it works:

```py
area_function = SquareAreaFunction()
Square.area = area_function
sq = Square(5)

print(area_function)  # <__main__.SquareAreaFunction object at 0x106499400>
print(Square.area)  # Inside SquareAreaFunction.__get__ with cls = <class '__main__.Square'>
# <__main__.SquareAreaFunction object at 0x10649a7b0>
print(sq.area)  # Inside SquareAreaFunction.__get__
# <__main__.BoundMethod object at 0x106499400>
```

When accessed as a method, you get a method.

Does it run, though..?

```py
print(sq.area())  # Inside SquareAreaFunction.__get__; 25
```

It runs, and it runs correctly!

You're getting the result 25.

And most importantly, before you see the result, you get the print from inside `__get__`.

That's because methods always start with an attribute access.

Think about it!

The expression `sq.area()` has two components, the `sq.area`, which is an attribute access, and the `...()`, which is a function call.

The attribute access is resolved first, which is what triggers the function descriptor mechanism to return a method.

Only then you call the method to get the result.

## Got your brain twisted?

If this is the first time you're working through this, it can twist your brain.

Especially since there's only so much you can reinvent in Python when trying to do it from first principles.

And the fact that you're using classes to write functions and methods, which themselves are using the magic you're trying to replicate (`self` being inserted automatically) doesn't help.

Allow yourself some time to process this and play with the code examples.

With time, it'll make sense!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
