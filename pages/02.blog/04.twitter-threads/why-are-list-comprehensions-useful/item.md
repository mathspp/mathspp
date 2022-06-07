---
date: 07-06-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This article explains why list comprehensions are good and useful."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: why-are-list-comprehensions-useful
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Why are list comprehensions useful?"
---

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

Let me explain why list comprehensions are good and useful.

===

Why are list comprehensions good?
Why are list comprehensions useful?

Let me pick a very simple example.
I have a list of numbers and I want to create another list with the squares.
Here is the code in a `for` loop:

```py
nums = [42, -73, 0, 10, -16]

squares = []
for num in nums:
    squares.append(num ** 2)
```

How does this work?

 - create an empty list;
 - iterate over the source list; and
 - append the modified values.


Easy enough, right?

Let's go with another example.
I have a list of months and I want another list with the first 3 letters.
Here is the code in a `for` loop:

```py
month_names = ["January", "June", "December"]

initials = []
for month in month_names:
    initials.append(month[:3])
```

How does this work?

 - create an empty list;
 - iterate over the source list; and
 - append the modified values.


Notice that the descriptions of the 2 tasks are the same!
What's the only difference?
The way in which we _modify_ the original values:

 - For the squares, we took a number and squared it with `num ** 2`.
 - For the months, we took a name and sliced it with `month[:3]`.


In other words, when you have a task like this:

 > “Take a list with values and build a new list of modified values”

You just have to fill in the blanks in this code:

```py
original_list = ... # List with original data.

new_list = []
for element in new_list:
    new_list.append(... element ...)
    # modify the element ^^^^^
```


We have this recipe that is fairly easy to understand.
Not only that, it is also fairly common.
Going over a list and creating a new one is a common task.
So, Python decided to take that recipe and make it shorter!
That's where list comprehensions come in.

A list comprehension takes that recipe and makes it shorter.
Here is the comparison side-by-side:

```py
original_list = ... # List with original data.

# Loop in 3 lines.
new_list = []
for element in new_list:
    new_list.append(... element ...)
    # modify the element ^^^^^

# Shorter list comprehension.
new_list = [... element ... for element in original_list]
# Modify element ^^^^^
```


Fine, but if list comprehensions are just a shorter version of that recipe, why is the order different?
List comprehensions switch up the order to highlight the only thing that changes:
The way in which we modify the original elements!

Do you see what I mean?
List comprehensions emphasise the data transformation.
Why?

Because that's the main thing, the most important thing.
And that, in my opinion, is the best argument in favour of list comprehensions.


I hope you enjoyed this write-up.
Feel free to leave your thoughts below!


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1534182962303991810) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
