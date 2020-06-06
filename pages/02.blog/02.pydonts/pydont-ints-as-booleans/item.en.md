---
title: Py-don't use integers as booleans
date: 08-03-2020
published: false
slug: ints-as-booleans
taxonomy:
    tag: [pydont, programming, python]
---

If you want to create an infinite `while` loop, don't do the following:

```py
while 1:
    print("Stop this, please!")
```

Python has built-in boolean values! No need to use integers as boolean constants!

===

The more pythonic code would be

```py
while True:
    print("Stop this, please!")
```

People might think that the first snippet is the way to go because they might come from a C background, where one would have to write

```c
while (1) {
    printf("Stop this, please!");
}
```

or because Python's objects can be implicitly treated as booleans, meaning that values like integers, strings and lists can be used in `if` and `while` statements.

Remember that [_explicit is better than implicit_][zen-of-python] and we are gaining nothing from this implicit conversion, so we could easily avoid it.

This implicit capacity of being able to convert objects to booleans, sometimes referred to as the _truthiness_ of an object, wasn't created to replace the built-ins `True` and `False`. When you have to hardcode a boolean, chances are you should go with `True` and `False`.

Refer to this snippet for some common _Truthy_ and _Falsy_ objects:

```py
# 0 is Falsy, all other ints are Truthy so this prints from -10 to 9, except 0
for i in range(-10, 10):
    if i:
        print(i)

# The empty string is Falsy, all other strings are Truthy
s = ""
if s:
    print(s)
s = "sad ddfa fda fda"
if s:
    print(s)

# The empty list is Falsy, all other lists are Truthy
l = []
if l:
    print(l)
l = [0, ""]
if l:
    print(l)
```

A Pythonic use case of implicitly using an object as a boolean value might be when we want to process a list while the list is non-empty, for example to successively pop elements and using them for something:

```py
l = [67, 2, 7, 4, 8]
while l:
    last = l.pop()
    if last % 2:
        l.append(last - 1)
        l.append(last - 3)
    print(last, end=" ")

# outputs "8 4 7 4 6 2 67 64 66 "
```

Did this make any sense? Let me know in the comment section below!

[zen-of-python]: ../pydont-zen-of-python