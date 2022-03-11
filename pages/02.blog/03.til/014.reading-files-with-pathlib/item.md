Today I learned you can use `pathlib` to read the contents of a file.

===

<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>

![](thumbnail.png "Photo by Mr Cup / Fabien Barral on Unsplash")


# Reading files with `pathlib`

[`pathlib`][pathlib] is an amazing module from the Python 3 Standard Library.

I have been tweeting about `pathlib` and recently I shared a mini cookbook
on it:

<blockquote class="twitter-tweet"><p lang="en" dir="ltr">The Python 🐍 Standard Library is one of the reasons I love 💙 Python.<br><br>📂🔍 dealing with your filesystem is super simple.<br><br>All you have to do is use the `pathlib` module.<br><br>This short thread is a mini `pathlib` cookbook 🍳, showing some example usages of `pathlib`.<br><br>Ready 🚀? <a href="https://t.co/kWacRATY1w">pic.twitter.com/kWacRATY1w</a></p>&mdash; Rodrigo 🐍📝 (@mathsppblog) <a href="https://twitter.com/mathsppblog/status/1456909237548826625?ref_src=twsrc%5Etfw">November 6, 2021</a></blockquote>

Adam Johnson proceeded to comment on that thread,
telling me about two methods I didn't know: `.read_text` and `.read_bytes`.

As it turns out, `pathlib.Path` can also be used to read the full contents of a file!

Here is an example text file I have in `C:/tmp/foo.txt`:

```txt
This
is

just
some
text!
```

Using `pathlib` to read it is easy:

```py
>>> from pathlib import Path
>>> Path("C:/tmp/foo.txt").read_text()
'This\nis\n\njust\nsome\ntext!'
>>> Path("C:/tmp/foo.txt").read_bytes()
b'This\r\nis\r\n\r\njust\r\nsome\r\ntext!'
```

It's interesting to notice, above,
how the `.read_text` method only returns `"\n"` for newlines
but `.read_bytes` returns `"\r\n"` on each newline
(I'm using a Windows machine).


That's it for now! [Stay tuned][subscribe] and I'll see you around!


[subscribe]: /subscribe
[pathlib]: https://docs.python.org/3/library/pathlib.html
