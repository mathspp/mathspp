---
author: Hillel Wayne
date: 25-04-2025 14:47
link: https://buttondown.com/hillelwayne/archive/a48fce5b-8a05-4302-b620-9b26f057f145/
taxonomy:
    category: link
title: 'Solving a "Layton Puzzle" with Prolog'
---

In this article, the author solves a logic puzzle with Prolog.
The puzzle is as follows:

Mary, Dan, Lisa, and Colin took a test with 10 True/False questions.
Here are their answers and scores:

| Name | Answers | Score |
| | | |
| Mary | `FFTFTFFTFF` | 7/10 |
| Dan | `FTTTFTFTTT` | 5/10 |
| Lisa | `FTTTFFFTFT` | 3/10 |
| Colin | `FFTTTFFTTT` | ?/10 |

Your objective is to find out Colin's score.

The author proceeded to write some Prolog code to solve this.
I don't know Prolog but I was quite fascinated with some of the ideas presented (I can't judge the brilliance of the ideas because of my ignorance; maybe these are standard in the Prolog world).
For example, here's a recursive definition for `score` which I'll attempt to interpret:

```prolog
% The student's test score
% score(student answers, answer key, score)
score([], [], 0).
score([A|As], [A|Ks], N) :-
   N #= M + 1, score(As, Ks, M).
score([A|As], [K|Ks], N) :- 
    dif(A, K), score(As, Ks, N).
```

I think the three terms `score(...)` set up some sort of predicates that Prolog can evaluate to true or false, and the first one is the base case: for an empty answer sheet and for a test with no questions, the score is 0.

Then, this line:

```prolog
score([A|As], [A|Ks], N) :-
   N #= M + 1, score(As, Ks, M).
```

Earlier in the article, the author says that you can write “`A #= B + 1` to say "A is 1 more than B"”.
And the code `[A|As], [A|Ks]` seems to do some kind of pattern matching on the first element of both lists when they are the same.
So, this seems to be saying that `score([A|As], [K|Ks], N)` will be true if:
 - `N` is 1 more than `M`; and
 - `score(As, Ks, M)` is true.

Similarly,

```prolog
score([A|As], [K|Ks], N) :- 
    dif(A, K), score(As, Ks, N).
```

seems to be saying that the predicate `score([A|As], [K|Ks], N)` will be true if:
 - `A` is different from `B`; and
 - `score(As, Ks, N)` is true.
