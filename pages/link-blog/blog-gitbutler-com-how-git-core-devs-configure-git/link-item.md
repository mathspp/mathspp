---
author: Scott Chacon
date: 03-03-2025 13:47
link: https://blog.gitbutler.com/how-git-core-devs-configure-git/
taxonomy:
    category: link
title: "How Core Git Developers Configure Git"
# via:
---

In this article, Scott goes over some git settings that many git core developers have set in their own git configuration files.
I won't go over all the settings that Scott suggests we set, but I will share some of the settings that I have adopted:

```toml
# ~/.gitconfig
[diff]
        algorithm = histogram
        colorMoved = plain
        mnemonicPrefix = true
        renames = true
[push]
        default = simple
        autoSetupRemote = true
        followTags = true
[fetch]
        prune = true
        pruneTags = true
        all = true
```

And out of these settings, the ones I find the most useful are the ones under `diff`.
`histogram` is a slightly slower diff algorithm that tends to yield better results when things are moved around, which I do a lot; setting `colorMoved = plain` will also use colours to represent things that were moved around, instead of showing up in red where they were moved from and green where they were moved to; `mnemonicPrefix` will use the letters `i`, `w`, and `c`, in diffs, to indicate where the diff is coming from (index, working directory, or commit, respectively); and `renames` will let you know if a file was renamed.

I recommend you go through the article linked to learn about some other settings and to adopt the ones you like.
