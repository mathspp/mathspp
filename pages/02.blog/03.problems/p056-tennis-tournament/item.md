How many matches does it take to find the winner of a tennis tournament?

===

![](thumbnail.png "Photo by Valentin Balan on Unsplash.")


## Problem statement

Suppose that $n$ players are going to play in a tennis tournament.
The players will be randomly assigned to [brackets](https://en.wikipedia.org/wiki/Bracket_(tournament)),
and each bracket plays a match.
The winner of each match advances to the next bracket,
until the two final players face each other in the final match,
which determines the winner.

As a function of the number of players $n$,
how many matches are needed to determine the winner of the tournament?

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


## Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - David H., Taiwan;
 - Umberto M., Italy;
 - Christ van W., The Netherlands;
 - Thierry Z., Burkina Faso;
 - Kishan M., India;
 - Ioan E., Ukraine;
 - Soliu, Nigeria;
 - Francisco M., Mexico;
 - Han A., Malaysia;
 - Pavan J., India;
 - David F., United States;

Know how to solve this?
Join the list of solvers by [emailing me][email] your solution!


## Solution

There is a nice intuitive solution to this problem that means you don't need to do any calculations whatsoever!

Each time two players face each other, one player leaves the tournament and the other player remains.
On top of that, determining the winner is the same as saying that all players have left the tournament,
except for one.
Thus, if there are $n$ players, we want to eliminate a total of $n - 1$ players,
which means we need to play $n - 1$ matches.


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe
