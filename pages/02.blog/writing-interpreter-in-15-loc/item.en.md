---
metadata:
    description: In this blog post I'll show how to write a full interpreter for a programming language in less than 15 loc of Python.
title: Implementing an interpreter in 14 lines of Python.
---

In this blog post I'll show you how you can write a full interpreter for the [brainf*ck][bf] programming language in just 14 lines of Python. Be prepared, however, to see some unconventional Python code!

===

![A slightly edited photo of the code in question](code.png)

# Preview

By the time you've gone through this blog post, you'll have a [brainf*ck][bf] interpreter that takes the brainf*ck code `+[-->-[>>+>-----<<]<--<---]>-.>>>+.>>..+++[.>]<<<<.+++.------.<<-.>>>>+.` and interprets it, so that it prints `"Hello, World!"`.

And what is more, we'll do it in just 14 lines of (very unconventional!) Python code!

## The code

You can go ahead and download the code [from GH][code].
For your convenience, you can also just copy the code from [here][gh-raw].
To run it, you need Python 3.8 or greater.
Assuming you saved it to the file `brainfck.py`, you can run the example above with

```sh
python brainfck.py "+[-->-[>>+>-----<<]<--<---]>-.>>>+.>>..+++[.>]<<<<.+++.------.<<-.>>>>+."
```

but perhaps a simpler example is

```sh
python brainfck.py ",[>,]<[.<]" "This will get reversed!"
```

The calling syntax is `brainfck.py [code | filepath] [program input]` and the code that is getting called is this:

```py
from sys import*;import io,os;V=argv;V.extend([""]*2);stdin=io.StringIO(V[2])if V[2]else stdin
r=os.path.exists(V[1]);f=r and open(V[1]);b=f.read()if r else V[1];r and f.close()
def I(b,t,p):
 while b: # interpret while there's code
  c,*b=b;c="+-><,.[]".find(c) # get next op
  if c in[0,1]:t[p]+=1-2*c;t[p]%=256 # increase memory cell and wrap at 256
  if c in[2,3]:p+=5-2*c;t=[0]*(p<0)+t+[0]*(p==len(t));p=max(p,0) # move pointer and adjust tape
  if c==4:i=stdin.read(1)or chr(0);t[p]=ord(i)%256 # read one char as numeric input
  if c==5:stdout.write(chr(t[p])) # print one char as output
  if c==6:
   d=1;j=[d:=d+(x=="[")-(x=="]")for x in b].index(0);b,b_=b[j+1:],b[:j]
   while t[p]:t,p=I(b_,t,p) # loop while memory cell is non-zero
 return t,p
t,p=I(b,[0],0);print();print(t,p) # interpret and print debugging info
```

Keep reading below to see what this does!

# Brainf*ck

(skip to "[Implementing an interpreter](#implementing-an-interpreter)" if you know what brainf\*ck is and how to program in it.)

[Brainf*ck][bf] is a minimalistic [esoteric programming language][epl],
meaning brainf\*ck was not created with actual usability in mind.
Programs in brainf\*ck are written with eight simple commands,
them being `+-><,.[]`, and programming in brainf\*ck is more or less
like programming directly on a Turing machine...
(If you don't know what that means, don't worry.)

## Programming in brainf*ck

Program execution in brainf\*ck happens on top of a tape
that is made of infinitely many cells,
each cell holding an 8bit unsigned integer (that is, integers from 0 to 255).

At any time in the execution of a brainf\*ck program,
we are looking at a single cell of those infinitely many,
and the cell we are looking at is controlled by a pointer.

All brainf\*ck programs start at the same point:

```
tape cells
----------
[0][0][0][0][0]...
 ^ pointer
```

4 of the 8 basic operations are used to move the pointer right `>` and left `<`,
and to increment `+` and decrement `-` the current cell.

For example, the program `+++` gives

```
tape cells
----------
[3][0][0][0][0]...
 ^ pointer
```

and the program `+++>++>>++` gives

```
tape cells
----------
[3][2][0][2][0]...
          ^ pointer
```

The cells can only hold values between 0 and 255,
so many implementations choose to wrap around when values go above 255 or below 0.
In doing that, the simple program `-` gives

```
tape cells
----------
[255][0][0][0][0]...
 ^ pointer
```

because we wrapped under the 0 to 255.

