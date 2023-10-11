Today I learned that Python and other programming languages have negative zero, -0.0.

===


# Negative zero `-0.0`

If you open your Python REPL and type `-0` in, you get `0` back:

```py
>>> 0
0
>>> -0
0
```

That makes sense.
After all, the integer `-0` is the same as the integer `0`...

However, `0.0` and `-0.0` are also equal but they are displayed differently in your Python REPL:

```py
>>> -0.0
-0.0
>>> 0.0
0.0
```

As it turns out, because of the way floats work in computers, `0.0` and `-0.0` are two different entities.
Obviously, they compare as equal:

```pycon
>>> 0.0 == -0.0
True
```

But they are different entities.
And _sometimes_, having `-0.0` and `0.0` as two distinct things can be helpful.

For example, you can think of `-0.0` as a very small negative number that you just couldn't represent as a float.
In fact, the code below shows a couple of divisions that should all result in negative numbers, until suddenly we're dealing with such _small_ numbers, that we get `-0.0`:

```py
x = -1
for _ in range(20):
    x /= pow(10, 20)
    print(x)

"""
-1e-20
-1e-40
-1e-60
-1e-80
-1e-100
-1e-120
-1e-140
-1e-160
-1e-180
-1e-200
-1e-220
-1e-240
-1e-260
-1e-280
-1e-300
-1e-320
-0.0
-0.0
-0.0
-0.0
"""
```

In contrast to this, if `x` starts out as a positive number and you keep dividing it further, you end up at `0.0`:

```py
x = 1
for _ in range(20):
    x /= pow(10, 20)
    print(x)

"""
# ...
1e-260
1e-280
1e-300
1e-320
0.0
0.0
0.0
0.0
"""
```


## Use cases for negative zero

[Someone on Twitter](https://x.com/somacdivad/status/1711877395739709453) mentioned that they've seen this used in a chemistry paper.
The appearance of a `-0.0` indicated that a very small number was rounded to `0` from a negative number, but in that particular context (of the chemistry paper), that indicated that a specific chemical process had occurred.

[Someone else reported](https://twitter.com/tmcw/status/1553575741631111168) using negative zero `-0.0` to represent the value `None` in a list that could only contain floats.
So, if for some reason you have a list that is typed as `list[float]` and you want to be able to represent the value `None`, you could use `-0.0`.

If you know of another practical use case for negative zero, comment below and I'll add it to this article!


## Distinguish `0.0` from `-0.0`

Because `0.0 == -0.0`, one way I could think of to distinguish negative zero from “regular” zero is by converting it to a string and comparing the signs of the number.

The function `is_negative_zero` below computes this:

```py
def is_negative_zero(number):
    return str(number) == "-0.0"

print(is_negative_zero(0.0))   # False
print(is_negative_zero(-0.0))  # True
```

Then, Adam Johnson on Twitter reminded me of the function `math.copysign`, which copies the sign of the second argument into the first:

```pycon
>>> from math import copysign

>>> copysign(10, 1)
10.0
>>> copysign(10, -1)
-10.0
>>> copysign(10, -1234134.1235143)
-10.0

>>> copysign(10, 0)
10.0
>>> copysign(10, -0.0)
-10.0
```

So you can also use it to identify negative zero:

```py
from math import copysign

def is_negative_zero(number):
    return number == 0 and copysign(1, number) == -1
```

If you come up with a different way to identify negative zero, comment below and I'll add it here!
