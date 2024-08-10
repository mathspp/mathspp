Today I learned that `True` is equal to 1 and `False` is equal to 0.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![A Python REPL showing that `True` is equal to 1 and that `False` is equal to 0.](thumbnail.webp)


# Booleans are a subclass of `int`

In Python, Booleans are a subclass of integers:

```py
>>> isinstance(True, int)
True
>>> isinstance(False, int)
```

I've known this for a long time, and this even allows you to write things like

```py
>>> True + True  # 1 + 1
2
>>> True * False  # 1 * 0
0
```

In fact, I tweeted about this recently.

What I didn't know is that `True` and `1` are equal,
much like `False` and `0` are equal:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">In lots of programming languages 0 is False and 1 is True. For example if you say x = 0, and check if x == True it will return False, whereas if x = 1 it will return True.<br><br>Python just giving you some extra easter eggs to play with 😄</p>&mdash; Matthew 🍵 (@uxai_net) <a href="https://twitter.com/uxai_net/status/1491011446431698948?ref_src=twsrc%5Etfw">February 8, 2022</a></blockquote>

Isn't that interesting?

In hindsight, I shouldn't be so surprised...
After all, Booleans can be converted to integers:

```py
>>> int(True)
1
>>> int(False)
0
```

and the [Truthy and Falsy][pydont-truthy-falsy-bool] value of integers
means that integers can also be converted to Booleans:

```py
>>> bool(1)
True
>>> bool(0)
False
# And other integers (and floats) can be converted to `True`:
>>> bool(73)
True
>>> bool(0.5)
True
```

So, these two conversions, plus the fact that `bool` is a subclass of `int`
makes this fact a bit more understandable...
But still!

```py
>>> True == 1
True
>>> False == 0
True
```

As to whether `True` and `False` being interpretable as integers is useful or not: it is.


# When to use Booleans as integers in Python?

Booleans can be interpreted as integers, for example,
to count objects that satisfy a given property,
or to flatten some conditions.

I recorded a short YouTube video on the subject,
that you can watch [here][yt-counting-with-booleans].

In that video, I explain how we can use Booleans to count things;
for example, the total amount of numbers in the list below that are divisible by 4:

```py
nums = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
sum(not num % 4 for num in nums)
```


[yt-counting-with-booleans]: https://www.youtube.com/watch?v=u6_6oJNqzEY
[pydont-truthy-falsy-bool]: /blog/pydonts/truthy-falsy-and-bool


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
