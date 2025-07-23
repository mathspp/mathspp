You can ignore exceptions with `contextlib.suppress` and in this article I show why this is preferred over `try: ... except: pass`.

===

!!! This article was first published in my newsletter.
!!! [Subscribe to the newsletter here](/subscribe) for Python tips sent directly to your inbox.

Sometimes you want to run some code that you know might raise an exception and you're not worried about the exception.
You just want to ignore it, if it's the case.

To implement this behaviour, you'd probably think of the `try` statement:

```py
try:
    code_that_might_error()
except SomeException:
    pass
```

The code shown above will handle an exception of the type `SomeException` by ignoring it.

For example, when you’re deleting a key from a dictionary, you can get an error if the key doesn’t exist:

```py
d = {}
del d[3]  # KeyError
```

If you want to delete a key that may be in the dictionary but you’re not sure, you can do one of two things:

 1. you can check if the key is there and only delete it if it’s there; or
 2. you can try to delete the key and handle the exception you get if it’s not there.

(We won’t discuss why option 2. is typically preferred in Python, but it has to do with [the “look before you leap” and “easier to ask for forgiveness than permission” coding styles](/blog/pydonts/eafp-and-lbyl-coding-styles).)

Here’s what option 2 would look like:

```py
try:
    del my_dict[my_key]
except KeyError:
    pass
```

The reason we use the keyword `pass` in case there’s an error is because we literally don’t need to do anything.
We were trying to delete a key and the key wasn’t even there to begin with, so all is fine.

This is quite decent.
But there’s a better option:


## `contextlib.suppress`

The module `contextlib`, from the standard library, provides many tools to work with context managers.
(A context manager is something you use with the keyword `with`, like `with open(some_file) as f:`.)

One tool from `contextlib` is `suppress`, which you can use as a context manager and that ignores the errors passed in as arguments.
So, the code from before would become

```py
from contextlib import suppress

with suppress(KeyError):
    del my_dict[my_key]
```

This is better than using the keyword `try` because of readability.
Let me explain.

When you’re reading code and you see the keyword `try` you immediately think “ok, I’m about to read some code that might error and I need to handle that error”.

And then you don’t know how you’ll handle the error until you reach the statement `except`.
And you might also have statements `else` and `finally`.
And you don't know if you will have these or not just by looking at the `try`.

So, just by seeing the `try`, it’s not clear what will happen and you have to read the whole code to figure out what the point of the exception handling is.
So you become anxious, you start pulling your hair, and you become bald at a very young age.
And you get all of this stress just to read the rest of the code and conclude “oh ok, we just want to ignore the error”.

If you use `contextlib.suppress`, you know **immediately** what you’ll be doing with the error.

You’ll be suppressing it.
Ignoring it.
This makes it easier to read the code.
Period.

That’s why you’ll want to use `contextlib.suppress` instead of `try: … except: pass`.


## A generic example use case

An example generic use case, that I someone shared on [BlueSky](https://bsky.app/profile/mathspp.com), is for apps or scripts that may be used by non-developers.
If you want to provide a clean exit when the user cancels your program with <kbd>Ctrl</kbd>+<kbd>C</kbd>, you can wrap your main function in `suppress(KeyboardInterrupt)`.
This will prevent Python from displaying a nasty traceback in case your user interrupts your code.

You'd use it like this, for example:

```py
# yourcode.py

from contextlib import suppress

...

def main():
    ...

if __name__ == "__main__":
    with suppress(KeyboardInterrupt):
        main()
```
