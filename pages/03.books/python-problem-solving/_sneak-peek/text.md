Here's a taste of one of the many challenges you'll tackle:

```python
# Challenge: given a list of numbers, return a new list where each element
# is the product of all the other numbers except the one at that position.

def products_except_self(nums):
    result = []
    for i in range(len(nums)):
        product = 1
        for j, n in enumerate(nums):
            if i != j:
                product *= n
        result.append(product)
    return result

# How would you improve this?  The book explores multiple solutions and their trade‑offs.
```

In the book you'll see variations that precompute prefixes and suffixes, use Python's built‑in functions and even leverage itertools – all while discussing their pros and cons.