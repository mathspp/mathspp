---
date: 07-04-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "Let me tell you why it is always 100% unnecessary to use a full slice `[:]` with a string in Python."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: full-slice-of-a-string-is-irrelevant
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Full slice of a string is irrelevant"
---

Let me tell you why it is always 100% unnecessary to use a full slice `[:]` with a string in Python.

===

# Full slice of a string is irrelevant

Yesterday I challenged your Python 🐍 knowledge!

I asked:

Why is it always 100% unnecessary to use a full slice `[:]` with a string?

Now it's time I give you the answer!

Let's go 🚀

```py
my_str = "Hello, world!"
#     vvv never use this with strings.
my_str[:]
```


Recall that slices do a copy of the sliced chunk.

If you are using a full slice `my_str[:]`, that means you are getting the whole string.

So, why wouldn't you just use `my_str`..?

Why do you need a _copy_ of your string?

Sometimes you _do_ need a copy of a list:

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


Sometimes you need a copy of a list because lists are _mutable_.

This means that the contents of a list can change!

So, if you want to modify a list but also keep its original values, you need a copy.

But this doesn't make sense for strings!

Can you see why?

```py
>>> l = [42, 73, 0, 10]
>>> new_l = l  # Regular assignment...
>>> new_l[1] = 999
>>> l
[42, 999, 0, 10]
#    ^^^ valued changed “through” new_l.
```


It doesn't make sense to get a copy of a string because strings are _immutable_!

What does this mean?

It means that the contents of a string never change!

For example, haven't you noticed how `.upper` returns a _new_ string?

You never modify the original string!

```py
>>> hey = "Hey!"
>>> scream = hey.upper()
# All upper case:
>>> scream
'HEY!'
# Preserved its casing:
>>> hey
'Hey!'
```


That is why it never makes sense to make a copy of a string.

You can't change it either way, so might as well use the original string from the start!

Did this make sense?

I hope so 🤞


Was this thread a waste of time?

If not, retweet it and follow @mathsppblog for more Python 🐍 knowledge!

This thread, together with all my other threads, can be found on my website: https://mathspp.com/blog/twitter-threads.

You can go there and bookmark this thread if you need it for later!


Key takeaways:

 - `[:]` copies sliced object
 - lists are mutable, i.e., their contents can change
 - useful, for example, when you want to modify a list & also keep the original
 - irrelevant for strings because strings are immutable, i.e., their contents can't change


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1511342969990823937) I published on Twitter [@mathsppblog](https://twitter.com/mathsppblog).
