This article teaches you how to use `functools.partial`, how it works, and when to use it, with clear examples.

===


# `functools.partial`

## What is `functools.partial`?

`functools.partial` is a tool from the standard module `functools` that allows you to curry positional and keyword arguments in functions.
In a certain way, it's as if `partial` creates a specialised version of the function you pass it in, with certain arguments frozen.

For example, the built-in `int` converts objects to integers:

```pycon
>>> int("14")
14
>>> int(14.5)
14
```

Maybe you didn't know that the built-in `int` also accepts a second argument that specifies the base from which the number must be converted:

```pycon
>>> bin(99)  # 99 in binary is 1100011
'0b1100011'
>>> int("1100011", base=2)
99

>>> hex(99)  # 99 in hexadecimal is 63
'0x63'
>>> int("63", base=16)
99
```

By using `functools.partial`, you can create specialised versions of `int` where the parameter `base` was fixed to a certain base:

```pycon
# `partial` lives inside the module `functools`:
>>> from functools import partial

>>> from_bin = partial(int, base=2)
>>> from_bin("1100011")
99

>>> from_hex = partial(int, base=16)
>>> from_hex("63")
99
```

The two examples above show that the first argument to `partial` is the function whose argument(s) we want to freeze.
Then, we can provide as many positional or keyword arguments as needed.
In our case, we just specified the parameter `base` as a keyword parameter.
Here's how you could read both examples of `partial`:

 - `partial(int, base=2)` - create a new version of `int` where the parameter `base` is always set to `2`; and
 - `partial(int, base=16)` – create a new version of `int` where the parameter `base` is always set to `16`.


## How to use `functools.partial`

The example above showed briefly how `partial` works and this section will go over the most important details.
For demonstration purposes, let us create a function with a couple of parameters:

```py
def foo(a, b, *, c, d=10):
    print(a, b, c, d)
```

The function `foo` above has 4 parameters, of which `c` and `d` are keyword-only and `d` has a default value of `10`.


### `functools.partial` and positional arguments

`partial` can be given positional parameters, which will be passed in, in order, to the function that is the first argument to `partial`.
When you call the new function, the other positional arguments you pass in are appended to the ones you already specified.
So, `bar = partial(foo, 1)` is a function that has 3 parameters and that corresponds to `foo(1, b, *, c, d=10)`.
Similarly, `baz = partial(foo, 1, 2)` is a function that has 2 parameters and that corresponds to `foo(1, 2, *, c, d=10)`.

```pycon
>>> bar = partial(foo, 1)
>>> bar(20, c=30, d=40)
1 20 30 40

>>> baz = partial(foo, 1, 2)
>>> baz(c=30, d=40)
1 2 30 40
```

Notice that `partial` doesn't do any type of validation whatsoever regarding the number of arguments you pass in.
This means that a call to `partial` might succeed but then produce a function that is unusable.

The example below shows this.
The `partial` call below succeeds but then we're then unable to call `too_many_args` because `foo`, the underlying function, only accepts two positional arguments:

```pycon
>>> too_many_args = partial(foo, 1, 2, 3)  # This works fine...

>>> too_many_args()  # ... even though `too_many_args` is unusable...
TypeError: foo() takes 2 positional arguments but 3 were given

>>> too_many_args(c=3, d=4)
TypeError: foo() takes 2 positional arguments but 3 positional arguments (and 2 keyword-only arguments) were given
```


### `functools.partial` and keyword arguments

In the very first example we've already seen that `partial` accepts keyword arguments which are then passed into the underlying function.
Keyword arguments set in `partial` can also be overridden:

```pycon
>>> foo_no_d = partial(foo, d=999)

>>> foo_no_d(1, 2, c=3)  # `d` was set...
1 2 3 999

>>> foo_no_d(1, 2, c=3, d=4)  # ... but it can be overridden.
1 2 3 4
```

A very useful thing to note is that we can pass in arguments as keyword arguments to `partial` even if they're not necessarily keyword arguments in the original function.
This means that we can use `partial(foo, b=...)` to freeze the value of the parameter `b` while leaving `a` unspecified, for example:

```pycon
>>> foo_no_b = partial(foo, b=999)

>>> foo_no_b(1, c=3, d=4)
1 999 3 4
```

What we can't do is try to override the value of `b` by passing a second positional argument:

```pycon
>>> foo_no_b(1, 2, c=3, d=4)
TypeError: foo() got multiple values for argument 'b'
```

Finally, bear in mind something that may or may not be obvious, which is that if a parameter is defined as _positional-only_, then you can't set it as a keyword with `partial`:

