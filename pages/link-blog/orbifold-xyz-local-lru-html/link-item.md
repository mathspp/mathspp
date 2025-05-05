---
author: Moshe Zadka
date: 05-05-2025 14:43
link: https://orbifold.xyz/local-lru.html
taxonomy:
    category: link
title: "Orbifolds and Other Games - A Local LRU Cache"
# via:
---

In reply to [one of the Python tips I sent to my Python drops 🐍💧](/drops) newsletter, a reader sent me this article explaining how `functools.lru_cache` might create issues in a threaded program.
After all, adding a cache around a function introduces global state that's shared across threads and that isn't properly protected against that.

The article explores an example and explains the many ways in which things can go wrong, and then suggest a better way to use the cache that makes it much safer and that is a technique that I appreciated.

Instead of adding the cache as a decorator where the function is defined, the cache can be added on the call site by using the decorator explicitly as a callable:

```py
cached_func = lru_cache(maxsize=1024)(my_function)
```

By doing this, it is clear that the cache will live for as long as the variable `cached_func` lives, and that the scope will be limited to the context in which `cached_func` is defined, which will be narrower than the module-level scope of the original function.

For a more detailed exploration of the problems that might be introduced by a global cache, read the original article!
