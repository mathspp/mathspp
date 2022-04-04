---
date: 04-04-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This thread talks about slicing syntax and the full slice `[:]` for copying."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: full-slice
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Full slice [:]"
---

This thread talks about slicing syntax and the full slice `[:]` for copying.

===

Do you know about slicing in Python 🐍?

It's that thing that _looks_ like indexing but has colons : in it.

For example, `my_list[2:6]` is list slicing!

When you do list slicing, did you know `[:]` gets the whole thing?

But what's the point in that..?

I'll explain 🚀

```py
>>> my_str = "Hello, world!"
>>> my_str[:5]
'Hello'
>>> my_str[7:]
'world!'
>>> my_str[:]
'Hello, world!'
```


First, how does slicing work?

The idea is simple:

When you write a slice, Python copies the sliced chunk and gives it to you.

The key here is to realise the slice is a copy of the sliced portion.

Notice, below, how the slice is independent of the original list:

```py
>>> l = list(range(10))
>>> l
[0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
>>> sub = l[2:6]
>>> sub[0] = 42
>>> l
[0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
>>> sub
[42, 3, 4, 5]
```


When you slice, you can omit the first number in the slice.

That means “slice from the beginning”.

Likewise, omitting the second number in the slice means “slice until the end”.

So, if you omit both..?

“Slice from the beginning until the end.”

```py
>>> l = [42, 73, 0, 10]
>>> l[:3]
[42, 73, 0]
>>> l[1:]
[73, 0, 10]
>>> l[:]
[42, 73, 0, 10]
```


So, what's the point of using `[:]`?

We use it to get a copy of the original thing!

That is when using `[:]` is useful:

When you want a copy so as to not change the original.

For example, you can use `[:]` on a list if you are going to modify it and want to keep the original:

```py
>>> words = "How is your day going?".split()
>>> words
['How', 'is', 'your', 'day', 'going?']
>>> their_words = words[:]
>>> their_words[2] = "their"
>>> their_words
['How', 'is', 'their', 'day', 'going?']
# changed     ^^^^^^^
>>> words
['How', 'is', 'your', 'day', 'going?']
# original    ^^^^^^
```


Did this make any sense?

If you want to learn more about slicing, you can always check this Pydon't: https://mathspp.com/blog/pydonts/idiomatic-sequence-slicing

To check if you understood, I have a challenge for you 💪

Why is it ALWAYS 100% unnecessary to use `[:]` on a string?

```py
my_str = "Hello, world!"
#     vvv never use this with strings.
my_str[:]
```


!!!! This thread was originally published [here](https://twitter.com/mathsppblog/status/1510980579340308483).
