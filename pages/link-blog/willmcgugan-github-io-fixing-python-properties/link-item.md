---
author: Will McGugan
date: 26-06-2025 15:50
link: https://willmcgugan.github.io/fixing-python-properties/
taxonomy:
    category: link
title: "Fixing Python Properties"
# via:
---

In this article, Will shares a situation he encountered when working on [Textual](https://github.com/textualize/textual), where if a [property](/blog/pydonts/properties) has setters and getters with different types, mypy will complain[^1].
The example Will gives is for setting CSS padding, where the user can conveniently set the padding in 4 different ways:

 1. a single integer that sets padding for all four sides;
 2. a 1-item tuple with the same meaning as above;
 3. a 2-item tuple that sets vertical and horizontal padding; or
 4. a 4-item tuple that sets padding on each side independently.

However, the getter will always return a 4-item tuple with the individual values, even if they're the same.

Will's solution is to use a descriptor, which he presents, and then links folks who aren't familiar with descriptors to a talk of mine where I teach descriptors, which is a full-circle moment because I learned about descriptors by working on the Textual codebase.

What I enjoyed was that the code example that Will shares, that takes user-specified padding in any of the 4 formats and then homogenises it, can also be written neatly with [structural pattern matching](/blog/pydonts/structural-pattern-matching-tutorial):

```py
def unpack_padding(pad) -> tuple[int, int, int, int]:
    match pad:
        case int(p) | (int(p),):
            return (p, p, p, p)
        case (int(vert), int(horz)):
            return (vert, horz, vert, horz)
        case (int(top), int(right), int(bottom), int(left)):
            return (top, right, bottom, left)
        case _:
            raise ValueError(f"1, 2 or 4 integers required for padding; got {pad!r}.")
```

[^1]: As it turns out, this is no longer the case because mypy was updated recently to fix this.
