Today I learned you can read from stdin with `open(0)`.

===

![The code `open(0)  # ?!` in a good-looking thumbnail.](thumbnail.png)


# Opening a file

In Python, we typically use the function `open` to open a file and read from it.
In particular, the construct

```py
with open(filepath, mode) as f:
    ...
```

is _very_ common in Python.

Well, today I learned that the function `open` can accept the integer `0` to read from standard input.

Knowing this, you can type in multiline input in the REPL with ease:

```py
>>> open(0)
```

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[pydont-negative-indexing]: /blog/pydonts/sequence-indexing#negative-indices
[docs-bitwise-invert]: https://docs.python.org/3/reference/expressions.html#unary-arithmetic-and-bitwise-operations
