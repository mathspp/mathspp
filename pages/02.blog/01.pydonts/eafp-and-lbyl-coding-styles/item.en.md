---
metadata:
    description: "There are many situations in which it is better to use a try statement to handle an error than using an if statement to prevent the error."
title: "EAFP and LBYL coding styles | Pydon't 🐍"
---

In Python, if you are doing something that may throw an error, there are many
cases in which it is better to "apologise than to ask for permission".
This means you should prefer using a `try` block to catch the error,
instead of an `if` statement to prevent the error.

===

![A Python code snippet, using a `try` statement instead of an `if`.](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)

# EAFP and LBYL

"EAFP" is an acronym that stands for "Easier to Ask for Forgiveness than Permission",
a coding practice that is more or less the opposite of the "LBYL", which stands for
"Look Before You Leap".

LBYL means you first check if a given operation can be made successfully, and then
proceed to do it.
For example, if you want to ask the user for a number whose default value will be
1, you can use the code

```py
print("Type a positive integer (defaults to 1):")
s = input(" >> ")
if s.isnumeric():
    n = int(s)
else:
    n = 1
```

(In the code above, we use the method `str.isnumeric` to check if the string is a valid
integer. Try running `print(str.isnumeric.__doc__)` in your Python REPL.)

With EAFP, you first try to perform whatever operation it is you want to do, and
then use a `try` block to capture an eventual exception that your operation might
throw in case it is not successful.
In our example, this means we simply try to convert `s` into an integer and in
case a `ValueError` exception is raised, we set the default value:

```py
print("Type a positive integer (defaults to 1):")
s = input(" >> ")
try:
    n = int(s)
except ValueError:
    n = 1
```

We use `except ValueError` because a `ValueError` is the exception that is raised if
you try to convert to integer a string that doesn't contain an integer:

```py
>>> int("345")
345
>>> int("3.4")
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: invalid literal for int() with base 10: '3.4'
>>> int("asdf")
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: invalid literal for int() with base 10: 'asdf'
```

# EAFP instead of LBYL?

Writing code that follows the EAFP style can be advantageous in several situations,
and I will present them now.

## Avoid redundancy

Sometimes, coding with EAFP in mind allows you to avoid redundancy in your code.
Imagine you have a dictionary from which you want to extract a value associated
with a key, but that key might not exist.

With LBYL, you would do something like:

```py
d = {"a": 1, "b": 42}
print("What key do you want to access?")
key = input(" >> ")
if key in d:
    print(d[key])
else:
    print(f"Cannot find key '{key}'")
```

If the key that was entered exists in the dictionary,
this code performs two accesses to the dictionary: the first checks if `key` exists
as a key, and the second retrieves its value. This is more or less like you opening a
box to see if it contains something and closing it. Then, if the box was not empty, you
open it again and remove whatever is inside. Would you do this in real life?

With EAFP, you can open the box and immediately empty it if you find something inside:

```py
d = {"a": 1, "b": 42}
print("What key do you want to access?")
key = input(" >> ")
try:
    print(d[key])
except KeyError:
    print(f"Cannot find key '{key}'")
```

Still aligned with the EAFP mindset is a method that you should know about: `dict.get`!
This operation I described is so common that dictionaries even come with a method that
have a EAFP-like behaviour for when you want to take a value associated with a key,
and use a default value if the key is not present:

```py
d = {"a": 1, "b": 42}
print("What key do you want to access?")
key = input(" >> ")
print(d.get(key, None))
```

Try running the code above and type in keys that don't exist in your dictionary `d`.
Notice that `None` gets printed in those cases.

## EAFP can be faster

If failing is expected to happen not very often, then EAFP is faster:
you just run a piece of code (your operation) instead of two
(the "look" and the "leap").

As an example, let's go over the code from the example image above, using the
`timeit` module to see what option is faster when the input *can* be converted
to an integer:

```py
>>> import timeit
>>> eafp = """s = "345"
... try:
...     n = int(s)
... except ValueError:
...     n = 0"""
>>> timeit.timeit(eafp)
0.1687019999999393
```

