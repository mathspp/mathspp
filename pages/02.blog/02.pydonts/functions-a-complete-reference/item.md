This article serves as a complete reference for all the non-trivial things you should know about Python functions.

===

![](thumbnail.webp)

# Functions: a complete reference

There is a lot to learn about functions after you learn how to define a function.
In fact, knowing how to use the keyword `def` is just the first step, really.
I will walk you through the remaining steps in this article.

In this Pydon't, you will learn:

 - what should go into a function and what shouldn't;
 - the difference between parameters and arguments;
 - a good heuristic to help you order your parameters;
 - how to force a function to accept positional-only and/or argument-only arguments;
 - that you can inspect a function signature dynamically;
 - how to avoid mutability issues with default arguments;
 - to define functions that accept arbitrary numbers of positional and/or keyword arguments;
 - about anonymous functions and the keyword `lambda`;
 - to leverage the fact that functions are objects in their own right;

 - decorators
 - currying and partial
 - closures
 - late binding
 - partials
 - late binding?
 - generators (yield)

<!--v-->
!!! You can get all the Pydon'ts as a [free ebook with over +400 pages and hundreds of tips](/books/pydonts). [Download the ebook “Pydon'ts – write elegant Python code” here](/books/pydonts).
<!--^-->


## What goes into a function and what doesn't

Do not overcrowd your functions with logic for four or five different things.
A function should do a single thing and it should do it well.
And the name of the function should clearly tell you what your function does.

If you are unsure about whether some piece of code should be a single function or multiple functions, I think it's best to err on the side of too many functions.
That is because a function is a modular piece of code, and the smaller your functions are, the easier it is to compose them together to create more complex behaviours.

Now I am going to illustrate the point with an exaggerated example.
Hopefully, the exaggeration helps you understand the point I want to make.
Consider the function `process_order` defined below.
While it is not incredibly long, it does too many things:

```py
def process_order(order):
    # Validate the order:
    for item, quantity, price in order:
        if quantity <= 0:
            raise ValueError(f"Cannot buy 0 or less of {item}.")
        if price <= 0:
            raise ValueError(f"Price must be positive.")

    # Write the receipt:
    total = 0
    with open("receipt.txt", "w") as f:
        for item, quantity, price in order:
            # This week, yoghurts and batteries are on sale.
            if "yoghurt" in item:
                price *= 0.8
            elif "batteries" in item:
                price *= 0.5
            # Write this line of the receipt:
            partial = price * quantity
            f.write(f"{item:>15} --- {quantity:>3} x {price:>6.2f} : ${partial:>6.2f}\n")
            total += partial
        f.write(" " * 27 + f"Total : ${total:>6.2f}\n")

order = [
    ("greek yoghurt", 2, 4.57),
    ("AAA batteries", 1, 9.99),
    ("AA batteries", 1, 8.99),
    ("milk", 3, 1.13),
    ("rice", 2, 1.45),
]
process_order(order)
```

If you run this code, the file `receipt.txt` will look like this:

```txt
  greek yoghurt ---   2 x   3.66 : $  7.31
  AAA batteries ---   1 x   5.00 : $  5.00
   AA batteries ---   1 x   4.50 : $  4.50
           milk ---   3 x   1.13 : $  3.39
           rice ---   2 x   1.45 : $  2.90
                           Total : $ 23.09
```

Suppose that the week changes and the products on sale are different.
It is going to be a bit painful to find the code that computes the discounts.
And, to be completely honest, the current logic is terrible.
Maybe you should store discounted products somewhere and compute the discounted prices with the help of that information?

If you split the function `process_order` into modular subfunctions, this becomes simpler:

