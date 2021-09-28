---
metadata:
    description: "This Pydon't explains the importance of refactoring your code and gives you hints on what to look for when refactoring code."
title: "Bite-sized refactoring | Pydon't 🐍"
---

In this Pydon't I show you why refactoring is important
and show you how to do it in little steps,
so that it doesn't become too overwhelming.

===

![An abstract background with the Python logo and three arrows in a circle, alluding to the 'recycle' symbol.](_thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

Refactoring code is the act of going through your code and
changing bits and pieces, generally with the objective of
making your code shorter, faster, or better any metric you set.

In this Pydon't I share my thoughts on the importance of refactoring
and I share some tips for when you need to refactor your code,
as I walk you through a refactoring example.

In this Pydon't, you will:

 - understand the importance of refactoring;
 - walk through a real refactoring example with me; and
 - learn tips to employ when refactoring your own code.


<!--v-->
 > You can now get your free copy of the ebook “Pydon'ts – Write beautiful Python code” [on Gumroad][gumroad-pydonts]
 > to help support the series of “Pydon't” articles 💪.
<!--^-->


# Refactoring

 > REFACTOR – verb
 >
 > “restructure (the source code of an application or piece of software) so as to improve operation without altering functionality.”

As you can see from the definition above,
the act of refactoring your code is an attempt at making your code better.
Making your code better might mean different things,
depending on your context:

 - it might mean it is easier to maintain;
 - it might mean it is easier to explain to beginners;
 - it might mean it is faster;
 - ...

Regardless of the metric(s) you choose to improve,
everyone can benefit from learning to refactor code.

Why is that?

When you are refactoring code you are training a series of skills
that are helpful to you as a developer, like your ability to read
code and really comprehend it, pattern recognition skills,
critical thinking, amongst others.


## Ability to read code and really comprehend it

If you change a piece of code without understanding it,
you are much more likely to break it.
Therefore, when you want to refactor a piece of code,
you should do your best to try and _really_ comprehend what the code
is doing and how it does it.


## Pattern recognition skills

One of the things that you should be looking out for,
when refactoring code,
is redundancies and repetitions.
If you see code that looks like it was copied and pasted,
or if you find code that has a very similar structure,
then it probably is a good target for refactoring.

Sometimes, spotting these things is very simple,
because there will be lines of code that are _identical_.
However, finding _structural_ similarities between
different parts of your code is harder than finding identical lines,
so in trying to spot these you will be training your pattern
recognition skills.
Beware that this becomes much easier to do _after_
you have really understood the code.


## Critical thinking

When reading code you wish to refactor,
you will invariably find pieces of code that look like they shouldn't
be there.

This can have many meanings.

It might be a piece of code that is in the wrong file.
A piece of code that is in the wrong function.
Sometimes, even, a piece of code that looks like it could/should
be deleted.
At these points in time, the only thing you can do is use your brain
to figure out what are the implications of moving things around.
You shouldn't be afraid to move things around,
_after_ you have considered what are the implications of leaving
things as-is versus changing them.

Remember, [you should strive to write elegant code][pydont-elegance],
and part of that entails writing code
in a way that makes it as easy as possible to refactor later on.
Code is a mutable thing, so make sure to facilitate the life
of your future self by writing elegant code that is easy to read.


# What to refactor?

I am sure that people with different life experiences will answer
differently to this question,
the only thing I can do is share my point of view on the subject.

## Refactor often...

... or at least create the conditions for that.

If you have the possibility to refactor a piece of code and you _know_
there are things that can be improved upon, go ahead and do it.
As you mature as a developer and gain experience,
you keep learning new things;
on top of that, the technologies you are using are probably
also evolving over time.
This means that code naturally goes into a state where it could
benefit from refactoring.


This is a never-ending cycle: you should write code that is
elegant and easy to read; that means that, in the future,
refactoring the code is easier and faster;
refactoring makes the code easier to read and even more elegant;
which makes it easier to refactor in the future;
that will make it easier to read and more elegant;
and so on and so forth.

Code refactoring shouldn't be a daunting task because there is
much to gain from it, so make sure to write your code in a way
that will allow you, or someone else, to refactor it later.


## Refactor little by little

Of course there should be a balance between refactoring
code that already exists and writing new code for new features, etc.

Refactoring often makes it a very manageable task that you can
actually learn to appreciate.
If you don't refactor often, you let all these sub-optimal structures,
bad design choices, etc, pile up, and those will be much more
difficult to fix all at the same time.

Refactor little by little, at your own scale.
If you are a very fresh beginner, this might mean that you want
to refactor a line of code at a time, or maybe a couple of lines.
If you are much more experienced, this might mean you are refactoring
one or more files at the same time.
Just refactor “a little”, regardless of what that means to you.


# Case study

Now I will go in-depth into a short Python function that was written
by a beginner and [shared to Reddit][case-study]
I will walk you through the process that happened in my brain
when I tried refactoring that piece of code,
and I will share little tips as we go along.

First, let me tell you the task that the code is supposed to solve.

Write a function that changes the casing of its letters:

 - letters in even positions should become uppercase; and
 - letters in odd positions should become lowercase.

Go ahead and try solving this task.


## Starting point

The piece of code that was shared on the Internet was the following:

```py
def myfunc(a):
    empty=[]
    for i in range(len(a)):
        if i%2==0:
            empty.append(a[i].upper())
        else:
            empty.append(a[i].lower())

    return "".join(empty)
```

## Automatic style formatting

The very first step you can take towards writing code that is as
elegant as possible is running an auto formatter.
If you use [`black`][black], for example, you can fix many style
issues and inconsistencies right from the get-go.

In this case, running `black` on the code above produces the following:

```py
def myfunc(a):
    empty = []
    for i in range(len(a)):
        if i % 2 == 0:
            empty.append(a[i].upper())
        else:
            empty.append(a[i].lower())

    return "".join(empty)
```

The only difference here was the spacing in `empty = []`
and in `if i % 2 == 0:`.
Spacing around operators is very important because it gives
your code room to breathe.
Making sure that your code has a consistent style goes
a great length in making it readable to yourself and to others,
so do try and build the habit of following a certain style.

[PEP 8][pep-8] proposes a Python style and many follow that style,
so it might be a good idea to take your time to review that
style guide.
After you figure out how that style works, remember that
you don't need to start doing everything at the same time.
You can pick that style up gradually.
Also, recall that critical thinking is very important.
Sometimes it is best to ignore the style guide completely.


## Naming

Names are very important, and naming your functions and variables
correctly is crucial.
Names can make or break a program.
Good names aid the reader of the code, whereas bad names make you
spend hours analysing otherwise simple code.

Names should reflect the intent, or a very important property,
of the thing they refer to.
This is the opposite of using very generic names,
like `myfunc` for a function or `num` for a number,
when that function has a specific role or that number
contains some specific information.

A notable exception is the usage of `i` in for loops, for example,
although personally I tend to prefer the slightly more verbose `idx`.

So, looking at the code we currently have,
I can identify three names that could be improved upon.
Can you figure out what those are?
Have a go at changing them to something better.

Now, your suggestion doesn't have to match mine,
but here is what I came up with:

```py
def alternate_casing(text):
    letters = []
    for idx in range(len(text)):
        if idx % 2 == 0:
            letters.append(text[idx].upper())
        else:
            letters.append(text[idx].lower())

    return "".join(letters)
```

Here are the changes that I made:

 - `myfunc` -> `alternate_casing`;
 - `a` -> `text`;
 - `empty` -> `letters`; and
 - `i` -> `idx` (because of my personal preference).

Now, in and of itself, `empty = []` seems to be a pretty good name.
_However_, right after we initialise `empty` with the empty list,
we start filling it in, and so the name doesn't reflect a property
of the object that holds throughout the program or that is important.
Instead, by naming it `letters`,
we specify what will be stored in there.


## Traversing data and indices

Python has some really good capabilities to deal with `for` loops,
and one of the tools that we are given is the `enumerate` built-in.

I [wrote at length about `enumerate` before][pydont-enumerate],
so just be advised that `enumerate` is the tool to reach for when you
write a `for` loop where you need to work with the indices _and_
the data at the same time.

In our function we need the indices _and_ the data,
because we need the index to determine the operation to do,
and then we need the data (the actual letter) to change its casing.
Using `enumerate`, here is how that loop would end up:

```py
def alternate_casing(text):
    letters = []
    for idx, letter in enumerate(text):
        if idx % 2 == 0:
            letters.append(letter.upper())
        else:
            letters.append(letter.lower())

    return "".join(letters)
```

Not only we were able to remove the explicit indexing,
therefore cutting down on one operation,
but we also express our intent more clearly:
when someone finds an `enumerate`, they should immediately
understand that to mean “in this loop I need both the indices
and the data I'm traversing”.


## Nest only what is needed

In Python, indentation indicates code nesting,
which indicates dependence.
If a line of code is nested inside a `for` loop,
it means it depends on the `for` loop.
If it is further nested inside an `if` statement,
it means it only applies when certain conditions are met.
If it is further nested inside a `try` statement,
we may expect it to raise an error, etc.

Nesting code means we need to keep track of many contexts
in our head while we read the code,
and even though you might not notice it,
that's exhausting.
Going in and out of all those indented structures,
making all those context switches, consumes brain power.
Flatter code places less strain on our brains and makes it easier
to keep up with the code.

To make it simpler to keep up with the context,
we should try and nest as little code as possible.
We should only nest the pieces of code that are
absolutely necessary to be nested.

For `for` loops, that's generally things that depend
on the iterator variables between `for` and `in`,
and for `if`-`else` statements,
that's the pieces of code that are _unique_ to each statement.

Now, in the `if`-`else` statement above,
can you spot something that is not _unique_ to a single branch?
Here is the code:

```py
if idx % 2 == 0:
    letters.append(letter.upper())
else:
    letters.append(letter.lower())
```

Notice that we are doing a `letters.append` _regardless_
of the branch we are in, which makes it less clear that
the thing that is changing from one branch to the other
is the choice of method that we call on `letter`.
It is even _less_ clear because `.upper()` and `.lower()`
take up exactly the same number of characters,
so the two lines are aligned and make it harder to notice
the `.upper()` vs `.lower()` going on.

Now, if we work on factoring out that `.append()`,
because that's independent of the value of `idx % 2`,
we could get something like

```py
def alternate_casing(text):
    letters = []
    for idx, letter in enumerate(text):
        if idx % 2 == 0:
            capitalised = letter.upper()
        else:
            capitalised = letter.lower()
        letters.append(capitalised)

    return "".join(letters)
```

You may feel strongly about the fact that I just added a line of code,
making the code longer instead of shorter,
but sometimes better code takes up more space.
However...


## Conditional assignment and conditional expressions

Having factored out the `.append()` to outside of the `if`
makes it blatantly clear that the `if` statement is only
there to decide on what to assign to `capitalised`.
This opens the door for another simplification,
that will come in the form of a conditional expression.

Conditional expressions are like condensed `if`-`else` blocks
that are great for conditional assignment.

Using a conditional expression, we rewrite the `if`-`else` as

```py
capitalised = letter.upper() if idx % 2 == 0 else letter.lower()
```

All in all, the intermediate variable is not needed
and we can write the whole thing as

```py
def alternate_casing(text):
    letters = []
    for idx, letter in enumerate(text):
        letters.append(letter.upper() if idx % 2 == 0 else letter.lower())

    return "".join(letters)
```


## Truthy and Falsy

The next step concerns itself with simplifying the condition
of the `if` statement.
In Python, we have this wonderful thing which allows us to
interpret many objects as Booleans, even if they are not
Booleans themselves.
This is often referred to as the Truthy/Falsy value of an object in Python,
and you can learn all about this in [a previous Pydon't][pydont-truthy-falsy].

For our case, what matters is that the number `0` is treated as `False`
and any other integer is treated as `True`.
Therefore, the condition `if idx % 2: ...` reads as
“if `idx` has a remainder when divided by 2”, which is equivalent to
“if `idx` is odd”.
Now, if the index is odd, we want the letter to be lowercased,
so we can simplify the conditional expression if we simplify
the condition and then switch the `.upper()` and `.lower()` calls:

```py
def alternate_casing(text):
    letters = []
    for idx, letter in enumerate(text):
        letters.append(letter.lower() if idx % 2 else letter.upper())
    return "".join(letters)
```

At this point, the function is getting _so_ short that there's no
point in having an extra blank line separating the return statement,
so I decided to put everything together.


## List comprehensions versus appending

One thing that you can also learn to spot is when you are building
a list by calling `.append()` on it successively.
When that is the case, look for an opportunity to use a list comprehension.
List comprehensions are very Pythonic when used well,
and they allow you to initialise a variable with the correct contents
right from the start,
instead of having to initialise a variable to change it right away.

Using a list comprehension, you can rewrite your loop into something like

```py
def alternate_casing(text):
    letters = [letter.lower() if idx % 2 else letter.upper() for idx, letter in enumerate(text)]
    return "".join(letters)
```


## Avoid long lines

The problem with the list comprehension above is that
now we have a really long line of code.
Long lines of code are things to be avoided whenever possible,
because they make it harder to read the code
and make it harder to work with the code when you have it side-by-side
with a debugger, or another file, or a Zoom call, or whatever.
Horizontal scrolling in code is to be avoided at all costs,
and that means lines shouldn't get too long.

There are a couple of ways in which we could fix that long list comprehension.
Something that is always an option is _not_ doing it.
Just because an idea looks good under a certain angle,
doesn't mean it is clearly superior.

However, we have something else up our sleeves.
The names inside the list comprehension only live inside the list comprehension,
so they are very short-lived and have a very specific role.
Because of that, if the structure of what is happening is clear enough,
we can use shorter variable names inside the list comprehension:

```py
def alternate_casing(text):
    letters = [l.lower() if i % 2 else l.upper() for i, l in enumerate(text)]
    return "".join(letters)
```

Now, bear in mind that we can only get away with this because the
target variable is well-named (`letters`) and so is the variable
we are iterating over (`text`).
I think there are several sensible alternatives for the list comprehension above,
for example using `c` or `char` instead of `l`.

If you prefer, you could've left the long names and split the list comprehension instead:

```py
def alternate_casing(text):
    letters = [
        letter.lower() if idx % 2 else letter.upper()
        for idx, letter in enumerate(text)
    ]
    return "".join(letters)
```


## Auxiliary variables

Once again, auxiliary variables aren't always needed.
Whether you have the broken up list comprehension or the one
with the short names, you can just get rid of the auxiliary
variable and call `.join()` on those letters directly:

```py
def alternate_casing(text):
    return "".join([l.lower() if i % 2 else l.upper() for i, l in enumerate(text)])
```

or

```py
def alternate_casing(text):
    return "".join([
        letter.lower() if idx % 2 else letter.upper()
        for idx, letter in enumerate(text)
    ])
```


## Redundant list comprehensions

We have come so far, but there is one final thing we can do,
and that is related to how we can get rid of the `[]` of the list comprehension.
I mean we can literally delete them, so that we end up with the following:

```py
def alternate_casing(text):
    return "".join(l.lower() if i % 2 else l.upper() for i, l in enumerate(text))
```

or

```py
def alternate_casing(text):
    return "".join(
        letter.lower() if idx % 2 else letter.upper()
        for idx, letter in enumerate(text)
    )
```

What is happening?
Now, instead of a list comprehension, we have a generator expression.
Generator expressions are amazing, in my opinion,
and they come with memory and speed benefits,
so try to use them when you can.
In practice, when you are calling a function with a list comprehension,
you can often omit the `[]` altogether to switch to a generator expression.

!!! I will devote a single Pydon't to generator expressions,
!!! so be sure to [subscribe] so you don't miss it!


## Final comparison

For your reference, here is the code we started with:

```py
def myfunc(a):
    empty=[]
    for i in range(len(a)):
        if i%2==0:
            empty.append(a[i].upper())
        else:
            empty.append(a[i].lower())

    return "".join(empty)
```

and here are two possible end products:

```py
def alternate_casing(text):
    return "".join(l.lower() if i % 2 else l.upper() for i, l in enumerate(text))
```

and

```py
def alternate_casing(text):
    return "".join(
        letter.lower() if idx % 2 else letter.upper()
        for idx, letter in enumerate(text)
    )
```

Notice how the end products look _so_ different from the starting point,
but notice that we did everything one small change at a time.
Take your time to understand the small steps separately,
and then appreciate how they all fit together in this refactor.

One of the main takeaways is really that refactoring doesn't need to
happen in one fell swoop.
It is ok to do incremental changes, and maybe even preferable:
incremental changes are easier to manage and easier to reason about.


# Conclusion

Here's the main takeaway of this Pydon't, for you, on a silver platter:

 > “*Elegant code is easier to refactor, and when you refactor your code,
 you should strive to make it more elegant.*”

This Pydon't showed you that:

 - the ability to refactor code is important;
 - the ability to refactor code is something you train;
 - code refactoring can (and maybe should!) happen in small steps;
 - consistent style increases code readability;
 - auto-formatters can help enforce a fixed style upon our code;
 - naming is important and should reflect
   - the purpose of an object; or
   - an important characteristic that is invariant;
 - `enumerate` is your best friend when traversing data _and_ indices;
 - repeated code under an `if`-`else` block can be factored out;
 - conditional expressions excel at conditional assignments;
 - `if` conditions can be simplified with Truthy and Falsy values;
 - list comprehensions are good alternatives to simple `for` loops with `.append()` operations; and
 - list comprehensions can be turned into generator expressions.


If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[gumroad-pydonts]: https://gum.co/pydonts
[pydont-elegance]: /blog/pydonts/does-elegance-matter
[pydont-enumerate]: /blog/pydonts/enumerate-me
[pydont-truthy-falsy]: /blog/pydonts/truthy-falsy-and-bool
[case-study]: https://www.reddit.com/r/learnpython/comments/o2ko8l/i_get_zero_output_even_though_theres_nothing
[black]: https://github.com/psf/black
[pep-8]: https://www.python.org/dev/peps/pep-0008/
