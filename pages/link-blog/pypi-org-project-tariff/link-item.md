---
# author:
date: 15-04-2025 15:47
link: https://pypi.org/project/tariff/
taxonomy:
    category: link
title: "tariff · PyPI"
via: https://bsky.app/profile/stephenturner.us/post/3lmubty5ddu2g
---

The parody Python package `tariff` can be used to impose import tariffs on other modules!
The example from the package README:

```py
import tariff

# Set your tariff rates (package_name: percentage)
tariff.set({
    "numpy": 50,     # 50% tariff on numpy
    "pandas": 200,   # 200% tariff on pandas
    "requests": 150  # 150% tariff on requests
})

# Now when you import these packages, they'll be TARIFFED!
import numpy   # This will be 50% slower
import pandas  # This will be 200% slower
```

I don't know exactly how this is done, but I would guess it's enough to:

 - use `tariff.set` to set some sort of hook or “watcher” on the import system; and then
 - when doing the actual import, time it and then just sleep for the time set by the import tariff.
