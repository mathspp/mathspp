---
date: 02-03-2026 18:17
metadata:
    author: Rodrigo Girão Serrão
    description: "Crash course on uv and how to use it to create lib projects."
    og:image: "https://mathspp.com/insider/archive/uv-crash-course-lib-projects/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/uv-crash-course-lib-projects/thumbnail.webp"
title: "uv crash course: lib projects"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 uv crash course: lib projects

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## What's uv?

This is the _fifth_ email on a uv crash course short series.

If you haven't been paying attention, or don't know what uv is, uv is a package manager that's lightning fast.

It's also supposed to be a single tool for all of your project management, packaging, tooling, and scripting needs.

You can [check the official docs to see how to install uv](https://docs.astral.sh/uv/).

Two weeks ago you learned how to [create app projects with uv](https://mathspp.com/insider/archive/uv-crash-course-app-projects) and last week you learned how to [package your own app and install it on your system as a global tool](https://mathspp.com/insider/archive/uv-crash-course-apps).

Today you'll learn how to create and manage lib projects with uv.

## What's a lib project?

uv knows about two types of Python projects:

 - **App projects**: Projects that are suitable for when you'll have something you want to run, like a CLI or a web server.
 - **Lib projects**: Projects that provide modules to be imported by other projects.

When you create a new project, you can tell uv which type of project you want and uv will initialise the project with slightly different contents for you.

Of course, in the end you can modify everything and the distinction between the two types of projects isn't enforced in any way.

It's just about the initial boilerplate that's done for you.

## Create a lib project

To create a lib project, you'll want to run

```bash
$ uv init --lib example-project
```

This is in opposition to using the flag `--app`, for an app project.

## The contents of a lib project

This creates the folder `example-project` that contains all of your project files:

- `.git` and `.gitignore` are provided by default because uv assumes you'll version your code. Shame on you if you don't!
- `.python-version` is a tiny file that tells uv what Python version you're using for that project.
- `pyproject.toml` contains your project metadata, along with dependencies, tool configuration, and more.
- `README.md` is the typical readme file where you'll want to add basic instructions about your project.
- `src` is the source folder that's created for you.

If you look inside `src`, you'll find the following directory hierarchy:

```text
|- src
   |- example_project
      |- __init__.py
      |- py.typed
```

The file `py.typed` is _empty_ and it is just a marker file.

Lots of tools in the Python ecosystem use the file `py.typed` as a _hint_ that the project uses type hints.

You can delete that file if you won't be using type hints in your project.

On the other hand, `__init__.py` already contains two lines of code:

```py
def hello() -> str:
    return "Hello from example-project!"
```

## Testing your project

Since your project already includes a function, you can try to import it already.

To test your project, you must run Python with uv:

```bash
$ uv run python
```

In doing so, uv will build your project, install it in the local virtual environment, and then start a Python REPL within the project's virtual environment.

Since everything is happening based on your virtual environment, you can import your example project in the REPL:

```pycon
>>> import example_project
>>> example_project.hello()
'Hello from example-project!'
```

## Structuring your project and your imports

The directory hierarchy inside `src/example_project` is what defines how your imports will work.

Suppose your hierarchy _inside_ `src/example_project` looks like this:

```text
|- __init__.py
|- root.py
|- sub
   |- __init__.py
   |- subsub.py
```

If you have relevant objects in `subsub.py`, then you need to import them from `example_project.sub.subsub`.

The import starts with the project name, followed by the folder(s), and then the file name.

In a way, the imports mimic the file path to the function.

The files that are named `__init__` are special, though.

If the file `sub/__init__.py` has relevant objects, then you need to import them from `example_project.sub`.

The files `__init__.py` allow you to use the name of the parent folder as the last value in the import path.

## Managing dependencies

You can manage your project dependencies in the exact same way you'd [manage them in an app project](https://mathspp.com/insider/archive/uv-crash-course-app-projects#managing-app-dependencies).

The gist of it is that you use `uv add dep1 dep2 dep3` to add dependencies.

And you use... You guess it: `uv add remove dep1 dep2 dep3` to remove dependencies and their transitive dependencies.

## Project versioning

When you're working in your code, you can use uv itself to manage the version of your project.

The command `uv version` tells you the current project version:

```bash
$ uv version
example-project 0.1.0
```

But you can use the option `--bump` to bump the version number.

For example, after all of your hard work you decided you're ready to release version `1.0.0`, but in beta.

You can do that with

```bash
$ uv version --bump major --bump beta
example-project 0.1.0 => 1.0.0b1
```

Your users test your beta release and you fix a couple of bugs, so now you need to make a new beta release:

```bash
uv version --bump beta
example-project 1.0.0b1 => 1.0.0b2
```

After more testing, it looks solid.

Now, you can make it a release candidate version:

```bash
$ uv version --bump rc
example-project 1.0.0b2 => 1.0.0rc1
```

Once it's finally ready for the big `1.0.0` release, you just mark it as stable:

```bash
$ uv version --bump stable
example-project 1.0.0rc1 => 1.0.0
```

This is just a small example, and you can also use `minor` and `patch` to bump minor and patch numbers.

## And there's much more...

Once you create your project, you'll want to publish it.

Maybe upload it to PyPI so that others can install it and use it?

Maybe you want to use your own project locally in some of your other projects?

The sky is the limit...

## What's next?

I'm getting close to the end of what I wanted to tell you about in this uv crash course.

What else would you like to learn about uv?

You'll also want to [download this free uv cheatsheet with 40 of the most common and useful uv commands](https://mathspp.com/blog/uv-cheatsheet).

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_intro'} ) } %}
