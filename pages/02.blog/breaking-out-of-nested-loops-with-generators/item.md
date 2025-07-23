You can use generators to simplify nested loops and make it easier to break out of them.

===


The keyword `break` is used to break out of the enclosing loop.
So, if you have a nested loop like the one shown below, how can you break out of the _two_ loops as soon as you find the number 3?

```py
def this_is_the_one(x):
    return x == 3

my_list = [[1, 2], [3, 4], [5, 6]]
for sublist in my_list:
    for element in sublist:
        print(f"Checking {element}")
        if this_is_the_one(element):
            # ...?
```

Again, `break` won't be of much help because it will only break out of the inner `for` loop.
In fact, if you put the `break` there and run the code, you will see we still process the sublist `[5, 6]`:

```py
for sublist in my_list:
    for element in sublist:
        print(f"Checking {element}")
        if this_is_the_one(element):
            break

"""Output:
Checking 1
Checking 2
Checking 3
Checking 5
Checking 6
"""
```

There are multiple ways to work around this difficulty and in this short article I want to show you how to use generators to do so.
The idea is that you'll factor out the nested loop into a separate generator, and then use that generator in place of the original loops.
If you factor out _all_ loops into the generator, you'll be left with a single loop in place of the original nested loops, and you can easily break out of that single loop.

In the case of the previous example, you'd start by defining the generator function that encapsulates the looping logic:

```py
def elements_from_sublists(my_list):
    for sublist in my_list:
        for element in sublist:
            yield element
```

Then, you'd use the generator in the original piece of code:

```py
for element in elements_from_sublists(my_list):
    print(f"Checking {element}")
    if this_is_the_one(element):
        break

"""Output:
Checking 1
Checking 2
Checking 3
"""
```

Now we only have a single loop, so the keyword `break` works perfectly.
This strategy of factoring out looping logic into a generator can be quite useful, so keep an eye out for opportunities to use it!

**Bonus brain points**: the example above contained a very simple example that showed you the gist of the technique.
In this particular instance, there was an even better alternative already in the standard library, so that you wouldn't have to define your own generator function:

```py
from itertools import chain

for element in chain.from_iterable(my_list):
    print(f"Checking {element}")
    if this_is_the_one(element):
        break
```

`chain` and `chain.from_iterable` can be quite convenient, so make sure you know how to use both!
