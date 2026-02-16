---
date: 16-02-2026 18:57
metadata:
    author: Rodrigo Girão Serrão
    description: "Crash course on uv and how to use it to create app projects."
    og:image: "https://mathspp.com/insider/archive/uv-crash-course-app-projects/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/uv-crash-course-app-projects/thumbnail.webp"
title: "uv crash course: app projects"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 uv crash course: app projects

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## What's uv?

Two weeks ago you [learned how to use uv to manage your scripts and their dependencies](https://mathspp.com/insider/archive/uv-crash-course-scripts).

Last week you [learned how to use uv to install, manage, and use tools](https://mathspp.com/insider/archive/uv-crash-course-tools).

Today you'll learn how to use [uv](https://docs.astral.sh/uv), an “extremely fast Python package and project manager”, to create and manage an app project.

If you haven't yet, [install uv by following the official installation instructions](https://docs.astral.sh/uv/getting-started/installation/) and then keep reading!

## Creating a project with uv

Since uv is a “project manager”, you shouldn't be surprised to know you can create projects with uv!

The command `uv init` will initialise a project in the current directory.

If you use the command `uv init project-name`, uv creates a directory `project-name` and initialises the project in that directory instead.

Go ahead.

Create an example project with uv and cd into it:

```bash
$ uv init example-project
Initialized project `example-project` at `path/to/example-project`
$ cd example-project
```

## The contents of the project

If you inspect the directory you created, you'll find a couple of files and a folder:

- `.git` and `.gitignore`: uv assumes you'll want to use versioning control and it uses git by default.
- `main.py`: uv creates a stub for your app code. More on that in a second.
- `README.md`: the typical README file where you should include basic instructions and documentation about your project.

On top of those, uv also creates a very important file: `pyproject.toml`.

My `pyproject.toml` file looks like this:

```toml
[project]
name = "example-project"
version = "0.1.0"
description = "Add your description here"
readme = "README.md"
requires-python = ">=3.14"
dependencies = []
```

This file `pyproject.toml` contains basic project metadata but it is also where you configure tools, manage dependencies, and more.

uv also created the file `.python-version`, that maybe you missed the first time around, because its name starts with a dot.

The file `.python-version` contains the number of the Python version uv associated with your project, and it picks up your default Python version by default.

To specify a different Python version for the project, create the project with the option `--python 3.X`.

## App projects and `main.py`

uv makes the distinction between two types of projects:

1. application projects
2. library projects

The default is for uv to create an application project.

An application project is suitable for things like web servers, scripts, and command-line interfaces.

You'll learn all about library projects in a future email.

For now, you'll focus on application projects.

The idea of an app project is that it has code you can run directly and that you'll want to run directly.

That's what's inside `main.py`.

The stub code for your application!

And to run it?

Just use the command `uv run main.py`:

```bash
$ uv run main.py
Using CPython 3.14.3
Creating virtual environment at: .venv
Hello from example-project!
```

The first time you run `uv run main.py` you should see two lines of output talking about a virtual environment.

uv automatically manages the virtual environment of your project!

You don't even have to activate it!

And then it runs your code, printing a generic message.

You get a generic greeting because that's what's inside `main.py`:

```py
def main():
    print("Hello from example-project!")

if __name__ == "__main__":
    main()
```

## Managing app dependencies

Your app is already great but you want to take it up a notch.

Instead of a plain greeting, you want to have the greeting inside a panel, and you'll do that with the library `rich`.

To add the `rich` dependency to your project, run the command `uv add rich`:

```bash
$ uv add rich
Resolved 5 packages in 109ms
Prepared 4 packages in 180ms
Installed 4 packages in 6ms
 + markdown-it-py==4.0.0
 + mdurl==0.1.2
 + pygments==2.19.2
 + rich==14.3.2
```

 > You probably could have guessed that command, but [check this uv cheatsheet with 40+ reference commands](https://mathspp.com/blog/uv-cheatsheet) for the others you can't guess.

The output shows everything uv installed, which in this case is `rich` and its dependencies.

Now you can modify your script to use `rich`:

```py
from rich import print
from rich.panel import Panel

def main():
    print(Panel("Hello from example-project!"))

if __name__ == "__main__":
    main()
```

If you run your script again with `uv run main.py`, you'll see the improved output:

```bash
$ uv run main.py
╭───────────────────────────────╮
│ Hello from example-project!   │
╰───────────────────────────────╯
```

Notice how your script was able to import `rich`.

`rich` is installed in a virtual environment but you never have to activate it because uv takes care of everything.

By the way, if you run `uv run python` inside your project, uv will open a Python REPL with access to your dependencies.

## Dependencies behind the scenes

At this point, if you look inside `pyproject.toml`, you'll see a couple of new lines:

```toml
dependencies = [
    "rich>=14.3.2",
]
```

Whenever you add or remove a dependency, uv keeps track of that in the file `pyproject.toml`.

You can see the list `dependencies` as a high-level list of the dependencies you require.

However, you'll also find a new file in your project: `uv.lock`.

The contents of this file look a bit cryptic but that's ok.

You should _never_ modify `uv.lock` by hand.

The file `uv.lock` is a **lockfile**: a complete and thorough description of everything you have installed in your virtual environment.

It includes your dependency `rich`, as well as the dependencies that `rich` has.

It lists everything, together with their exact versions, and some more info.

This file is what allows you, another contributor, or a user, to reproduce your virtual environment in a different machine.

This lockfile is important and should be tracked by your versioning control system.

## More app flexibility

At this point, your app project looks like a glorified script.

When [you learned about scripts](https://mathspp.com/insider/archive/uv-crash-course-scripts), you saw that you could already manage dependencies, so it may look like you're not gaining much.

And that's a fair point.

Today you learned mostly about the structure of a project and the role of each file.

## And there's much more...

Next week you'll learn how to take your app projects to the next level by creating packageable projects, defining entry points, and installing your own projects as tools.

It's going to be super fun!

This is also the final module of the [intermediate Python course](https://mathspp.com/courses/intermediate-python-course), where you learn how to create packages, install your code as local tools, publish your projects to PyPI, and more.

There's a cohort starting soon!

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
