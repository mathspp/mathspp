---
date: 09-06-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "How do you format list comprehensions that are too long and go beyond the line width?"
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: formatting-long-list-comprehensions
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Formatting long list comprehensions"
---

How do you format list comprehensions that are too long and go beyond the line width?

===

What to do when you have a [list comprehension][list-comps-101] that is too long?

When it comes to formatting, there are three ways to go about it:

 1. Don't worry with code formatting **at all*.
 2. Applying a consistent (personal) style.
 3. Letting an auto-formatter do the heavy lifting.

In my opinion, you shouldn't be in category 1.
As I have written before, [code style matters][code-style-matters].
As for categories 2. and 3., you can do whatever you want.

I do both:

 - I use [`black`][black] to format my code consistently; but
 - I try to write my code already in the style that `black` likes.

For list comprehensions, I think it looks pretty nifty:

```py
# 🤮
def some_function(...):
    while True:
        with open(...) as really_long_file_name_variable:
            beginnings = [this_is_a_line[:10] for this_is_a_line in really_long_file_name_variable]
            # This list comprehension is waaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaay too long -^

# ⚫✨
def some_function(...):
    while True:
        with open(...) as really_long_file_name_variable:
            beginnings = [
                this_is_a_line[:10]
                for this_is_a_line in really_long_file_name_variable
            ]
```

When a list comprehension is too long, `black` splits it:

 - the opening bracket `[` stays right where it is;
 - the main expression goes on a new line by itself and is indentend with respect to the line where the `[` is;
 - the loop goes on a new line by itself and aligned with the main expression; and
 - the closing bracket `]` goes in a new line, with the indentation of the line where the `[` is.

If there are more loops or if there are conditions, they all get their own lines:

```py
# 🤮
[final for sub1 in iterable if condition1(sub1) for sub2 in sub1 if condition2(sub2) for final in sub2]

# ⚫✨
[
    final
    for sub1 in iterable
    if condition1(sub1)
    for sub2 in sub1
    if condition2(sub2)
    for final in sub2
]

# This is a terrible list comprehension...
# I'm just showing how formatting works 🤣
```

Now you know what to do with a long list comprehension!


[black]: https://github.com/psf/black
[list-comps-101]: /blog/pydonts/list-comprehensions-101
[code-style-matters]: /blog/pydonts/code-style-matters


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1535016174496407555) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
