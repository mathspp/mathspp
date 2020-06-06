---
title: Py-don't abuse the walrus operator
slug: walrus-operator
date: 17-03-2020
published: false
taxonomy:
    tag: [pydont, python, programming]
---

Don't use the walrus operator `:=` in convoluted ways!

```py
import sys

if (i := input())[0] == "q" or i == "exit":
    sys.exit()
```

This snippet of code stops the program if the input starts with a `"q"` or if the input is `"exit"`. But does it look good?

===

The walrus operator is, at the time of writing, a fairly recent addition to Python (you can read about its introduction to the language in the [PEP 572][pep-572]). This means people are still getting used to it and haven't really grasped how to use it well. Again, [PEP 572][pep-572] includes a really nice explanation of why the walrus operator was introduced in Python and provides a fair share of really good use cases.

The pythonic version of the code above would be

```py
import sys

i = input()
if i[0] == "q" or i == "exit":
    sys.exit()
```

It uses one more line but it does keep it much more readable. Not only [readability counts][zen-of-python] but this is also a case where one can argue that [beautiful is better than ugly][zen-of-python]!

Personally, I think the walrus operator `:=` is particularly useful when we want to write a `while` loop where the body uses something that is also used in the loop condition, which also needs an initialization step. For example, say you want to write a simple loop that checks if the input line is not empty and, in that case, evaluate it as Python code. Without using `:=` there are a couple of decent alternatives, like

```py
inp = input()
while inp:
    eval(inp)
    inp = input()
```

or

```py
while True:
    inp = input()
    if not inp:
        break
    eval(inp)
```

but none of those snippets beat this alternative implementation that is shorter, more expressive and much more elegant:

```py
while (inp := input()):
    eval(inp)
```

Below you can find a couple of other examples of interesting use cases of `:=`, taken and/or adapted from [PEP 572][pep-572].

#### Look for a "witness" in a list

```py
import random
random.seed(0)  # for reproducibility

ints = [random.randint(0, 100) for _ in range(100)]
if any((witness := elem) % 10 == 0 for elem in ints):
    print(f"{witness} is the first multiple of 10 in the list!")
else:
    print("No multiples of 10 found.")
```

This snippet prints `100 is the first multiple of 10 in the list!`, because it goes through the randomly generated list (`[49, 97, 53, 5, 33, 65, 62, 51, 100, 38, ...]`) and searches for a multiple of 10. The first multiple found is `100`.

#### Track changes in a list comprehension

```py
import random
random.seed(0)  # for reproducibility

some_list = [random.randint(-100, 100) for _ in range(50)]
total = 0
partial_sums = [total := total + elem for elem in some_list]
print(partial_sums)
```

This snippets prints `[-2, 92, 99, 9, -25, ...]` which are the first $5$ partial sums of `some_list`, whose values are `[-2, 94, 7, -90, -34, ...]`.

#### Reuse potentially expensive computations

```py
def fib(n):
    """This could be made more efficient in many different ways!"""
    if n <= 1:
        return n
    else:
        return n * fib(n-1)

n = 17
print(f"Fib {n} is {(f:=fib(n))} and fib {n+1} is {(n+1)*f}")
```

This snippet prints `Fib 17 is 355687428096000 and fib 18 is 6402373705728000`, corresponding to $17!$ and $18! = 18 \times 17!$.

---

If you find a situation where you really wanted to use the walrus operator but it doesn't really fit well with the surrounding code... Then maybe that is not the time nor the place! [Don't disrespect the Zen of Python][zen-of-python]!

Did this make any sense? Let me know in the comment section below!

[zen-of-python]: ../zen-of-python
[pep-572]: https://www.python.org/dev/peps/pep-0572
[pydont]: ../.