---
date: 02-02-2026
metadata:
    description: "Crash course on uv and how to use it to manage scripts."
title: "uv crash course: scripts"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 uv crash course: scripts

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!


## What's uv?

If you don't know what uv is, then you have been living under a rock!

But kidding aside, uv is an “extremely fast Python package and project manager, written in Rust.”

Today I want you to learn about one of my favourite uv features and one of the things that [we talked about in the Intermediate Python course](https://mathspp.com/courses/intermediate-python-course) from last week.

Today you'll learn how to use uv to manage scripts.

## Scripting hell

Python is a **scripting language**, which means you use it to write scripts.

But there's a problem you run into when writing Python scripts...

And that's when your scripts have _dependencies_.

If your script depends on third-party modules, you need to install those dependencies.

But if you install everything in your global Python installation, you're polluting your Python installation...

_And_ you'll quickly have competing dependencies when you need different versions for different scripts...

## Virtual environments

The solution to the problem of having dependencies for all your scripts is using **virtual environments**.

A virtual environment lets you install dependencies for one script in a location that's isolated from all other scripts.

But that's cumbersome if you need _one virtual environment per script_.

It can be done...

But the sheer amount of bookkeeping, and the numerous virtual environments you create, and then having to activate and deactivate them all the time...

## There is a better way!

The better way to manage all this is to let uv manage your script's dependencies for you.

It's simple because uv does everything for you.

## Creating a script

It all starts with a simple uv command

```bash
$ uv init --script my_script.py --python 3.14
Initialized script at `my_script.py`
```

This command tells uv that you want it to manage a script called `my_script.py`.

The flag `--python 3.14` tells it to use Python 3.14 explicitly but you can omit the flag `--python`.

In that case, uv uses whatever version is your default version.

(I _hope_ it's 3.14 but I _bet_ it's Python 3.6 or something even sadder :(.)

The script comes pre-populated with some code and some comments.

Let us break it down.

## The boilerplate code

The script includes a little bit of boilerplate code:

```py
def main() -> None:
    print("Hello from my_script.py!")

if __name__ == "__main__":
    main()
```

This is nothing special and you can easily delete it if you want.

But at least it already created a main function for you and added the `if __name__ == "__main__"` statement.

uv is very helpful!

But the main thing you care about is the header that uv created...

## The script header

uv will add a special header to your script that comes _before_ the boilerplate code:

```py
# /// script
# requires-python = ">=3.14"
# dependencies = []
# ///
```

These comments include basic metadata regarding the Python version your script requires...

And also its dependencies!

## Managing the script dependencies

Suppose you want to add `rich` as a dependency to your script:

```bash
$ uv add rich --script my_script.py
Resolved 4 packages in 198ms
```

This modifies the script header:

```py
# /// script
# requires-python = ">=3.14"
# dependencies = [
#     "rich>=14.3.2",
# ]
# ///
```

Now, whenever uv runs your script, it knows it has to install rich!

## Testing the script with dependencies

To test the way uv manages the dependencies, tweak your script:

```py
# ... header
import rich
import rich.panel

def main() -> None:
    rich.print(rich.panel.Panel("Hello from rich."))

if __name__ == "__main__":
    main()
```

Now, run your script _with uv_:

```bash
$ uv run my_script.py
╭───────────────────╮
│ Hello from rich.  │
╰───────────────────╯
```

It is _very important_ that you use the command `uv run` to run the script.

This is what tells uv to run your script.

When uv does that, it starts by reading the header.

If uv finds dependencies (which it did, in this example), it will _install the dependencies in an isolated environment_ before running the script.

uv does all of this for you, without you having to worry about activating any virtual environments or anything.

## You're just scratching the surface

Even within the world of scripts, uv can do _so much more_:

- Specify versions for the dependencies
- Remove dependencies
- Specify local dependencies
- Add a shebang to make your script executable through uv
- And more!

[Download a free uv cheatsheet](https://mathspp.gumroad.com/l/cheatsheet-uv?layout=profile) to learn more about uv for scripts and for its other use cases.

Alternatively, if you want to become the smartest Python developer in the room, [sign up for the intermediate Python course](https://mathspp.com/courses/intermediate-python-course).

The March cohort is spread across 5 weeks to maximise your learning and retention.

And one of the weeks is fully dedicated to uv.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
