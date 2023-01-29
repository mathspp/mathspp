Can you prove that there are arbitrarily many primes in arbitrarily big intervals?

===

# Problem statement

The problem statement asks you to prove that for _any_ positive integer number $k$ you can think of,
there will be a certain lower bound $n_0$ (that depends on $k$) such that for _any_ integer $n \geq n_0$,
_there is_ an interval of length $n$ that contains _exactly_ $k$ primes.
(When we talk about the length of the interval, we are talking about how many integers it contains.)

If the statement confused you a bit, that is ok.
Let me rephrase it.

You and I will play a game.
I will think of a positive integer $k$.
Now, your job is to come up with another positive integer $n_0$ such that,
if I pick a number $n$ greater than $n_0$,
you can always find an interval of size $n$ that contains $k$ integers.

For example, if I thought of $k = 5$, you _could not_ pick $n_0 = 4$.
Why not?
Because if I pick $n = 4$, there is _no_ interval of length $4$ that contains $5$ prime numbers...
Especially because an interval of length $4$ contains only $4$ integers!

This problem was posed to me by my mathematician cousin and I confess that worried me a bit.
Funnily enough, the problem has a surprisingly simple solution.
(I am not saying you will get there easily.
I am just saying that once you do, you will realise the solution was not very complicated.)

!!! Give it some thought!

**Remember**:

 - there are infinitely many primes; however
 - they become scarcer and scarcer the further you go down the number line.


# Solvers

Congratulations 🎉 to everyone who managed to solve this problem:
Congratulations to you if you managed to solve this problem correctly! 
If you did, feel free to

 - Rodrigo G. S., Portugal 🇵🇹  (<- example);

If _you_ managed to solve this problem, you can [add your name to the list][add-name]!
You can also [email me][email] your solution and we can discuss it.


# Solution

A thing I like about this problem is that not only can you prove that interesting statement about the prime numbers, but you can also determine exactly what the lower bound $n_0$ is.

Let us say that $p_k$ is the $k$-th prime.
Then, if we set $n_0 = p_k$, we are good to go.
Let me show you why.

Suppose that $n$ is any integer $n \geq n_0$.
Then, the interval $[1, n]$ contains $p_k$ in it.
Why?
Because $p_k = n_0$ and $n \geq n_0$.

So, the interval $[1, n]$ contains $k$ _or more_ prime numbers.
If it contains $k$ prime numbers, we just found our interval of length $n$ that contains exactly $k$ primes.
If it contains more than $k$ primes, we must do something about it.

If the interval $[1, n]$ contains more than $k$ prime numbers, then we start sliding the interval to the right, like so:

$$
[1, n] \rightarrow [2, n+1] \rightarrow [3, n+2] \rightarrow [4, n+3] \rightarrow \cdots \rightarrow [1 + s, n + s]
$$

If we slide the interval for enough time, we will eventually find an $s$ such that the interval $[1 + s, n + s]$ contains exactly $k$ primes.
But how can I be so sure?

Remember that the interval $[1, n]$ has more than $k$ primes (because we assumed it did).
Every time you slide the interval to the right by one unit, two things happen:

 - a number leaves the interval on the left; and
 - a new number enters the interval on the right.

The numbers entering and leaving the interval may or may not be prime (we don't know),
but we know _for sure_ that the number of prime numbers inside the interval can only fluctuate by one:

 - if the number leaving is prime and the number entering isn't, the quantity of primes inside the interval goes down by 1;
 - if the number leaving is prime and the number entering is prime, the quantity of primes inside the interval stays the same; and
 - if the number leaving isn't prime and the number entering is prime, the quantity of primes inside the interval goes up by 1.

For example, the interval $[1, 6]$ contains $3$ primes: $2$, $3$, and $5$.
If we slide it to $[2, 7]$, the number $1$ leaves the interval and the number $7$ enters the interval and the number of prime numbers in the interval goes up from $3$ to $4$: $2$, $3$, $5$, $7$.
If we slide it once more, we get to $[3, 8]$ and we go back to $3$ prime numbers (because $2$ is prime and left the interval but $8$ is not prime).

So, we see that sliding the interval to the right will make the number of primes inside it fluctuate by a maximum of one at a time.

At the same time, we know that if we slide for long enough (that is, if $s$ becomes large enough),
the total number of prime numbers inside the interval must go below $k$!
If it didn't – if the number of prime numbers in the interval $[1 + s, n + s]$ were always greater than $k$ for _any_ value of $s$ –,
then the proportion of prime numbers among the integers would be above $k / n$.
We know that that isn't possible, so there must be a value of $s'$ for which $[1 + s', n + s']$ already has less than $k$ prime numbers.

To conclude, if $[1, n]$ has more than $k$ prime numbers, if $[1 + s', n + s']$ has less than $k$ prime numbers,
and if the number of prime numbers inside an interval can only go up or down by $1$,
there was a value of $s < s'$ such that the interval $[1 + s, n + s]$ contains exactly $k$ prime numbers.


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[add-name]: https://github.com/mathspp/mathspp/edit/master/pages/02.blog/02.problems/{{ page.folder }}/item.md
[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe
