Today I learned how to automatically delete local git branches that have been merged already.

===


## Delete merged git branches

Today I ran the command `git branch` and I got scared when I saw the amount of results I got:

```txt
  codeql-on-code-change
  docs-improvements
  document-reactive-watch
  early-show-root
  escape-markup-in-md-headings
  fkaab/main
  gh-pages
  issue3637
* main
  opacity-component-classes
  tree-reload-preserve-state
  # ...
```

I had about 40 branches on me!
However, most of them had been merged already and I wanted to get rid of them.


### A ChatGPT solution

I was aware of two commands that I suspected could be used as the main ingredients:

 - `git branch --merged` lists branches that have been merged; and
 - `git branch -d` deletes a branch.

So, I opened ChatGPT and I asked it:

 > How can I combine the commands `git branch --merged` and `git branch -d` to delete all of the local branches that have been merged already?

ChatGPT complied to my request and gave me back this command:

```bash
git branch --merged | grep -v "\*" | xargs -n 1 git branch -d
```

It then proceeded to explain what each part does:

 1. `git branch --merged` lists all local branches that have been merged into the currently checked-out branch;
 2. `grep -v "\*"` excludes the currently checked-out branch from the list. The asterisk (*) is used to denote the currently checked-out branch in the `git branch` output; and
 3. `xargs -n 1 git branch -d` deletes each branch listed in the output using the `git branch -d` command. The `-n 1` option specifies that `xargs` should pass one branch at a time to the `git branch -d` command.

I made sure to be in my branch `main` and then I ran this command to delete more than 30 branches that I no longer cared about.

Then, [I asked on X](https://twitter.com/mathsppblog/status/1727283644493095309) if there were any issues with the approach that ChatGPT recommended.
I explicitly tagged [Adam Johnson, the author of “Boost Your Git DX”][boost-git-dx] and a contributor to git, and I got some interesting replies.


### A human solution

When I got feedback by more experienced users, they suggested a more robust version of the command.
They gave me essentially two equivalent versions, so I'll show you just one:

```bash
git switch main &&
git pull --prune &&
git branch --format '%(refname:short) %(upstream:track)' | awk '$2 == "[gone]" { print $1 }' | xargs -r git branch -D
```

What are we doing here?

 - `git switch main` makes sure we're in the branch `main`;
 - `git pull --prune` updates local information about branches that have been deleted in the remote repository (e.g., branches you've deleted after merging pull requests);
 - `git branch --format '%(refname:short) %(upstream:track)'` lists all local branches with their name and information about the upstream branch. If a branch has been deleted upstream, the part `%(upstream:track)` will look like `[gone]`;
 - `awk '$2 == "[gone]" { print $1 }'` uses `awk` to look for branches that show the output `[gone]` and prints the names of those branches; and
 - `xargs -r git branch -D` will take the names from the previous step and pass them to the command `git branch -D`.

I hope this made sense!
If not, check the references below or go ahead and read about this in [Adam's book “Boost Your Git DX”][boost-git-dx].

Finally, to top it all off, we'll create a `git` alias so that this is easy to run!
The command below will create the alias `sync`:

```bash
git config --global alias.sync '!git switch main && git pull --prune && git branch --format '\''%(refname:short) %(upstream:track)'\'' | awk '\''$2 == "[gone]" { print $1 }'\'' | xargs -r git branch -D'
```

This will save an alias in your `~/.gitconfig` file.
Now, you can run `git sync` and it will run the chain of commands above.


[boost-git-dx]: https://gumroad.com/a/817193683/wlrcr
