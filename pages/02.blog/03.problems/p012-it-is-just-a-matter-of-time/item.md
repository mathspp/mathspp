How often do the hands of a clock overlap?

===

Today's problem is really easy to state and doesn't require advanced techniques to be solved.
After you know the solution, you can reverse the question and it will sound like a very intriguing riddle you can ask your friends.


## Problem statement

Assume that the minute and hour hands of your clock are overlapping.
How long will it take for that to happen again?


## Solution

It is quite easy to come up with the solution for the problem if we think the right way.
In fact, this can be solved with equations involving angles but we won't follow that path.

It all boils down to realising that in a 12h period the hands of a clock will overlap 11 times, which means they overlap roughly every 1 hour, 5 minutes, and 27 seconds.

Notice that at 00h00 the minute and hour hands are overlapping.
At 12h00 the hands will be overlapping again at the exact same spot, and by that time the minute hand will have given 12 turns while the hour hand will have given a single turn.
When the time starts ticking at 00h00, to 00h01, 00h02, 00h03, ... the minute hand will start in front of the hour hand and until 01h00 we know, for sure, that the two hands won't overlap!

We just have to notice that the two hands will certainly overlap at 01h??, 02h??, etc, 10h??.
This makes up for a total of 10 overlaps.

Now we notice that the two hands can't overlap at 11h??.
If they did, they would overlap at 11 hours and $X$ minutes, and then again at 12h00.
Between the two events there would be a time interval of $60-X$ minutes, which would be less than one hour; but the minute hand takes more than 1h to meet the hour hand again, so that is impossible.
Thus the minute and hour hands do not overlap at 11h??.

(If the two hands of a clock are overlapping, then an hour later the minute hand will return to its position but the hour hand will have moved forward a bit, so that the interval between overlaps is greater than 60 minutes.)

In the end they finish on top of one another, at 12h00.
Thus, over the course of 12h they overlapped exactly 11 times, meaning we only have to perform the division $12h/11 = 1.09090909\cdots h$ which equals 1 hour and $9/99 \times 60$ minutes, which is $9/99\times60 = 5,454545\cdots$ minutes, which gives roughly 1 hour, 5 minutes and 27 seconds.

Reversing the question, one can ask _"what happens every hour, five minutes and twenty-seven seconds?"_, which sounds like a very interesting riddle!


## Bonus question

What's the answer for the same question, but with the hours/seconds or minutes/seconds hands:
