---
title: "Problem #040 – the dozen puzzle"
metadata:
    description: "Three friends are assigned three different numbers that add up to a dozen. Can you figure out who thought of what?"
---

Three friends are given three different numbers that add up to a dozen.
Can you figure out everyone's numbers?


===

![](thumbnail.png "Photo by Kelly Neil on Unsplash")

# Problem statement

Three friends, Alice, Bob, and Charlie, are assigned three different
positive whole numbers by their fourth friend, Diane.
Furthermore, Diane told them that their three numbers add up to 12
and that Charlie's is the largest one.

Diane then asks the three of them if they know everyone's numbers,
to which Bob replies “I do!”, whereas Alice and Charlie remain silent.
After Bob's revelation, Alice and Charlie think for a couple of seconds
and confirm that now they also know everyone's numbers.

What were Alice's, Bob's, and Charlie's numbers?


!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - Ashok M., India;
 - David H., Taiwan;
 - Attila K., Hungary;
 - Jason P., US;
 - “Starsmer”, US;

Join the list of solvers by [emailing me][email] your solution!


# Solution

For Bob to know Alice's and Charlie's numbers,
Bob's number must be big enough so that Charlie's number doesn't have much wiggle room.

If Bob had the number 6, then Charlie would have at least 7 and their two numbers would add up to 13,
which is already above 12.
However, if Bob's number is 5, then Bob knows that Charlie can only have a 6
(if Charlie had 7 or more, then Bob and Charlie alone would add up to 12 or more)
and hence Alice has to have a 1.

Thus, we conclude that Bob can guess everyone's numbers if Bob is given the 5.

From Alice's point of view, holding a 1 doesn't give her enough information
to deduce what Bob and Charlie have, because they could have 2 and 9, for example,
or 3 and 8.

From Charlie's point of view, holding a 6 doesn't give him enough information
to deduce what Alice and Bob have, because they could have 1 and 5, or 2 and 4,
for example, not to mention that Charlie wouldn't know who has the largest number.

After Bob announces he knows everyone's numbers,
the other two can reverse engineer this reasoning and discover the missing numbers as well.

An alternative approach would be to list all possible number assignments
and then look for the assignment that attributes a unique number to Bob.
In other words, we can go through the numbers 1 to 12 and ask:
“If Bob had this number, in how many different ways could I attribute
numbers to Alice and Charlie?”.


This problem was taken from [this Reddit post][source],
and shared with permission.

<!-- v -->
[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox and to add your reaction below.
<!-- ^ -->


[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: https://mathspp.com/subscribe
[source]: https://www.reddit.com/r/puzzles/comments/o62ddq/dozen_total_puzzle/
