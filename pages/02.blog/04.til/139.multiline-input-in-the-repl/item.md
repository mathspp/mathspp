Today I learned how to do multiline input in the REPL using an uncommon combination of arguments for the built-in `open`.

===

A while ago [I learned I could use `open(0)` to open standard input](/blog/til/020).
This unlocks a neat trick that allows you to do multiline input in the REPL:

```pycon
>>> msg = open(0).read()
Hello,
world!
^D
>>> msg
'Hello,\nworld!\n'
```

The cryptic `^D` is <kbd>Ctrl</kbd>+<kbd>D</kbd>, which means EOF on Unix systems.
If you're on Windows, use <kbd>Ctrl</kbd>+<kbd>Z</kbd>.

The problem is that if you try to use `open(0).read()` again to read more multiline input, you get an exception:

```py
OSError: [Errno 9] Bad file descriptor
```

That's because, when you finished reading the first time around, Python closed the file descriptor `0`, so you can no longer use it.

The fix is to set `closefd=False` when you use the built-in `open`.
With the parameter `closefd` set to `False`, the underlying file descriptor isn't closed and you can reuse it:

```pycon
>>> msg1 = open(0, closefd=False).read()
Hello,
world!
^D
>>> msg1
'Hello,\nworld!\n'

>>> msg2 = open(0, closefd=False).read()
Goodbye,
world!
^D
>>> msg2
'Goodbye,\nworld!\n'
```

By using `open(0, closefd=False)`, you can read multiline input in the REPL _repeatedly_.
