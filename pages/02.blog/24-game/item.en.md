---
title: Studying the "24 Game"
---

The [24 Game][24-game] is a well-known maths game that is played with kids in school to help them master the four basic arithmetic operations. In this blog post we will study the game in depth.

===

### The game

The "24 Game" is a simple game. You are given four numbers between $1$ and $9$ (for example $\{1, 2, 3, 4\}$) and your objective is to find an expression that evaluates to $24$. The rules are fairly simple:

 - each given number must be used exactly once;
 - the only operations available are addition, subtraction, multiplication and division;
 - operations may be used repeatedly or not at all;
 - operation precedence can be manipulated by the use of parentheses;
 - no "clever tricks" should be used, only simple arithmetics.


#### Examples

If the given numbers are $\{1, 2, 3, 4\}$, an answer could be

 - $1 \times 2 \times 3 \times 4$.

If the given numbers are $\{2, 5, 7, 8\}$, an answer could be

 - $(2\times 5 - 7)\times 8$.


### The motivation

I was talking to a friend who had just challenged me to make $24$ out of $\{3, 3, 8, 8\}$ ([give it a try yourself][P020]) and as we talked about the game, we asked each other "Why is $24$ the target value? Is $24$ special in any way?".

I had already written a computer program that solves instances of the game so we decided we could use said program to find out neat things about the game itself.

!!! To make my life easier writing this blog post, let's agree that when I talk about _input_ I mean the numbers you have to use to make $24$ and when I talk about the _target_ I mean the number you are trying to make, which is $24$ in the standard game.

The first question we asked ourselves was

#### Does $24$ work for any input?

As far as I know, the game is usually played by giving four distinct numbers as input, so for the usual game $\{2, 3, 4, 5\}$ is a valid input but $\{3, 3, 4, 5\}$ wouldn't be valid. Turns out that $24$ does _not_ work for any input. In fact, out of the $126$ valid inputs, $24$ only works for $124$ of them, failing for

 - $\{1, 6, 7, 8\}$
 - $\{3, 4, 6, 7\}$

which is "acceptable", we thought. It would be really incredible if $24$ worked for any input, but we could live with this solvability rate of $\approx 98.4\%$.

If we allow for inputs with repeated digits, then there are exactly $495$ valid inputs and $24$ works for $404$ of them, dropping its solvability rate to $\approx 81.6\%$.

Having seen this, with $24$ not working for _all_ the $126$ unique inputs, we then asked ourselves

#### Is $24$ the optimal target?

What we mean by this is: out of all the small integers, is $24$ the one that is solvable for more inputs? A quick modification of my script produced this graph:

![graph showing solvability numbers for all targets from 0 to 100 and unique inputs](unique_100.png)

The horizontal dotted line is at the $126$ mark, which is the total number of valid inputs. From this graph we can easily see that $24$ is _not_ the optimal target choice, as picking $2$, $3$, $4$, $6$, $10$ or $12$ would have been better. In fact, $2$, $3$, $4$ and $10$ can be solved with _any_ input, $6$ is only impossible for $\{6, 7, 8, 9\}$ and $12$ is only impossible for $\{1, 5, 7, 8\}$.

So we could say that $2$, $3$, $4$ and $10$ are the "perfect" targets.

It can also be interesting to look at the next graph below, which is similar to the one above except now we take every integer from $0$ to $3024$ as target ($3024 = 9 \times 8 \times 7 \times 6$ is the highest we can get with four unique digits as input):

![graph showing solvability numbers for all targets from 0 to 100 and unique inputs](unique_full_100.png)

Having looked at the graph above, a new question arose naturally...

#### Are any of the "perfect" targets still perfect if the input numbers are allowed to repeat?

Turns out the answer is _no_, but $2$ is quite close to remaining perfect! From the $495$ inputs $2$ does not work for

 - $\{1, 1, 1, 7 \}$
 - $\{1, 1, 1, 8 \}$
 - $\{1, 1, 1, 9 \}$

which means $2$ has an overall solvability rate of $\approx 99.4\%$.

The other perfect targets' solvability rates drop significantly when we include inputs with repeated digits, as we can see in this graph:

![graph showing solvability numbers for all targets from 0 to 100 and non unique inputs](non_unique_100.png)

Below I included a table with all the targets which have solvability rates higher than those of $24$ for any input type ("inputs must have unique digits" and "inputs can have repeated digits").

