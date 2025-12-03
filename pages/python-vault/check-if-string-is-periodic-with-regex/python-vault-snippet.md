---
suggested-by:
title: Check if string is periodic with regex
taxonomy:
  snippet-tags:
    - strings
    - text processing
    - regex
python_version: '3'
---

The function `is_periodic` uses regular expressions and references to capturing groups to check if a string can be formed by repeating one of its proper prefixes.

The part `(.+)` matches a prefix and the reference `\1+` tries to match that same pattern more times.
By adding `^` and `$`, you are saying that the whole pattern should be a sequence of arbitrary characters (`.+`), then repeated one or more times `(\1+)`.

(The usage of `fullmatch` together with `^` and `$` is redundant but better safe than sorry!)
