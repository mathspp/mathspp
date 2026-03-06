---
date: 12-02-2026
event: "Python Users Berlin"
event_link: https://www.meetup.com/python-users-berlin-pub/events/312711011/
resources: https://github.com/mathspp/talks/tree/main/20260212_pub_the-paradox-of-itertools-tee
slides_pdf: https://github.com/mathspp/talks/blob/main/20260212_pub_the-paradox-of-itertools-tee/slides.pdf
taxonomy:
    category: talks
    tags:
        - "Python Users Berlin"
        - "Germany"
        - "remote"
        - "2026"
title: "The paradox of `itertools.tee`"
watch: https://youtu.be/7tYGPC-yzjE
youtube_embed: https://www.youtube.com/embed/7tYGPC-yzjE
---

## Abstract

The module `itertools` provides 20 tools.
There's 19 iterables and then there’s `tee`...

But what does tee do and why is it the only thing in the module `itertools` that's not an iterable?
In this talk you will understand what `tee` does and when to use it, but most importantly, you will understand the paradox behind `tee`...

See, the thing is that `tee` seems to go against the laws of generators…