The next two operations we will go over are `,.` that are used
for input and output.
`,` reads a single character to the current cell, but characters
are converted to their ASCII code points before writing to the cell.
In conclusion, if you give `"a"` as input to the brainf\*ck program `,`,
then the result will be

```
tape cells
----------
[97][0][0][0][0]...
 ^ pointer
```

because `"a"` has code point 97.
(You can see [here][ascii] for a reference table with the 256 ASCII code points.)

In the same way, `.` is used to print the character corresponding
to the value of the current cell.
For example, if we have the code `,+.` and the input `"a"` then the tape looks like

```
tape cells
----------
[98][0][0][0][0]...
 ^ pointer
```

by the time we are done and the program outputs `"b"`.
But don't take my word for it!
Go ahead, [download the file][code], save it to `brainfck.py` and run

```py
python brainfck.py ",+." "a"
```

Finally, the commands `[]` are for looping.
`[` starts a `while` loop and `]` closes it.
When program execution reaches a `[`, it will only run the code between `[]`
**if** the current cell is not 0.
So, if the tape looks like any of the cases below, reaching a `[`
will execute the code between `[` and `]`.

```
tape cells           |  tape cells         |  tape cells
----------           |  ----------         |  ----------
[98][0][0][0][0]...  |  [0][1][0][255]...  |  [0][0][0][73]...
 ^ pointer           |      ^ pointer      |            ^ pointer
```

Then, program execution repeats the code between the `[]` as long as the
pointer keeps pointing at a cell that is not 0 when we reach `]`.

This is roughly equivalent to the Python code

```py
while tape[pointer]:
    interpret(code_between_brackets)
```

Notice that the code between `[]` *can* move the pointer,
in which case the loop checks the *current* cell, not the cell
that was active when the loop started.

Finally, you can nest arbitrarily many `[]` inside each other.

Now that we have seen how one programs in brainf\*ck,
let's implement an interpreter!

# Implementing an interpreter

First things first, we have to decide on a couple of things that
are not universal across interpreters.

## Specs

Our interpreter will abide by the following specs:

 - The memory tape is infinite in both directions.
 - The starting value of each cell is 0 (this is standard).
 - The first command line argument is for the code:
   - it may be brainf\*code, or
   - the path to a file with the code.
 - Input can be given
   - prior to program execution, as the second command line argument;
   - interactively, during program execution.
 - Reading input with `,` when there is none left sets the current cell to 0.
 - Cell values are 8 bit unsigned integers (between 0 and 255).

Now that we know *exactly* what we want, let's implement it.

## Implementation

Here's the full implementation of the interpreter, in 14 loc:

```py
from sys import*;import io,os;V=argv;V.extend([""]*2);stdin=io.StringIO(V[2])if V[2]else stdin
r=os.path.exists(V[1]);f=r and open(V[1]);b=f.read()if r else V[1];r and f.close()
def I(b,t,p):
 while b: # interpret while there's code
  c,*b=b;c="+-><,.[]".find(c) # get next op
  if c in[0,1]:t[p]+=1-2*c;t[p]%=256 # increase memory cell and wrap at 256
  if c in[2,3]:p+=5-2*c;t=[0]*(p<0)+t+[0]*(p==len(t));p=max(p,0) # move pointer and adjust tape
  if c==4:i=stdin.read(1)or chr(0);t[p]=ord(i)%256 # read one char as numeric input
  if c==5:stdout.write(chr(t[p])) # print one char as output
  if c==6:
   d=1;j=[d:=d+(x=="[")-(x=="]")for x in b].index(0);b,b_=b[j+1:],b[:j]
   while t[p]:t,p=I(b_,t,p) # loop while memory cell is non-zero
 return t,p
t,p=I(b,[0],0);print();print(t,p) # interpret and print debugging info
```

!! The style of this Python code is very unconventional and I am not, in any way, suggesting you code like this in any setting.
!! Writing code like this was a proof of concept.

## Overview of the code

The first two lines perform some setup,
like
 - importing libraries,
 - setting default command line arguments,
 - deciding if we are reading input interactively or from the command line arguments
 - and deciding if we are reading code from a file or from the command line arguments.

Next, we define the function `I` which interprets brainf\*ck code.
The several `if`s distinguish the several commands and most notably,
when we encounter a `[`, we call `I` recursively in a `while` loop.

Finally, the last line of code interprets the whole program and prints some
debugging information when the program is done.

# Breaking down the code

Let me break down every instruction of the program for you!