```pycon
>>> def pos_only(a, /, b):  # The `/` says that `a` is positional-only.
...     print(a, b)
... 
>>> pos_only(1, 2)
1 2
>>> pos_only(a=1, b=2)  # `a` can't be specified as a keyword value...
TypeError: pos_only() got some positional-only arguments passed as keyword arguments: 'a'


>>> f = partial(pos_only, a=1)
>>> f(b=2)
TypeError: pos_only() got some positional-only arguments passed as keyword arguments: 'a'
```


### The `partial` object and its attributes

In the beginning of this article I wrote that “`partial` is a tool” instead of “`partial` is a function”, and I did that because `partial` is not a function.
In fact, `partial` is a class:

```pycon
>>> type(partial)
<class 'type'>
```

This means that using `partial` creates `partial` objects:

```pycon
>>> from_bin = partial(int, base=2)
>>> from_bin
functools.partial(<class 'int'>, base=2)
```

This is not terribly important or worrying, although it does mean you get access to three read-only attributes:

| Attribute name | Description |
| :- | :- |
| `func` | The original function passed into `partial`. |
| `args` | The leftmost positional arguments for the function. |
| `keywords` | The keyword arguments for the function. |

Using the same `foo` as before, we can see all these three attributes in action:

```pycon
>>> spam = partial(foo, 1, c=3, d=999)

>>> spam.func
<function foo at 0x1035a25e0>
>>> spam.func is foo
True

>>> spam.args
(1,)

>>> spam.keywords
{'c': 3, 'd': 999}
```

Knowing this, we can understand the simplified implementation of `partial` that the documentation shows:

```py
def partial(func, /, *args, **keywords):
    def newfunc(*fargs, **fkeywords):
        newkeywords = {**keywords, **fkeywords}
        return func(*args, *fargs, **newkeywords)
    newfunc.func = func
    newfunc.args = args
    newfunc.keywords = keywords
    return newfunc
```

The line `newkeywords = {...}` shows that keyword arguments can be overridden and the line `return func(*args, *fargs, ...)` shows that the positional arguments you pass into `partial` are used before the positional arguments passed in when you call the function.


## `functools.partial` versus `lambda`

There are many practical cases in which using `partial` or `lambda` ends up being similar.
For example, the `from_bin` and `from_hex` examples could've been `lambda` functions:

```pycon
>>> from_bin = lambda x: int(x, base=2)
>>> from_bin("1100011")
99

>>> from_hex = lambda x: int(x, base=16)
>>> from_hex("63")
99
```

From the functional point of view, the two versions above are essentially the same as the two versions using `partial` and in some contexts deciding between a `partial` or a `lambda` may be a matter of personal preference.
However, do keep in mind that `partial` provides much better introspection capabilities, whereas `lambda` is pretty opaque.
Finally, most people consider that naming a `lambda` is an anti-pattern and something you shouldn't do.

`partial` is also much more convenient when freezing arguments in a function with a long signature because the `lambda` definition would have to recreate the whole signature.
For example, if we only want to freeze the parameter `a` in `foo`, the version with `lambda` becomes more verbose:

```py
from functools import partial
partial(foo, 1)

lambda b, *, c, d=10: foo(1, b, c=c, d=d)
```

The `partial` version is also more convenient to update.


## When to use `functools.partial`

There are two occasions that are typically good use cases for `partial`, and that's when you want to create callbacks and when you want to create specialised versions of general functions.


### Create callbacks

One example application for `partial` is to create callbacks in code that interacts with frameworks.

The function `bt_draw` below is a function from a [tkinter calculator](/blog/single-line-calculator).
Among other things, the function accepts a `key` that determines whether the button is a digit, an operator like `+` or `-`, or the key to clear the calculator.
Then, the function `bt_press` is in charge of handling button presses.

To connect each button to the function `bt_press`, the first line of `bt_draw` uses `command` to specify which function must be called when the button is pressed.
This function that will be called later is the _callback_.
In our case, we say that the callback is the function `bt_press`.
In order for `bt_press` to know what was the actual key that was pressed, when we specify the callback, we freeze the value of the key.

In the original calculator code, this was done with `lambda`:

```py
def bt_draw(key, col, lin):
    bt = tk.Button(window, text=key, command=lambda: bt_press(key))  # <--
    bt.grid(column=col+1, row=lin+1)
    return bt

def bt_press(key):
    ...
```

This could also be done with partial:

```py
def bt_draw(key, col, lin):
    bt = tk.Button(window, text=key, command=partial(bt_press, key))  # <--
    ...
```

