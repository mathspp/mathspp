This article shows the small script I use to automatically push code changes while live coding in talks or classes, improved with uv.

===


One year ago I [wrote a short TIL article where I showed how I automatically push code changes while I'm live-coding](/blog/til/automatically-push-code-changes-during-live-coding).
The code I shared looked like this:

```py
from pathlib import Path
from time import sleep

from git import Repo

repo = Repo(Path(__file__).parent)

while True:
    repo.index.add("*")
    repo.index.commit("Auto sync commit")
    repo.remote().push()
    sleep(60)
```

This code uses `GitPython` to add all files in the folder that contains this script, adds a generic commit message, and pushes.
The script does this every minute, so when I'm teaching or doing any sort of live-coding, the repo I'm working off of gets updated every minute and participants can keep tabs on what I'm writing.

Having used this for a while, there are two disadvantages to it:

 1. I need to install `GitPython` in a virtual environment in each repo I want to use this on; and
 2. the script itself gets pushed to the repo, polluting it a bit.

(As I write this, I realised, I could fix 2. by adding the script to a file `.gitignore`!)

To fix 1. and 2., and to improve my user experience a bit, I [started using uv to inline the dependency on `GitPython` and to turn it into a standalone executable](/blog/til/standalone-executable-python-scripts-with-uv).

Assuming the script was called `gitsync.py`, by doing `uv add GitPython --script gitsync.py` and by [adding the uv shebang](/link-blog/simonwillison-net-2024-aug-21-usrbinenv-uv-run), the top of the script now looks like this:

```py
#!/usr/bin/env -S uv run

# /// script
# requires-python = ">=3.13"
# dependencies = [
#     "gitpython",
# ]
# ///
```

I also tweaked the script structure a bit and now I use `os.getcwd()` to figure out the current working directory when I run `gitsync.py`:

```py
#!/usr/bin/env -S uv run

# /// script
# requires-python = ">=3.13"
# dependencies = [
#     "gitpython",
# ]
# ///

import os
from time import sleep

from git import Repo

def main() -> None:
    repo_folder = os.getcwd()
    print(f"gitsync.py starting at {repo_folder}")
    repo = Repo(repo_folder)

    while True:
        repo.index.add("*")
        repo.index.commit("Auto sync commit")
        repo.remote().push()
        sleep(60)


if __name__ == "__main__":
    main()
```

This makes it so that I can put `gitsync.py` in a directory that's in my PATH, and then use it from anywhere.
Now, when I'm teaching, I just run `gitsync.py &` and that starts syncing my code in the background.
Pretty cool!
