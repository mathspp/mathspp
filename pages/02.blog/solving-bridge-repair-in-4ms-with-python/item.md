Solving “Bridge Repair”, from day 7 of Advent of Code 2024, in 4ms with Python with a simple deductive algorithm.

===


# Solving “Bridge Repair” in 4ms with Python

Today I solved the problem from [Advent of Code 2024, day 7](https://adventofcode.com/2024/day/7) in 4ms using Python.
My first solution used brute-force and ran in 15 seconds.
It was very easy to implement and I got the job done quite quickly.
But then I thought about using a smarter algorithm, and I got a solution that's over 4,000 times faster and that runs in under 4ms.
I will describe both solutions in this article.

(It should be obvious by now, but this article contains spoilers!)

## Problem statement

In short, given a target integer and a list of integers, can we use addition, multiplication, and concatenation, to manipulate the list of integers to equal the target integer?
There is the added restriction that the operations are always evaluated from left to right and the integers cannot be reordered.

Here are some examples that are possible:

```
5: 2 3
6: 2 3
23: 2 3
94: 2 4 9 5
```

To make $5$ with $2$ and $3$ we can do $5 = 2 + 3$.
To make $6$ with $2$ and $3$ we can multiply the two numbers and to make $23$ with $2$ and $3$ we can concatenate the two digits.
Finally, to make $94$ with the integers $2$, $4$, $9$, and $5$, we start by multiplying, then we concatenate, and then we add.
If we use $||$ to represent concatenation, then $94 = ((2 \times 4) || 9) + 5$.


## Brute-force solution, 15 seconds

To determine if a certain list of numbers can be manipulated to match the target value, we can write a function that generates all distinct sequences of operators and then tries to use each sequence of operators in turn.

Using `itertools.product`, we can generate all sequences of distinct operators quite easily:

```py
from itertools import product
from operator import add, mul

valid_operators = [
    add,
    mul,
    lambda x, y: int(f"{x}{y}")
]

def is_valid(target, operands):
    operator_sequences = product(valid_operators, repeat=len(operands) - 1)
    start, *head = operands
    for operators in operator_sequences:
        total = start
        for operator, operand in zip(operators, head):
            total = operator(total, operand)
        if total == target:
            return True
    return False

print(is_valid(94, [2, 4, 9, 5]))
```

Advent of Code provides an [input file with 850 tests](./input.txt) and this code takes slightly over 15 seconds to classify all 850 tests on my machine.
Now, I will show you how you can speed this up by a factor of 2,000.


## Deducing valid operations, 4 milliseconds

The faster algorithm is conceptually very simple.
Disappointingly simple.
Instead of working from the front to the back, trying random operations and seeing if they work out in the end, we work from the back to the front.

For example, consider the target `94` and the list of integers `[2, 4, 9, 5]`.
If this is a possible case, then the final operation must be an addition.
Why?

The last operation cannot be concatenation because the last number in the list is `5` and `94` does not end with `5`.
The last operation also cannot be multiplication because `94` isn't a multiple of `5` and all steps must be integers.
So, if this is a possible case, then the final step must be an addition.

This reduces our problem a bit, because now we undo this final step and our target is now `94 - 5`, or `89`, and our list of numbers is `[2, 4, 9]`.
This time, we know the possible operations are addition and concatenation, so we have to try those two out.
To explore the concatenation route, the new target would be the `89` without the final `9`, so it would be only `8` and the list would be `[2, 4]`.
If we tried the addition route again, the new target would be `89 - 9` and the list would also be `[2, 4]`.

We implement this algorithm in a recursive function that starts at the end of the list of numbers and works up to the front.
Initially, my function looked like this:

```py
def is_valid(target, operands):
    if target < 0:
        return False
    elif target == 0:
        return not operands
    elif not operands:
        return False

    last_op = operands[-1]
    head = operands[:-1]

    if is_valid(target - last_op, head):  # addition
        return True

    if target % last_op == 0:  # multiplication
        if is_valid(target // last_op, head):
            return True

    if str(target).endswith(str(last_op)):  # concatenation
        if is_valid(target // pow(10, len(str(last_op))), head):
            return True

    return False
```

The three base cases say the following:

- If `target` is negative then it's not valid, since we're only ever working with positive integers.
- If `target` is zero, then it's valid if we used up all operands.
- If there are no operands left, then it's not valid.

Then, we try each operation in turn, making sure to only try the operations that work.

The function `is_valid`, as seen above, takes roughly 7ms to go over [the 850 tests from my input file](./input.txt).
Then, I realised it would probably better to start by trying the operations that are least likely to work, and I inverted the order of the operations to try concatenation first, then multiplication, then addition:

```py
def is_valid(target, operands):
    if target < 0:
        return False
    elif target == 0:
        return not operands
    elif not operands:
        return False

    last_op = operands[-1]
    head = operands[:-1]

    if str(target).endswith(str(last_op)):  # concatenation
        if is_valid(target // pow(10, len(str(last_op))), head):
            return True

    if target % last_op == 0:  # multiplication
        if is_valid(target // last_op, head):
            return True

    if is_valid(target - last_op, head):  # addition
        return True

    return False
```

This cut the execution time from 7ms to 5ms.
Then, I noticed the asymmetry between concatenation/multiplication and addition, since addition is always being tested without verifying if it's legal.
When I added the legality check, I realised I could also tweak the base cases at the top of the function:

```py
def is_valid(target, operands):
    if len(operands) == 1:  # <-- changed
        return target == operands[0]
    elif not operands:
        return False

    last_op = operands[-1]
    head = operands[:-1]

    if str(target).endswith(str(last_op)):  # concatenation
        if is_valid(target // pow(10, len(str(last_op))), head):
            return True

    if target % last_op == 0:  # multiplication
        if is_valid(target // last_op, head):
            return True

    if target > last_op:  # <-- added
        if is_valid(target - last_op, head):  # addition
            return True

    return False
```

This new version runs through all tests in under 4ms, so I almost cut down in half the execution time of the already-much-faster solution!
I was quite pleased with the final result and that's why I decided to share this with you.

If you want to join me, and others, in solving Advent of Code in Python, or if you just want to improve your algorithmic knowledge and Python skills, [join me at the Python Mastery Guild community](https://skool.com/python-mastery).
I'll see you inside!
