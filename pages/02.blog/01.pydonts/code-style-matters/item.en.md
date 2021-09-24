---
metadata:
    description: "In this Pydon't I talk about Python style and I go over some tools you can use to help you remain within a consistent style."
title: "Code style matters | Pydon't 🐍"
---

In this Pydon't I talk about Python style and I go over some tools you can use to help you remain within a consistent style.

===

![A badly formatted Python function](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

The overall style of your code can have a great impact
on the readability of your code.
And code is more often read than written,
so you (and others!) have a lot to benefit from you writing
well stylised code.

In this Pydon't, you will:

 - understand the importance of having a consistent style; and
 - learn about tools that help you with your code style.

By the way, this week I wrote a shorter and lighter article,
as I am still investing lots of time [preparing for
Euro Python 2021][europython2021]...
I hope you still find it useful!


<!--v-->
 > You can now get your copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->


# Code style

## Consistency

Humans are creatures of habit.
From the fact that the first leg that goes into your trousers is always the same,
to the fact that you always start brushing your teeth on the same side.

These habits automate routines that do not require much attention,
so that you can spend your precious brain power on other things.

As far as my experience goes, the same can be said about your coding style:
if you write with a consistent code style,
it becomes easier to read because you already expect a given structure;
you are only left with acquiring the information within that structure.

Otherwise, if your style isn't consistent,
you have to spend more precious brain power parsing the structure of what you are reading
and only then apprehend the information within that structure.

[PEP 8][pep-8] is a document whose purpose is to outline a style guide
for those who write Python code.
It has plenty of useful recommendations.
However, right after the introduction, PEP 8 reads

 > “A style guide is about consistency.
 > Consistency with this style guide is important.
 > Consistency within a project is more important.
 > Consistency within one module or function is the most important.
 >
 > However, know when to be inconsistent -- sometimes style guide recommendations just aren't applicable.
 > When in doubt, use your best judgment.
 > Look at other examples and decide what looks best.
 > And don't hesitate to ask!”

This is very important: PEP 8 is a style guide that contains _recommendations_,
not laws or strict rules.
And what is more, notice that there is a strong focus on _consistency_.
Using your own (possibly weird) style consistently is better than using no style at all.
That's if you are working alone; in a project, it is a good idea to decide on
a particular style beforehand.


## Whitespace matters

When I'm teaching Python, I often do some sort of live coding,
where I explain things and type examples,
that I often ask students to type as well.
I have noticed that people that are _just starting_ with Python
will often copy the words that I'm typing,
but won't respect my whitespace usage.

It is a bit of an exaggeration, but I might type

```py
def f(a, b, x):
    return a*x + b
```

and then they will type things like

```py
def f  (a,b ,  x):
    return a *x+ b
```

Python is the language where whitespace matters
(because Python uses indentation to nest structures),
but whitespace turns out to be important in more places than just those.

For example, above, we can see that the misuse of blank spaces
makes the second definition of `f` much more hectic and aesthetically unpleasant.
And if there is one thing we know, is that [elegance matters][pydont-elegance].

If you skim through PEP 8 you will find that _most_ recommendations there are about whitespace.
Number of empty lines around functions, classes, methods;
whitespace around operators and keywords;
whitespace before/after commas; etc.
Take a look at PEP 8 and gradually try to incorporate some recommendations into your coding.

For example, PEP 8 suggests that you use whitespace to help the reader
parse the priority of mathematical operations in an expression.
Above, I wrote

```py
return a*x + b
```

surrounding `+` with blanks,
to indicate that the `a*x` (notice the lack of blanks around `*`) has higher priority.
Of course, the number of blank spaces I use doesn't alter the order of operations,
but it helps you _see_ the order of things.

And the cool thing is that the more used to writing like this you are,
the more helpful it becomes!

Again, my suggestion is that you take a look at PEP 8 and pick a couple of recommendations
you enjoy and try incorporating those into your coding.
When those sink in, add a couple more.
And just roll with that.
Easier than trying to change everything all at once.


# Tools for your tool belt

On a happier note,
there are _many_ tools you can use that help you format your code
and keep it neat and tidy.


## Auto-formatters

### `black`

A class of tools that you can use is what are known as (auto-)formatters,
of which `black` is a prime example (see their repo [here][black]).

Auto-formatters like `black` take your code and reformat it so that it fits
within the style that the tool supports/you configure.

For example, let me create the file `my_f.py` and paste this code in there:

```py
# In my_f.py
def f  (a,b ,  x):
    return a *x+ b
```

Now let me run `black` on that file:

```py
 > python -m black my_f.py
reformatted my_f.py
All done! ✨ 🍰 ✨
1 file reformatted.
```

Now, I open my file and this is what is inside:

```py
# In my_f.py
def f(a, b, x):
    return a * x + b
```

As we can see, `black` took my code and just reformatted it to the style
that `black` adheres to.
`black`'s style is fairly similar to PEP 8's style and `black` is a great
tool if you just want to have something automatically helping you reformat your code,
so that you don't have to think too much about it.

`black` is as easy to install as any other Python module:

```py
python -m pip install black
```

There are many tools like `black` out there;
another common option is `pycodestyle`.

#### `pycodestyle`

`pycodestyle` checks if your style is similar to what PEP 8 recommends.
In fact, `pycodestyle` used to be called `pep8`,
but was renamed so that people understand that:

 1. PEP 8 isn't a set of rigid rules; and
 2. `pycodestyle` doesn't match PEP 8's recommendations 100%.

Let me modify the file `my_f.py` to the following:

```py
# In my_f.py
import os, time
def f(a, b, x):
    return a * x + b
```

If I run `pycodestyle`, this is what I get as output:

```py
 > python -m pycodestyle my_f.py
my_f.py:2:10: E401 multiple imports on one line
my_f.py:3:1: E302 expected 2 blank lines, found 0
```

We can see that `pycodestyle` complained about a couple of things:

 1. the fact that I merged `import os` and `import time`; and
 2. the fact that there aren't enough empty lines separating the imports from `f`.

A big difference between `black` and `pycodestyle` is that `black`
does reformat your code, whereas `pycodestyle` just complains.

Installing `pycodestyle` is just a matter of typing

```py
python -m pip install pycodestyle
```

For both tools, and for most of the similar tools out there,
you can configure them to ignore types of errors, or ignore sections of your code, etc.
Just go read their documentation!


## Level up (aka linters)

(Auto-)Formatters are helpful, but there are other tools out there that have even more potential: linters.

Linters are tools that analyse your code and help you find things like

 - stylistic issues (like the formatters do);
 - programming errors;
 - some types of bugs;
 - etc.

These tools can be incredibly helpful, for example, to manage all the imports in a big project.
I often find myself importing some modules and using them.
Later, [I refactor my code][pydont-refactor], and I stop needing those imports.
When I do that, I _always_ forget to check if the imports are still needed or no longer relevant.
Linters can, for example, flag unused imports.

An example of a linter is `flake8` (you can find it [here][flake8]).
If I use `flake8` on my `my_f.py` file, here is what I get:

```py
 > python -m flake8 my_f.py
my_f.py:2:1: F401 'os' imported but unused
my_f.py:2:1: F401 'time' imported but unused
my_f.py:2:10: E401 multiple imports on one line
my_f.py:3:1: E302 expected 2 blank lines, found 0
```

You can see that now `flake8` is complaining about the fact that I am importing things that I don't use at all!
Not only that, but the two bottom lines are identical to `pycodestyle`'s output above...
And that's because `flake8` uses `pycodestyle` within itself.

You can install `flake8` with

```py
python -m pip install flake8
```

Another fairly common alternative for a linter is `pylint` ([`pylint`'s page][pylint]).
Running it on the same `my_f.py` file, I get some more warnings:

```py
 > python -m pylint my_f.py
************* Module my_f
my_f.py:1:0: C0114: Missing module docstring (missing-module-docstring)
my_f.py:2:0: C0410: Multiple imports on one line (os, time) (multiple-imports)
my_f.py:3:0: C0103: Function name "f" doesn't conform to snake_case naming style (invalid-name)
my_f.py:3:0: C0103: Argument name "a" doesn't conform to snake_case naming style (invalid-name)
my_f.py:3:0: C0103: Argument name "b" doesn't conform to snake_case naming style (invalid-name)
my_f.py:3:0: C0103: Argument name "x" doesn't conform to snake_case naming style (invalid-name)
my_f.py:3:0: C0116: Missing function or method docstring (missing-function-docstring)
my_f.py:2:0: W0611: Unused import os (unused-import)
my_f.py:2:0: W0611: Unused import time (unused-import)

-------------------------------------
Your code has been rated at -20.00/10
```

We can see that `pylint` was more unforgiving,
complaining about the fact that I did not include docstrings
and complaining about my 1-letter names.
This might be something you appreciate!
Or not!

I reckon personal taste plays a big role in picking these tools.

Installing `pylint` can be done through

```py
python -m pip install pylint
```



# Conclusion

As far as these tools are concerned,
I suggest you pick something that is fairly consensual for your personal projects,
so that it doesn't hurt you too much when you contribute to other projects.
For open source projects, you will often be asked to follow a given style,
and there may or may not be tools that help you reformat your code to follow
that style.

This article was not supposed to be a thorough review of all the possibilities
there are out there, I only touched upon a couple of popular alternatives,
so that might be a decent indicator of things that are consensual.

By the way, many IDEs these days have integrated support for these linters,
making it even easier to harness their helpful suggestions.

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Pay attention to the style with which you write code and pick a suite of tools to help you if you want/need.*”

This Pydon't showed you that:

 - coding style has an impact in code readability;
 - tools like `black` and `pycodestyle` can help you fix the style of your code; and
 - linters like `flake8` and `pylint` can give further insights into some types of errors/bugs/problems your programs might have.


If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-elegance]: /blog/pydonts/does-elegance-matter
[pydont-refactor]: /blog/pydonts/bite-sized-refactoring
[black]: https://github.com/psf/black
[pep-8]: https://www.python.org/dev/peps/pep-0008/
[pycodestyle]: https://pycodestyle.pycqa.org/en/latest/intro.html
[pylint]: https://www.pylint.org/
[europython2021]: https://ep2021.europython.eu/profiles/rodrigo-girao-serrao/
[flake8]: https://flake8.pycqa.org/en/latest/
