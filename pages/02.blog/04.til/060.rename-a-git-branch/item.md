Today I learned how to rename a git branch from the CLI.

===

## Rename a git branch

### How to rename a local git branch

To rename a _local_ git branch, you can use the command `git branch` with the option `-m`.

If you're in the branch you want to rename, just do

```bash
git branch -m new-name
```

If you are in another branch, you can do

```bash
git branch -m old-name new-name
```

### How to rename a remote git branch

If you want to rename a remote git branch, you can just delete the old branch and push the new one.

I also found the command

```bash
git push origin :old-name new-name
```

but I have never used this command and when I quickly scanned the documentation for `git push` I couldn't find out what the `:old-name new-name` really does.
(Comment below if you know!)


That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
