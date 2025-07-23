---
date: 07-04-2022 18:00
metadata:
    author: Rodrigo Girão Serrão
    description: "Join me as we explore what happens when you keep appending to a list you are using in a `for` loop."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: appending-to-a-list-used-in-a-for-loop
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Appending to a list used in a for loop"
---

Join me as we explore what happens when you keep appending to a list you are going over in a `for` loop.

===


## Appending to a list used in a `for` loop

Yesterday I posted a Python 🐍 code snippet with a major typo 👇

```py
numbers = [42, 73, 0, 16, 10]

is_big = []
for num in numbers:
    numbers.append(num > 10)

print("Done.")
```

Let's explore this accident.

Question ❓: what's the behaviour of this script?


This script never finishes!

(By the way, use Ctrl + C to interrupt an infinite loop!)

Can you see why?

Let me rewrite that `for` loop with a `while` loop that does more or less the same thing 👇

```py
numbers = [42, 73, 0, 16, 10]

is_big = []
idx = 0
while idx < len(numbers):
    num = numbers[idx]
    numbers.append(num > 10)
    idx += 1
```

Can you see now what is happening?


Let us clean this up a bit more.

First, notice that the list `is_big` isn't really used, so we can get rid of it.

Then, we can add some `print` calls to see what is happening real time:

```py
numbers = [42, 73, 0, 16, 10]
idx = 0
while idx < len(numbers):
    num = numbers[idx]
    print(f"idx is {idx} and num is {num}.")
    numbers.append(num > 10)
    idx += 1
```

What are the successive values that `numbers` takes?


If you run this program, you'll be flooded with output 👇

```markdown
idx is 0 and num is 42.
idx is 1 and num is 73.
idx is 2 and num is 0.
idx is 3 and num is 16.
idx is 4 and num is 10.
idx is 5 and num is True.
idx is 6 and num is True.
idx is 7 and num is False.
idx is 8 and num is True.
idx is 9 and num is False.
idx is 10 and num is False.
idx is 11 and num is False.
idx is 12 and num is False.
idx is 13 and num is False.
idx is 14 and num is False.
```

That's because we're iterating over a list that keeps growing!

But the output looks interesting, doesn't it?

For the first 5 lines, `num` is one of the original numbers.

Then, `num` becomes a Boolean value?!

Why/how?


It may look weird, but it makes sense:

The loop adds Booleans to the list because the expression inside `numbers.append(...)` is a comparison: `num > 10`.

So, for the first 5 iterations, we go over the original numbers in the list...

And check which ones are greater than 10.


Then, we add the results of those comparisons to the same list!

In the first iteration, we have:

 - idx = 0
 - num = 42
 - num > 10 = True

So, we append `True` to the list.

Then, the list `numbers` becomes `[42, 73, 0, 16, 10, True]`.


Therefore, later down the road, when `idx` becomes `5`, `num` becomes `True`.

So, if `num` is `True`, why doesn't `num > 10` raise an error?

Because Boolean values in Python 🐍 (that is, `True` and `False`) can also be seen as integers!


In other words, when need be, we can

 - look at `False` as if it were `0`; and
 - look at `True` as if it were `1`.

Thus, when `num` is `True` and we do the comparison `num > 10`, Python does the comparison `1 > 10`.

The result, we know, is `False`.


After the first 5 iterations, `numbers` becomes this:

`[42, 73, 0, 16, 10, True, True, False, True, False]`

Then, `idx` goes from 5 to 9:

We take those 5 Boolean values and compare them with 10:


`True > 10` is `False`, and so is `False > 10`.

Thus, after 5 more iterations, `numbers` becomes:

`[42, 73, 0, 16, 10,
 True, True, False, True, False,
 False, False, False, False, False]`

So, we reach a point where we keep appending `False` to the list, over and over again.


This particular situation arose because I made a mistake when writing my program.

And also because I didn't triple-check my code.

But one key thing we learn from this is that we shouldn't append to lists that are being used in `for` loops.

That's typically a bad idea!


Sorry for the long ramble!

Here are three key takeaways:

 - lists in `for` loops _can_ be modified but _shouldn't_;
 - Boolean values can be used as integers; and
 - `True` corresponds to `1` and `False` corresponds to `0`.

Follow me @mathsppblog for more hilarious code bugs!

!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1512087364406951937) I published on Twitter [@mathsppblog](https://twitter.com/mathsppblog).
