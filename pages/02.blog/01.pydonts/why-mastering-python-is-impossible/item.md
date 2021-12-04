Let me tell you why it is impossible to truly master Python, but also show you how to get as close to it as possible.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![A rocky trail heading up a hill.](thumbnail.png "Photo by Migle Siauciulyte on Unsplash.")


# Introduction

[It has been said][wiki-outliers] that you need 10,000 hours to master a skill.
I won't dispute if that's true or not.
What I'll tell you is that, even if that's true,
I'm not sure it applies to Python!

In this Pydon't, I'll explain why I think you can't really _master_ Python,
but I'll also tell you why I think that's ok:
I'll give you a series of practical tips that you can use to make sure
you keep improving your Python knowledge.

Finally, by the end of the Pydon't,
I'll share a little anecdote from my own personal experience with Python,
to support my claims.

<!--v-->
!!! You can now get your free copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
!!! to help support the series of “Pydon't” articles 💪.
<!--^-->


# “to master”, verb

Here's the [dictionary definition][dict-master] of the verb “to master”:

 > “master”, verb – to learn or understand something completely

From my personal experience,
there are two levels at which I believe one cannot master Python;
I'll lay both of them down now.


## Python is an evolving language

The Python language is an evolving language: it isn't a finished product.
As such, it keeps growing:

 - new functions get added;
 - new syntax is introduced;
 - the standard library changes;
 - ...

Therefore, I can never know everything about it!
As soon as I think I just learned all the things there are to learn,
new things pop up.

This is something I believe in, but it is also almost a philosophical point of view.
There is also a practical side to this argument.


## Python is just too big

Not only does the language keep changing,
one can argue that the Python language is already too big for you to be able to master it.

For example, most of us are familiar with the list methods `.append` or `.pop`.
But, from my experience, most people aren't familiar with the list methods `.copy`, or `.extend`, for example.
In fact, let's do an experiment: can you name the 11 existing list methods?

Scroll to the bottom of the page and write them down as a comment.
If not the 11, write down as many as you can remember.

Here are they:

```py
>>> [name for name in dir(list) if not name.startswith("__")]
['append', 'clear', 'copy', 'count', 'extend', 'index', 'insert', 'pop', 'remove', 'reverse', 'sort']
```

