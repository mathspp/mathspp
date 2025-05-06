---
# author:
date: 06-05-2025 13:34
link: https://www.cs.yale.edu/homes/perlis-alan/quotes.html
taxonomy:
    category: link
title: "Perlisisms - "Epigrams in Programming" by Alan J. Perlis"
# via:
---

I've read Alan J. Perlis's epigrams a dozen of times.
Some of them I don't understand.
Others, highly resonate with me.

One of my favourite epigrams must be number 10 in the linked article, and in particular, the second half:

 > “[...] The only difference(!) between Shakespeare and you was the size of his idiom list - not the size of his vocabulary.”

I really like this idea because if you think about Python, for example, there is a finite and fairly small number of built-in functions.
However, the ways in which you can combine them are orders of magnitude larger, and the more you try to combine the built-ins, the syntactic features of the language, and the modules, the more expressive your code becomes.

That is how I feel about my favourite line of code:

```py
sum(predicate(value) for value in iterable)
```

A fairly simple line of code that combines the built-in `sum`, duck typing, and a generator expression.
When you put the three together, you get an idiom that counts how many elements of the given `iterable` satisfy the given predicate.
