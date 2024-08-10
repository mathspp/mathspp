Two realtors discuss who's netting the award for highest average commission, but it isn't clear who the winner is...

===


![](thumbnail.png "Photo by Dillon Kydd on Unsplash.")


# Problem statement

Alice and Bob are two realtors that work at the same premium real estate agency, WSH.
Because WSH really wants to focus on selling premium properties,
they instated a monetary prize that is awarded every two years to the realtor that has the highest average commission over the past two years.

The award for the years 2020 and 2021 is being attributed soon.
One day, at the office, Alice overheard Bob gloating:

 > “I can't wait to get my money! I already know what I'll be doing with it!”

Alice turned to Bob and asked:

 > “How can you be so sure that you are the one netting the prize?”

Bob smirked and returned:

 > “I guess you haven't been paying attention, Alice!
 > In 2020 I was the realtor with the highest average commission over that year...
 > And in 2021 I did it again!”

Alice smiled and returned to work.

Who do you think is right?
Is Bob definitely getting the award?
Or is there a scenario in which Alice gets the prize?

!!! Give it some thought!


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - David H., Taiwan;
 - Michael W., US;

Know how to solve this?
Join the list of solvers by [emailing me][email] your solution!


# Solution

Bob is wrong because there is a scenario in which Alice can win the award...
Even though Bob had the highest average commission both in 2020 and 2021.
Does this seem counterintuitive?
Because it is...
This is an instance of the [Simpson's Paradox][simpsons-paradox].

To paint a clearer picture of how the Simpson's Paradox comes about,
I will create a fairly extreme hypothetical scenario:

Suppose that Alice and Bob are the _only_ realtors working for WSH,
so that we don't have to care about anyone else.

Suppose that, in 2020, these were the commissions each one made:

 - Bob sold a single house and made a $10,005 commission.
 - Alice sold a single house and made a $10,000 commission.

Furthermore, suppose that, in 2021, these were the commissions each one made:

 - Bob sold a single house and made a $20,005 commission.
 - Alice sold 1000 houses and made a $20,000 commission _on each one_.

Here is a table summarising the average commission each one of them made for each year:

| | 2020 | 2021 |
| -: | -: | -: |
| Alice | $\frac{1 \times 10000}{1} = 10000$ | $\frac{1000 \times 20000}{1000} = 20000$ |
| Bob | $\frac{1 \times 10005}{1} = 10005$ | $\frac{1 \times 20005}{1} = 20005$ |

As we can see, Bob has a higher average commission in 2020...
But also in 2021!

However, if we add a third column for 2020 and 2021 combined,
everything becomes clear:

| | 2020 | 2021 | 2020 + 2021 |
| -: | -: | -: | -: |
| Alice | $\frac{1 \times 10000}{1} = 10000$ | $\frac{1000 \times 20000}{1000} = 20000$ | $\frac{1 \times 10000 + 1000 \times 20000}{1001} \approx 19990$ |
| Bob | $\frac{1 \times 10005}{1} = 10005$ | $\frac{1 \times 20005}{1} = 20005$ | $\frac{1 \times 10000 + 1 \times 20000}{2} = 15000$ |

The numbers don't lie!
Alice has a higher average commission if the time period being considered is 2020 and 2021.

<!-- v -->
Like I said, this was a very skewed scenario that I came up with to **explain the Simpson's Paradox**.
I have also written about a [real-life example of the Simpson's Paradox][simpsons-paradox-reallife],
if you are interested in learning more!

[simpsons-paradox]: /blog/til/simpsons-paradox
[simpsons-paradox-reallife] /blog/til/simpsons-paradox#example-of-simpsons-paradox


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe
<!-- ^ -->
