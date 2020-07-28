---
title: A gradient descent algorithm to optimally distribute points over a sphere
---

![Animation of the algorithm](./n6s-2.gif)

===

In this first post I want to share with you guys a piece of code I wrote to "solve" a problem where geometry meets optimization. I say "solve" because I didn't actually do anything that fantastic regarding the actual problem I address, but rather developed a small tool to help visualize the geometrical part of the problem. Even so, I do believe that for the smaller cases my tool can solve the problem.

The problem is along the lines of: define an energy function whose value depends on the positions of points in a sphere, and now try to minimize/maximize it (depending on a parameter). That is it. I used my coding skills to write an algorithm that solves this when the number of points is small, and that lets me see the creation of the solution: I create a random distribution of points and then let them adjust themselves to their desired positions, hopefully reaching the desired minimum/maximum.

In [here](https://drive.google.com/file/d/0ByBeLS6ciLYVVTdjTHdVTVF5dWc/view?usp=sharing) you can find the report I wrote for this, in English. The code, some figures and some animations (GIFs like the one up there) can be found [here](https://drive.google.com/file/d/0ByBeLS6ciLYVTjlZRTVRT1NZWGc/view?usp=sharing).