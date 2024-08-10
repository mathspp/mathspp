Can you find the centre of the circle with just five lines?

===

![](thumbnail.webp "Photo by Luis Eusebio on Unsplash.")


# Problem statement

Suppose you have a circle, like the one in the figure below.
At your disposal, you have a compass, a straightedge
(like a ruler, but without length ticks),
and a pencil.

![A black circle on a white background.](_circle.webp "A circle.")

Can you find the centre of the circle with just five lines?
(Every time you use the compass counts as one line,
and every time you use the straightedge counts as another line.)

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - Dmitry R., USA;
 - Martin J., Czech Republic;
 - David H., Taiwan;
 - Mario R., Ecuador;
 - Paul M., USA;
 - Luis C., Peru;
 - Pietro P., Italy;

Know how to solve this?
Join the list of solvers by [emailing me][email] your solution!


# Solution

There are many ways in which the centre of the circle can be found!
However, doing that with just 5 lines is the challenge.


## Deductive reasoning

Recall that the centre of the circle is the point that is at the same distance of all the points in the circumference.
So, if you draw _any_ chord and then draw its bisector, you know that bisector will go through the centre of the circle (point A in the figure):

![A circle with an arbitrary chord with endpoints D and E. Auxiliary circles were drawn from D to E and from E to D, and their intersections defined the bisector of the chord. The bisector goes through the centre of the original circle.](_chord_bisector.webp "The bisector of a chord goes through the circle centre.")

In the figure above, I picked two arbitrary points D and E and drew the chord [DE].
Then, I used D and E two draw to circles:

 - one centred at D with radius equal to the length of [DE]; and
 - another centred at E with radius equal to the length of [DE].

Then, the line defined by the two intersections of those two circles goes through the centre (A).
If we do that once more, the intersections of those two bisectors give you the centre:

![Same process repeated on a second chord, whose bisector intersected with the first one at the centre of the original circle.](_two_chord_bisectors.webp "The intersection of the two chords defines the centre.")

However, this uses a total of 8 lines.
We want to do this in just 5...
And yet, going down to 6 lines is easy:
we just need to realise we don't really care about the chords, only their endpoints...
And picking arbitrary points on the circumference doesn't cost any “lines”:

![Same drawing, but with the chords erased.](_six_moves.webp "4 circles and 2 lines make up a total of 6 lines.")

The final step comes from realising that we don't need 4 separate circles!
The two bisector lines of the implied chords can be drawn with just 3 circles if we pick the points well enough!

After drawing the first two auxiliary circles, pick one of the circles.
That circle will intersect the original circle at a point that you haven't used yet (H in the figure below).
Use that point as the centre of the third circle, which you can draw with a radius equal to the other two auxiliary circles:

![Third circle drawn with centre equal to one of the intersections of the second auxiliary circle with the original circle.](_three_circles.webp)

By making use of those 3 circles we can draw 2 bisectors which intersect at the centre of the circle.
That makes up for a total of 5 lines.


## Animated solution

Here is a GIF of the solution:

![A GIF of the process described, done from scratch.](_circle_centre.gif "Animation of the optimal process to determine the centre of a circle.")


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe
