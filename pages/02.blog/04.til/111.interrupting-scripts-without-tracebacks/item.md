Today I learned that if you suppress the exception `KeyboardInterrupt` then your program is still interrupted but it doesn't display a traceback.

===


## Interrupting scripts without tracebacks

The module `contextlib` provides a context manager called `suppress` that you can use to suppress an exception of the given type for the code within its body.
Writing

```py
from contextlib import suppress

with suppress(SomeException):
    ...
```

is more or less the same as writing

```py
try:
    ...
except SomeException:
    pass
```

If an exception is raised inside `SomeException`, the body of the `with` statement stops executing and the code continues after the context manager.

Thus, if we use `suppress` in conjunction with a function “main” from a script, we can provide a way for the user to interrupt the script with <kbd>Ctrl</kbd> + <kbd>C</kbd> and _without_ polluting the terminal with a traceback.

Here is an example of a silly script:

```py
import time

def main():
    print("Doing work...")
    time.sleep(60)  # Not really, just sleeping!
    print("Done!")

if __name__ == "__main__":
    main()
```

If you run this and hit <kbd>Ctrl</kbd> + <kbd>C</kbd>, your terminal will display this:

```
^CTraceback (most recent call last):
  File "<string>", line 9, in <module>
    main()
    ~~~~^^
  File "<string>", line 5, in main
    time.sleep(60)  # Not really, just sleeping!
    ~~~~~~~~~~^^^^
KeyboardInterrupt
```

If you don't want the traceback, for example if you're sharing your script with non-technical folks, you can use `suppress` around the call to the function `main`:

```py
from contextlib import suppress
import time

def main():
    print("Doing work...")
    time.sleep(60)  # Not really, just sleeping!
    print("Done!")

if __name__ == "__main__":
    with suppress(KeyboardInterrupt):
        main()
```

If you interrupt your modified script, you only get this:

```
^C%
```

This is much cleaner!
