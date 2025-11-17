Today I learned how to set the configurations of my Jupyter Book to build my book in the EPUB format.

===


# Publish an EPUB book with Jupyter Book

I've been redoing my book [Pydon'ts – Write elegant Python code](/books/pydonts) in [Jupyter Book](https://jupyterbook.org) (v1) because the PDFs have great typesetting defaults and look much better than what I currently have with pandoc plus a couple of custom filters.

I was trying to also get Jupyter Book to build my book in EPUB format but was struggling a bit with it because I was getting a couple of weird warnings when I ran the build command:

```bash
bash % jb build --all --builder=custom --custom-builder=epub .
```

This created an EPUB with the name `Projectnamenotset.epub` and the book title was `Projectnamenotset`, so I knew something was off.

The configuration file had the correct metadata:

```yaml
# _config.yml
title: Pydon'ts – Write elegant Python code
author: Rodrigo Girão Serrão
# ...
```

! Strictly speaking, I was only getting warnings so the build process was working fine.
! But I wanted to run the flag `-W`, which turns warnings into errors, so I was hitting a couple of roadblocks on top of the weird project name.


## EPUB3 requires a version

I was getting a warning saying that the format EPUB3 required a non-empty version, which just meant I had to specify a version in the Sphinx config:

```yaml
sphinx:
  config:
    version: "2025.11.17"
```


## Setting the EPUB file name

To set the EPUB file name to something other than `Projectnamenotset.epub` I had to set the option `epub_basename` in the Sphinx config:

```yaml
# _config.yml
sphinx:
  config:
    # ...
    epub_basename: "pydonts"
```


## Setting the EPUB title

Although I had the `title` metadata set, I had to set it again in the Sphinx config so it would show as the EPUB title:

```yaml
sphinx:
  config:
    # ...
    epub_title: "Pydon'ts – Write elegant Python code"
```


## Unknown mimetype for `index.html`

Since my root file is not called `index`, I was also getting a warning saying `sphinx.errors.SphinxWarning: unknown mimetype for index.html`.

Long story short, an extension wants me to have the file `index.html` so that I can always navigate to the root URL and see something, and then the file `index.html` just redirects to my custom root, which is `foreword` in this case:

```yaml
# _toc.yml
format: jb-book
root: foreword  # <--
parts:
  - caption: Introduction
    chapters:
    - file: pydonts/pydont-disrespect-the-zen-of-python.md
    # ...
```

So, I had to tell the EPUB builder to ignore the file `index.html`, that was being built but shouldn't be used when building the final EPUB:

```yaml
sphinx:
  config:
    epub_exclude_files:
      - "index.html"
```


## Final configuration

Here's the final set of configurations I use to build the EPUB:

```yaml
sphinx:
  config:
    epub_exclude_files:
      - "index.html"
    epub_basename: "pydonts"
    epub_title: "Pydon'ts – Write elegant Python code"
    version: "2025.11.17"
    language: en
```

I build with this command:

```bash
bash % jb build --all --builder=custom --custom-builder=epub -W .
```
