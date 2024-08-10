Today I learned how to create git aliases in my `.gitconfig` file.

===

# Git alias for quick commit & push

Today I learned (or was reminded, really) that you can create aliases for `git` commands.
For example, for this blog I often run these two commands in sequence:

```bash
git commit -m "Update"
git push
```

So, I realised I could set an alias, like `git cp`, to do this for me!
I first learned how to create git aliases from Adam Johnson's [“Boost Your Git DX”](https://gumroad.com/a/817193683/wlrcr), but the very short version of one way in which this can work is by modifying the section `[alias]` of your `.gitconfig` file.

You should place the file `.gitconfig` in your home directory (in case it isn't there yet) and then you can add this to its contents:

```
[alias]
	cp = !git commit -m "Update" && git push
	facp = !git add . && git cp
```

This makes it so that `git cp` is equivalent to running `git commit -m "Update" && git push` and `git facp` (fast add, commit, and push) is equivalent to running `git add .` followed by `git cp`.

By saving around three seconds every time I commit things on my blog, I expect these two aliases to save me a full minute by next month!
Maybe in a couple of years they will have saved me enough time to make up for the time I lost creating the aliases _and_ writing a blog article about them.

To conclude, the diagram below contains the information of this article in a diagram:

![A diagram showing that a section `alias` can be added to the git configuration file `.gitconfig` in order to create command aliases.](_diagram.webp)
