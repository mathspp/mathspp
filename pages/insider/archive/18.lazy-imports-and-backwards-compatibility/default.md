---
date: 20-04-2026 18:05
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn the mechanisms that you can use to use lazy imports in a backwards-compatible manner."
    og:image: "https://mathspp.com/insider/archive/lazy-imports-and-backwards-compatibility/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/lazy-imports-and-backwards-compatibility/thumbnail.webp"
title: "Lazy imports and backwards compatibility"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Lazy imports and backwards compatibility

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Explicit lazy imports

[Last week](https://mathspp.com/insider/archive/explicit-lazy-imports) you learned about **explicit lazy imports**, a feature coming to Python 3.15 this October.

An explicit lazy import starts with the keyword `lazy`:

```python
lazy import json
```

The module `json` isn't loaded immediately.

Instead, you get a special object that represents the module.

As soon as you _touch_ the module to do anything with it, the loading of the module is triggered.

## The new keyword `lazy`

The new keyword `lazy` will be a soft keyword: Python's fifth.

Other soft keywords include `match` and `case`, which means they can still be used as variable names.

But in the correct place, they behave like keywords.

It's the same thing with the keyword `lazy`.

This means that if your code uses the variable `lazy`, it won't break when you switch to Python 3.15.

Again, that's because `lazy` will be a soft keyword.

Keywords like `if` or `for`, that are not soft, can _never_ be used as variable names.

## The backwards compability problem

If you're developing a library, you won't be able to use the `lazy import` statement right away.

That's because you need to support multiple versions of Python.

And the statement `lazy import` doesn't run in Python 3.14 or earlier.

So, if you wanted to use `lazy import` as a library maintainer, you'd need to drop support for Python 3.14, 3.13, etc.

Or you'd need to use `if` statements to check the Python version.

Something like this:

```py
import sys

if sys.version_info >= (3, 15):
    lazy import json
else:
    import json
```

This works.

But it's a bit cumbersome.

Thankfully for you, there are two fully backwards-compatible ways of using lazy imports.

## The list `__lazy_modules__`

You can declare lazy imports by defining a list with all the modules that should be imported lazily.

Like so:

```py
__lazy_modules__ = ["json"]

import json
```

In Python 3.15 and later, the list `__lazy_modules__` is used to check if an import should be lazy.

_Without needing the keyword lazy_.

In Python 3.14 and earlier, the list `__lazy_modules__` is just ignored and all imports are eager.

This means you can write code that targets Python 3.15 and earlier, using lazy imports for Python 3.15 and eager imports for other versions.

## Command-line option

There is another mechanism you can use to your benefit.

The command line option `-X lazy_imports=<mode>`, where `<mode>` is one of:

 - **normal** (or unset): only imports that have the keyword `lazy` are lazy
 - **all**: all module-level imports (except the ones inside `try` blocks or with `import *`) become lazy
 - **none**: no imports are lazy, even those explicitly marked with the keyword `lazy`

How do you use this?

Suppose you have a file called `myscript.py`.

You can run it, while setting all imports to be lazy, with:

```bash
$ python -X lazy_imports=all myscript.py
```

Using the command-line option has the added benefit of allowing you to use lazy imports in code that was written for earlier versions of Python.

If the command `python` is for Python 3.14 or earlier, the option `lazy_imports` is ignored.

## Questions about lazy imports?

If you have any questions about explicit lazy imports, reply to this email.

It goes straight to my inbox!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
