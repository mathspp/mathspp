Today I learned that you can dynamically change the type of an object in Python.

===


# Dynamically changing the type

An object's type can be checked by using the built-in `type`:

```py
print(type(3))  # <class 'int'>
```

You can also access that information directly by checking the dunder attribute `__class__`:

```py
print((3).__class__)  # <class 'int'>
```

Apparently, for your own custom types, you can _assign_ to the dunder attribute `__class__` and dynamically change the type of an instance.

For example, the variable `x` below holds an instance of the class `A` that doesn't have any methods:

```py
class A:
    def __init__(self, value):
        self.value = value

x = A(42)
```

Now, you can define the class `B` that defines a method, you can assign `x.__class__` to it, and you can call that method:

```py
class B:
    def mult(self, x):
        return self.value * x

x.__class__ = B
print(type(x))  # <class '__main__.B'> !?
print(x.mult(10))  # 420
```

I learned this in a cool lightning talk given at EuroPython 2025!
