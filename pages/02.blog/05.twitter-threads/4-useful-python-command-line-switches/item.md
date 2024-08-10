---
date: 05-09-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This short article teaches you 4 common switches to use in the command line with Python."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
publish_date: 05-09-2022 15:00
slug: 4-useful-python-command-line-switches
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "4 useful Python command line switches"
---

This short article teaches you 4 common switches to use in the command line with Python.

===

The Python 🐍 command has many different switches.

Here are the 4 switches I use the most:

 1. `-c cmd`: program passed in as string;
 2. `-m mod`: run library module as a script;
 3. `-i`: inspect interactively after running script; and
 4. `-q`: don't print version and copyright messages on interactive startup.


## `-c`

The switch `-c` runs code directly from the command line.
It doesn't open the REPL, and it is convenient for short, one-off expressions.
The result isn't printed by default, so don't forget your `print`!

```
# What is the factorial of 15?
λ python -c "import math; print(math.factorial(15))"
1307674368000

# What is 2 + 2?
λ python -c "print(2 + 2)"
4
```


## `-m`

The switch `-m` runs a module as a script.
This will run an installed module's section that is inside `if __name__ == "__main__":`.
The one I use the most is the module `timeit` to measure execution time:

```bash
λ python -m timeit -s "import math" "math.factorial(15)"
2000000 loops, best of 5: 167 nsec per loop

λ python -m timeit -s "import math" "math.factorial(150)"
200000 loops, best of 5: 1.52 usec per loop
```


## `-i`
The switch `-i` stands for Inspect Interactively.
By running your code with `-i`, after the script is done,
you get a REPL session with the variables and functions from that script.
Useful to play around with functions you just defined.

Suppose this is your file `example.py`:

```py
x = 3
y = 5

def add(x, y):
    return x + y
```

If you run it with `-i`, you get to play around with the variables `x` and `y` and with the function `add`:

```bash
λ python -i example.py
>>> x
3
>>> y
5
>>> add(x, 10)
13
```


## `-q`

The switch `-q` opens the REPL Quietly.
What this means is that it opens the REPL without displaying all the version/platform information.
I use it when recording videos and demoing things.

```bash
λ python -q
>>> # This is a standard REPL
```


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1566777236035870720) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
