---
title: "Problem #039 – rope timer"
metadata:
    description: "In this logic riddle you have to use two ropes and a lighter to measure 45min."
---

You have two magical ropes that you can set on fire and you need
to count 45 minutes.
How do you do it?

===

![](thumbnail.png "Photo of an hourglass by Aron Visuals on Unsplash.")


## Problem statement

You are given two magic ropes and a lighter.
The ropes are magic because you are told they burn in a weird way:
each rope takes exactly 1 hour to burn from end to end,
but they don't burn at a constant rate.
(What that means is that the time elapsed doesn't have to be
proportional to the length of burnt rope.
For example, it may happen that the first half of the rope
takes 35 min to burn,
then a huge portion of the remaining rope burns in 10 min,
and then the final tip of the rope takes 15 min to burn.)

Given two magic ropes like this, how do you use them to measure 45 minutes?

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


## Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who already sent me their correct solutions:

 - Christ van W., The Netherlands;
 - Attila K., Hungary;
 - Ashok M., India;
 - David D., US;
 - Greg K., US;
 - Shivam T., US;
 - Marco M., Italy;
 - David H., Taiwan;
 - Cody B., US;

(The list is in no particular order.)

[Email me][email] your solution to get your name (or an alias) featured in here!


## Solution

I find this problem to be really interesting!
The fact is that it looks like there is not much that we can do,
because the first thing that pops into our minds is to cut the rope
into portions, however, the problem statement tells us that the
time that a piece of rope takes to burn will not be proportional to its length.
Therefore, cutting the rope isn't an option.

Our only other option is to actually light the rope(s) on fire,
but that can't be _just_ it, because a rope takes 60 minutes to burn
and we want to time 45 minutes.

The next step is realising that 45 minutes is $3/4$ of an hour,
and $3/4 = 1/2 + 1/4$.
What is more, $1/4 = (1/2)\times(1/2)$, i.e.,
three quarters of an hour is half an hour plus half of another half hour.
The recurring theme here is halves.
Therefore, it might be a good idea if we reframe the problem.
Instead of trying to measure a specific amount of time with the burning
of the ropes, can you do something to the rope so that it measures exactly
half of the total time that the rope could burn for?

In other words, if a rope takes a full $x$ minutes to burn,
how could you work with that rope in order to measure $x/2$ minutes?

Give it some thought.

If you light up the rope on both ends,
then it will burn for exactly half of the time!
So, if you take a rope that can burn for 60 minutes and light
both ends on fire, the rope will be consumed after 30 minutes.
Now we just need to measure another 15 minutes,
which would be super easy if we had a rope that would need
30 minutes to burn completely, as we would just need to apply the same technique...

Oh wait, can't you do something clever with the second rope
to “turn it” into a 30 minute rope?

When you light both ends of the first rope on fire at the same time,
you can also set fire to one of the ends of the second rope.
Therefore, when the first rope is done,
the second rope will have been burning for 30 minutes,
meaning a full 30 minutes are left.
At that point in time, you just have to set fire to the _other_
end of the second rope, effectively treating it as if it were
a 30 minute rope.
By doing this, the second rope will take another 15 minutes to burn entirely,
allowing you to measure a total of 45 minutes!


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox and to add your reaction below.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
