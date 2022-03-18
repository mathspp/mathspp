---
date: 18-03-2022 14:00
metadata:
    author: Rodrigo Girão Serrão
    description: "This thread goes over a possible Python implementation for the look-and-say sequence."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: datetime-objects-and-f-strings
taxonomy:
    category: twitter thread
    tag:
      - mathematics
      - programming
      - python
      - sequences
title: "Look-and-say sequence implementation"
---

This thread goes over a possible Python implementation for the look-and-say sequence.

===

 - 1
 - 11
 - 21
 - 1211
 - 111221
 - 312211
 - ???

I challenged you with this sequence earlier.

Now I'll explain the answer.

I will also show you a nice Python 🐍 implementation for this sequence.

Here we go 🚀

https://twitter.com/mathsppblog/status/1503823606899519489


First, how does the sequence work?

This sequence is called the look-and-say sequence.

Why?

Because the next term is created by looking at the previous one and saying what you are seeing.

We start with `1`. What do you see?

You see “one 1” → 11.


Now you have `11`. What do you see?

You see “two 1s” → 21.

Now you have `21`. What do you see?

You see “one 2 and one 1” → 1211.

Keep doing this until you reach `312211`. What do you see?

You see “one 3, one 1, two 2s, and two 1s” → 13112221.


Thus, the next term in the sequence would be 13112221.

Trivia fact: all terms in this sequence have even length!

(Well, except the starting `1` 🤪)

Another cute fact:

As the sequence grows, the lengths of the terms tends to increase by ~30% each time.

What does this mean?


Say you walk along the sequence and find a term with length 1000.

Then, the next term will have length ~1300!

Isn't it insane that this is a sequence described by words AND maths can prove this fact?

I wrote about it in a TIL article of mine 👇

https://mathspp.com/blog/til/look-and-say-sequence-growth


But hey, let's jump straight into the implementation.

The key here is understanding that we want to look at groups of consecutive equal digits, right?

That's how we go from 111221 to 312211 to 13112221.

 111 | 22 | 1
   31   22  11
↓
 3 | 1 | 22 | 11
 13  11   22  21


How can we do this in Python?

This grouping functionality is perfect for one tool from the `itertools` module:

`groupby`

`groupby` returns consecutive keys and groups from an iterable.

The keys are the unique elements and the groups are the runs of unique elements.


Here are some examples to show how `groupby` works.

 - Notice how the keys are the unique consecutive elements.

 - If we don't convert groups to lists first, we can't get the length of the group.

So, do you see where this is going? 👇

```py
>>> from itertools import groupby

# The keys are the unique elements:
>>> [k for k, _ in groupby("AAABAADDDCC")]
['A', 'B', 'A', 'D', 'C']

# The groups are iterables with the consecutive elements:
>>> [list(g) for _, g in groupby("AAABAADDDCC")]
[['A', 'A', 'A'], ['B'], ['A', 'A'], ['D', 'D', 'D'], ['C', 'C']] 

# We can compute the length of a group:
>>> [len(list(g)) for _, g in groupby("AAABAADDDCC")]
[3, 1, 2, 3, 2]

# We can pair keys and length of groups to count elements:
>>> [(len(list(g)), k) for k, g in groupby("AAABAADDDCC")]
[(3, 'A'), (1, 'B'), (2, 'A'), (3, 'D'), (2, 'C')]
```


By using a similar structure as the last line of code, we can get pretty far with our look-and-say implementation 👇

```py
>>> def look_and_say(digits):
...     return [(len(list(g)), k) for k, g in groupby(digits)]
...
>>> look_and_say([1, 1, 1, 2, 2, 1])
[(3, 1), (2, 2), (1, 1)]
```


However, the result isn't a flat list of digits... It's a list of tuples.

How can we flatten this?

`itertools` to the rescue again!

One of the best ways to flatten a list of lists is with `itertools.chain.from_iterable`:

```py
>>> from itertools import chain

>>> list(chain.from_iterable(
...     [(3, 'A'), (1, 'B'), (2, 'A'), (3, 'D'), (2, 'C')]
... ))
[3, 'A', 1, 'B', 2, 'A', 3, 'D', 2, 'C']
```


So, by putting together `chain` and `groupby`, we have ourselves an implementation!

Bonus Q: what if you wanted your function to accept an integer and return an integer?

E.g., `look_and_say(111221) → 312211`.

Can you modify the function below to handle that case?

```py
>>> from itertools import chain, groupby

>>> def look_and_say(digits):
...     return list(chain.from_iterable( 
...         (len(list(g)), k) for k, g in groupby(digits)
...     ))
...
>>> look_and_say([1, 1, 1, 2, 2, 1])
[3, 1, 2, 2, 1, 1]
```


I hope you learnt something from this thread!

Follow me @mathsppblog for more educational threads like this 😄

Also, FYI, you can read this thread – and all my other threads – on my blog:

https://mathspp.com/blog/twitter-threads


TL;DR:

 - the look-and-say sequence works by “reading out loud” the digits;
 - `itertools` has a tool `groupby` that groups consecutive elements in an iterable; and
 - `itertools` has a tool `chain` that you can use to flatten a list of lists.

```py
from itertools import chain, groupby

def look_and_say(digits):
    return list(chain.from_iterable( 
        (len(list(g)), k) for k, g in groupby(digits)
    ))
```
