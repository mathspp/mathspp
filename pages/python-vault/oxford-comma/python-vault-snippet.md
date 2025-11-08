---
title: Oxford comma
taxonomy:
  snippet-tags:
    - strings
    - text processing
python_version: '3'
---

The function `oxford_comma` turns a list of strings into a Human-readable enumeration that separates elements with commas and the final element with the word “and” using the Oxford comma style.

You can also use [structural pattern matching](/blog/pydonts/structural-pattern-matching-tutorial) in Python 3.10+ to match on the structure of the input list:

```py
def oxford_comma(strings):
    match strings:
        case []:
            return ""
        case [s]:
            return s
        case [s1, s2]:
            return f"{s1} and {s2}"
        case _:
            return ", ".join(strings[:-1]) + ", and " + strings[-1]
```
