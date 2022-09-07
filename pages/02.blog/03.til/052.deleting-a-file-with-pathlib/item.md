Today I (re)learned how to delete a file in Python with the module pathlib.

===

![A colourful background with the word “unlink” big and centre](thumbnail.png)

# Module `pathlib`

The module `pathlib` is one of the modules I use the most,
but I keep forgetting how to delete files with it.
I always expect a method `remove` or `rm` to exist,
especially because the method `rmdir` is the method that removes directories.

Alas, the `pathlib.Path` method to remove a file is `unlink`!

## How to delete a file using `pathlib`?

If you have a path `filepath` that points to an existing file,
then `pathlib.Path(filepath).unlink()` will remove that file:

```py
>>> from pathlib import Path
>>> filepath = Path("myfile.txt")  # File I want to delete.

>>> filepath.exists(), filepath.is_file()  # The file exists for now...
(True, True)

>>> filepath.unlink()  # Delete the file 💣

>>> filepath.exists(), filepath.is_file()  # The file no longer exists.
(False, False)
```


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
