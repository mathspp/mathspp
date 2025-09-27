# 🐍🚀 what's there beyond `__init__`?

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider#subscribe) to get weekly Python deep dives like this one on your inbox!

## `__init__` is just a method

Consider the following, very basic, class Person:

```py
class Person:
    def __init__(self, name):
        self.name = name
```

Now, I'll create an instance of a person called John:

```py
john = Person("John")
```

You know that Python called the method `__init__` behind the scenes, so that `john.name` is the string `"John"`:

```py
print(john.name)  # John
```

But the method `__init__` is a regular method.

It has a funky name.

But it's a regular method.

So, you can call it at will:

```py
john.__init__("Steve")
```

After this call, what do you think is the value of `john.name`?

It's `"Steve"`!

```py
print(john.name)  # Steve
```

Since the method `__init__` runs instance initialisation, when you call `__init__` you are re-initialising your object, effectively mutating it.

Here's another example with a list:

```py
my_list = [42, 73, 0, 16, 10]
my_list.__init__(range(3))
print(my_list)  # [0, 1, 2]
```

## Immutability and `__init__`

Now, the fun thing is that this only works with mutable types.

Think about it...

If it worked with immutable types, they wouldn't be immutable!

Here's an example showing how calling `__init__` on a float does absolutely nothing:

```py
f = 0.5
f.__init__(3.4)

print(f)  # 0.5
```

Again, this HAS to be this way.

Otherwise, floats wouldn't be immutable...

So, this shows that there must be some other method playing a part in object creation.

There must be another method that actually built the immutable float with the value `0.5`...

## Something happens before `__init__`

In case it isn't clear yet, this other dunder method must execute before `__init__`.

As proof, consider this class that inherits from float and that tries to accept a second argument:

```py
class SubFloat(float):
    def __init__(self, value, arg):
        super().__init__(value)
```

The point is that we're only passing the single argument `value` to the parent class, which is what the parent class expects.

But we cannot instantiate this class `SubFloat`, for some unknown reason...

```py
sf = SubFloat(0.5, "Whatever")
# TypeError: float expected at# most 1 argument, got 2
```

So...

Something that happens before `__init__` expected a single argument (the value you need to build a float) and you passed it two.

This is happening because `float` has a dunder method that we have not overridden in our class `SubFloat...`

## The dunder method `__new__`

This special, magical, unknown dunder method is the dunder method `__new__`.

`__new__` is responsible for creating new objects, while `__init__` is only responsible for initialising them.

`float.__new__` only expects a single argument (the value), which is why it broke when we wrote `sf(0.5, "Whatever")`.

When creating an instance of a class, Python starts by calling `__new__` to create the new object.

Only then Python calls `__init__` to initialise it.

Now, here's the fun stuff...

Since `__new__` is what's CREATING the object, it can't take `self` as the first argument!

`__new__` is actually a class method that accepts the class it's trying to instantiate.

So, how do we make our `SubFloat` work..?

## Subclassing an immutable type

`SubFloat` must implement a method `__new__` that accepts two arguments and then it must defer to float's method `__new__`, so that the float is created correctly:

```py
class SubFloat(float):
    def __new__(cls, value, arg):
        return super().__new__(cls, value)
```

`__new__` is a class method that must RETURN the value that is being created.

This is different from `__init__`, which is tasked with mutating the object to initialise its state.

So, at this point, we can create instances of Sub`Float:

```py
sf = SubFloat(0.5, "Whatever")
print(sf)
```

Pretty cool, but what's the point?!

## Tolerant float

An example of a useful subclass of the built-in immutable `float` is to create a “tolerant float”.

Let us say that a tolerant float is a floating point number that uses an error tolerance when making equality comparisons.

Here's how you could create one:

```py
from math import isclose

class TolerantFloat(float):
    def __new__(cls, value, rel_tol):
        # Create the float.
        float_obj = super().__new__(cls, value)
        # Save the relative tolerance.
        float_obj.rel_tol = rel_tol
        # Return the float that was created.
        return float_obj

    def __eq__(self, other):
        return isclose(self, other, rel_tol=self.rel_tol)

x = TolerantFloat(0.5, rel_tol=0.1)  # 10% error margin.
print(x == 0.51)  # True
print(x == 0.42)  # False
```

Note how the method `__new__` saves the relative tolerance in the object.

If we did that inside `__init__` then our instances of tolerant floats would be (partially) mutable.

(We might want that, or not.)

But this isn't even the full story about `__new__` and `__init__`...

## Dipping my toes in metaprogramming

The dunder method `__new__` is an excellent entry point into the wondeful but crazy world of metaprogramming.

I originally learned about when I was studying the source code for the module pathlib, which uses `__new__` to power a behaviour you might have seen:

When you instantiate `Path`, you get instances of either `PosixPath` or `WindowsPath`, but never `Path` itself...

And the thing you get depends on your operating system.

How does `Path` do that?!

(Spoiler: with `__new__`.)

Today's email, and the follow-up teaser I just shared, are the starting point for my upcoming PyCon Italia 🇮🇹 talk.

Will I see you there?

If you can't attend the talk you might want to [read this article about `__new__` and `__init__`](https://mathspp.com/blog/customising-object-creation-with-__new__).

## Enjoyed reading?

This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter.
Subscribe to the mathspp insider 🐍🚀 to get weekly Python deep dives like this one on your inbox:

[Join mathspp insider 🐍🚀](?classes=btn,btn-lg,btn-center#subscribe)
