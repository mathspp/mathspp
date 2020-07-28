---
metadata:
    description: This post goes over a well known riddle about measuring quantities.
title: Water buckets riddle
---

Can you measure exactly $2$L of water with two plain buckets with volumes of $14$L and $5$L? Of course you can!

===

![some gray tin (?) buckets](buckets.jpg "Photo by Carolyn V on Unsplash")

The way you go about measuring those $2$L of water is simple:

 - Pour the $5$ bucket into the $14$ one, then
 - fill the $5$ bucket, then
 - pour the $5$ bucket into the $14$ one, then
 - fill the $5$ bucket, then
 - pour the $5$ bucket into the $14$ one, then
 - empty the $14$ bucket, then
 - pour the $5$ bucket into the $14$ one, then
 - fill the $5$ bucket, then
 - pour the $5$ bucket into the $14$ one, then
 - fill the $5$ bucket, then
 - pour the $5$ bucket into the $14$ one, then
 - fill the $5$ bucket, then
 - pour the $5$ bucket into the $14$ one

Piece of cake. Ok, maybe this sequence of moves is annoyingly long but this _is_ a relatively well-known riddle and is a really interesting problem, honestly: given $N$ buckets of capacities $c_1, c_2, \cdots, c_N$, as well as a target value $T$ and an infinite source of water, is there a sequence of moves that puts exactly $T$ litres in one of the buckets?

There are some cases for which one can immediately say that there is no such sequence. On one hand, if $T > c_i\ \forall i\leq N$, it is obvious we cannot do so; on the other hand, if such a sequence exists, then $d = \gcd{(c_1,\cdots, c_N)} | T$, i.e., if $T$ is not a multiple of the greatest common divisor $d$ of all the capacities, then the answer is no.

I don't find it obvious that $T$ being a multiple of $d$ is sufficient to prove the existence of a solving sequence, even though we can find integer coefficients $a_i$ such that $a_1c_1 + \cdots + a_Nc_N = T$ (which would hint on the sequence to be used) it feels like we could not be able to juggle the water in the buckets to hold the intermediate steps needed to get to the final solution.

Even so, it was with astounding ease that I created a script to solve this problem, presenting the sequence of moves when there is a solving sequence and indicating that there is no such sequence when there is no solution. By "astounding ease" I mean that I was expecting it to be fairly difficult, but turned out not to be that complicated. I challenge you to try it for yourself. _Really_.

Say we have $N$ ordered buckets. We can represent how much each bucket is filled by a tuple $(w_1, \cdots, w_N)$. We can now think of all the tuples of length $N$ that could represent a plausible state; that is, we can think of $V = \{(w_1,\cdots, w_N) \in \mathbb{N}_0^N | w_i \leq c_i \}$ as the set of vertices of a directed graph, and then have an edge from $a = (a_1, \cdots, a_N)$ to $b = (b_1, \cdots, b_N)$ if we can get from the state $a$ to the state $b$ by either completely filling one of the buckets with the water source, by pouring one bucket $i$ into bucket $j$ or by emptying one of the buckets.

If we think of our original problem in these terms, our original question becomes:

_Is there a path in the graph described above from $(0, \cdots, 0)$ to a vertex having at least one coordinate with value $T$?_

Answering this question is rather easy and can be done by resorting to the well-known [breadth-first search algorithm](https://en.wikipedia.org/wiki/Breadth-first_search). A good thing about this particular application of the algorithm is that we can build the graph just as we need it, potentially reducing memory usage and increasing the speed of the algorithm a bit. Using breadth-first search also guarantees that the solution the script finds is the shortest one, in terms of bucket moves.

The code can be found [on GitHub](https://github.com/RojerGS/projects/blob/master/misc/bucketSolver.py) and can be [ran online on repl.it](https://repl.it/@RojerGS/PoisedRepentantIndianjackal). Alternatively you can try it in the widget below, but I believe it will be fairly slower than running the script directly in repl.it or on your machine. At the top of the script you have two variables, `T` and `buckets`, that can be changed to change the problem configuration. `T` is the target value and `buckets` is a list of capacities.

<iframe allowfullscreen="true" allowtransparency="true" frameborder="no" height="400px" sandbox="allow-forms allow-pointer-lock allow-popups allow-same-origin allow-scripts allow-modals" scrolling="no" src="https://repl.it/@RojerGS/WaterBuckets?lite=true" width="100%"></iframe>

Can you come up with a value for `T` and a list for `buckets` that is possible to solve but is really hard to find?

Let me know your thoughts in the comment section below!
