---
date: 27-04-2026 17:07
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how lazy imports handle the fact that some exceptions might be deferred."
    og:image: "https://mathspp.com/insider/archive/handling-exceptions-with-lazy-imports/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/handling-exceptions-with-lazy-imports/thumbnail.webp"
title: "Handling exceptions with lazy imports"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Handling exceptions with lazy imports

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Explicit lazy imports

Two weeks ago you [learned about **explicit lazy imports**](https://mathspp.com/insider/archive/explicit-lazy-imports), a feature coming to Python 3.15 this October.

You also learned about [a couple of backwards-compatible ways of using lazy imports](https://mathspp.com/insider/archive/lazy-imports-and-backwards-compatibility).

As a _super brief_ recap, an explicit lazy import starts with the keyword `lazy`:

```python
lazy import json
```

The module `json` isn't loaded immediately.

Instead, you get a special object that _represents_ the module.

As soon as you _touch_ the module to do anything with it, the loading of the module is triggered.

This process is called **reification**.

## Playing with lazy imports

**Tangent**: if you want to follow along the examples in this article, install uv.

Then, to open a 3.15 REPL, just run `uv run --python 3.15 python`.

To run a script with 3.15, run `uv run --python 3.15 path/to/script.py`.

To learn more about uv, [sign up for this 4-part free email course](https://mathspp.com/courses/uv).

## Inspecting lazy modules before reification

Back to the main topic!

**Reification** is an interesting process.

When you run a lazy import, a module is lazy loaded.

As soon as you _touch_ it, the lazy object is reified and the module is loaded.

Ok...

But what _thing_ is a module that has been imported lazily but hasn't been reified yet?

It's not like you can print its type because, as soon as you call `type` on the lazy module, it's reified:

```pycon
>>> lazy import json
>>> print(type(json))
<class 'module'>
```

But there's a workaround, which is to access the lazy object through `globals()`.

Make sure you quit the REPL, open it with `uv run --python 3.15 python`, and then try this:

```pycon
>>> lazy import json
>>> globals()["json"]
<lazy_import 'json'>
```

And you can even check its only method:

```pycon
>>> dir(globals()["json"])
[..., 'resolve']
```

The [method `resolve` is what resolves the lazy import and returns the actual module](https://mathspp.com/blog/til/resolve-a-lazy-import-manually).

## Deferring exceptions

Importing a module lazily also means that you might be deferring some exceptions.

For example, if you open the REPL and try to import the module `jsom` (note it's misspelled), you get an immediate `ModuleNotFoundError`:

```pycon
>>> import jsom
Traceback (most recent call last):
  File "<python-input-2>", line 1, in <module>
    import jsom
ModuleNotFoundError: No module named 'jsom'. Did you mean: 'json'?
```

But if you try to import the module `jsom` lazily, you don't get the error.

The import is lazy, so Python hasn't tried to run any code yet.

So it can't know whether the import will work or not:

```pycon
>>> lazy import jsom  # Note "JSON" is misspelled.
>>> # No exception raised.
```

This can also hide other types of errors.

If you type the name of the module correctly, but the module itself raises an exception, you're also deferring that.

Suppose you define the module `mod.py` with the following silly code:

```py
print("Inside mod.py")
1 / 0
print("This will never be seen.")
```

If you open the REPL and try to import `mod`, you see the exception immediately:

```pycon
>>> import mod
Inside mod.py
Traceback (most recent call last):
  File "<python-input-0>", line 1, in <module>
    import mod
  File "/Users/rodrigogs/Documents/mathspp/mathspp/mod.py", line 2, in <module>
    1 / 0
    ~~^~~
ZeroDivisionError: division by zero
```

However, a lazy import won't trigger the exception:

```pycon
>>> lazy import mod  # No exception raised.
>>> globals()["mod"]
<lazy_import 'mod'>
```

## Seeing lazy import error messages

Because lazy imports potentially defer some exceptions, debugging these exceptions could become quite cumbersome.

To help with that, exceptions raised when a module is reified have two tracebacks.

One traceback shows the exception that the module raised and the other shows where the module was lazily imported.

To see this in action, make sure you create the dummy module `mod.py` from before.

Then, create another file called `other.py`, next to `mod.py`, with the following code:

```py
# other.py
lazy import mod

print("Inside other.py")
print(mod)
```

Now, try to run the file `other.py` and you should see an exception with two tracebacks.

The bottom half shows the exception that was encountered:

```text
Traceback (most recent call last):
  File "/Users/rodrigogs/Documents/mathspp/mathspp/other.py", line 4, in <module>
    print(mod)
          ^^^
  File "/Users/rodrigogs/Documents/mathspp/mathspp/mod.py", line 2, in <module>
    1 / 0
    ~~^~~
ZeroDivisionError: division by zero
```

The problem is the division by zero inside the module `mod.py`.

But if you look closely at the traceback, you'll see it looks a bit weird.

The exception was raised because you ran `1 / 0` but _immediately above_ the code that you ran was `print(mod)`.

The confusing part?

How come printing a variable triggers a division by zero?

This can be confusing if you forget/don't know that `mod` was imported lazily and hasn't been reified yet.

That's why, immediately above, Python shows another traceback:

```text
Traceback (most recent call last):
  File "/Users/rodrigogs/Documents/mathspp/mathspp/other.py", line 1, in <module>
    lazy import mod
ImportError: deferred import of 'mod' raised an exception during resolution

The above exception was the direct cause of the following exception:
```

Python adds more context that explains the weird-looking traceback.

It's because `mod` hadn't been reified yet and printing it triggered the reification process.

## Catch exceptions when using lazy imports

As a sanity check, when using lazy imports, you can run your code with the flag `-X lazy_imports=none`.

This _disables_ all lazy imports and lets you check if you have any exceptions at import time.

However, this will only work if you don't have **lazy circular imports**.

## Questions about lazy imports?

If you have any questions about explicit lazy imports, reply to this email.

It goes straight to my inbox!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