## `from sys import *`

We import everything from `sys` so we can refer to `argv` (the command line arguments), `stdin` (the standard input stream) and `stdout` (the standard output stream)
without having to type `sys.` in front of them every single time.

## `import io,os`

We import `io` to use a `StringIO` object to replace `stdin` (standard input)
in case the user gave the program input beforehand and doesn't want an
interactive input session.

## `V=argv`

We set an alias for `argv` (the command line arguments) to save some typing.

## `V.extend([""]*2)`

We extend the command line argument vector with two empty strings,
so that we can index `V[1]` and `V[2]` safely.
`V[1]` holds the brainf\*ck code or the path to the file with the code,
and `V[2]` holds the input, if an interactive input session is not wanted.

## `stdin=io.StringIO(V[2])if V[2]else stdin`

If the second command line argument sets some input,
we set the `stdin` object (from `sys`) to read from that string,
instead of interactively from the shell/terminal.

If `V[2]` is empty, then `stdin = stdin` does nothing and later
we read the input interactively.

## `r=os.path.exists(V[1])`

The boolean flag `r` tells us if we are going to `r`ead the brainf\*ck code
from a file.

## `f=r and open(V[1])`

This assignment makes use of short-circuiting to do one of two things:
 - set `f` to `False` if we don't need to read the brainf\*ck code from a file;
 - set `f` to the opened file from where we will read the brainf\*ck code.

## `b=f.read()if f else V[1]`

Depending on whether `r` is set or not,
we read the code from the file `f` or from the first command line argument `V[1]`.

## `r and f.close()`

We use another short-circuiting `and` to close the file if needed.

## `def I(b,t,p)`

We define the header of the function that `I`nterprets brainf\*ck code.

The arguments are the `b`rainf\*ck code to be interpreted,
the `t`ape and the `p`ointer.

## `while b:`

Interpret the code character by character, while the string
holding the code is not empty.

## `c,*b=b`

Split the code into the next `c`ommand and the remaining `b`rainf\*ck code.

## `c="+-><,.[]".find(c)`

Assign `c` to a numerical value corresponding to the index in the string
of all the brainf\*ck commands.
If `c` is not a valid command, `c` defaults to `-1`.

## `if c in[0,1]`

We enter this `if`-statement if the command is `+` or `-`, and then

## `t[p]+=1-2*c`

This increments or decrements the current cell, depending on whether
the command was `+` or `-`:

| Command | `c` | `1-2*c` |
| :- | :-: | -: |
| `+` | `0` | `1` |
| `-` | `1` | `-1` |

## `t[p]%=256`

Ensures the current cell is still in the range `0 - 255`.
In case you don't know, `t[p]%=256` is syntatic sugar for `t[p] = t[p] % 256`.

## `if c in[2,3]`

We enter this `if`-statement if the command is either `>` or `<`, and then

## `p+=5-2*c`

Transform `c` into `1` or `-1`, to adjust the current `p`ointer.
Uses basic arithmetic to get it right:

| Command | `c` | `5-2*c` |
| :- | :-: | -: |
| `>` | `2` | `1` |
| `<` | `3` | `-1` |

## `t=[0]*(p<0)+t+[0]*(p==len(t))`

Extends the `t`ape if the `p`ointer is no longer pointing to a valid position.

The brainf\*ck tape is infinite in theory, but in practice we only ever hold
a finite number of cells, the ones that are needed.
For that matter, we dynamically extend the tape as needed.
That is what `[0]*(p<0)` and `[0]*(p==len(t))` do:
 - if we are pointing beyond the left edge of the `t`ape, then `p < 0` and
`[0]*(p<0)` creates a `[0]` to prepend to the `t`ape;
 - if we are pointing beyond the right edge of the `t`ape, then `p == len(t)` and
`[0]*(p==len(t))` creates a `[0]` to append to the `t`ape.

## `p=max(p,0)`

`p` only goes to `-1` when we are at the left edge of the `t`ape and we
walk left `<` again.
When that happens, we immediately extend the tape by prepending an empty
cell `[0]` to it, so that `p` should now point to the index `0`,
which we enforce with `p=max(p,0)`.

## `if c==4`

We enter this `if`-statement if the command is `,`, and then

## `i=stdin.read(1)or chr(0)`

