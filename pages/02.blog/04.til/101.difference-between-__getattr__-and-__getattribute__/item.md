Today I learned the difference between `__getattr__` and `__getattribute__`.

===


## `__getattr__` vs `__getattribute__`

Today I attended a tutorial at EuroPython 2024 where the speaker showed a key difference between the [dunder methods](/blog/pydonts/dunder-methods) `__getattr__` and `__getattribute__`:

```py
class Missing:
    attr = 42

    def __getattr__(self, name):
        print(f"In __getattr__, asked for {name}")
        return 73

m = Missing()
print(m.attr)  # 42
print(m.xyz)  # In __getattr__, asked for xyz; 73
```

The code above shows that the dunder method `__getattr__` was called for the lookup `m.xyz`.

```py
class Always:
    attr = 42

    def __getattribute__(self, name):
        print(f"In __getattribute__, asked for {name}")
        return 73

a = Always()
print(a.attr)  # In __getattribute__, asked for attr; 73
print(a.xyz)  # In __getattribute__, asked for xyz; 73
```

The snippet with the class `Always` shows that the dunder method `__getattribute__` was called for both lookups: `a.attr` and `a.xyz`.

I posted about this on [X (Twitter)](https://x.com/mathsppblog/status/1810592342451118084) and [Fosstodon](https://fosstodon.org/@mathsppblog/112755595275154340) and someone briefly explained that `__getattribute__` is the dunder method that governs all attribute lookups, whereas `__getattr__` is what's called when `__getattribute__` fails and it's `__getattr__` that you typically want to implement.
When playing with these two dunder methods, it is easy to create infinite recursion loops if you implement `__getattribute__` poorly.

The same person who commented on my post also pointed to [the blog article “Unravelling attribute access in Python”](https://snarky.ca/unravelling-attribute-access-in-python/) (also in the references) that goes into _much_ more detail on how this actually works.
It's a good article.
Take a look if you're interested in understanding how this works.
