This article shows how I made a secret Santa draw with Python.

===


Today I needed to make a Secret Santa draw for my whole family.
There are lots of services online that do this for you, but they require giving those services the emails of my family members who will then be mercilessly spammed by said services...

So I decided to make my own draw with a bit of Python programming.
All I needed was:

 - to be able to input the names of my family members; and
 - to be able to set exclusion groups so that family members don't draw the names of others in their nuclear family (for example, I don't want to be able to draw the name of my own brother).

This article is my account of how I did it, bit by bit, on the REPL.
I'll try to describe briefly what each objective was at each point in time.
You'll see that the code isn't particularly well structured but it shows an accurate depiction of real code I wrote to solve a real-life need I had.


## Specifying the names

The first thing I did was specify the names of everyone that was participating in the draw.
Without giving it too much thought, I figured this looked like a reasonable way of doing it:

```py
names = [
    ["Alfred", "Ana", "Arthur"],
    ["Ben", "Beatrice", "Bianca"],
    ["Charles", "Catherine"],
    ["David"],
    ["Edward", "Elaine", "Eve"],
]
```

The idea is that each sublist represents a nuclear family, so we wouldn't want Alfred to draw Ana's or Arthur's name, but he could draw any other name starting with B, C, D, or E.


## A flat list with all participants

The next step, I figured, was to get a flat list with _all_ of the participants, so that I could easily shuffle them around.

Flattening a list of lists is easy if you know what to do:

```py
from itertools import chain
flat_names = list(chain.from_iterable(names))
print(flat_names)  # ['Alfred', 'Ana', 'Arthur', 'Ben', ...]
```


## Creating a more manageable data structure for exclusions

As I was getting ready to shuffle things around, I figured I'd need a quick and easy way to take a pair of names and figure out if they were in the same exclusion group or not.

To do this, I created a dictionary that maps each single name into its exclusion group.
I used a dictionary comprehension, which is aking to a [list comprehension](/blog/pydonts/list-comprehensions-101), but for dictionaries.

This dictionary adds some redundancy, but it's easier to work with:

```py
exclusions = {name: group for group in names for name in group}
print(exclusions)
"""
{
    'Alfred': ['Alfred', 'Ana', 'Arthur'],
    'Ana': ['Alfred', 'Ana', 'Arthur'],
    'Arthur': ['Alfred', 'Ana', 'Arthur'],
    'Ben': ['Ben', 'Beatrice', 'Bianca'],
    # ...
}
"""
```


## Drawing names

The next step was generating the actual draw.
I didn't feel like thinking too much about it, so I thought I'd go with brute-force, randomising the draw and then repeating the draw until all exclusion requirements were met by coincidence.

To achieve this, I wrote this `for` loop:

```py
import random
for _ in range(1000):
    random.shuffle(flat_names)
    for giver, receiver in zip(flat_names, flat_names[1:] + [flat_names[0]]):
        if receiver in exclusions[giver]:
            break
    else:
        print("Done!")
        print(flat_names)
        break
else:
    print("Failed")
```

It took me 2 attempts, if I recall correctly, but this is the loop I ended up with.
I'm not particularly happy with the way I wrote the inner loop that traverses `zip(flat_names, flat_names[1:] + [flat_names[0]])`.
The idea is that each person will be gifting the person that's next in the list.

So, suppose we run the loop and this is the result we get:

```py
# Done!
['Beatrice', 'Eve', 'Arthur', 'Elaine', 'Ana', 'Charles', 'Alfred', 'Edward', 'Ben', 'Catherine', 'Bianca', 'David']
```

In this case:

 - Beatrice is Eve's secret Santa (Beatrice will be giving a gift to Eve);
 - Eve is Arthur's secret Santa;
 - Arthur is Elaine's secret Santa;
 - ...; and
 - Catherine is Beatrice's secret Santa.


I ran this a couple of times just to make sure the exclusion requirements were being met, and when I was happy with my empirical evidence, I moved on to the next challenge.


## Letting everyone know the result of the draw

The next & final step was to notify everyone of the name they had drawn.
I quickly Googled how to send emails with Python and it looked like I was about to waste a couple of hours just trying to connect Python to my Gmail account, get authentication working, etc.

I was considering it because I didn't want to print the full list of results, otherwise I would learn everyone's secret Santa and that would ruin it for me!

But sending automatic emails wasn't looking like it was going to be simple and fast enough, so, I decided to dumb it down even further.
I recalled there was a Python package called `pyperclip` that allowed you to copy strings into the clipboard.
Leveraging that package, here was my idea:

 - I'll create an email template I'll send everyone;
 - I'll go through the list of names drawn;
 - I'll fill the email template with the result of the draw;
 - I'll copy the email template into my clipboard; and
 - I'll paste the email and send it without reading the contents of what I pasted.

I was very happy with this dumb solution!
If a dumb piece of code works, it's dumb, but at least it works!

This is essentially the code I wrote:

```py
import pyperclip
email_template = "Hey! You'll be giving {secret} a present!"

for giver in flat_names:
    receiver = flat_names[(flat_names.index(giver) + 1) % len(flat_names)]
    contents = pyperclip.copy(email_template.format(secret=receiver))
    print(f"Copied email for {giver}.")
    input()
```

This will go through each result and wait for me to press <kbd>Enter</kbd> before copying the next email into my clipboard.

If you run the loop and press <kbd>Enter</kbd> a couple of times, this is what you see:

```py
Copied email for Beatrice.
''
Copied email for Eve.
''
Copied email for Arthur.
''
Copied email for Elaine.
```

Then, I realised there was a silly issue with the loop above!
(Can you spot it?!)

So I fixed it:

```py
for giver in sorted(flat_names):
    # ...
```

Then, I sent all of my emails!
I felt very happy about all of this when I was done.


## A posteriori improvements

After writing the code, running it, generating the draw, and sending the results to my family, I came up with a couple of improvements I could have made to the code.
The most obvious one would be to implement the draw in a way that's smarter than just brute-force, but I settled for simpler improvements:

 - use sets in the dictionary `exclusions` for faster membership checking; and
 - keep the flat list of names unchanged and instead shuffle a second copy.

Here is the code with those changes implemented:

```py
names = [
    ["Alfred", "Ana", "Arthur"],
    ["Ben", "Beatrice", "Bianca"],
    ["Charles", "Catherine"],
    ["David"],
    ["Edward", "Elaine", "Eve"],
]

from itertools import chain

flat_names = list(chain.from_iterable(names))

# Turn `group` into a set for faster membership checking.
exclusions = {name: set(group) for group in names for name in group}

# Let flat_names be a fixed reference and instead shuffle a copy.
shuffled = flat_names[:]
import random

for _ in range(1000):
    random.shuffle(shuffled)
    # Traverse givers & receivers with `itertools.pairwise`.
    for giver, receiver in zip(flat_names, shuffled):
        if receiver in exclusions[giver]:
            break
    else:
        print("Done!")
        print(flat_names)
        break
else:
    print("Failed")

# Email generation stays the same.
import pyperclip

email_template = "Hey! You'll be giving {secret} a present!"

for giver, receiver in zip(flat_names, shuffled):
    contents = pyperclip.copy(email_template.format(secret=receiver))
    print(f"Copied email for {giver}.")
    input()
```
