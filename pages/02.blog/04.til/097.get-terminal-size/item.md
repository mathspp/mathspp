Today I learned how to get the size of the terminal your code is running on.

===

## Get terminal size

The module `shutil` has a method `.get_terminal_size` that you can use to get the terminal size you're running on.
For example, I stretched out my terminal, made it very short, and ran this code:

```pycon
>>> import shutil
>>> shutil.get_terminal_size()
os.terminal_size(columns=160, lines=9)
```

The output that we see is a named tuple, so we can use it in multiple ways:

```pycon
>>> size = shutil.get_terminal_size()
>>> size.columns
160
>>> width, height = size
>>> width
160
>>> height
9
```

This method accepts a fallback argument that is returned if Python fails to detect the terminal size (or if there is no terminal associated with the Python interpreter that is running).
This fallback value defaults to `(80, 24)`.

The module `os` also has a similar method (`os.get_terminal_size`) but the documentation says the `shutil` method is the one you should use and `os.get_terminal_size` is a lower-level method you won't typically need to use.
