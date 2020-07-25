---
title: Py-don't brainlessly reinvent the wheel
---

Say you want to write a Python script that prints the name of its parent directory. Would you come up with a solution like this?

```py
import re

r = r"(.*/)?(\w+)/\w*[.]\w+"
match = re.match(r, __file__)
if match:
    print(match.group(2))
```

This might not be the best solution for you, it doesn't even handle Windows paths with backslashes `\`...

===

![two wooden wheels against a wall](wheel.jpg "Photo by Jon Cartagena on Unsplash")

I would argue a more pythonic solution would be:

```py
from pathlib import Path

path = Path(__file__)
print(path.parts[-2])
```

Remember that [_simple is better than complex_][zen-of-python] and this `import` seems simple enough!

Python comes with a _huge_ standard library, for example following [this link][py3.8 docs] you can find out about all the modules that come shipped your vanilla Python installation, you don't even need to install anything else. There are plenty of common tasks that are already implemented for us in the standard library so that we don't need to keep reinventing the wheel left and right!

Please don't get me wrong, reinventing the wheel can be a great learning exercise and can also be quite fun! But it may also be not the _best_ way forward when you are on a tight deadline or really need to write robust, easily maintainable code. Leveraging on the great standard library that ships with Python gives you more time to focus on what you _really_ need to write from scratch.

I have found myself reinventing the wheel left and right for all sorts of things... Sometimes I only think of googling to check for the existence of a Python module that does what I am doing after I repeat myself a couple of times... I think most of us could try to be more active in reusing all the great code that is already out there... And don't even get me started on _all_ the code we can have access to if we look at [PyPI], for example.

Maybe you think importing from the standard library or from some other packages you installed separately makes your code less pure but you also need to remember that [_practicality beats purity_][zen-of-python].

You already got the gist of this py-don't, but I will give a couple more examples of situations in which it is good/bad to reinvent the wheel, just to get my point across.

Let's talk about parsing command-line arguments. Writing a Python program to parse command-line arguments is a great exercise for you to flex your Python skills! But if you are writing a Python program that does some task X or Y and you want it to be usable from the command-line, then what do you think sounds more productive?

 - Go with `import argparse` and [read the `argparse` HOWTO][argparse howto] to quickly prototype the CLI you need for your program;
 - Stop working on your important program for quite some time because you have to write all these functions to parse command-line arguments.

Similarly, writing a fully-functional numerical matrix data type is not something you get done in fifteen minutes and is a really nice exercise to train your programming skills and maybe even your linear algebra if you implement things like Gauss-Jordan elimination and matrix inverse... _But_ if you have to hand in a report about some numerical method you studied in class (like I had to do a couple of times) then I would recommend you just go with `import numpy` because `numpy` is fast and reliable and you will be able to focus on the things that really matter for _that_ project.

Do you agree with me? Let me know in the comments below.

And don't forget to share this with all your friends who know/are learning Python!

[py3.8 docs]: https://docs.python.org/3/library/index.html
[argparse howto]: https://docs.python.org/3/howto/argparse.html
[PyPI]: https://pypi.org
[zen-of-python]: ../pydont-zen-of-python