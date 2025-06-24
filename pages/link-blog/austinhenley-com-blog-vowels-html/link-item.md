---
author: Austin Z. Henley
date: 24-06-2025 22:07
link: https://austinhenley.com/blog/vowels.html
taxonomy:
    category: link
title: "The fastest way to detect a vowel in a string"
via: https://www.pythonweekly.com
---

In this article, Austin goes over 11 creative ways to check if there is a vowel in a string.
After a lot of acrobatics and creative snippets of code, Austin found out that a regex search using the module `re` was faster than any other Python solution that Austin wrote...

And then the internet stepped in, and the (so far) fastest solution was found:

```py
def loop_in_perm(s):  # best
        for c in "aeiouAEIOU":
            if c in s:
                return True
        return False
```

What's fun is that a similar function, with “the loops reversed” had already been considered and was only performing mediocrely.
This is the mediocre version:

```py
def loop_in(s):  # similar to best but mediocre
    for c in s:
        if c in "aeiouAEIOU":
            return True
    return False
```
