---
author: Annie Versary
date: 21-01-2025 10:45
link: https://versary.town/blog/using-linear-programming-to-find-optimal-builds-in-league-of-legends/
taxonomy:
    category: link
title: "using Linear Programming to find optimal builds in league of legends - annieversary"
# via:
---

In this post, the author uses linear programming to solve an optimisation problem in League of Legends, a computer game.
This post is a great example of how one can use a problem from “real life” (at least, for those who play the game) to introduce technical topics or concepts.

The problem being solved has to do with the fact that a champion needs to buy items that increase three of their stats so that the champion's abilities evolve.
What is the optimal build to achieve this?
In other words, what are the cheapest items to buy that grant enough of these stats for the evolved abilities?

The author proceeds to use Rust and a linear programming package to compute the solution and then goes on to discuss whether the optimal solution really is optimal, because when buying items you want to consider the various stages of the game, and not just the single moment when your abilities might evolve because of the items you bought.
The author then poses relevant follow-up questions and possible ways of answering those solutions but doesn't answer the questions, leaving them “as an exercise for the reader”.
