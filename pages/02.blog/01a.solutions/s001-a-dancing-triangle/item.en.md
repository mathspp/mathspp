---
title: 'Solution #001 - a dancing triangle'
---

This post contains my proposed solution to [Problem #001 - a dancing triangle][prob]. Please do not read this solution before making a serious attempt [at the problem][prob].

===

### Solution

The answer to the question is _no_, one cannot apply the transformation a given number of times and get to a triangle with sides that are twice the original size. To see why, we will show that the area of the triangle remains unchanged when you apply the transformation. If we do that and if we realize that doubling up the lengths of the sides makes the area four times bigger, then we conclude that we cannot create the triangle we wished.

Say we have a triangle $[ABC]$ and, without loss of generality, we are going to apply the transformation to $C$. Furthermore, we can assume $[AB]$ is horizontal (this is not needed, just makes it even simpler to visualize the proof). We know that $C$ is going to be moved to a point $C'$ in a line that is parallel to $[AB]$. We know that the area of a triangle is $\frac{b \times h}{2}$ where $b$ is the length of the base and $h$ is the height. If $[AB]$ is seen as the base (its length doesn't change when $C$ moves to $C'$) we see that the height $h$ is the length of the line segment that goes from $AB$ to $C$ and is perpendicular to $AB$. But the line segment that goes from $C'$ to $AB$ and is perpendicular to it has exactly the same length, so the area of the triangle didn't change. QED.

[prob]: ../../problems/{{ page.slug }}
