---
# author:
date: 13-11-2025 18:04
link: https://docs.python.org/3/library/cmdline.html
taxonomy:
    category: link
title: "Modules command-line interface (CLI) — Python 3.14.0 documentation"
# via:
---

This page from the Python documentation lists all Python modules that provide a CLI interface.
I've always liked reference pages so it's cool that the Python documentation provides a page that neatly lists all modules with a CLI.

There are many modules listed there that provide CLIs that I didn't know about but one that I am likely to use in the future is the CLI for the module `random`:

```txt
usage: python3.14 -m random [-h] [-c CHOICE [CHOICE ...] | -i N |
                            -f N]
                            [input ...]

positional arguments:
  input                 if no options given, output depends on the input
                            string or multiple: same as --choice
                            integer: same as --integer
                            float: same as --float

options:
  -h, --help            show this help message and exit
  -c, --choice CHOICE [CHOICE ...]
                        print a random choice
  -i, --integer N       print a random integer between 1 and N inclusive
  -f, --float N         print a random floating-point number between 0 and N inclusive
```
