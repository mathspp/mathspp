---
date: 22-08-2022
metadata:
    author: Rodrigo Girão Serrão
    description: "This short article explains a monstrous piece of code that David Beazley wrote."
    og:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
    twitter:image: https://mathspp.com/blog/twitter-threads/thumbnail.png
slug: deciphering-david-beazleys-monstrosity
taxonomy:
    category: twitter thread
    tag:
      - programming
      - python
title: "Deciphering David Beazley's monstrosity"
---

This short article explains a monstrous piece of code that David Beazley wrote.

===

I just found another Python 🐍 monstrosity:

```py
sub = lambda x,y,c: c(x-y)
mul = lambda x,y,c: c(x*y)
eqv = lambda x,y,c: c(x==y)
bnz = lambda t,c,a: (a,c)[t]()

def fact(n, c):
    (loop:= lambda n, r0: (
            eqv(n, 1, lambda r1:
            bnz(r1, lambda: c(r0), lambda:
            mul(n, r0, lambda r2:
            sub(n, 1, lambda r3:
            loop(r3, r2)))))))\
    (n, 1)
    
fact(5, print)
```

This one was brought to you by the infamous David Beazley.
But what does it do?

Let us start by looking at the four lambdas at the top.

The first three are quite similar to each other:

```py
sub = lambda x,y,c: c(x-y)
mul = lambda x,y,c: c(x*y)
eqv = lambda x,y,c: c(x==y)
bnz = lambda t,c,a: (a,c)[t]()

sub(5, 3, print)  # Prints 2
mul(5, 3, print)  # Prints 15
eqv(5, 3, print)  # Prints False
```

Take the first two arguments, operate on them, and recursively call `c` with the result.

So, David is going to chain operations by doing these crazy recursive calls.

The fourth lambda is a bit more interesting but also crazier: it implements conditionals.
(I have no idea what “bnz” means, though 🤷)
The first argument is the Boolean value, then:

 - if the Boolean is `True`, we call `c`;
 - if it is `False`, we call the alternative `a`.

```py
bnz = lambda t,c,a: (a,c)[t]()

yes = lambda: print("heya")
no = lambda: print(":(")

bnz(True, yes, no)  # Prints heya
bnz(False, yes, no)  # Prints :(
```


Alright, we are halfway there!
Now, we just need to understand how David put everything together to implement the factorial function.

The function `fact` defines `loop` in its body, which is a tail recursive factorial.
Remember that the 3rd arguments of `sub`, `mul`, and `eqv`, can be seen as what happens after that operation.

```py
def fact(n, c):
    (loop:= lambda n, r0: (  # Start computing with n and r0.
            eqv(n, 1, lambda r1:  # Is n equal to 1?
            bnz(r1, lambda: c(r0), lambda:  # If it is, call c(r0) and end, if not...
            mul(n, r0, lambda r2:  # multiply n with r0 and call it r2, then
            sub(n, 1, lambda r3:  # compute n - 1 and call it r2, and
            loop(r3, r2)))))))\   # go to the top, with the new values for n and r0.
    (n, 1)
```

In the lambda `loop`, `n` is the value for which we still have to compute the factorial,
and it decreases with each call.
`r0` is the accumulated factorial that grows as we reduce the value of `n`.

Does this make sense?


!!!! This article was generated automatically from [this thread](https://twitter.com/mathsppblog/status/1561765223408730116) I published on Twitter [@mathsppblog][mathsppblog].
!!!! Then it was edited lightly.

[mathsppblog]: https://twitter.com/mathsppblog
