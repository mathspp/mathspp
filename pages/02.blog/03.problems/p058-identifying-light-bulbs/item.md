Please help me identify these 100 light bulbs by turning ON and OFF their switches.

===

![](thumbnail.png "Photo by Juan Carlos Becerra on Unsplash.")


## Problem statement

I have a very peculiar room in my house.
It's a simple room that doesn't have much decoration.
However, I do have 100 light bulbs hanging from the ceiling because I thought it would look cool.
When I installed the 100 light bulbs I wanted maximum freedom,
so I also installed 100 independent switches:

 - each switch controls exactly one light bulb; and
 - each light bulb is controlled by exactly one switch.

Of course I was completely silly, so I installed the switches in a room that is far from the room with the light bulbs **and** I completely forgot which light switch controls which light bulb.
How can I identify which switch controls which light bulb in the _least amount of trips_ possible?

For example, I could flip ON a switch and then go verify which light bulb turned ON,
and I could do this for the 100 light bulbs...
But that would take me 100 trips.

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.

This problem was adapted from [here][source] and is licensed under [CC BY-SA 3.0][cc-by-sa-3].


## Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - David H., Taiwan;
 - Shubham S., India;
 - Dan, USA;
 - Jeena K., India;
 - Frank X., Shenzhen, China;
 - Wolfgang, Germany;
 - Naveen K., India;
 - Pedro G., Portugal;
 - Dylan S., USA;
 - Vladimir L., USA;
 - Sean L., USA;

Know how to solve this?
Join the list of solvers by [emailing me][email] your solution!


## Solution

We can devise a strategy that identifies $2^n$ light bulbs in $n$ trips making use of binary.

In short, what we do is number each switch in binary.
Suppose the longest binary number has $k$ digits.
Then, we do $k$ trips and for each trip $t$ we turn ON the switches whose binary expansions have a $1$ in the corresponding position.
After the $k$ trips, the times at which each light bulb was on will correspond to the binary expansion of its switch.

As an example, suppose we have 4 light bulbs and 4 switches.
We number the four switches in binary:

 1. $00$
 2. $01$
 3. $10$
 4. $11$

This means that for the first trip we only turn ON switches 2 and 4, and for the second trip we only turn ON the switches 3 and 4.
As two example associations, the light bulb associated with the switch 4 is the light bulb that was turned ON both times and the light bulb associated with the switch 1 is the light bulb that was turned OFF both times.

For 100 light bulbs, the largest binary number will be $1100011$ (for 99), which means we'll need 7 trips.


[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe

[source]: https://puzzling.stackexchange.com/q/20447/41687
[cc-by-sa-3]: https://creativecommons.org/licenses/by-sa/3.0/
