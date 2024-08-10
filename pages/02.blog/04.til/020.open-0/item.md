Today I learned you can read from stdin with `open(0)`.

===

![The code `open(0)  # ?!` in a good-looking thumbnail.](thumbnail.webp)


# Opening a file

In Python, we typically use the function `open` to open a file and read from it.
In particular, the construct

```py
with open(filepath, mode) as f:
    ...
```

is _very_ common in Python.

Well, today I learned that the function `open` can accept the integer `0` to read from standard input.

That's because the function `open` accepts file descriptors as its argument,
and [`0` is the file descriptor for standard input][wiki-file-descriptor].

`1` is the file descriptor for standard output,
and `2` is the file descriptor for standard error,
so you can also write to these two streams by using the built-in `open`:

```py
>>> stdout.write("Hello, world!\n")
Hello, world!
14
>>> stdout.close()
```

Knowing that you can read from stdin with `open(0)`, you can type in multiline input in the REPL with ease:

```py
>>> msg = open(0).read()
Hello,
world!
^Z
>>> msg
'Hello,\nworld!\n'
```

To stop reading, you need to go to an empty new line and press some magic key(s).
(On Windows, it's <kbd>Ctrl</kbd>+<kbd>Z</kbd>.
On Linux/Mac OS it _may_ be <kbd>Ctrl</kbd>+<kbd>D</kbd>, not sure.)

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[wiki-file-descriptor]: https://en.wikipedia.org/wiki/File_descriptor
