ALL Python functions return something and this article explains how and why.

===


# Does `print` return something?

Does the built-in `print` return something when you call it?
It doesn't!..

Or does it?

If you open a Python REPL and call `print`, you get the value you printed and nothing more:

```pycon
>>> print("Hello, world!")
Hello, world!
```

However, when you call a function that returns something, the value that is returned is shown in the output:

```pycon
>>> def return_5():
...     print("Hello, world!")
...     return 5
...
>>> return_5()
Hello, world!
5
```

So, it looks like `return_5` returns `5` and that `print` returns nothing...

But that's not quite true.
And the reason why it _looks_ true is because of the way the REPL handles a certain value...


# The value `None` in the REPL

Open the REPL, type `None`, and press <kbd>Enter</kbd>.
What do you expect to see?

What if you assign `None` to a variable and then type the name of the variable into the REPL?

What if you create a function that returns `None` and then call it?

In all of the scenarios above, the REPL shows nothing:

```pycon
>>> None
>>> my_value = None
>>> my_value
>>> def return_none():
...     return None
...
>>> return_none()
```

Notice that the REPL never shows any output.
That's because the REPL treats the value `None` in a special way and omits it from outputs!
If you want to see these values, you can print them, for example:

```pycon
>>> print(None)
None
>>> print(my_value)
None
>>> print(return_none())
None
```

So, we are now aware that the value `None` is handled differently inside the REPL.
Now, this is going to be a very important piece of information for what comes next.


# All functions return something

The truth of the matter is that _**ALL**_ functions return _something_.
And the ones that look like they don't?
That's because they return `None`.

For example, here is me assigning the return value of calling `print` to a variable and then printing the value of that variable:

```pycon
>>> print_return = print("Hello, world!")
Hello, world!
>>> print(print_return)
None
```

As another example, the method `.append` of Python lists also look like they don't return anything.
Wrong!
They return `None`:

```pycon
>>> my_list = [73, 42]
>>> append_return = my_list.append(0)
>>> print(append_return)
None
```

If `print` or `append` didn't return a thing, we wouldn't be able to assign their calls to variables.
But we can.
And we can print those values.
So, we know that those functions always return something.

But now, things get _even_ more interesting.
See, maybe `print` and `append` end with `return None`, right?
So, what if you write a function that doesn't have `return None` at the end?


# No-return functions return `None`

Let me define an empty function:

```py
def empty():
    pass
```

If you use the module `dis` to dissect that function, you will see the instructions that Python runs under the hood when you call the function.
For such a simple function, the result of dissecting it tells a lot about how Python works:

```pycon
>>> import dis

>>> def empty():
...     pass
...

>>> dis.dis(empty)
  1           0 RESUME                   0

  2           2 LOAD_CONST               0 (None)
              4 RETURN_VALUE
```

Notice the three words `RESUME`, `LOAD_CONST`, and `RETURN_VALUE`?
Those are the three instructions that Python runs when going over the function `empty`.
The `RESUME` does some set-up for when you enter the function.

But the `LOAD_CONST` and `RETURN_VALUE` came out of nowhere!
See the `(None)` in front of `LOAD_CONST`?
What that tells us is that Python will return the value `None` from the function.

Perhaps this is easier to interpret if we compare it to a function that just returns a simple string:

```py
def hello_world():
    return "Hello, world!"
```

If you dissect this function, here is what you get:

```pycon
>>> def hello_world():
...     return "Hello, world!"
...

>>> dis.dis(hello_world)
  1           0 RESUME                   0

  2           2 LOAD_CONST               1 ('Hello, world!')
              4 RETURN_VALUE
```


See how remarkably similar the two dissect outputs are?
The only difference is the value that is shown in front of `LOAD_CONST`, which is `None` for the function `empty` and `'Hello, world!'` for `hello_world`.
So, the instructions for `empty` resemble the instructions of a function whose only job is to return a specific value.

To really drive the point home, let us compare the dissect of `empty` with the result of dissecting a function that only contains the statement `return None`:

```pycon
>>> def return_none():                     | >>> def empty():
...     return None                        | ...     pass
...                                        | ...
>>> dis.dis(return_none)                   | >>> dis.dis(empty)
  1      0 RESUME               0          |   1      0 RESUME               0           
                                           |
  2      2 LOAD_CONST           0 (None)   |   2      2 LOAD_CONST           0 (None)    
         4 RETURN_VALUE                    |          4 RETURN_VALUE                     
```

This shows that Python essentially injected the `return None` in your empty function, but Python will do it in other cases too.
Whenever Python detects that your function doesn't return anything explicitly, Python will add a statement `return None` implicitly.

As a challenge, write a function that does something but without returning a value.
Use the module `dis` to dissect it and find the `return None` that Python inserted in the bytecode.

Here is an example of such a function:

```pycon
>>> def greet(name):
...     print(f"Hello, {name}!")
...
>>> dis.dis(greet)
  1           0 RESUME                   0

  2           2 LOAD_GLOBAL              1 (NULL + print)
             14 LOAD_CONST               1 ('Hello, ')
             16 LOAD_FAST                0 (name)
             18 FORMAT_VALUE             0
             20 LOAD_CONST               2 ('!')
             22 BUILD_STRING             3
             24 PRECALL                  1
             28 CALL                     1
             38 POP_TOP
             40 LOAD_CONST               0 (None)
             42 RETURN_VALUE
```


# Why do all functions return something?

! The explanation that follows is an _educated guess_ based on my experience.

We've seen that Python does some funky things with `return None` in some functions.
But why?

The reason Python will add `return None` in certain cases is because Python wants to ensure that calling a function in Python is always an expression.

In programming, loosely speaking, an expression is a piece of code that produces a value that can be used for other things.
For example, a mathematical calculation like `3 + x` is an expression because there is a _result_ associated with the `3 + x` and you can use that result for other things:

 - as the value for a variable assignment;
 - as an argument for a function call;
 - as part of an even larger expression;
 - ...

Next to expressions, we have statements.
Again, loosely speaking, statements are pieces of code that _do_ things with the language.
For example, an `if` is a statement that determines whether or not a piece of code should run.
The `if` itself doesn't produce a value, and that is why it is not an expression.

In Python, many function calls are expressions because they produce values.
For example, calling `max(3, x)` will produce a value, so `max(3, x)` is an expression.
So, for the sake of consistency, Python will make sure that all function calls are always expressions.

This simplifies the language a bit, as it would be fairly awkward to have some functions be expressions and some others be statements.
Or even worse, functions that could be both!
For example, if Python didn't add a `return None`, the function below would be an expression some times and a statement some other times:

```py
def sometimes_expression(num):
    if num > 0:
        return 1
    print("No dice.")
```

So, Python just went with the homogenous solution.
All function calls are expressions.
And that's it.

After all, [the Zen of Python][zen-of-python] says “Special cases aren't special enough to break the rules.”.


# Conclusion

Now you know that all Python functions return something:

 - the ones that look like they don't (e.g., `print` or `append`); and
 - even the ones you define that do not include explicit `return` statements.

This knowledge should give you a clearer understanding of how Python functions work.
It should also give you a deeper appreciation for the work that Python does to be a homogenous language with as few special cases as possible.
Having few(er) special cases means the language is easier to learn and, ultimately, easier to master.


[zen-of-python]: /blog/pydonts/pydont-disrespect-the-zen-of-python
