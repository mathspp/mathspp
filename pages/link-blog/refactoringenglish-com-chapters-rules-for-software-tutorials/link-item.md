---
author: Michael Lynch
date: 22-01-2025 19:12
link: https://refactoringenglish.com/chapters/rules-for-software-tutorials/
taxonomy:
    category: link
title: "Rules for Writing Software Tutorials · Refactoring English"
---

In this article the author puts forward 16 rules for writing good software tutorials:

1. Write for beginners
1. Promise a clear outcome in the title
1. Explain the goal in the introduction
1. Show the end result
1. Make code snippets copy/pasteable
1. Use long versions of command-line flags
1. Separate user-defined values from reusable logic
1. Spare the reader from mindless tasks
1. Keep your code in a working state
1. Teach one thing
1. Don’t try to look pretty
1. Minimize dependencies
1. Specify filenames clearly
1. Use consistent, descriptive headings
1. Demonstrate that your solution works
1. Link to a complete example

All of these rules made perfect sense to me and some of them I already try to enforce in my articles.
The suggestions that resonated particularly well with me were those related to making your tutorial as easily reproducible and as easy to follow along as possible.
To this end, Michael suggests several tweaks I can make to my code samples that make life much easier for the reader, which should be my end goal any way.

I'm happy to say that I have already been doing things in this direction.
For example, I used to include lots of snippets of Python REPL sessions, like this:

```pycon
>>> x, y = 1, 2
>>> x
1
>>> y
2
```

However, this was hard to work with if you wanted to copy my code and run it yourself.
A small change I made was to start writing everything as Python scripts and use the `print` function instead:

```py
x, y = 1, 2
print(x)  # 1
print(y)  # 2
```

This is much easier to copy and run for yourself.
Not to mention the “copy to clipboard” button I added to all code snippets!
