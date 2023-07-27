Today I learned how to customise the Python REPL on start-up.

===


# How to customise the Python REPL on start-up

Customising the Python REPL on start-up allows you to run custom code whenever you open the Python REPL.
Typically, people use this to import modules they use commonly or define functions that they use a lot.

To customise the Python REPL, you need two things:

 1. You need to create the file `.pythonrc` in which you write the code you want to run when the REPL starts.
 2. You need to set the environment variable `PYTHONSTARTUP` to point to the file.

Then, when the REPL starts, it will look at the environment variable `PYTHONSTARTUP` and if it is set to something, it will run the Python code in that file.


## Example `.pythonrc` file

My `.pythonrc` file is very simple and looks like this:

```py
import rich

help = rich.inspect
print = rich.print
```

It imports the [module `rich`](https://rich.readthedocs.io) to override two useful built-ins (`help` and `print`) with two versions that are even more helpful and colourful: `rich.inspect` and `rich.print`.

That way, whenever I use `help` or `print` in the REPL (which happens A LOT), I automatically use the version from the library `rich`, which I am really fond of.

If you want to have the same `.pythonrc` file, just be sure to install `rich` with `python -m pip install rich`.


## Setting the environment variable `PYTHONSTARTUP`

To set your environment variable `PYTHONSTARTUP` to point to your `.pythonrc` file, you can run the command

```bash
set PYTHONSTARTUP=C:\path\to\your\file\.pythonrc  # Windows
```

```bash
export PYTHONSTARTUP=/path/to/your/file/.pythonrc  # MacOS / Linux
```

To make sure everything is working, add a `print("hello world")` to your file `.pythonrc` and open the shell.
You should see your greeting.

If you want this configuration to be set permanently, what you can do is figure out the file that your shell uses for start-up customisation.
For example, when my shell starts, it runs the commands found in the file `~/.zshrc`.
So, if I add the line `export PYTHONSTARTUP=/path/to/your/file/.pythonrc` to my file `~/.zshrc`, then `python` will always know where to find the file `.pythonrc`.


## Backup your `.pythonrc` file

I first heard of the `.pythonrc` file when reading [Adam Johnson's “Boost Your Git DX”][boost-git-dx][^1], where Adam suggests that configuration files like these (as long as they don't have sensitive data, like credentials) could be kept in a GitHub repository.
Then, you just create a symlink from the typical location `~/.pythonrc` to the versioned file in your repository.

This way, when you change computers, you have all your configurations ready to be cloned from the repo and they're easier to setup on your new machine.


[boost-git-dx]: https://gumroad.com/a/817193683/wlrcr
[^1]: If you buy this book, I may get an affiliate commission because I referred you to it, but this comes at no extra cost to you.


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
