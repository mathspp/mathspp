---
date: 15-04-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "Learn how strings are ordered and compared in Python."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: how-are-strings-ordered-in-python
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "How are strings ordered in Python?"
---

Learn how strings are ordered and compared in Python.

===

How are strings ordered in Python 🐍?

How do the operators <, <=, >=, and >, work for strings?

You can find a couple of examples below.

Can you tell what the outputs will be?

```py
>>> "bar" > "acorn"
# ???

>>> "dice" < "dolphin"
# ???

>>> "car" > "carnivore"
# ???

>>> "Rice" <= "corn"
# ???

>>> "10" < "2.5"
# ???

>>> ".py" < "_py"
# ???
```


Have you ever seen a dictionary? The book kind?

That book with thousands of words and their meanings.

How are words ordered in there?

In alphabetical order, right?

First, we have all the words starting with A.

Then, all the words starting with B.

And so on...


That's why I like looking at `str1 < str2` and reading:

“Does `str1` come **before** `str2` in the dictionary?”

And I look at `str1 > str2` and I read:

“Does `str1` come **after** `str2` in the dictionary?"

With this in mind, what are the results of the comparisons below?

```py
>>> "bar" > "acorn"
# ???

>>> "dice" < "dolphin"
# ???

>>> "car" > "carnivore"
# ???
```


 - "bar" starts with B.
 - "acorn" starts with A.

So, `"bar" > "acorn"` is `True`.

What about `"dice" > "dolphin"`?

Think about it.

They start with the same letter, D.

But then, "dice" has an I and "dolphin" has an O.

I comes before O, so "dice" comes before "dolphin".


What about `"car" > "carnivore"`?

 - both start with C;
 - 2nd letter of both is A; and
 - 3rd letter of both is R.

But then, one word ends and the other continues...

What comes first in the dictionary?

The short one!

So `"car" > "carnivore"` is actually false.


Now we are ready to tackle the next set of examples:

```py
>>> "Rice" <= "corn"
# ???

>>> "10" < "2.5"
# ???

>>> ".py" < "_py"
# ???
```

What is the result of `"Rice" <= "corn"`?


Well, now we can't just think about dictionaries.

Why is that?

Because `"Rice"` and `"corn"` are capitalised differently.

So, we need to know what comes first:

 - an upper case R?
 - or a lower case C?


The thing that comes first is actually the upper case R!

Why?

Python can compare _any_ two strings.

Even strings that don't have letters, for example.

And all those strings need to be comparable to each other.


So, the solution that people came up with is to attach an integer to each character.

Think of it like an id.

Then, when comparing characters, we compare the associated ids instead.

In reality, the id of each character is its Unicode code point...


So, in other words, Python didn't come up with random ids for all the characters.

It actually borrows those ids from the Unicode standard.

How can you check the codepoint of a character?

With the `ord` built-in:

```py
>>> ord("R")
82
>>> ord("c")
99
>>> ord("R") < ord("c")
True
>>> "Rice" < "corn"
True
```


With this in mind, you should be able to answer the next examples.

Now, let us tackle the comparison `"10" < "2.5"`.

In the Unicode standard, the digits 0 to 9 have consecutive code points.

So, when comparing `"10"` to `"2.5"`, we start with comparing the `"1"` and the `"2"`:


The 1 comes before the 2 in the Unicode standard, so `"10" < "2.5"` evaluates to `True`.

**Attention**: this shows that comparing strings that contain numbers is **different** from comparing the numbers themselves.

This can be misleading, because some times the results agree:

```py
>>> "34" < "47"     # ... but "34" < "4" is False
True
>>> "-56" > "-105"  # ... but "-56" > "-58" is False
True
>>> "2.5" < "23.4"  # ... but "2.5" < "10" is False
True
```


TL;DR:

 - for words, think of a dictionary (the book) as a mnemonic;
 - strings are compared char by char;
 - short strings come first ("car" vs "carnivore");
 - characters are ordered by their Unicode code point;
 - the built-in `ord` returns the code point of a char.


But wait!

There is one example left!

Can you tell me what the result is?

And can you justify it with the help of the built-in `ord`?

Give it a shot 🚀


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1515060216232132609) I published on Twitter [@mathsppblog](https://twitter.com/mathsppblog).
