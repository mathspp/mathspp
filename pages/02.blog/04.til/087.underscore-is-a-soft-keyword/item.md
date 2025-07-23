Today I learned that the underscore `_` is a soft keyword in Python.

===


## Underscore is a soft keyword

I've written about [the multiple usages of the underscore `_` in Python](/blog/pydonts/usages-of-underscore) and in that article I write about the fact that the underscore is idiomatically used to assign a value we don't care about.
Something like this:

```py
## Suppose we have a colour like `colour = ("red", (255, 0, 0))`
colour_name, _ = colour
```

The code above uses [unpacking](/blog/pydonts/deep-unpacking) to extract the colour name from the variable `colour` and we use the second variable name `_` to idiomatically say “we expect there to be a second value to unpack, but we don't care about its value”.
But this is just a convention.
There is nothing special about the underscore `_` in this situation; it's just a valid variable name:

```py
_ = 3
_ *= 2
print(_)  # 6
```

However, in [Python's 3.10 `match` statement](/blog/pydonts/structural-pattern-matching-tutorial), the underscore `_` was turned into a soft keyword.
This means that when you write a `match` statement containing a `case` statement that looks like `case _:`, Python actually parses the underscore `_` as a keyword!

So, if `_` can be used as a regular variable name and if `_` can be parsed as a keyword, depending on the context, that makes it a soft keyword!

!!! To confirm that `_` can be parsed as a keyword inside a `case` statement, don't take my word for it.
!!! You can [open Python's grammar and see for yourself](https://github.com/python/cpython/blob/12a30bc1aa0586308bf3fe12c915bcc5e54a032f/Grammar/python.gram#L533)!
