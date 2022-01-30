---
date: 29-09-2021
metadata:
    author: Rodrigo Girão Serrão
    description: A comparison of many possible Python implementations of the sign function.
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
publish_date: 26-01-2022
slug: sign-challenge-review
taxonomy:
    category: twitter thread
    tag:
      - code review
      - programming
      - python
title: "`sign` challenge review."
---

A comparison of many possible Python implementations of the sign function.

===

! This article is an **unedited** reproduction of a piece of content
! I posted on Twitter here:
! <blockquote class="twitter-tweet"><p lang="en" dir="ltr">I challenged you 🏆...<br><br>You delivered 💪!<br><br>I asked you to implement the sign function in Python 🐍.<br><br>Now I&#39;ll go over some alternatives and tell you what I like ✅ and dislike ❌ about them.<br><br>I&#39;ll also tell you which one I think is the best, most Pythonic ✨ one.<br><br>👇🧵 <a href="https://t.co/JORiBUWEgi">pic.twitter.com/JORiBUWEgi</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1443138696220487681?ref_src=twsrc%5Etfw">September 29, 2021</a></blockquote>

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

I challenged you 🏆...

You delivered 💪!

I asked you to implement the sign function in Python 🐍.

Now I'll go over some alternatives and tell you what I like ✅ and dislike ❌ about them.

I'll also tell you which one I think is the best, most Pythonic ✨ one.

👇

```py
# sign: returns 1 for positive numbers,
# 0 for 0, and -1 for negative numbers.
>>> sign(73.73)
1
>>> sign(0)
0
>>> sign(-42)
-1
```


By the way, for reference, here is the original challenge:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Here's a Python 🐍 challenge for you 🏆

The sign function is a VERY simple function.

It accepts a number, and:
👉 returns 0 if the number is 0;
👉 returns 1 if the number is positive; and
👉 returns -1 otherwise.

What's the most Pythonic 🐍 implementation you can think of? <a href="https://t.co/wAyiDoK6Pa">pic.twitter.com/wAyiDoK6Pa</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1442042804398665732?ref_src=twsrc%5Etfw">September 26, 2021</a></blockquote>


Let's start with the “basic” `if: ... elif: ... else: ...` approach first.

Gets the job done, is simple, and is easy to read.

Funnily enough, the only one to share that solution was a _very_ seasoned Python 🐍 programmer, author, and trainer, @dabeaz 🙃

Now, a couple of notes:

```py
def sign(x):
    if x > 0:
        return 1
    elif x < 0:
        return -1
    else:
        return 0
```


For the arg name, we don't need something VERY long like `number`, but don't shorten it to `n`.

`n` has a connotation for positive whole numbers, and `sign` accepts other numbers.

I also prefer to have the `else:` return the `0`, and that's to preserve the symmetry of 1 and -1.


We can use this as the cornerstone for many interesting implementations.

For such a simple function, it might look like a bummer that we have to spend 6 lines implementing it.

Can we shorten it a bit, perhaps?

Using conditional expressions, we can:

```py
def sign(x):
    if x == 0:
        return 0
    else:
        return 1 if x > 0 else -1
```


We can also get rid of the `else`, which may reinforce the sense of “0 is just an edge-case”...

Depending on how you usually read Python code.

Here it is:

```py
def sign(x):
    if x == 0:
        return 0

    return 1 if x > 0 else -1
```


Now, you might be thinking...

Instead of returning the conditional expression...

Can I compute Abacus the 1 or -1?

Well, you can!

And many of you submitted things like what follows.

But that gives rise to another issue...

```py
def sign(x):
    if x == 0:
        return 0

    return int(abs(x) // x)
```


Do we really need the `int`?

Well, depends on what you want your function to return, because `abs(x) // x` returns a float if `x` is a float.

And what about floating point inaccuracies? Does `int(abs(x) // x)` always return 1?


To be honest with you, I am not entirely sure!

I couldn't find a value of `x` for which `abs(x) // x` evaluated to 0.99999(something).

However, to be extra safe, you could replace `int` with `round`.

Ok, and are there “safer” alternatives?


There are!

Have you heard about the Truthy and Falsy values of things in Python?

That makes it so that all objects can be interpreted as Booleans when needed.

But did you know that Booleans (`True` and `False`) can be handled as integers??

Hence, you can write this

What⁉

```py
def sign(x):
    if x == 0:
        return 0

    return (x > 0) - (x < 0)
```


I like the fact that it looks like a face:

(x > 0) - (x < 0)

The `-` is the nose, and the `(...)` are the eyes!

And there's even the added benefit that you can drop the `if` statement:

```py
def sign(x):
    return (x > 0) - (x < 0)
```


In my opinion, this is very cool!

BUT it's not the “way to go” in Python, generally.

This looks more like a thing you'd do in an array-oriented language, like APL.

So, we steered away from Pythonic solutions.

Can we get back on track?


Yes!

But let's not, for now 😂

I just wanna show you another interesting one, making use of the integer value of Boolean values 👇

It looks interesting, and it is useful to _understand_ how it works.

But I'm sure the majority won't feel this is Pythonic.

```py
def sign(x):
    return -1 if x < 0 else int(bool(x))
```


I think the conditional expression wasn't that bad, right?

Maybe we could improve on it?

Well, we can try!

What if we nest two conditional expressions to handle the three cases?

Again, just a quick remark about this one:

```py
def sign(x):
    return 1 if x > 0 else -1 if x < 0 else 0
```


I like to have 0 at the end, because I like the symmetry between

 - x > 0 -> 1
 - x < 0 -> -1

This might sound silly to you, but these symmetries and patterns really make my life easier!

