Learn how to work around the Python machinery to resolve an explicit lazy import manually.

===

A couple of articles ago I wrote about how you could [inspect a lazy import](/blog/til/inspect-a-lazy-import).

Apparently, you can use a similar trick to check the attributes and methods that a lazy import has:

```pycon
>>> lazy import json
>>> dir(globals()["json"])
['__class__', '__delattr__', '__dir__', '__doc__', '__eq__', '__format__', '__ge__', '__getattribute__', '__getstate__', '__gt__', '__hash__', '__init__', '__init_subclass__', '__le__', '__lt__', '__ne__', '__new__', '__reduce__', '__reduce_ex__', '__repr__', '__setattr__', '__sizeof__', '__str__', '__subclasshook__', 'resolve']
```

Apart from a large number of [dunder methods](/blog/pydonts/dunder-methods) and dunder attributes, you'll find the method `resolve`.
You can run `help(globals()["json"].resolve)` to get the help text on that method:

```text
Help on built-in function resolve:

resolve() method of builtins.lazy_import instance
    resolves the lazy import and returns the actual object
```

This shows that it's the method `resolve` that resolves a lazy import.

If you call the method, you can get access to the resolved module:

```pycon
>>> lazy import json
>>> resolved_json = globals()["json"].resolve()
>>> resolved_json
<module 'json' from '/Users/rodrigogs/.local/share/uv/python/cpython-3.15.0a8-macos-aarch64-none/lib/python3.15/json/__init__.py'>
```

After calling `resolve`, the lazy module doesn't disappear automatically:

```pycon
>>> globals()["json"]
<lazy_import 'json'>
```

Which shows that the mechanism that's responsible for reification _most likely_ calls the method `resolve` and then _reassigns_ the name of the module to the module returned by `resolve`.
In a way, it's as if the reification process ran something like

```py
globals()["json"] = globals()["json"].resolve()
```

In hindsight, this isn't too surprising.
After all, Python tends to be very consistent.
The only mistery that remains is _what_ triggers the reification process.
How is it that Python can detect when something _touches_ the lazy import..?
