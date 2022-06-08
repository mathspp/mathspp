---
date: 08-06-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This article explains how long a list comprehension should be."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: how-long-should-a-list-comprehension-be
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "How long should a list comprehension be?"
---

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

Let me tell you my rule of thumb for how long a list comprehension should be.

===

[List comprehensions][list-comps-101] can become ugly, quickly.
And if they do, they lose their appeal.
A list comprehension that is too long is tough to understand.

Want an example?
Just look at the list comprehension below, taken from my [high-resolution list comprehension cheatsheet][list-comp-cheatsheet]:

```py
# Don't do this 🤮
[func(elem) for sub1 in iterable if cond1(sub1) if cond2(sub1) for sub2 in sub1 if cond3(sub2) for elem in sub2]

# Equivalent nested structure:
l = []
for sub1 in iterable:
    if cond1(sub1):
        if cond2(sub1):
            for sub2 in sub1:
                if cond3(sub2):
                    for elem in sub2:
                        l.append(func(elem))
```

I have a couple of rules of thumb I follow when writing list comps.
I use those to decide if my list comp is decent or too complicated.
Now, obviously, readability is in the eye of the beholder.
Therefore, depending on the context, my rules of thumb may not make much sense.

Usually, this is what I go for:

 - If the list comp has no `if`s
   - and the main expression is simple, I go up to 3 loops; but
   - if the main expression is long or complex, I go up to 2 loops.
 - If the list comp has an `if`, I only use a single loop and an `if`.

With these rules of thumb in mind, here are some list comprehensions that I think are OK:

```py
nested = [[[1, 2], [3, 4]], [[5]]]
# 3 loops:
#     vvv               vvv             vvv
[elem for sub in nested for ssub in sub for elem in ssub]
# [1, 2, 3, 4, 5]
```

```py
name_groups = [["alIce", "ADam", "AbE"], ["Dean", "DiANa"]]
# 2 loops, main expression is a long method call:
#    vvvvvvvvvvvvv vvv                     vvv
[name.capitalize() for group in name_groups for name in group]
# ['Alice', 'Adam', 'Abe', 'Dean', 'Diana']
```

```py
letters = "ABcDEfghIjKLmN"
# 1 loop and 1 condition:
#       vvv                   vv
[letter for letter in letters if letter.isupper()]
# ['A', 'B', 'D', 'E', 'I', 'K', 'L', 'N']
```

And remember, these are just _rules of thumb_.
Use your best judgement and consider the context when writing a list comprehension!

I hope this was useful!
Feel free to leave your thoughts below!


[list-comps-101]: /blog/pydonts/list-comprehensions-101
[list-comp-cheatsheet]: https://mathspp.gumroad.com/l/cheatsheet_list_comps_101


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1534182962303991810) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
