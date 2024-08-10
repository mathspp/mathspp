When will these two clocks synchronise again?

===

# Problem statement

You have two digital clocks.
One of them just displays the hours and minutes in the format HH:MM while the other is a stopwatch that displays minutes and seconds in the format MM:SS.
The stopwatch wraps around at 59:59, when it goes back to 00:00.

On a certain day at 00:00, the stopwatch is turned on.
When will the clock and the stopwatch have matching displays again?
And how often does that happen?

!!! Give it some thought!


# Solution

The two clocks will have matching displays again at 01:01:01, then again at 02:02:02, 03:03:03, 04:04:04, ..., 23:23:23, so this happens 24 times per day.

To get to this conclusion, we start by realising that the stopwatch can actually be seen as a clock that shows the current time, but without the hours.
Thus, the minutes display of the regular clock will always match the minutes display of the stopwatch.

To recap, the two clocks have displays in the format HH:MM and MM:SS and we already know that the MM sections always match.
This means that for the two displays to display the same thing, we must have HH = MM = SS, which happens at 00:00:00, 01:01:01, ..., up to 23:23:23.


## Bonus question

What if the stopwatch wraps around at 99:59 instead of 59:59?
