How can 2018 monks find eternal peace?

===


## Problem statement

On an island far from the bad, vain, and consumerist habits of today's societies, live $2018$ silent monks.
Among other things, the monks renounced vanity and thus they never saw themselves on the mirror nor on any other reflecting surface.

One day, the volcano in the centre of the island – slowly but steadily – started hinting at an incoming eruption and the monks grew worried as the days passed.

Some days later, at exactly 23h59, a divine being showed itself to the monks in their dreams and offered them a painless death – thus avoiding the wrath of the volcano – as long as every monk found out the colour of his own eyes.

The divine being also told them that every monk in the island had either blue or green eyes and that there was at least one monk with blue eyes.

The divine being left with its final condition: _every day_, at 23h59, all monks who knew the colour of their eyes would be taken in peace.

Depending on the number $k$ of monks with blue eyes ($k > 0$), will the monks save themselves?
In how many days?


## Solution

To arrive at the solution, we start with a small number of monks with blue eyes and keep extending the train of thought to increasing values of $k$.
Let us start with $k = 1$.

Set $k = 1$ and let us say that the day when the divine being appeared in the monks' dreams at 23h59 was day $0$, making the next morning the morning of day $1$, making the day after that day, day $2$, and so on and so forth.

Suppose now that I am the only monk with blue eyes.
Day $1$ I wake up and notice that none of the other $2017$ monks has blue eyes.
Because the divine being told me that $k$ was strictly greater than $0$ – i.e. there is _at least_ one monk with blue eyes – I must be the _only_ monk with blue eyes and at 23h59 of day $1$ I am taken in peace.

Suppose now I am one of the $2017$ monks with green eyes.
Day $1$ I wake up and see one monk with blue eyes and $2016$ monks with green eyes.
The divine being only said that $k > 0$ and thus I am uncertain of whether $k = 1$ or $k = 2$, this depending on the colour of my own eyes.
Either way, by the end of day $1$ I am not sure of the colour of my eyes...
Nonetheless, I do know that if it is the case that $k = 1$, then the monk I saw with blue eyes would have been taken.
If I wake up the next morning and that monk is still alive then $k = 2$ and I must have blue eyes as well.

Because (we are assuming) there is only one monk with blue eyes, I wake up in the morning of day $2$ and conclude I have green eyes.
But all other monks can complete this same train of thought and thus everyone realizes they have green eyes.
At 23h59 of day $2$ all monks with green eyes are taken in peace.
If $k = 1$, we need $2$ days for all monks to save themselves.

We can, and we should, try to repeat this train of thought for $k = 2$ unless we are pretty sure of how this process should generalize.

Let $k = 2$.
Say I'm one of the two monks with blue eyes.
Day $1$ I wake up and notice there is $1$ monk with blue eyes and $2016$ monks with green eyes.
I am not sure if $k = 1$ or $k = 2$.
Either way, I know that if it is the case that $k = 1$, day $2$ I will wake up and one of the monks will be gone.

When I wake up in the morning of day $2$ and notice the monk with blue eyes is still alive, I conclude that I have blue eyes.
Because of that, at 23h59 of day $2$ the other monk with blue eyes and I are taken in peace.

Now, let us say I am one of the monks with green eyes.
Day $1$ I wake up and see two monks with blue eyes and $2015$ monks with green eyes, making me wonder whether $k = 2$ or $k = 3$.
If it is the case that $k = 2$, in the morning of day $3$ the other two monks will be gone and I will be able to conclude that I have green eyes, much like the other $2015$ monks.
This means that at 23h59 of day $3$ the $2016$ monks with green eyes are taken in peace, i.e. if $k = 2$ the monks need $3$ days to earn their salvation.

We start to notice a recursive pattern in our train of thought. For the reader that is yet to be convinced, try repeating the same train of thought for $k = 3$ and conclude that we need $4$ days for salvation.
We now present the generic proof:

We will proceed to prove that, under the conditions of the problem statement and assuming that the number $n$ of monks on the island is finite, with $0 < k \leq n$ monks with blue eyes, the monks need $k+1$ days to earn their salvation.
For that, we will use induction on the number $t$ of monks with blue eyes.

Define the following statements:

 - $P(t)$: "with $t$ blue eyed monks, the _monks with blue eyes_ need $t$ days to find out the colour of their eyes"; and
 - $P'(t)$: "with $t$ blue eyed monks, the _monks with green eyes_ need $t+1$ days to find out the colour of their eyes".

The beginning of the proof shows that both $P(1)$ and $P'(1)$ are true.
Now we show $P(t) \\implies P(t+1)$ and $P(t) \\implies P'(t)$.

Suppose $P(t)$ is true and that $k = t+1$.
Any of the $t+1$ monks with blue eyes will wake up day $1$ and see $t$ monks with blue eyes, as well as $n-t-1$ monks with green eyes, making that monk wonder if $k = t$ or $k = t+1$, given that he does not know the colour of his own eyes.
Nonetheless, the monk knows that $P(t)$ is true and, if it is really the case that $k = t$, at 23h59 of day $t$ all the $t$ monks with blue eyes will be gone and the monk will wake up in the morning of day $t+1$ only to find his friends were released.

If it is not the case that $k = t$, the other $t$ blue eyed monks won't be gone by the morning of day $t+1$ leading to the obvious conclusion that $k = t+1$.
This implies that that day, at 23h59, the $t+1$ blue eyed monks are taken in peace.
We conclude that $P(t+1)$ is true.
By induction, this proves that $P(t)$ is true for all values of $t$ that make sense, i.e. $t < n$.

Now we show that $P(t) \implies P'(t)$, which concludes the proof.
If $k = t$, day $1$ every green eyed monk will see $t$ monks with blue eyes and $n-t-1$ monks with green eyes, wondering if $k = t$ or $k = t+1$.
Either way, the monk knows that $P(t)$ is true and if he wakes up in the morning of day $t+1$ and the other $t$ blue eyed monks are gone he concludes that his eyes must be green, i.e. he took $t+1$ days to find out he had green eyes.
This train of thought can be carried out by any of the $n-t$ monks with green eyes and at 23h59 of day $t+1$ they are all taken in peace.
This concludes our proof.

Summing up, if an island has $n$ monks and $0 < k \leq n$ monks with blue eyes, $k+1$ days are needed for all the monks to earn their salvation.
