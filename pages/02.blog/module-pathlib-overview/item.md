Learn some of the most common and useful features of the `pathlib` module that allows you to manipulate files and directories.

===


## Introduction

The module `pathlib` is one of my favourite modules from the standard library.
It is my go-to tool whenever I need to deal with and manipulate files and directories.

Earlier today, at the time of writing, I had to rename hundreds of files inside a folder.
I didn't even know exactly how many files I needed to rename.
What I needed to do was find all files that had a `.css` extension and change it to `.tcss`.

I solved it with these three lines of code, directly from the REPL:

```py
from pathlib import Path
for file in Path(".").rglob("*.css"):
    file.rename(file.with_suffix(".tcss"))
```

That was it!
Pretty powerful.

Let me give you a brief introduction to `pathlib`.
I'll explain what just happened in the code above and I will also show a couple of other commonly useful tools.


## The class `Path`

It all starts with the class `Path`.
The class `Path` creates instances of paths: objects that represent directories and file paths in your filesystem.

The class `Path` lets you manipulate _real_ files and directories and it can effect _real_ changes: for example, actually rename files, like I mentioned above.

For example, the code below could be used to rename a file `file_1.txt` as `my_file.txt`:

```py
from pathlib import Path
Path("file_1.txt").rename("my_file.txt")
```

Note that you instantiate `Path` but `pathlib` will create one of two objects for you:

 - `WindowsPath` if you're on a Windows machine; or
 - `PosixPath` otherwise.

You should always instantiate `Path` and let `pathlib` figure out which type to use.
This will make sure your code is portable and can run on other machines.


## Useful path attributes

Paths have many useful attributes that let you access important pieces of information associated with each path.
There are [ten such attributes](https://docs.python.org/3/library/pathlib.html#methods-and-properties), but the two most commonly used and useful are `.name` and `.parent`.

### Attribute `.name`

The attribute `.name` (which really is a [property]) will give you the name of the file or folder that the path refers to:

```py
>>> from pathlib import Path
>>> Path("/this/is/a/path/to/a/file.txt").name
'file.txt'
>>> Path("/this/is/a/path/to/a/folder").name
'folder'
```

### Attribute `.parent`

On the other hand, the attribute `.parent` (which is also a [property]) retrieves the logical parent of the path.
It is more or less what you'd get if you dropped the `.name` from the path:

```py
>>> from pathlib import Path
>>> Path("/this/is/a/path/to/a/file.txt").parent
PosixPath('/this/is/a/path/to/a')
>>> Path("/this/is/a/path/to/a/folder").parent
PosixPath('/this/is/a/path/to/a')
```

!!! While `.name` returns a string with the last part of a path, `.parent` returns **another `Path` instance**, which means you can use `.parent` to navigate up the file hierarchy.


## Navigating the filesystem with `/`

I just showed you how to navigate up the filesystem with `.parent`.
The operator `/`, which is typically used for division with integers and floats, can be used to concatenate file paths.

```py
>>> from pathlib import Path
>>> Path("/this") / "is" / "a" / "path"
PosixPath('/this/is/a/path')
```

You can also use `..` to navigate up the filesystem, in which case you may want to use the method `resolve` to get the final path:

```py
>>> from pathlib import Path
>>> Path("/this/is/a/path/tooo") / ".." / "to/a/file.txt"
PosixPath('/this/is/a/path/tooo/../to/a/file.txt')
>>> (
...     Path("/this/is/a/path/tooo") / ".." / "to/a/file.txt"
... ).resolve()
PosixPath('/this/is/a/path/to/a/file.txt')
```


## Useful path methods


### Does the path point to an existing file or directory?

The answer: `.exists`.
The method `.exists` returns `True` if the file/directory exists and returns `False` otherwise.
Simple as that.


### Changing parts of the path

If you have a path that you want to modify slightly to create a new path, you may use the `.with_X` methods of path objects.

For example, the code in the introduction used the method `with_suffix`, which essentially changes the extension of a file path:

```py
>>> from pathlib import Path
>>> Path("file")
PosixPath('file')
>>> Path("file").with_suffix(".txt")
PosixPath('file.txt')
>>> Path("file.txt").with_suffix(".csv")
PosixPath('file.csv')
```

There are three such methods:

 - `with_name`: change the name of a path;
 - `with_stem`: change the stem of a path (the name, minus the extension) (Python 3.9+); and
 - `with_suffix`: change the suffix (extension) of a path.


### Renaming files and folders

If you want to rename a file or a folder, you can use the method `rename`.
That's what I used above to rename hundreds of files.
If you are renaming your file/folder to a name `target` that already exists, you can use `.replace(target)` instead, and it will overwrite the existing target.


### Creating files and directories

To create a file you'll want to use the method `.touch`.
If you want to create a directory, you'll want to use the method `.mkdir`.
Both methods raise a `FileExistsError` error if the thing you're trying to create already exists.

If you specify the argument `exist_ok=True`, then the methods won't raise an error if the file/directory already exists.


### Searching for files

The final functionality that I used in my two lines of code above and that I didn't explain yet is the method `.rglob`.
The methods `.glob` and `.rglob` look for files in a given path that match a “glob pattern”.

The glob pattern `*.css` finds all files that end with the extension `.css`.
The call `.glob("*.css")` finds `.css` files in the folder specified.
The call `.rglob("*.css")` finds `.css` files in the folder specified and recurses into other directories.

Suppose we have this file structure:

```
folder
|- one.css
|- two.css
|- sub
   |- three.css
```

Then, the code would work as follows:

```py
>>> from pathlib import Path
>>> list(Path("folder").glob("*.css"))
[PosixPath('css/one.css'), PosixPath('css/two.css')]

>>> list(Path("folder").rglob("*.css"))
[
    PosixPath('css/one.css'),
    PosixPath('css/two.css'),
    PosixPath('css/sub/three.css'),
]
```


## Find the folder a script is in

As a bonus tip, I want to share with you a small expression I use quite often in my scripts, which lets me figure out the folder a script is running in:

```py
from pathlib import Path
folder = Path(__file__).parent
```

If you are in a Python script (note this doesn't work in the REPL and I think it also doesn't work in notebooks), then `__file__` is a dunder attribute that is the path of the file itself, so `Path(__file__).parent` is the folder your script is running in.

I use this very often when I need to access other folders that live next to the script.
For example, to access the folder `res` that lives next to the script `my_script.py` in the following hierarchy:

```
some_project
|- res
   |- ...
my_script.py
```

I can get to `res`, from within `my_script`, with `Path(__file__).parent / "res"`.

[property]: /blog/pydonts/properties