| target | # inputs solvable with only unique digits | # inputs solvable allowing non-unique digits |
|-:|-:|-:|
|  0 | 116 | 485 |
|  1 | 121 | 470 |
|  2 | 126 | 492 |
|  3 | 126 | 472 |
|  4 | 126 | 464 |
|  5 | 123 | 462 |
|  6 | 125 | 469 |
|  7 | 122 | 461 |
|  8 | 120 | 455 |
|  9 | 120 | 453 |
| 10 | 126 | 447 |
| 11 | 120 | 417 |
| 12 | 125 | 444 |
| 14 | 118 | 410 |
| 15 | 120 | 416 |
| 16 | 122 | 425 |
| 18 | 120 | 405 |

Notice that when we only allow unique digits, $24$ is the seventh best input but when we allow repeated digits, it is only the eighteenth best target... Interesting!


### The algorithm & the code

I wrote a simple APL program to help me answer all these questions. I present the code here, together with a brief description of the algorithm used. The algorithm was recursive and brute-force in nature.

```apl
:Namespace GameOf24
    ⍝ Generalized solver for the "game of 24".

    Solve ← {
        ⍝ Dyadic function to find ways of building ⍺ with the numbers in ⍵
        (reprs values) ← Combine⊂⍵ 
        mask ← ⍺=∊values
        (mask/reprs) (mask/values)
    }

    Combine ← {
        ⍝ Recursive dyadic function combining the numbers ⍵ which have been obtained by the expressions ⍺
        ⎕DIV←1
        ⍺←⍕¨¨⍵ ⍝ default string representations of input numbers
        1=l←≢⊃⍵: ⍺ ⍵  ⍝ if no more numbers to combine, return
        C ← { ⍝ Combine two numbers of ⍵ with the dyadic function in ⍺
            (r v) ← ⍵
            (li ri) ← ↓⍉idx⌿⍨sub← ≠v[idx]
            newv ← v[li] (⍎⍺) v[ri]
            oldv ← v[sub⌿unused]
            values ← ↓newv,oldv
            reprs ← ↓r[sub⌿unused],⍨↓(↑sub/⊂⍺),(↑r[li]),' ',↑r[ri]
            reprs values
        }
        idx ← (~0=(1+l)|⍳l*2) ⌿ ↑,⍳l l
        unused ← idx ~⍨⍤1 1 ⍳l     
        (a w) ← Unpack, '+-×÷' ∘.C ↓⍉↑⍺ ⍵
        u←≠w
        a ∇⍥(u∘/) w
    }
    
    Unpack ← { ⍝ unpack pairs of nested results
        ⊃{(wl wr)←⍵ ⋄ (al ar)←⍺ ⋄ (al,wl)(ar,wr)}/⍵
    }
    
    IsEmpty ← ((0⍴⊂,0)≡⊃∘⌽) ⍝ Check if a return from Solve is empty
    
    CountSolvable ← {
        ⍝ Given a target integer ⍵ check how many 4 unique-digit inputs are solvable
        inps ← ({∧/2</⍵}¨inps)/inps←,1+⍳4⍴9     
        1⊥~IsEmpty¨ ⍵ Solve¨ inps
    }
    
    ∇ counts ← {allowRepeated} StudySolvability upTo
        :If 900⌶⍬
            allowRepeated ← 0
        :EndIf
        :If allowRepeated
            filter ← {∧/2≤/⍵}
            title ← 'Solvable inputs by target'
        :Else
            filter ← {∧/2</⍵}
            title ← 'Unique solvable inputs by target'
        :EndIf
        ⎕← 'Starting the study.'
        (reprs values) ← Unpack Combine∘⊂¨ inps←(filter¨inps)/inps←,1+⍳4⍴9
        flat ← ∊values
        ⎕← 'Maximum attainable value is ', ⌈/flat
        counts ← 1⊥⍉ (⍳upTo+1) ∘.= flat
        
        'InitCauseway' 'View' ⎕CY 'sharpplot'
        InitCauseway ⍬
        sp ← ⎕new Causeway.SharpPlot
        sp.Heading ← title
        sp.XCaption ← 'Target'
        sp.YCaption ← '# inputs solvable'                  
        sp.LineGraphStyle ← Causeway.LineGraphStyles.XYPlot
        sp.LineGraphStyle ← Causeway.LineGraphStyles.GridLines
        sp.DrawLineGraph counts (xs←⍳≢counts)              
        sp.DrawLineGraph (xs ∘.⊢ ≢inps) xs
        View sp
    ∇
:EndNamespace
```

[24-game]: https://en.wikipedia.org/wiki/24_Game
[P020]: ../make-24-with-3-3-8-8
[P019]: ../fold-the-alphabet