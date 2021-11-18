Today I learned about the DARPA network challenge and the power of referral systems.

===

![A red balloon held by a hand.](thumbnail.png "Photo by Blake Cheek on Unsplash")

# DARPA network challenge

The DARPA network challenge was a challenge that took place in 2009 in the United States.

(DARPA stands for Defense Advanced Research Projects Agency, a research organisation of the US Department of Defense.)

The challenge was for participants to report the locations of 10 red balloons that were to be deployed, at the same time, in mainland US.
In other words, 10 balloons were scattered around the US and the teams had to find them.

Its purpose was to study the effect that Internet and social networking could have in solving time-constrained, broad-scope challenges.

The challenge had an associated pecuniary prize worth $40,000.

# Winning strategy

The challenge was won by an MIT team who employed a networking referral strategy:
each of the 10 balloons was defined to be worth \$4,000 (total prize divided by total number of balloons).

The person that accurately reported the location of a balloon would be awarded \$2,000.
The person that had referred the reporter got \$1,000.
The person that referred the referrer got \$500.
And so on, and so forth.

By consecutively dividing the prize in half, the team was able to make sure that the incentives flowed up the referral network, and this incentivised people to refer yet other people, as the new referrals would be working “for” the referrers, and not “against”.

One of the things I like about this strategy is how it theoretically works for an arbitrarily long chain of referrals, because even if you have to give compensation to millions of referrers in a chain, adding up all the money spent on compensations will never exceed the money allocated for each balloon.

That's because the series

$$
\sum_{n = 1}^\infty \frac1{2^n} = \frac12 + \frac14 + \frac18 + \cdots
$$

adds up to 1.

The prize money that was left, after awarding the compensations, was donated to charity.


That's it for now! [Stay tuned][subscribe] and I'll see you around!


[subscribe]: /subscribe
[pathlib]: https://docs.python.org/3/library/pathlib.html
