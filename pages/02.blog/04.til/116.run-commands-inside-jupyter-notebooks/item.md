Today I learned that I can use the percent sign to run commands from inside Jupyter notebooks.

===


# Run commands inside Jupyter notebooks

If you are running a Jupyter notebook you can use the percent sign `%` to run “magic commands”, namely shell commands you already probably use.

For example, you can use `%pwd` to check the directory you're in, you can use `%ls` to list the current directory, you can use `%cd` to change the current directory, and you can use `%cat` to print the contents of a file.

Below you can see an example of me use this to print out a short Python script, `writer.py`, that doesn't even make much sense:

```
[1] %cd ~/Documents/tmp
/Users/rodrigogs/Documents/tmp

[2] %pwd
'/Users/rodrigogs/Documents/tmp'

[3] %ls
__pycache__/          lt.py                 tests.py
bezier.py             marta.py              tree/
card.html             oop.ipynb             tree.mp4
cd.py*                output.gif            tree.png
cm.py                 output.mp4            tree.py
dataclasses.ipynb     palette.png           tree1.py
dunder-methods.ipynb  piece_table.py        tree2/
esc42.py              receipt.txt           tree2.py
fp.py                 review_card.png       tree3.py
frames/               rot/                  webinar.py
greputils/            rotate/               writer.py
hello.py              santa.py

[4] %cat writer.py
from itertools import count

for number in range(100):
    print(number)
```

What's even more cool is the command `%load`, which will take a path to a file and it will load the contents of that file into the cell.

So, if I run `%load writer.py`, then the source code of the file `writer.py` is loaded into the cell so that I can run it!
After running `%load writer.py` in a cell, the cell turns into this:

```py
# %load writer.py
from itertools import count

for number in range(100):
    print(number)
```
