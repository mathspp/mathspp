# 🐍🚀 cooking with asyncio

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider#subscribe) to get weekly Python deep dives like this one on your inbox!

## The mental model for understanding asynchronous programming

I came up with a cool analogy to help me understand the point of asynchronous programming and the role that the module `asyncio` plays in it.
Now, I’m sharing it with you in the hopes that it helps you too!

## Here’s what I had for lunch

Today I had pasta, meatloaf, and avocado, for lunch.
The meatloaf was in the fridge, so I only had to reheat it.
The avocado needed peeling.
And I had to cook pasta because I didn’t have any pasta that was already cooked and I don’t eat raw pasta.

Here’s how I prepped my lunch:

## An inefficient lunch prep

I filled a pot with water, brought it to a boil, and put some pasta in.

I stared at the pot for 10 min while the pasta cooked.

When the pasta was done, I set it aside.

Then, I opened the fridge, I served some meatloaf onto a plate, and I microwaved it.

I stared at the microwave for 3 minutes and when it was done I added the meatloaf to the plate of pasta.

Then, I grabbed an avocado from the fridge.

I peeled it and sliced it and served it on my plate.

That took roughly 2 minutes.

In the end, how much time did it take me to prep my lunch?

A full 15 minutes.

Pretty inefficient, given that I stared at a pot of pasta for 10 minutes…

## My lunch prep in code

You can encode this process of prepping my lunch into code.
I’ll write one function per thing I had to prepare, and then call those functions one by one, in order, to get those parts of my meal ready.

```python
import time

def cook_pasta():
    print("Pasta will now cook.")
    time.sleep(10)  # 10 min to cook.
    print("Pasta ready.")

def heat_meatloaf():
    print("Meatloaf in the microwave.")
    time.sleep(3)  # Meatloaf takes 3 min to heat.
    print("Meatloaf heated.")

def peel_avocado():
    print("Peeling/slicing avocado.")
    time.sleep(2)  # 2 min to peel <- I'm slow.
    print("Avocado sliced.")

def lunch():
    print("Preparing lunch.")
    start = time.time()
    cook_pasta()
    heat_meatloaf()
    peel_avocado()
    end = time.time()
    print(f"{round(end - start)} min of prep time.")

lunch()
```

This program is synchronous because everything happens in its own time, each function after the other.
Running this program would give you this output:

```
Preparing lunch.
Pasta will now cook.
Pasta ready.
Meatloaf in the microwave.
Meatloaf heated.
Peeling/slicing avocado.
Avocado sliced.
15 min of prep time.
```

## Efficiently managing my time

Alright, we can do better than this.

Let us try again, but this time YOU help ME out, ok?

Whenever I have to **wait*- for something, I will tell you I have twiddling my thumbs and you will tell me what to do next.

Here we go.

I start by cooking the pasta in the pot.

When the pasta is starting to cook, I tell you: hey, I’m **waiting*- for the pasta.
What can I do in the meantime?

You look around and say: go take care of the meatloat.

I put the meatloaf in the microwave.

When the microwave starts going, I tell you: hey, I’m **waiting*- for the meatloaf.
What can I do in the meantime?

You look around and say: go take care of the avocado.

I peel and slice the avocado. When I’m done, I let you know.
What now?

You tell me to go check on the pasta.

The pasta is still cooking, so I ask you: hey, I’m still **waiting*- for the pasta.
What can I do in the meantime?

You tell me to check on the meatloaf.

I walk up to the microwave and the meatloaf just finished heating up, so I serve it on my plate next to the avocado.

I let you know I’m free again, so you just tell me to wait for the pasta, since it’s the only thing left.

When the pasta is done, I serve it and I eat.

How much time did it take me this time?

It took me 10 minutes to do the whole prep!

## Switching between tasks saved time

This time it only took 10 minutes to prep lunch because YOU were coordinating me.

Whenever I needed to wait for something out of my control, YOU told me to do something else that was useful.

Those are the two ingredients you need to use the module `asyncio`:

 - tasks that take time but don’t depend on you; and
 - something to manage where you spend time while waiting for those tasks.

How can you take the previous piece of code and rewrite it following this model?

## Asynchronous lunch prep

When I was cooking the pasta and heating the meatloaf, I had times where I was doing nothing: I was just waiting.

When those moments happened, I **notified*- you that I was waiting.

That “notification” is very important, because it’s what told you I was ready to work on something else.

In async code, you do this “notification” with the keyword `await`.

You’re saying “I’m awaiting for something outside of my control, please give me something useful to do”.

Here’s what the functions `cook_pasta` and `heat_meatloaf` could look like:

```python
import asyncio

async def cook_pasta():
    print("Pasta will now cook.")
    await asyncio.sleep(10)  # Pasta takes 10 min to cook.
    print("Pasta ready.")

async def heat_meatloaf():
    print("Meatloaf in the microwave.")
    await asyncio.sleep(3)  # Meatloaf takes 3 min to heat.
    print("Meatloaf heated.")
```

## `asyncio.sleep`? Where did `time.sleep` go?

Do you understand the idea of me asking for your help to decide what to do next while I'm waiting for the pasta to boil/meatloaf to reheat?

That's done with the `await` keyword, **BUT*- ... It needs something specific on the right of `await`!

It needs an object that is aware of this whole situation, and that understands that I might have better things to do than just stand still while waiting.

Because that's what `time.sleep` does. It just waits quietly.

However, the coroutine `asyncio.sleep` is more understanding: it understands you might have better things to do.

That is also why your `cook_pasta` and `heat_meatloaf` now have the keyword `async` to the left of `def`:

They build **coroutines**.

If you run `cook_pasta`, you get this output:

```python
>>> cook_pasta()
<coroutine object cook_pasta at 0x000001DDE47571C0>
```

It doesn't “run” in the sense we are used to.

It just builds a coroutine that will start running when you tell me to start working on it.

Coroutines are just objects that make part of this whole “notify me of what to do next” game.

So, inside your `async def` definitions, you just put an `await` when you know you can do something else while waiting for that to finish.

You just have to be careful, though: what's on the right of `await` needs to be aware of the async game going on.

## What about the “manager” of the work?

After you define your tasks and determine in what places you can switch focus, you just have to take care of one thing:

The something that decides on what I should focus next.

That's a job the module `asyncio` already knows how to do, thankfully 😅

The something that manages the work is called **the event loop**.

We have a series of tasks that we want to switch back and forth from, right? It's a bunch of them.

So, we use `asyncio.gather` to literally gather all those tasks together, and let the event loop (you, the manager) switch the focus back and forth.

That’s what `lunch` now does:

```python
async def lunch():
    print("Preparing lunch.")
    start = time.time()
    await asyncio.gather(
        cook_pasta(),
        heat_meatloaf(),
        peel_avocado(),
    )
    end = time.time()
    print(f"{round(end - start)} min of prep time.")
```

## Running a coroutine

But `lunch` is also a coroutine and if you call it, it doesn’t really “run” in the sense that you want it.

You need to use `asyncio.run` to take a coroutine and kick things off.

That call to `asyncio.run(lunch())` would be equivalent to the moment you walk up to me and say
“Rodrigo, go in the kitchen and start preparing lunch”.

```python
async def lunch():
    print("Preparing lunch.")
    start = time.time()
    await asyncio.gather(
        cook_pasta(),
        heat_meatloaf(),
        peel_avocado(),
    )
    end = time.time()
    print(f"{round(end - start)} min of prep time.")

asyncio.run(lunch())  # <-- !!!
```

## What about the avocado code?

The `peel_avocado` becomes this:

```python
async def peel_avocado():
    print("Peeling/slicing avocado")
    time.sleep(2)  # 2 min to peel <- I'm slow.
    print("Avocado sliced.")
```

Wait, what?! `peel_avocado` has `async def` but no `await`?! What?!

It has `async def` because it is aware of this whole game of switching back and forth. Thus, it's a coroutine function.

However, it has no `await` because I can't walk away from the avocado!

The avocado won't peel itself, right? I have to do it.
I have to spend 2 whole minutes peeling the avocado and slicing it.
That's what `time.sleep` does.

Only when I'm completely done with the avocado, and the coroutine finishes, you can tell me to do something else.

This is another key idea:

!!! 💡 **I can only switch to work on something else at specific points in the program. And I mark those times with `await`.**

And that only works as long as I am “awaiting” for something that actually speaks the async language.

## The final output

If you run the final async version, you get the same lines of output but in a very different order!

```
Preparing lunch.
Pasta will now cook.
Meatloaf in the microwave.
Peeling/slicing avocado.
Avocado sliced.
Meatloaf heated.
Pasta ready.
10 min of prep time.
```

That’s because `asyncio` helped me be smarter about my lunch prep!

## Takeaways

Here’s some key conclusions:

 - `async def` defines coroutine functions
 - coroutine functions build coroutines
 - coroutines are better at managing time
 - with `await`, you can do something useful while waiting for something else to finish
 - the expression `await obj` only works if `obj` is awaitable

---

> This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider#subscribe) to get weekly Python deep dives like this one on your inbox!
