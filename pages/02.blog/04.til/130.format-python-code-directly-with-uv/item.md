Today I learned you can format your Python code directly with uv.

===


In uv version 0.8.13, released one or two days ago, uv added the command `format` that allows you to format your Python code directly through the uv CLI.

## Update your uv

First and foremost, make sure you're rocking uv 0.8.13 or greater by running `uv self update`.

## Format your code with uv

To format your code with uv you can simply run `uv format`, which will use Ruff to format the code in your current directory:

```sh
$ uv format
```

The idea is not to have uv replace Ruff; it's just so that you don't have to think about a separate tool if you don't want to.

## `uv format` arguments

`uv format` accepts the same arguments and options that `ruff format` accepts, so you'll want to [check the Ruff docs](https://docs.astral.sh/ruff/formatter/) to learn more.
My favourite option is `--diff`, to take a look at the formatting diff without doing any formatting changes.

As of now, the feature is marked as being experimental, which means it might change in the future!
