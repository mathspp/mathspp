---
metadata:
    description: In this blog post I will talk about probabilities and estimated values, with Pokémon and filling the Pokédex as motivating example.
title: "Filling your Pokédex - a probabilistic outlook"
---

Join me in this blog post for Pokéfans and mathematicians alike.
Together we'll find out how long it would take to fill
your *complete* Pokédex by only performing random trades.

===

![A picture of a trade happening inside Pokémon Home](thumbnail.jpg)

# Objective

The objective of this blog post is to take a look at how one computes
the average time it takes for a random event to happen.
We will use a particular mechanic of the Pokémon games as the motivating example,
after briefly introducing it in case you don't know it.

More specifically, we will find out how long it would take,
on average, to complete the Pokédex if we were only allowed
to perform random trades.
Keep reading below to see how long it would be!

We will perform the necessary calculations and I will explain them step-by-step
to you, so you learn how to do them for yourself.
This is done by the end of the post, so as not to break the main flow of the post.

By the time we are done,
 - you will have an improved understanding of how to formulate
the theoretical solution;
 - you will have performed calculations that involve infinite summations,
 - and you will have learned a couple of tricks to compute these summations.


# Pokémon games and the Pokédex

This brief section aims at introducing you to the world of Pokémon.
I will explain the very basics of the game, enough so that you understand
the ["Formulating the problem"](#formulating-the-problem) section that follows.
You can skip to that section if you already know what the Pokémon games are all about.

In the Pokémon games there exist some creatures (called Pokémon,
short for "pocket monsters") that you can capture and collect.

Here's a low-quality image with three of them:

![A low-quality picture of three Pokémon](_pokemons.jpg)

At the time of writing, there's [898][wiki-pokemon-list] different Pokémon.
In the games, your *Pokédex* is like a catalogue of all the Pokémon you've seen
and you've owned, and one of the objectives of the games -
at least for players that take the game seriously -
is to fill the Pokédex (i.e. meet all Pokémon).

Pokémon can be met in the wild but you can also trade them with your friends
or with random people on the Internet.

In this blog post we wish to see how long it would take you to fill up your Pokédex
if all you could do was make *random* trades.
That is, if your only way of gaining
access to new Pokémon was through trading, but in such a way that you had **no**
control whatsoever on the Pokémon you would be receiving.


# Formulating the problem

We wish to see the average number of trades it would take before we got to see
all 898 Pokémon that exist at the time of writing.

We will assume we start with one single Pokémon and we will also assume that,
in a random trade, all 898 Pokémon are equally likely to make their way to you.

(This assumption will make the calculations slightly easier
but doesn't necessarily hold,
for example it is clear in the "Wonder Trade" system of Pokémon Home that some Pokémon
appear much more frequently than others.)

## Abstracting it from the context

If we strip the problem of its *"superfluous"* details (from the mathematical
point of view), then what we want to know is the estimated value of the number
of trials it would take for us to observe the remaining 897 outcomes of a random event,
assuming all events are equally likely.

In here, the outcomes refer to "receiving a specific Pokémon" and we only consider 897
of those because there's one Pokémon we don't need to receive: the one we start with.

# Formulating the solution

In tackling probability problems like this,
it is often useful to try and break it down into several parts,
so that we have smaller subproblems that are easier to deal with.
Then, we perform the computations we need for each of the subproblems and in the end
we combine everything in the appropriate way.

## Breaking it down

For this particular problem,
thinking about how many trades we need in order to find all 898 Pokémon
may be a bit overwhelming,
but we can instead think about these:

 > If I have seen $10$ Pokémon so far, how long (on average)
 > will it take for me to receive a new Pokémon through a random trade?

 > If I have seen $253$ Pokémon so far, how long (on average)
 > will it take for me to receive a new Pokémon through a random trade?

 > If I have seen $890$ Pokémon so far, how long (on average)
 > will it take for me to receive a new Pokémon through a random trade?

and, in general,

 > If I have seen $n$ Pokémon so far, how long (on average)
 > will it take for me to receive a new Pokémon through a random trade?

In fact, once we think about the problem above and recognise it as the most important
question we need to answer, we can write down the solution to our original problem!

The solution to the original problem reads

$$
\overset{\text{Time it takes}}{\underset{\text{different Pokémon}}{\text{to meet 898}}} = \overset{\text{Time it takes}}{\text{to meet 2nd Pok.}} + \overset{\text{Time it takes}}{\text{to meet 3rd Pok.}} + \cdots + \overset{\text{Time it takes}}{\text{to meet 898th Pok.}}
$$

Now we just need to compute the terms to the right of the equality above,
and trust me:
these are much simpler to compute when compared to the original problem.

## Meeting a new Pokémon

Let $T(n)$ represent *"the time it takes to meet a new Pokémon,
assuming we've seen $n$ different Pokémon so far"*.
In that case, because we start with a single Pokémon, the solution is

$$
\sum_{n = 1}^{897} T(n) = T(1) + T(2) + T(3) + \cdots + T(897)
$$

Now we compute $T(n)$ for a generic value of $n$ and then we sum everything up.
How do we do that, though?

Well, let's say we have seen $n$ Pokémon and we get a random Pokémon through
a random trade.
How likely is it that it is a new Pokémon?
The probability that we have already seen it is $\frac{n}{898}$
and the probability that it is a brand new Pokémon is
$1 - \frac{n}{898} = \frac{898 - n}{898}$.

So, the probability of meeting a new Pokémon after seeing $n$ different Pokémon
is $\frac{898 - n}{898}$.
Of course, unless $n = 0$,
this is less than $1$ and so it may happen that we get a repeated Pokémon
and we need to make more random trades before getting a new Pokémon.
This means that computing $T(n)$ is also accomplished by breaking this
problem into smaller problems and tackling those.

## Breaking it down... again?

In fact, notice that meeting a new Pokémon after having seen $n$ different Pokémon
can happen in a variety of ways:

 - We get a new Pokémon on the first try.
 - We get a repeated Pokémon and then a new one on the 2nd try.
 - We get two repeated Pokémon and then a new one on the 3rd try.
 - ...

If we manage to compute each of the probabilities above,
then we can use the definition of *expected value* to compute $T(n)$.

### Expected value

Remember that the *expected value* of something is equal to a sum
(or an infinite sum, a *summation*):
each single outcome times the probability that specific outcome has of happening.

For example, what is the *expected value* of a dice roll?
In other words, what is the average of a dice roll?
Well, there's the outcomes $1, 2, 3, 4, 5, 6$ and each one has a probability $\frac16$
of happening, so the expected value of a dice roll is

$$
\begin{gathered}
\frac16\times 1 + \frac16\times 2 + \frac16\times 3 + \frac16\times 4 + \frac16\times 5 + \frac16\times 6 = \\
= \frac16\times\left(1 + 2 + 3 + 4 + 5 + 6 \right) = \\
= 3.5 ~.
\end{gathered}
$$

We will do a similar thing to compute $T(n)$.
Let $p(n) = \frac{898 - n}{898}$, because I'm lazy, and notice the following:

 - It can take $1$ trade to meet a new Pokémon after having seen $n$ Pokémon,
and that happens with a probability $p(n)$.
 - It can take $2$ trades to meet a new Pokémon after having seen $n$ Pokémon,
and that happens with a probability $(1 - p(n))p(n)$ -
the first trade reveals a repeated Pokémon and the second trade reveals a new one.
 - It can take $3$ trades to meet a new Pokémon after having seen $n$ Pokémon,
and that happens with a probability $(1 - p(n))^2p(n)$ -
the first two trades reveal repeated Pokémon and the third one reveals a new one.
 - ...
 - It can take $k$ trades to meet a new Pokémon after having seen $n$ Pokémon,
and that happens with a probability $(1 - p(n))^{k-1}p(n)$ -
the first $k-1$ trades reveal repeated Pokémon and the $k$<sup>th</sup> one reveals a new one.
 - ...

## The time it takes to meet a new Pokémon

If we take the train of thought that I laid out above, and if we write a summation
with all possible numbers of trades we might need to meet a new Pokémon and if,
on top of that, we multiply each said number by the probability that *that* is the
amount of trades we need, then we get

$$
T(n) = \sum_{k = 1}^\infty k \times (1 - p(n))^{k-1}p(n)
$$

and that might look ugly, but that is the definition of expected value of the
event "meeting a new Pokémon after having seen $n$ different Pokémon".
Thankfully, the result looks much nicer.
Please, go ahead and try to compute the summation for yourself.
It is a really good exercise.
The calculation is done step-by-step
[by the end of the post](#auxiliary-calculations).

The result of the summation is

$$
T(n) = \frac{1}{p(n)}
$$

## The time it takes to meet all Pokémon

Previously we have seen that our problem was reduced to computing

$$
\sum_{n = 1}^{897} T(n)
$$

and now we know that $T(n) = \frac{1}{p(n)} = \frac{898}{n - 898}$,
so we can put everything together and compute

$$
\begin{aligned}
\sum_{n = 1}^{897} T(n) &= \sum_{n = 1}^{897} \frac{1}{p(n)} \\
&= \sum_{n = 1}^{897} \frac{898}{n - 898} \\
&= 898\sum_{n = 1}^{897} \frac{1}{n - 898} \\
&= 898\sum_{n = 1}^{897} \frac{1}{n} ~ .
\end{aligned}
$$

Now, that particular sum $\frac11 + \frac12 + \cdots + \frac1{897}$ doesn't
have a nice formula,
but it is a finite summation so we can ask a calculator to do it for us.
[Using WolframAlpha][wa-result] we get $\approx 7.377$,
which means our final result is approximately

$$
898 \times 7.377 = 6624.546 ~ .
$$

And there you have it!


# Trading 6624 times

On average, you need to make roughly $6624$ random trades until you can meet
all $898$ different Pokémon there exist!


# Auxiliary calculations

Now I'll show you the auxiliary calculations I performed to achieve at the final
result.
I will also show you a couple of tricks I use myself when doing these calculations,
given that I am terrible at memorising formulas.

Go ahead and skip right to the bottom of the post if all you want is show your
appreciation for this post, by reacting with an emoji, or by commenting
with your thoughts!

If you want to stick around and learn a couple of tricks, here we go.

We want to compute the following summation:

$$
\sum_{k = 1}^\infty k(1 - p)^{k-1}p ~ .
$$

This summation is equal to $\frac1p$ whenever $|1 - p| < 1$,
but I never know that by heart.
Instead, I have to compute this every time it shows up in front of me.

In case you never did this, I don't want you to be caught by surprise.
Here's the main overview of the steps we will be taking.
Don't worry if you don't understand how everything fits together,
I just want you to have the bigger picture:

 - we will rewrite the original summation;
 - look at each term as the derivative of another term from another summation;
 - look at the sum of those terms and get a closed formula for that;
 - differentiate the formula so we get a formula for the original terms.

## Derivation of the formula

I will try to be fairly careful with all the intermediate steps but if you
just want the main idea, you can [fast-forward](#fast-forward).

In order to do so, the first thing we do is look at

$$
\sum_{k = 1}^\infty k(1-p)^{k-1}p ~.
$$

We want to look for a simple function $g_k(p)$ whose derivative is
as similar as possible to the term above.
If we pick $g_k(p) = (1-p)^k$ we see that

$$
g_k'(p) = -k(1 - p)^{k-1} ~ ,
$$

so let's rework the summation above to include the $g_k'(p)$:

$$
\sum_{k = 1}^\infty k(1-p)^{k-1}p = -p \sum_{k = 1}^\infty -k(1-p)^{k-1} = -p \sum_{k = 1}^\infty g_k'(p) ~ .
$$

Why do we care about this?
Here's what we will try to do:
we will write the summation of the $g_k(p)$ and try to relate it to the summation
of the $g_k'(p)$.
If it all works out, we get a formula for the summation of the $g_k(p)$ and
then by differentiating that formula, we get the formula for the summation
of the $g_k'(p)$.
To do this, we start by writing the following:

$$
S_K(p) = \sum_{k = 1}^K g_k(p) = \sum_{k = 1}^K (1 - p)^k ~ .
$$

Most will know the formula for the result, but I have terrible memory and
so I don't.
What I do know is how to find it out:

$$
S_{K+1}(p) - S_K(p) = (1 - p)^{K+1}
$$

and also

$$
\begin{aligned}
S_{K+1}(p) &= \sum_{k = 1}^{K+1} (1 - p)^k \\
&= (1 - p) + (1-p)\sum_{k = 1}^K (1 - p)^k \\
&= (1 - p) + (1-p)S_K(p) ~ .
\end{aligned}
$$

If we put both of these together, we get

$$
\begin{aligned}
S_{K+1}(p) - S_K(p) = (1 - p)^{K+1} &\iff (1 - p) + (1-p)S_K(p) - S_K(p) = (1 - p)^{K+1} \\
&\iff (1 - p - 1)S_K(p) = (1 - p)^{K+1} - (1 - p) \\
&\iff S_K(p) = -\frac{(1 - p)^{K+1} - (1 - p)}{p} ~ .
\end{aligned}
$$

Another way to get there, perhaps easier to understand, is by writing the following:

$$
\begin{gather}
\begin{aligned}
(1 - p)S_K(p) &= &&(1 - p)^2 + \cdots + (1 - p)^K + (1 - p)^{K + 1} ~ ,\\
S_K(p) &= (1 - p) +&&(1 - p)^2 + \cdots + (1 - p)^K
\end{aligned} \implies \\
\\
\begin{aligned}
(1 - p)S_K(p) - S_k(p) &= (1 - p)^{K+1} - (1 - p) \iff \\
S_K(p) &= -\frac{(1 - p)^{K+1} - (1 - p)}{p} ~ .
\end{aligned}
\end{gather}
$$

Now notice that by the quotient rule, and some rearranging,

$$
\frac{Kp(1-p)^K + (1 - p)^K - 1}{p^2} = S_K'(p) = \sum_{k = 1}^K -k(1 - p)^{k - 1}
$$

which means that computing $S_K(p)$ gave us a formula for the sum

$$
S_K'(p) = \sum_{k = 1}^K -k(1 - p)^{k - 1} ~ .
$$

Our original summation arises if we take $K \to \infty$,
so all we have to do is check if we can also take the limit $K \to \infty$
in the formula.

Thankfully, we have that

$$
\lim_{K \to \infty} \frac{Kp(1-p)^K + (1 - p)^K - 1}{p^2} = -\frac{1}{p^2}
$$

if $|(1 - p)| < 1$, because in that case the terms $(1 - p)^K$ become
infinitely small, so that $Kp(1-p)^K$ and $(1 - p)^K$ vanish as $K \to \infty$.

But in that case, what we managed to show was

$$
S(p) = \lim_{K \to \infty} S_K'(p) = \sum_{k = 1}^\infty - k (1-p)^{k - 1} = -\frac{1}{p^2} ~ .
$$

Looking back at our original summation, we had

$$
\sum_{k = 1}^\infty kp(1 - p)^{k - 1} = -p\sum_{k = 1}^\infty -k(1-p)^{k-1} = -pS(p) = \frac1p ~ .
$$

And that settles it.

For this section I was relatively careful with handling the infinite
summations and the derivatives, but sometimes you just don't have the time
and you know things will work out.
When that is the case, you may want to skip a few steps (at your own risk!):

## Fast-forward

If we are to fast-forward past some intermediate steps that usually won't be
problematic, we could've gone like this:

 1. Start by writing

$$
\sum_{k = 1}^\infty k(1-p)^{k-1}p = -p \sum_{k = 1}^\infty -k(1-p)^{k-1} = -p \sum_{k = 1}^\infty g_k'(p) ~ .
$$

 2. "Integrate" the $g_k'(p)$ and write

$$
S(p) = \sum_{k = 1}^\infty g_k(p) = \sum_{k = 1}^\infty (1 - p)^k ~ .
$$

 3. Compute

$$
S(p) = \frac{1}{p} ~ .
$$

 4. Differentiate

$$
S'(p) = -\frac{1}{p^2} = \sum_{k = 1}^\infty g_k'(p) ~ .
$$

 5. Plug in the original summation:

$$
\sum_{k = 1}^\infty k(1-p)^{k-1}p = -p \sum_{k = 1}^\infty - k(1-p)^{k-1} = -p \sum_{k = 1}^\infty g_k'(p) = -pS'(p) = \frac1p ~ .
$$

Playing with what you leave out of the $g_k'(p)$ in the first step allows you
to use this technique for different combinations of exponents and constant terms.
You may also have to employ several layers of this trick, for example to compute

$$
\sum_{k = 1}^\infty k^2(1 - p)^k ~ .
$$

For this one you'd split it into

$$
\sum_{k = 1}^\infty k^2(1 - p)^k = (1 - p)^2\sum_{k = 1}^\infty k(k-1)(1-p)^{k-2} + (1 - p)\sum_{k = 1}^\infty k(1 - p)^{k - 1} ~ .
$$

The right summation is basically what we just computed.
The left one would be tackled by noticing the term $k(k-1)(1 - p)^{k-2}$ is
the second derivative of $g_k(p) = (1 - p)^k$,
meaning we'd have to do step 4. above twice.

[wiki-pokemon-list]: https://en.wikipedia.org/wiki/List_of_Pok%C3%A9mon
[wa-result]: https://www.wolframalpha.com/input/?i=1+%2B+1%2F2+%2B+1%2F3+%2B+...+%2B+1%2F897