```py
def validate_order(order):
    for item, quantity, price in order:
        if quantity <= 0:
            raise ValueError(f"Cannot buy 0 or less of {item}.")
        if price <= 0:
            raise ValueError(f"Price must be positive.")


def apply_discounts(order):
    discounted_prices = []
    for item, quantity, price in order:
        # This week, yoghurts and batteries are on sale.
        if "yoghurt" in item:
            price *= 0.8
        elif "batteries" in item:
            price *= 0.5
        discounted_prices.append((item, quantity, price))
    return discounted_prices


def format_partial_receipt_line(item, quantity, price):
    partial = quantity * price
    return f"{item:>15} --- {quantity:>3} x {price:>6.2f} : ${partial:>6.2f}\n"


def write_receipt(order):
    total = 0
    with open("receipt.txt", "w") as f:
        for item, quantity, price in order:
            line = format_partial_receipt_line(item, quantity, price)
            f.write(line)
            total += quantity * price
        f.write(" " * 27 + f"Total : ${total:>6.2f}\n")


def process_order(order):
    validate_order(order)

    order = apply_discounts(order)
    write_receipt(order)
```

Now that the code is split into more modular functions it becomes much easier to make changes to the code.
For example, the logic to apply discounts is still suboptimal.
Maybe it makes more sense to have a dictionary with the items that currently have a sale and compute the new prices from that:

```py
# ...
CURRENT_DISCOUNTS = {
    "greek yoghurt": 20,
    "light yoghurt": 20,
    "AAA batteries": 50,
    "AA batteries": 50,
}

def apply_discounts(order):
    discounted_prices = []
    for item, quantity, price in order:
        price *= 1 - CURRENT_DISCOUNTS.get(item, 0) / 100
        discounted_prices.append((item, quantity, price))
    return discounted_prices
# ...
```

What if the receipt should be printed in a different format?
All you have to do is change the function `write_receipt` and all other functions can stay the same.
You get the point.

