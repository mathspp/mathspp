Today I learned how to use uv to escape uv and go back to using venv and pip for a given project.

===


# Use pip directly from a uv virtual environment

uv comes with the subcommands `uv venv` and `uv pip` that let you use your `venv` + `pip` workflows, with the commands you already know, while benefiting from the speed of uv.
However, I have a specific project in which I need to be able to use `pip` directly from the virtual environment that uv created.

After I ran `uv venv` and activated my virtual environment, I tried using `pip` to install a package with `python -m pip install my_package` but got an error message saying “.venv/bin/python: No module named pip”.

To fix this, I used the option `--seed` that seeds my virtual environment with `pip`.
So, I recreated my virtual environment with `uv venv --seed`, activated it, and then I was able to use `pip` directly from within the virtual environment.

This kind of goes against the point of uv, but the fact that there is an escape hatch for random situations like mine just goes to show that uv is well thought out...
