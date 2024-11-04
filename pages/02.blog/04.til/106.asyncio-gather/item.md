Today I learned about the behaviour of `asyncio.gather`.

===


# `asyncio.gather`

Today I learned that you can use `asyncio.gather` to gather the return results of a bunch of awaitable objects.
The list of the returns come in the order the awaitables are passed in to `gather`, and not in the order they are completed:

```py
import asyncio

async def t1():
    await asyncio.sleep(1)
    return 1

async def t2():
    await asyncio.sleep(3)
    return 2

async def t3():
    await asyncio.sleep(2)
    return 3

async def main():
    return await asyncio.gather(t1(), t2(), t3())

print(asyncio.run(main()))  # [1, 2, 3]
```

Context: this tiny TIL article was brought to you thanks to a rant by Tushar on X (context: <https://x.com/tusharisanerd/status/1853273489794302320>).
