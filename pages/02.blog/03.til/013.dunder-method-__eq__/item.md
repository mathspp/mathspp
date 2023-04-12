Today I learned how the rich comparison protocol and, in particular, how __eq__ works behind the scenes.

===

<!--<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>-->

![](thumbnail.webp "Photo by Михаил Секацкий on Unsplash")


# Rich comparison

## Prelude

The rich comparisons are just the comparison operators we are used to:
`<`, `<=`, `==`, `>=`, `>`, and `!=`.

Much like other pieces of Python syntax, using these operators actually calls
dunder methods behind the scenes.
In this short article I'll write about `==` and its corresponding `__eq__` dunder method.

The `__eq__` dunder method is responsible for telling Python objects if they are,
or are not, equal to each other:

```py
>>> class Person:
...     def __init__(self, name):
...         self.name = name
...     def __eq__(self, other):
...         return self.name == other.name
...
>>> Person("Rodrigo") == Person("Jack")
False
>>> Person("Rodrigo") == Person("Rodrigo")
True
```

In the example above, we make it so that any two `Person` instances with the same name are “equal”.

What's interesting is that `object` implements its version of `__eq__`,
and it's more or less equivalent to the following:

```py
class object:
    def __eq__(self, other):
        return True if self is other else NotImplemented
```

This means that, by default, objects you create will only be “equal” to each other when they are
_the same object_:

```py
>>> class Obj:
...     pass
...
>>> o1 = Obj(); o2 = Obj()
>>> o1 == o1
True
>>> o1 == o2
False
>>> o1 is o1
True
>>> o1 is o2
False
```

But why does `object.__eq__` return `NotImplemented` instead of `False` when the objects are not the same?

That has to do with how the rich comparison protocol actually works.

In general, the code `a == b` calls `a.__eq__(b)`.
But if `a.__eq__(b)` returns `NotImplemented`, then `b.__eq__(a)` is called.
That's why `object.__eq__` returns `NotImplemented`: to give a chance to the `other`
object to do its own comparison.

This makes sense:
for example, you might create your own objects that you'd like to be able to compare to the built-in types.
Now, obviously the built-in types won't be able to compare themselves to your custom objects,
but your objects can implement `__eq__` and that will suffice!


## Comparisons return Booleans and methods return `NotImplemented`

All of the above makes a lot of sense.
At least, it made to me.
What threw me off was this excerpt from the REPL:

```py
>>> 3 == "3"
False
>>> (3).__eq__("3")
NotImplemented
>>> "3".__eq__(3)
NotImplemented
```

As we can see, `3 == "3"` obviously returns `False`, as we all expect.
_However_, explicitly calling the `__eq__` method of `int` with the string
returns `NotImplemented`, and similar when we call `str`'s `__eq__` method!

In English, it looks like this is what's happening:

 - `int` says “I don't know how to compare myself to strings”;
 - at which point, Python says “fine, let's try to compare strings to ints instead”; and then
 - `str` says “I don't know how to compare myself to ints”.

Even after all this, we can clearly see that `3` and `"3"` were successfully compared
(and the final result is `False`).

After [reading the docs on rich comparisons][docs] a couple of times
(ok, a couple dozen times), and after taking a look at Brett Cannon's
[blog post on rich comparisons][brett],
I eventually ended up staring at [the C code that solves my conundrum][C-code-solution]:


## `==` is special-cased

`==` and `!=` never fail.
What this means is that they _always_ return a Boolean value!

So, in a way, the C code special-cases this!

If a `==` or `!=` comparison ends up at a `NotImplemented`,
then the C code itself will return an appropriate Boolean value.

It's [these lines of code][C-code-eq-special-case] that matter:

```C
    /* If neither object implements it, provide a sensible default
       for == and !=, but raise an exception for ordering. */
    switch (op) {
    case Py_EQ:
        res = (v == w) ? Py_True : Py_False;
        break;
```

If the comparison operator (`op`) is the equality,
we return `True` or `False` according to whether the C objects
representing the two operands are the same or not.

Immediately above this special case, we can see the C code
that handles the rich comparison protocol, which is responsible for,
for example, checking `b.__eq__(a)` when `a.__eq__(b)` fails for `a == b`.



That's it for now! [Stay tuned][subscribe] and I'll see you around!


[subscribe]: /subscribe
[docs]: https://docs.python.org/3/reference/datamodel.html#object.__lt__
[brett]: https://snarky.ca/unravelling-rich-comparison-operators/
[C-code-solution]: https://github.com/python/cpython/blob/v3.10.0/Objects/object.c#L708
[C-code-eq-special-case]: https://github.com/python/cpython/blob/v3.10.0/Objects/object.c#L704-L709
