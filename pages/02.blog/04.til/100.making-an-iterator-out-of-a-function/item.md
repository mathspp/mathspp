You can use the Python built-in function `iter` with two arguments to create an iterator from a function.

===


# Making an iterator out of a function

Today I (re)learned that the built-in function `iter` has a variant with two arguments that lets you turn any 0-argument function into an iterator.
The version `iter(function, value)` will create an iterator that returns the successive return values of calling `function` until one of them matches `value`, at which point the iterator is done.

For example, using `input`, we can create a loop that runs while the user is typing input.
When the user types “exit”, the loop stops:

```py
for user_str in iter(input, "exit"):
    # Process user input...
    print(f"User typed {user_str!r}.")
```

Try copying and pasting the code above into a REPL and type a couple of lines to see the loop in action.

The documentation shows another example usage of `iter` in this way to create a “chunk reader”, for example to read chunks from a socket:

```py
from functools import partial

for block in iter(partial(sock.recv, 64), b""):
    # Process block of 64 bytes...
```

The loop above will read chunks of 64 bytes from the socket until there is nothing to be read.

Another example usage would be in the context of simulations, where you want to run your simulation until a random number hits a specific value.
For example, roll a die until you roll a 6:

```py
from random import randint

def roll():
    return randint(1, 6)

for die_roll in iter(roll, 6):
    # Process die roll.
```


## Python model of the built-in `iter` with two arguments

The built-in `iter` with two arguments is more or less equivalent to this generator function:

```py
def iter(func, target_value):
    while target_value != (value := func()):
        yield value
```


## More examples of using `iter` with two arguments

If you come up with more example usages of `iter` with two arguments, please comment below (or [reach out to me](/contact)) so I can add them here!
