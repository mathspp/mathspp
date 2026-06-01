---
date: 01-06-2026 18:26
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how to use asyncio to write a web crawler that is concurrent."
    og:image: "https://mathspp.com/insider/archive/cooperative-concurrency-with-asyncio/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/cooperative-concurrency-with-asyncio/thumbnail.webp"
title: "Cooperative concurrency with asyncio"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Cooperative concurrency with asyncio

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Cooperative concurrency

[Two weeks ago you learned about the differences between `asyncio`, `threading`, and `multiprocessing`](https://mathspp.com/insider/archive/asyncio-threading-and-multiprocessing) for concurrency.

Last week you saw [how to use threading to write a web crawler](https://mathspp.com/insider/archive/threaded-web-crawler).

You learned things like how to manage a pool of worker threads and their lifecycle.

You also learned how to use a lock around an operation that was not thread-safe.

This week you'll learn how to write _a web crawler using `asyncio`_.

## The main difference

You used the module `requests` for the threaded web crawler.

The module `requests` is _synchronous_.

It's by using threads that you get concurrency.

In `asyncio`-land, you can also add threads to the mix.

But I want you to learn how to do everything with cooperative concurrency.

Using coroutines and awaitables.

So, the main difference is that you need to use a module that is `async`-aware to make the http requests.

Today you'll use the module `httpx`, which has an asynchronous interface.

## How to get links asynchronously

Since you're using `httpx` and not `requests`, you need to modify the function `get_links`.

It's now a coroutine and it uses `await` to wait for `httpx` to return the response:

```py
from bs4 import BeautifulSoup

# vvv
async def get_links(client: httpx.AsyncClient, url: str) -> set[str]:
    try:
        #          vvvv
        response = await client.get(url, timeout=10)
        response.raise_for_status()
    except httpx.HTTPError as e:
        print(e)
        return set()

    soup = BeautifulSoup(response.text, "html.parser")
    # ... processing the response is exactly the same.
```

It's the usage of `await` that allows the event loop to make progress on other tasks while you wait for this specific response.

You couldn't use `await requests.get(...)` because `requests` is not an awaitable.

It is not prepared to work with asynchronous code.

By the way, the `client` argument is something you're sharing across workers.

You'll see where it's defined in a moment.

## The main function

For `asyncio` it also makes sense to use a pool of workers that process one URL at a time.

For that, you use an `asyncio.Queue` object.

The main function looks like this:

```py
import asyncio
import httpx

async def main():
    URL = "https://example.com"
    seen = {URL}

    to_crawl = asyncio.Queue()
    await to_crawl.put(URL)

    tasks = []
    async with (
        asyncio.TaskGroup() as tg,
        httpx.AsyncClient() as client,
    ):
        for wid in range(16):
            tasks.append(
                tg.create_task(
                    worker(wid, client, seen, to_crawl)
                )
            )

        await to_crawl.join()
        to_crawl.shutdown()
```

The `asyncio.TaskGroup` makes it easier to manage multiple tasks that run concurrently.

Each task will run a worker that fetches a URL from the queue at a time.

The main difference here is that `main` is now a coroutine, it uses an asynchronous context manager to create an instance of `httpx.AsyncClient`, and it needs to await `to_crawl.join`.

That's because the queue is now an `asyncio` queue, which is _not_ the same thing as the queue from the module `queue`.

(In fact, `asyncio.Queue` is _not_ thread-safe!)

## Worker coroutine

Your worker also preserves most of its original structure:

```py
async def worker(
    wid: int,
    client: httpx.AsyncClient,
    seen: set[str],
    to_crawl: asyncio.Queue[str],
) -> None:
    while True:
        try:
            url = await to_crawl.get()
        except asyncio.QueueShutDown:
            print(f"Shutting down worker {wid}.")
            break

        try:
            await asyncio.sleep(1 + 0.5 * random.random())
            print(f"{wid:02} Getting from {url}.")

            links = await get_links(client, url)

            # No lock.
            new_links = links - seen
            seen.update(new_links)

            for link in new_links:
                await to_crawl.put(link)
        finally:
            to_crawl.task_done()
```

What's important to note here?

First, you're now using `asyncio.sleep` instead of `time.sleep`.

That's because `time.sleep` is synchronous, so using that would stop the _whole_ program, not just the current ask.

Remember: `asyncio` is **cooperative**, but only with coroutines that know how to cooperate.

`time.sleep` is _not_ cooperative...

The other key difference is that you no longer need a lock.

Asynchronous code only switches tasks when you hit a keyword `await`.

Between getting new links and updating the set of links that you've seen, there's no `await`.

So you don't really need a lock there.

## Full code

<summary markdown=1>
<details>Full source code.</details>

```py
# /// script
# requires-python = ">=3.14"
# dependencies = [
#     "beautifulsoup4>=4.14.3",
#     "httpx>=0.28.1",
# ]
# ///

import asyncio
import random
from urllib.parse import urljoin, urlparse, urldefrag

from bs4 import BeautifulSoup
import httpx


async def get_links(client: httpx.AsyncClient, url: str) -> set[str]:
    try:
        response = await client.get(url, timeout=10)
        response.raise_for_status()
    except httpx.HTTPError as e:
        print(e)
        return set()

    soup = BeautifulSoup(response.text, "html.parser")

    base_domain = urlparse(url).netloc
    links: set[str] = set()

    for link in soup.find_all("a", href=True):
        href = link["href"]

        # Convert relative URLs to absolute URLs and drop any anchors
        absolute_url, _ = urldefrag(urljoin(url, href))

        # Keep only links from the same domain
        if urlparse(absolute_url).netloc == base_domain and "blog" not in absolute_url:
            links.add(absolute_url)

    return links


async def worker(
    wid: int,
    client: httpx.AsyncClient,
    seen: set[str],
    to_crawl: asyncio.Queue[str],
) -> None:
    while True:
        try:
            url = await to_crawl.get()
        except asyncio.QueueShutDown:
            print(f"Shutting down worker {wid}.")
            break

        try:
            await asyncio.sleep(1 + 0.5 * random.random())
            print(f"{wid:02} Getting from {url}.")

            links = await get_links(client, url)

            new_links = links - seen
            seen.update(new_links)

            for link in new_links:
                if "blog" not in link and "talk" not in link:
                    await to_crawl.put(link)
        finally:
            to_crawl.task_done()


async def main():
    URL = "https://mathspp.com"
    seen = {URL}

    to_crawl = asyncio.Queue()
    await to_crawl.put(URL)

    lock = asyncio.Lock()

    tasks = []
    async with (
        asyncio.TaskGroup() as tg,
        httpx.AsyncClient() as client,
    ):
        for wid in range(16):
            tasks.append(
                tg.create_task(
                    worker(wid, client, seen, to_crawl, lock)
                )
            )

        await to_crawl.join()
        to_crawl.shutdown()


if __name__ == "__main__":
    asyncio.run(main())
```

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
