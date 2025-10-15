---
# author:
date: 15-10-2025 13:47
link: https://www.tiktok.com/@ashmarlow52/video/7555537742512426263
taxonomy:
    category: link
title: "Mathematical magic trick with cards"
# via:
---

My brother sent me [this video of a nice magic card trick](https://www.tiktok.com/@ashmarlow52/video/7555537742512426263) and asked me to figure out the maths behind it.

If you can't/don't want to watch the video, it works like this:

 1. Shuffle a regular pack of 52 cards (no jokers).
 2. Reveal top 2 cards.
 3. For each revealed card, if the card is worth $n$, discard $10 - n$ cards. (E.g., if it's an 8, discard 2 cards). Figures are worth 10 and the ace is worth 1.
 4. Add the values of the two revealed cards and show the card at that position in the deck to the audience.
 5. Put the 2 revealed cards and the discarded cards at the top of the deck.
 6. Deal cards, alternating between discarding (first) and keeping them to yourself (second).
 7. Pick up the cards you dealt to yourself and repeat the step above.
 8. When you only have a single card left, it's the card you showed to the audience.

Here's how it works.

Steps 6 through 8 are deterministic and you always play them out in the same way, so they will always reveal the card that is in the 22nd position of the deck, regardless of whatever happened in steps 1 through 5.
Here's a simulation in Python:

```py
deck = list(range(1, 53))

while len(deck) > 1:
    deck = list(reversed(deck[1::2]))

print(deck)  # [22]
```

So, it's just a matter of understanding that steps 1 through 5 always make you look at the 22nd card.

Suppose the cards shown have the values $a$ and $b$.
On top of the card that is worth $a$, you discard $10 - a$ cards.
On top of the card that is worth $b$, you discard $10 - b$ cards.
Finally, you look at the card at position $a + b$.

But when you put everything back on top, the card that was in position $a + b$ is now under some extra cards:

 - the two cards that had been revealed;
 - the $10 - a$ cards that were discarded on the first card; and
 - the $10 - b$ cards that were discarded on the second card.

Adding those up gives $22 - a - b$ cards.
So, the card that was in position $a + b$ of the partial deck is in position $(22 - a - b) + (a + b)$ of the full deck, which simplifies to 22.
So, you're always looking at the 22nd card.

Try it for yourself.
Grab the ace of spades and make sure it's the 22nd card counting from the top of the deck.
Play these steps out and you'll always find the ace of spades.
