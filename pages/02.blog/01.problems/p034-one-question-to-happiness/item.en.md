---
title: "Problem #034 – one question to happiness"
metadata:
    description: "A logic riddle: two doors, one gives you eternal happiness and the other eternal sadness."
---

Two doors, one gives you eternal happiness and the other eternal sadness.
How can you pick the correct one?

===

![](thumbnail.png "Photo by Hans Eiskonen on Unsplash.")


# Problem statement

Today's problem is a classical one.
If you solve lots of logic puzzles and riddles then it is likely
that you already know this one, but here it goes for the others that do not know it yet:

Two identical doors are in front of you.
One of them is between you and eternal happiness, whereas the other one will give you eternal sadness if you cross it.

Furthermore, there are two people close by, and they know which door is which.
You are allowed to ask one of those persons a single question.
However, one of the persons always lies and the other person always tells the truth.

What question should you ask in order to determine the correct door?

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - Jeff W., USA;
 - António A., Portugal.

(The list is in no particular order.)


# Solution

I am yet to meet someone who can walk me through their thought process
while they solve this for the first time,
so I will have a hard time trying to show you how you could have gotten
to the solution by yourself if you haven't yet.
This is one of those type of problems where it is just easier to
tell the solution and then verify that it works.

Because you know one person always lies but the other person always tells
the truth, you have to figure out a way to have your question ran by
both of them at the same time, even though you can only direct your question
at one of them.
Having said that, you can ask a hypothetical question to person A,
saying something along the lines “If I had asked person B ...”.
This is the key.

What you should ask to either person is

 > “If I had asked which door would bring me eternal happiness
 to the other person, what door would that person have pointed to?”

The person you directed your question at will point to a door,
and you should then use the other one.
Let me break this down for you.

For the sake of the explanation, suppose the door on the left
brings eternal happiness and the person on the left is the liar.

 - If you direct your question to the liar, their train of thought
 will be along the lines of “the truth-teller will definitely point
 to the door on the left, so I should point to the one on the right”,
 therefore the liar lies about the honest person's hypothetical answer
 and points to the door on the right.
  
 - If you direct your question to the honest person, their train of thought
 will be along the lines of “the liar will definitely point to the
 door on the right, so that is what I should point to”,
 therefore the honest person honestly tells you about the liar's lie and
 points to the door on the right.

Either way, you have to use the left door, i.e., you use the door opposite
to the one that is pointed to.
And this is how you could solve it.

I have been told that there is a very interesting solution to this problem
that asks a fundamentally different question,
but for the life of me I can't figure any other solutions out,
so if you _do_ know a different solution, feel free to comment it down in the comments!


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox and to add your reaction below.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[sol]: ../../solutions/{{ page.slug }}
