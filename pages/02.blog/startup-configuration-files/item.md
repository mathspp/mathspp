Learn how to use startup configuration files to run package entry points when the Python interpreter starts up.

===

A **startup configuration file** is a file with the extension `.start`.
Starting from Python 3.15, the Python interpreter uses **startup configuration files** to run package entry points when the interpreter starts up.

Here is an example of a simple startup configuration file that [installs `error-links`](https://github.com/rodrigogiraoserrao/error-links) when the Python interpreter starts:

```text
error_links:install
```

The entry point `error_links:install` from the above points to the function `install` from the module `error_links`.
In general, an entry point resembles an import:

 1. the import path
 2. followed by a colon
 3. followed by the callable that gets called

For the entry point `error_links:install`, the equivalent import would be `from error_links import install`.

## The module `site` and some historical context

The module `site`, from the standard library, is imported automatically when the Python interpreter starts up.
When imported, the module `site` does a couple of things.
Among them, it adds some paths to the module search path and then checks them for configuration paths.

In Python 3.14 or earlier, configuration files were `.pth` files.
`.pth` files can be used to extend `sys.path` by listing directories.
Alongside that, and for backwards compatibility reasons, `.pth` files could also include `import` lines.

`import` lines in a `.pth` [represent a security risk](https://peps.python.org/pep-0829/#motivation), so [PEP 829](https://peps.python.org/pep-0829/) introduced a safer way of running custom hooks during Python interpreter start up.
`import` lines in `.pth` files are now deprecated and Python 3.15+ also looks for `.start` files to run the listed entry points.

## Python 3.15 brings much more to the table

From the two new built-ins `frozendict` and `sentinel`, to lazy imports, package startup configuration files, typing improvements, or more colour everywhere, Python 3.15 has a lot more to offer.
If you want to learn more about what's new in Python 3.15, [take a look at the “15 days of Python 3.15” series](/python-315) I'm running.
For 15 days, I'll explain a new Python 3.15 feature every day.

Get the free 3.15 email series:

{% include "forms/form.html.twig" with {form: forms("enroll_bottom")} %}

