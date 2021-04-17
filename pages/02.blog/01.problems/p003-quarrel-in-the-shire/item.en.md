---
title: 'Problem #003 - a quarrel in the Shire'
---

Gandalf has some Hobbits to appease but his task seems to go on forever. Can you give him a hand..?

===

![A picture of the Shire](shire.jpg)

Once again I bring you a problem alongside my proposed solution. If you find any mistakes or come up with a different solution, please let me know!

### Problem statement

The Shire is a lovely place where $N$ Hobbits live in perfect harmony. Or at least they lived, until a Hobbit decided to become an outside decorator and convinced some of his friends to paint their front doors with a very _fashionable_ purple (all doors were yellow before that preposterous change).

Overnight, the perfect balance and harmony in which the Hobbits lived shattered, and Hobbits whose doors were different colours couldn't stand one another.

Worried, the great and wise Gandalf hurried to the Shire to try and settle this matter. This was what he decided to do: in alphabetical order, he would visit each Hobbit. When visiting a Hobbit, he would change the colour of its door if and only if there were more Hobbits mad at him than there were Hobbits at peace with him. After visiting each Hobbit once, he would visit them all again in the same order, and then again, and then again, ..., repeating this process until a complete round of visits didn't change a thing.

Is Gandalf's task always going to end, regardless of $N$ and of the way the doors are coloured when he first arrives? Or are there values of $N$ and/or door colourings such that Gandalf will have to spend an eternity trying to solve the Hobbits' problems?

!!! Give it some thought... Grab a piece of paper and play out some scenarios by hand!

If you need any clarification whatsoever, feel free to ask in the comment section below.

!!!! **Hint**: Gandalf's endeavour is always a finite one.

!!!! **Hint**: look for a "semi-invariant"; a quantity that can only change in a certain way, which allows you to verify that Gandalf will rest eventually.

### Solution

You can read the solution [here][sol] to compare with your own solution.

[sol]: ../../solutions/{{ page.slug }}
