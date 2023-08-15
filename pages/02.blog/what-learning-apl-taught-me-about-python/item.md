Learning programming in APL taught me many new things about Python and this article is an account of that.

===


# What learning APL taught me about Python

In the past, I've written an article where I share some areas in which my Python programming was heavily influenced by learning APL.
You can read this article here: [“Why APL is a language worth knowing”][apl-worth-knowing].

I've given it further thought and now I have a better understanding of how APL influenced my Python code.
It is likely that I am not fully aware of all the repercussions that APL had on me, but I have new things to tell you about and that is what I want to focus on in this article.

So, without further ado, let me tell you what APL taught me about Python.


# The LOC that changed everything

There is one line of code (LOC) that changed everything!
Figuratively speaking.

Some time ago I wrote a piece of Python code pretty naturally.
You know when you are so focused on what you are doing that things just flow?
That's the state I was in.
When suddenly?
I looked at the code I had written and I saw this piece of code:

```py
sum(age > 17 for age in ages)
```

When I looked at it, I was surprised by it.
I was surprised because that is not the type of Python code that I usually see written in the books or tutorials I learn from.
And it's also not a pattern I had seen in Python code from other projects...
Until it hit me: I wrote this Python code because of the influence that APL had on the way I think about programming.

But first, let us make sure we understand this code!
What does `sum(age > 17 for age in ages)` do?
Well, if `ages` is a list with the age of a bunch of different people, then the line of code we saw above will count how many people are aged 18 or older:

```pycon
>>> ages = [18, 15, 42, 73, 5, 6]
>>> sum(age > 17 for age in ages)
3
```

That's what the code does.
That's it.
It is not that magical.
But, curiously enough, it encapsulates plenty of things that APL taught me about Python, so let me tell you all about them.


# Stating the obvious

Now, the remainder of this article might seem like I will be stating the obvious over and over and over and over.
That may be the case.
But the truth of the matter is, before I stated these things to myself, they hadn't clicked.
Or, put another way, it was when I stated these things out loud and wrote about them that everything finally made sense.
After all, just because something is obvious, it doesn't mean it is worthless saying it out loud!

