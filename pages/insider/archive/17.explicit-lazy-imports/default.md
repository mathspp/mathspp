---
date: 13-04-2026 19:34
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn about the new explicit lazy imports coming to Python 3.15."
    og:image: "https://mathspp.com/insider/archive/explicit-lazy-imports/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/explicit-lazy-imports/thumbnail.webp"
title: "Explicit lazy imports"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Explicit lazy imports

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Explicit lazy imports

Python will get explicit **lazy imports**, coming to you this Fall.

Explicit lazy imports will be introduced to Python 3.15 after [PEP 810](https://peps.python.org/pep-0810/) was accepted.

A lazy import starts with the keyword `lazy`:

```python
lazy import json
```

By marking an import as _lazy_, the import statement doesn't trigger the full import immediately.

## Regular imports can trigger side-effects

As you may know, importing a module means _running_ the module in that code.

Importing a module means running _all_ the code in that module.

For example, create a file `mymodule.py` and put this code inside it:

```py
# mymodule.py
print("Inside mymodule.py")

def f():
    return 42
```

Now run the Python REPL in the directory of the file `mymodule.py`:

```py
>>> import mymodule
Inside mymodule.py
>>> mymodule.f()
42
```

As you can see, the just act of importing `mymodule` triggers the call to the function `print`.

## “from” imports run all the code

Even if you use an import like `from mymodule import f`, you execute all the code in the module:

```py
>>> from mymodule import f
Inside mymodule.py
>>> f()
42
```

Think about it...

If you don't run the full module, Python won't know for sure what objects are available.

## Lazy imports stop this

If you have uv installed, you can try using lazy imports already.

Run this command to start a Python 3.15 REPL with a pre-release version:

```bash
$ uv run --python 3.15.0a8 python
```

This will start Python 3.15.0a8, the alpha 8 of Python 3.15.

Inside it, you can lazily import `mymodule`:

```py
>>> lazy import mymodule
>>>
```

When you use a lazy import, side-effects aren't triggered.

That's because the code of the module didn't run yet.

As soon as you access something from the module, the module executes.

For example, if you try to call the function `mymodule.f`, you will see the printed message:

```py
>>> lazy import mymodule
>>> mymodule.f()
Inside mymodule.py
42
```

## Lazy “from” imports

You can also add the keyword `lazy` to imports of the form `from ... import ...`.

Here's an example with `mymodule`:

```py
>>> lazy from mymodule import f
>>> f()
Inside mymodule.py
42
```

## Lazy imports are a start-up performance optimisation

The rationale behind lazy imports is that they speed up the start-up time of your applications.

Typically, imports are defined at the top of your application.

That means you're running everyone else's code before your own.

And sometimes you won't even need everyone else's code.

Imagine you have a CLI that has a couple of slow imports.

But the user just ran the CLI with `--help` because they only want to see the help message.

The slow imports don't care.

They'll be slow.

And you can only show the help text _after_ those slow imports.

Even though the help text didn't depend on the slow imports.

The fix is to make those imports lazy.

Then, you only need to pay the cost of the import _if_ and _when_ you need those imports.

## This is just the tip of the iceberg

There's a lot to be said about explicit lazy imports.

You're just scratching the surface...

Next week you'll learn more about them!

So, if you have any questions about explicit lazy imports let me know and I'll answer them next week!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
