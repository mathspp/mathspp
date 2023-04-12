Today I learned not to cache generators.

===

# Do not cache generators

I was [optimising all images on my blog](/blog/til/optimising-images-for-the-web) and I noticed my script was not working correctly.
It was skipping some images...

That is when I realised I was caching some generators and I shouldn't!

Here is a simplified demonstration of the issue:

```py
from functools import lru_cache

@lru_cache
def squares(n):
    return (num ** 2 for num in range(n))


for square in squares(3):
    print(square)

for square in squares(3):
    print(square)
```

What's the output of this code?
I expected it to be

```
0
1
4
0
1
4
```

But it's actually

```
0
1
4
```

Why is that?
Because the `lru_cache` cached the generator and the generator is an _iterator_.
As soon as you go over it once, it is "depleted" and you can't iterate over it again!

Here is another demonstration of the issue:

```py
from functools import lru_cache

@lru_cache
def squares(n):
    return (num ** 2 for num in range(n))


squares_to_3 = squares(3)
for square in squares_to_3:
    print(square)

new_squares_to_3 = squares(3)
print(new_squares_to_3 is squares_to_3)  # True, they are the SAME object.

# Hence, "new" squares_to_3 has actually been traversed:
print(next(new_squares_to_3, None))  # None, there is no next element.
```


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