Is `partial` a much better solution here?
Not necessarily!
`lambda` was perfectly fine in this case and in situations like this, where we are creating callbacks, I think that `partial` is better than `lambda` only when you need introspection or when the callbacks have long signatures.


### Create specialised functions

The situation where `partial` really shines, in my opinion, is when you have a general function and you want to specialise it.
Going back to the `int` example, notice how using `partial` to freeze the value of `base` allowed me to create two specialised versions of `int`:

 1. one that converts binary strings into integers; and
 2. one that converts hexadecimal strings into integers.

By creating these specialised versions, I created two functions that are simpler to use and are more well-defined.
In particular, if the name is well chosen, the specialised versions become very convenient and may improve the readability of your code.

The benefits of using `partial` to create specialised functions increase with the complexity of the original function and/or the combination of arguments you are specifying.
For example, if you specify `base=0`, `int` will interpret the string as an integer literal, which lets you convert things like `0b1100011` or `0x63` to integers, and `int` takes care of figuring out the base:

```pycon
>>> int("0b1100011", base=0)
99
>>> int("0x63", base=0)
99
```

By using `partial` with `base=0`, we can create a specialised function with a better name that makes it easier for others to understand what is happening if they don't know what `base=0` does yet:

```pycon
>>> interpret_int_literal = partial(int, base=0)
>>> interpret_int_literal("0b1100011")
99
>>> interpret_int_literal("0x63")
99
```

The fact that we use the name `interpret_int_literal` is a great hint as to what `int(..., base=0)` does, which is helpful to those who are reading the code and never encountered the parameter `base=0`.

Another example I enjoy a lot is that of using `max`/`min`/`sorted` with the `key` parameter set to `len`.
When setting `key` to the built-in `len`, these functions get a new meaning:

| `partial` object | Meaning |
| :- | :- |
| `partial(max, key=len)` | Find longest item |
| `partial(min, key=len)` | Find shortest item |
| `partial(sorted, key=len)` | Sort by length |

Here's an example application:

```pycon
>>> longest = partial(max, key=len)
>>> longest("The quick brown fox jumps over the lazy dog".split())
'quick'
```


#### Table of `partial` examples

Just for the fun of it, the table below includes many examples of using `partial` that take a general function and create a more specific function with a more specific meaning.
The main purpose of this table is to show the relationships between some built-ins and some concepts.
Thus, not all examples of `partial` here would be suitable for use in production code.

As an exercise, use each one of these `partial` objects.


| Original | `partial` application | Meaning |
| :--- | :--- | :--- |
| `int` | `partial(int, base=2)` | Read binary numbers |
| `int` | `partial(int, base=16)` | Read hexadecimal numbers |
| `max` | `partial(max, key=len)` | Find longest item |
| `min` | `partial(min, key=len)` | Find shortest item |
| `sorted` | `partial(sorted, key=len)` | Sort by length |
| `max` | `partial(max, default=float("-inf"))` | Mathematically correct `max` |
| `min` | `partial(min, default=float("inf"))` | Mathematically correct `min` |
| `round` | `partial(round, ndigits=2)` | Round to 2 decimal places |
| `round` | `partial(round, ndigits=-3)` | Round to nearest thousand |
| `reduce` | `partial(reduce, operator.add)` | `sum` built-in[^1] |
| `reduce` | `partial(reduce, operator.mul)` | `prod` from the module `math`[^2] |
| `reduce` | `partial(reduce, opertator.and_)` | `all` built-in[^3] |
| `reduce` | `partial(reduce, operator.or_)` | `any` built-in[^4] |
| `range` | `partial(range, 1)` | Natural counting |
| `enumerate` | `partial(enumerate, start=1)` | Natural enumeration |
| `filter` | `partial(filter, None)` | Skip Falsy values |


If you come up with other interesting examples of `partial`, feel free to comment them below or to email me and I might add them here!

[^1]: The `partial` application is not equivalent to `sum` because `sum([])` gives `0` and the `partial` application will error.
[^2]: The `partial` application is not equivalent to `prod` because `prod([])` gives `1` and the `partial` application will error.
[^3]: The `partial` application is not equivalent to `all` because `all([])` gives `True` and the `partial` application will error. Furthermore, `all` will [short-circuit](/blog/pydonts/boolean-short-circuiting#all-and-any) and the `partial` application won't.
[^4]: The `partial` application is not equivalent to `any` because `any([])` gives `False` and the `partial` application will error. Furthermore, `any` will [short-circuit](/blog/pydonts/boolean-short-circuiting#all-and-any) and the `partial` application won't.
