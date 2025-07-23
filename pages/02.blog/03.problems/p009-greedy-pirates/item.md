How can a greedy pirate captain keep his treasure to himself?

===


## Problem statement

Captain Jack Girão was the most feared pirate of the 15th century.
He was accompanied by 9 toothless pirates and they pillaged every ship that sailed their seas.
Besides having perfect logic reasoning, all the pirates were sadistic!
Whenever they had a chance, they would throw a fellow pirate to the sea so they could watch him swim with the sharks.
The only thing the pirates preferred over watching a mate be thrown overboard was gold.

One day, Captain Jack Girão and his crew pillaged a ship from where they took 30 gold coins.
Captain Jack Girão, dangerous but democratic, summoned a meeting to distribute the recently plundered gold.
Gold distribution among perfectly logical and democratic pirates follows these rules:

 1. The Captain proposes a certain distribution of the gold coins and every pirate has to vote if they approve said distribution or not.
 2. If half or more of the votes are in favour, the proposed distribution passes.
 If not, the Captain is thrown overboard, the most experienced pirate becomes the Captain, and then the process is repeated.

What distribution should Captain Jack Girão propose in order to maximize his own profits and to keep his life?


## Solution

If the crew is composed of 10 pirates in total, a proposal needs at least 5 votes to be accepted.
We can assume Captain Jack Girão never votes against himself, meaning we only need to ensure 4 more votes.

A pirate who isn't offered gold will always vote against us.
After all, these pirates were very sadistic.
This means we'll have to spend a bare minimum of 4 gold coins, which in turn means it is impossible for Captain Jack Girão to keep more than 26 coins.
However, we will show that there is a strategy according to which Captain Jack Girão gets to keep 26 gold coins!

We will follow an inductive reasoning: we will see what happens when the crew is composed of 2 pirates, then what happens when we have 3 pirates, then 4, etcetera.
To make the explanation easier, let us say that the pirates' names are, in descending order of experience, Captain Jack Girão, Abel, Bernard, Charles, Dickson, Esteban, Fabian, Gabe, Henry and Ian.

Suppose there are only 2 pirates, Henry and Ian: as there are only two pirates, one vote suffices to approve the proposal, so Henry can do whatever he wants.
He'll propose the distribution $(30, 0)$ (30 coins for him and 0 for Ian) and he'll have his proposal accepted.

Suppose there are only 3 pirates, Gabe, Henry, and Ian.
Ian knows that if Gabe is thrown overboard, then Henry will keep all 30 coins to himself and give none to Ian.
So, if Gabe offers a single coin to Ian, that's enough to buy his vote.
Ultimately, if it comes down to it, Gabe just has to make the offer $(29, 0, 1)$.

Suppose there are only 4 pirates, Fabian, Gabe, Henry, and Ian.
Henry knows that if Fabian is thrown overboard, then Gabe and Ian will share the coins amongst themselves and Henry gets nothing.
So, if Fabian offers a single coin to Henry, that's enough to buy his vote.
Ultimately, if it comes down to it, Fabian just has to make the offer $(29, 0, 1, 0)$.

If we keep following this train of thought, we see that these are the greediest distributions that get accepted for each number of pirates:

 1. $(30)$
 2. $(30, 0)$
 3. $(29, 0, 1)$
 4. $(29, 0, 1, 0)$
 5. $(28, 0, 1, 0, 1)$
 6. $(28, 0, 1, 0, 1, 0)$
 7. $(27, 0, 1, 0, 1, 0, 1)$
 8. $(27, 0, 1, 0, 1, 0, 1, 0)$
 9. $(26, 0, 1, 0, 1, 0, 1, 0, 1)$
 10. $(26, 0, 1, 0, 1, 0, 1, 0, 1, 0)$

Summing it up, 26 was the maximum ammount of gold coins the Captain could hope for, as he would have to spend at least 4 of them.
On the other hand, there is a distribution where he gets to keep 26 coins, thus the answer has to be 26.
