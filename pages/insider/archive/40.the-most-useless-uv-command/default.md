---
date: 28-09-2026 18:07
metadata:
    author: Rodrigo Girão Serrão
    description: "Comparing uv tool install with uvx and when to use the latter"
    og:image: "https://mathspp.com/insider/archive/the-most-useless-uv-command/thumbnail.webp"
    twitter:image: "https://mathspp.com/insider/archive/the-most-useless-uv-command/thumbnail.webp"
title: "The most useless uv command"

process:
  twig: true
cache_enable: false
---

# 🐍🚀 The most useless uv command

 > This is a past issue of the [mathspp insider 🐍🚀](/insider) newsletter. [Subscribe to the mathspp insider 🐍🚀](/insider) to get weekly Python deep dives like this one on your inbox!

## uv allows you to manage tools

If you've gotten my [uv cheatsheet](https://mathspp.com/blog/uv-cheatsheet), you'll know that you can use uv to manage tools.

This means you can use the command `uv tool install toolname` to install a given tool.

This installs the tool, along with its dependencies, in an isolated environment.

Useful, for example, for linters, type checkers, and other random CLI utilities you may have.

## One-shot tool runs

uv also provides the command `uvx`.

The command `uvx` allows running a tool without having to install it first, like `uvx toolname`.

For example, `uvx ty --check` will run the ty type checker without installing it first...

But if you think about it, `uvx` sounds like a fake promise.

It downloads the tool and its dependencies, it installs them in an isolated environment, and then runs the tool for you.

And whatever tool you're running once, you'll probably run it again.

So, why not just run `uv tool install toolname; toolname`?

Why is `uvx` useful?

I'll tell you about a situation where I found `uvx` very useful.

## Separate sets of plugins for a single tool

Recently I was doing a technical review of a Python book for O'Reilly.

I created a jupyter notebook for each chapter.

As I read, I'd run the code examples in the notebook to make sure everything worked.

But each chapter had a different set of dependencies.

For example, some notebooks depended on pandas, others on `matplotlib`, and others on `numba`.

So, for each notebook, I needed to install a different set of dependencies.

How would you do this?

I thought of three alternatives, but one was clearly better than the others.

## Alternative 1: install all dependencies in a single environment

The first alternative is to install _all_ the dependencies of all chapters in a single environment.

This means that each notebook would have access to _everything_.

In practice, this would work for a regular reader.

But I am not a regular reader, I'm a technical reviewer.

One of the things that I need to ensure is that the list of dependencies for a chapter is complete.

So, if all notebooks have access to all dependencies, I won't be able to tell if the chapter descrption is missing a dependency for that specific chapter.

## Alternative 2: create a virtual environment for each chapter

This alternative works just fine.

This gives me, by definition, an isolated environment per chapter and doesn't suffer from the same problems as the first alternative.

However, this is a _very_ cumbersome alternative.

The book I was reviewing had around 30 chapters.

Creating 30 different virtual environments, one per chapter, would require a cumbersome and deeply nested file hierarchy.

But that's not what I want.

What I want is a single folder with every single notebook.

Then, the third alternative occurred to me...

## Alternative 3: use `uvx` with extra depedencies

The third alternative is just like alternative two but without the extra steps of maintaining the virtual environments myself.

The command `uvx` can be used to run a tool in an ephemeral isolated environment.

But the command `uvx` also accepts the flag `--with`.

This allows you specify extra dependencies to run alongside the tool.

This is a very useful pattern for when you're running a tool with plugins, for example.

That's because a plugin wouldn't naturally be installed as a dependency of the tool.

But you don't want to run the plugin directly with `uvx plugin_name` because that's not how the tool works.

So, you'd use `uvx tool_name --with plugin_name`.

As it turns out, another use case for this is to run jupyter notebooks with a given set of modules installed.

For example, for a chapter that required pandas and matplotlib, I used the command

```bash
uvx --from jupyter-core --with pandas,matplotlib -- jupyter lab
```

This runs Jupyter Lab with pandas and matplotlib installed for me to use.

There's extra stuff in that command, but that has to do with how you [should run jupyter notebooks in uv](https://mathspp.com/blog/til/install-jupyter-with-uv).

## How to run Jupyter with uv

The command `jupyter` is from a packaged called `jupyter-core`.

When you run `uvx jupyter`, uv guesses that the command and the package have the exact same name.

But that's not the case.

Thus, to run `jupyter`, you need the command `uvx --from jupyter-core jupyter lab`.

That's the extra stuff in the command above.

## Making it more convenient

To wrap it up, it'd be a pain to write these lengthy commands each time I wanted to run the notebook for a given chapter.

My fix was to create a makefile with a command per chapter.

For example, here's the section for chapter 28:

```makefile
ch28:
    uvx --from jupyter-core --with numpy,matplotlib -- jupyter lab
```

## Enjoyed reading? 🐍🚀

Get a Python deep dive 🐍🚀 every Monday by dropping your best email address below:

{% include "forms/form.html.twig" with {form: forms( {route: '/insider/_hero'} ) } %}
