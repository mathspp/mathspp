Today I learned about the Python 3.12 type statement you can use to create type aliases.

===


# `type` statement and type aliases

Python 3.12 introduced the soft keyword `type`, which is used in the `type` statement to create type aliases.
Thus, the `type` statement offers a more convenient way of creating type aliases that doesn't require importing `TypeAlias` from the module `typing`.

In its simplest form, the `type` statement is composed of the `type` keyword, the name of the type alias you are creating, and the type you are aliasing.
The example below shows how to create a type alias called `Point` that is the same as a pair with two floats:

```py
type Point = tuple[float, float]
```

Before the introduction of the `type` statement, you could create a type alias via a regular assignment:

```py
Point = tuple[float, float]
```

You could also annotate `Point` with `typing.TypeAlias` to make it clear that you were creating a type alias:

```py
from typing import TypeAlias

Point: TypeAlias = tuple[float, float]
```

So, why do we care about the `type` statement?


## Forward references

One of the advantages of the `type` statement is that it supports forward referencing without having to quote the names of the types you are refering to.
This is possible because the type value is lazily evaluated.

For example, suppose we want to create a recursive type for a linked list, where a linked list is a tuple with two elements: an integer and a linked list (the remainder of the linked list).
In 3.12, you could write it as such:

```py
type LinkedList = tuple[int, LinkedList]
```

The self-reference works just fine, and so does the forward reference of the example below:

```py
type A = tuple[B, C, D]
type B = int
type C = str
type D = list[str]
```

In Python 3.11 and earlier, you'd have to quote the forward references or the self-reference of the first example, like so:

```py
from typing import TypeAlias

LinkedList: TypeAlias = tuple[int, "LinkedList"]

A: TypeAlias = tuple["A", "B", "C"]
B: TypeAlias = int
C: TypeAlias = str
D: TypeAlias = list[str]
```


## Generic type aliases

Type aliases can also be made generic.
For example, the linked list could be a type list of any kind of value we want, not just integers.

We could type the linked list like so:

```py
type LinkedList[T] = T | tuple[T, LinkedList[T]]
```

This means that a linked list is either a value of its type `T` or it is a pair with a value and a linked list.

For example, the variable `ll` below defines a linked list of integers:

```py
ll: LinkedList[int] = (42, (73, (10, (16, 0))))
```

This is just the tip of the iceberg.
Generics were also improved in Python 3.12, so there's even more you can do.
You can take a look at the [references](#references) below to learn more about this.
