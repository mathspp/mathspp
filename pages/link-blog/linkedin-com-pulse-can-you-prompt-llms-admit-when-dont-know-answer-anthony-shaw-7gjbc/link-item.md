---
title: Can you prompt LLMs to admit when they don't know the answer?
link: https://www.linkedin.com/pulse/can-you-prompt-llms-admit-when-dont-know-answer-anthony-shaw-7gjbc/
via: https://bsky.app/profile/anthonypjshaw.bsky.social/post/3lf2hg4v6tk2q
date: 06-01-2025 09:11
---

In this piece, Anthony Shaw talks about his attempts to make LLMs recognise their inability to answer certain questions and how, by default, LLMs will output a response that might look helpful even if it is complete gargabe.

The main example given was that of a [game of Set](https://en.wikipedia.org/wiki/Set_(card_game)) where Anthony and his kid needed help determining whether there was a match or not, given a picture of the game.
The LLMs prompted were very keen on creating garbage output, with most (if not all) mentioning cards that weren't even in play in the photo shown.

In the end, Anthony did manage to get better outputs by instructing LLMs to reply with a shrugging emoji in case they could not compute the answer reliably.
(There is also a bonus snippet of Python code in the end that looks for matches programmatically. Maybe prompting the LLMs to write such piece of code would've been easier and more reliable.)
