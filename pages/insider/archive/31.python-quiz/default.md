---
date: 20-07-2026 20:52
metadata:
    author: Rodrigo Girão Serrão
    description: "Test your knowledge of the Python language and community with this short Python quiz."
    og:image: "https://mathspp.com/insider/archive/python-quiz/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/python-quiz/thumbnail.webp"
title: "Python quiz"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 Python quiz

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## EuroPython 2026 (quiz)

I spent the last 7 days in Krakow, Poland, at EuroPython 2026.

The largest Python conference in Europe.

Probably the second largest in the world (just after PyCon US).

I gave a tutorial, presented a poster, and helped organise the conference.

One of the things I did was prepare a Python quiz for all the participants to enjoy.

Today, I want to share that quiz with you to see how you do.

For context, the quiz included some questions about the conference itself that I will omit from this newsletter issue.

Send me your answers and I'll send you the solutions.

## GitHub popularity

The number of stars a project has on GitHub is a common measure of popularity of a project.

Consider the following projects:

- CPython
- Django
- FastAPI
- uv

Can you order them from **most** stars to **least** stars?

(As of July 20th, 2026.)

## Python commits

The CPython repository has over 130,000 commits made during the past 35+ years by 3,500+ contributors.

The Core Python developers are the ~200 developers that have permissions to commit directly to the repository.

Out of the following 4 core developers, who made the fewest commits during the lifetime of the CPython project?

- Guido van Rossum (the creator of Python)
- Hugo van Kemenade (Python 3.14 and 3.15 release manager)
- Łukasz Langa (Python Developer in Residence for almost 5 years)
- Pablo Galindo Salgado (Python 3.10 and 3.11 release manager)

## Numeric expressions

This year, EuroPython celebrated 25 years of conferences.

With that in mind, which of the following expressions does _not_ evaluate to 25?

- `0x19`
- `0b11001`
- `0o33`
- `25`

## Two new built-ins

3.15, coming out in October of 2026, comes with two new built-in functions.

Before that, the previous Python version that got new built-ins was 3.10, with also two new built-ins.

What two built-ins were introduced in 3.10?

- `sentinel` & `frozendict`
- `aiter` & `anext`
- `breakpoint` & `compile`
- `frozenset` & `memoryview`

## Cached squares

Take a look at the following snippet of code:

```py
from functools import cache

@cache
def squares(stop):
    for num in range(stop):
        yield num ** 2

print(sum(squares(10)))  # 285
print(sum(squares(10)))  # ??
```

What does the second `print` output if you run this in Python 3.15?

- `0`
- `285`
- `KeyError`
- `ValueError`

## Cursed code

Take a look at the following cursed code:

```py
print`3<3`[~3]
```

In Python 2, what does that code output?

- `'a'`
- `3`
- `True`
- `IndexError`

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
