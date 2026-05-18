---
date: 18-05-2026 18:00
metadata:
    author: Rodrigo Girão Serrão
    description: "What are the differences between asyncio, threading, and multiprocessing?"
    og:image: "https://mathspp.com/insider/archive/asyncio-threading-and-multiprocessing/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/asyncio-threading-and-multiprocessing/thumbnail.webp"
title: "asyncio, threading, and multiprocessing"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 asyncio, threading, and multiprocessing

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Concurrency

The objective of concurrency is to speed up your programs by running parts of your code “concurrently”:

At the same time.

Python has three modules that you can use when you want to write concurrent code:

 1. `asyncio`
 2. `threading`
 3. `multiprocessing`

What are their differences?

What is each used for?

To help you understand each, there's a great cooking analogy that explains each.

In the past, I've written about this analogy to explain `asyncio`.

Today, I extend the analogy to explain `threading` and `multiprocessing`, too.

## How I got my lunch ready

It's a weird combination, but today for lunch I had:

 - leftover meatloaf that I microwaved
 - pasta I cooked today
 - avocado I had to peel and prepare also

When it was time for lunch I got up and walked into the kitchen.

I grabbed a pot of water and put the pasta in.

I stared at the pot while the pasta cooked.

When the pasta was ready, I walked to the microwave the microwave the meatloaf.

I stared at the microwave while it heated my meatloaf.

When the meatloaf was ready, I walked to the cutting board to peel the avocado and prepare it.

I grabbed everything and I had lunch.

If you look at what I did closely, you'll realise I was terribly inefficient.

I could've microwaved the meatloaf while the pasta was cooking.

And I could've taken care of the avocado while the meatloaf was being microwaved.

But I didn't do any of that...

## Optimising my time with `asyncio`

`asyncio` allows me to write cooperative concurrent code.

In the world of `asyncio`, whenever a long-running task doesn't depend on me, I can work on some other task.

For example, I graph the pot of water and put the pasta in.

Since the pasta has to cook by itself, I am left there **_(a)waiting_** for the pasta.

At that point, you tell me:

“Don't just stand there!

Go heat the meatloaf!”

And I go and microwave the meatloaf.

While the microwave does its magic, I am left there **_(a)waiting_** for it to finish.

At that point, you tell me:

“Don't just stand there!

Go take care of the avocado!”

And I go and start peeling the avocado.

In the meantime, the microwave dings.

But I'm focused on the avocado, working, so I just get on with it.

When I finish taking care of the avocado, you tell me to go check on the meatloaf.

And then I'm just waiting for the pasta to be ready.

When it is, I serve it and I have lunch.

## What's what in the `asyncio` analogy

In this cooking analogy, I'm the only person doing work.

But there's an orchestrator.

Someone looking at everything and telling me I can work on different things.

This “orchestrator” is the event loop.

It's responsible for helping me work on different tasks.

But I can only switch tasks when I say I am “waiting” for something.

Some tasks can be awaited:

 - cooking pasta
 - microwaving food

These are things like I/O operations or network requests.

They take time, but it's someone else spending that time.

But some other tasks can't be awaited, like peeling the avocado.

These are your CPU-intensive tasks, like crunching numbers.

When _you_ have to do the hard work.

## Switching tasks with `threading`

The module `threading` allows you to have multiple threads running.

But because of the GIL, the threads don't ever run at the same time.

Having multiple threads means you have 3 people, one in front of each item:

 - Alice is in front of the pot of water with the pasta
 - Bob is in front of the microwave
 - Charlie is in front of the avocado

Because of the GIL, _no 2 people can move at the same time_.

If Alice is putting the pasta inside the pot, Bob and Charlie are frozen.

Suddenly, Alice freezes mid-movement and Bob puts the meatloaf in the microwave...

But doesn't hit “start” yet.

Suddenly, Bob freezes and Charlie picks up the knife.

As she's about to slice the avocado in half, she freezes.

Bob unfreezes and is able to hit the “start” button in the microwave.

Bob freezes again and Alice finishes putting the pasta inside the pot.

Alice freezes again and now it's back to Charlie, that finally slices the avocado.

Now, while Alice and Bob are frozen, the pot is still cooking the pasta and the microwave is still heating the meatloaf.

## What's what in the `threading` analogy

Each person is a thread.

The CPU switches threads at will, which means only certain threads are doing work at a time.

And because of the GIL, exactly only one person can be doing any work at any given point in time.

But some tasks don't depend on you to finish working, and that's when threads are useful.

If you're doing I/O or network requests, for example.

Because even if the CPU switches you to another thread, the underlying task is still making progress.

But some tasks require you to do the hard work yourself.

Like peeling the avocado.

And peeling the avocado can only be done when Charlie is not frozen.

## `asyncio` vs `threading`

`asyncio` and `threading` sound similar.

And they're sometimes used in conjunction.

But because `asyncio` is _collaborative_, it only works with modules and functions and classes that are aware of this possibility of collaboration.

It only works with coroutines.

`threading`, on the other hand, relies on the operating system to switch threads when it determines it should switch threads.

So you don't have to use any specific libraries to be able to use multiple threads.

## `multiprocessing`

The library `multiprocessing` is more heavyweight and lets you spawn multiple independent Python processes.

It's like creating multiple kitchens with their own chefs:

 - one kitchen takes care of the pasta
 - another kitchen microwaves the meatloaf
 - and yet another kitchen takes care of the avocado

Because the kitchens are truly isolated, they can run in parallel (if you have 2+ processors in your computer).

But because the kitchens are isolated, it also means it is much harder to share information.

Or, in this case, it is much harder to plate my food for me to eat.

So, multiprocessing is great when you have to crunch numbers.

When you have CPU-intensive operations to run.

But it also makes it harder to share data across multiple processes.

## The cooking analogy

So, what did you think of the cooking analogy?

I think it's a great analogy to help you wrap your head around the libraries `asyncio`, `threading`, and `multiprocessing`, and how they work.

Send any questions you have about concurrency, `asyncio`, `threading`, `multiprocessing`, free-threaded Python, and related topics, and I'll try to answer in next week's email.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
