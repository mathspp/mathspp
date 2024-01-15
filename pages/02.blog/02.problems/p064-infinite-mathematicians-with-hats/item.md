How can an infinite number of mathematicians figure out their own hat colours?

===

# Problem statement

An infinite number of mathematicians are standing in a line.
In a couple of minutes, a disruption in the space-time continuum will cause a black or white hat to appear on the head of each mathematician.
After that happens, the mathematicians will try to guess their own hat colour (they can't see it) based on the colours of the hats of all other mathematicians.

What's the strategy that the mathematicians must agree on, before hand, so that only a _finite_ number of mathematicians guesses wrong?

!!! Give it some thought!


# Solution

When we look at each matematician's hat we can create a sequence of $0$s and $1$s.
For example, if everyone has a white hat except for the first three mathematicians, then we would have the sequence $(1,1,1,0,0,\cdots)$.

Let us say two sequences are _alike_ if they are the same except for a finite number of positions.
As an example, the sequence we get when all mathematicians have a white hat and the previous sequence are _alike_ because they differ only in the first three positions: $(\underline{0}, \underline{0}, \underline{0}, 0, 0,\cdots)$.

For any sequence $S$ we can think of all the other sequences with which $S$ is _alike_.
Let us call that group of _alike_ sequences $[S]$.
What the mathematicians must do is: for every single group $[S]$ they must pick a random sequence from that group and memorise it.

When they are given the hats, they must look at their colleagues and figure out in which group $[S]$ their hat sequence belongs.
Then, they will recall the sequence they all memorized from that group and each mathematician will say that their hat is of the colour corresponding to their position on the memorized sequence.
By definition, only a finite number of mathematicians guesses wrong because the sequence they will recreate and the real sequence are _alike_, i.e. they differ only in a finite number of positions.
