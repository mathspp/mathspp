---
date: 27-04-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "Here are some numbers on the size of Python and everything you can learn."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: python-is-a-big-language
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Python is a big language"
---

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

Here are some numbers on the size of Python and everything you can learn.

===

Python 🐍 is a huge language:

 - it has many built-in functions;
 - it has a rich set of built-in types + their methods;
 - it has a great standard library;
 - it has a HUGE ecosystem;
 - ...

There's always something to keep you busy!
Let me tell you exactly _how_ busy:

Python 3.10 has 71 built-in functions.
The built-in functions are the functions that you can use without having to import anything.
Among them you can find `print` or `int`...
But also `compile` or `__import__`.

```py
>>> compile("def f(): return 42", "", "exec") 
<code object <module> at 0x0000028A4183CD40, file "", line 1>
>>> __import__("math")
<module 'math' (built-in)>
```

Python 3.10 has many built-in types and they come with many methods:

 - integers have 10 methods, floats have 7, and complex numbers have 3;
 - lists have 11 methods (and tuples have 2);
 - dictionaries also have 11 methods;
 - sets have 17 methods (but frozensets only 8);

And strings?!
Don't even get me started on strings.
How many methods do you think strings have?
Care to take a guess?
Drumroll, please...

Strings have 47 methods:
capitalize, casefold, center, count, encode, endswith, expandtabs, find, format, format_map, index, isalnum, isalpha, isascii, isdecimal, isdigit, isidentifier, islower, isnumeric, isprintable, isspace, istitle, isupper, join, ljust, lower, lstrip, maketrans, partition, removeprefix, removesuffix, replace, rfind, rindex, rjust, rpartition, rsplit, rstrip, split, splitlines, startswith, strip, swapcase, title, translate, upper, and zfill!

That's a lot of string methods...
And these counts I presented exclude things like dunder methods, which are also quite a few...
So, learning all of this will take you some time!

There is also the standard library, which is also huge!
The Python Standard Library has almost 250 modules!
That's why they say Python has “batteries included”...
Those are some big batteries! 🤣


<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Did you know that Python 🐍 has over 240 modules in the standard library?<br><br>Here is a MEGA thread 🧵with a super high level overview of those modules...<br><br>... but before that, I challenge you to name as many modules by heart as possible! 💬👇<br><br>Let&#39;s see who gets more!</p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1430108219872002048?ref_src=twsrc%5Etfw">August 24, 2021</a></blockquote>


And if all of this isn't enough, you can always head over to PyPI, the Python Package Index.
On PyPI you can find all the packages that people have published for others to use.
As of writing this thread, there are 371,830 projects on PyPI!
That's a lot of code 😁

So, as you can see, Python really is huge and there is a LOT to learn.
In fact, I think you will never learn **ALL** of it.
But that's fine.

In fact, I am of the opinion that mastering Python is impossible, and that's actually OK.
The point is not to know it all.

I actually wrote an article a couple of months ago about this subject.
In it, I describe how I go about navigating those numbers and always learning something new without suffering from paralysis by analysis:
<https://mathspp.com/blog/pydonts/why-mastering-python-is-impossible>.
Go ahead and give that a read!

One of the main takeaways is that you **don't** need to learn all of Python.
You just need to learn a bit and start practising and building things.
Then, keep learning, bit by bit, gradually...
And you will keep improving 🚀

!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1519381134408396800) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
