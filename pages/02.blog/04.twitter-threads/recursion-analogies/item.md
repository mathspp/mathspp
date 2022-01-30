---
date: 30-01-2022
metadata:
    author: Rodrigo Girão Serrão
    description: A couple of analogies I use when thinking about recursion.
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
publish_date: 26-01-2022
slug: recursion-analogies
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
      - recursion
title: "Recursion analogies"
---

A couple of analogies I use when thinking about recursion.

===

I remember it took me a couple of years to really _understand_ recursion.

When I first started learning computer programming, it just didn't click!

Now, recursion feels natural to me.

```py
def f():
    return f()
```

Here's how I think of it 👇


First, I'll tell you how I imagine recursion in my head.

(I am a very intuitive person, so these analogies are really important to me.)

Then, I will explain how these analogies work when writing recursive code.

Let's go 🚀


The first analogy I run in my head is that recursion is like time-travelling.

It's like you are writing code...

... while believing that your future self will have succeeded in writing that code 🤣


That's what I feel I am doing.

Why?

Because I'm calling a function that hasn't been finished yet!

That's why I say there's time-travelling involved.

I amuse myself with this analogy, but I also understand this may not be the most helpful for beginners.

Here's another one:


Calling a recursive function is like trusting your programming buddy.

You can imagine you have a programming buddy, and they are _really_ good.

So, when you are writing a piece of code, you off-load most of the work to your buddy.


How do you do that?

Well, you call your buddy's function!

It's like you are working together on the same piece of functionality...

So, when you start writing your code, you try to leverage your buddy's code as much as possible.


I have one other weird image in my head:

A recursive function is like a tunnel!

When you call a recursive function, you go inside a tunnel.

When you call it again, it's a tunnel in another tunnel.

And you just hope that you come out of all the tunnels, on the other end.


These are the three main analogies I think about when I think about recursion.

(Are they too weird? I hope not 🤣)

Now, let me explain how I think of those when actually writing code.

As a motivating example, let's implement a (recursive) function to sum a list of integers.

```py
>>> sum([42, 73, 0, 5])
120
```


Writing recursive code doesn't have to be scary!

Honestly, it doesn't.

The best way to go about it?

Just start.

Literally!

Start with the function definition, the docstring, and an example 👇

```py
def sum(lst):
    """Sums a list of numbers recursively.

    Example: sum([42, 73, 0, 5]) == 120.
    """
    pass
```


Now, it's just a matter of following a couple of simple steps.

The steps are simple...

Mastering them isn't ― so don't be discouraged if things are hard!

The first step you take is figure out what's the simplest, most basic example you could have entered.


Imagine you are doing the work of the function by hand.

(In this case, imagine you have to sum the numbers by hand.)

What's the argument that would give you the least work?

Imagine you are REALLY lazy 😴 what argument would you pick?


For example, summing up lists with just one item is easy, right?

If the list only has 1 item, summing it means returning the only item in the list!

You didn't have to do much for this one, which is nice, because you are lazy.

Now, write it in code:

```py
def sum(lst):
    """Sums a list of numbers recursively.

    Example: sum([42, 73, 0, 5]) == 120.
    """
    if len(lst) == 1:
        return lst[0]

    ...
```


So, you already covered a couple of cases with your code 👇

```py
>>> sum([10])
10
>>> sum([-18])
-18
>>> sum([52354234543])
52354234543
```

What about longer lists..?

Now, remember you are lazy, right?

You don't want to have to sum long lists... That's too much work.

THANKFULLY, your buddy implemented that function too!


So, what you have to do is call your buddy's code.

Imagine you have a longer list.

It would be great if you could just give that big list to your buddy...

But you can't!

Your buddy won't sum the _whole_ list for you.

You are lazy, but you have to do _something_.


So, you make an agreement with your buddy.

Your buddy will do MOST of the work, as long as you do _something_.

So, you are lazy 😴

Remember?

If you get a long list, and your buddy is willing to do _most_ of the work, how little can you do?


Ah! I know! Your buddy can sum the whole list, except 1 item!

Your buddy sums all of that, and then you just need to add the last item yourself.

How about that?

Just write it in the code!

```py
def sum(lst):
    """Sums a list of numbers recursively.

    Example: sum([42, 73, 0, 5]) == 120.
    """
    if len(lst) == 1:
        return lst[0]

    return lst[0] + sum(lst[1:])
```


Notice how, in the example above, we now call `sum`.

We call the function `sum` with `lst[1:]`.

What does that mean?

It means we are calling your buddy's code with almost all of the list.

The only item we leave out is the first one, that we add by hand with `lst[0] + ...`.


After implementing the function recursively, test it!

Pass it different arguments and see how it behaves:

```py
>>> sum([42, 73, 0, 5])
120
>>> sum([42, 73, 0])
115
>>> sum([42, 73])
115
>>> sum([42])
42
>>> sum([])
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
  File "<stdin>", line 8, in sum
IndexError: list index out of range
```


Woops! The function seems to have an issue!

Our function doesn't handle empty lists, for now...

That's because we thought the “simplest” case was when the argument list had 1 item.

Looks like, after all, the simplest case is when the list is empty:

```py
def sum(lst):
    """Sums a list of numbers recursively.

    Example: sum([42, 73, 0, 5]) == 120.
    """
    if len(lst) == 0:
        return 0

    return lst[0] + sum(lst[1:])
```


From my experience teaching Python 🐍, many bugs in recursive functions come from this:

Using the wrong base case.

The base case is the branch of the function that is _not_ recursive.

It is the lazy case that you handle yourself.


We started with the wrong base case, but now it's correct.

How can you figure out the correct base case?

Well, practising helps!

I also think in terms of this laziness: think about the _least_ you can do.

It also helps if you try to break down the problem in many small steps.


For your base case, you always want to include as little small steps as possible.

In our example, the small steps are summing each single item.


That's it for this thread, I hope it was helpful!

If you are on your programming journey, and learning Python 🐍 in particular, follow @mathsppblog for more educational content.

This thread will be automatically unrolled 🧻 here 🔗 https://mathspp.com/blog/twitter-threads


TL;DR:

 - you can think of recursion like time-travelling 🤯;
 - or like using code a buddy of yours wrote 🤝;
 - start by identifying the small steps;
 - write the base case to handle 1 small step;
 - use your buddy's code to handle almost all steps.
