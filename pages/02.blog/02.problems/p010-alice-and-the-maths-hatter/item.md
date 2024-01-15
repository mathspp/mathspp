How can 4 friends guess their own hat colours?

===


# Problem statement

Alice and her friends Bob, Charles, and Diana, were captured by the Maths Hatter.
The Maths Hatter took Alice and locked her away.
Then, he placed Bob, Charles, and Diana, on a staircase:

```
 B
--- C
|--- D
 |---
```

Bob could see Charles and Diana and Charles could see Diana, but Diana couldn't see anybody.
After arranging the three friends like this, the Maths Hatter took four hats – two white and two black – and he gave one hat to each of the friends.

After doing this, the Maths Hatter said he'd release them all if any one of the four friends could guess the colour of their own hat, knowing that there are two hats of each colour, the friends can't communicate, and the three friends on a staircase can't turn around to look at the friends behind them.

Can the four friends save themselves?


# Solution

Either Charles and Diana have hats of the same colour, or not.
If their hats are of the same colour (say they are both black), then Bob can see two black hats and will be able to deduce his own hat has to be white (just like Alice's).

If Charles and Diana have hats of different colours (say Diana has a white hat and Charles a black hat) then Bob won't be able to tell the colour of his hat.
After a while, Charles will realise Bob is still silent and think: "if Bob hasn't guessed his own hat it means Diana and I have hats of different colours!".
Therefore, Charles concludes his hat is black because he sees Diana with a white hat.
