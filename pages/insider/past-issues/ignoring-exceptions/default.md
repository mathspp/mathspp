# 🐍🚀 ignoring exceptions

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider#subscribe) to get weekly Python deep dives like this one on your inbox!

## Ignoring exceptions with `try: ... except: ...`

Sometimes you want to run some code that might fail, and you're ok with that.

When that is the case, the first thing you think of is probably the `try` statement:

```python
try:
    code_that_might_error()
except SomeException:
    pass
```

The code shown above will handle an exception of the type `SomeException` by... ignoring it.

For example, when you're deleting a key from a dictionary, you can get an error if the key doesn't exist:

```python
d = {}
del d[3]  # KeyError
```

If you want to delete a key that may be in the dictionary but you're not sure, you can do one of two things:

 - you can check if the key is there and only delete it if it's there; or
 - you can try to delete the key and handle the exception you get if it's not there.

(We won't discuss why option 2. is typically preferred in Python, but it has to do with the [“look before you leap” and “easier to ask for forgiveness than permission” coding styles](/blog/pydonts/eafp-and-lbyl-coding-styles).)

Here's what option 2 would look like:

```python
try:
    del my_dict[my_key]
except KeyError:
    pass
```

The reason we use `pass` in case there's an error is because we literally don't need to do anything.

We were trying to delete a key and the key wasn't even there to begin with, so all is fine.

This is quite decent.

But there's a better option:

## `contextlib.suppress`

The module `contextlib`, from the standard library, provides many tools to work with context managers.

(A context manager is something you use with the keyword `with`, like `with open(some_file) as f:`.)

One tool from `contextlib` is `suppress`, which you can use as a context manager and that ignores the given errors.

So, the code from before would become

```python
from contextlib import suppress

with suppress(KeyError):
    del my_dict[my_key]
```

This is better than using `try` because of readability.

Think with me.

When you're reading code and you see a `try` you immediately think “ok, I'm about to read some code that might error and I need to handle that error”.

And then you don't know how you'll handle the error until you reach the statement `except`.

And you might also have `else` and `finally`.

So, just by seeing the `try`, it's not clear what will happen and you have to read the whole code to figure out what the point of the `try` is, even if it's just to conclude “oh ok, we just want to ignore the error”.

If you use `contextlib.suppress`, you know IMMEDIATELY what you'll be doing with the error.

You'll be suppressing it.

Ignoring it.

This makes it easier to read the code.

Period.

That's why you'll want to use `contextlib.suppress` instead of `try: ... except: pass`.


## Enjoyed reading?

This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter.
Subscribe to the mathspp insider 🐍🚀 to get weekly Python deep dives like this one on your inbox:

[Join mathspp insider 🐍🚀](?classes=btn,btn-lg,btn-center#subscribe)
