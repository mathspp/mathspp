The built-in function max in Python is broken and this article explains why, drawing parallels with other programming and mathematics concepts.

===


# `max` is broken

## The default values

Did you ever notice that in Python, you have:

```py
sum([]) == 0
all([]) == True
any([]) == False
math.prod([]) == 1
```

Notice that the functions `sum`, `all`, `any`, and `math.prod` all have one thing in common:
they take a list of things and [reduce it into a single result](/blog/pydonts/the-power-of-reduce).
In all four cases above, if we pass the function an empty list we get a default value back.

However, `max` is also a reduce function and `max` doesn't have a nice default value.
Instead, it throws an error:

```py
max([])  # ValueError
```

But why?
Isn't there a suitable default value for `max`?

From a mathematical point of view, there is a suitable default value for `max`, so the fact that the function raises an error instead of returning it is wrong and that is why I claim that `max` is “broken”.
For `max` to be mathematically correct, the default value for `max` should be negative infinity, which you can get with `float("-inf")`:

```py
max([]) == float("-inf")
```

So, why does Python raise an error instead of being mathematically correct?
I'm guessing Python goes with the “practicality beats purity” approach and prefers to raise an error instead of returning infinity values to users, given that infinity can be quite an exotic thing...
Especially if we consider that `max` should return _negative_ infinity and `min` should return infinity, which are different things.

(Right, I forgot to tell you but `min` is also broken!)

But why “should” the return values be (negative) infinity?
That's because all of these reduction functions (`sum`, `any`, `all`, `math.prod`, `max`, and `min`) should return the identity element for their respective operations.

 - `sum` performs addition and 0 is the identity element for addition.
 - `any` performs the Boolean operation “or” and False is the identity element for “or”.
 - `all` performs the Boolean operation “and” and True is the identity element for “and”.
 - `math.prod` performs multiplication and 1 is the identity element for multiplication.

Likewise, the identity element for the `max` operation is `float("-inf")` and the identity element for the `min` operation is `float("inf")`.

How can you know that `float("-inf")` is the identity value for the operation `max`?
Try to come up with a numerical value for `x` such that `max(float("-inf"), x)` is _different_ from `x`.
I bet you can't, and that's why `float("-inf")` is the identity value for the operation `max`; because `max(float("-inf"), x) == x` for any number `x`.

Do you get what I'm saying?
Feel free to leave a comment below if you don't!


## Fixing `max`

To fix `max` in a mathematical sense you'll need to set its `default` parameter to `float("-inf")`.
The version below uses [`functools.partial`](/blog/functools-partial) to freeze the parameter `default`:

```py
from functools import partial

pedantic_max = partial(max, default=float("-inf"))
```
