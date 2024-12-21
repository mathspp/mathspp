Reverse-engineering the program from “Chronospatial Computer”, day 17 of Advent of Code 2024.

===


# Reverse-engineering the “Chronospatial Computer”

The “Chronospatial Computer” is from [Advent of Code 2024, day 17](https://adventofcode.com/2024/day/17), a problem that entertained me for a couple of hours.

## Parsing the input

My input file looked like this:

```txt
Register A: 64012472
Register B: 0
Register C: 0

Program: 2,4,1,7,7,5,0,3,1,7,4,1,5,5,3,0
```

To read the input and parse it I used a context manager and a couple of calls to `readline`:

```py
with open("input.txt", "r") as f:
    register_a = int(f.readline().split()[-1])
    register_b = int(f.readline().split()[-1])
    register_c = int(f.readline().split()[-1])
    _ = f.readline()
    program = [int(num) for num in f.readline().split()[-1].split(",")]

print(program, register_a, register_b, register_c)
```

## Solving part 1 with functional programming

Part 1 required me to simulate a series of simple instructions that operate on three registers.
When I read the problem statement, I decided I wanted to use some ideas from functional programming.
So, what I did was to separate each operator (there are 8) into three parts:

1. the part that performs some computation with the registers and/or with the operand;
2. the part that updates the state of the program, maybe by updating a register or by outputting a value; and
3. the part that updates the pointer of the program, which controls the instruction that will run next.

By using `lambda` functions, [dunder methods](/blog/pydonts/dunder-methods), and [currying with `functools.partial`](/blog/functools-partial), each list below represents one of the three parts of each opcode.

First, the computation part of each operation:

```py
registers = [0, 1, 2, 3, register_A, register_B, register_C]
A, B, C = 4, 5, 6  # Indices of the named registers in the list `registers`.

computations = [
    lambda o: registers[A] // pow(2, registers[o]),  # ADV
    lambda o: registers[B] ^ o,                      # BXL
    lambda o: registers[o] % 8,                      # BST
    lambda o: ...,                                   # JNZ
    lambda o: registers[B] ^ registers[C],           # BXC
    lambda o: registers[o] % 8,                      # OUT
    lambda o: registers[A] // pow(2, registers[o]),  # BDV
    lambda o: registers[A] // pow(2, registers[o]),  # CDV
]
```

In the lambda functions above, when we use `o` in isolation, we're using the operand as a literal operand, whereas the list `registers` maps an operand into its combo operand.
By using this list, we can map the numbers 0 through 3 to themselves and the indices 4, 5, and 6, to the registers A, B, and C, respectively, without having to use a conditional statement.

The operation `JNZ` has a lambda function that does nothing because there is no proper computation for this operator.

Then, I wrote a list with all the functions that update the state of the program:

```py
from functools import partial

output = []
state_updates = [
    partial(registers.__setitem__, A),
    partial(registers.__setitem__, B),
    partial(registers.__setitem__, B),
    lambda v: ...,
    partial(registers.__setitem__, B),
    output.append,
    partial(registers.__setitem__, B),
    partial(registers.__setitem__, C),
]
```

This uses the [dunder method `__setitem__`](/blog/pydonts/dunder-methods) and [the function `functools.partial`](/blog/functools-partial) to create a function that accepts a single value and that writes that value to the correct register in the list `registers`.

Finally, all operators move the program pointer by two positions except the operator `JNZ`:

```py
jumps = [
    lambda ptr, _: ptr + 2,
    lambda ptr, _: ptr + 2,
    lambda ptr, _: ptr + 2,
    lambda ptr, o: ptr + 2 if registers[A] == 0 else o,
    lambda ptr, _: ptr + 2,
    lambda ptr, _: ptr + 2,
    lambda ptr, _: ptr + 2,
    lambda ptr, _: ptr + 2,
]
```

By creating these three lists of functions that represent small units of computation in our chronospatial computer, the simulation code is really short:

```py
ptr = 0
while ptr < len(program):
    opcode = program[ptr]
    operand = program[ptr + 1]
    value = computations[opcode](operand)
    state_updates[opcode](value)
    ptr = jumps[opcode](ptr, operand)

print(output)  # [1, 0, 2, 0, 5, 7, 2, 1, 3]
```

This was fun, but also mostly useless when it comes to solving part 2...


## Part 2 and quines

As a short tangent, part 2 wants you to find a value for the register A that turns your program into a [quine](/blog/buffet-of-self-references#quines), which is a program that prints itself.

For example, here's a short Python quine using f-strings:

```py
s = 'print(f"s = {s!r}\\n{s}")'
print(f"s = {s!r}\n{s}")
```


## Trying brute-force in part 2

My initial approach was to brute-force part 2, but as I later figured out, there were 246,290,604,621,824 (almost 150 trillion) different possible values for the register A that I could try, and in a couple of hours my brute-force approach only checked 2 billion values, which is around 0.00000000166% of all possible values. 🤡

But I can only tell you this in hindsight.
While my brute-force code was running I didn't understand how massive the search space was, so I started working on a different approach.
I will try to tell you more or less what happened.


## Rewriting my program

The first thing I did was grab a piece of paper and try to rewrite my program in a way that I understood, given that the sequence of integers wasn't very friendly.
I started by writing the operations with formulas that I could look at:

| Opcode | Operation | Formula |
| --- | --- | --- |
| 0 | `ADV` | $A ~ // ~ 2^{o_c}\to A$ |
| 1 | `BXL` | $B ~ \wedge ~ o_l \to B$ |
| 2 | `BST` | $o_c ~ \% ~ 8 \to B$ |
| 3 | `JNZ` | - |
| 4 | `BXC` | $B ~ \wedge ~ C \to B$ |
| 5 | `OUT` | $\texttt{print(}o_c ~ \% ~ 8\texttt{)}$ |
| 6 | `BDV` | $A ~ // ~ 2^{o_c}\to B$ |
| 7 | `CDV` | $A ~ // ~ 2^{o_c}\to C$ |

In the table above, $o_c$ is a combo operator and $o_l$ is a literal operator.

Then, I rewrote my program as the sequence of these instructions:

| Program sequence |
| --- |
| `BST (A)` |
| `BXL (7)` |
| `CDV (B)` |
| `ADV (3)` |
| `BXL (7)` |
| `BXC` |
| `OUT (B)` |
| `JNZ (0)` |

In the table above, the column `value` shows the operand for the given operator after interpreting literal and combo operands.
For example, in the second to last line, `OUT (B)` means that we print the register B.

## My program is a loop

If you look at the program above, you will see that there is a single jump instruction `JNZ` and it jumps to the very beginning of the program, so my program is essentially a loop.
The loop depends on the value of the register A, which is only modified by the operation `ADV (3)`, which divides A by 8.
This means that A alone determines how many iterations our loop does.

Given that my input program is a list of 16 integers, this means my loop must run 16 times to produce 16 integers.
Since the value of A is divided by 8 and truncated on each iteration, this means A is at least $8^{15}$ and at most $8^{16} - 1$.

That's when I realised brute-forcing this was definitely not an option.


## Doing a pass over the program

The next thing I did was write out, by hand, what happens if I go once over the program.
I thought that maybe I could fuse the operations together to write a more optimised simulation that computed a single program pass in one go, instead of simulating every single operation.

So, we start out with the following registers:

$$
(A, ~ B, ~ C)
$$

After applying the operation `BST (A)`, we get:

$$
(A, ~ A ~ \% ~ 8, ~ C)
$$

So, we can immediately see that from one program iteration to the next, the value that was stored in B is overwritten and does not matter.

Then, we apply `BXL (7)`:

$$
(A, ~ (A ~ \% ~ 8) ~ \wedge ~ 7, ~ C)
$$

The next step is `CDV (B)`:

$$
(A, ~ (A ~ \% ~ 8) ~ \wedge ~ 7, ~ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 7 })
$$

The fourth step `ADV (3)` divides A by eight and truncates it:

$$
(A ~ // ~ 8, ~ (A ~ \% ~ 8) ~ \wedge ~ 7, ~ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 7 })
$$

The next step `BXL (7)` applies the bitwise XOR to B again, with the literal operand 7 again, which cancels out the previous XOR:

$$
(A ~ // ~ 8, ~ A ~ \% ~ 8, ~ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 7 })
$$

The final computation step is `BXC`:

$$
(A ~ // ~ 8, ~ \left[A ~ \% ~ 8\right] ~ \wedge ~ \left[A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 7 }\right], ~ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 7 })
$$

The computational part of the loop ends here.
Then, we take the value of the register B modulo 8 and output it, and then we repeat the whole thing if A is not zero.

We started off with three arbitrary integers for the three registers.
In the end, the value that is produced in one iteration only depends on the value of the register A at the start of the iteration:

$$
\left[A ~ \% ~ 8\right] ~ \wedge ~ \left[A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 7 }\right]
$$


## Reverse-engineering the output

At this point, I started reverse-engineering the output.

My input program is `2,4,1,7,7,5,0,3,1,7,4,1,5,5,3,0`, which ends with 0.
I also know that in the last iteration, the register A must have a value between 1 and 7, inclusive, otherwise $A // 8 \geq 1$ and there would be another iteration.
So, I can determine what was the value of the register A for the last iteration:

```py
def b(a):
    a_mod_8 = a % 8
    return (a_mod_8 ^ (a // pow(2, a_mod_8 ^ 7))) % 8

for possible_a in range(8):
    print(f"{possible_a} -> {b(possible_a)}")
"""Output:
1 -> 1
2 -> 2
3 -> 3
4 -> 4
5 -> 4
6 -> 5
7 -> 0
"""
```

The function `b` has a final `% 8` in the return statement because we know the values that are printed are computed modulo 8.

The code above shows that if A is 7, then the value produced is 0.
This means that A must be 7 in the last iteration.

But wait.
If A is 7 in the last iteration, then it must have been between 56 and 63, inclusive, in the previous iteration since 56 is the smallest integer for which `ADV (3)` gives `7` and 63 is the largest for which `ADV (3)` gives `7`.
My second-to-last digit in the program is a `3`, so I can check which value of A produces it:

```py
for possible_a in range(56, 63):
    print(f"{possible_a} -> {b(possible_a)}")
"""Output:
56 -> 0
57 -> 1
58 -> 3
59 -> 0
60 -> 3
61 -> 2
62 -> 1
"""
```

Now we see that both `58` and `60` produce a `3`, so we have two options to explore when backtracking.

We can do this process 14 more times and we'll have our answer!
Instead of doing all of this backtracking by hand, of course we can write a program that does it for us:

```py
def b(a):
    a_mod_8 = a % 8
    return (a_mod_8 ^ (a // pow(2, a_mod_8 ^ 7))) % 8


def backtrack(program, b):
    to_explore = [(0, 0)]  # Pairs of (a, matches_done) to backtrack.
    found = []
    while to_explore:
        next_a, matches_done = to_explore.pop()
        if matches_done == len(program):
            found.append(next_a)
            continue
        target_b = program[-(matches_done + 1)]
        for a in range(next_a * 8, next_a * 8 + 8):
            produced = b(a)
            if produced == target_b:
                to_explore.append((a, matches_done + 1))

    return found

print(sorted(backtrack(program, b)))
```

If we run this, I get a list of two possible initial values for the register A:

```
[265652340990875, 265652340990877]
```

Now I just have to pick the smallest, and that's the answer I'm looking for.

This is the process I went through and then I wondered whether I had gotten lucky with my specific program or if this was something I could generalise to other inputs.
I asked for a couple of friends for their input programs and now I'll validate my approach with those.

## Validating my approach with other inputs

### 2,4,1,1,7,5,4,4,1,4,0,3,5,5,3,0

Translating this program to opcodes, we get

| Program sequence |
| --- |
| `BST (A)` |
| `BXL (1)` |
| `CDV (B)` |
| `BXC` |
| `BXL (4)` |
| `ADV (3)` |
| `OUT (B)` |
| `JNZ (0)` |

This is a great sign, because it is very similar to my own program.
The parts of the program that I relied on for my approach are exactly the same, namely that the register A gets divided by 8 at each step and that the program is a single loop that iterates over repeatedly.

What changes slightly are the computations and the order in which they happen, but that's what creates some variety in the player outputs.

Starting with $(A, B, C)$, here is what this program looks like:

$$
\begin{align}
(A, B, C) &\to (A, ~ A ~ \% ~ 8, C) \\
&\to (A, ~ (A ~ \% ~ 8) ~ \wedge ~ 1, C) \\
&\to (A, ~ (A ~ \% ~ 8) ~ \wedge ~ 1, A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 1}) \\
&\to (A, ~ \left[(A ~ \% ~ 8) ~ \wedge ~ 1\right] ~ \wedge ~ \left[ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 1} \right], A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 1}) \\
&\to (A, ~ \left[(A ~ \% ~ 8) ~ \wedge ~ 1\right] ~ \wedge ~ \left[ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 1} \right] ~ \wedge ~ 4, A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 1}) \\
&\to (A ~ // ~ 8, ~ \left[(A ~ \% ~ 8) ~ \wedge ~ 1\right] ~ \wedge ~ \left[ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 1} \right] ~ \wedge ~ 4, A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 1})
\end{align}
$$

In this instance, the formula for the final value of the register B is slightly more convoluted, but the principle works the same: we define a function `b2` that computes that value and then we run the backtracking:

```py
program2 = [2, 4, 1, 1, 7, 5, 4, 4, 1, 4, 0, 3, 5, 5, 3, 0]

def b2(a):
    a_mod8_xor1 = (a % 8) ^ 1
    return (a_mod8_xor1 ^ (a // pow(2, a_mod8_xor1)) ^ 4) % 8

print(sorted(backtrack(program2, b2)))
```

This produces a list with a couple of different values:

```py
[202991746427434, 202991746427437, 202991746427439, 202992818072106, 202992818072109, 202992818072111, 202992820169258, 202992820169261, 202992820169263]
```

Of these, we take the smallest one as our final answer, and voilá!

We can go back to part 1 and check that with this program and this initial value of register A, the output is the same program.
This verifies that the given value for A turns the program into a quine, but we don't have an excellent way of making sure this is the smallest value of A, other than double checking with the friend that provided this alternative input.

Let's just do one more.

### 2,4,1,3,7,5,4,0,1,3,0,3,5,5,3,0

Translating this program to opcodes, we get

| Program sequence |
| --- |
| `BST (A)` |
| `BXL (3)` |
| `CDV (B)` |
| `BXC` |
| `BXL (3)` |
| `ADV (3)` |
| `OUT (B)` |
| `JNZ (0)` |

Next, we can unfold the sequence of steps:

$$
\begin{align}
(A, B, C) &\to (A, ~ A ~ \% ~ 8, C) \\
&\to (A, ~ (A ~ \% ~ 8) ~ \wedge ~ 3, C) \\
&\to (A, ~ (A ~ \% ~ 8) ~ \wedge ~ 3, A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 3}) \\
&\to (A, ~ \left[(A ~ \% ~ 8) ~ \wedge ~ 3\right] ~ \wedge ~ \left[ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 3} \right], A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 3}) \\
&\to (A, ~ \left[(A ~ \% ~ 8) ~ \wedge ~ 3\right] ~ \wedge ~ \left[ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 3} \right] ~ \wedge ~ 3, A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 3}) \\
&\to (A ~ // ~ 8, ~ \left[(A ~ \% ~ 8) ~ \wedge ~ 3\right] ~ \wedge ~ \left[ A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 3} \right] ~ \wedge ~ 3, A ~ // ~ 2^{(A ~ \% ~ 8) ~ \wedge ~ 3})
\end{align}
$$

This translates to a slightly different `b3` that we can use for the backtracking:

```py
program3 = [2, 4, 1, 3, 7, 5, 4, 0, 1, 3, 0, 3, 5, 5, 3, 0]

def b3(base_a, a_mod_8):
    a_mod8_xor3 = (a % 8) ^ 3
    return (a_mod8_xor3 ^ (a // pow(2, a_mod8_xor3)) ^ 3) % 8

print(sorted(backtrack(program3, b3)))
```

This prints another list with possible values:

```py
[9641154550269, 11565299898877, 11608249571837, 11840177805821, 108107574778365]
```

Taking these values for the A register and running the simulation with this input program, we get the program back as output, so this one also looks alright!

## A less manual approach

I got to this approach by using pen and paper but you don't need to write down formulas to define the function `b`.

The function `b` can also be defined in terms of simulating the program running.
However, instead of doing the full simulation, you stop after one iteration over the instructions.

This works well if you're too lazy to find a piece of paper to scribble on or if you don't feel comfortable with the idea of writing down the program steps by hand.
