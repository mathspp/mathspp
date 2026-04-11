---
date: 08-04-2026
event: "PyCon Lithuania 2026"
event_link: https://pycon.lt/2026/talks/TXCRFM
# main_reference: xxx
resources: https://github.com/mathspp/talks/tree/main/20260408_pycon_lithuania_the-paradox-of-itertools-tee
slides_pdf: https://github.com/mathspp/talks/blob/main/20260408_pycon_lithuania_the-paradox-of-itertools-tee/slides.pdf
taxonomy:
    category: talks
    tags:
        - "event name"
        - "country / remote"
        - "year"
title: "The paradox of itertools.tee"
# watch: xxx
# youtube_embed: xxx
---

## Abstract

The module `itertools` provides 20 tools.
There's 19 iterables and then there's `tee`...

But what does `tee` do and why is it the only thing in the module `itertools` that's not an iterable?

In this talk you will understand what `tee` does and when to use it, but most importantly, you will understand the paradox behind `tee`...
See, the thing is that `tee` seems to go against the laws of iterators...

## Outline

 - **Iterables vs iterators** (5'): a brief overview of the difference between iterables and iterators, as well as showing how iterators can only be traversed once.
 - **What does tee do?** (10'): this section will show what tee does, including a live-coded demonstration through two examples; one that is purely mechanical to show that tee splits the given iterator into n iterators and one that is closer to a real-world usage of tee, using tee to implement pairwise.
 - **The solution to the paradox** (10'): if iterators can only be traversed once, how is it that tee allows you to create n copies of the given iterator? This section will provide a possible solution to the paradox by live coding a pure-Python implementation of tee that is fully working and that can be tested against the two examples of the previous section.

## Talk notebook

The notebook that was live-coded during the session can be [downloaded from GitHub](https://github.com/mathspp/talks/tree/main/20260408_pycon_lithuania_the-paradox-of-itertools-tee).
