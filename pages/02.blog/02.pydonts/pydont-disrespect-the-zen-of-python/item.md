---
metadata:
    description: "The 'Zen of Python' is a set of guidelines you should always keep in mind when programming in Python."
title: "Pydon't disrespect the Zen of Python 🐍"
---

The "[Zen of Python]" is the set of guidelines that show up in your screen if you `import this`. If you have never read them before, read them now and again from time to time.
If you are looking to write Pythonic code, write code that abides by the Zen of Python.

===

![A Python code snippet importing the module `this`.](thumbnail.webp)

To kick-off the [Pydon't series][manifesto] we start with a set of guidelines
that all Pythonistas should be aware of: the [Zen of Python].

The [Zen of Python] is like a meta style guide.
While you have things like [PEP 8][pep8] that tell you how you should format
your code, how to name your variables, etc., the [Zen of Python] provides you
with the guidelines that you should follow when thinking about (Python) code
and when designing a program.

## Zen of Python

You can read the [Zen of Python] by executing `import this` in your REPL, which
should print the following text:

```
The Zen of Python, by Tim Peters

Beautiful is better than ugly.
Explicit is better than implicit.
Simple is better than complex.
Complex is better than complicated.
Flat is better than nested.
Sparse is better than dense.
Readability counts.
Special cases aren't special enough to break the rules.
Although practicality beats purity.
Errors should never pass silently.
Unless explicitly silenced.
In the face of ambiguity, refuse the temptation to guess.
There should be one-- and preferably only one --obvious way to do it.
Although that way may not be obvious at first unless you're Dutch.
Now is better than never.
Although never is often better than *right* now.
If the implementation is hard to explain, it's a bad idea.
If the implementation is easy to explain, it may be a good idea.
Namespaces are one honking great idea -- let's do more of those!
```

Take a look at those guidelines and try to appreciate their meaning.
If you want to write truly Pythonic code, then you should try to embrace these
guidelines as much as possible.

Digging in the reference of [PEP 20 -- The Zen of Python][Zen of Python] shows
that Tim Peters (a major contributor to Python in its earlier days) thinks that these
guidelines are “*fundamental idiomatic recommendations for operating
within the spirit of the [Python] language*”, which goes to show that these
recommendations are serious and should not be taken lightly - if you are willing
to go the extra mile.

If you've seen the [Kung Fu Panda], think of it this way:
the [Zen of Python] is to Python programmers what the *Dragon Scroll* is to kung fu
practitioners: Po was only able to take his kung fu skills to the next level,
becoming truly amazing, after embracing the Dragon Scroll.
You will only become a *true* Pythonista after you embrace the [Zen of Python].

My advice would be to read this from time to time, and to try and remember the
[Zen of Python] while you code and while you go over code that has already been
written (by you or someone else).
I don't know about *you*, but whenever I write a (text) document, like a letter
or a blog post, I never get it right on the first try.
I usually write a first draft and then go over it, editing as I see fit: sometimes
reworking whole sections.
Writing code is the same: chances are, the first thing you write can be greatly
improved upon.


## What does the Zen of Python mean?

Sometimes, saying what something _is not_ is as important,
or even more important,
than saying what something _is_.
The Zen of Python is not splitting hairs and making sure we come up with an objective metric that we can use to rank Python code.

The Zen of Python is a text that is open to interpretation and you should keep that in mind when reading it,
thinking about it, and discussing it with others.

Having said that, I would like to share my interpretation of some of the guidelines outlined in the Zen of Python.
My interpretations will reflect _my_ personal experience, my thoughts, my opinions, etc.
If you disagree with me, that doesn't mean one of us is wrong!
It just means we see things differently.

If you would like to engage in thoughtful discussion about the Zen of Python, or about any particular guideline, just scroll down to the bottom of the page and leave a comment there!


### Beautiful is better than ugly

[Adapted from my Twitter thread.][tt-beautiful]

The Zen of Python 🐍 says
“Beautiful is better than ugly.”.

I take this to mean that aesthetics _do_ matter,
as they matter in everything else.
So, if your code is elegant, it is more likely to be good code.

Striving to write elegant code might seem weird, but it's not!
A similar thing is done in maths:

There are many theorems that can be proven in many different ways,
but mathematicians always try to find the most elegant proof.
One mathematician even took that to an extreme.

Paul Erdös, a prolific mathematician of the 20th century, often talked about “The Book”:
a book where the most elegant proofs for mathematical theorems had been written by a divinity.
When he'd find a beautiful proof, he would say “This one is from The Book!”.

So, aesthetics matter in fashion, design, marketing, etc...
But they also matter in mathematics and programming, and don't let anyone tell you otherwise!

You might counter by saying that what is aesthetically pleasing is subjective...
And you are right!
Aesthetics are subjective in code, in maths, in fashion, in design, in art, ...
And yet, people seem to gravitate towards a consensus!

So, I claim this guideline is about getting familiar with that consensus.
The more code you read and the more people you work with,
the more exposed you will be to code from the real world,
and the more equipped you will be to understand what's elegant code.

Ultimately, your sense for elegant code or your ability to judge your code by its aesthetics develops when you write code but, most importantly, when you read code.


### Explicit is better than implicit

The Zen of Python 🐍 says
“Explicit is better than implicit.”

This means what it says on the tin, but “explicit” might not mean what you expect.
Explicit code isn't code that spells out every single step.

For example, suppose you want to sum a list of numbers.
Which of the two snippets below do you prefer?

```py
## Using `sum`.
my_list = [...]
list_sum = sum(my_list)

## Using a `for` loop.
my_list = [...]
list_sum = 0
for number in my_list:
    list_sum += number
```

The `for` loop is more explicit, right?
So, should we prefer the `for` loop instead of the built-in `sum`?
Hell no!

We want to be explicit about the semantics of our code and not about the irrelevant details.
When you see the built-in `sum` being used you know _immediately_ that we are summing a list.
On the other hand, when you see a `for` loop, you have to interpret it.
You have to analyse it and figure out what it's doing.
This is taxing for your brain, no matter how easy it ends up being.
Thus, making use of the built-in `sum` is _more explicit_ than the `for` loop.

In general, if there are functions and/or modules that do what you need or want, use them!
Of course, it all depends on the context you are in and the people who work with your code...
But remember, a programmer's responsibility is to use the _best_ tool for the job...

Therefore, if there's a function/module that really does what you need,
should you reinvent the wheel, or should you use it?
You should probably use it!
Even if others around you don't know it...
In fact, if others don't know it, _now_ is probably a good time to learn about it!


## Conclusion

This Pydon't was more of a “meta” Pydon't, with subjective advice on how to code.
This might seem useless to you at first, but the more you dwell on it the more
helpful it will become.
The next Pydon'ts will show you objective, practical tips on how to write
more Pythonic code.

---


If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.

Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!

## References

 - PEP 20 -- The Zen of Python, [https://www.python.org/dev/peps/pep-0020/](https://www.python.org/dev/peps/pep-0020/)
 - "The Way of Python" mailing thread, [https://groups.google.com/g/comp.lang.python/c/B_VxeTBClM0/m/L8W9KlsiriUJ](https://groups.google.com/g/comp.lang.python/c/B_VxeTBClM0/m/L8W9KlsiriUJ)
 - Tim Peters (software engineer), Wikipedia [https://en.wikipedia.org/wiki/Tim_Peters_(software_engineer)](https://en.wikipedia.org/wiki/Tim_Peters_(software_engineer))


[subscribe]: /subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[tt-beautiful]: /blog/twitter-threads/beautiful-is-better-than-ugly
[pep8]: https://www.python.org/dev/peps/pep-0008/
[Zen of Python]: https://www.python.org/dev/peps/pep-0020/
[Kung Fu Panda]: https://en.wikipedia.org/wiki/Kung_Fu_Panda#Kung_Fu_Panda_(2008)
