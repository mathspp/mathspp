This article shows how to solve the N queens problem in 20 lines of code.

===

# N queens problem

## The problem for N = 10

(You can [skip to the solution](#the-solution) or just [see the code](#the-code).)

“Do you understand what you have to do?”, Queen #6 asks.

“Uh, I– I think so.”

The challenge sounded simple enough but I was a bit intimidated by the Ten Queens all looking at me.

They stood tall in front of me.
An impeccable pose, worthy of a Queen.
Times ten.

They looked rigid and cold, wearing their white and black dresses.
But if you looked carefully enough, they also looked…
They looked… hopeful!
They believed I would be able to help them.

I wasn’t feeling confident and Queen #8 picked up on that, so she decided to recap what they needed:
“Like my sister #6 said, we need to distribute ourselves on this 10 × 10 board.
The goal is to _count_ in how many different ways this can be done.

We like to have room to pace a bit, so no two of us can be on the same row, column, or diagonal.
This restriction is essential.”

I nodded along while she recapped.
Then, I asked “How on Earth am I supposed to compute this?
I can’t do this with pen and paper!”.

All Queens started laughing uncontrollably.
Queen #1 managed to control herself for long enough to reply.

”You silly! You use Python, of course!”
She waved.

The two pawns at the entrance of the room we were in opened the massive doors.
As the huge doors creaked and opened slowly, four pawns came into the hall, carrying a computer.
The computer was already turned on.
As the computer moved closer, this is what I saw on the screen:

```
Python 3.12.0 (the Ten Queens build)
Type "help" for more information.
>>>
```

“You have one week.
You can start now.”

I walked up to the computer and started working on the problem.

## The solution

I was thinking aloud while I was typing.

“We know that no two queens can be on the same row or column.
And that's easy to enforce in my code.
I'll traverse the columns and put one of you in each column while also not repeating rows.
It's the diagonals that I have to be careful about.”

I paused for a bit.
Then I wrote this function:

```py
def diagonally_safe(col, row, placements):
    for qcol, qrow in enumerate(placements):
        if row - col == qrow - qcol or row + col == qrow + qcol:
            return False
    return True
```

I proceeded to explain:

“I'll store a tuple called `placements` with the positions of some of you.
The index is the column you're in and the value itself represents the row.
For example, if `placements` is `(5, 0, 4)` that means that the column `0` has a queen in position `5`, the column `1` has a queen in position `0`, and the column `3` has a queen in position `4`.”

The queens seemed to be following along.

“Now, suppose I want to place a queen in column `3` and in row `r`.
I can call the function `diagonally_safe(r, 3, (5, 0, 4))` and it will tell me if placing a queen in column `3`, row `r` will clash with the other three queens.
This can be done with just a little bit of maths.”

Queen #9 wasn't pleased: “Ugh.
I hate maths.”

“It's basic arithmetic, really.
Pick a square on the board.
Any square.
Suppose it is in column `c` and row `r`.
There are two diagonals going over that square.

Pick any other square in the diagonal that goes up and to the right.
Suppose it's in column `c_` and row `r_`.
You'll see that `r + c == r_ + c_`, and that's how you can see if two squares are in the same diagonal that goes up and to the right.

Similarly, in the diagonal that goes up and to the left you check if `r - c == r_ - c_`.”

Queen #5 looked like she was struggling, so I printed a table with some Python code:

```pycon
>>> for row in range(10):
...     for column in range(10):
...         print(f"{row + column:>3}", end="")
...     print()
...
  0  1  2  3  4  5  6  7  8  9
  1  2  3  4  5  6  7  8  9 10
  2  3  4  5  6  7  8  9 10 11
  3  4  5  6  7  8  9 10 11 12
  4  5  6  7  8  9 10 11 12 13
  5  6  7  8  9 10 11 12 13 14
  6  7  8  9 10 11 12 13 14 15
  7  8  9 10 11 12 13 14 15 16
  8  9 10 11 12 13 14 15 16 17
  9 10 11 12 13 14 15 16 17 18
```

I added “Each number is the same of its coordinates.
Notice how a number creates a diagonal going up and right.
If you subtract them, you'll get diagonals going up and left.”

All queens looked happy, so I kept thinking.

“Now that I have a way of verifying if a possible placement is safe or not, all I have to do is try to fill the board recursively and backtrack every time I can't place the last queens on the board!
I can do this with a loop and a recursive call:

```py
def solve(n, col, placements):
    if col == n:
        return 1

    count = 0
    for row in range(n):
        if row not in placements and _diagonally_safe(col, row, placements):
            count += solve(n, col + 1, placements + (row,))

    return count
```

This was easy to explain to the queens.

“`n` is the total number of queens I need to place and `col` represents the index of the column I am going to place a queen on.
If `col == n`, that's because I already placed all queens and I found a single (`1`) placement.
Otherwise, I'll go over all possible rows and tentatively place a queen on rows that haven't been occupied nor that clash diagonally with the queens that are already there.
If I find such a placement, I try to place all the other queens with some recursion.

The queens looked puzzled for a bit.
I told them to recap my explanation in their heads and look at the code while doing it.
One after the other, they started nodding.

This meant I could show my final lines of code to tie everything together:

```py


def queens(n):
    print(solve(n, 0, tuple()))

queens(10)  # 724
```


## The code

Here's the full code:

```py
def diagonally_safe(row, col, placements):
    for qrow, qcol in enumerate(placements):
        if row - col == qrow - qcol or row + col == qrow + qcol:
            return False
    return True

def solve(n, col, placements):
    if col == n:
        return 1

    count = 0
    for row in range(n):
        if row not in placements and _diagonally_safe(col, row, placements):
            count += solve(n, col + 1, placements + (row,))

    return count

def queens(n):
    print(solve(n, 0, tuple()))

queens(10)  # 724
```
