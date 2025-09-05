---
author: Chris Wellons
date: 05-09-2025 13:37
link: https://nullprogram.com/blog/2020/12/31/
taxonomy:
    category: link
title: "State machines are wonderful tools"
via: https://learnbyexample.gumroad.com/l/learnbyexample-weekly?layout=profile
---

In this article, Chris shares a couple of examples of using state machines for some tasks (decoding Morse and UTF-8) and claims that state machines are “wonderful tools” for a variety of reasons.
However, what caught my attention the most was a Python generator that counted words in a stream of bytes passed into the generator via `.send`:

```py
WHITESPACE = {  # Codepoints for whitespace characters.
    0x0009, 0x000a, 0x000b, 0x000c, 0x000d,
    0x0020, 0x0085, 0x00a0, 0x1680, 0x2000,
    0x2001, 0x2002, 0x2003, 0x2004, 0x2005,
    0x2006, 0x2007, 0x2008, 0x2009, 0x200a,
    0x2028, 0x2029, 0x202f, 0x205f, 0x3000,
}

def wordcount():
    count = 0
    while True:
        while True:
            # low signal
            codepoint = yield count
            if codepoint not in WHITESPACE:
                count += 1
                break
        while True:
            # high signal
            codepoint = yield count
            if codepoint in WHITESPACE:
                break

wc = wordcount()
next(wc)  # prime the generator
wc.send(ord('A'))  # => 1
wc.send(ord(' '))  # => 1
wc.send(ord('B'))  # => 2
wc.send(ord(' '))  # => 2
```

First, Chris rightfully claimed that the fact that you have to use `.send` to pass bytes into the generator made it painful to work with. And second, Chris seemed to enjoy branchless implementations of state machines, so I tried to make the generator branchless and easier to work with:

```py
WHITESPACE = {...}

def wordcount(source_stream):
    count = 0
    in_word = False
    for codepoint in source_stream:
        count += (not in_word) and (codepoint not in WHITESPACE)
        in_word = codepoint not in WHITESPACE
        yield count

s = "A B "
for char, count in zip(s, wordcount(map(ord, s))):
    print(count)  # 1 1 2 2
```

Depending on whether you'll be getting the data from strings, streams of bytes, files, etc, and depending on whether you'll want just the final word count or a stream of byte & count pairs, you can write a tiny wrapper to make it even easier to work with.

For example, assuming you'll be giving it non-empty strings and want only the final count:

```py
from collections import deque

# wordcount implementation.

def string_word_count(s):
    return deque(wordcount(map(ord, s)), maxlen=1).pop()

print(
    string_word_count("The quick brown fox jumps over the lazy dog.")
)  # 9
```
