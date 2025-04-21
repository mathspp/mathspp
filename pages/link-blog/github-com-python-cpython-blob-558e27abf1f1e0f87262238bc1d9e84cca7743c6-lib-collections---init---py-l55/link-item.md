---
# author:
date: 21-04-2025 20:06
link: https://github.com/python/cpython/blob/558e27abf1f1e0f87262238bc1d9e84cca7743c6/Lib/collections/__init__.py#L55
taxonomy:
    category: link
title: "cpython/Lib/collections/__init__.py module-level __getattr__"
via: https://x.com/raymondh/status/1914094806772391987
---

Raymond Hettinger talked about the module-level dunder method `__getattr__` and then linked to an old version of the module-level attribute of the module `collections`.

This module-level `__getattr__` is used to issue deprecation warnings when certain aliases from the module `collections.abc` are accessed.
It wouldn't make sense to issue the deprecation warnings as soon as the module is imported, or when a different object is imported from the module, so the module-level `__getattr__` is put in place for that effect:

```py
def __getattr__(name):
    # For backwards compatibility, continue to make the collections ABCs
    # through Python 3.6 available through the collections module.
    # Note, no new collections ABCs were added in Python 3.7
    if name in _collections_abc.__all__:
        obj = getattr(_collections_abc, name)
        import warnings
        warnings.warn("Using or importing the ABCs from 'collections' instead "
                      "of from 'collections.abc' is deprecated since Python 3.3, "
                      "and in 3.10 it will stop working",
                      DeprecationWarning, stacklevel=2)
        globals()[name] = obj
        return obj
    raise AttributeError(f'module {__name__!r} has no attribute {name!r}')
```
