---
date: 10-06-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "Let me share a tip regarding naming inside list comprehensions."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: naming-in-list-comprehensions
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Naming in list comprehensions"
---

Let me share a tip regarding naming inside list comprehensions.

===

Naming things is the hardest problem in programming.
That's something I've heard people say quite a bit 😁
That's why I wrote an article [about naming variables and functions in Python][naming-matters].

Overall, you should always use descriptive names.
Naming things inside [list comprehensions][list-comps-101] isn't an exception.
As a simplified example, compare the two list comprehensions below:

```py
# 🤮
l = [42, 73, 10, 16]
l2 = [x > 21 for x in l]

# ✨✨
ages = [42, 73, 10, 16]
is_adult = [age > 21 for age in ages]
```

Because of the descriptive names, it becomes much easier to understand what is going on in the second list comprehension!
Strive to use descriptive names as often as possible.

However, list comprehensions lend themselves to a bit more flexibility, in my opinion.
The auxiliary variables (like `age` above) only exist inside the list comprehension.

In other words, these variables are fairly short-lived and only matter in that single place.
So, if you have a fairly long list comprehension, I think it is fairly reasonable to shorten the names of the variables a bit.
Here is an example:

```py
first_names = ["Alice", "Bob", "Charles", "Diana"]
last_names = ["Abbey", "Bacon", "Carden", "Dalton"]
ages = [42, 73, 10, 16]

# Long list comprehension:
info = [f"{first_name} {last_name} is {age} years old." for first_name, last_name, age in zip(first_names, last_names, ages)]

# Shorter variable names, still long:
info = [f"{first} {last} is {age} years old." for first, last, age in zip(first_names, last_names, ages)]

# Even shorter, a bit more extreme, sometimes useful:
info = [f"{f} {l} is {a} years old." for f, l, a in zip(first_names, last_names, ages)]
```

Using one-letter variable names in list comprehensions can be useful.
Just make sure that's ok in the context you are doing that.
You may have to adhere to a coding style that is very much against one-letter variable names!


[list-comps-101]: /blog/pydonts/list-comprehensions-101
[naming-matters]: /blog/pydonts/naming-matters


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1535375291291471875) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
