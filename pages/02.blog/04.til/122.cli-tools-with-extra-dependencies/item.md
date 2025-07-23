Today I learned you can use uv to install CLI tools with extra dependencies.

===


## CLI tools with extra dependencies

You can use `uv` to install CLI tools in isolated virtual environments.
E.g., `uv tool install black` installs `black` in an isolated venv.
After installing, `black` will be added to your path and you can use it directly.

When installing tools, you can use the option `--with` to specify extra dependencies that will be available when you use the CLI tool you are installing.
Here are two examples:

 - `uv tool install --with pandas --with polars marimo` – this installs Marimo and makes sure Pandas and Polars will be available inside Marimo notebooks.
 - `uv tool install --with "ruamel.yaml" cogapp` – this installs cog, a file generation tool, but makes sure there is a YAML parser available to be used by cog (I use this a lot to build my books).