! No idea what `dir` is? Just [scroll down](#dir-and-help).

Maybe you even knew about all of them,
but being able to name them is hard, right?

Let's do a similar thing for strings!
First, jot down as many string methods that you can remember.

Done?

Great.
Now count them.
How many did you get?

Now, how many string methods do you _think_ there are?

There are 47 (!) string methods!

Probably, you never even heard about some of them:

```py
>>> [name for name in dir(str) if not name.startswith("__")]
['capitalize', 'casefold', 'center', 'count', 'encode', 'endswith', 'expandtabs', 'find', 'format', 'format_map', 'index', 'isalnum', 'isalpha', 'isascii', 'isdecimal', 'isdigit', 'isidentifier', 'islower', 'isnumeric', 'isprintable', 'isspace', 'istitle', 'isupper', 'join', 'ljust', 'lower', 'lstrip', 'maketrans', 'partition', 'removeprefix', 'removesuffix', 'replace', 'rfind', 'rindex', 'rjust', 'rpartition', 'rsplit', 'rstrip', 'split', 'splitlines', 'startswith', 'strip', 'swapcase', 'title', 'translate', 'upper', 'zfill']
>>> len(_)
47
```

This just speaks to my point.
Strings have 47 methods, many of them with a couple arguments.

Lists and dictionaries have 11 methods and sets have 17.

Python (3.9) has 69 built-in functions.
Do you know all of them?
Do you think you could _know_ all of them?

I'm sure you could memorise all the built-in functions, but this is not about memorising!
Programming is about building things, solving problems,
and having memorised the names of all the functions won't help you very much...

However, being vaguely aware of them, will!


# How to get as close to mastering Python as possible

## Getting exposure

A good programmer uses the right tool for the job,
but you can only use the right tools if you know they exist,
and if you know where to find them.

This means that, while you won't be able to master Python,
in the sense of “to learn or understand something completely”,
you can get pretty darn close!

And, from my experience, you get there by getting exposure to as much of Python as possible.
Even if you can't know everything, it does help if you read about – and play around with –
as many features as possible.

By being exposed to new/different things, your brain is likely to take a small note
about the new things you try or play around with.
You might not be able to name all these things you play around with off the top of your head, sure.
The rationale is that, when the time comes,
there is a chance that your brain will light up 💡 and you'll remember you've seen something relevant in the past.

This situation is pretty much cut and dried:

 - if you've never seen a function that would be useful right now, it is _impossible_ for you to remember about it;
 - if you _have_ seen a function that would be useful right now, you _may_ remember it!

So, it all boils down to figuring out how you can get as much exposure to all the different parts of Python.
To get exposure to different parts of Python, you just need to explore the language and play around with it!

Let me tell you about some things that will probably help you find new things to learn about and explore.

Just a quick **disclaimer**, though: most of the things that I'm about to tell you
are _unstructured_ ways of learning more about Python.
Depending on how far down the road you are in your Python journey,
the things I'm telling you about may make more or less sense.
All in all, if you are starting your Python journey now,
I would recommend that you find a way of learning Python
that is not simply by following these ad-hoc methods.


## Documentation

Are you familiar with Python's documentation?
It's at a URL that is easy to remember: <https://docs.python.org>.

The docs are a great learning resource!
If you ever find yourself without knowing what to learn,
open the documentation and navigate through it for a bit.
You are likely to end up somewhere you've never been.

But visiting new pages in the docs isn't the only way to learn new things.
I keep referencing some pages over and over and over,
because they just have so much information!
Here are a couple of pages I like to visit often:

 - [the page](https://docs.python.org/3/library/functions.html) with all the built-in functions;
 - [the page](https://docs.python.org/3/library/stdtypes.html) with all the built-in types and their methods;
 - [the page](https://docs.python.org/3/py-modindex.html) containing the index of all the modules of the Standard Library; and
 - [the page](https://docs.python.org/3/reference/datamodel.html) about the data model (that's where you can find out about most of the dunder names);


## `dir` and `help`

Another great way of finding more things to learn revolves around going to the REPL
and making use of two great functions: `dir` and `help`.

The `help` function, which you'll know about if you read
[the Pydon't about improving productivity in the REPL][pydont-repl],
accepts a Python object and will print its docstring,
giving you information about that object:

```py
>>> help(max)
Help on built-in function max in module builtins:

max(...)
    max(iterable, *[, default=obj, key=func]) -> value
    max(arg1, arg2, *args, *[, key=func]) -> value

    With a single iterable argument, return its biggest item. The
    default keyword-only argument specifies an object to return if
    the provided iterable is empty.
    With two or more arguments, return the largest argument.
```

For example, did you know that `max` can accept the keyword arguments `default` and `key`?

Paired up with `help`, you can definitely make use of `dir`:

```py
>>> help(dir)
Help on built-in function dir in module builtins:

dir(...)
    dir([object]) -> list of strings

    If called without an argument, return the names in the current scope.
    Else, return an alphabetized list of names comprising (some of) the attributes
    of the given object, and of attributes reachable from it.
    ...
```

In essence, you can use `dir` to find out more about the attributes of an object.
For example, you can easily find what methods strings have with `dir(list)`,
like you saw above.
However, earlier I filtered most of the attributes out to ignore dunder attributes.
Without any filtering, I'm sure `dir` will show you things you don't know yet:

```py
>>> dir(list)
['__add__', '__class__', '__class_getitem__', '__contains__', '__delattr__', '__delitem__', '__dir__', '__doc__', '__eq__', '__format__', '__ge__', '__getattribute__', '__getitem__', '__gt__', '__hash__', '__iadd__', '__imul__', '__init__', '__init_subclass__', '__iter__', '__le__', '__len__', '__lt__', '__mul__', '__ne__', '__new__', '__reduce__', '__reduce_ex__', '__repr__', '__reversed__', '__rmul__', '__setattr__', '__setitem__', '__sizeof__', '__str__', '__subclasshook__', 'append', 'clear', 'copy', 'count', 'extend', 'index', 'insert', 'pop', 'remove', 'reverse', 'sort']
```


## Teach

I hear this a lot, but maybe you don't, so let me break it to you:
teaching someone actually improves your understanding of the thing you are teaching.

This happens to me all the time:
I set out to write an article on something I _think_ I know pretty well,
and as I do my research, I learn a bunch of new things.

Teaching is a great way of learning also because it forces you to actively think
about the things you are trying to teach.
Just the “simple” act of figuring out how to best explain something turns out to be really useful as well!

On top of that, many students ask questions,
and you are likely to be caught off guard by some of those questions.

If you don't have a human being next to you that is learning Python,
you might be thinking that you have no one to teach.
You couldn't be further from the truth.

There are plenty of communities out there where Python is a topic of discussion.
Plenty of people learning Python gather in those communities,
and where there are learners, there are opportunities to teach.

Here are a couple of examples:

 - [StackOverflow](https://stackoverflow.com);
 - [Reddit's /r/Python](https://reddit/r/python); and
 - [Reddit's /r/learnpython](https://reddit.com/r/learnpython/);


## Read source code

This is, _hands down_, one of the most underappreciated skills a programmer needs to have:
knowing how to read code is a skill that you also need to practise!
We are all taught how to _write_ code, but we are never taught how to _read_ code...
And yet, we spend hours reading code!

By reading code you get exposed to different use cases for the functions you already know,
you learn about new tools, you get to see different coding styles,
new algorithms, among other things!

Is there a module that you use all the time?
Take a peek at its source code.
It doesn't even have to be a third-party module.
If I'm not mistaken, most of Python's standard library is written _in Python_.
Go [take a look][cpython-gh].


## Get acquainted with the Standard Library

Again, being a programmer is all about using the right tools for the job.
The Python Standard Library has more than 249 modules?

I know, because I wrote a huge Twitter thread listing them all:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Did you know that Python 🐍 has over 240 modules in the standard library?<br><br>Here is a MEGA thread 🧵with a super high level overview of those modules...<br><br>... but before that, I challenge you to name as many modules by heart as possible! 💬👇<br><br>Let&#39;s see who gets more!</p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1430108219872002048?ref_src=twsrc%5Etfw">August 24, 2021</a></blockquote>

You certainly don't know all of them!
Which is fine!
But just goes to say: you can still learn a lot more about Python.

You don't even need to go deep into forsaken modules.
Are you _well_ familiarised with `functools`, `collections`, `functools`?
What about `random`? `pathlib`?
These are just some of the _really_ useful modules that you could learn more about!


## Find out about more Python packages

Not only can you work on getting more acquainted with Python's Standard Library,
but you can also try to get more acquainted with any number of Python packages out there!

Projects like Flask, Django, Rich, Pandas, NumPy, PyTorch, and Tensorflow,
are staples in the Python community.
You can pick a project that's related to the niche(s) you use Python in,
or you can opt for studying a module that is more general purpose.
Either way, you will certainly learn a lot from doing that!

If you've got no ideas of modules to look at,
you can always [search PyPI][pypi-browse] or [explore GitHub][gh-explore-python].


## Learn OTHER languages

This might seem paradoxical, but learning _other_ languages can boost your Python skills.
I am speaking from experience; I have _felt_ this.

Programming is, among other things, about solving problems.
You need to go from A to B, and you need to pick a path that takes you from A to B.
You need to figure that out.

The path you pick to go from A to B will depend on your experience and background, right?

 - Because of my formal education in mathematics,
 I often try to come up with a more maths-oriented solution to some programming challenges I face.
 Those won't necessarily be better or worse, but they will definitely be _different_.

 - More experienced people are more likely to have already seen a problem that is similar,
 and thus might have a better idea of which things to try first.

As it turns out, the programming languages you know _also_ affect how you think about the problems.
Sadly, this isn't necessarily true for all other languages out there.
To maximise the impact that a new language will have on your proficiency with other languages,
you need to learn a new language that is _fundamentally different_ from everything else you've learned.

So, if you know Python, you can learn a lot of new things if you pick up JavaScript,
C, C++, Perl, Rust, or some other language like this.
However, these languages are fundamentally the same: they are all imperative languages.

The paradigm of imperative languages isn't the only paradigm out there.
If you learn a new language from a new paradigm,
you will be shown a new way of thinking,
and that opens up new possibilities when writing code in other languages as well.

Alan Perlis, the first recipient of a Turing Award (the “Nobel Prize of Computing”) puts it eloquently:

 > “A language that doesn't affect the way you think about programming, is not worth knowing.”
 > ― Alan Perlis, Epigrams on Programming

For me, particularly, [Haskell](https://www.haskell.org/) and [APL](https://apl.wiki)
changed the way I think about programming (and henceforth, influenced my Python code)
much more than all other languages I've used.

Sadly, it's hard to quantify by how much my programming improved,
or what specific things changed...
It's one of those things you really have to see for yourself.

If you'd like to learn about some other fundamentally different paradigms
and languages you can learn from those paradigms,
take a look at [this blog post](https://codereport.github.io/Galaxy-Brain-Languages/).
It is non-technical and a light read – a great first step into learning a
_completely new language_.


# Anecdote with string methods

I am, by no means, a Python expert.
I have a lot of fun with Python, learning it and teaching it,
and building stuff with it.
I've been doing so since 2012.
And yet, _every single time_ I use Python, I learn something new.

Personally, I am trying to get really fluent with the core Python:
all of its core syntactic features and the standard library.
Why?
Not sure; I think it's because I have recognised there is _huge_
potential in these core things, and I want to get really good at using them.

I have been studying the core of Python for a while now,
and I like to think I am getting good at it...
Except, clearly, I still have a lot to learn!

Here is a tweet I published:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Here&#39;s a Python 🐍 challenge for you 🏆<br><br>`swap_casing` is a VERY simple function.<br><br>It accepts a string, and:<br>👉 turns uppercase letters into lowercase letters;<br>👉 and vice-versa.<br><br>What&#39;s the most Pythonic 🐍 implementation you can think of? <a href="https://t.co/5zkZ7pHqvx">pic.twitter.com/5zkZ7pHqvx</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1458350716301426688?ref_src=twsrc%5Etfw">November 10, 2021</a></blockquote>

I posted that challenge,
only to find out later that strings already have that method baked into them:

```py
>>> "Hello, world!".swapcase()
'hELLO, WORLD!'
```

This is just a small piece of anecdotical evidence that there is _always_ something
else to learn about Python.
I even built my Pyjamas Conf 2021 talk around this!


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*There is always something new to learn. Don't shy away from that.*”

This Pydon't showed you that:

 - you can learn a lot about Python from the docs;
 - the built-in functions `help` and `dir` can teach you about new things;
 - teaching boosts your learning;
 - reading code is another great way of improving your skills;
 - exploring the Python Standard Library will expose you to many new tools;
 - and so will exploring the Python Package Index; and
 - learning _other_ languages can improve your Python code.

<!-- v -->
If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!
<!-- ^ -->

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-repl]: /blog/pydonts/boost-your-productivity-with-the-repl

[twitter-thread-psl]: https://twitter.com/mathsppblog/status/1430108219872002048

[wiki-outliers]: https://en.wikipedia.org/wiki/Outliers_(book)
[dict-master]: https://www.oxfordlearnersdictionaries.com/definition/english/master_2
[cpython-gh]: https://github.com/python/cpython/tree/main/Lib
[pypi-search]: https://pypi.org/search/
[gh-explore-python]: https://github.com/topics/python
