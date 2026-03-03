Today I learned how to install jupyter properly while using uv to manage tools.

===


## Running a Jupyter notebook server or Jupyter lab

To run a Jupyter notebook server with uv, you can run the command

```bash
$ uvx jupyter notebook
```

Similarly, if you want to run Jupyter lab, you can run

```bash
$ uvx jupyter lab
```

Both work, but uv will kindly present a message explaining how it's actually doing you a favour, because it _guessed_ what you wanted.
That's because `uvx something` usually looks for a package named “something” with a command called “something”.

As it turns out, the command `jupyter` comes from the package `jupyter-core`, not from the package `jupyter`.

## Installing Jupyter

If you're running Jupyter notebooks often, you can install the notebook server and Jupyter lab with

```bash
$ uv tool install --with jupyter jupyter-core
```

### Why `uv tool install jupyter` fails

Running `uv tool install jupyter` fails because the package `jupyter` doesn't provide any commands by itself.

### Why `uv tool install jupyter-core` doesn't work

The command `uv tool install jupyter-core` looks like it works because it installs the command `jupyter` correctly.
However, if you use `--help` you can see that you don't have access to the subcommands you need:

```bash
$ uv tool install jupyter-core
...
Installed 3 executables: jupyter, jupyter-migrate, jupyter-troubleshoot
$ jupyter --help
...
Available subcommands: book migrate troubleshoot
```

That's because the subcommands `notebook` and `lab` are from the package `jupyter`.
The solution?
Install `jupyter-core` _with_ the additional dependency `jupyter`, which is what the command `uv tool install --with jupyter jupyter-core` does.

## Other usages of Jupyter

The uv documentation has a [page dedicated exclusively to the usage of uv with Jupyter](https://docs.astral.sh/uv/guides/integration/jupyter/), so check it out for other use cases of the uv and Jupyter combo!
