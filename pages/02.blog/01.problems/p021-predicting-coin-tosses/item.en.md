---
metadata:
    description: This problem has two incarcerated friends try to predict coin tosses.
title: 'Problem #021 - predicting coin tosses'
---

Alice and Bob are going to be locked away separately and their faith depends on their guessing random coin tosses!

===

![A coin half buried in the sand.](coin-in-sand.jpg "Photo by Mark Normand from FreeImages")


# Problem statement

Alice and Bob are going to be incarcerated separately. Everyday at 12h, prison guard Charles meets Alice and prison guard Daniel meets Bob. Each prison guard takes his own prison guard coin out of their pocket (a coin with heads and tails, but not necessarily a fair coin) and tosses it, showing the result to the prisoner in front of him. Then, each prisoner tries to guess what the outcome of their friend's coin toss was. That is, Alice sees the outcome of Charles's coin toss and tries to guess what outcome Bob saw from Daniel's coin toss, and vice-versa. If *any* of the prisoners gets it right, both are set free.

What is the best strategy that Alice and Bob can agree upon, so that they are released as soon as possible? According to that strategy, what is the average number of days it will take them to be released?

!!! Give it some thought...

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solution

There is a perfect strategy that allows for Alice and Bob to escape prison on their first day of predicting the coin tosses. If you tried solving the problem but struggled, give it another go with this hint. Look for a perfect strategy, because it exists.

For the perfect strategy, Alice should make a guess equal to the result of the coin toss in front of her and Bob makes a guess opposite to the result of the coin toss in front of him.

To make it clear why this strategy works, let us analyse the four possible outcomes of the coin tosses:

| Charles | Daniel |
| :-: | :-: |
| Heads | Heads |
| Heads | Tails |
| Tails | Heads |
| Tails | Tails |
<br />

Note that the fact that the coins may not be fair doesn't change the fact that there are only four possible outcomes. The breakthrough comes when you realise that the outcomes that really matter are only two, which are the relationships between the two coin tosses:

| Charles | Daniel | Results are... | Who guesses |
| :-: | :-: | :-: | :-: |
| Heads | Heads | The same | Alice |
| Heads | Tails | Different | Bob |
| Tails | Heads | Different | Bob |
| Tails | Tails | The same | Alice |
<br />

And that is it! No need for boring or complicated calculations.


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox and to add your reaction below.

[subscribe]: https://mathspp.com/subscribe
