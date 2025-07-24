---
author: Will McGugan
date: 24-07-2025 11:11
link: https://willmcgugan.github.io/streaming-markdown/
taxonomy:
    category: link
title: "Efficient streaming of Markdown in the terminal"
# via:
---

In this article, Will writes about how he implemented efficient Markdown streaming for [his project Toad](https://willmcgugan.github.io/announcing-toad/).

The highlight of this article, for me, is the fact that Will managed to implement efficient rendering of streamed Markdown content even though he's using a markdown parser that does not support streaming.
His solution was simple in hindsight: Markdown can be parsed in blocks, and when you get content that starts a new block, you know you won't have to reparse the previous content.
