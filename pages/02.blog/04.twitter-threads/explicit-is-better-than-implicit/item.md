---
date: 17-03-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "What does the Zen of Python 🐍 mean by “Explicit is better than implicit.”?"
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: explicit-is-better-than-implicit
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Explicit is better than implicit"
---

What does the Zen of Python 🐍 mean by “Explicit is better than implicit.”?

===

The Zen of Python 🐍 says

“Explicit is better than implicit.”

This means what it says on the tin.

But “explicit” might not mean what you expect.

```py
>>> import this
# ...
Explicit is better than implicit.
# ...
```


Explicit code isn't code that spells out every single step.

For example, suppose you want to sum a list of numbers.

Which of the two snippets below do you prefer?

```py
my_list = [...]
list_sum = sum(my_list)
```

```py
my_list = [...]
list_sum = 0
for number in my_list:
    list_sum += number
```


Well, the `for` loop is more explicit so it's preferred, right?

Hell NO!

We want to be explicit about the semantics of our code and not about the irrelevant details.

When you see the built-in `sum` being used you know _immediately_ that we are summing a list.


On the other hand, when you see a `for` loop, you have to interpret it.

You have to analyse it and figure out what it's doing.

This is taxing for your brain, no matter how easy it ends up being.

Thus, making use of the built-in `sum` is _more explicit_ than the `for` loop.


In general, if there are functions and/or modules that do what you need or want, use them!

Of course, it all depends on the context you are in and the people who work with your code...

But remember, a programmer's responsibility is to use the _best_ tool for the job...


Therefore, if there's a function/module that really does what you needed...

Should you reinvent the wheel, or should you use it?

Probably, you should use it!

Even if others around you don't know it...

In fact, if others don't know it...

Now is a good time to learn about it!


This is a type of discussion I see myself having quite regularly.

Where do you stand on this?

In what scenarios should we _not_ use modules that others might not know of yet?

In what scenarios should we push others to learn these new modules?

---

This thread ties in nicely with the [Pydon't about the Zen of Python][pydont-zen], where I go over the point of the Zen of Python as a whole.

[pydont-zen]: /blog/pydonts/pydont-disrespect-the-zen-of-python
