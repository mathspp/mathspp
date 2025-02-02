---
author: Daniel Roy Greenfeld
date: 02-02-2025 13:47
link: https://daniel.feldroy.com/posts/til-2025-01-every-jupyter-notebook-cell-runs-in-an-async-loop
taxonomy:
    category: link
title: "TIL: Every Jupyter notebook cell runs in an async loop"
# via:
---

In this TIL article Daniel shares how he learned that if you're writing asynchronous code, you don't need to run an async loop inside the notebook.
In fact, you can just paste the snippet below into a code cell and it will run directly:

```py
import asyncio

async def f():
    print("starting")
    await asyncio.sleep(3)
    print("done")

await f()
```

In a regular Python script, you can't write `await f()` just like that, outside of an asynchronous function.
