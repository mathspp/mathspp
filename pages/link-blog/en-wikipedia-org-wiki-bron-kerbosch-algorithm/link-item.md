---
title: Bron-Kerbosch algorithm
link: https://en.wikipedia.org/wiki/Bron–Kerbosch_algorithm
date: 24-12-2024 17:39
via: https://bsky.app/profile/bbelderbos.bsky.social/post/3ldxlssm5j22h
taxonomy:
    category: link
---

This year I've been solving [Advent of Code](https://adventofcode.com) consistently and for day 23 I had to implement a small algorithm that finds clicks in a graph.
My recursive function isn't particularly clever but stands at 5 lines of code:

```py
def find_clicks(click, nodes, edges):
    for idx, node in enumerate(nodes):
        if click <= edges[node]:
            yield from find_clicks(click | {node}, nodes[idx + 1 :], edges)

    yield click
```

`nodes` is a list of all the vertices in the graph and `edges` is a mapping from vertex to set of neighoubrs.
Initially, call it with `find_clicks(set(), nodes, edges)`.
The generator yields sub-maximal clicks but this was good enough for my purposes.
I was pleasantly surprised (but not too surprised) to find later that there is an algorithm that finds maximal clicks in a graph.
