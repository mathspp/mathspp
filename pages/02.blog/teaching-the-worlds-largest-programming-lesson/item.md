This article goes over the content I taught at the world's largest programming lesson that broke a Guinness World Record with 1668 students.

===


# Teaching the world's largest programming lesson

On the 12th of October of 2024 I was part of an attempt to break the Guinness World Record of the largest programming lesson in the world.
The previous record was at 724 students and we managed to surpass it by giving a programming lesson to 1668 students.
This incredible lesson was organised by [Magma Studio](https://magmastudio.pt), a company I worked at (and whose partners take random ideas I get waaaay too seriously) and [Instituto Superior Técnico](https://tecnico.ulisboa.pt/en/), my _alma mater_.

The lesson was designed for people that did not have any programming background and was broken up into three sections:

 1. first, students were introduced to the concept of algorithm. This part of the lesson was taught by Professor Inês Lynce;
 2. next, I taught students how to write some Python code to turn algorithms into programs that a computer can run; and
 3. finally, Professor Arlindo Oliveira showed some more advanced algorithms by showing a [Game of Life](https://en.wikipedia.org/wiki/Conway%27s_Game_of_Life) implementation and an example of a RAG application that let students interact with a major literary work that Portuguese students are supposed to read in school.

I will now share the contents of my lesson, as promised to my 1668 official students (we had more people in the room but some were disqualified!).

![Selfie showing a large room with a sea of people that goes so far back that you cannot really count how many rows of chairs there are. You cannot see all of the students who were trying to break the Guinness World Record for the largest programming lesson. Everyone has their arms up and part of my face is visible on the left.](_lesson.webp "Selfie that I took during the lesson.")


## Objective of the lesson

The objective for my part of the lesson was to teach some Python to students and have them write some code.
The professor before me taught them what an algorithm was and used Sudoku as an example, so my ultimate goal is to write some Python code to solve a Sudoku board with Python.
For this to be possible I have to teach the students some parts of the syntax of Python.


## First interactions with Python

Everyone was instructed to download Python from python.org, as that would mean everyone would get the editor IDLE installed on their computers.
While IDLE isn't typically used in a professional context, it was an editor that would be readily available to anyone who installed Python, meaning I wouldn't have to instruct students to install _two_ things instead of one.

I also made sure I explained the difference between Python, the programming language, and IDLE, the program / editor we used to interact with Python.

After opening IDLE, we interacted with Python on the REPL by performing some basic calculations:

```pycon
>>> 3 + 3
6
>>> 2 * 4  # We use `*` for multiplication
8
>>> 2 ** 3  # We use `**` for exponentiation
8
```

We saw how typing things into the REPL and pressing <kbd>Enter</kbd> presented us with the result immediately.
Then, we explored variables and how we can use them to store values inside them:

```pycon
>>> age = 27
>>> age
27
```

We noticed how defining a variable and then pressing <kbd>Enter</kbd> didn't show an immediate result in the console but typing the name of the variable and then pressing <kbd>Enter</kbd> would show the value that was put inside the variable.
Although not strictly correct, it is easy to think of a variable as a box inside which we put things.

We also took a quick look at how we need to be careful when typing code.
Something as simple as messing up the capitalisation of the letters in a variable name can make Python show an error message.


## Writing Python code in a file

The next thing we did was move on to writing code in a file.
We write code in files because that makes it easier to rerun your code, to access it and store it for later, to edit it, to share it, and more.
Python files typically end with the extension `.py` and we wrote a first simple script:

```py
age = 27
age
```

By running the code we understood that we need to do something else to actually see the value of the variable `age` that we created in our file.
For that, we introduced `print`:

```py
age = 27
print(age)
```

The function `print` is responsible for making results show up on the screen.


## Conditional statements

Immediately after we took a quick look at conditional statements, which allow us to take different actions depending on the context of our program.
In our case, we checked if the value of the variable `age` meant that the user should go vote or not:

```py
age = 27

if age > 18:
    print("You are an adult. Go vote.")
else:
    print("You are young!")
```

(A friend of mine pointed out, and rightfully so, that I made a mistake and that the condition should have been `age >= 18` but I did not notice at the time...)


## Repeated actions with loops

The next bit of syntax I needed the students to be aware of was `while` loops.
I explained that loops are useful when you need to repeat an action and that `while` loops, in particular, are useful when you don't know beforehand how many times that action needs to be repeated.

For example, whenever I eat some soup I don't know how many spoonfuls I will need to eat all of the soup.
What I do is I grab a spoon and I fill the spoon and take it to my mouth _while_ there is soup in my plate.

We also saw an interesting example of a `while` loop where the condition was just `1 > 0`.
We agreed that `1 > 0` was always true and then we ran the following piece of code:

```py
while 1 > 0:
    print("1 is greater than 0. Wow.")
```

We saw that this created an infinite loop and I taught them all that we needed to press <kbd>Ctrl</kbd>+<kbd>C</kbd> to interrupt Python in this case.

We also saw that we could use the keyword `break` to stop a loop within code.
For example, the loop below only ran once:

```py
while 1 > 0:
    print("1 is greater than 0. Wow.")
    break
```

## Lists and `for` loops

The next thing we did was define a list with square brackets:

```py
enjoyable_things = ["chocolate", "sunny days", "bad jokes"]
```

I then introduced a `for` loop as a type of loop that can be used to traverse a list; to go through all of the items in a list and do something with them:

```py
enjoyable_things = ["chocolate", "sunny days", "bad jokes"]

for thing in enjoyable_things:
    print(thing)
```

Running this code produced the following output:

```txt
chocolate
sunny days
bad jokes
```


## Importing other code

These were the main syntactic features of Python that we needed.
We also took a brief look at the statement `import` to bring code written by others into effect so that we could use it.
We played for a short while with a random number generator and saw that by typing `import random`, we could then use `random.randint` to generate a random integer:

```pycon
>>> import random
>>> random.randint(1, 6)
4
>>> random.randint(1, 6)
5
>>> random.randint(1, 6)
4
>>> random.randint(1, 6)
1
```

At this point I mentioned that I had also written some code that we would want to use in our lesson.
I used Python to create a way of representing a Sudoku board and we would use that representation, that abstraction, to solve the board with some Python code.
I tried to get them to download the code I had prepared but they managed to bring my site down.
This was the code they needed:

<script src="https://gist.github.com/rodrigogiraoserrao/df94d78864366bfaceb78d1488ec623c.js"></script>

## Playing with the Sudoku board

I told them to download the file and to open it with IDLE.
We'd keep working under everything I had already created and we would start by printing the variable `sudoku`, which was supposed to hold a Sudoku puzzle:

```py
print(sudoku)
```
```
┏━━━┯━━━┯━━━┳━━━┯━━━┯━━━┳━━━┯━━━┯━━━┓
┃ 5 │ 3 │   ┃   │ 7 │   ┃   │   │   ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 6 │   │   ┃ 1 │ 9 │ 5 ┃   │   │   ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃   │ 9 │ 8 ┃   │   │   ┃   │ 6 │   ┃
┣━━━┿━━━┿━━━╋━━━┿━━━┿━━━╋━━━┿━━━┿━━━┫
┃ 8 │   │   ┃   │ 6 │   ┃   │   │ 3 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 4 │   │   ┃ 8 │   │ 3 ┃   │   │ 1 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 7 │   │   ┃   │ 2 │   ┃   │   │ 6 ┃
┣━━━┿━━━┿━━━╋━━━┿━━━┿━━━╋━━━┿━━━┿━━━┫
┃   │ 6 │   ┃   │   │   ┃ 2 │ 8 │   ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃   │   │   ┃ 4 │ 1 │ 9 ┃   │   │ 5 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃   │   │   ┃   │ 8 │   ┃   │ 7 │ 9 ┃
┗━━━┷━━━┷━━━┻━━━┷━━━┷━━━┻━━━┷━━━┷━━━┛
```


## Checking for an empty cell

To start solving our puzzle, the first thing we needed to do was look for a vacant cell that we could try to fill.
Thankfully, the variable `sudoku` lets us type `sudoku.get_vacant_cell` to check for the first vacant cell in the puzzle:

```py
print(sudoku.get_vacant_cell())
```
```
<Cell(row=0, col=2)>
```

At this point we had to stop for a second so that I could explain how Python starts counting positions from 0 instead of one, so that the cell at row 0 and column 2 was actually the cell in the first row and third column, to the right of the 5 and the 3.

After finding a vacant cell, we need to find the values that could go in that cell.
For that, we use a different piece of code.


## Getting the possible values for a cell

After being in possession of a cell we'd like to fill, we need to save that cell into a variable and then use it in the function `sudoku.get_possible_values`, which returns a list of all the values we can consider for that cell:

```py
cell = sudoku.get_vacant_cell()
print(sudoku.get_possible_values(cell))
```
```
[1, 2, 4]
```

If you look at the puzzle above you will see that the values 1, 2, and 4, are the only ones that could be inserted in the first vacant cell.


## Putting a value on the board

The next step would be to pick a value from that list and putting it on the board.
We could do that with `sudoku.put`:

```py
cell = sudoku.get_vacant_cell()
new_sudoku = sudoku.put(cell, 1)
print(new_sudoku)
```
```
┏━━━┯━━━┯━━━┳━━━┯━━━┯━━━┳━━━┯━━━┯━━━┓
┃ 5 │ 3 │ 1 ┃   │ 7 │   ┃   │   │   ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 6 │   │   ┃ 1 │ 9 │ 5 ┃   │   │   ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
# elided...
```

Now that we know about `get_vacant_cell`, `get_possible_values`, and `put`, we need to bring all of these together to actually solve the Sudoku puzzle.


## Implementing the algorithm

In the first part of the lesson the students were introduced to an algorithm to solve a Sudoku puzzle:

1. Is there an empty cell in the puzzle?
  - If not, the puzzle is solved!
  - If yes, keep going.
2. What are the values that could go into that cell?
  - If there are _no_ possible values, that means you made a mistake previously and this isn't the solution to the puzzle.
  - If there is at least one value that could go into the cell, keep going.
3. Go through all of the possible values that could go in that cell, put them there, and go back to 1.

The way this algorithm works is by creating sort of a series of parallel universes in which we try to solve the puzzle with different numbers.
The code we write will solve all of these different puzzles in all of these alternate realities and as soon as we find a solution in one of the universes, we stop.

The code that implements the algorithm goes more or less like this:


### We keep going forever

We started by writing an infinite loop.
I claimed that we would use `break` later to stop it when needed, but that the outer loop would be an infinite one.
This infinite loop would be used to continuously inspect one of our many parallel realities where we were playing out a potential solution to the puzzle.
These solutions would all be stored in a list:

```py
parallel_universes = [sudoku]

while 1 > 0:
    this_sudoku = parallel_universes.pop()
```

`.pop` takes an item from the list, so we can use it to grab a Sudoku board to work with it.


### Is there an empty cell in the puzzle?

The first thing we need to do is check if we are looking at the parallel universe where we actually solve the puzzle.
We do that by checking if there is a vacant cell or not:

```py
parallel_universes = [sudoku]

while 1 > 0:
    this_sudoku = parallel_universes.pop()
    cell = this_sudoku.get_vacant_cell()
    if cell is None:
        print(this_sudoku)
        break
```

The line of code `if cell is None` does exactly what is written there: it checks if there are no vacant cells.
If there aren't, this is the solved puzzle and we can print it.
After printing it we can also stop our loop.

If there is a vacant cell, we need to keep working.


### What are the values that could go into that cell?

Now that we have a vacant cell we need to check the values that could go in there.
After we do so, we can use a `for` loop to create parallel universes where we try each one of the different options:

```py
parallel_universes = [sudoku]

while 1 > 0:
    this_sudoku = parallel_universes.pop()
    cell = this_sudoku.get_vacant_cell()
    if cell is None:
        print(this_sudoku)
        break
    # This is new:
    values = this_sudoku.get_possible_values(cell)
    for value in values:
        new_parallel_sudoku = this_sudoku.put(cell, value)
        parallel_universes.append(new_parallel_sudoku)
```

What's brilliant about this piece of code is that we don't even need to write anything specific to check the part of whether there aren't any possible values for the cell in question.
If the cell in question has no possible values, then the list `values` is empty and the `for` loop will do nothing, which organically discards this alternative universe because we reached a dead end.

If we run this code, we get the correct answer pretty much immediately:

```
┏━━━┯━━━┯━━━┳━━━┯━━━┯━━━┳━━━┯━━━┯━━━┓
┃ 5 │ 3 │ 4 ┃ 6 │ 7 │ 8 ┃ 9 │ 1 │ 2 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 6 │ 7 │ 2 ┃ 1 │ 9 │ 5 ┃ 3 │ 4 │ 8 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 1 │ 9 │ 8 ┃ 3 │ 4 │ 2 ┃ 5 │ 6 │ 7 ┃
┣━━━┿━━━┿━━━╋━━━┿━━━┿━━━╋━━━┿━━━┿━━━┫
┃ 8 │ 5 │ 9 ┃ 7 │ 6 │ 1 ┃ 4 │ 2 │ 3 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 4 │ 2 │ 6 ┃ 8 │ 5 │ 3 ┃ 7 │ 9 │ 1 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 7 │ 1 │ 3 ┃ 9 │ 2 │ 4 ┃ 8 │ 5 │ 6 ┃
┣━━━┿━━━┿━━━╋━━━┿━━━┿━━━╋━━━┿━━━┿━━━┫
┃ 9 │ 6 │ 1 ┃ 5 │ 3 │ 7 ┃ 2 │ 8 │ 4 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 2 │ 8 │ 7 ┃ 4 │ 1 │ 9 ┃ 6 │ 3 │ 5 ┃
┠───┼───┼───╂───┼───┼───╂───┼───┼───┨
┃ 3 │ 4 │ 5 ┃ 2 │ 8 │ 6 ┃ 1 │ 7 │ 9 ┃
┗━━━┷━━━┷━━━┻━━━┷━━━┷━━━┻━━━┷━━━┷━━━┛
```


## Conclusion

I had 45 minutes for my section of the lesson and I ended up talking for 60 minutes.
Even so, this was a lot of information for someone who had never written any code and while I hope I managed to show that with a couple of keywords and syntactic constructs we can already do something interesting, like solving a Sudoku, I also hope I did not give the students the impression that this was supposed to be trivial.

All in all, I had a blast teaching roughly 1750 students (the official record only recognises 1668 because some were disqualified) and some students told me afterwards that they enjoyed my lesson.
Most of those I think already had some programming experience.
I just hope I didn't scare the others who didn't.

![Photo of me looking to the side while holding the Guinness World Record certificate that attributes the record to the two institutions that organised the event.](_certificate.webp "Photo of me holding the certificate after the lesson.")
