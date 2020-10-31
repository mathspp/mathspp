---
metadata:
    description: This blog post contains my proposed solution to one of the problems of this blog.
title: 'Solution #021 - predicting coin tosses'
---

This post contains my proposed solution to [Problem #021 - predicting coin tosses][prob]. Please do not read this solution before making a serious attempt [at the problem][prob].

===

### Solution

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

If you have any questions about my solution, found any mistakes (whoops!) or would like to share *your* solution, be sure to leave a comment below.

[prob]: ../../{{ page.slug }}
