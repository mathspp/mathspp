The Zen of Python says “there should be one -- and preferably only one -- obvious way to do it”, but what if there's a dozen obvious ways to do it?

===

![](thumbnail.png "My speaker card for the PyCascades conference.")


# Preamble

This article is the written version of my talk entitled “50 shades of `sign`”
that I gave for the 2022 edition of the [PyCascades][pycascades] conference.

The slide deck can be found in [this GitHub repository][talk-gh].


# 50 shades of `sign`

The other day I was writing some maths-adjacent code for an algorithm,
and at some point I needed the function `sign`.
The function `sign` is typically well-known, given its simplicity:

The function `sign` (called `sgn` in some languages) accepts a number
and returns another number:

 - if the input is positive, it returns 1;
 - if the input is negative, it returns -1; and
 - if the input is zero, it returns 0.

I don't want to bother you too much with the context of the project,
so I won't go into the details of _why_ I needed this function.

Anyway, I opened up the documentation page for the standard module `math`
and searched the page for the occurrence of the word “sign”,
to see if I could find the function I needed.
Much to my surprise, I couldn't find it!

I mean, the letters “sign” show up 20 times in the [doc page for `math`](https://docs.python.org/3/library/math.html),
and they mention the sign of numbers a bunch of times,
but the function itself doesn't exist!

So I decided to get my hands dirty and thought I'd implement the function myself.
In mathematics, the function is usually represented as such:

$$
\text{sgn} ~ x = \begin{cases}\begin{aligned}
&-1, ~ &\text{if} ~ x < 0, \\
&0, ~ &\text{if} ~ x = 0, \\
&1, ~ &\text{if} ~ x > 0.
\end{aligned}\end{cases}
$$

That could be translated almost literally and be turned into a possible implementation of `sign`,
but it wouldn't look too good.
So, I decided to reorder things a bit and to make use of `elif` and `else` statements:

```py
def sign(x):
    if x > 0:
        return 1
    elif x < 0:
        return -1
    else:
        return 0
```

By having the cases `> 0` and `< 0` together, we put the symmetry of the function `sign` under the spotlight, and we use the `else` statement to cover the single case of when `x == 0`, which we can almost interpret as being the edge case of our function.

I looked at my code and felt pretty good about myself,
given that it really looked like I had done a great job!
So, I opened the REPL, and thoroughly tested my function:

```py
>>> sign(73)
1
>>> sign(0)
0
>>> sign(-42.42)
-1
```

“Done and dusted”, I thought.

But then, I looked at the code I had written and I thought that something was off.
I mean, I'm being paid to write software and this is what I write?
It's just an `if` statement with three branches!
It's too basic!
I have to try to improve this, otherwise I'll just get fired because I have almost 10 years of experience and I still write code like a beginner!

Ah, I know what I'll do.
The conditions in the `if` statement are so basic that I can just fold this whole thing into a [conditional expression][conditional-expressions]!

If I do that, I can save 5 lines of code!
Alright, let's roll that version:

```py
def sign(x):
    return 1 if x > 0 else -1 if x < 0 else 0
```

Hmmm, feels better, right?
I mean, the solution looks shorter,
but the essence is still pretty much the same,
I'm not really making use of any nice Python features...

Maybe if I made use of the fact that Python has [Truthy and Falsy values][truthy-falsy]?
I mean, that just means I'd have to reorder some things around,
to explicitly check for equality with `0`:

```py
def sign(x):
    return 0 if x == 0 else 1 if x > 0 else -1
```

I guess this sacrifices the symmetry we had:

 - `x > 0` returned `1`.
 - `x < 0` return `-1`.

But now, we can replace the equality check `x == 0` by something more Pythonic, right?
In fact, `0` is considered Falsy and any other number will be considered Truthy:

```py
>>> bool(73)
True
>>> bool(0)
False
>>> bool(-42.42)
True
```

So we can just replace `x == 0` with `not x`:

```py
def sign(x):
    return 0 if not x else 1 if x > 0 else -1
```

That's much better!

Probably..?

Probably not, I'm getting a bit too carried away.
I don't want to get fired by writing illegible in the most readable language of all times!

Two conditional expressions chained and making use of the Falsy value of `0` might be a bit too much...
And I even sacrificed the symmetry of the solution!
The symmetry was nice because our brain apprehends patterns faster than just random bits of information...

Alright, but I know how to not get fired!
Let's treat the function `sign` as if really the case `x == 0` is the edge case that we need to take care of immediately:

```py
def sign(x):
    if x == 0:
        return 0
    else:
        return 1 if x > 0 else -1
```

Ah, that sounds like a great idea!

But wait, if `x == 0` really is to be treated like an edge case,
then it doesn't make sense to have the `else` in there.

I mean, edge cases don't generally happen, so the `else` statement is just a bit redundant...
I should probably get rid of it!

```py
def sign(x):
    if x == 0:
        return 0

    return 1 if x > 0 else -1
```

Right, brilliant move!
This even goes in line with what the [Zen of Python][zen-of-python] says:

 > “Flat is better than nested.”

Perfect, this should settle it...

Except...

What if...
What if, instead of using conditionals to pick the correct result,
I used a bit of maths to _compute_ the result?
That will impress my managers, right?
If I use maths I'll show them I know what I'm doing!

I can definitely do this, because the [wiki page on the function][sign-wiki] says that, when `x` is not equal to `0`,
we can compute it from the value itself and its absolute value...
And guess what?
The function “absolute value” is built-in:

```py
>>> abs(73)
73
>>> abs(0)
0
>>> abs(-42.42)
42.42
```

Now, all I have to do is replace the conditional expression with the correct quotient:

```py
def sign(x):
    if x == 0:
        return 0

    return int(abs(x) // x)
```

This is amazing, because it _computes_ two of the outputs I'd like to produce!
However, the division there really is begging for floating point issues to arise...

Oh, wait a second!
Objects have Truthy and Falsy values, right?
But did you know that `True` and `False` can be converted to integers?
That's right:

```py
>>> int(True)
1
>>> int(False)
0
```

With this, I can produce two of the three different outputs that the function `sign` is supposed to return!
Now, I only have to special-case the negative inputs:

```py
def sign(x):
    return -1 if x < 0 else int(bool(x))
```

I was about to submit my code for code review,
when a co-worker of mine noticed my code.

They suggested I didn't do that,
because no one likes annoying coders who act like they are smarter than anyone else.

I thought my co-worker was right, and so I started thinking about other ways in which I could map positive numbers to `1` and negative numbers to `-1`...

And then, 💡!

It's all about _mapping_, so I should definitely try to use a dictionary to map the correct values to the correct places.
For example, I can take care of the case `x == 0` as an edge case,
and then use a mapping to distinguish the positive and the negative numbers:

```py
def sign(x):
    return 0 if x == 0 else {True: 1, False: -1}[x > 0]
```


Hehe, this is amusing, but I'll make it even more amusing!

What I can do with the fact that Booleans can also be seen as zeroes and ones is that a 2-item list can be used as a sort of `if` statement...

So, instead of using a dictionary to create the explicit mapping,
I can use the integer value of Booleans and a list to create the implicit mapping through indexing!

All it takes is using `x > 0` to index into the two results that I care about, which are `-1` and `1`:

```py
def sign(x):
    return 0 if x == 0 else [-1, 1][x > 0]
```

Notice how the result of the condition is being used to index into the list of the two results I care about.
It's just a shame that I have to treat `x == 0` as an edge case
and can't cover all three cases with a single list...

Or can I?

_Thinking..._

Oh wait, I can!
You are going to love this:

```py
def sign(x):
    return [-1, 0, 1][(x >= 0) + (x > 0)]
```

 - If `x` is positive, it satisfies both conditions and adding them produces the index `2`.
 - If `x` is zero, only the first condition is satisfied and adding the two conditions produces the index `1`.
 - If `x` is negative, neither condition is satisfied and their addition produces the index `0`.

At this point I thought I had reached the peak of my career as a programmer
(likely because no one was ever going to let me write code again, not because I had written the best Python code ever seen)...
And yet again, enlightenment struck me!

I'm doing this all wrong!
I already have everything I need from the conditions,
I don't need to index into a list...
I just have to use the correct mathematical operation:

```py
def sign(x):
    return (x > 0) - (x < 0)
```

Look at the code above!
It's a work of art, it's a huge emoji!
The minus `-` looks like the nose and the two sets of parenthesis represent the eyes.

This works because the minus `-` forces the two Boolean results to evaluate to an integer.
Then, you can only have one of the two conditions be `True` at a time:

 - if `x > 0` is `True`, then its the left condition that is `True` and we get `True - False == 1 - 0 == 1`;
 - if `x < 0` is `True`, then its the right condition that is `True` and we get `False - True == 0 - 1 == -1`; and
 - if `x == 0`, neither condition is `True` and we get `False - False == 0 - 0 == 0`.

Sitting in awe, looking at my computer,
I found it interesting how the result I wanted could be derived from the two conditions I used:

```py
[x > 0, x < 0]
```

In particular, everything worked because that list can only have three values:

```py
[True, False]   # when x > 0
[False, True]   # when x < 0
[False, False]  # when x == 0
```

The three results I wanted to compute depended solely on the Boolean pattern of that 2-item list...

But wait, are we talking about patterns?
Oh, that's exciting, because it means we get to use the new structural pattern matching introduced in Python 3.10!

To do that, we just need to match the structure of the 2-item list to the three cases I laid out already!
Here is the code:

```py
def sign(x):
    match x > 0, x < 0:
        case True, False: return 1
        case False, True: return -1
        case False, False: return 0
```

This works, but feels a bit clunky, right?
With all those Boolean values all over the place,
the function feels a bit too cluttered.

Maybe we could give it some breathing room but getting rid of all the Booleans,
and instead using guards to define each matching case:

```py
def sign(x):
    match x:
        case x if x > 0: return 1
        case x if x < 0: return -1
        case _: return 0
```

Now, the first two case statements will only match `x` when `x` is positive and negative, respectively, and the final `case` takes care of everything else, which is just when `x == 0`!

As I finished this train of thought,
I squinted at my screen and almost fell out of my chair:
I had gone through ALL this trouble,
and ended up going full-circle back to where I started at!
In fact, if I remove the extra noise around my code, this is what I get:

```py
def sign(x):
    if x > 0: return 1
    if x < 0: return -1
    return 0
```

It's _the same thing_ as what I had in the beginning!
Heck, it even _has the same bytecode_!

In other words, even though it looks _different_:

 - different number of lines; and
 - a big `if` statement versus three separate statements,

Python runs these two functions in exactly the same way.

I can use [the module `dis`][til-dis] to prove this!
Just `import dis`, define the two functions, and let's compare the outputs we get in Python 3.10.

Here is the disassemble of the longer version that takes up 6 lines:

```py
>>> dis.dis(sign)
  2           0 LOAD_FAST                0 (x)
              2 LOAD_CONST               1 (0)
              4 COMPARE_OP               4 (>)
              6 POP_JUMP_IF_FALSE        6 (to 12)

  3           8 LOAD_CONST               2 (1)
             10 RETURN_VALUE

  4     >>   12 LOAD_FAST                0 (x)
             14 LOAD_CONST               1 (0)
             16 COMPARE_OP               0 (<)
             18 POP_JUMP_IF_FALSE       12 (to 24)

  5          20 LOAD_CONST               3 (-1)
             22 RETURN_VALUE

  7     >>   24 LOAD_CONST               1 (0)
             26 RETURN_VALUE
```

Here is the disassemble of the shorter version that I wrote in the end:

```py
>>> dis.dis(sign)
  2           0 LOAD_FAST                0 (x)
              2 LOAD_CONST               1 (0)
              4 COMPARE_OP               4 (>)
              6 POP_JUMP_IF_FALSE        6 (to 12)
              8 LOAD_CONST               2 (1)
             10 RETURN_VALUE

  3     >>   12 LOAD_FAST                0 (x)
             14 LOAD_CONST               1 (0)
             16 COMPARE_OP               0 (<)
             18 POP_JUMP_IF_FALSE       12 (to 24)
             20 LOAD_CONST               3 (-1)
             22 RETURN_VALUE

  4     >>   24 LOAD_CONST               1 (0)
             26 RETURN_VALUE
```

The only difference comes from the line numbers,
because the bytecode is otherwise _identical_.

So, now that I have taken a look at 16 different implementations of the function `sign`,
which one do I go for?
I'm trying to avoid getting fired for writing basic code,
so I hope at least one of these is fast enough to make it worth having!

Alright, let's do some basic timings and check what we get.

Let's time the functions by applying them to zero, positive and negative floats,
and (large) positive and negative integers.
The first row contains the function that ran the fastest (when summing all 5 inputs)
and all other rows contain the relative difference in speed to the fastest one.

The names of the functions (on the first column) try to indicate the syntactic elements that composed that specific implementation.


| Function                              |   +3.14 |   -2.78 |       0 |   +10¹² |   -10¹² |
| :-                                    |      -: |      -: |      -: |      -: |      -: |
| `sign_conditional_conditional_falsy`  |      -% |      -% |      -% |      -% |      -% |
| `sign_conditional_conditional`        |  -11.0% |  +12.7% |  +42.8% |  -11.0% |   +7.8% |
| `sign_canned_if_elif_else`            |   -9.8% |  +12.7% |  +42.3% |  -10.7% |   +8.0% |
| `sign_standard_if_elif_else`          |   -9.6% |  +12.7% |  +42.9% |  -10.4% |   +8.3% |
| `sign_if0_conditional_expression`     |  +14.1% |  +13.2% |  +12.6% |  +10.5% |   +9.6% |
| `sign_conditional_conditional_2`      |  +14.3% |  +13.3% |  +12.6% |  +10.8% |   +9.5% |
| `sign_if_else_conditional_expression` |  +14.1% |  +13.3% |  +12.7% |  +11.1% |   +9.6% |
| `sign_match`                          |   -1.5% |  +20.7% |  +53.0% |   -1.5% |  +16.0% |
| `sign_boolean_emoji`                  |  +24.2% |  +20.4% |  +48.2% |  +17.5% |  +14.3% |
| `sign_conditional_int_bool`           |  +70.9% |  -11.5% | +120.6% |  +73.2% |  -13.2% |
| `sign_conditional_list`               |  +55.6% |  +52.6% |  +12.4% |  +53.8% |  +49.3% |
| `sign_conditional_dict`               |  +90.9% |  +87.0% |  +11.2% |  +90.8% |  +85.8% |
| `sign_list`                           |  +78.5% |  +75.5% | +120.2% |  +74.0% |  +69.5% |
| `sign_if0_divide_abs`                 | +109.4% | +105.2% |  +12.4% | +157.2% | +163.5% |
| `sign_structural_match`               |  +88.9% | +130.3% | +264.0% |  +85.8% | +128.6% |

We can see that the fastest function was `sign_conditional_conditional_falsy`,
which was the implementation with the double conditional expression that checked if the input was falsy:

```py
def sign_conditional_conditional_falsy(x):
    return 0 if not x else 1 if x > 0 else -1
```

This function was closely followed by three equivalent implementations,
including the very first one and the last one, which were allegedly “too simple”:

```py
def sign_conditional_conditional(x):
    return 1 if x > 0 else -1 if x < 0 else 0

def sign_canned_if_elif_else(x):
    if x > 0: return 1
    if x < 0: return -1
    return 0

def sign_standard_if_elif_else(x):
    if x > 0:
        return 1
    elif x < 0:
        return -1
    else:
        return 0
```

(You can verify that these functions are equivalent by looking at their bytecode.)

Now, what is interesting here is that all these implementations were written by real people.
I went to Twitter a couple of months ago and [asked people to implement the function `sign` in the most elegant way possible][original-tweet].

I literally asked “What's the most Pythonic implementation you can think of?”,
although I assume some just got a bit carried away...

One day after my original tweet, after a dozen different answers poured in,
a _very experienced_ Python programmer, pointed out that no one had yet written the most straightforward solution:
the one with the `if: ... elif: ... else: ...` structure.

Maybe everyone thought it was “too basic”, just like in my story?
And yet, all these other “nicer” implementations were just too complicated for what we were trying to achieve.

Sometimes, people sacrifice elegance and readability in the name of speed,
but that doesn't even work here, because the simplest solution achieves almost top performance!

I guess what I'm trying to say is that there is no advantage in writing complicated code just for the sake of making it more complicated.
There is beauty, elegance, and other things to be gained in simplicity.

Now, there is a fine line between overcomplicating things and making good use of all the features Python has to use...
I guess that would be a whole other talk!



[conditional-expressions]: /blog/pydonts/conditional-expressions
[truthy-falsy]: /blog/pydonts/truthy-falsy-and-bool
[zen-of-python]: /blog/pydonts/pydont-disrespect-zen-of-python
[til-dis]: /blog/til/028

[pycascades]: https://2022.pycascades.com/
[sign-wiki]: https://en.wikipedia.org/wiki/Sign_function
[original-tweet]: https://twitter.com/mathsppblog/status/1442042804398665732
[talk-gh]: https://github.com/mathspp/talks
