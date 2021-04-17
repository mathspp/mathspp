---
metadata:
    description: In this problem you have to figure out how many people can guess the colour of their own hats.
title: 'Problem #024 - hats in a line'
---

Some people are standing quiet in a line, each person with a hat that has one of two
colours. How many people can guess their colour correctly?

===

![A picture with a couple of people in a line, with different-coloured hats](thumbnail.png)

### Problem statement

Some people, let's say $n$ people, are standing in a line.
(Of course they are
more than 2m away from each other, social distancing has to be taken seriously by
all of us.)

Each person has a hat, like the picture above shows.
Each hat is either light or dark, but no one knows the colour of their own hat
and people can only look forward and **cannot** move at all.
(Except perhaps to blink and to breath.)

Assuming they got a chance to meet before they got placed in a line and
received their hats, what strategy do they have to agree on so that
the *most* people can guess their own hat colour correctly?
We can pretend that people who fail to guess their hat colour are sent
to prison, and of course we want to keep as many people out of prison as possible.
The only thing they know is that the hats will be distributed randomly,
they have no idea how many hats of each colour will be distributed.

So your task is to devise the best possible strategy and to find out how many people
that strategy saves, on average.

It is important to note that:
 - people cannot communicate with each other once they are in a line;
 - they can try to guess the colours of their hats in any order you see fit;
 - each person gets a single attempt;
 - everyone hears everyone's guess, but only the people behind the person
making a guess know if that person got it right.
Everyone else has no idea if the guess was correct or not.

!!! Give it some thought...

If you need any clarification whatsoever, feel free to ask in the comment section below.

### Solution

The solution to this problem will be posted [here][sol] after this problem has been live for 2 weeks. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.
<!--You can read the solution [here][sol] to compare with your own solution. You can also use that link to post your own solution in the comments! Please **do not** post spoilers in the comments here.-->

---

This problem was posed to me by my friend [LeafarCoder].

If you enjoyed the problem and would like to get new problems directly in your inbox, be sure to [subscribe to the Problems newsletter][subscribe].

[sol]: ../../solutions/{{ page.slug }}
[subscribe]: https://mathspp.com/subscribe
[LeafarCoder]: https://github.com/LeafarCoder
