This practical tutorial shows how to use uv to build and install custom Python CLI applications globally on your system.

===

# Using uv to build and install Python CLI apps

I find myself writing Python scripts that automate certain parts of my work or life and then I want to turn them into commands in my system.
This short tutorial will show you how I do that using uv.

You can build and install a command (that's a Python CLI app) globally in your system in 5 easy steps:

1. Install uv [following the installation instructions from the uv documentation](https://docs.astral.sh/uv/getting-started/installation/).
2. Start a Python project managed by uv by running `uv init --app --package myproj` and cd into it.
3. Write the code for your CLI in the source folder `src/myproj` that is created.
4. Install the project `myproj` globally with `uv tool install . -e`.
5. Run your CLI `myproj` from anywhere on your system.

That's it.
Really.
Go and have fun!

In the remainder of the article, I will explain all the steps in a bit more detail by working through a small example.


## Preamble

The CLI app we will build is a tiny and basic clone of the command line utility `wc`.
Our version of `wc` will take a file path and it will output the number of bytes, words, and lines, in that file.
You can also use any combination of the options `-c`, `-w`, and `-l`, to ask for the respective pieces of information.

Below you can find a sample implementation of the function that implements this functionality:

```py
from pathlib import Path

def wc(filepath: Path, show_bytes: bool, show_words: bool, show_lines: bool) -> None:
    content = filepath.read_bytes()
    byte_count = len(content)
    word_count = len(content.split())
    line_count = len(content.splitlines())
    output = (
        (f"{line_count:8}" if show_lines else "")
        + (f"{word_count:8}" if show_words else "")
        + (f"{byte_count:8}" if show_bytes else "")
        + f" {filepath}"
    )
    print(output)

if __name__ == "__main__":
    wc(Path(__file__), True, True, True)
```

If you take this code, paste it into a file `mywc.py`, and then run it, you'll get the following output (or similar):

```
      18      60     547 mywc.py
```

This means the file `mywc.py` has 18 lines, 60 words, and 547 bytes.
Now, you want to use uv to somehow make this function globally available as a CLI app in your system.
This is where the five steps above come into play.


## Step 1: installing uv

uv is a Python package and project manager and you will use it for two things in this short tutorial:

1. you will use it to create a project where you will write the code for your CLI app; and
2. you will use it to install your own CLI app as a command in your system.

There are a lot of things you can do with uv and this tutorial will only scratch the surface.
I recommend you take a look at the [uv documentation](https://docs.astral.sh/uv/) to learn more.

To install uv, simply [follow the installation instructions in the uv documentation](https://docs.astral.sh/uv/getting-started/installation/).


## Step 2: initialise a project

For uv, a project is a folder that contains source code, settings, metadata, documentation, and more, all belonging or relating to the same... project.
To initialise a project called `myproj` with uv, you use the command `uv init myproj`.
For your CLI app, you'll want to run `uv init` with a couple of extra options:

```sh
uv init --app --package myproj
```

The options `--app` and `--package` tell uv to set things up in a more convenient way for you:

 - `--app` tells uv that you will write an application, a piece of code that runs and does something (think of a webserver). This is in contrast with the option `--lib`, where you tell uv that you want to write code that will be imported to be used by others (think of the module `itertools`).
 - `--package` tells uv that you will want to build a package out of your code to distribute it, which is what makes it installable. In your case, you want to be able to install your code in your own system. (And possibly share it online so others can install it as well.) This is in contrast with the option `--no-package`, that does not set this up for you.

Note that these options don't do anything magical that you wouldn't be able to do yourself.
They just make life slightly easier for you.

After you run the command `uv init --app --package myproj`, uv should create a directory `myproj` that should look like this:

```
myproj
  |-pyproject.toml
  |-README.md
  |-.gitignore
  |-.python-version
  |-.git
  |-src
  |  |-myproj
  |  |  |-__init__.py
```

The file `pyproject.toml` holds metadata about your project (e.g., author name or project name) and the file `.python-version` tells uv and other tools what Python version to use.
If the version in `.python-version` isn't the one you want, you can manually edit this (people will hate me for telling you to do this manually!) or you can go back in time and create the project with your preferred Python version by specifying when you run `uv init`.
The example below creates the project with Python 3.14:

```sh
uv init --app --package --python 3.14 myproj
```


## Step 3: write the code for the CLI

If steps 1 and 2 worked out ok, you should have a file `__init__.py` inside `src/myproj`.
This file should contain a short function `main` with a call to the function `print`.
Something like this:

```py
# __init__.py as created by uv
def main() -> None:
    print("Hello from myproj!")
```

This function `main` is very important because it is what uv will run when you tell it to run your project.
Make sure you're in the root folder of your project, the one with the readme file, and `pyproject.toml`, and the others, and try running your project with `uv run myproj`.
You should see the output of the call to `print`:

```sh
❯ uv run myproj
Hello from myproj!
```

This shows you that, by default, the code you want to run in your command needs to go in the function `main`.
For the example you and I are working with, I'll just paste the function `wc` in the file `__init__.py` and rewrite `main` to provide [a CLI interface to it using `click`](https://click.palletsprojects.com/en/stable/):

```py
# __init__.py after edited by you

from pathlib import Path

import click  # <- click dependency

def wc(filepath: Path, show_bytes: bool, show_words: bool, show_lines: bool) -> None:
    ... # Same as above.

@click.command()
@click.argument("filepath", type=Path)
@click.option("-c", is_flag=True, help="Show byte count.")
@click.option("-w", is_flag=True, help="Show word count.")
@click.option("-l", is_flag=True, help="Show line count.")
def main(filepath: Path, c: bool | None, w: bool | None, l: bool | None) -> None:
    # If none of the options was explicitly set, they're all `True`.
    if {c, w, l} == {False}:
        c, w, l = True, True, True
    wc(filepath, c, w, l)
```

You can use any CLI framework you like, like `click`, `Typer`, or the built-in `argparse`.
If you don't know any, you can paste the function `wc` into an LLM and ask it to generate a CLI interface for it.
LLMs tend to be decent at this particular job.

Your code is making use of `click`, and possibly other dependencies, so you have to tell uv about them.
To add a dependency in a project you use the command `uv add`.
Thus, you'd need to run the following command to add `click` as a dependency:

```sh
uv add click
```

After adding this dependency, you should be able to run your project again.
This time, it no longer prints a generic message.
It should print line, word, and byte count, of the given file.
Here is an example:

```sh
❯ uv run myproj src/myproj/__init__.py
      33     143    1119 src/myproj/__init__.py
```

This means you can run your project, but for now it's only available from within the directory `myproj`.
To check this, try opening a new terminal in a random directory and see if you can run the command `myproj`.
You shouldn't be able to do this.
The next step makes this work.


## Step 4: install the project globally

To install the project to make it available globally in your system you will use another feature of uv: the ability to manage commands from Python packages.
[The uv documentation explains everything in detail](https://docs.astral.sh/uv/guides/tools/), so I'll just jump to the fun stuff for this short tutorial.

Make sure you are in the project directory and run the command

```sh
uv tool install . -e
```

The command `uv tool` is what lets you tap into the capabilities that uv has to deal with commands from Python packages.
The dot `.` tells uv to install the package you're in (which should be `myproj`) and the flag `-e` makes it an “editable” installation, which means that changes you make to the source code of the package are instantly reflected in the command you can run.
(You'll want to omit the flag `-e` if you intend to do serious versioning of your project, for example.)

At this point, the command `myproj` should be available globally!


## Step 5: run your CLI from anywhere

Open a new terminal and try running the command `myproj` against a random file you have in your computer.
Here is an example I ran:

```sh
❯ myproj ~/.python_history
    1509    3714   32797 /Users/rodrigogs/.python_history
```

Congratulations, you just turned a Python script into a command you can run globally in your computer!


## Bonus step: better command name

`myproj` is a bit of a weird command name, so you can do one of two things to fix it:

1. go back in time and recreate the project with a more appropriate name; or
2. configure your project to have a more sensible command name.

Let me show how option 2 works, to show you a bit of how everything can be done by hand.
Open the file `pyproject.toml` and look for this section:

```toml
[project.scripts]
myproj = "myproj:main"
```

If you don't remember writing that, that's ok.
You're not going crazy.
uv wrote it for you.
Edit that section so it looks like this:

```toml
[project.scripts]
mywc = "myproj:main"
```

This tells uv that the command `mywc` will run by looking for the function `main` in the module `myproj`.
(Remember that the CLI was implemented in the function `main`?)

After modifying the file and saving it, you need to reinstall the project explicitly.
I know I said `-e` would reflect changes automatically...
But that's for code changes.
This is a project configuration, so you need to force uv to install the project again:

```sh
uv tool install . -e --force
```

After this, you should be able to use the command `mywc` and the command `myproj` should be gone.
Here is an example I ran from a random directory in my system:

```sh
❯ mywc ~/.python_history
    1509    3714   32797 /Users/rodrigogs/.python_history
```


## Conclusion

This short workflow will make it easy for you to take your Python scripts and tools and make them available to you in a structured way, without you having to cram everything into a single folder called `my_tools` or something of the sort.

As I learn more about uv, I'll keep sharing tips and tricks!
You can [look for blog articles tagged with “uv”](/blog/tags/uv) to learn more.