The example I showed you was of a function that was doing too many things that go towards the same goal.
Here, the example was processing an order.
Sometimes, you want to add code to a function that is helpful to the developer or the user, but that is not directly related to the original goal of the function.
For example, you may want to add logging to a function, or a cache, or profiling.
When you want to add these behaviours that are _orthogonal_ to the objective of the function, you will want to [use a decorator](#decorators).


## The function signature

### `*args` and `**kwargs`

Built-in functions like `max` accept a variable number of positional arguments:

```pycon
>>> max(1, 2)
2
>>> max(1, 2, 3)
3
>>> max(1, 2, 3, 4)
4
```

And built-ins like `dict` accept a variable number of keyword arguments:

```pycon
>>> dict(a=1)
{'a': 1}
>>> dict(a=1, b=2)
{'a': 1, 'b': 2}
```

To be able to define functions with this kind of functionality, you need to learn about `*args` and `**kwargs`.
If a function signature includes a parameter with the asterisk on its left, like `*args`, then that function accepts a variable number of positional arguments and those arguments are collected in a tuple:

```py
def f(a, b, *args):
    assert isinstance(args, tuple)
    print(a, b, args)

f(1, 2)  # 1 2 ()
f(1, 2, 3)  # 1 2 (3,)
f(1, 2, 3, 4)  # 1 2 (3, 4)
```

The special syntax here is the asterisk `*` next to the parameter name.
The function `f` would work in the same way if you wrote `*variable_positional_args` or `*bananas`.

Similarly, if a function signature includes a parameter with two asterisks on its left, like `**kwargs`, then that function accepts a variable number of keyword arguments and those arguments are collected in a dictionary:

```py
def f(a, b, **kwargs):
    assert isinstance(kwargs, dict)
    print(a, b, kwargs)

f(a=1, b=2)  # 1 2 {}
f(a=1, b=2, c=3)  # 1 2 {'c': 3}
f(a=1, b=2, c=3, d=4)  # 1 2 {'c': 3, 'd': 4}
```

Like with `*args`, `**kwargs` is a convention that we follow in the Python world.
The parameter could have any other name, as long as it had the two asterisks on its left.

`*args` and `**kwargs` can be present in the same function signature but the variable positional arguments must always come first.
When it comes to ordering parameters, it is also worth mentioning that `*args` must come after all other positional arguments and `**kwargs` must be the last parameter of your function signature.


### Positional-only and keyword-only arguments

If you want to write a function signature for which one or more parameters can only be used as positional parameters, then you might use a forward slash `/` in the signature, as if it were a parameter.
All parameters to the left of the forward slash are automatically positional-only:

```py
def f(a, /, b):
    return a + b

print(f(1, 2))  # 3
print(f(1, b=2))  # 3
print(f(a=1, b=2))  # TypeError
```

You will not need to use the forward slash `/` in the parameter list very often, but it's mostly useful when the names of the parameters are not meaningful and it's the position that is relevant.

You can do a similar thing to force some parameters to accept only arguments by keyword.
If you include an asterisk `*` in the parameter list of a function signature, all parameters to the right of the asterisk will only accept keyword arguments:

```py
def f(a, *, b):
    return a + b

print(f(a=1, b=2))  # 3
print(f(1, b=2))  # 3
print(f(1, 2))  # TypeError
```

Using `*` in your parameter list is more common than using `/`.
A good use case for `*` is when your function has some parameters that modify or configure the main purpose of your function.
When that is the case, forcing those parameters to be keyword-only helps document the function call.

For example, the built-in function `pow` has three parameters:

```py
# They're the same thing:
print(pow(2, 32) % 997)  # 966
print(pow(2, 32, 997))  # 966
```

The reason `pow` has the third parameter `mod` is because there is an efficient algorithm to compute expressions like `(base ** exp) % mod`, and writing `pow(base, exp) % mod` does not make use of that algorithm.
To increase the readability of function calls like `pow(2, 32, 997)`, you could've defined the function `pow` like this:

```py
def pow_(base, exp, *, mod=None):
    return pow(base, exp, mod)

print(pow_(2, 32, mod=997))  # 966
```

In the snippet above, the function call becomes clearer because you were forced to use the parameter name when passing the argument `997` in.


### Mutable default arguments

A common gotcha with Python functions is using mutable values as default arguments.

When you define a default value for an argument, that value is evaluated only once when the function is defined, which means the same value is used whenever the function is called.
If that value is mutable, weird things can happen:

```py
def put_in_list(value, list_to_put_into=[]):
    list_to_put_into.append(value)
    return list_to_put_into

my_list = put_in_list(3)  # Put 3 in a new list.
print(my_list)  # [3]
my_list = put_in_list(4, my_list)  # Put 4 in that list.
print(my_list)  # [3, 4]

new_list = put_in_list(5)  # Put 5 in a new list.
print(new_list)  # [3, 4, 5] ?!
print(my_list)  # [3, 4, 5] ?!
```

This happens because the list that you set as the default value is a single list that is associated with your function:

```py
print(put_in_list.__defaults__)  # ([3, 4, 5],)
default_list = put_in_list.__defaults__[0]
print(default_list is my_list)  # True
```


### (Type) annotations

Function signatures support a feature called annotations.
These annotations let you add metainformation to each parameter by adding a colon `:` and then the metadata you want:

```py
def pow_(base, exp, *, mod: "modulus to use for powermod algorithm" = None):
    return pow(base, exp, mod)
```

The code above is perfectly valid Python code.
Until the rise of static type checking in Python, these parameter annotations were seldom used.
Nowadays, they are commonly used in projects that use static type checkers, like mypy or pyright.

On top of being possible to annotate parameters, you can also annotate the whole function signature by adding an arrow `->` after the closing parenthesis:

```py
def foo() -> "This is another annotation":
    pass
```

Within the context of type checking and type annotations, the annotations after the parameters are used to indicate the type of each parameter and the annotation after the function signature is used to annotate the type of the function return value.

For example, here is [the type annotations for the built-in function `hash`](https://github.com/python/typeshed/blob/7ab6620c0f37e07486a2944460f477336c925618/stdlib/builtins.pyi#L1454):

```py
def hash(obj: object, /) -> int:
    ...
```

The type annotations say that the built-in `hash` accepts an object (which is very generic on purpose) and returns an integer result.


### The signature object

Python is usually known for its introspection capabilities, and the module `inspect` provides a function `signature` you can use if you need to retrieve information about the signature of a function.

This isn't something you would use very commonly, so I included a snippet below that only shows a small example of the information that `inspect.signature` provides:

```py
from inspect import signature

def my_join(left: str, right: str, sep: str = " ") -> str:
    return left + sep + right

sig = signature(my_join)
print(sig)  # (left: str, right: str, sep: str = ' ') -> str
print(type(sig))  # <class 'inspect.Signature'>

print(sig.parameters)
# OrderedDict({
#     'left': <Parameter "left: str">,
#     'right': <Parameter "right: str">,
#     'sep': <Parameter "sep: str = ' '">
# })

print(sig.parameters["sep"])  # sep: str = ' '
print(type(sig.parameters["sep"]))  # <class 'inspect.Parameter'>

print(sig.parameters["sep"].kind)  # POSITIONAL_OR_KEYWORD
print(sig.parameters["sep"].default == " ")  # True
```

The snippet above shows that there are two classes `Signature` and `Parameter` that you can play around with and that they have some useful attributes (and methods, of which I showed none).
You can [read the documentation](https://docs.python.org/3/library/inspect.html#inspect.signature) or [the PEP that introduced `inspect.signature`](https://peps.python.org/pep-0362/) for more information.

### Ordering the parameters

To close the subsection on the function signature, I wanted to share a rule of thumb that can help you determine how to order the parameters in your function signature.
Talking about the “correct ordering of your function parameters” is a bit of a pedantic discussion to have, but sometimes it makes things easier when you're using things like closures or partial functions.

Order your function parameters by how likely the caller is to change them.
Parameters that are more likely to remain the same across function calls go first and parameters that are more likely to change across function calls go last.

For example, the Portuguese government has a platform where I can check all of the receipts I have issued as a trainer during a specific time period.
They are likely to have an endpoint, somewhere, that accepts a taxpayer number, a time interval start date, and a time interval end date, and the endpoint retrieves all of the receipts for that taxpayer within that timeframe.

Their endpoint was either defined as:

```py
def fetch_receipts(taxpayer, start_date, end_date):
    ...
```

or

```py
def fetch_receipts(start_date, end_date, taxpayer):
    ...
```

What I am claiming is that you will benefit from following the first approach.
Why?
It is much more common to have a user log in and fetch receipts for different time frames than it is for a group of users to log in, one at a time, to fetch receipts from the exact same time frame.

Opting for `fetch_receipts(taxpayer, start_date, end_date)` is not only more sensible, but also more practical, for example when [using partial functions with `functools.partial`](/blog/functools-partial).


## Anonymous functions

The standard way to define a function is with the `def` keyword.
This sets up a function definition statement that creates a function and then assigns it to the name you pick.
For example, the snippet below creates a function that clips the first argument to be within the other two and binds that function to the name `clip`:

```py
def clip(number, lower, upper):
    return min(upper, max(lower, number))

print(clip(17, 0, 10))  # 10
```

However, there are some situations in which it is useful to have something lighter that doesn't require a statement.
That is what the keyword `lambda` is for.
By using the keyword `lambda`, you can write an expression that evaluates to a function.

Just like the expression `1 + 4` evaluates to the integer `5`, the expression `lambda x: x + 1` evaluates to the function that accepts an argument `x` and returns `x + 1`:

```py
print(lambda x: x + 1)  # <function <lambda> at 0x1051efc40>
```

Since the code `lambda x: x + 1` _is_ a function (the whole thing), you can use parenthesis to call it.
However, if you write `lambda x: x + 1()`, Python will think you are trying to call `1`, so you need to wrap the anonymous function in parenthesis, too:

```py
print((lambda x: x + 1)(4))  # 5
```

Since you can use the keyword `lambda` to create a function and use it without ever giving it a name, you call `lambda x: x + 1` an “anonymous function”.


### Use case rule of thumb

The point of anonymous functions isn't to let you write code like `print((lambda x: x + 1)(4))`, though, but an extensive treatment of the use cases for anonymous functions is both subjective and outside the scope of this reference.
As a good rule of thumb, you'll want to use anonymous functions to create one-off functions to be used within higher-order functions.
To understand how (anonymous) functions can be used within higher-order functions, read the section below.


### The body of an anonymous function

Anonymous functions, created with the keyword `lambda`, can only contain a single expression in their body.
If you need to use statements, such as assignment, error handling, conditional statements, and loops, you need to define a function with the statement `def`.

Constructs that are valid inside anonymous functions include, but are not limited to:

- [conditional expressions](/blog/pydonts/conditional-expressions);
- standard arithmetic and comparison operations;
- calling other functions or methods;
- [list comprehensions](/blog/pydonts/list-comprehensions-101); and
- [assignment expressions](/blog/pydonts/assignment-expressions-and-the-walrus-operator).

As a direct consequence of this, you _cannot_ write an anonymous function with an empty body because the keyword `pass` is a statement.
The “best” you can do, from a readability point of view, is to define the function with an ellipsis in its body: `lambda x: ...`.
That may look like an empty anonymous function to you and me, but this is a regular lambda function:

```py
print( (lambda x: ...)(73) )  # Ellipsis
```


### Signature

What you learned about function signatures, namely [the usage of `*args` and `**kwargs`](#args-and-kwargs), the [issue with mutable default arguments](#mutable-default-arguments), and how to define [positional-only and keyword-only parameters](#positional-only-and-keyword-only-arguments), also apply to the signature of a lambda function.

The definition of the signature of a lambda function does not require the parenthesis around the parameter list, but everything else is the same.
So, the functions below all have valid signatures:

```py
lambda: ...  # Takes no arguments.
lambda a, b, c: ...  # Takes 3 arguments.
lambda *args: ...  # Takes an arbitrary number of arguments.

lambda x, y=3: ...  # `y` has a default value.
lambda **kwargs: ...  # Takes arbitrary keyword arguments.

lambda x, y, *args, debug, **kwargs: ...  # Takes 2 positional arguments
# `x` and `y`, 0 or more extra positional arguments, a keyword-only argument
# called `debug`, and 0 or more extra keyword arguments.

lambda x, y, /: ...  # `x` and `y` are positional-only arguments.
lambda *, x, y: ...  # `x` and `y` are keyword-only arguments.
lambda x, /, y, *, z: ...  # `x` is positional-only, `y` can be whatever you want,
# and `z` is keyword-only.
```


## Functions as objects

Python is far from being a perfect language, but if there is something I like about Python is its consistency.
In Python, everything is an object.
And when I say everything, I mean _everything_.
Ok, everything except keywords.
But everything else is an object!
Even the ellipsis:

```py
x = ...
print(x)  # Ellipsis
print(type(x))  # <class 'ellipsis'>
print(id(x))  # 4400502840
```

Functions are also objects, which means you can manipulate them in the same way you can manipulate any other values.
You can assign other variable names to functions.
You can create lists of functions.
You can have dictionaries that map to functions.
If you already know this, then you know, but this is of the utmost importance because it lets you write cleaner code in some scenarios, of which I will list some.


### Don't wrap functions in anonymous functions

The built-ins `sorted`, `max`, and `min`, accept a keyword argument `key` that turns these three built-ins into three very versatile built-ins.
The keyword argument `key` is supposed to be a function that gets applied to the values you passed to `sorted`/`max`/`min`, and the values that the key function returns are the values that the built-ins `sorted`/`max`/`min` rely on to compute the ordering.

As an illustrative example, let us take the function `max`.
By itself, you think of it as a function that accepts numbers and returns the largest number.
If you're creative enough, you can realise that it will also accept a bunch of strings and return the one that shows up later in the dictionary:

```py
print(max(
    ["cat", "zebra", "lion"]
))  # zebra
```

But if you use the parameter `key` to specify a function that returns the length of a string, you can use `max` to find the longest string in a list of strings:

```py
print(max(
    ["giraffe", "zebra", "rhinoceros"],
    key=lambda string: len(string),
))  # rhinoceros
```

Isn't this cool?
It is!
But also, why is there a `lambda` in your call to `max`?
`len` is a function, which means it is a regular object like any other Python object, and you can refer to it directly and by name without calling it.
You can specify `key=len` and it will still work:

```py
print(max(
    ["giraffe", "zebra", "rhinoceros"],
    key=len,
))  # rhinoceros
```

The example with `key` and `max` is a pretty concrete example, but it should help you realise that you can pass functions around as arguments to other functions.
To take it a step further, you can do the same thing with methods.
For example, suppose you want to reuse `max` to find the last string in a list of strings:

```py
print(max(
    ["ZEBRA", "ant"]
))  # ant
```

Casing matters when performing string comparisons, so [you'll want to use the method `casefold` to perform case-insensitive string comparison](/blog/how-to-work-with-case-insensitive-strings):

```py
print(max(
    ["ZEBRA", "ant"],
    key=lambda string: string.casefold()
))  # ZEBRA
```

Except, again, you can use the method directly.
If `s` is a string, `s.casefold()` is the same thing as `str.casefold(s)`, so you can pass `str.casefold` directly to `key`:

```py
print(max(
    ["ZEBRA", "ant"],
    key=str.casefold
))  # ZEBRA
```


### Don't be afraid to put functions in containers

Another good example of how you can leverage the fact that functions are objects is by putting functions inside containers.
You won't need to do it every day, but it's good to remember that you can do it.
As an example, I will show you two ways in which you can write a function that accepts a string and returns the string in [alternating caps](https://en.wikipedia.org/wiki/Alternating_caps):

```py
print(alt_caps("Hello, world!"))  # HeLlO, wOrLd!
```

We can put the methods `str.upper` and `str.lower` inside a list and then use `itertools.cycle` to cycle through those two functions as you iterate over the characters of the argument string:

```py
from itertools import cycle

def alt_caps(s):
    func_cycle = cycle([str.upper, str.lower])
    result = [f(char) for char, f in zip(s, func_cycle)]
    return "".join(result)

print(alt_caps("Hello, world!"))  # HeLlO, wOrLd!
```

Alternatively, you can use the functions as dictionary values, so you can map the even and odd positions to the appropriate method instead:

```py
def alt_caps(s):
    funcs = {
        0: str.upper,
        1: str.lower,
    }
    result = [funcs[idx % 2](char) for idx, char in enumerate(s)]
    return "".join(result)

print(alt_caps("Hello, world!"))  # HeLlO, wOrLd!
```

### Anonymous functions as objects

The keyword `lambda`, [introduced in the section about anonymous functions](#anonymous-functions), creates an expression that evaluates to a function, which can also be used in the contexts listed above.

For example, if you are working with the Collatz conjecture, you can use anonymous functions inside a dictionary to map out the transformations that are applied to even and odd numbers:

```py
COLLATZ = {
    0: lambda x: x // 2,
    1: lambda x: 3 * x + 1,
}

def next_step(x):
    return COLLATZ[x % 2](x)

steps = [17]
while steps[-1] != 1:
    steps.append(next_step(steps[-1]))
    
print(steps)  # [17, 52, 26, 13, 40, 20, 10, 5, 16, 8, 4, 2, 1]
```


## Closures

A closure is like a bubble that forms around an inner function that keeps the variables of the outer function available even after the inner function is returned from inside the outer function.

DIAGRAM HERE

## Decorators


## Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*bla bla*”

This Pydon't showed you that:

 - the arithmetic operators are implemented through special methods called dunder methods;
 - the arithmetic dunder methods are called automatically by Python behind the scenes;
 - custom objects can interact with the standard arithmetic operators via those same dunder methods;
 - binary arithmetic operators correspond to dunder methods with two parameters;
 - the singleton value `NotImplemented` is used behind the scenes to flag operator/argument(s) combinations that cannot be handled;
 - you need to use `NotImplemented` so that Python knows what methods to call;
 - the singleton value `NotImplemented` is distinct from the exception `NotImplementedError`;
 - binary arithmetic operators have a reflected variant, with an `r` prepended to the name;
 - the reflected variants are called when
   - the original call wasn't handled by the left operand of the operator (that is, it returned `NotImplemented`); or
   - when the right operand is from a subclass of the left operand.
 - binary arithmetic operators have an in-place variant, with an `i` prepended to the name;
 - the in-place variants are called by the augmented assignment operators, like `+=` and `-=`; and
 - if the in-place variants are not available, Python unfolds the augmented assignment naturally.

<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss a single Pydon't!
<!-- ^ -->