Here we define `s` as an integer immediately so that the timing does not have to
take into account the time it takes for me to type an integer.
Also, the `timeit` function is running the code [a bunch](https://docs.python.org/3/library/timeit.html#timeit.timeit) of times and I don't want to have to type
one million integers in the console.

Now, compare it with the LBYL approach:

```py
>>> lbyl = """s = "345"
... if s.isnumeric():
...     n = int(s)
... else:
...     n = 0"""
>>> timeit.timeit(lbyl)
0.30682630000001154
```

The LBYL approach took almost twice the time.
If you can make it so that the operation fails very rarely, then you are saving
time by using a EAFP approach.

## LBYL may still fail

When interacting with the environment, for example with the Internet or with the OS,
in between the time it takes for you to do your safety check and then perform the
operation, circumstances may change and your operation may no longer be viable.

For example, imagine you have a script that is reading some files.
You can only read a file that exists, obviously, so an LBYL approach could entail
writing code like

```py
import pathlib

print("What file should I read?")
filepath = input(" >> ")
if pathlib.Path(filepath).exists():
    with open(filepath, "r") as f:
        contents = f.read()
    # Do something with the contents.
else:
    print("Woops, the file does not exist!")
```

If your script is in a computer that can be accessed by several users, or if there are
other scripts working with the file system, your `if` statement might evaluate to
`True` because the file was found, but then an external agent might delete the file
and your `with` statement fails, raising an error and breaking your code.
If you are writing critical code, this possibility has to be taken into account.
Or if the code your executing after the check takes a long time to run.

If you use an EAFP approach, the code either reads the file or doesn't, but both
cases are covered:

```py
print("What file should I read?")
filepath = input(" >> ")
try:
    with open(filepath, "r") as f:
        contents = f.read()
except FileNotFoundError:
    print("Woops, the file does not exist!")
else:
    # Do something with the contents.
    pass
```

The `else` in the `try` block above ensures you only run the code that processes the
`contents` if you are able to read the file.
(I'll write a Pydon't about this, don't worry!)

## Catch many types of fails

If you are trying to perform a complex operation that might fail in several ways,
it might be easier to just enumerate the exceptions that might be raised instead
of writing a really, really long `if` statement that performs all the necessary
checks in advance.

For example, if you want to call a third party function that might throw several
different exceptions, it is fairly simple to write an elegant `try` block
that covers all the cases that might arise.

Imagine you have a function that takes a string, representing an integer,
and then returns its inverse, but the person who wrote it performs no checks:
just assumes the string represents an integer, converts it with `int` and then
divides 1 by that integer:

```py
def get_inverse(num_str):
    return 1 / int(num_str)
```

You want to use that function in your code after asking for user input, but you
notice the user might type something that is not an integer, or the user might
type a 0, which then gives you a `ZeroDivisionError`.
With an EAFP approach, you write:

```py
print("Type an integer:")
s = input(" >> ")
try:
    print(get_inverse(s))
except ValueError:
    print("I asked for an integer!")
except ZeroDivisionError:
    print("0 has no inverse!")
```

How would you do this with LBYL? Maybe

```py
print("Type an integer:")
s = input(" >> ")
if s.isnumeric() and s != "0":
    print(get_inverse(s))
elif not s.isnumeric():
    print("I asked for an integer!")
else:
    print("0 has no inverse!")
```

But now you are using the function `isnumeric` twice.
And `isnumeric` doesn't even work for negative integers.
And what if the user types something like `" 3"`? `isnumeric` fails, but this
is still an integer that `int` can convert!
Or what if the user types `"000"`? This still evaluates to `0`...
I hope you get my point by now.

# Conclusion

EAFP code is a very good alternative to LBYL code, even being superior in
various alternatives, like the ones I mentioned above.
When writing code, try to weigh the different pros and cons of the several approaches
you can take, and don't forget to consider writing EAFP code!

EAFP is not the absolute best way to go in *every single situation*, but EAFP
code can be very readable and performant!

---

If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Do you have a nice example of some EAFP code that would be terrible with LBYL?
Or maybe the other way around?
Share with all of us by writing it in the comments below!

Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

# References

 - PEP 463 -- Exception-catching expressions, [https://www.python.org/dev/peps/pep-0463/](https://www.python.org/dev/peps/pep-0463/)
 - Python 3 Documentation, The Python Standard Library, Debugging and Profiling, `timeit`, [https://docs.python.org/3/library/timeit.html][timeit].
 - Python 3 Documentation, The Python Tutorial, Errors and Exceptions, [https://docs.python.org/3/tutorial/errors.html](https://docs.python.org/3/tutorial/errors.html).
 - Microsoft Devblogs, Idiomatic Python: EAFP versus LBYL, [https://devblogs.microsoft.com/python/idiomatic-python-eafp-versus-lbyl/](https://devblogs.microsoft.com/python/idiomatic-python-eafp-versus-lbyl/).
 - Stack Overflow, "What is the EAFP principle in Python?", [https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python](https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python).
 - Stack Overflow, "Ask forgiveness not permission - explain", [https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python](https://stackoverflow.com/questions/11360858/what-is-the-eafp-principle-in-python).

Online references consulted on the 19th of January of 2021.

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[timeit]: https://docs.python.org/3/library/timeit.html