That's why I prefer this ordering over, say, this one:

```py
def sign(x):
    return 0 if x == 0 else 1 if x > 0 else -1
```


But we can still be friends if you order things your way 😁

Another thing to notice is that all the `if`s with `if x == 0` could be replaced by `if not x`.

Some might prefer it, some might not.

I have no strong feelings for neither 🤷‍♂️


Is there any other way to emulate the “choose one of -1, 0, or 1” behaviour without using long `if`s..?

Someone submitted this, and I gotta say:

It looks odd, but it is incredibly easy to read.

Is it Pythonic? I don't think so 😢

```py
def sign(x):
    return 0 if x == 0 else {True: 1, False: -1}[x > 0]
```


Instead of accessing a dictionary, we can also try to index into a list.

Now, close your eyes and skip this if you don't want to be horrified 👇

It's not that bad 😂

Again, not recommended style, but _understanding_ how it works gives insights into how Python works.

```py
def sign(x):
    return 0 if x == 0 else [-1, 1][x > 0]
```


This can be taken one step further.

Should you?

Nah.

Will I do it nonetheless?

Absolutely!

Here it goes 👇 Can you understand how it works?

```py
def sign(x):
    return [-1, 0, 1][(x >= 0) + (x > 0)]
```


Hmmm, 'kay.

That's all nice and fine.

What if I don't want to use `if`s, but also no weird Boolean computations?

Then, maybe you could try the new pattern matching features from Python 3.10!

In case you need to learn it:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">Have you heard that Python 🐍 3.10 will be released soon?<br><br>Are you ready for when it drops 💣🔥?<br><br>Python 3.10 will ship with the new ✨ match statement, and I created a cheatsheet just for that.<br><br>This is also a thread 👇🧵 that breaks it down and explains everything:</p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1442264814865326080?ref_src=twsrc%5Etfw">September 26, 2021</a></blockquote>


Can we use `match` to solve this?

Well, not directly.

Which shows that `match` really isn't the tool for this job.

But we can use guards and get away with it 👇

But if you look closely, the `case` statements aren't doing anything!

So, can we get rid of them..?

```py
def sign(x):
    match x:
        case x if x > 0: return 1
        case x if x < 0: return -1
        case _: return 0
```


Absolutely, and you end up with this 👇

Equivalent to the `if` from the beginning, but condensed.

Canned `if`-blocks, everyone!

```py
def sign(x):
    if x > 0: return 1
    elif x < 0: return -1
    else: return 0
```

Ok!

So, we used `match` and ended up getting away from it?

Unacceptable!

One more try:


`match` is supposed to be useful for structural pattern matching.

Can we match patterns, then?

Of course. Here's a solution no one submitted, but that I came up with 👇

What do you make of it?

```py
def sign(x):
    match x > 0, x < 0:
        case True, False: return 1
        case False, True: return -1
        case False, False: return 0
```


All in all, there are plenty of interesting solutions in this thread.

So, which ones do I prefer?

Well, *personally*, I love weird snippets of code 😂 But I don't write them in production.

So, that means I end up with a split preference, between 👇

<blockquote class="twitter-tweet" data-conversation="none"><p lang="en" dir="ltr">I think the conditional expression wasn&#39;t that bad, right?<br><br>Maybe we could improve on it?<br><br>Well, we can try!<br><br>What if we nest two conditional expressions to handle the three cases?<br><br>Again, just a quick remark about this one: <a href="https://t.co/zl3wFvKKeR">pic.twitter.com/zl3wFvKKeR</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1443144847460868098?ref_src=twsrc%5Etfw">September 29, 2021</a></blockquote>

and the slightly condensed `if: ... elif: ... else: ...` block 👇

I don't know why, but it *really* bothers me that such a simple function takes 6 lines of code as a “full” `if: ... elif: ... else: ...` block!

(Does anyone else feel like that?)

<blockquote class="twitter-tweet" data-conversation="none"><p lang="en" dir="ltr">Absolutely, and you end up with this 👇<br><br>Equivalent to the `if` from the beginning, but condensed.<br><br>Canned `if`-blocks, everyone!<br><br>Ok!<br><br>So, we used `match` and ended up getting away from it?<br><br>Unacceptable!<br><br>One more try: <a href="https://t.co/kj4EIIn7AE">pic.twitter.com/kj4EIIn7AE</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1443149714749276160?ref_src=twsrc%5Etfw">September 29, 2021</a></blockquote>


Thanks for making it so far!

I'd love if you read your thoughts on these solutions!

Here's a quick link to the beginning of the thread. Leave your comments there 💬 and retweet 🔁 it if you found value in this thread!

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">I challenged you 🏆...<br><br>You delivered 💪!<br><br>I asked you to implement the sign function in Python 🐍.<br><br>Now I&#39;ll go over some alternatives and tell you what I like ✅ and dislike ❌ about them.<br><br>I&#39;ll also tell you which one I think is the best, most Pythonic ✨ one.<br><br>👇🧵 <a href="https://t.co/JORiBUWEgi">pic.twitter.com/JORiBUWEgi</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1443138696220487681?ref_src=twsrc%5Etfw">September 29, 2021</a></blockquote>


Finally, if you have ideas for future challenges, feel free to send them to me!

Write them down in the comments, or send me a DM!

Then, if you want to keep learning a lot about Python 🐍, follow me [@mathsppblog](https://twitter.com/mathsppblog?ref_src=twsrc%5Etfw).

I'll see you soon! 👋


P.S. I should give credit to everyone who contributed to this thread by sending their solutions.

Many people replied, so I don't think I should mention all of them..? You can find all of them in the original challenge, linked at the beginning.

Thanks a lot for participating!
