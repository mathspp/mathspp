---
metadata:
    description: This blog post contains a problem related to the riddle of the water
        buckets and a setting in which it is impossible to solve.
title: 'Problem #004 - solvability of the water buckets'
---

In [this post][wbr post] I talked about the riddle of the water buckets. Now I challenge you to prove that in some situations it is _impossible_ to solve it!

===

![A grayscale image with 3 buckets](buckets.jpg "Photo by Nils Schirmer on Unsplash")

### Problem statement

You have $n$ buckets, each bucket with capacity for $c_i$ litres of water, $i = 1, \cdots, n$. You want to manipulate the buckets in such a way that one of them holds exactly $t$ litres of water, knowing that the only moves you can do are:

 - completely fill up bucket $i$ so that it holds $c_i$ litres of water;
 - completely empty bucket $i$ so that it now holds $0$ litres of water;
 - move water from bucket $i$ to bucket $j$, until bucket $i$ becomes empty or bucket $j$ becomes full, whatever happens first.

Prove that, if $t$ is not a multiple of the greatest common divisor of the $c_i$, $i = 1, \cdots, n$ then it is impossible for a single bucket to hold exactly $t$ litres of water.

For example, if the buckets have capacities $4$ and $6$ and $t = 3$, then you can't perform the moves above to get exactly $3$ litres of water into one of the two buckets as the greatest common divisor of $4$ and $6$ is $\gcd(4, 6) = 2$ and $3$ is _not_ a multiple of $2$.

!!! Give it some thought... and give it an actual shot! Take out a piece of paper and a pencil and get your brain working!

If you need any clarification whatsoever, feel free to ask in the comment section below.

### Solution

You can read the solution [here][sol] to compare with your own solution.

[sol]: ../../solutions/{{ page.slug }}
[wbr post]: ../../water-buckets
