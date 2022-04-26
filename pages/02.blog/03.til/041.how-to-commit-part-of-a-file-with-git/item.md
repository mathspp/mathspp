Here is how you can commit only part of a file with git.

===


# How to commit only part of a file with git?

If you are using git, you are probably aware that the command `git add` takes a file name argument and then stages that file for committing.
If you use the command option `--patch`, then you can add only some of your changes for staging.

When you use the option `--patch` (or `-p`), git will go through all of your changes for that file and will try to split them into portions, called “hunks”.
Then, git starts an interactive session where it goes through each “hunk” and asks you if you want to commit said hunk or not.
It prompts you with this message:

```text
Stage this hunk [y,n,a,d,/,j,J,g,e,?]?
```

This is asking if the hunk that is being displayed is one of the hunks that you want to “`git add`”.
You can reply with `y` for “yes” or with `n` for “no”, but you have plenty of different options.

In the screenshot below, you can see an example of the interactive prompt and me replying with “no”,
because I didn't want to add that specific hunk at that point in time.

!["Screenshot of a terminal where it shows a small git diff in the terminal, with a basic code change, and then the interactive question from `git add -p` asking if I want to stage the current hunk. In front of the interactive question there is a single character 'n', that I typed, because I didn't want to add that hunk."](_example-hunk.png "Screenshot of the interactive prompt for `git add -p`.")

The options I use the most are:

 - `y` to add the current hunk;
 - `n` to not add the current hunk; and
 - `s` to split the current hunk even further.

But there are more options.


# "Stage this hunk" options

If you type `?` in front of the interactive staging prompt, you get a short description of each option:

```text
y - stage this hunk
n - do not stage this hunk
a - stage this and all the remaining hunks in the file
d - do not stage this hunk nor any of the remaining hunks in the file
g - select a hunk to go to
/ - search for a hunk matching the given regex
j - leave this hunk undecided, see next undecided hunk
J - leave this hunk undecided, see next hunk
k - leave this hunk undecided, see previous undecided hunk
K - leave this hunk undecided, see previous hunk
s - split the current hunk into smaller hunks
e - manually edit the current hunk
? - print help
```


# How to split a git hunk?

If git presents you with a hunk that is too big (i.e., that contains some changes that you want to add and other changes that you don't want to add), you can use the option `s` to split the current hunk into smaller hunks.
Like I said above, after the `y` and `n` options, the option `s` to split a hunk is the option that I use the most!
It's just too handy!


# Further reading

You can check the git documentation centre to read more about [interactive staging][git-interactive-staging].

[git-interactive-staging]: https://git-scm.com/book/en/v2/Git-Tools-Interactive-Staging

That's it for now! [Stay tuned][subscribe] and I'll see you around!

[subscribe]: /subscribe
