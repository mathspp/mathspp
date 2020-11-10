---
title: 'Solution #003 - a quarrel in the Shire'
---

This post contains my proposed solution to [Problem #003 - a quarrel in the Shire][prob]. Please do not read this solution before making a serious attempt [at the problem][prob].

===

### Solution

Gandalf's task always has an end. To see why, imagine the $N$ Hobbits represented as dots, and every two Hobbits are connected by a line. That line is green if they are friendly towards each other (their front doors have the same colour) and red if their front doors have different colours. Now count the number of red lines in that representation and call it $R_0$, where the $0$ indicates the number of visits Gandalf has already paid to the Hobbits. If Gandalf already visited $t$ Hobbits, let $R_t$ denote the number of red lines in the representation I defined earlier.

It should be fairly easy to see that we have $R_{t+1} \leq R_t$. This is true because when Gandalf visits a Hobbit, he will only change its door if that means the number of green lines increases, i.e. the number of red lines decreases. This means only two things can happen:

 - For a certain $k$, $R_k = 0$ and that means all Hobbits are now friendly towards each other;
 - For some $k, R_{k+N} = R_k$, which means that Gandalf visited every single Hobbit in the Shire and didn't change a single door, which means his visits stopped having any impact whatsoever.

Either way, we can see that another complete round of visits by Gandalf would change nothing, and thus Gandalf can now rest.

Bonus question: is there a value of $N > 1$ and a colour configuration such that the second bullet point happens? That is, Gandalf's visits become irrelevant at some point but not all Hobbits are friends with each other? Share your findings in the comment section below ;)

[prob]: ../../problems/{{ page.slug }}
