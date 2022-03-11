Today I learned about the symmetry in indexing from the beginning and end of a list with the bitwise invert operator.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![The text “~i” inside a pair of square brackets in front of a highly geometrical building.](thumbnail.png)


# Negative indexing

As you might be aware of, Python allows indexing with negative indices:

```py
>>> s = "Hello, there!"
>>> s[-1]
'!'
```

In case this is new to you, you can check out [this Pydon't on the subject][pydont-negative-indexing].

One thing to note is that the index for the `n`th element is `n - 1`,
but the index for the `n`th element from the end is `-n`.
This is just “how it works”, but it is kind of a bummer because it is very asymmetrical:

```py
>>> seq = "ABCDCBA"
>>> seq[0], seq[-1]     # 0 and -1
('A', 'A')
>>> seq[1], seq[-2]     # 1 and -2
('B', 'B')
>>> seq[2], seq[-3]     # 2 and -3
('C', 'C')
>>> seq[3], seq[-4]     # 3 and -4
('D', 'D')
```

By looking at the correspondences above,
we can see that the positive index `n` pairs up with the index `-n - 1`.


# Bitwise invert

Python has a couple of bitwise operations, one of them being bitwise invert `~`.
Bitwise invert `~n` is defined as `-(n+1)`:

```py
>>> n = 38
>>> ~n
-39
>>> -(n+1)
-39
>>> n = -73
>>> ~n
72
>>> -(n+1)
72
```

Now, maybe you can see where I'm going with this, but `-(n+1)` simplifies to `-n - 1`.


# Symmetrical indexing with bitwise inversion

If we put these two pieces of knowledge together,
we can see how we can use bitwise inversion `~` to index symmetrically from the beginning
_and_ end of a sequence, like a string or a list:

```py
>>> seq = "abccba"
>>> seq[1], seq[~1]
('b', 'b')
>>> for i in range(len(seq) // 2):
...     print(seq[i], seq[~i])
...
a a
b b
c c
```

Doesn't this look beautiful?

I feel like this is one of those things that you really won't use that often,
but there will come a time in your life when you'll want to exploit this symmetry!
And, you either remember what you just learned about `~`, or you'll be writing the uglier version with subtractions:

```py
>>> seq = "abccba"
>>> seq[1], seq[~1]
('b', 'b')
>>> for i in range(len(seq) // 2):
...     print(seq[i], seq[-i - 1])
...
a a
b b
c c
```

Thanks a lot to Tushar Sadhwani from bringing this to my attention:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">🐍Python Tip of the day:<br>You can use `~i` to index a list in python from behind, instead of `-1 - i`: <a href="https://t.co/cVm4JE2aZA">pic.twitter.com/cVm4JE2aZA</a></p>&mdash; Tushar Sadhwani (@sadhlife) <a href="https://twitter.com/sadhlife/status/1464993896346181637?ref_src=twsrc%5Etfw">November 28, 2021</a></blockquote>


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
[pydont-negative-indexing]: /blog/pydonts/sequence-indexing#negative-indices
[docs-bitwise-invert]: https://docs.python.org/3/reference/expressions.html#unary-arithmetic-and-bitwise-operations
