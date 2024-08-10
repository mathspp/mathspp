Today I learned how to find the commits that affected a specific file with `git log`.

===


# Find commits that affected a file

When using git, you can use the command `git log` to show a log of all commits (I like `git log --oneline` to show a terser log).

What I learned today is that using the command `git log -- path/to/file` will filter the logs to only show commits that affected that file.


## Practical example

I was looking at [commit 1b61a95](https://github.com/Textualize/textual/blob/1b61a95c7025160cbbcb74cf9562a3b057afd3e6/tests/test_table.py) of the [Textual](https://github.com/textualize/textual) repository, which created a file `tests/test_table.py` with a test that I cared about.

What I wanted was to figure out how that file evolved over time because the file `tests/test_table.py` no longer exists and because I couldn't find the tests that existed in that file.

So, what I did was run the command `git log -- tests/test_table.py` and I got this output:

```git
commit 12d429dbd0c9e86fb45251c17dbb9e15ad6624cf
Author: Darren Burns <darrenb900@gmail.com>
Date:   Tue Jan 24 11:33:35 2023 +0000

    Replace DataTable row_count with property, test improvements

commit d1413ee352a79b8b5e0a8f23cee9276893896903
Author: Darren Burns <darrenb900@gmail.com>
Date:   Tue Jan 24 10:38:47 2023 +0000

    Updating test to ensure row_key mapped data is correct

commit a45a3dca6a1513b9e64cb4b680d337ae3b75c1f8
Author: Josh Karpel <josh.karpel@gmail.com>
Date:   Wed Dec 21 16:57:43 2022 -0600

    add option to clear columns in DataTable.clear

commit 1b61a95c7025160cbbcb74cf9562a3b057afd3e6
Author: Will McGugan <willmcgugan@gmail.com>
Date:   Thu Nov 10 16:22:52 2022 +0000

    table tests
```

Then, using `git show <commit>` you can take a look at each commit and figure out what happened with the file.
If the file was deleted or renamed, then the commit at the top of the log should be the commit where said file was deleted or renamed.

For example, in this case, running `git log --oneline -- tests/test_table.py` outputs this:

```git
12d429dbd Replace DataTable row_count with property, test improvements
d1413ee35 Updating test to ensure row_key mapped data is correct
a45a3dca6 add option to clear columns in DataTable.clear
1b61a95c7 table tests
```

Then, if I do `git show 12d429dbd` I can scroll through the commit changes and eventually hit the part of the diff that shows that the file was deleted:

```diff
diff --git a/tests/test_table.py b/tests/test_table.py
deleted file mode 100644
index d57be5a60..000000000
--- a/tests/test_table.py
+++ /dev/null
```
