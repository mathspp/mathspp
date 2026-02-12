---
date: 09-02-2026
metadata:
    author: Rodrigo Girão Serrão
    description: "Crash course on uv and how to use it to manage tools."
    og:image: "https://mathspp.com/insider/archive/uv-crash-course-tools/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/uv-crash-course-tools/thumbnail.webp"
title: "uv crash course: tools"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 uv crash course: tools

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## What's uv?

uv is an “extremely fast Python package and project manager, written in Rust.”

If you haven't yet, [install it by following their instructions](https://docs.astral.sh/uv/getting-started/installation/)!

Last week I showed you how you can [use uv to manage your scripts and their dependencies](https://mathspp.com/insider/archive/uv-crash-course-scripts).

Today, you will learn how to use uv to install and manage **tools**.

## What's a tool?

In the context of uv, a _tool_ is any executable application.

Typically, commands!

Some examples include pytest, mypy, ty, ruff, and uv itself!

## Running a tool with `uvx`

The quickest and easiest way to run a tool with uv is by using the shortcut `uvx`.

If you open your terminal and run `uvx pytest --version`, you are telling uv to run pytest with the option `--version`:

```bash
$ uvx pytest --version
Installed 5 packages in 15ms
pytest 9.0.2
```

uv will install pytest into a _temporary, isolated virtual environment_ and run it for you.

The shortcut `uvx` is great for running one-off tools in the spur of the moment.

By the way, `uvx` is short for `uv tool run`.

## Installing tools

If you have tools you're using often, like a linter, or a static type checker, you can install them into isolated virtual environments.

To install a tool, run the command `uv tool install`.

For example, `uv tool install marimo` will install marimo notebooks in your computer.

Now, whenever you want to use marimo, you can run marimo commands directly!

For example, `marimo new` will create a new marimo notebook.

**Note**: after you install a tool, you don't need to use `uv tool run` to run that tool.

## Installing tools with extra dependencies

When you're installing a tool, you can specify extra dependencies to be installed with it.

For example, `uv tool install marimo --with pandas` will install marimo notebooks with pandas as a dependency.

Using this `--with ...` option is also very useful to install tools together with their plugins.

## Installing local tools

One of the best uses of `uv tool install` is to install your own tools, globally on your computer.

Suppose you have a Python project that provides some executables.

You can navigate to the project directory and then run `uv tool install -e .`.

This will install your own project's executables in an isolated environment for you to use!

The `-e` is there to make the install _editable_, meaning changes you make to your code will be automatically picked up when you rerun your tool.

## Listing and upgrading your tools

Run the command `uv tool list` to see all of your installed tools and their executables.

You can also use `uv tool upgrade ...` to upgrade a tool or `uv tool upgrade --all` to upgrade all tools.

## You're just scratching the surface

You just learned some of the functionality that uv provides when working with tools.

You can also:

- Specify tool versions
- Install tools with specific Python versions
- Run tools from packages with different names
- Install tools from alternate sources
- And more!

[Download a free uv cheatsheet](https://mathspp.gumroad.com/l/cheatsheet-uv?layout=profile) to learn more about uv for tools and for its other use cases.

Alternatively, if you want to become the smartest Python developer in the room, [sign up for the intermediate Python course](https://mathspp.com/courses/intermediate-python-course).

The March cohort is spread across 5 weeks to maximise your learning and retention.

And one of the weeks is fully dedicated to uv.

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