A prime example of something obvious that we can benefit from by saying it out loud and even giving it a name is the pigeonhole principle.
It is like a mathematical theorem, but excruciatingly obvious.
And yet, it allows you to prove non-obvious things, like the fact that there cannot be a perfect compression algorithm.
(If you're intrigued by that, take a stab at [this problem about imperfect compression](/blog/problems/imperfect-compression).)


# Reductions are everywhere

The first thing I want to look at is the built-in `sum` itself.
What if I told you that `sum` is very closely related to six other Python built-ins and functions?

 1. `prod` from the `math` module;
 2. `min`;
 3. `max`;
 4. `any`;
 5. `all`; and
 6. `"".join`.

So, `sum` is related to all these other functions, but in what way?
Long story short, all these seven functions are specialised versions of `functools.reduce`.

If you are not familiar with `functools.reduce`, you can learn about it in [this article](/blog/pydonts/the-power-of-reduce).
There is also a 5 minute talk in which I explain this concept and you can watch it here:

<iframe width="560" height="315" src="https://www.youtube.com/embed/YeH7CwruEUs" title="Smoosh all the things | Lightning talk at EuroPython 2022 by Rodrigo Girão Serrão" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>


# Boolean values, 0/1, and data-driven conditionals

Another big thing that APL made me realise is that the Boolean values `True`/`False` and the integers `1`/`0` are tightly connected.

Python hints at this because the type `bool` is a subclass of the type `int`:

```pycon
>>> issubclass(bool, int)
True
```

This means that we can use the Boolean values `True` and `False` as the integers `1` and `0`, respectively:

```pycon
>>> True + True
2
>>> 3 * False
0
```

This may look terrible, but the truth is that this connection is extremely meaningful.
For one, it is related to how hardware works.
What's more, the fact that `True` and `False` can be used as the integers `1` and `0` enables data-driven conditionals in Python.

For example, going back to the expression `sum(age > 17 for age in ages)`, if you look closely, the subexpression that is being summed up is `age > 17`.
However, `age > 17` is either `True` or `False`, depending on the value of the age:

```pycon
>>> age = 18
>>> age > 17
True
>>> age = 5
>>> age > 15
False
```

So, if `ages` is a list with ages, then what we are doing is computing a series of Boolean values that indicate whether each value is greater than 17 or not:

```pycon
>>> ages = [18, 15, 42, 73, 5, 6]
>>> bools = [age > 17 for age in ages]
>>> bools
[True, False, True, True, False, False]
```

What the built-in `sum` does is sum all those Boolean values up, interpreting the `True` as `1` and the `False` as `0`:

```py
>>> sum(bools)
3
```

But this seeming interchangeability – [because `1` and `0` can also be used as `True` and `False`](/blog/pydonts/truthy-falsy-and-bool) – unlocked even more things for me.
Using Booleans as integers helps you write data-driven conditionals.

I've also written about [data-driven conditionals before](/blog/why-apl-is-a-language-worth-knowing#data-driven-conditionals), but I can also show you an example.
Consider this standard Python code:

```py
count = 0
for age in ages:
    if age > 17:
        count += 1
```

This code uses an `if` statement to determine whether or not to add `1` to the variable `count`.
So, depending on the value of the condition `age > 17`, we perform an addition or not.

The code above is equivalent to this more symmetric version, even though it may be more verbose:

```py
count = 0
for age in ages:
    if age > 17:
        count += 1
    else:
        count += 0
```

Rewriting the code in this way exposes a pattern in both branches: we always want to add something to `count` but the condition `age > 17` changes the value we want to add.
This is even more obvious if we use a [conditional expression](/blog/pydonts/conditional-expressions):

```py
count = 0
for age in ages:
    count += 1 if age > 17 else 0
```

So, a conditional expression is kind of similar to a data-driven conditional.
But there are differences:

 - a conditional expression is a syntactical construct, it is part of the written language, and it changes the value you want to use with an `if`;
 - a data-driven conditional uses the data itself to _compute_ the value that we want to use and it isn't a syntactical construct, it is a concept that you may or may not use.

In our simple case, we can go from `count += 1 if age > 17 else 0` to `count += age > 17`, but in APL we use data-driven conditionals commonly.
You can read another [example of a data-driven conditional here](/blog/why-apl-is-a-language-worth-knowing#data-driven-conditionals).

And while data-driven conditionals don't always translate directly into idiomatic Python code, the _concept_ of a data-driven conditional helped me appreciate the situations in which I can write my code in a more symmetric way.
We saw this above.
I had an `if` statement and I made it more symmetric by including the `else` branch that was implicit and also a bit redundant.
And what happens often, at least for me, is that adding the redundant branch(es) that are missing helps me spot patterns and rewrite the whole `if` statement in a cleaner way.
This, in turn, reduces nesting in my Python code, which is a good thing.

All in all, the way APL handles Boolean values helped me understand the relationship between Booleans and the integers 1 and 0, and it made me aware of some patterns in Python code that now I know that can be simplified.
If you are interested, go ahead and learn more about [APL and Boolean values](/blog/why-apl-is-a-language-worth-knowing#apl-and-boolean-values).


# Scalar functions and list comprehensions

In case you haven't noticed, I am partially obsessed with list comprehensions.
I even [wrote a book with +200 exercises about list comprehensions](/comprehending-comprehensions) and related concepts, like set/dict comprehensions and generator expressions.
And the reason I am so obsessed with list comprehensions is that I am convinced that there is a big portion of the Python community that doesn't give list comprehensions their due credit.

List comprehensions are insanely useful and most people are unaware of what the main advantage of list comprehensions is.
It is not speed, nor is it the fact that they are shorter to type than the corresponding `for` loop.
The true advantage of list comprehensions is something that I can only justify after having learned about how APL handles scalar functions – more on that in a second!

The main advantage of list comprehensions is that they tend to be more readable than their `for` loop counterparts because they highlight the data transformation.
What does this mean..?
Again, let us look at two pieces of code to compare.
First, take a look at this `for` loop:

```py
is_adult = []
for age in ages:
    is_adult.append(age > 17)
```

We only have three lines of code, but the thing that matters the most is hidden at the bottom right of the code: the expression `age > 17`.
That is the expression that determines what we fill the list `is_adult` with, and it is at the bottom right of the code.
If I rewrite the code as a list comprehension, this is what I end up with:

```py
is_adult = [age > 17 for age in ages]
```

Notice that the expression that matters, `age > 17`, is now much closer to the top left, which is where we start reading the code.
The loop itself, the `for age in ages`, was moved to _after_ the expression, because the loop itself matters less than the expression!
And this is what makes list comprehensions typically more readable than the explicit loops!

Now, if you haven't learned list comprehensions yet, you may say that list comprehensions are more complicated.
But you say that because you haven't learned them yet.
Just [read this “list comprehensions 101”](/blog/pydonts/list-comprehensions-101) or my book [“Comprehending Comprehensions”](/comprehending-comprehensions) and soon you'll agree with me!

I mentioned scalar functions in APL before.
Many functions in APL are scalar functions.
Loosely speaking, this means that handling one single value or multiple values is done in the exact same way, without needing loops or list comprehensions.
In APL, `age>17` determines whether the value `age` is greater than `17`.
If `ages` is a list of ages, like `18 15 42 73 5 6`, then `ages>17` just works!

It would be equivalent to this:

```pycon
>>> ages = [18, 15, 42, 73, 5, 6]
>>> ages > 17  # If `>` were a scalar function like in APL:
[True, False, True, True, False, False]
```

In Python, you can't use `>` with an integer and a list but in APL you can!
APL only cares about the computation that you are doing, it doesn't care about the looping.
In Python, you _have_ to write some code to do the looping, but you can “hide” it by using a list comprehension and putting it after the expression that matters.

So, you can't write only `ages > 17`, but you can write `[age > 17 ...]` to get the same result.

If you are curious about APL's scalar functions and how they relate to list comprehensions, you can learn more by [reading this](/blog/why-apl-is-a-language-worth-knowing#apl-and-list-comprehensions).


# An idiom to put everything together

So, I realised that APL led me to understand that `sum` and many other built-ins are just specialised reductions.
This let me establish connections between functions and algorithms that I didn't know were connected.
APL also taught me about data-driven conditionals and how to look for ways to make my code more symmetric.
Finally, list comprehensions started making much more sense to me after I realised that the point of list comprehensions is to highlight the data transformation.

When everything converged at the same time, I wrote this:

```py
sum(age > 17 for age in ages)
```

In hindsight, this is a translation of the APL code that does the same thing, which is just `+/ages>17`.
The `+/` in APL is equivalent to `sum` in Python and we've seen that `ages>17` in APL needs to be written as a list comprehension in Python.

I dissected this Python code and studied each of its parts.
Again, the reductions, the Booleans/data-driven conditionals, and the scalar functions/list comprehensions.
And when I took all of those things and put them together, I learned one other thing!

The code I've written exhibits this pattern:

```py
sum(predicate(element) for element in iterable)
```

If `predicate` is a function that returns `True` or `False`, then the code above is an idiom that counts how many elements in `iterable` satisfy the function `predicate`!
In our previous example, we had this correspondence:

```pycon
>>> iterable = [18, 15, 42, 73, 5, 6]
>>> predicate = lambda age: age > 17
>>> sum(predicate(element) for element in iterable)
3
```

Suppose we have a list of words and want to count how many words have the letter `"a"` in it.
We can use the same idiom:

```pycon
>>> words = ["cat", "dog", "parrot"]
>>> has_a = lambda word: "a" in word
>>> sum(has_a(word) for word in words)
2
```

How cool is this?
I like this idiom a lot and I use it often in my own Python code!


# Conclusion

All in all, learning APL was a terrific experience because it forced me to think about programming in new ways and, as a result, it also influenced the way I think about programming in the other languages that I already knew, namely Python.
If you have similar stories of how learning one language changed the way you wrote code in another, share them with me!
Drop a comment below or tag me elsewhere on the Internet!


[apl-worth-knowing]: /blog/why-apl-is-a-language-worth-knowing
