Today I learned you can have invisible variables in Python.

===

# What characters can you use in Python variable names?

The exact definition of what characters are valid can be found in [the Python docs][valid-characters], but a broad definition could be "all characters that can be used in written languages".

Here are some examples of Python variables that use non-ASCII characters:

```py
빵 = "bread"  # Korean
ψωμί = "bread"  # Greek
面包 = "bread"  # Chinese (Simplified)
ขนมปัง = "bread"  # Thai
pão = "bread"  # Portuguese
талх = "bread"  # Mongolian
パン = "bread"  # Japanese
```

(I used Google translate for this...
Please let me know if there is a mistake here!)

The exact list of all valid characters is gigantic.
But, in the midst of that gigantic list, four characters stand out:

 - [ᅟ U+115F HANGUL CHOSEONG FILLER](https://unicode-explorer.com/c/115F)
 - [ᅠ U+1160 HANGUL JUNGSEONG FILLER](https://unicode-explorer.com/c/1160)
 - [ㅤ U+3164 HANGUL FILLER](https://unicode-explorer.com/c/3164)
 - [ﾠ U+FFA0 HALFWIDTH HANGUL FILLER](https://unicode-explorer.com/c/FFA0)

These four characters are invisible but I do not think they are considered whitespace.
At least, not to the Unicode standard.
And, thus, they can be used in Python variables!

First, let me show you the literal transcript of a REPL session in which I use those four characters to represent four variables:

```py
>>> def ᅟ(a, b):   # Function name is U+115F
...     return a + b + 1
... 
>>> ᅠ = 6          # Variable name is U+1160
>>> ㅤ = 8         # Variable name is U+3164
>>> ﾠ = ᅟ(ᅠ, ㅤ)  # Result is U+FFA0
>>> ﾠ
17
```

Depending on your system, you may be able to see _something_ for the first two characters, but the last two should be invisible!
So, you _can_ have invisible variable names in Python.

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[valid-characters]: https://docs.python.org/3/reference/lexical_analysis.html#identifiers
