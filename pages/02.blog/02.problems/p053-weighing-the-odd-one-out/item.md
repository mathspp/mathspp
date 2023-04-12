Can you find the fake ball by weighing it?

===

![](thumbnail.webp "Photo by AlSimonov on Getty Images.")

# Problem statement

You have 12 balls, 11 of which are identical and weigh exactly the same.
The 12th ball _looks_ like all the others, but it is fake, and is either lighter or heavier.

Can you figure out which of the 12 balls is the fake one and whether it is lighter or heavier than the others?
You can make use of a [traditional scale](https://en.wikipedia.org/wiki/Weighing_scale) with two plates,
although you have no weights at your disposal.
Also, the scale is so rusty it will stop working soon and you can only use it three times.

This problem was proposed by Attila, a fellow solver from previous problems.


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - David H., Taiwan;
 - Kees de L, Netherlands;

Know how to solve this?
Join the list of solvers by [emailing me][email] your solution!


# Solution

I'm risking stating the obvious, but I'll say it:
to solve this problem, we want each weighing to give _as much information_ as possible!
So, how do we do that?

One might be tempted to start by having 6 balls in one plate and 6 balls in the other,
but this will _not_ work because the scale will obviously be imbalanced _and_
it won't tell us a thing, because we don't even know if the fake ball is heavier or lighter than the others.

In fact, the first weighing should distribute things evenly across the two plates _and_ off the scale:
we start by weighing 4 balls on one plate, 4 on the other, and leaving 4 on the side.

If the scale remains balanced, that's because the 8 balls on the scale are real,
and the fake one is among the other 4.
Let's handle this case first.

During the remainder of the solution, we'll use

 - `?` to represent a ball about which we know nothing;
 - `O` to represent a ball with the correct weight;
 - `H` to represent a ball that may be the correct one, or the fake one and heavier; and
 - `L` to represent a ball that may be the correct one, or the fake one and lighter.

## The first weighing leaves the scale balanced

If the scale is balanced, the 4 balls off the scale contain the fake one.
We pick 3 of those up, and we weigh them against 3 balls from the group of 8:

```txt
??? v OOO
---   ---
```

If the scale is balanced, that's because the fake ball is the only one left,
and by weighing it against one of the real balls, we can find out if it's heavier or lighter.

If the 3 - 3 weighing is unbalanced, it will tell us whether the fake ball is heavier or lighter,
and it also says that it's within the 3 balls we just weighed.
For example, the scale could've tilted towards the `?` balls that become `H`:

```txt
      OOO
    v ---
HHH
---
```

After we determine if the fake ball is heavier or lighter (we assume it is heavier for the explanation),
we weigh 2 of the unknown balls, 1 in each plate:

```txt
H v H
-   -
```

If the scale remains balanced, the `H` left out is the fake ball.
If the scale tilts, the plate that tilts down contains the fake ball.

So, this covers the case where the original weighing was balanced.
Let's now cover the case where the original weighing is unbalanced.


## The first weighing leaves the scale unbalanced

If the scale is unbalanced, we have 4 balls that may be real or the fake one and lighter;
and we have 4 balls that may be real, or the fake one and heavier:

```txt
LLLL
---- v
       HHHH
       ----
```

How do we solve this conundrum?
Well, think of it this way:
we are going to do _a_ weighing, we don't know which one yet.
We do know, however, that it cannot contain all 8 balls, because that won't help.
So, some balls will be left out.

Now, suppose that this next weighing remains balanced.
That means we will know the fake ball lies within the group we left out,
and we will only have 1 weighing left to figure out which one it is.
In other words, we need to be clever picking the balls that we leave out from the next weighing.

Let's do the following: let's weigh `LLH` against `LLO` and leave three `HHH` out.
If `LLH` and `LLO` is balanced, the `HHH` contains the fake ball.
Weigh `H` against `H` and check: if the scale is balanced, the fake ball is the heavy left out;
if `H` against `H` is unbalanced, the plate that is down contains the fake heavy ball.

So, what if `LLH` and `LLO` is unbalanced?
Well, depends on how it tilts:

```txt
LLH
--- v
      LLO
      ---
```

If the `LLO` plate tilts down, it can't be because it contains a heavy fake ball.
Thus, the plate `LLH` contains a light ball.
Just weigh them against each other to figure out which one is the light ball.
Alternatively, we might have

```txt
      LLO
    v ---
LLH
---
```

If the `LLH` plate tilts down, it may be because `H` is the fake heavy ball,
or because one of the `L` in the `LLO` plate is light.
Well, weigh the two `LL` against each other.
If the scale remains balanced, the `H` was heavy.
If the scale tilts, it will tell you which one is lighter.

That's it!


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe
