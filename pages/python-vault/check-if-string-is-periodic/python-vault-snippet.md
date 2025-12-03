---
suggested-by: Scott Huberty
title: Check if string is periodic
taxonomy:
  snippet-tags:
    - algorithms
    - strings
    - text processing
python_version: '3'
---

The function `is_periodic` checks if a string can be formed by repeating one of its proper prefixes.

It's not obvious that this function works, but think about it...
If the check `string in ...` returns `True`, then `string` is found as a substring of `(string + string)[1:-1]` at some index `k`.
That means you can imagine that they align, starting at index `k`:

```txt
string:                  1 2 3 4 5 6
string + string:         1 2 3 4 5 6 1 2 3 4 5 6
(string + string)[1:-1]:   2 3 4 5 6 1 2 3 4 5

string in ...:
  k
2 3 4 5 6 1 2 3 4 5
  1 2 3 4 5 6
```

The way in which the characters are aligned, you see that characters `1 2` must be the same as `3 4` because the `1 2` is under the `3 4`, but the `3 4` is also under the `5 6`, so those must be the same, and at the end you see `5 6` under `1 2`, so the string must have a repeated pattern of two characters, like `"ababab"`.
