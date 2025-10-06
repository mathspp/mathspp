This article serves as a complete reference for all the non-trivial things you should know about Python functions.

===

Functions are the basic building block of any Python program you write, and yet, many developers don't leverage their full potential.
You will fix that by reading this article.

Knowing how to use the keyword `def` is just the first step towards knowing how to define and use functions in Python.
As such, this Pydon't covers everything else there is to learn:

 - How to structure and organise functions.
 - How to work with a function signature, including parameter order, `*args` and `**kwargs`, and the special characters `*` and `/`.
 - What anonymous functions are, how to define them with the keyword `lambda`, and when to use them.
 - What it means for functions to be objects and how to leverage that in your code.
 - How closures seem to defy a fundamental rule of scoping in Python.
 - How to leverage closures to create the decorator pattern.
 - What the keyword `yield` is and what generator functions are.
 - What the keyword `async` is and what asynchronous functions are.
 - How partial function application allows you to create new functions from existing functions.
 - How the term “function” is overloaded and how you can create your own objects that behave like functions.

Feel free to skim through this article to see everything that is covered here and then bookmark it to use it as a reference for whenever you have questions later, or for when you forget something about functions.

<!--v-->
!!! Bookmark this reference for later or download the [“Pydon'ts – write elegant Python code”](/books/pydonts) ebook for free.
!!! The ebook contains this chapter and many others, including hundreds of tips to help you write better Python code.
!!! [Download the ebook “Pydon'ts – write elegant Python code” here](/books/pydonts).
<!--^-->


## What goes into a function and what doesn't

Do not overcrowd your functions with logic for four or five different things.
A function should do a single thing, and it should do it well, and the name of the function should clearly tell you what your function does.

If you are unsure about whether some piece of code should be a single function or multiple functions, it's best to err on the side of too many functions.
That is because a function is a modular piece of code, and the smaller your functions are, the easier it is to compose them together to create more complex behaviours.

Consider the function `process_order` defined below, an exaggerated example that breaks these best practices to make the point clearer.
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
The current discount logic is terrible and it's buried inside somewhere in the function `process_order`, so finding it and updating it will be painful.

If you split the function `process_order` into modular subfunctions, it becomes easier to find the logic you care about:

```py
# ✅ Refactored for clarity: broken up into smaller functions.

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

Now that the code is split into more modular functions, it's easier to change the code.
For example, the current discount logic is terrible because it hardcodes the discounts in the function.
It makes more sense to have a dictionary with the items that currently have a sale and compute the new prices from that:

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

Making this focused change is easy because you only have to update a single, independent function.
If other requirements change – what if the receipt must be printed in a different format? –, then all you have to do is find the appropriate function and update it.
Everything else stays untouched.

By splitting the different steps into smaller subfunctions, it becomes easier to update those steps when the requirements of your program change.

Note, however, that you can argue that the function `process_order` _still_ does too many things, since it's validating the order, applying the discounts, and writing the receipt.
Before, the function `process_order` contained all the code that did those things, but now it's just the “driver” of your program, passing the responsibility of computing the different steps to different functions.

Sometimes, you want to add code to a function that is helpful to the developer or the user, but that is not directly related to the original goal of the function.
For example, you may want to add logging to a function, a cache, or profiling.
When you want to add these behaviours that are _orthogonal_ to the original objective of the function, you will want to [use a decorator](#decorators), something you'll learn about later.


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
The parameter can have any other name, as long as it has the two asterisks on its left.

`*args` and `**kwargs` can be present in the same function signature, but the variable positional arguments must always come first.
When it comes to ordering parameters, it is also worth mentioning that `*args` must come after all other positional arguments and `**kwargs` must be the last parameter of your function signature.


### Positional-only and keyword-only arguments

If you want to write a function signature for which one or more parameters can only be used as positional parameters, then you might use a forward slash `/` in the signature.
All parameters to the left of the forward slash are automatically positional-only:

```py
def f(a, /, b):
    return a + b

print(f(1, 2))  # 3
print(f(1, b=2))  # 3
print(f(a=1, b=2))  # TypeError
```

This syntactic feature is most useful when the names of the parameters are not meaningful and it is their position that is relevant.

You can do a similar thing to force parameters to be keyword-only.
If you include an asterisk `*` in the parameter list of a function signature, all parameters to the right of the asterisk `*` become keyword-only:

```py
def f(a, *, b):
    return a + b

print(f(a=1, b=2))  # 3
print(f(1, b=2))  # 3
print(f(1, 2))  # TypeError
```

A good use case for `*` is when your function has parameters that act as modifiers or configuration options.
When that is the case, forcing those parameters to be keyword-only helps document the function call.

For example, the built-in function `pow` has three parameters:

```py
# They're the same thing:
print(pow(2, 32) % 997)  # 966
print(pow(2, 32, 997))  # 966
```

`pow` accepts the third parameter `mod` because there is an efficient algorithm to compute expressions like `(base ** exp) % mod`, and writing `pow(base, exp) % mod` does not leverage that algorithm.
To increase the readability of function calls like `pow(2, 32, 997)`, you could've defined the function `pow` like this:

```py
def pow_(base, exp, *, mod=None):
    return pow(base, exp, mod)

print(pow_(2, 32, mod=997))  # 966
```

In the snippet above, the function call becomes clearer because you were forced to use the parameter name when passing in the argument `997`.


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

This happens because default arguments are evaluated once, when the function is first defined, and then become associated with your function:

```py
print(put_in_list.__defaults__)  # ([3, 4, 5],)
default_list = put_in_list.__defaults__[0]
print(default_list is my_list)  # True
```

Whenever you call `put_in_list` without specifying a list, the list that is used by default is always the same list, which is the one that you can access from the dunder attribute `__defaults__`.

The most common way to fix this is by using `None` as the default and then checking for it in the function:

```py
def put_in_list(value, list_to_put_into=None):
    if list_to_put_into is None:
        list_to_put_into = []
    list_to_put_into.append(value)
    return list_to_put_into
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
Nowadays, they are commonly used in projects that use static type checkers, like mypy, ty, or pyright.

On top of being possible to annotate parameters, you can also annotate the whole function signature by adding an arrow `->` after the closing parenthesis:

```py
def foo() -> "This is another annotation":
    pass
```

Within the context of type checking and type annotations, the annotations after the parameters are used to indicate the type of each parameter and the annotation after the function signature is used to annotate the type of the function return value.

For example, below you can find [the built-in function `hash` and its type annotations](https://github.com/python/typeshed/blob/7ab6620c0f37e07486a2944460f477336c925618/stdlib/builtins.pyi#L1454):

```py
def hash(obj: object, /) -> int:
    ...
```

The type annotations say that the built-in `hash` accepts any object (which is generic on purpose) and returns an integer result.


### The signature object

Python is usually known for its introspection capabilities, and the module `inspect` provides a function `signature` you can use if you need to retrieve information about the signature of a function.

Here is an example that gives you an idea of the information you can access with `inspect.signature`:

```py
from inspect import signature

def my_join(left: str, right: str, sep: str = " ") -> str:
    return left + sep + right

sig = signature(my_join)
print(sig)  # (left: str, right: str, sep: str = ' ') -> str
print(type(sig))  # <class 'inspect.Signature'>
```

The first thing you can see is that there is a class `inspect.Signature` that holds the information about the signature.
This signature also has information about all parameters:

```py
print(sig.parameters)
# OrderedDict({
#     'left': <Parameter "left: str">,
#     'right': <Parameter "right: str">,
#     'sep': <Parameter "sep: str = ' '">
# })
```

In turn, each parameter is an instance of the class `inspect.Parameter`:

```py
print(sig.parameters["sep"])  # sep: str = ' '
print(type(sig.parameters["sep"]))  # <class 'inspect.Parameter'>
```

Naturally, each parameter has some information, like its kind (positional and/or keyword) and its default value:

```py
print(sig.parameters["sep"].kind)  # POSITIONAL_OR_KEYWORD
print(sig.parameters["sep"].default == " ")  # True
```

This isn't something you will use often, but if you need it, you can [read the documentation](https://docs.python.org/3/library/inspect.html#inspect.signature) or [the PEP that introduced `inspect.signature`](https://peps.python.org/pep-0362/) for more information.


### Ordering the parameters

I wanted to share a rule of thumb that can help you determine how to order the parameters in your function signature.
Talking about the “correct ordering of your function parameters” is a bit of a pedantic discussion to have, but sometimes it makes things easier when you're using things like [closures](#closures) or [partial functions](#partial-function-application), which we'll cover later.

Order your function parameters by how likely the caller is to change them.
Parameters that are more likely to remain the same across function calls go first, and parameters that are more likely to change across function calls go last.

For example, the Portuguese government has a platform where I can check all of the receipts I have issued as a trainer during a specific time period.
They are likely to have an endpoint, somewhere, that accepts a taxpayer number, a time interval start date, and a time interval end date, and the endpoint retrieves all of the receipts for that taxpayer within that timeframe.

Their endpoint was either defined as:

```py
#                  vvvvvvvv
def fetch_receipts(taxpayer, start_date, end_date):
    ...
```

or

```py
#                                        vvvvvvvv
def fetch_receipts(start_date, end_date, taxpayer):
    ...
```

According to my recommendation, the first signature is better.
Why?
It is much more common to have a user log in and fetch receipts for different time frames than it is for a group of users to log in, one at a time, to fetch receipts from the exact same time frame.

Opting for `fetch_receipts(taxpayer, start_date, end_date)` is not only more sensible, but also more practical, for example, [when using partial function application](#partial-function-application).


### Parameters versus arguments

To close the section about the function signature, I just wanted to [put my pedantic hat on again](#ordering-the-parameters) and shine a light on the fact that “argument” and “parameter” are two different words that mean two different things, although most people use these terms interchangeably.

An _argument_ is a concrete value that is passed into a function when you call a function.
For example, you can say “the built-in function accepts all types of arguments, not just strings”.

A _parameter_ is a formal variable defined in the function signature.
For example, you can say the function `add` shown below has two parameters called `a` and `b`:

```py
def add(a, b):
    return a + b
```

Knowing this distinction won't make or break your career as a Python developer, but it's important to be aware of these nuances, so that your understanding of the Python language and of programming in general advances.


## Anonymous functions

The standard way to define a function is with the keyword `def`.
This sets up a function definition statement that creates a function and then assigns it to the name you pick.
For example, the snippet below creates a function that clips the first argument to be within the other two and binds that function to the name `clip`:

```py
def clip(number, lower, upper):
    return min(upper, max(lower, number))

print(clip(17, 0, 10))  # 10
```

I'm talking about “binding that function to the name `clip`” because the function object is an entity that exists outside of the name.
In fact, there are situations in which you need a function object, but you don't need the ceremony of defining it with the keyword `def` and giving it a name.
That is what the keyword `lambda` is for.

By using the keyword `lambda`, you can write an expression that evaluates to a function.
Just like the expression `1 + 4` evaluates to the integer `5`, the expression `lambda x: x + 1` evaluates to the function that accepts an argument `x` and returns `x + 1`:

```py
print(lambda x: x + 1)  # <function <lambda> at 0x1051efc40>
```

Since the code `lambda x: x + 1` _is_ a function, you can use parentheses to call it, as long as you place the parentheses correctly.
If you write `lambda x: x + 1()`, Python will think you are trying to call `1`, so you need to wrap the anonymous function in parentheses:

```py
print(
    (lambda x: x + 1)(4)
)  # 5
```

Since you can use the keyword `lambda` to create a function and use it without ever giving it a name, functions defined with the keyword `lambda` are called _anonymous functions_.


### The body of an anonymous function

Anonymous functions, created with the keyword `lambda`, can only contain a single expression in their body.
If you need to use statements, such as assignment, error handling, conditional statements, and loops, you need to define a function with the statement `def`.

Constructs that are valid inside anonymous functions include

- [conditional expressions](/blog/pydonts/conditional-expressions);
- standard arithmetic and comparison operations;
- calling other functions or methods;
- [list comprehensions](/blog/pydonts/list-comprehensions-101);
- [assignment expressions](/blog/pydonts/assignment-expressions-and-the-walrus-operator);
- and more.

As a direct consequence of this, you _cannot_ write an anonymous function with an empty body because the keyword `pass` defines a statement.
The “best” you can do, from a readability point of view, is to define the function with an ellipsis in its body: `lambda x: ...`.
That may look like an empty anonymous function to you, but this is a regular anonymous function:

```py
print(
    (lambda x: ...)(73)
)  # Ellipsis
```


### The signature of an anonymous function

What you learned about function signatures, namely [the usage of `*args` and `**kwargs`](#args-and-kwargs), the [issue with mutable default arguments](#mutable-default-arguments), and how to define [positional-only and keyword-only parameters](#positional-only-and-keyword-only-arguments), also applies to the signature of an anonymous function.

The definition of the signature of an anonymous function does not require the parentheses around the parameter list, but everything else is the same.
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


### Use-case rule of thumb

As a good rule of thumb, you'll want to use anonymous functions to create one-off functions to be used within higher-order functions.
To understand how (anonymous) functions can be used within higher-order functions, you'll want to read about [using functions as objects](#functions-as-objects), which is what comes next.


## Functions as objects

Python is far from being a perfect language, but one thing I like about Python is its consistency.
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
You can assign functions to variables.
You can create lists of functions.
You can have dictionaries that map to functions.
You can pass functions as arguments to other functions or create and return functions from within other functions.
And much more!


### Don't wrap functions in anonymous functions

The built-ins `sorted`, `max`, and `min`, accept a keyword argument `key` that turns these three built-ins into three versatile built-ins.
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

For example, suppose you have a list of points represented as pairs:

```py
points = [
    (2, 2),
    (1, 3),
    (4, 0),
    (3, 2),
]
```

Which of these is farther away from the point `(0, 0)`?
By using the built-in `max` and an anonymous function, you can determine that:

```py
print(
    max(points, key=lambda p: p[0] ** 2 + p[1] ** 2)
)  # (4, 0)
```


## Closures

### Local function variables

Variables that you define inside a function are lost once you leave the function.
For example, in the snippet below, after executing the function `print_x`, the variable `x` no longer exists:

```py
def print_x():
    x = 73
    print(x)

# print(x)  # This would raise a `NameError`.
print_x()  # 73
# print(x)  # This would raise a `NameError`.
```

Closures create an exception to this rule.
A closure is like a bubble that keeps variables from a function alive, but only in a specific scenario.
Consider the snippet of code below, where the function `outer` defines a function called `inner` and then calls it:

```py
def outer():
    x = 73
    def inner():
        print(x)
    inner()
```

If you call the function `outer`, does it print the number `73` or does it raise a `NameError`?

As it turns out, because of the LEGB scoping rules (local – **enclosing** – global – built-ins), the function `outer` will print `73` because the function `inner` has access to the variable `x` that is defined in an enclosing scope:

```py
def outer():
    x = 73  # <─────┐ The function inner sees
    def inner():  # │ the variable x defined
        print(x)  # ┘ in the enclosing scope.
    inner()

outer()  # 73
```

[Because functions are objects](#functions-as-objects), if the function `outer` returns the function `inner` instead of calling it, suddenly the function `inner` will be referencing a variable `x` that was defined in the enclosing scope of `outer`, but it will try to access it long after the function `outer` is done for:

```py
def outer():
    x = 73
    def inner():
        print(x)
    return inner

inner_function = outer()
print(inner_function.__name__)  # inner
```

If you run the function `inner_function`, do you see the number `73` printed on the screen, or do you get an error?

Because of closures, the function `inner_function` still works:

```py
inner_function()  # 73
```


### The dunder attribute `__closure__`

When you return the function `inner` from the function `outer`, Python creates a closure that gives `inner` access to the enclosing variables it will need after being returned.
In fact, you can inspect this closure through the dunder attribute `__closure__`:

```py
print(inner_function.__closure__)  # (<cell at 0x103e62e30: int object at 0x104ea0db8>,)
print(inner_function.__closure__[0].cell_contents)  # 73
```

As you can see, in Python, a closure takes the form of a tuple of “cells”, where each cell holds the value of one of the enclosing variables that the function will need to access.
In the case of the function `inner_function`, that's only the variable `x` that has the value `73`.

For most functions, the dunder attribute `__closure__` is set to `None` because most functions don't have closures:

```py
def no_closure():
    pass

print(no_closure.__closure__)  # None
```

The example with the functions `outer` and `inner` shows you a closure in action, but doesn't explain why closures are useful, nor does it give you a good example of a closure being used for something useful.
That's what the next section is for, where you will learn about [decorators](#decorators).


## Decorators

A decorator is a function that enhances other functions with functionality that is useful but that, at the same time, is independent from the main purpose of the function to be enhanced.

Suppose you are developing a program that traverses a website looking for broken links.
You might need a function that accepts a URL and returns all the links found on that page:

```py
def get_links(url):
    page = get_page_from_url(url)
    links = get_links_from_page(page)
    return links
```

You might need to call this several times during a short interval, so you realise it might be worth caching the results so you don't keep fetching the same page over and over:

```py
_links_cache = {}  # Maps URLs to lists of links.
def get_links(url):
    if url not in _links_cache:
        page = get_page_from_url(url)
        links = get_links_from_page(page)
        _links_cache[url] = links
    return _links_cache[url]
```

The cache works, but [you've polluted the logic of your function with something that doesn't belong there](#what-goes-into-a-function-and-what-doesnt): caching.
The job of your function was to fetch links from a URL, and that's it.
On top of that, if you want to add caching to other functions, you will have to modify the functions again, and yet the logic will be very similar.

That's when decorators come into play: a decorator allows you to factor out this logic so that you can apply it repeatedly and independently to multiple functions.
In practical terms, decorators tend to be functions that accept a function to decorate (the function to be enhanced) and then define an inner function that implements some useful functionality around the function to be decorated:

```py
def cache(function):
    _cache = {}
    def cached_function(*args):
        if args not in _cache:
            result = function(*args)
            _cache[args] = result
        return _cache[args]
    return cached_function
```

Note that, when the inner function `cached_function` is returned, the function will reference the dictionary `_cache` and the variable `function`, which will remain accessible because of the [closure](#closures) defined around the inner function `cached_function`.

The decorator function can be used by passing it another function as an argument and by saving its return value in a variable:

```py
def get_links(url):
    page = get_page_from_url(url)
    links = get_links_from_page(page)
    return links

get_links = cache(get_links)  # `get_links` now has a cache.
```

Most of the time, you'll want to apply a decorator as soon as you define a function, and for that, you can use the special syntax that uses the at sign `@`.
The at sign `@` is only syntactic sugar, though, so the previous snippet is equivalent to the one below:

```py
@cache
def get_links(url):
    page = get_page_from_url(url)
    links = get_links_from_page(page)
    return links
```

To see that the decorator `cache` works, the example below shows a “slow” function that is “sped up” by caching its results:

```py
import time

@cache
def double(x):
    time.sleep(2)
    return 2 * x

# First call takes ~2 seconds:
print(double(3))  # 6
# Since the result is cached, the next call is instantaneous:
print(double(3))  # 6
```

There is a lot more to decorators than just this, so [read this article if you'd like to learn more about decorators](/blog/pydonts/decorators).
Remember that the key idea is that [a decorator is a **reusable function**][^1] that allows you to enhance other functions with functionality that is useful but orthogonal to the original purpose of the function.

[^1]: If you read the section “[Not all functions are functions](#not-all-functions-are-functions)” you may realise that it's enough for a decorator to be a callable.
Decorators may also accept callables that aren't functions and they may return callables that aren't functions.


## Generator functions

A generator function is a function that uses the keyword `yield` in its body.
So, by definition, the function `gf` below is a generator function:

```py
def gf():
    yield 0
```

But what does `yield` do, and what's the point?

I like to think of the keyword `yield` as a “temporary return”.
You can use the keyword `yield` to return values to the caller of the function, but instead of exiting the function for good, the keyword `yield` pauses the function, and you can resume its execution later.

Consider the function `return123`:

```py
def return123():
    return 1
    return 2
    return 3
```

If you call this function, you get the value `1`, and there is no way to ever get to the value `2`.
However, if you replace the keyword `return` with the keyword `yield`, you can iterate over the multiple usages of the keyword `yield` to access the values `1`, `2`, and `3`:

```py
def yield123():
    yield 1
    yield 2
    yield 3

for value in yield123():
    print(value)
# 1
# 2
# 3
```

The reason you can iterate over the values is that a generator function (like `yield123`) produces a generator object, which is an iterable:

```py
print(yield123())  # <generator object yield123 at 0x103dc9fc0>
```

When you write `for value in yield123()`, you're iterating over this generator object, not over the generator function directly.
So, a generator function is just a convenient way of defining iterables.

The good thing about generator functions is that you can write code that is as complex or as simple as you'd like, and you can use that to generate values that you care about.
For example, the generator function `squares` shown below only has a single keyword `yield` but generates multiple results because it's inside a loop:

```py
def squares(up_to):
    for n in range(up_to):
        yield n ** 2

for sq in squares(5):
    print(sq, end=", ")
# 0, 1, 4, 9, 16,
```

Generators are a fascinating tool that allows you to write very efficient code because generators can be used to save computational resources through laziness.
The potential upside of using generators is infinite!


## Asynchronous functions

On top of regular functions and generator functions, there is another type of function you can create by using a special keyword, and that's asynchronous functions.

An asynchronous function, also called a coroutine function, is a function that's defined with `async def`:

```py
async def coroutine():
    pass

print(coroutine)    # <function coroutine at 0x103e6b1c0>
print(coroutine())  # <coroutine object coroutine at 0x103dc9900>
```

Asynchronous functions are used in asynchronous programming to define coroutines, which are objects whose execution can be paused and resumed at different times, allowing you to switch back and forth between multiple coroutines.

(If “objects that can be paused and resumed at different times” sounds like generators, then well spotted!
In the past, coroutines were defined using the generator function syntax when the `async`/`await` keywords were not supported.)

The snippet below shows an asynchronous function `print_after` that is defined with the keyword `async`:

```py
import asyncio

async def print_after(msg, seconds):
    await asyncio.sleep(seconds)
    print(msg)
```

Asynchronous functions return coroutines, not results.
That's why calling `print_after("Hello, world!", 1)` will not automatically print the string `"Hello, world!"` after waiting for a second.

The function `print_after`, defined above, also uses the special keyword `await`.
You can think of it as a keyword that facilitates the coordination between multiple coroutines, since it is what allows execution to switch back and forth between coroutines.

If you inspect the code below, you will see that `"scheduled before"` is scheduled first, but the program prints it last:

```py
import asyncio

async def main():
    await asyncio.gather(
        print_after("scheduled before", 3),  # Will print in 3 seconds.
        print_after("scheduled after", 2),  # Will print in 2 seconds.
    )

asyncio.run(main())
# scheduled after
# scheduled before
```

To learn more about the world of asynchronous programming, and to understand what the keyword `await` is doing, I recommend that you [read this cooking analogy that explains the asynchronous programming model and how coroutines fit into it](/blog/til/cooking-with-asyncio).


## Partial function application

### The parameter `key` and ordering

Do you remember that the built-in functions `max`, `min`, and `sorted`, all have a keyword parameter `key` that you can use to induce a different ordering in the iterable that you pass them?
For example, if you pass a list of strings to the built-in `sorted`, your strings will be sorted alphabetically:

```py
fruits = ["banana", "apple", "pear"]
print(sorted(fruits))  # ['apple', 'banana', 'pear']
```

However, if you set `key=len`, you get the list of strings sorted _by length_:

```py
fruits = ["banana", "apple", "pear"]
print(sorted(fruits, key=len))  # ['pear', 'apple', 'banana']
```

This shows how the parameter `key` can be used to give a new meaning to these built-ins.


### Freezing the argument `key`

Suppose you wanted to sort many lists by the length of their items.
You could create a small function for that purpose:

```py
def len_sort(items):
    return sorted(items, key=len)
```

Note how the only purpose of the function `len_sort` is to fill in the blank in the function call `sorted(..., key=len)`.
In other words, your function `len_sort` freezes the argument `key=len` and you're just left with filling in the value of the iterable `items`.

Maybe you even considered using an [anonymous function](#anonymous-functions) for this, and wrote

```py
len_sort = lambda items: sorted(items, key=len)
```

This is called **partial function application**: you already specified some of the arguments that your function will accept, and you'll pass in the rest of the arguments later.
This allows you to take generic functions and specialise them for repeated use.


### How to do partial function application in Python

Python provides tools that are designed specifically with partial function application in mind, and the main tool for that is `functools.partial`.
`functools.partial` is preferred over defining your own function with `def` or your own anonymous function because it conveys the intent of partial function application in a clearer manner, it supports introspection, and it provides other niceties.

Using `functools.partial`, the example with `sorted(..., key=len)` would be written as

```py
from functools import partial

len_sort = partial(sorted, key=len)

fruits = ["banana", "apple", "pear"]
print(len_sort(fruits))  # ['pear', 'apple', 'banana']
```

You can [learn more about `partial` and its complementary tool, `functools.Placeholder`, in this article](/blog/functools-partial).


## Not all functions are functions

### Some built-in functions are classes

The [Python documentation has a page called “Built-in Functions”](https://docs.python.org/3/library/functions.html) that lists 70+ built-in functions, like `int`, `str`, `enumerate`, `range`, `print`, and more.

However, of the five I listed, only one is a _built-in function_:

```py
print(print)  # <built-in function print>
print(type(print))  # <class 'builtin_function_or_method'>
```

`int`, `str`, `enumerate`, and `range`, are all classes, not functions:

```py
print(type(int))        # <class 'type'>
print(type(str))        # <class 'type'>
print(type(enumerate))  # <class 'type'>
print(type(range))      # <class 'type'>
```

And there are more of these so-called “built-in functions” that are classes, not functions.

But...
Is this distinction important?


### Callables versus functions

Well, yes, but actually no.

In practice, the distinction does not matter.
You use the built-ins `range`, `enumerate`, or `zip`, as if they were functions.
You typically call them and use their results immediately in a `for` loop, for example.
You use the built-ins `str` and `int` to convert integers to strings and vice versa.
It doesn't matter that these are classes and not functions.

Understanding the distinction will help you understand the Python language a bit better, though.
The page with the “built-in functions” lists some classes because they are _callables_, just like functions.
Classes and functions can be used with the parentheses `()` to get them to perform some action or computation, and that is the behaviour that you care about.

As long as `range` remains a callable and its behaviour stays unchanged, it doesn't matter what type of callable it is.
You won't care or be affected if someone modifies the Python source code to make `range` a function instead of a class.

This is an example of duck typing in action.
If it quacks like a duck, it must be a duck...
In this case, if it is a callable like a function, it must be a function...

In fact, Python provides a function `callable` that allows you to check if a given object is callable, and naturally, that returns `True` for all the “built-in functions”, regardless of whether they are functions or classes:

```py
print(callable(print))  # True
print(callable(int))    # True
```


### The type `Callable`

Precisely because the distinction between classes and functions isn't important – what matters is that they're callable –, the module `typing` provides a type `Callable` that you can use when you need to specify that something is a callable.

For example, the function `make_adder` below accepts an integer and returns a function, but in the typing world you say that the function `make_adder` returns something that _can be used like a function_:

```py
from typing import Callable

def make_adder(a: int) -> Callable[[int], int]:
    def adder(b: int) -> int:
        return a + b

    return adder

plus_5 = make_adder(5)
print(plus_5(10))  # 15
```

The first argument of `Callable` is a list of the types of the arguments of the callable, and the second argument is the return type.
For example, `Callable[[int, str], bool]` is a callable that accepts an integer and a string and returns a Boolean.


### Creating your own callables

You can create your own callables by defining the [dunder method](/blog/pydonts/dunder-methods) `__call__`.
For example, a class that represents a neural network could define the dunder method `__call__`:

```py
class NeuralNetwork:
    ...

    def __call__(self, input):
        ...  # Process the input
        return result
```

By defining it this way, a neural network `nn` can be used as `nn(input)` to process the input and produce its result.
The dunder method `NeuralNetwork.__call__` makes instances of the type `NeuralNetwork` callables:

```py
nn = NeuralNetwork()
print(callable(nn))  # True
```


## Summary

You've seen everything functions can do and everything you can do with functions, so let's recap the key takeaways:

 - a function should [do one thing and should do it well](#what-goes-into-a-function-and-what-doesnt);
 - if you want to enhance a function with functionality that isn't directly tied to the function, you should [use a decorator](#decorators);
 - the [arguments of a function are the values you pass into the function when you call it](#parameters-versus-arguments);
 - the [parameters of a function are the formal variables you write in the function signature when you define a function](#parameters-versus-arguments);
 - as a good rule of thumb, [parameters that are more likely to be changed across multiple function calls should come later in the function signature](#ordering-the-parameters);
 - the [asterisk `*` can be used in the parameter list to force any parameter that comes after it to be keyword-only](#positional-only-and-keyword-only-arguments);
 - the [forward slash `/` can be used in the parameter list to force any parameter that comes before it to be positional-only](#positional-only-and-keyword-only-arguments);
 - you can [use `*args` and `**kwargs` to accept an arbitrary number of positional or keyword arguments](#args-and-kwargs), respectively;
 - the [asterisk `*` can also be used to write a function that accepts an arbitrary number of positional arguments](#args-and-kwargs);
 - the [module `inspect` can be used to inspect a function's signature](#the-signature-object);
 - default arguments are evaluated when the function is defined and, because of that, [you shouldn't use mutable objects as default arguments](#mutable-default-arguments);
 - [the keyword `lambda` can be used to write expressions that define lightweight functions](#anonymous-functions);
 - [functions defined with `def` are objects](#functions-as-objects), [and so are `lambda` functions](#anonymous-functions-as-objects), meaning they can be passed as arguments to functions, returned from functions, and more;
 - [a closure](#closures) preserves variables from the enclosing scope alive when a function returns another function and the returned function accesses variables from the function that just exited;
 - you can [access a function's closure through the dunder attribute `__closure__`](#the-dunder-attribute-__closure__);
 - the keyword `yield` is used to define [generator functions](#generator-functions);
 - and the keyword `async` is used to define [asynchronous functions](#asynchronous-functions), which in turn may use the keyword `await`;
 - [partial function application](#partial-function-application) is the practice of freezing some arguments in a function call;
 - you should [use `functools.partial` for partial function application](#how-to-do-partial-function-application-in-python);
 - [functions and classes can be used interchangeably in some contexts](#not-all-functions-are-functions);
 - Python duck typing means you often only care about [whether an object is a callable or not](#callables-versus-functions);
 - [the type `typing.Callable` can be used to represent callable objects](#the-type-callable); and
 - you can [use the dunder method `__call__` to create your own callables](#creating-your-own-callables).

<!-- v -->
If you found this Pydon't useful, check out the [“Pydon'ts – write elegant Python code”](/books/pydonts) ebook.
It's a free ebook that contains dozens of chapters that teach you to write better Python code.
[Download the ebook “Pydon'ts – write elegant Python code” here](/books/pydonts).
<!-- ^ -->
