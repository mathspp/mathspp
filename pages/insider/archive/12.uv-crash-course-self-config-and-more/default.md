---
date: 09-03-2026 19:05
metadata:
    author: Rodrigo Girão Serrão
    description: "Crash course on uv and how to manage itself, configure it, manage Python versions, and more."
    og:image: "https://mathspp.com/insider/archive/uv-crash-course-self-config-and-more/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/uv-crash-course-self-config-and-more/thumbnail.webp"
title: "uv crash course: self, config, and more"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 uv crash course: self, config, and more

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## Updating uv

[uv, the Python project and package manager](docs.astral.sh/uv) you've been learning about for the past few weeks, is constantly being updated.

To check the version you're on, run

```bash
$ uv self version
uv 0.10.9 (f675560f3 2026-03-06)
```

When you want to update uv, all you have to do is run the command

```bash
$ uv self update
```

Just two months ago, uv was at version `0.9.6`.

In two months, it went all the way up to `0.9.30`, then `0.10.0`, and now it's at `0.10.9` already!

That's a pretty insane pace, if you ask me!

In keeping up with the rapid uv development, earlier today I [updated this free uv cheatsheet](https://mathspp.com/blog/uv-cheatsheet) to make sure it's up to date and relevant.

## The command `help`

Whenever you're using uv and don't remember how to do something, use the command `help`.

For example, running

```bash
$ uv help run
```

will display the help on the command `run`, including its subcommands, options, flags, and more.

If you write code with agents, telling them to check `uv help X` is also a pretty good way of instructing the LLM to manage your project/scripts/tools with uv.

## The option `--python`

A big portion of uv commands accept the option `--python` that you can use to request a specific Python version for that command.

For example, run

```bash
$ uv run --python 3.12 python
```

To tell uv to specifically open a Python 3.12 REPL, or

```bash
$ uv tool install marimo --python 3.13.3
```

to tell uv to [install the tool](https://mathspp.com/insider/archive/uv-crash-course-tools) marimo specifically with Python 3.13.3.

Or even

```bash
$ uv init --app --python 3.11
```

To [initialise an app project](https://mathspp.com/insider/archive/uv-crash-course-app-projects) with Python 3.11.

These are just a few examples of when the option `--python` might come into play.

If you don't specify it, then uv has a couple of rules to determine which Python version to use.

And for that, uv will figure out which Python versions you have on your system...

## Managing Python versions

On top of other Python versions you might have because

 - they come bundled with your operating system; or
 - other tools installed them

uv can also be used to manage Python versions.

Run the command

```bash
$ uv python list
```

to see all Python versions that uv knows about, both the _system_ versions (installed by others) and the _managed_ versions (installed by uv).

Whenever you use the option `--python` and you request a Python version that you don't have yet, uv will quickly download that Python version so it's available for you.

If you want to update the patch versions of your Python versions, for example from 3.14.2 to 3.14.3, just run the command

```bash
$ uv python upgrade
```

and uv will upgrade all its managed Python versions.

The older ones aren't automatically deleted, though, since you might have virtual environments or installed tools that use the older versions.

uv can also be used to manage Python versions from other distributions, for example PyPy, or to install free-threaded Python versions.

## Configuring uv

When inside a project, uv can be configured in the file `pyproject.toml` in the table `[tool.uv]`.

For example, if you include

```toml
[tool.uv]
python-preference="only-managed"
```

then uv will _only_ use Python versions managed by uv in your project.

You can also configure uv in a file `uv.toml`, and these will work outside of projects, too.

For example, for user-level uv configuration, you can create a file `uv.toml` in the directory `~/.config/uv` (on Linux/MacOS) or `%APPDATA%\uv` (on Windows).

You'll want to [check the uv docs on all the settings you can configure](https://docs.astral.sh/uv/reference/settings/).

Many commands and options can also be configured through [environment variables](https://docs.astral.sh/uv/reference/environment/), which are often referenced in the help text of the command `uv help`.

## uv questions?

Over the past few weeks you learned how to use uv to

 1. run and manage scripts with inline dependencies
 2. install executable tools for general use, like `ruff` or `mypy`
 3. create and manage app projects, add dependencies, and more
 4. build and install your own apps in your system
 5. create and manage lib projects, update your project's version, and more
 6. manage installed Python versions
 7. and more

You covered a lot of ground and should now be in a position to use uv effectively in your own workflows.

If you have any uv questions left over, or if you need help with uv, reply to this email to let me know.

Your reply goes straight to my inbox.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}