We read one character of `i`nput.
We do so by first trying to read one character from the `stdin` object,
and if there is no input left from there,
we artificially set `i` to be the character whose ASCII code point is `0`,
with `chr(0)`.

Remember that `stdin` might be the standard input stream,
or it might be a `io.StringIO` object.
Either way, `stdin.read(1)` reads at most one input character.

## `t[p]=ord(i)%256`

We read the ASCII code point of the input character into the
current cell, but we do so modulo `256` in case the input
was not an ASCII character.

## `if c==5`

We enter this `if`-statement if the command is `.`, and then

## `stdout.write(chr(t[p]))`

We just need to convert the current cell into a character and write it
to the standard output stream.

## `if c==6`

We enter this `if`-statement if the command is `[`,
i.e. if we want to start a loop.

The idea here will be that we will look for the matching `]`,
extract the code in between `[` and `]` and recursively evaluate it.
Here's how I do it:

## `d=1`

The variable `d` will control the running `d`epth of the brackets.
Coming across a `[` increments `d` and coming across a `]` decrements `d`.
We want to find a `]` that sets `d` to `0`.

## `j=[d:=d+(x=="[")-(x=="]")for x in b].index(0)`

This sets `j` to the position of the matching `]` by computing the running depths
in the list comprehension and then finding the index of `0`.
If the code doesn't have a matching `]`, then this errors.

The list comprehension is used to compute the level of depth inside pairs of `[]`
for the brainf\*ck code.
For each character `x` in the code we update the `d`epth by checking if `x`
is `[` (in which case we increase the depth)
or if `x` is `]` (in which case we decrease the depth).

For example, if the code is `[++[<]]`, then `c` gets `[` and `b` becomes `++[<]]`.
Then `d` is initalised to `1` and the list comprehension evaluates to
`[1, 1, 2, 2, 1, 0]`.

## `b,b_=b[j+1:],b[:j]`

After we find the place where the matching `]` is, we split the code in two parts.
The part that will be interpreted recursively, that is `b_`,
and the part of the code we will interpret when we resume evaluation after the `[]`.

## `while t[p]`

This is the test we perform to check if we are going to evaluate the code
inside `[]` or not.

## `t,p=I(b_,t,p)`

If the current cell is non zero, then we have to interpret the code inside `[]`
(which is `b_`).
We do so by recursively calling `I` with the current parameters:
the `t`ape and the `p`ointer.
We then set the return value of the recursive call to update the current `t`ape
and `p`ointer with `t,p=I(...)`.

## `return t,p`

After all code is interpreted, we return the current `t`ape and `p`ointer values.
This is mainly so that the recursive calls (for when `[]` is used) work.

At this point, the definition of `I` is complete.

## `t,p=I(b,[0],0)`

This is the initial call that starts the interpreting of a `b`rainf\*ck program.

## `print()`

This prints a newline to separate any program output from the debugging information
that is printed in the next statement.

## `print(t,p)`

This prints the final state of the `t`ape and of the `p`ointer for debugging purposes.

**And that is all!** This is the whole interpreter!

You can go ahead and write your own brainf\*ck programs, or you can have a look
at the additional resources below for links to places with interesting programs.

# Additional resources

You can find two *really* short brainf\*ck interpreters written in Python
in [here][bf-golf-1] and in [here][bf-golf-2], both of which are from the
[Code Golf][cg] website, of the Stack Exchange network.
[This][bf-tutorial] page contains a nice brainf\*ck tutorial and you
can interpret brainf\*ck code online [on this webpage][bf-online].

You can also go online at [brainfuck.org](http://brainfuck.org)
to find an amazing collection of brainf\*ck programs by Daniel B. Cristofani.

[bf]: https://en.wikipedia.org/wiki/Brainfuck
[epl]: https://en.wikipedia.org/wiki/Esoteric_programming_language
[ascii]: http://www.asciitable.com/
[code]: https://github.com/RojerGS/languages/blob/master/brainfck/brainfck_terse_terse.py
[cg]: codegolf.stackexchange.com
[bf-golf-1]: https://codegolf.stackexchange.com/a/187127/75323
[bf-golf-2]: https://codegolf.stackexchange.com/a/3085/75323
[bf-tutorial]: https://gist.github.com/roachhd/dce54bec8ba55fb17d3a
[bf-online]: https://copy.sh/brainfuck/
[gh-raw]: https://raw.githubusercontent.com/RojerGS/languages/master/brainfck/brainfck_terse_terse.py
