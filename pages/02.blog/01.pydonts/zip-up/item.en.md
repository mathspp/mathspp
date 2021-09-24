---
metadata:
    description: "`for` loops are the bread and butter of imperative programming and Python has some really nice tools to work with them. If you want to traverse several structures in parallel, have you considered using `zip`?"
title: "Zip up | Pydon't 🐍"
---

`for` loops are the bread and butter of imperative programming
and Python has some really nice tools to work with them.
If you want to traverse several structures in parallel,
have you considered using `zip`?

===

![A Python code snippet showing a basic use of `zip`](thumbnail.png)

(If you are new here and have no idea what a Pydon't is, you may want to read the
[Pydon't Manifesto][manifesto].)


# Introduction

One of the things I appreciate most about Python, when compared to other programming
languages, is its `for` loops.
Python allows you to write very expressive loops,
and part of that is because of the built-in `zip` function.

In this article you will

 - see what `zip` does;
 - get to know a new feature of `zip` that is coming in Python 3.10;
 - learn how to use `zip` to create dictionaries; and
 - see some nice usage examples of `zip`.


# How `zip` works

In a simple `for` loop, you generally have an iterator `it` and you just write
something like

```py
for elem in it:
    # Do something with elem
    print(elem)
```

An “iterator” is something that can be traversed linearly, of which a list
is the simplest example.
Another very common iterator used in Python's `for` loops is a `range`:

```py
for n in range(10):
    print(n**2)
```

Sometimes you will have two or more iterators that contain related information,
and you need to loop over those iterators to do something with the different
bits of information you got.

In the example below, we have a list of first and last names of people
and we want to print the full names.
The naïve solution would be to use a `range` to traverse all the indices
and then index into the lists:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans"]
>>> for i in range(len(firsts)):
...     print(f"'{firsts[i]} {lasts[i]}'")
...
'Anna Smith'
'Bob Doe'
'Charles Evans'
```

This does the job, but a `for` loop like this only hints at the fact that you
are probably going to access the values in `firsts`, because you wrote

```py
range(len(firsts))
```

but turns out you also want to access the items in `lasts`.
This is what `zip` is for: you use it to pair up iterables that you wanted
to traverse at the same time:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans"]
>>> for first, last in zip(firsts, lasts):
...     print(f"'{first} {last}'")
... 
'Anna Smith'
'Bob Doe'
'Charles Evans'
```

Notice that you can specify two iterating variables in the `for` loop,
in our case `first` and `last`, and each variable will take the successive
values of the respective iterator.

This is a special case of an unpacking assignment,
because `zip` is actually producing tuples with the names in them:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans"]
>>> for z in zip(firsts, lasts):
...     print(z)
...
('Anna', 'Smith')
('Bob', 'Doe')
('Charles', 'Evans')
```

What we are doing is taking that tuple and assigning each portion:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans"]
>>> for z in zip(firsts, lasts):
...     first, last = z
...     print(f"'{first} {last}'")
...
'Anna Smith'
'Bob Doe'
'Charles Evans'
```

But instead of the intermediate step, we unpack right in the `for` statement.
This unpacking, tied with good naming of variables, allows `for` loops to be
read in plain English.

For example, the loop from before was

```py
for first, last in zip(firsts, lasts):
```

and that can be read as

 > “For each `first` and `last` [name] in the lists `firsts` and `lasts`...”


# Zip is lazy

One thing to keep in mind is that `zip` doesn't create the tuples immediately.
`zip` is lazy, and that means it will only compute the tuples when you ask
for them, for example when you iterate over them in a `for` loop
(like in the examples above)
or when you convert the `zip` object into a `list`:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans", "Rivers"]
>>> z = zip(firsts, lasts)
>>> z
<zip object at 0x0000019F56702680>
>>> list(z)
[('Anna', 'Smith'), ('Bob', 'Doe'), ('Charles', 'Evans')]
```

`zip` being lazy also means that `zip` by itself isn't _that_ similar to a list.
For example, you cannot ask what is the length of a zip object:

```py
>>> len(z)
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
TypeError: object of type 'zip' has no len()
```


# Three is a crowd

We have seen `zip` with two arguments, but `zip` can take an arbitrary
number of iterators and will produce a tuple of the appropriate size:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> middles = ["Z.", "A.", "G."]
>>> lasts = ["Smith", "Doe", "Evans"]
>>> for z in zip(firsts, middles, lasts):
...     print(z)
...
('Anna', 'Z.', 'Smith')
('Bob', 'A.', 'Doe')
('Charles', 'G.', 'Evans')

>>> prefixes = ["Dr.", "Mr.", "Sir"]
>>> for z in zip(prefixes, firsts, middles, lasts):
...     print(z)
...
('Dr.', 'Anna', 'Z.', 'Smith')
('Mr.', 'Bob', 'A.', 'Doe')
('Sir', 'Charles', 'G.', 'Evans')
```

# Mismatched lengths

`zip` will _always_ return a tuple with as many elements as the arguments
it received, so what happens if one of the iterators is shorter than the others?

If `zip`'s arguments have unequal lengths, then `zip` will keep going until
it exhausts one of the iterators.
As soon as one iterator ends, `zip` stops producing tuples:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans", "Rivers"]
>>> for z in zip(firsts, lasts):
...     print(z)
...
('Anna', 'Smith')
('Bob', 'Doe')
('Charles', 'Evans')
```

Starting with Python 3.10, `zip` will be able to receive a keyword argument
named `strict` that you can use to tell `zip` to error if the lengths
of the iterators do not match:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans", "Rivers"]
>>> for z in zip(firsts, lasts, strict=True):   # strict=True available in Python >= 3.10
...     print(z)
...
('Anna', 'Smith')
('Bob', 'Doe')
('Charles', 'Evans')
Traceback (most recent call last):
  File "<stdin>", line 1, in <module>
ValueError: zip() argument 2 is longer than argument 1
```

Notice that `zip` only errors _when_ it finds the length mismatch,
it doesn't do the check in the beginning:
this is because the arguments to `zip` may themselves be lazy
iterators.

! (Lazy) iterators will be covered in further Pydon'ts,
! so be sure to [subscribe] to the Pydon't newsletter to stay tuned!

In general, `zip` is used with iterators that are _expected_ to have the same length.
If that is the case – if you expect your iterators to have the same length –
then it is a good idea to always set `strict=True`, because that will help you
catch bugs in your code.


# Create a dictionary with `zip`

You can create dictionaries in Python by feeding key-value pairs
to the `dict` function, which means `zip` is a prime way of
creating dictionaries when you have all the keys in an iterator
and all the values in another iterator:

```py
>>> firsts = ["Anna", "Bob", "Charles"]
>>> lasts = ["Smith", "Doe", "Evans"]
>>> dict(zip(firsts, lasts))
{'Anna': 'Smith', 'Bob': 'Doe', 'Charles': 'Evans'}
```

# Examples in code

Now you will see some usages of `zip` in actual Python code.


## Snake game

A friend of mine is learning Python and he started creating a replica
of the game of Snake.
There is a certain point in the game where he has a menu and he wants
to display thumbnails of the “maps” that that can be played on,
and he has those images in a list called `lvlpictures`.
At the same time, he has the positions of where those images should go
in a list called `self.buttons`.
In order to display the thumbnails in the correct positions,
he has to call a function called `blit`, which expects the image
and the position the image should go to.

Here are the loops he wrote before and after knowing about `zip`.

```py
# Before:
for i in range(len(lvlpictures)):
    self.surface.blit(lvlpictures[i], (self.buttons[i][0]+2,self.buttons[i][1]+2))

# Then he learned about `zip`:
for pic, btn in zip(lvlpictures, self.buttons):
    self.surface.blit(pic, (btn[0] + 2, btn[1] + 2))
```

Notice that using `zip` makes your code shorter and it also makes more
clear the intent of processing the pictures and the buttons together.
Finally, when Python 3.10 is released, he may even add the `strict=True`
keyword argument, because he expects `lvlpictures` and `self.buttons`
to have the same length.


## Matching paths

If you are not aware of it, then you might be interested in knowing
that Python has a module named [`pathlib`][pathlib] that provides
facilities to deal with filesystem paths.

When you create a path, you can then check if it matches a given pattern:

```py
>>> from pathlib import PurePath
>>> PurePath('a/b.py').match('*.py')
True
>>> PurePath('/a/b/c.py').match('b/*.py')
True
>>> PurePath('/a/b/c.py').match('a/*.py')
False
```

If you take a look at this `match` function, you find this:

```py
class PurePath(object):
    # ...

    def match(self, path_pattern):
        """
        Return True if this path matches the given pattern.
        """
        # code omitted for brevity
        for part, pat in zip(reversed(parts), reversed(pat_parts)):
            if not fnmatch.fnmatchcase(part, pat):
                return False
        return True
```

The code omitted does some checks that allow the function to tell right away
that there is no match.
The `for` loop that I am showing you makes use
of `zip` to pair each part of the path with each part of the pattern,
and then we check if those match with `fnmatch.fnmatchcase`.

Try adding a couple of prints here:

```py
class PurePath(object):
    # ...

    def match(self, path_pattern):
        """
        Return True if this path matches the given pattern.
        """
        # code omitted for brevity

        print(parts)      # added by hand to check what is going on.
        print(pat_parts)  # same here.
        for part, pat in zip(reversed(parts), reversed(pat_parts)):
            if not fnmatch.fnmatchcase(part, pat):
                return False
        return True
```

And then rerun the examples from the documentation:

```py
>>> from pathlib import PurePath
>>> PurePath('a/b.py').match('*.py')
['a', 'b.py']   # parts
['*.py']        # pat_parts
True
>>> PurePath('/a/b/c.py').match('b/*.py')
['\\', 'a', 'b', 'c.py']
['b', '*.py']
True
>>> PurePath('/a/b/c.py').match('a/*.py')
['\\', 'a', 'b', 'c.py']
['a', '*.py']
False
```

It should become clearer what `parts` and `pat_parts` actually do,
and it should become clearer why we `zip` them up together.

This is a nice example of when using `strict=True` makes no sense,
because it may happen that the path and the pattern have a different
number of parts, and that is perfectly fine.


## Writing a CSV file

The Python Standard Library comes with a module, [`csv`][csv], to read and write CSV files.
Among other things,
it provides you with the classes `DictReader` and `DictWriter` for when you want to
use the header of the CSV file to read the data rows like dictionaries or for when you
have the data rows as dictionaries.

Here is an example of how you might take several dictionaries and write them as a CSV file:

```py
import csv

with open('names.csv', 'w', newline='') as csvfile:
    fieldnames = ['first_name', 'last_name']
    writer = csv.DictWriter(csvfile, fieldnames=fieldnames)

    writer.writeheader()
    writer.writerow({'first_name': 'Baked', 'last_name': 'Beans'})
    writer.writerow({'first_name': 'Lovely', 'last_name': 'Spam'})
    writer.writerow({'first_name': 'Wonderful', 'last_name': 'Spam'})
```

The `fieldnames` variable will establish the header of the CSV file and is then used
by the `writerow` method to know the order in which the values of the dictionary
should be written in the file.

The `writeheader` function is the function that writes the header of the CSV file,
and here is what it looks like:

```py
class DictWriter:
    # ...

    def writeheader(self):
        header = dict(zip(self.fieldnames, self.fieldnames))
        return self.writerow(header)
```

Basically, what this function is doing is using `zip` to transform the header
names into a dictionary where the keys and the values are the same,
pretending that the header is just a regular data row:

```py
>>> fieldnames = ['first_name', 'last_name']
>>> dict(zip(fieldnames, fieldnames))
{'first_name': 'first_name', 'last_name': 'last_name'}
```

Therefore, the `writeheader` function just needs to create this dictionary
and can then defer the actual _writing_ to the `writerow` function.


# Conclusion

Here's the main takeaway of this article, for you, on a silver platter:

 > “`zip` is your friend whenever you need to traverse two or more iterables
 > at the same time.”

This Pydon't showed you that:

 - `zip` can be used to traverse several iterables at the same time;
 - `zip` by itself returns a `zip` object which must then be iterated or converted explicitly to a `list`
if you want the tuples it produces;
 - if the arguments to `zip` have uneven lengths, `zip` will stop as soon as one of the iterators is exhausted;
 - starting with Python 3.10, you can use the keyword argument `strict=True` to tell `zip` to error if the
arguments to `zip` have different lengths; and
 - `zip` can provide for a really simple way to create dictionaries.

If you liked this Pydon't be sure to leave a reaction below and share this with your friends and fellow Pythonistas.
Also, [don't forget to subscribe to the newsletter][subscribe] so you don't miss
a single Pydon't!


[subscribe]: https://mathspp.com/subscribe
[manifesto]: /blog/pydonts/pydont-manifesto
[csv]: https://docs.python.org/3/library/csv.html
[pathlib]: https://docs.python.org/3/library/pathlib.html
