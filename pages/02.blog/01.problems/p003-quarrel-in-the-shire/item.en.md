---
title: 'Problem #003 - a quarrel in the Shire'
---

Gandalf has some Hobbits to appease but his task seems to go on forever. Can you give him a hand..?

===

![a picture of the Shire](shire.jpg)

Once again I bring you a problem alongside my proposed solution. If you find any mistakes or come up with a different solution, please let me know!

### Problem statement

The Shire is a lovely place where $N$ Hobbits live in perfect harmony. Or at least they lived, until a Hobbit decided to become an outside decorator and convinced some of his friends to paint their front doors with a very _fashionable_ purple (all doors were yellow before that preposterous change).

Overnight, the perfect balance and harmony in which the Hobbits lived shattered, and Hobbits whose doors were different colours couldn't stand one another.

Worried, the great and wise Gandalf hurried to the Shire to try and settle this matter. This was what he decided to do: in alphabetical order, he would visit each Hobbit. When visiting a Hobbit, he would change the colour of its door if and only if there were more Hobbits mad at him than there were Hobbits at peace with him. After visiting each Hobbit once, he would visit them all again in the same order, and then again, and then again, ..., repeating this process until a complete round of visits didn't change a thing.

Is Gandalf's task always going to end, regardless of $N$ and of the way the doors are coloured when he first arrives? Or are there values of $N$ and/or door colourings such that Gandalf will have to spend an eternity trying to solve the Hobbits' problems?

!!! Give it some thought... Grab a piece of paper and play out some scenarios by hand!

!!!! **Hint**: Gandalf's endeavour is always a finite one.

!!!! **Hint**: look for a "semi-invariant"; a quantity that can only change in a certain way, which allows you to verify that Gandalf will rest eventually.


### Solution

Gandalf's task always has an end. To see why, imagine the $N$ Hobbits represented as dots, and every two Hobbits are connected by a line. That line is green if they are friendly towards each other (their front doors have the same colour) and red if their front doors have different colours. Now count the number of red lines in that representation and call it $R_0$, where the $0$ indicates the number of visits Gandalf has already paid to the Hobbits. If Gandalf already visited $t$ Hobbits, let $R_t$ denote the number of red lines in the representation I defined earlier.

It should be fairly easy to see that we have $R_{t+1} \leq R_t$. This is true because when Gandalf visits a Hobbit, he will only change its door if that means the number of green lines increases, i.e. the number of red lines decreases. This means only two things can happen:

 - For a certain $k$, $R_k = 0$ and that means all Hobbits are now friendly towards each other;
 - For some $k, R_{k+N} = R_k$, which means that Gandalf visited every single Hobbit in the Shire and didn't change a single door, which means his visits stopped having any impact whatsoever.

Either way, we can see that another complete round of visits by Gandalf would change nothing, and thus Gandalf can now rest.

Bonus question: is there a value of $N > 1$ and a colour configuration such that the second bullet point happens? That is, Gandalf's visits become irrelevant at some point but not all Hobbits are friends with each other? Share your findings in the comment section below ;)
