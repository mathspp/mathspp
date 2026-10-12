Learn how to use lazy imports to speed up the startup time of your Python applications.

===

Python 3.15 introduced **explicit lazy imports**.
An **explicit lazy import** is an import statement that starts with the keyword `lazy`:

```py
import json
lazy import math
lazy from pathlib import Path
```

The module `json` is imported normally, but `math` and `Path` are imported lazily.

If an import is lazy, the module will only be loaded when it is needed instead of being loaded immediately when Python encounters the import statement.
This article explains how to use lazy imports and how to write backwards-compatible code with these features.

## 15 days of Python 3.15

To [celebrate the release of Python 3.15](/python-315), over the next 15 business days I'll be writing about a new 3.15 feature every day.
Explained clearly and with examples so you don't have to sift through the changelog.

Subscribe to receive this free email series:

{% include "forms/form.html.twig" with {form: forms("enroll")} %}

If you want, you can also [check the schedule](/python-315) of the upcoming emails.

## Why does Python need lazy imports?

The import system in Python loads a module when it encounters the import statement.
That means that when Python finds a statement `import some_module` it looks for the corresponding Python module file and runs it.
In turn, that file might import other modules that will run at that point in time, and this branching can be arbitrarily deep.

Since Python files usually collect all imports at the top, this means that you may spend an arbitrarily long amount of time taking care of your imports even before your start running your own code.
And in some situations, you won't even need your imports.

Imagine a CLI that needs to import some heavy dependencies.
Maybe the dependencies perform network authentication, for example.
And that can take a bit.
But if you only run the command `my_cli --help`, you don't want to wait for the authentication to finish (or fail!) before you get your help message.

By using a lazy import, you can make sure that the dependencies are only loaded _when_ they are needed and _if_ they are needed.

## Lazy modules

When you use an explicit lazy import you get a `lazy_import` object:

```py
lazy import math

print(globals()["math"])  # <lazy_import 'math'>
```

The snippet above prints `math` in a roundabout way to show you the object of type `lazy_import`.
If you _touch_ the module `math` in any direct way, the module is automatically loaded and substituted in:

```py
lazy import math

print(globals()["math"])  # <lazy_import 'math'>

print(math)  # <module 'math' (built-in)>
```

The second call to `print` shows the regular module `math` because the call to print triggered resolution of the lazy import.

## Lazy from

The statement `lazy from pathlib import Path` defers loading the module `pathlib` until `Path` is used.
But when `Path` is used, Python still runs the whole module `pathlib` before making `Path` available.

Regular imports also run the whole module before exporting only the required names, but it's worth highlighting that this didn't change with lazy imports.

## Backwards-compatible lazy imports

If you want to use lazy imports in Python 3.15+ but also make sure your code can run in earlier versions of Python, you can use the variable `__lazy_modules__`.
This variable is ignored in earlier versions of Python but in Python 3.15+, a regular import checks `__lazy_modules__` to see if it should import the module lazily.

Suppose you write the following code:

```py
import math

__lazy_modules__ = ["math", "json"]

from json import dump, load
import pathlib
```

In Python 3.15+, the modules `math` and `pathlib` are loaded regularly but the module `json` will be loaded lazily.
In Python 3.14 or earlier, the variable `__lazy_modules__` is ignored and all three modules are loaded regularly.

You can verify this with a more complete snippet of code:

```py
import math

__lazy_modules__ = ["math", "json"]

from json import dump, load
import pathlib

# ---

import sys

print("math" in sys.modules)
print("json" in sys.modules)
print("pathlib" in sys.modules)
```

The check `"module_name" in sys.modules` checks if the module was already loaded.
In Python 3.15+ the code prints `True`/`False`/`True` but in Python 3.14 or earlier it prints `True` three times.

If you have uv (0.13.0+) installed, copy the snippet of code above and check it with

```bash
% uv run --python 3.14 python -c '<paste snippet here>'
True
True
True
% uv run --python 3.15 python -c '<paste snippet here>'
True
False
True
```

## Handling errors with lazy imports

Deferring the loading of a module might also defer the occurrence of an exception.
As a simple example, suppose you misspelled the name of a module:

```py
lazy import jsom  # M instead of N
```

Since the module will be loaded lazily, you won't see the corresponding exception until the module is used.

Suppose you use the module later:

```py
lazy import jsom

# ---

data = {}  # Some dictionary to write.
with open("myfile.txt", "w") as f:
    jsom.dump(f, data)
```

When Python tries to resolve the import `jsom`, it realises you made a mistake.
Python only noticed it in the line `jsom.dump(...)` but the mistake comes from the line `lazy import jsom`, so Python shows both lines in the traceback:

```py
Traceback (most recent call last):
  File "lazy.py", line 1, in <module>
    lazy import jsom
ImportError: lazy import of 'jsom' raised an exception during resolution

The above exception was the direct cause of the following exception:

Traceback (most recent call last):
  File "lazy.py", line 7, in <module>
    jsom.dump(f, data)
    ^^^^
ModuleNotFoundError: No module named 'jsom'. Did you mean: 'json'?
```

The bottom of the traceback presents the real error, which is that the module `jsom` doesn't exist.
Immediately above, the `ImportError` shows you the lazy import that delayed the exception.

## Other considerations

### Lazy imports

The introduction of lazy imports also enables the definition of circular imports.
However, it's still recommended that you avoid circular imports:

 1. it can be a symptom that your library is poorly designed; and
 2. there are mechanisms that disable lazy imports altogether in Python 3.15+ and that would prevent your code from working in those scenarios.

### Python interpreter switch

The Python interpreter now accepts the switch `-X lazy_imports=...`, which can have one of three values:

| Value | Meaning |
| :- | :- |
| `normal` | (default) Only imports marked with the keyword `lazy` are lazy |
| `all` | All module-level imports are lazy |
| `none` | No imports are lazy, even if they're marked with the keyword `lazy` |

### Profiling slow imports

You can run your application with the switch `-X importtime` to profile the import times of your code.

To see this in action, create a file with a single line of code:

```py
# single_import.py
import json
```

Then, run it with `python -X importtime single_import.py`.
You'll get a long table[^1] that ends with this:

[^1]: The beginning of the table shows stuff that Python imports automatically at startup time.

```
import time: self [us] | cumulative | imported package
...
import time:       420 |        420 |       _json
import time:       239 |        659 |     json.scanner
import time:       307 |        965 |   json.decoder
import time:       249 |        249 |   json.encoder
import time:       184 |       1397 | json
```

For now, focus on the rightmost column:

```
| imported package
|       _json
|     json.scanner
|   json.decoder
|   json.encoder
| json
```

The rightmost column shows package names in reverse order, and the indentation represents dependency.
Reading from bottom to top, the output shows that:

 - You imported `json`
 - `json` imported `json.encoder` and `json.decoder`
 - `json.decoder` imported `json.scanner`
 - `json.scanner` imported `_json`

The two other columns show the time each import took:

```
self [us] | cumulative | imported package
      420 |        420 |       _json
      239 |        659 |     json.scanner
      307 |        965 |   json.decoder
      249 |        249 |   json.encoder
      184 |       1397 | json
```

The second column shows how much time a module took to be imported.
For example, the module `json` took 1397 microseconds to import.
However, the first column shows that the module itself loaded quite quickly, in only 184 microseconds.
All the other time was spent importing the dependencies of `json`.

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}
