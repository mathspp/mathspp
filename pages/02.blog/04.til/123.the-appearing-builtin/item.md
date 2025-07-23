Today I learned that the module `builtins` grows dynamically in the REPL.

===


## The appearing built-in

After [some back and forth on BlueSky](https://bsky.app/profile/klaus.seistrup.dk/post/3loj3oimjj42n), I tripped on some funky behaviour in the REPL:

```pycon
## Fresh new REPL, Python 3.13:
>>> import builtins
>>> len(dir(builtins))
159
>>> len(dir(builtins))
160
>>> len(dir(builtins))
160
```

What the heck is going on?
Why did the module `builtins` grow in size between the two calculations?!

I restarted the REPL and tried something else:

```pycon
## Fresh new REPL, Python 3.13:
>>> import builtins
>>> print(dir(builtins))
[ ... ]
>>> print(dir(builtins))
[ ... ]
```

I printed the contents of the module `builtins` twice in a row and they were exactly the same...
Then, I checked, and the length of the module `builtins` was still 159:

```pycon
>>> len(dir(builtins))
159
```

But then I checked again, and it grew again:

```pycon
>>> len(dir(builtins))
160
```

For a second I was left wondering what sort of magic trick the built-in `len` must be playing, since it looks like it's making the module `builtins` grow another member...
But then I printed the contents of the module again and I found the culprit after using a text diff tool online:

![A screenshot of a text diff comparison where the text on the right shows that the name "_" is new.](_diff.webp "The name `_` appears magically.")

That's when it hit me: [the REPL has a special name `_` that refers to the result of the last operation](/blog/pydonts/usages-of-underscore#recovering-last-result-in-the-session).

Importing the module isn't enough to make it show up because it's a statement and statements don't produce a value.
Similarly, the code `print(dir(builtins))` also isn't enough because printing “doesn't return a result”, but when I run `len(dir(builtins))`, the length is saved in `_`.

But why is this variable in the module `builtins` if other variables aren't..?

I am not entirely sure, but my guess is that this special `_` isn't a regular variable!
You can assign to `_` and it will “overwrite” the other value:

```pycon
## Fresh new REPL:
>>> 1 + 2
3
>>> _
3
>>> _ = 73
>>> print(_)
73
```

But it's not really overwriting the previous value...
It's actually shadowing the other one!
Here's proof:

```pycon
## Fresh new REPL:
>>> 1 + 2
3
>>> _
3
>>> _ = 73
>>> print(_)
73
>>> import builtins
>>> builtins._
3
>>> globals()["_"]
73
```

Fun, right?
