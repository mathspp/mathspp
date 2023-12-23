---
title: "Problem #044 – send more money"
metadata:
    description: "Can you solve this simple-looking arithmetic challenge?"
---

Can you solve this simple-looking arithmetic challenge?

===

![A piece of paper where one can read "SEND + MORE = MONEY".](thumbnail.png)

# Problem statement

The image above represents an arithmetic calculation:

SEND + MORE = MONEY

Each letter represents a unique digit,
and each word represents a number with as many digits as letters
(there are no leading 0s).

Can you figure out the numeric value of each word?

!!! Give it some thought!

If you need any clarification whatsoever, feel free to ask in the comment section below.


# Solvers

Congratulations to the ones that solved this problem correctly and, in particular, to the ones
who sent me their correct solutions:

 - [Giorgio N.](https://twitter.com/qJakc0), Italy;
 - [B. Praveen R.](https://twitter.com/BPrvn_Rj), India;
 - [Nishant M.](https://twitter.com/sci_c0), India;
 - David H., Taiwan;
 - [Matthias W.](https://twitter.com/m2u_84), Germany;

Join the list of solvers by [emailing me][email] your solution!


# Solution

SEND and MORE are two 4-digit numbers that are both 9999 or less.
That means that their sum is 19998 or less.
Because the result is MONEY, with 5 digits, we immediately conclude that the leading M in MONEY is 1, from which we get this:

```
  S E N D
+ 1 O R E
---------
1 O N E Y
```

Now, we can look at the S + 1 on the left.
The result of S + 1 must be 10 or more (because there must be a 1 to carry to the left) and there may be a carry coming from the hundreds.
Let us consider what happens if we assume there (or there isn't) carry.

If there is carry, then O = S + 1 + 1 = S + 2.
O can't be 1 (because 1 is already taken) so O must 0, because for O to be 2 or greater, S would have to be greater than or equal to 10.
So, if there is carry, O = 0 and S = 8.

If there is no carry, then O = S + 1.
O can't be 1 (because 1 is already taken) so O must be 0, because for O to be 2 or greater, S would have to be greater than or equal to 11.
So, if there is no carry, O = 0 and S = 9.

Regardless of whether there is carry or not, we concluded that O = 0, so we're at this point:

```
  S E N D
+ 1 0 R E
---------
1 0 N E Y
```

If we look at the hundreds place, we see E + 0 = N.
This is only possible if there is carry coming from the tens place, which means that N + R must be greater than 10.
But, if that is the case, then E is, at most, an 8 (which would happen if N and R were 9 and 8, and there was carry coming from the units place).
If E is at most an 8, then in the hundreds place the calculation E + 0 + 1 (the carry) is less than 10, which means that there is no carry in the hundreds place, from which we conclude that S is 9:

```
  9 E N D
+ 1 0 R E
---------
1 0 N E Y
```

Looking at E + 0 = N in the hundreds place, this means that N is the digit after E.
On the other hand, in the tens place, we see that N plus R plus a possible carry goes all the way around to E.
This would only be possible if R and the carry added up to 9.
Now, 9 is already taken, so it _must_ be that R = 8 and that D + E is greater than or equal to 10.

So, replacing R:

```
  9 E N D
+ 1 0 8 E
---------
1 0 N E Y
```

The digits we have left are 2, 3, 4, 5, 6, and 7.
D + E must be 10 or greater, so we know we won't be using the 2.
At the same time, the units digit of D + E is Y, which must also be one of 2, 3, 4, 5, 6, and 7.
This means that D + E is either 13 (if we pick 6 and 7) or 12 (if we pick 5 and 7), but either way the 7 is used.
E can't be the 7 because N is E + 1 and 8 was already used, so D must be 7.
But this also means that E can't be 6, because N is E + 1 and 7 was already used, so E is 5.
From this, we also conclude that N = 6 and Y = 2:

```
  9 5 6 7
+ 1 0 8 5
---------
1 0 6 5 2
```



[Don't forget to subscribe to the newsletter][subscribe] to get bi-weekly
problems sent straight to your inbox.

[email]: mailto:rodrigo@mathspp.com?subject=Solution%20to%20{{ page.title|regex_replace(['/ /'], ['%20']) }}
[subscribe]: /subscribe
