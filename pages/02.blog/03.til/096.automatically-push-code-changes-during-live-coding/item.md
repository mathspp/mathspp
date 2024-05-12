Today I learned how to automatically push code changes while I'm doing live coding, for example while teaching.

===

# Automatically push code changes during live coding

When I'm doing live coding during tutorials / workshops / webinars, I often want to push the code I'm writing to GitHub so that participants have a way to fetch my work in case they fall behind.
I don't want to have to do this manually every time I write some code, so I wrote a short script that does this automatically for me.

I can never find the script that automates this process and every time I do some live coding during a webinar I have to rewrite the code, so this time I decided I'd write it down in my blog so that I don't have to keep reinventing the wheel.
Here's the script:

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

It depends on [the module GitPython](https://gitpython.readthedocs.io/en/stable/) and the script also assumes it's located at the root of the repository I want to sync.

Whenever I'm doing live coding, all I need to do is install the GitPython dependency and start the script, which will add, commit, and push, all my changes every 60 seconds.
