---
title: How to draw a fractal with Python ⚡️
event: "PyCon Ireland 2023"
image_align: right
---

In this lightning talk my only objective was to show how easy it is to create stunning images with a little bit of Python and a little bit of maths.

With some live-coding, some terrible jokes, and a couple of typos, everyone enjoyed watching two nested loops and the innocent function that you see below turn into the colourful image depicted here.

```py
def f(z, c):
    return z**2 + c
```

===

![](_colours.webp "A depiction of the Mandelbrot set.")

===

[PyCon Ireland 2023](http://pycon.ie/pycon-2023/schedule/), 11-11-2023 • [Main reference](/blog/mandelbrot-set) • [Source code](https://github.com/mathspp/talks/blob/main/20231111_pycon_ireland_fractals/mandelbrot.py)
