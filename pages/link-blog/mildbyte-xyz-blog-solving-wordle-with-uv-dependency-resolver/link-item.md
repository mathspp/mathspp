---
# author:
date: 08-07-2025 12:01
link: https://mildbyte.xyz/blog/solving-wordle-with-uv-dependency-resolver
taxonomy:
    category: link
title: "Solving Wordle with uv's dependency resolver"
# via:
---

In this article, the author solves Wordle by encoding the game information as Python package versions and then using uv's dependency resolver figure that out.

The very summarised gist of it is that different packages represent different types of information and then their versions encode the letters that go where.
Then, the author created all the necessary fake packages locally (around 5,000 of them) and then got the dependency resolver to play the game by trying to resolve the dependencies of these fake packages.
