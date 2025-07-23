Today I learned how to create standalone Python scripts with uv.

===


## Standalone Python scripts with uv

I picked up uv a couple of weeks ago and I am absolutely amazed by everything that I can do with it.
I [created a Python countdown timer](/blog/using-an-llm-to-write-a-countdown-timer) and I wanted to make it an executable I could use on my system.

Previously, to do this I would package it as a proper Python app, add its dependencies, and then use something like pipx to install that same Python app on my system.

Now, I can just use `uv` to do everything.

As an example, let me use a countdown timer I created today:

![A countdown timer with black numbers on a white background going from 02:59 to 02:56.](/blog/using-an-llm-to-write-a-countdown-timer/_timer_demo.gif "The timer.")

The source code for this timer [can be found in a different article I wrote](/blog/using-an-llm-to-write-a-countdown-timer#full-source-code-for-the-countdown-timer).

Now that you also have the code, here are the steps:

### Use uv to manage script dependencies

If the code is saved in the file `cd.py`, you can use uv to add a dependency to that file:

```bash
$ uv add pygame --script cd.py
Updated `cd.py`
```

uv will promptly say it updated your file, which should now have some comments in the beginning:

```py
## /// script
## requires-python = ">=3.13"
## dependencies = [
##     "pygame",
## ]
## ///
```

This lets uv run your script in a self-contained way.
If you run the script with `uv run cd.py`, then uv will install pygame in an isolated environment and then it will run your countdown timer.

### Add a uv shebang to the script

The next thing we will do is add a uv shebang to the script so that we can use it as an executable and to defer to uv for execution.
To do this, we add a new line at the very top of the script `cd.py`:

```py
#!/usr/bin/env -S uv run
## /// script
## requires-python = ">=3.13"
## dependencies = [
##     "pygame",
## ]
## ///
```

I [link-blogged about this shebang before](/link-blog/simonwillison-net-2024-aug-21-usrbinenv-uv-run) and I'm really happy I got to use it today.

### Make the script executable

The next step is to make your script executable:

```bash
chmod +x cd.py
```

Additionally, I moved it to a directory in my `$PATH` environment variable with

```bash
mv cd.py ~/.local/bin
```

Now, I can run my countdown timer from anywhere I want:

```bash
cd.py 3  # Start a 3-minute countdown.
```

### Bonus: run the timer from the Internet

I hosted the code for the timer on my website as well, at this URL: https://mathspp.com/blog/using-an-llm-to-write-a-countdown-timer/cd.py

With this URL, you can use uv to run the timer without even having the code locally.
For example, here's how to start a 3 minute countdown:

```bash
uv run https://mathspp.com/blog/using-an-llm-to-write-a-countdown-timer/cd.py 3
```
