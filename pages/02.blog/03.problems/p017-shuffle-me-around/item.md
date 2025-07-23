There's 100 drawers and 100 shuffled balls. Can you find the one I choose?

===

This post contains a problem that haunted me for a couple of years. I had it lingering in my brain for a long time and it felt quite challenging... then I presented it to a friend of mine one night, and the morning after I had a text from him saying he had cracked it! So kudos to him! I only got to the answer after he gave me a hint...

## Problem statement

You and a friend are outside a room guarded by a sphinx.
The sphinx tells you that inside the room there are 100 drawers, numbered from $1$ to $100$, where each drawer contains one of a hundred balls (also numbered from $1$ to $100$).

The balls are randomly shuffled inside the drawers but each drawer contains exactly only one unique ball.
You, your friend and the sphinx are to play a game.
First of all, your friend is going to the room where they will be able check the contents of each drawer for as long as they wants; they will also be given the opportunity to swap the balls of two drawers.
This swap is _not_ mandatory.

When your friend leaves the room you won't be able to talk to them.
The sphinx will pick a number $n$ from $1$ to $100$ and you will go into the room to find the ball with the number $n$ in $50$ attempts or less.

What is the strategy that you and your friend should follow that maximizes the probability of finding the ball the sphinx picks?

!!! Hint: checking $50$ drawers at random gives you $50%$ probability of winning.

## Solution

There is a strategy that guarantees that you will be able to find the ball that the sphinx picks.
Take one more moment to think it through, now that you know for a fact that there is a perfect strategy.

The solution can be phrased shortly if you are familiar with permutations: the task of your friend is to go inside the room and see the balls inside the drawers as a permutation of the numbers from $1$ to $100$; check if the permutation has a cycle of length larger than $50$ and, if so, break it.
When you go into the room, just start by opening the drawer $n$ and then follow the cycle it traces until you hit the ball $n$; because your friend broke the only possible cycle of length greater than $50$, you are guaranteed to find the ball you are looking for.

Now let me break it down for people who are not familiar with permutations or who aren't comfortable with them.
For this, I will also exemplify with a similar problem but with $6$ drawers/balls and a maximum of $3$ attempts.

Assume this is the state the room is in:

$$\begin{bmatrix} 1 & 2 & 3 & 4 & 5 & 6 \\ 2 & 5 & 3 & 6 & 1 & 4 \end{bmatrix}$$

The top row gives the number of the drawer and the bottom row gives the number of the ball inside that drawer.
If you imagine the numbers of the balls as telling you where to go next, you can sort of write out some "paths":

 - Starting at drawer $1$, extract ball number $2$ -> open drawer $2$ to find ball $5$ -> open drawer $5$ and find ball $1$ -> now you are back at the start.
 - Starting at drawer $3$, extract ball number $3$ -> you don't even get to move.
 - Starting at drawer $4$, extract ball number $6$ -> open drawer $6$ to find ball $4$ -> back where we started.

These sequences of moves $1 \to 2 \to 5 \to 1$, $3 \to 3$ and $4 \to 6$ are actually cycles.
If you decide that your strategy will be to follow cycles, then it doesn't matter if you start with drawer $1$, $2$ or $5$: you will open all three of them looking for the $n$ ball.

Note this, as well: if you start the cycle of the drawer $d$, at the end of the cycle you find the ball with number $d$...
So, if you wanted to find ball $4$ you would go through the cycle starting at the drawer number $4$, and you would find the ball $4$ after two attempts.
If you wanted ball $5$, you would go to drawer $5$ and follow the cycle: you would find the $5$ ball at the third attempt, just the maximum number of guesses you had.

Now the actual solution to the problem lies in two small considerations:
 1. First, after the balls are randomly shuffled, there cannot be more than one cycle with length greater than $50$.
 2. Second, your friend can always go into the room and break the lengthy cycle into two bits of length smaller than or equal to $50$.

As to the first statement, we can think of the cycles as those diagrams with arrows that just go around, so the diagram $1 \to 2 \to 3 \to 1$ is the same as the diagram $2 \to 3 \to 1 \to 2$.
Then each diagram contains some portion of the total number of balls available, and two different diagrams cannot share numbers.

For example, it would not make sense to have both these diagrams: $1 \to 6 \to \cdots \to 1$ and $2 \to 6 \to \cdots \to 2$ because it means that both the drawer number $1$ and drawer number $2$ have the ball with the number $6$, which cannot be the case.
So, if there were two different cycles with length greater than $50$ we would need at least $102$ balls, but we only have $100$.
We conclude there is, at most, one cycle of length greater than $50$.

Now we just have to show that your friend can always break a cycle that is too long.
The easiest way to prove this is to show how it is done; for that, I will exemplify and let you extrapolate for the case where we have $100$ balls.

Suppose we had the cycle $1 \to 6 \to 2 \to 4 \to 3 \to 5 \to 1$, which has length $6$; we are going to break it into two cycles of length $3$.
That cycle means our drawers currently look like this:

$$\begin{bmatrix} 1 & 2 & 3 & 4 & 5 & 6 \\ 6 & 4 & 5 & 3 & 1 & 2 \end{bmatrix}$$

The problem is that, after opening drawer number $2$, we should be sent back to $1$ but instead got sent to $4$...
Well, then move the ball $1$ to drawer $2$ and put the ball that was inside drawer $2$ in the drawer from which you got ball $1$...
So do this swap:

$$\begin{bmatrix} 1 & 2 & 3 & 4 & 5 & 6 \\ 6 & 4 & 5 & 3 & 1 & 2 \end{bmatrix} \mapsto \begin{bmatrix} 1 & 2 & 3 & 4 & 5 & 6 \\ 6 & \underline{\textbf{1}} & 5 & 3 & \underline{\textbf{4}} & 2 \end{bmatrix}$$

Now we have the cycles $1 \to 6 \to 2 \to 1$ and $3 \to 5 \to 4 \to 3$.
And this proves what we needed!

Going over it one more time, your friend has to go inside the room and if there is a cycle that is too long, break it.
When the sphinx asks you to find ball $n$, just go into the room, open the drawer $n$ and follow the path the cycle gives you!

