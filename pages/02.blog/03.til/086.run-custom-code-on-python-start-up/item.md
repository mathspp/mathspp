Today I learned that you can run custom Python code when Python starts-up, before running other scripts or programs.

===


# Run custom code on Python start-up

You can run custom Python code, every time Python starts-up to run a program or a script, if you customise the file `sitecustomize.py` in your directory `site-packages`.

By default, this file doesn't exist.
However, if you create it and write the code below, Python will print “Hello!” every time it runs something else:

```py
# sitecustomize.py
print("Hello!")
```

For example, if you run the command `pip --version`, you will be greeted:

```bash
❯ pip --version
Hello!
pip 23.3.1 from /Users/rodrigogs/.pyenv/versions/3.12.0/lib/python3.12/site-packages/pip (python 3.12)
```


## How to figure out where the directory `site-packages` is

If you open the Python REPL, you can use the built-in module `site` to figure out where the directory `site-packages` is located:

```pycon
>>> import site
>>> site.getsitepackages()
['/Users/rodrigogs/.pyenv/versions/3.12.0/lib/python3.12/site-packages']
```

With the output of the code above, now I know where to put the file `sitecustomize.py`.

You can also run the command below, if you can't be bothered to open the Python REPL:

```bash
❯ python -c "import site; print(site.getsitepackages())"
```


## How to customise the REPL on start-up

If all you want is to customise the REPL, and not necessarily every single thing that runs on/with Python, you can read my previous TIL on [how to customise the REPL on start-up](/blog/til/customise-the-repl-on-start-up).
