Today I learned how to publish a Python package to PyPI with uv.

===


# Publishing a Python package with uv

Publishing a package to PyPI with uv was quite simple.
All I had to do was read the documentation with the steps provided and I was done in less than 2 minutes.

The package I'm publishing is [my CLI that solves the LinkedIn Queens puzzle](/blog/beating-linkedin-queens-with-python), about which I wrote in a different article.

The first thing I did was make sure I could build my package with

```sh
uv build
```

The next step is to run `uv publish`.
To publish a package to PyPI I needed a token for authentication.
To get that, I opened my account settings, scrolled to “API tokens”, and created a new token that was scoped to “all projects” because the project I want to upload/publish hasn't been created yet.

After I created that token, I ran

```sh
uv publish --token MY_TOKEN_HERE
```

That took a second to run and then [my project `li_queens` was published on PyPI](https://pypi.org/project/li_queens/).

Now that the project exists, I went to my PyPI account settings, deleted the generic API token I created just now, and then I created a token that's scoped specifically for the project `li_queens`.

Now that the package is published, you can use `uv` to run my CLI `queens` without even having to install the package explicitly.
If `puzzle.png` is an image with a LinkedIn Queens-like puzzle, just run

```sh
uvx --from li_queens queens puzzle.png
```
