---
date: 25-05-2026 19:09
metadata:
    author: Rodrigo Girão Serrão
    description: "Write a performant web crawler with threading"
    og:image: "https://mathspp.com/insider/archive/threaded-web-crawler/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/threaded-web-crawler/thumbnail.webp"
title: "Threaded web crawler"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Threaded web crawler

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Concurrency

[Last week we talked about concurrency with `asyncio`, `threading`, and `multiprocessing`](https://mathspp.com/insider/archive/asyncio-threading-and-multiprocessing).

The three were explained with cooking analogies to give you a clear picture of how everything works, conceptually.

Today, you'll see threading in action to understand it even better.

## Crawling a website

You're going to develop a web crawler.

You point it to a URL and the crawler finds all the pages you can get to in the same website.

You'll essentially be mapping out the website based on the internal links between different pages.

You can do this with a list of URLs to crawl next and a set:

```py
URL = "https://your-website-here.com"
to_crawl = [URL]
seen = {URL}

while to_crawl:
    url = to_crawl.pop()

    links = get_links(url)
    for link in links:
        new_links = links - seen
        seen.update(new_links)
        for new_link in new_links:
            to_crawl.append(new_link)
```

All you need is a function `get_links` that gets the internal links from a given URL.

Here's that function:

```py
import requests
from bs4 import BeautifulSoup
from urllib.parse import urljoin, urlparse, urldefrag


def get_links(url) -> set[str]:
    try:
        response = requests.get(url, timeout=10)
        response.raise_for_status()
    except requests.RequestException as e:
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
        if urlparse(absolute_url).netloc == base_domain:
            links.add(absolute_url)

    return links
```

The problem with this crawler is that it's VERY slow.

You will spend a lot of time on the line of code `response = requests.get(url, timeout=10)`.

That line of code is slow because servers are slow returning requests.

But you can improve the performance with threading...

Since the slow parts of the code are slow because of the network, you can have multiple threads running.

## Working with multiple threads

Having multiple threads running concurrently will speed up your crawler.

You'll want to write a function `main` that creates a handful of threads.

Each thread will work on a different URL.

The problem is that all threads are crawling the same website.

So they need to communicate in some way.

That's what you use a queue for:

Instead of the list `to_crawl` from above, you use a queue.

All threads fetch URLs from that queue and put new links back on the queue to be processed later.

Your main function looks like this:

```py
def main():
    URL = "https://example.com"
    seen = {URL}

    to_crawl = Queue()
    to_crawl.put(URL)

    threads = [
        threading.Thread(
            target=worker,
            args=(id, to_crawl, seen),
        )
        for id in range(16)
    ]
    for thread in threads:
        thread.start()
```

The list comprehension creates 16 threads, each will run the function `worker`.

The function `worker` is the function that actually does the work:

```py
def worker(id: int, to_crawl: Queue, seen: set[str]) -> None:
    while True:
        url = to_crawl.get()

        print(f"{id:02} Getting from {url}")
        links = get_links(url)

        time.sleep(1 + 0.5 * random.random())

        new_links = links - seen
        seen.update(new_links)
        for link in new_links:
            to_crawl.put(link)

        to_craw.task_done()
```

## Joining the queue

The function `worker` has an infinite loop, so when does it stop?

Each time you insert something in the queue, the queue increments an internal counter of tasks.

Each time you call the method `task_done`, the queue decrements the counter of tasks.

You can use the method `join` to signal that you want to wait for the counter of the queue to get to zero.

So, you need to modify your function `main` to wait for the queue to have zero tasks:

```py
def main():
    URL = "https://example.com"
    seen = {URL}

    to_crawl = Queue()
    to_crawl.put(URL)

    threads = [
        threading.Thread(
            target=worker,
            args=(id, to_crawl, seen),
        )
        for id in range(16)
    ]
    for thread in threads:
        thread.start()

    to_crawl.join()      # <--
    to_crawl.shutdown()  # <--
```

## Stopping the workers

After every task has been processed, you call the method `to_crawl.shutdown`.

This will tell the workers that you can't get anything else from the queue.

So, in the places where you're calling `to_crawl.get()`, you'll now get an exception.

That's when you break out of the infinite loop:

```py
def worker(id: int, to_crawl: Queue, seen: set[str]) -> None:
    while True:
        try:
            url = to_crawl.get()
        except queue.ShutDown:
            print(f"Shutting down thread {id}.")
            break

        # ...
```

## Thread safety

Queues are thread-safe.

This means you can modify queues from multiple threads and be sure that you won't run into any weird issues.

But our set `seen` isn't thread-safe.

This means you need to be careful about modifying the set.

For example, look at these two lines of code from the function `worker`:

```py
def worker(...):
    # ...
        new_links = links - seen
        seen.update(new_links)
```

Threads switch from one to the other at _arbitrary_ points.

You may be unlucky and you may run from one thread to another in between those two lines of code.

That means that the set `new_links` and the set `seen` might become out of sync.

To prevent this, you use a lock.

A lock is a mechanism that ensures that only a single thread is doing an operation that you want to protect.

To safeguard the modification of the set `seen`, you create and share a lock across all workers:

```py
def main():
    URL = "https://example.com"
    seen = {URL}

    to_crawl = Queue()
    to_crawl.put(URL)

    lock = threading.Lock()  # <--

    threads = [
        threading.Thread(
            target=worker,
            args=(id, to_crawl, seen, lock),
        )                            #^^^^
        for id in range(16)
    ]
    for thread in threads:
        thread.start()
```

Then, your worker can use it as a context manager:

```py
def worker(
    id: int,
    to_crawl: Queue,
    seen: set[str],
    lock: threading.Lock,  # <--
) -> None:
    while True:
        # ...
        links = get_links(url)
        with lock:  # <--
            new_links = links - seen
            seen.update(new_links)

        for link in new_links:
            to_crawl.put(link)

        to_crawl.task_done()
```

## Preventing erroneous hanging

At this point, your web crawler is pretty efficient already.

There's one final tweak you must make.

Currently, if there's an exception when processing a URL, you never call `to_crawl.task_done` for that task.

This means that the line `to_crawl.join()` will wait forever because the task counter will never drop to zero.

To prevent this, you use a `try: ... finally: ...` around the processing:

```py
def worker(
    id: int,
    to_crawl: Queue,
    seen: set[str],
    lock: threading.Lock,
) -> None:
    while True:
        try:
            url = to_crawl.get()
        except queue.ShutDown:
            print(f"Shutting down thread {id}.")
            break

        try:  # <--
            print(f"{id:02} Getting from {url}")
            links = get_links(url)
            time.sleep(1 + 0.5 * random.random())
            with lock:
                new_links = links - seen
                seen.update(new_links)

            for link in new_links:
                to_crawl.put(link)
        finally:  # <--
            to_crawl.task_done()  # <--
```

That is it, the `finally` will make sure that you always mark the task as done.

Even if there's an exception while processing the URL.

## Challenge

Can you write a _similar_ crawler that uses `asyncio` instead of threads?

Send it to me if you do!

I'll write about it next week.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
