---
title: "Neural networks fundamentals"
---

Learn how neural networks work and implement one from scratch.

===

![](thumbnail.png "Photo by JJ Ying on Unsplash.")


# Purpose

This course is aimed at getting participants to better understand how neural networks work.

Nowadays, it is easy to access neural networks and to use them in our projects,
as many programming languages have well-established packages/libraries/plugins that
interface with these machine learning models.
A side-effect of the democratisation of access to machine learning models is that
people do not have to *know* how they work in order to use them.
This need not be a bad thing, but if you really want to understand the inner workings
of neural networks, then building one yourself will do more for you than
using some framework that someone else built.


# Pre-requisites

This course can be given in three sessions of 90 minutes,
provided the participants have solid
programming experience
(in any one programming language – not necessarily the language
of the course) and a very basic overview of what neural networks are.

The course further assumes the participants have had exposure to
matrix algebra and to the concept of derivatives.

These restrictions on prior knowledge can be lifted at the expense of
making the course longer.


# Programming language

The course can be given in any programming language that is decided upon
in advance.
I can recommend doing this course in Python or in APL, two very good
choices for the type of programming we will be doing.


# Curriculum

The core curriculum of this course is the following:

 1. the concept of neuron, layer and network;
 2. activation functions and the forward pass;
 3. network performance and loss functions;
 4. network training and backpropagation;
 5. handwritten digit recognition.

I have written a series of blog posts that mimics the curriculum
of the course, so you can prepare for the sessions (or jog your memory
on what you learned).
The blog posts also provide a sample implementation in Python.
Bullet points 1. and 2. are covered by the [“Intro”][nnfwp-intro], 3.
is covered in [“Network & loss”][nnfwp-network-loss], 4.
is covered in [“Backpropagation”][nnfwp-backprop], and 5.
is covered in [“MNIST”][nnfwp-mnist].

The curriculum of the course can be further extended in many different
directions, like a survey of more activation and loss functions
and their use cases or the student-teacher experiment.


# Feedback from attendees

Here are a couple of nice things that attendees from previous sessions said:


 > “[...] It was really interesting seeing things in action.
 > Also, the environment was super comfortable.” ― Laura F.

<!---->

 > “[The best things about the course were] my deepening of the knowledge about neural networks.
 > What they do and the math and structure behind them.” ― Anonymous.

<!---->

 > “[...] The dynamics and empathy created during the course were just excellent.” ― Gonçalo R.


If you want me to teach this course to a group of people you represent, feel free to [email me][mailme].


# Useful links

 - Blog post series that mimics the course's curriculum:
   - [“Intro”][nnfwp-intro]
   - [“Network & loss”][nnfwp-network-loss]
   - [“Backpropagation”][nnfwp-backprop]
   - [“MNIST”][nnfwp-mnist].
 - 3blue1brown's amazing YouTube short series on neural networks:
[https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi](https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi)


[nnfwp-intro]: /blog/neural-networks-fundamentals-with-python-intro
[nnfwp-network-loss]: /blog/neural-networks-fundamentals-with-python-network-loss
[nnfwp-backprop]: /blog/neural-networks-fundamentals-with-python-backpropagation
[nnfwp-mnist]: /blog/neural-networks-fundamentals-with-python-mnist
[3b1b-series]: https://www.youtube.com/playlist?list=PLZHQObOWTQDNU6R1_67000Dx_ZCJB-3pi
[mailme]: mailto:rodrigo@mathspp.com
