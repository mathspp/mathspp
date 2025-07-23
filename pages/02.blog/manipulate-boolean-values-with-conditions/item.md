In this article I explore a common code smell related to conditionals and Boolean values and show how to fix it.

===


## The code smell with `return`

Consider the function `is_even` shown below.

```py
def is_even(number):
    if number % 2 == 0:
        return True
    else:
        return False

print(is_even(2))  # True
print(is_even(3))  # False
```

What's the code smell it shows?

The code smell is that it uses an `if` statement to manipulate the Boolean value that we will return from the function.
I'll explain in more detail what I mean, but it'll be easier if I show you the improved version:

```py
def is_even(number):
    return number % 2 == 0

print(is_even(2))  # True
print(is_even(3))  # False
```

Notice how we took the condition from the `if` statement and just plugged it into the `return`, there is no need for an actual `if` statement.

What people sometimes forget is that the condition of the `if` statement is actually an expression:
it's a piece of code that produces an actual value that you can use.
So, whenever you have an `if` statement to pick a Boolean value to return, you can rewrite the return to include the condition(s).

I hope this is making some sense.
I have another example and an exercise for you, to make sure it is.


## The code smell with assignments

The code smell we're talking about isn't restricted to `return` statements.
Sometimes, the code smell shows itself in assignments like the one below:

```py
def morning_routine(rodrigo, day):
    if day in {"mon", "tue", "wed", "thu", "fri"}:
        weekday = True
        weekend = False
    else:
        weekday = False
        weekend = True

    rodrigo.shower()
    if weekday:
        rodrigo.shave()
    rodrigo.walk_pet()
    # ...
```

The point here is that we're still using an `if` statement to manipulate Boolean values instead of using the Boolean values directly.
For instance, we could assign to `weekday` directly and then set `weekend` to the negation of the value in `weekday`:

```py
def morning_routine(rodrigo, day):
    weekday = day in {"mon", "tue", "wed", "thu", "fri"}
    weekend = not weekday

    rodrigo.shower()
    if weekday:
        rodrigo.shave()
    rodrigo.walk_pet()
    # ...
```

Maybe we don't even need the variable `weekend`!
(Or maybe we do, it would depend on the remainder of the body of the function `morning_routine`.)


## Exercise

### Function to rewrite

Consider the function `is_ordered_triple`, shown below:

```py
def is_ordered_triple(tup):
    if len(tup) == 3:
        if tup[0] <= tup[1] and tup[1] <= tup[2]:
            return True
        else:
            return False
    else:
        return False

print(is_ordered_triple((1, 2)))  # False
print(is_ordered_triple((1, 3, 2)))  # False
print(is_ordered_triple((1, 2, 3)))  # True
```

Can you rewrite it in the best way possible, and specifically addressing the code smell I discussed above?
After you're done, add your solution to the comments below!


### Solution

The first thing you can do is rewrite the inner `if` statement so that we return the Boolean value directly:

```py
def is_ordered_triple(tup):
    if len(tup) == 3:
        return tup[0] <= tup[1] and tup[1] <= tup[2]
    else:
        return False

print(is_ordered_triple((1, 2)))  # False
print(is_ordered_triple((1, 3, 2)))  # False
print(is_ordered_triple((1, 2, 3)))  # True
```

Then, we can [chain comparison operators](/blog/pydonts/chaining-comparison-operators) to make the condition slightly shorter:

```py
def is_ordered_triple(tup):
    if len(tup) == 3:
        return tup[0] <= tup[1] <= tup[2]
    else:
        return False

print(is_ordered_triple((1, 2)))  # False
print(is_ordered_triple((1, 3, 2)))  # False
print(is_ordered_triple((1, 2, 3)))  # True
```

Finally, we can recognise that we're looking at the _same_ code smell _again_!

Notice how we're using an `if` statement to pick a Boolean value to return!
Instead, we can use the condition of the `if` statement (the code `len(tup) == 3`) and add it to the `return` statement:

```py
def is_ordered_triple(tup):
    return len(tup) == 3 and tup[0] <= tup[1] <= tup[2]

print(is_ordered_triple((1, 2)))  # False
print(is_ordered_triple((1, 3, 2)))  # False
print(is_ordered_triple((1, 2, 3)))  # True
```

If you're not sure how/why this works, you may want to read up on [Boolean short-circuiting](/blog/pydonts/boolean-short-circuiting), a very common technique in many programming languages.
