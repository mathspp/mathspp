---
date: 18-06-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "In this short article I explain the relationship between list comprehensions and functional programming."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: list-comprehensions-in-functional-programming
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "List comprehensions in functional programming"
---

In this short article I explain the relationship between list comprehensions and functional programming.

===

Did you know that [list comprehensions][list-comps-101] are actually a great tool for functional programming?

The first thing that you need to understand is what the core of functional programming is.
And while a bad CS class might make you think functional programming is all about `map` and `filter` and things like that...
That's not what FP is about!
FP is about purity...

Purity of what?
Purity of results and behaviour.
In functional programming you are not supposed to produce side-effects.

Proper discussion of this would make for a huge article, so let's stick with this:

 > In functional programming, we (typically) don't want side-effects.

List comprehensions provide just that:
List comprehensions provide a way to transform iterables into new lists without having a single side-effect!

This is not true for the equivalent `for`-loop.
Check the two examples below.
The `for` loop produces a variable as a side-effect:

```py
squares = []
for num in range(10):
    squares.append(num ** 2)

# The loop is over and `num` still exists:
print(num)
```

But the list comprehension doesn't:

```py
squares = [num ** 2 for num in range(10)]

# The list comp is over and `num` does NOT exist:
print(num)  # NameError: 'num' is not defined.
```

So, list comprehensions produce no side-effects by themselves...
And that's also why you are typically advised against doing work in list comprehensions that produces side-effects.

For example, many people have sent me list comprehension solutions that look something like this:

```py
squares = []
[squares.append(num ** 2) for num in range(10)]
```

What's the issue here?
The issue is that the list comprehension is producing side-effects:
As a side-effect to the list comprehension running, the list called `squares` is growing!

That's a big no no!

On top of that, you are defeating the purpose of list comprehensions...
Because you used a list comprehension to build a list that you just ignored!
So, for now, remember: **list comprehensions should produce no side-effects**.

And let us end with a quiz:
What's the output of the code below?
That should help clarify why it doesn't make much sense to use `.append` inside a list comprehension.

```py
squares = []
list_comp = [squares.append(num ** 2) for num in range(10)]
print(list_comp)
```


[list-comps-101]: /blog/pydonts/list-comprehensions-101


!!!! This article was generated automatically from [this thread]([https://twitter.com/mathsppblog/status/1535375291291471875](https://twitter.com/mathsppblog/status/1538206477726404609)) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
