---
# author:
date: 21-04-2025 19:56
link: https://github.com/python/cpython/blob/4f7f72ce34f7825e50346ed0c878fc36ef9421ca/Lib/typing.py#L3755
taxonomy:
    category: link
title: "cpython/Lib/typing.py module-level __getattr__"
via: https://x.com/raymondh/status/1914094806772391987
---

Raymond Hettinger talked about module-level `__getattr__` and `__dir__` on Twitter/X and someone commented with a link to the module `typing` that defines a module-level `__getattr__` as an excellent example of the usage of that dunder method to implement lazy loading of some names.

In the case of the module `typing`, the dunder method `__getattr__` is used to lazily import some deprecated objects that the module `typing` exposes and that are expensive to create.
Since they're rarely needed, deferring their creation to a module-level `__getattr__` makes importing the module `typing` much faster.

What I find particularly instructive about this example is that it shows how the module-level `__getattr__` should behave:

 - it accepts a string with the name of the object being accessed;
 - it should raise an exception `AttributeError` if that name isn't part of the module;
 - it should put the object in question in the global dictionary of the module when created with `globals()[attr] = obj`; and
 - it should return the object that is being accessed.

```py
def __getattr__(attr):
    """Improve the import time of the typing module.

    Soft-deprecated objects which are costly to create
    are only created on-demand here.
    """
    if attr == "ForwardRef":
        obj = _lazy_annotationlib.ForwardRef
    elif attr in {"Pattern", "Match"}:
        import re
        obj = _alias(getattr(re, attr), 1)
    elif attr in {"ContextManager", "AsyncContextManager"}:
        import contextlib
        obj = _alias(getattr(contextlib, f"Abstract{attr}"), 2, name=attr, defaults=(bool | None,))
    elif attr == "_collect_parameters":
        import warnings

        depr_message = (
            "The private _collect_parameters function is deprecated and will be"
            " removed in a future version of Python. Any use of private functions"
            " is discouraged and may break in the future."
        )
        warnings.warn(depr_message, category=DeprecationWarning, stacklevel=2)
        obj = _collect_type_parameters
    else:
        raise AttributeError(f"module {__name__!r} has no attribute {attr!r}")
    globals()[attr] = obj
    return obj
```
