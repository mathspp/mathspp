This article documents my journey through learning how to write a custom Sphinx extension.

===

![](thumbnail.png)


# Introduction

This article will be written as I explore the world of Sphinx and learn how to write a custom Sphinx extension,
something I need because of a work project I am working on.

The next few subsections will be brief and give you some context into why I am doing this.
If you don't care about the context and just want to tag along, feel free to skip the entirety of this section.


## What is Sphinx?

[Sphinx] is a tool that makes it easy to create intelligent and beautiful documentation.
It was originally built for the Python documentation, but is now used a wide range of languages and projects.
In particular, the [Jupyter Book][jb] project relies heavily on Sphinx under the hood.


## Why do I need to extend Sphinx?

[Jupyter Book][jb] is a great tool that allows you to write books from Jupyter notebooks,
and I'm using it in my [MDAPL] project.

Recently, [someone pointed out][phone-numbers-gh-issue]

[Sphinx]: https://www.sphinx-doc.org/
[jb]: https://jupyterbook.org
[mdapl]: https://mastering.dyalog.com/
[phone-numbers-gh-issue]: https://github.com/Dyalog/MDAPL/issues/19
